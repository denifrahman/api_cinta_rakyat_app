<?php

// extends class Model
class M_auth_M extends CI_Model
{

    // construct
    public function __construct()
    {
        parent::__construct();
        $this->menu_name = 'kebutuhan';
        $this->table_name = 'm_kebutuhan';
        $this->load->library('crypt');
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
    - $email : Berupa nama kolom ( String )
    Return :Object();
     */
    public function get_akun_by_email($email)
    {
        if ($email == '') {
            return $this->empty_response();
        } else {
            $data = $this->db->query("SELECT * from v_akun where akun_email = '$email'");
            $num_rows = $data->num_rows();

            //variabel meta berisi hasil status pengambilan
            $response['meta'] = array(
                "status_code" => 200,
                "success" => true,
            );

            //kondisi jika row lebih dari nol maka status message berhasil, jika tidak makan muncul total data 0
            if ($num_rows > 0) {
                $response['meta']['status_message'] = true;
            } else {
                $response['meta']['status_message'] = false;
            }

            //variabel data berisi hasil pengambilan data dari database
            $response['data'] = $data->row();

            //variabel response berupa array dari data dan meta
            return $response;
            // //variabel response berupa array dari data dan meta
            return $response;
        }
    }
}
