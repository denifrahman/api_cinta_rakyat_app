<?php

class Kecamatan extends BD_Controller
{

    // construct
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kecamatan_M');
        $this->load->library('crypt');
        // $this->auth();
    }

    /*
    Fungsi : mengambil semua data sesuai parameter
    Return : Array(Array())
     */
    public function get_all_post()
    {
        $list = $this->Kecamatan_M->get_all();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $customers) {
          $jenis_kelamin = '';
          // $btn = '';
          if ($customers->jenis_kelamin == 'L') {
            $jenis_kelamin = 'Laki-laki';
          } else if ($customers->jenis_kelamin == 'P') {
              $jenis_kelamin = 'Perempuan';
          }
            $btn = '<a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Edit" onclick="hapus(' . $customers->id . ')"><i class="flaticon-delete"></i></a><a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Edit" onclick="ubah(' . $customers->id . ')"><i class="la la-edit"></i></a>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $customers->nama_user_level;
            $row[] = $btn;
            $data[] = $row;
        }
  
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Kecamatan_M->count_all(),
                        "recordsFiltered" => $this->Kecamatan_M->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    public function get_by_id_get($id)
    {
        $response = $this->Kecamatan_M->get_by_id($id);
        $this->response($response);
    }
    public function get_all_data_by_id_get()
    {
        $id = $this->get('idKabKota');
    $response = $this->Kecamatan_M->get_all_data_by_id($id);
        $this->response($response);
    }
    public function get_all_data_get()
    {
    $response = $this->Kecamatan_M->get_all_data();
        $this->response($response);
    }
    public function update_index_get($id_siswa,$index)
    {
        $response = $this->Kecamatan_M->update_index($id_siswa,$index);
        $this->response($response);
    }
    public function add_post(){
        $dtusr = trim(file_get_contents('php://input'));
        $objx = json_decode(trim($dtusr), true);
        $response = $this->Kecamatan_M->add($objx);
        $this->response($response);
      }
    public function ubah_post()
    {
        $dtusr = trim(file_get_contents('php://input'));
        $objx = json_decode(trim($dtusr), true);
        $response = $this->Kecamatan_M->ubah($objx);
        $this->response($response);
    }
    public function delete_by_id_get($id){ 
        $response = $this->Kecamatan_M->delete_by_id($id);
        $this->response($response);
    }

    
}
