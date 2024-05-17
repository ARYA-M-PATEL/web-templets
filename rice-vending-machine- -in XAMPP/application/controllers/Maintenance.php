<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance extends Machine_Controller
{
    public function index()
	{
        if($this->input->is_ajax_request()) {
            $this->form_validation->set_rules($this->quantity);

            if ($this->form_validation->run() == FALSE) {
                responseMsg(false, '', validation_errors(), null, true);
            } else {
                $transaction = [
                    'quantity'      => $this->input->post('quantity'),
                    'v_id'          => $this->input->post('v_id'),
                    't_type'        => 'Fill up',
                    'created_date'  => date('Y-m-d'),
                    'created_time'  => date('H:i:s')
                ];

                $check = $this->main->transaction($transaction);

                $redirect = null;

                if($check === true) {
                    $redirect = base_url('maintenance');
                }

                responseMsg($check, "Quantity updated", "Quantity not updated", $redirect);
            }
        } else {
            $data['title'] = 'Maintenance';
            $data['name'] = 'maintenance';
            $data['varieties'] = $this->main->getAll('varieties', '*', []);

            return $this->template->load('template', 'maintenance', $data);
        }
	}

    public function qty_check($quantity)
    {
        $quantity = $quantity ? $quantity : 0;

        $variety = $this->main->get("varieties", 'avail_qty', ['id' => $this->input->post('v_id')]);

        if(!$variety) {
            $this->form_validation->set_message('qty_check', '%s does not exists');
            return FALSE;
        } 

        $quantity += $variety['avail_qty'];

        if($quantity > 200) {
            $this->form_validation->set_message('qty_check', "Max 200 %s allowed.");
            return FALSE;
        }

        return TRUE;
    }

    protected $quantity = [
        [
            'field' => 'quantity',
            'label' => 'Quantity',
            'rules' => 'required|is_natural_no_zero|callback_qty_check|trim',
            'errors' => [
                'required' => "%s is required",
                'is_natural_no_zero' => "%s must be greater than 0"
            ],
        ]
    ];
}