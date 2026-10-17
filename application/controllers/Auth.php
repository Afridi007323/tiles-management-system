
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    // Public portfolio demo credentials.
    // Production application mein hardcoded credentials use na karein.
    private $demo_username = 'demo';
    private $demo_password = 'TileDemo@2026';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
            return;
        }

        $data['error'] = $this->session->flashdata('login_error');

        $this->load->view('auth/login', $data);
    }

    public function login()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('Auth');
            return;
        }

        $username = trim((string) $this->input->post('username', TRUE));
        $password = (string) $this->input->post('password');

        if (
            hash_equals($this->demo_username, $username) &&
            hash_equals($this->demo_password, $password)
        ) {
            $this->session->sess_regenerate(TRUE);

            $this->session->set_userdata(array(
                'logged_in' => TRUE,
                'username'  => $this->demo_username,
                'role'      => 'demo'
            ));

            redirect('ManagementController/dashboard');
            return;
        }

        $this->session->set_flashdata(
            'login_error',
            'Incorrect username or password. Please try again.'
        );

        redirect('Auth');
    }

    public function logout()
    {
        $this->session->sess_destroy();

        redirect('Auth');
    }
}
