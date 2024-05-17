<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Machine_Controller extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();

		$this->machine = $this->main->get("machine", '*', []);

		if (!$this->machine) {
			return redirect('login');
        }
	}
}