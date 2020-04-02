<?php

class Dashboard extends BD_Controller{

  // construct
  public function __construct(){
    parent::__construct();
    $this->load->model('Dashboard_M');
  }

  /*
    Fungsi : mengambil semua data sesuai parameter
    Return : Array(Array())
  */
  public function get_odp_per_bulan_get(){
      $response = $this->Dashboard_M->get_odp_per_bulan();
      $this->response($response);
  }
  public function get_pdp_per_bulan_get(){
      $response = $this->Dashboard_M->get_pdp_per_bulan();
      $this->response($response);
  }
  public function get_pie_get(){
      $response = $this->Dashboard_M->get_pie();
      $this->response($response);
  }
  public function get_per_kota_get(){
      $response = $this->Dashboard_M->get_per_kota();
      $this->response($response);
  }

}

?>
