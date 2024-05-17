<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends Machine_Controller
{
    public function index()
	{
		$data['title'] = 'Error';
        $data['name'] = 'error';
        $message = $this->main->get('error_pages', 'message, name', ['name' => $this->uri->segment(2)]);
        $data['message'] = !empty($message['message']) ? $message['message'] : '';

        return $this->template->load('template', 'errors', $data);
	}
}