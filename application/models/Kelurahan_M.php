<?php

// extends class Model
class Kelurahan_M extends CI_Model
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
    - $param : Berupa nama kolom ( String )
    - $id : Berupa value dari kolom ( String )
    - $limit : Berupa limitasi untuk row yang diambil ( int )
    Return : Array(Array())
     */
    public function add($objx)
    {
        if (empty($objx)) {
            return $this->empty_response();
        } else {
            $this->db->trans_begin();
            $this->db->insert('m_akun', $objx);
            $str = $this->db->last_query();
            
            if ($this->db->trans_status() !== false) {
                //variabel meta berisi hasil status pengambilan
                $this->db->trans_commit();
                $response['meta'] = array(
                    "status_code" => 200,
                    "status_message" => 'Data Berhasil Ditambahkan',
                    "success" => true,
                );
            } else {
                $this->db->trans_rollback();
                $response['meta'] = array(
                    "status_code" => 500,
                    "status_message" => 'Data Gagal Ditambahkan' . $str,
                    "success" => false,
                );
            }
            return $response;
        }
    }
    public function ubah($objx)
    {
        //Mendefinikan variabel
        $response = array();
        $this->db->where('t_anggota_keluarga_id',$objx['t_anggota_keluarga_id']);
        $data = $this->db->update("t_anggota_keluarga", $objx);
        $str = $this->db->last_query();
        if ($this->db->trans_status() !== false) {
            $response['meta'] = array(
                "status_code" => 200,
                "status_message" => 'Data Berhasil Ditambahkan' . $str,
                "success" => true,
            );
        } else {
            $this->db->trans_rollback();

            $response['meta'] = array(
                "status_code" => 500,
                "status_message" => 'Data Gagal Ditambahkan',
                "success" => false,
            );
        }
        return $response;
    }
    public function get_all_data_by_id($id)
    {
        //Mendefinikan variabel
        $response = array();
        $data = $this->db->query("select * from m_ikelurahan where id_kecamatan =  '$id' order by nama_kelurahan ");
        $num_rows = $data->num_rows();

        //variabel meta berisi hasil status pengambilan
        $response['meta'] = array(
            "status_code" => 200,
            "success" => true,
        );
        if ($num_rows > 0) {
            $response['meta']['status_message'] = 'Pengambilan ' . $num_rows . ' Data ' . $this->menu_name . ' Berhasil';
        } else {
            $response['meta']['status_message'] = 'Total Data 0';
        }

        $response['data'] = $data->result();

        return $response;
    }
    public function get_all_data()
    {
        //Mendefinikan variabel
        $response = array();
        $data = $this->db->query("select * from m_iKelurahan order by nama_Kelurahan");
        $num_rows = $data->num_rows();

        //variabel meta berisi hasil status pengambilan
        $response['meta'] = array(
            "status_code" => 200,
            "success" => true,
        );
        if ($num_rows > 0) {
            $response['meta']['status_message'] = 'Pengambilan ' . $num_rows . ' Data ' . $this->menu_name . ' Berhasil';
        } else {
            $response['meta']['status_message'] = 'Total Data 0';
        }

        $response['data'] = $data->result();

        return $response;
    }
}
