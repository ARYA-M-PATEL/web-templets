<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Fawno\Modbus\ModbusRTU;
use Fawno\PhpSerial\SerialConfig;

class Plc extends Machine_Controller
{
    public function index()
	{
		if($this->input->is_ajax_request()) {
			$cmd = 'wmic path Win32_PnPEntity where "Caption LIKE \'USB%\'" get Caption,DeviceID';
			exec($cmd, $output, $returnCode);

			if ($returnCode === 0 && !empty($output)) {
				$comPort = $this->extractComPort($output);
				if ($comPort) {
					// Configure port and open it
					$serialConfig = new SerialConfig;

					$serialConfig->setBaudRate(19200);
					$serialConfig->setParity(2); // even
					$serialConfig->setDataBits(8);
					$serialConfig->setStopBits(1);
					$serialConfig->setFlowControl(false);

					try {
						$master = new ModbusRTU($comPort, $serialConfig);
						$master->open();

						if ($this->input->server('REQUEST_METHOD') === 'POST') {
							$startRegister = $this->input->post('startRegister');
							$quantity = !empty($this->input->post('values')) ? count($this->input->post('values')) : 0;
							$values = !empty($this->input->post('values')) ? $this->input->post('values') : [];
							$master->writeMultipleRegisters(1, $startRegister, $quantity, ...$values); // Write data to plc
						}

						$data = $master->readHoldingRegisters(1, 4196, 11);

						if(!empty($data['registers'])) {
							$response = [
								'error' 	=> false,
								'message' 	=> "Data found from PLC.",
								'data' 		=> $data['registers']
							];
						} else {
							$response = [
								'error' 	=> true,
								'message' 	=> "No data found from PLC.",
								'data' 		=> []
							];
						}
					} catch (\Throwable $th) {
						$response = [
							'error' 	=> true,
							'message' 	=> "Error in communication with PLC.",
							'data' 		=> []
						];
					}
				} else {
					$response = [
						'error' 	=> true,
						'message' 	=> "No PLC found connected to the system.",
						'data' 		=> []
					];
				}
			} else {
				$response = [
					'error' 	=> true,
					'message' 	=> "No usb found connected to the system.",
					'data' 		=> []
				];
			}

			die(json_encode($response));
		} else {
			return $this->error_404();
		}
	}

    public function restart()
	{
        exec('shutdown /r /f /t 0', $restart);
	}

	private function extractComPort($deviceInfo) {
		foreach ($deviceInfo as $device) {
			// Use regular expression to extract COM port number
			preg_match('/COM(\d+)/i', $device, $matches);

			// Check if a match was found
			if (strpos($device, 'USB Serial Port') !== false && isset($matches[1])) {
				return 'COM' . $matches[1];
			}
		}

		return null;
	}
}