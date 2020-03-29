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
  public function getSaldoById_get($id_guru){
      $response = $this->Dashboard_M->get_SaldoById($id_guru);
      $this->response($response);
  }
  public function getMurid_get($id_guru){
      $response = $this->Dashboard_M->get_murid($id_guru);
      $this->response($response);
  }
  public function getPertemuan_get($id_guru){
      $response = $this->Dashboard_M->get_pertemuan($id_guru);
      $this->response($response);
  }
  public function getPaketAktif_get($id_pelanggan){
      $response = $this->Dashboard_M->get_aktif_paket($id_pelanggan);
      $this->response($response);
  }


}

?>