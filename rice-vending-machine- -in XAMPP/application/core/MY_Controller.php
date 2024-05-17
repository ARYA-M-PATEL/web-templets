<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class MY_Controller extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Main_modal', 'main');
		
		$this->api_url = $this->main->get("site_settings", 's_value', ['s_name' => 'api-url']);
		$this->api_url = !empty($this->api_url['s_value']) ? $this->api_url['s_value'] : '';
		
        $this->video_url = $this->config->item('videos');
        $this->images_url = $this->config->item('images');
	}

	public function error_404()
	{
		$data['title'] = 'This Page Can’t Be Found';
        $data['name'] = 'error_404';
        $data['message'] = 'No webpage was found for the web address: <br /><span class="text-primary">'.current_url().'</span>';

		return $this->template->load('template', 'errors', $data);
	}
}