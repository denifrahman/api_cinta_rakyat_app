<?php

class Dashboard_siswa extends BD_Controller{

  // construct
  public function __construct(){
    parent::__construct();
    $this->load->model('Dashboard_siswa_M');
  }

  /*
    Fungsi : mengambil semua data sesuai parameter
    Return : Array(Array())
  */
  public function get_all_info_akademik_get(){
      $response = $this->Dashboard_siswa_M->get_all_info_akademik();
      $this->response($response);
  }
  public function get_catatan_by_siswa_get($id_siswa){
      $response = $this->Dashboard_siswa_M->get_catatan_by_siswa($id_siswa);
      $this->response($response);
  }

}

?>
