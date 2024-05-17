<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->video_url = $this->config->item('videos');
        $this->images_url = $this->config->item('images');
    }

    public function index()
	{
        if($this->input->is_ajax_request()) {
            $this->form_validation->set_rules($this->login);

            if ($this->form_validation->run() == FALSE) {
                responseMsg(false, '', validation_errors(), null, true);
            } else {
                $data = send_curl_request('login', 'post', [
                    'serial_number' => $this->input->post('serial_number')
                ]);

                $redirect = null;

                if($data['status'] === true) {
                    $redirect = base_url();
                    $this->main->login($data['row']);
                }

                responseMsg($data['status'], $data['message'], $data['message'], $redirect);
            }
        } else {
            $data['title'] = 'Login';
            $data['name'] = 'login';
    
            return $this->template->load('template', 'auth/login', $data);
        }
	}

    protected $login = [
        [
            'field' => 'serial_number',
            'label' => 'Serial number',
            'rules' => 'required|max_length[100]|trim',
            'errors' => [
                'required' => "%s is required",
                'max_length' => "Max 100 chars allowed"
            ],
        ]
    ];
}