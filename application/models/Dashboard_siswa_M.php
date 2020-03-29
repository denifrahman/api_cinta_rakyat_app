<?php

// extends class Model
class Dashboard_siswa_M extends CI_Model
{

    // construct
    public function __construct()
    {
        parent::__construct();
        $this->menu_name = 'kebutuhan';
        $this->table_name = 'm_kebutuhan';

    }

    /*
    Desc : Respon untuk data field yang kosong
     */
    public function empty_response()
    {
        //variabel meta berisi hasil status pengambilan
        $response['meta'] = array(
            "status_code" => 500,
            "status_message" => 'Field tidak boleh kosong',
            "success" => false,
        );
        return $response;
    }

    /*
    Fungsi : mengambil data sesuai parameter
    Parameter :
    - $param : Berupa nama kolom ( String )
    - $id : Berupa value dari kolom ( String )
    - $limit : Berupa limitasi untuk row yang diambil ( int )
    Return : Array(Array())
     */
    public function get_all_info_akademik()
    {

        //Mendefinikan variabel
        $response = array();

      
        $data = $this->db->query("SELECT * from t_info_akademik where is_aktif = '1' order by id DESC");
        $num_rows = $data->num_rows();

        //variabel meta berisi hasil status pengambilan
        $response['meta'] = array(
            "status_code" => 200,
            "success" => true,
        );

        //kondisi jika row lebih dari nol maka status message berhasil, jika tidak makan muncul total data 0
        if ($num_rows > 0) {
            $response['meta']['status_message'] = 'Pengambilan ' . $num_rows . ' Data ' . $this->menu_name . ' Berhasil';
        } else {
            $response['meta']['status_message'] = 'Total Data 0';
        }

        //variabel data berisi hasil pengambilan data dari database
        $response['data'] = $data->result();

        //variabel response berupa array dari data dan meta
        return $response;

    }
     public function get_catatan_by_siswa($id_siswa)
    {

        //Mendefinikan variabel
        $response = array();

      
        $data = $this->db->query("SELECT * from t_catatan_siswa where id_siswa = '$id_siswa' order by time_insert");
        $num_rows = $data->num_rows();

        //variabel meta berisi hasil status pengambilan
        $response['meta'] = array(
            "status_code" => 200,
            "success" => true,
        );

        //kondisi jika row lebih dari nol maka status message berhasil, jika tidak makan muncul total data 0
        if ($num_rows > 0) {
            $response['meta']['status_message'] = 'Pengambilan ' . $num_rows . ' Data ' . $this->menu_name . ' Berhasil';
        } else {
            $response['meta']['status_message'] = 'Total Data 0';
        }

        //variabel data berisi hasil pengambilan data dari database
        $response['data'] = $data->result();

        //variabel response berupa array dari data dan meta
        return $response;

    }


}
