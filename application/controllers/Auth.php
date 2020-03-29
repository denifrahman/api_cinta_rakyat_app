<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use \Firebase\JWT\JWT;

class Auth extends BD_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_auth_M');
        $this->load->library('crypt'); 
    }

    public function index_get(){
      echo ('Api auth');
    }
    public function get_akun_by_email_get(){
      $email = $this->get('email');
      $response = $this->M_auth_M->get_akun_by_email($email);
      $this->response($response);
    }


}
