<?php

// extends class Model
class Dashboard_M extends CI_Model
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
    public function get_odp_per_bulan()
    {

        //Mendefinikan variabel
        $response = array();

      
        $dataSehat = $this->db->query("SELECT count(t_anggota_keluarga_id) as value, date(time_insert)as label FROM t_anggota_keluarga where t_anggota_keluarga_status = 'SEHAT' GROUP BY label");
        $dataODP = $this->db->query("SELECT count(t_anggota_keluarga_id) as value, date(time_insert)as label FROM t_anggota_keluarga where t_anggota_keluarga_status = 'ODP' GROUP BY label");
        $dataPDP = $this->db->query("SELECT count(t_anggota_keluarga_id) as value, date(time_insert)as label FROM t_anggota_keluarga where t_anggota_keluarga_status = 'PDP' GROUP BY label");
        $dataPositif = $this->db->query("SELECT count(t_anggota_keluarga_id) as value, date(time_insert)as label FROM t_anggota_keluarga where t_anggota_keluarga_status = 'POSITIF' GROUP BY label");
        $num_rows = $dataSehat->num_rows();

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
        $response['data_sehat'] = $dataSehat->result();
        $response['data_odp'] = $dataODP->result() == [] ? '{value:0,label:2020-03-17}': $dataODP->result() ;
        $response['data_pdp'] = $dataPDP->result() == [] ? '{value:0,label:2020-03-17}':$dataPDP->result();
        $response['data_positif'] = $dataPositif->result()== [] ? '{value:0,label:2020-03-17}':$dataPositif->result();

        //variabel response berupa array dari data dan meta
        return $response;

    }
    public function get_pdp_per_bulan()
    {

        //Mendefinikan variabel
        $response = array();

      
        
        $dataPDP = $this->db->query("SELECT count(t_anggota_keluarga_id) as value, date(time_insert)as label FROM t_anggota_keluarga where t_anggota_keluarga_status = 'ODP' GROUP BY label");
        $num_rows = $dataPDP->num_rows();

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
        $response['data_pdp'] = $dataPDP->result();

        //variabel response berupa array dari data dan meta
        return $response;

    }
    public function get_pie()
    {

        //Mendefinikan variabel
        $response = array();

      
        $dataAll = $this->db->query("SELECT COUNT(t_anggota_keluarga_id)as jumlah from t_anggota_keluarga");
        $dataSehat = $this->db->query("SELECT COUNT(t_anggota_keluarga_id)as jumlah from t_anggota_keluarga where t_anggota_keluarga_status = 'SEHAT' ")->row();
        $dataPositif = $this->db->query("SELECT COUNT(t_anggota_keluarga_id)as jumlah from t_anggota_keluarga where t_anggota_keluarga_status = 'POSITIF' ")->row();
        $dataOdp = $this->db->query("SELECT COUNT(t_anggota_keluarga_id)as jumlah from t_anggota_keluarga where t_anggota_keluarga_status = 'ODP' ")->row();
        $dataPdp = $this->db->query("SELECT COUNT(t_anggota_keluarga_id)as jumlah from t_anggota_keluarga where t_anggota_keluarga_status = 'PDP' ")->row();
        $total = $dataAll->row();
        $persentase_Sehat = $dataSehat->jumlah / $total->jumlah * 100;
        $persentase_Odp = $dataOdp->jumlah /  $total->jumlah * 100;
        $persentase_Pdp = $dataPdp->jumlah / $total->jumlah * 100;
        $persentase_Positif = $dataPositif->jumlah / $total->jumlah * 100;
        
        $num_rows = $dataAll->num_rows();

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
        $response['persentase_odp'] = round($persentase_Odp);
        $response['persentase_pdp'] = round($persentase_Pdp);
        $response['persentase_sehat'] = round($persentase_Sehat);
        $response['persentase_positif'] = round($persentase_Positif);
        $response['total'] = $total->jumlah;
        $response['total_sehat'] = $dataSehat->jumlah;
        $response['total_odp'] = $dataOdp->jumlah;
        $response['total_pdp'] = $dataPdp->jumlah;
        $response['total_positif'] = $dataPositif->jumlah;

        //variabel response berupa array dari data dan meta
        return $response;

    }
     public function get_murid($id_guru)
    {

        //Mendefinikan variabel
        $response = array();

      
        $data = $this->db->query("SELECT COUNT(id_pelanggan) as murid from t_mengajar WHERE id_guru = $id_guru and id_status = 1");
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
        $response['data'] = $data->row();

        //variabel response berupa array dari data dan meta
        return $response;

    }
     public function get_aktif_paket($id_pelanggan)
    {

        //Mendefinikan variabel
        $response = array();

      
        $data = $this->db->query("SELECT t_detail_pembelian_paket.id, t_detail_pembelian_paket.expired, t_detail_pembelian_paket.time_ins, m_paket.nama_paket 
        FROM t_detail_pembelian_paket INNER JOIN m_paket on m_paket.id =  t_detail_pembelian_paket.id_paket 
        where t_detail_pembelian_paket.id_pelanggan = '$id_pelanggan'
        ORDER BY t_detail_pembelian_paket.id DESC");
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
        $response['data'] = $data->row();

        //variabel response berupa array dari data dan meta
        return $response;

    }
public function get_pertemuan($id_guru)
    {

        //Mendefinikan variabel
        $response = array();

      
        $data = $this->db->query("SELECT COUNT(nama_pelajaran) as pertemuan from t_mengajar_detail WHERE id_guru = $id_guru");
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
        $response['data'] = $data->row();

        //variabel response berupa array dari data dan meta
        return $response;

    }
  


}
