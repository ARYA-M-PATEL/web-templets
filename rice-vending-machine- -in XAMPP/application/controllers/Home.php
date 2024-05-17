<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Home extends Machine_Controller
{
    public function index()
	{
		$data['title'] = 'Home';
        $data['name'] = 'home';

        return $this->template->load('template', 'home', $data);
	}
    
    public function varieties()
	{
		$data['title'] = 'Varieties';
        $data['name'] = 'varieties';
        $data['varieties'] = $this->main->getAll('varieties', '*', ['avail_qty > ' => 0]);
        
        return $this->template->load('template', 'pages/varieties', $data);
	}

    public function shop(Int $id)
	{
        $data['variety'] = $this->main->get("varieties", 'v_name, avail_qty', ['id' => $id, 'avail_qty > ' => 0]);
        if(!$data['variety']) return redirect('');

		$data['title'] = strtoupper($data['variety']['v_name']);
        $data['name'] = 'shop';
        $data['quantities'] = $this->main->getAll('quantities', '*', ['v_id' => $id, 'unit_value <=' => $data['variety']['avail_qty']]);

        return $this->template->load('template', 'pages/shop', $data);
	}

    public function summary(Int $v_id, Int $q_id)
	{
        $data = $this->checkQtys($v_id, $q_id);

		$data['title'] = strtoupper($data['variety']['v_name']);
        $data['name'] = 'summary';

        return $this->template->load('template', 'pages/summary', $data);
	}

    public function print(Int $v_id, Int $q_id)
	{
        $data = $this->checkQtys($v_id, $q_id);
        $printCheck = $this->printCoupon($data['variety'], $data['quantity']);

        if(!$printCheck['status']) {
            $data['title'] = 'Error';
            $data['name'] = 'error';
            $data['message'] = 'Print not Successful';
            $data['disableThankyou'] = $printCheck['message'];

            return $this->template->load('template', 'pages/thankyou', $data);
        } else {
            $data['title'] = 'Thankyou';
            $data['name'] = 'thankyou';
            $data['message'] = 'Print Successful';

            return $this->template->load('template', 'pages/thankyou', $data);
        }
	}

    public function scan(Int $v_id, Int $q_id)
	{
        $data = $this->checkQtys($v_id, $q_id);

		$data['title'] = "Payment";
        $data['name'] = 'payment';

        $this->load->helper('paytm');

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");

        $checkSum = "";
        $paramList = array();

        $ORDER_ID = $this->machine['serial_number'].rand(10000, 99999999);
        $CUST_ID = $this->machine['serial_number'];
        $INDUSTRY_TYPE_ID = 'Retail';
        $CHANNEL_ID = "WEB";
        $TXN_AMOUNT = $data['quantity']['price'];

        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = PAYTM_MERCHANT_MID;
        $paramList["ORDER_ID"] = $ORDER_ID;
        $paramList["CUST_ID"] = $CUST_ID;
        $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
        $paramList["CHANNEL_ID"] = $CHANNEL_ID;
        $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
        $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
        $paramList["CALLBACK_URL"] = base_url("thankyou?variety={$v_id}&quantity={$q_id}");

        //Here checksum string will return by getChecksumFromArray() function.
        $data['checkSum'] = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);

        $data['paramList'] = $paramList;
        return $this->template->load('template', 'pages/scan', $data);
	}

    public function thankyou()
	{
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");

        $v_id = $this->input->get('variety');
        $q_id = $this->input->get('quantity');

        $data = $this->checkQtys($v_id, $q_id);        

        $this->load->helper('paytm');

        $paytmChecksum = "";
        $isValidChecksum = "FALSE";

        $paramList = $this->input->post();
        $paytmChecksum = isset($paramList["CHECKSUMHASH"]) ? $paramList["CHECKSUMHASH"] : ""; //Sent by Paytm pg
        $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

        if($isValidChecksum === "TRUE") {
            if ($this->input->post("STATUS") === "TXN_SUCCESS") {
                $data['message'] = 'Payment Successful';
            } else {
                $data['message'] = 'Payment Not Successful';
                $data['disableThankyou'] = $paramList['RESPMSG'];
            }
        } else {
            $data['message'] = 'Payment Not Successful';
            $data['disableThankyou'] = $paramList['RESPMSG'];
        }

        $data['title'] = 'Thankyou';
        $data['name'] = 'thankyou';
        
        return $this->template->load('template', 'pages/thankyou', $data);
	}

    public function filling(Int $v_id, Int $q_id)
	{
        $data = $this->checkQtys($v_id, $q_id);

		$data['title'] = 'Filling';
        $data['name'] = 'filling';

        $transaction = [
            'v_id'          => $v_id,
            'quantity'      => $data['quantity']['unit_value'],
            't_type'        => 'Fill out',
            'created_date'  => date('Y-m-d'),
            'created_time'  => date('H:i:s')
        ];

        $this->main->transaction($transaction);

        return $this->template->load('template', 'pages/filling', $data);
	}

    private function printCoupon(Array $variety, Array $quantity)
    {
        $command = 'wmic path Win32_PnPEntity where "Caption like \'%USB%\'" get Caption';
        exec($command, $usbs);

        if(!in_array('USB Printing Support', $usbs)) {
            return [
                'status'  => false,
                'message' => "Printer is not able to print coupons at this moment.",
            ];
        } else {

            $command = 'wmic path Win32_Printer where "PortName like \'%USB%\'" get Name, PortName, DriverName';
            exec($command, $pendingPrints);

            // Display the output as an array
            re($pendingPrints);

            require_once __DIR__ . '/../libraries/Item.php';
    
            $printerName = $this->main->get("site_settings", 's_value', ['s_name' => 'printer']);
            $printerName = !empty($printerName['s_value']) ? $printerName['s_value'] : "KPOS_80 Printer";
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);
    
            $items = [
                new item($variety['v_name'].' * '. $quantity['unit_value'] . $quantity['unit_id'], $quantity['price'])
            ];
    
            $subtotal = new item('Subtotal', $quantity['price']);
            $total = new item('Total', $quantity['price'], $quantity['currency']);
            $date = date('l jS \of F Y h:i:s A');
    
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text("{$this->machine['serial_number']}\n");
            $printer -> selectPrintMode();
            $printer -> text("{$this->machine['area']}\n");
            $printer -> feed();
    
            /* Title of receipt */
            $printer -> setEmphasis(true);
            $printer -> text("SALES INVOICE\n");
            $printer -> setEmphasis(false);
    
            /* Items */
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer -> setEmphasis(true);
            $printer -> text(new item('', $quantity['currency']));
            $printer -> setEmphasis(false);
            foreach ($items as $item) {
                $printer -> text($item);
            }
            $printer -> setEmphasis(true);
            $printer -> text($subtotal);
            $printer -> setEmphasis(false);
            $printer -> feed();
    
            /* Total */
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text($total);
            $printer -> selectPrintMode();
    
            /* Footer */
            $printer -> feed(2);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("Thank you for shopping at ExampleMart\n");
            $printer -> text("For trading hours, please visit example.com\n");
            $printer -> feed(2);
            $printer -> text($date . "\n");
    
            /* Cut the receipt and open the cash drawer */
            $printer -> cut();
            $printer -> pulse();
    
            $printer -> close();
    
            return [
                'status'  => true,
                'message' => "Printer is not able to print coupons at this moment.",
            ];
        }
    }

    private function checkQtys(Int $v_id, Int $q_id)
    {
        $data['quantity'] = $this->main->get("quantities", '*', ['id' => $q_id]);
        if(!$data['quantity']) return redirect('');

        $data['variety'] = $this->main->get("varieties", 'v_name, description, image, container', ['id' => $v_id, 'avail_qty >=' => $data['quantity']['unit_value']]);
        if(!$data['variety']) return redirect('');

        return $data;
    }

    public function sync_report()
    {
        $this->main->sync_report();
    }
}