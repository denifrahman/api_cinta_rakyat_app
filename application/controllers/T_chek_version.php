<?php

class T_chek_version extends BD_Controller
{

    // construct
    public function __construct()
    {
        parent::__construct();
        $this->load->model('T_chek_version_M');
        // $this->auth();
    }
    public function getNewVersion_get()
    {
        $response = $this->T_chek_version_M->get_newVersion();
        $this->response($response);
    }
}
