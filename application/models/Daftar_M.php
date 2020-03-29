<?php

// extends class Model
class Daftar_M extends CI_Model
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
        $this->db->where('akun_email',$objx['akun_email']);
        $data = $this->db->update("m_akun", $objx);
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
    public function update_index($id_siswa, $index)
    {
        //Mendefinikan variabel
        $response = array();
        $data = $this->db->query("update m_siswa set `index` = '$index' where `id` = '$id_siswa';");
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
}
