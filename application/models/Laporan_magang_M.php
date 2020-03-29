<?php

// extends class Model
class Laporan_magang_M extends CI_Model
{
    public $table = 'file_laporan_magang';
    public $column_order = array( 'nama_file'); //set column field database for datatable orderable
    public $column_search = array('nama_file'); //set column field database for datatable searchable
    public $order = array('id' => 'asc'); // defau
    // construct
    public function __construct()
    {
        parent::__construct();
        $this->menu_name = 'master';
        $this->table_name = 'file';
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
    private function _get_datatables_query($id_siswa,$id_role)
    {
        if($id_role != 3){
            $this->db->from($this->table);
        }else{
            $this->db->where('user_insert',$id_siswa);
            $this->db->from($this->table);
        }
        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                {
                    $this->db->group_end();
                }
                //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    public function get_all($id_siswa,$id_role)
    {
        $this->_get_datatables_query($id_siswa,$id_role);
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        // print_r($this->_get_datatables_query());
        // echo $query;
        return $query->result();
    }

    public function count_filtered($id_siswa,$id_role)
    {
        $this->_get_datatables_query($id_siswa,$id_role);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($id_siswa,$id_role)
    {
        if($id_role != 3){
            $this->db->from($this->table);
        }else{
            $this->db->where('user_insert',$id_siswa);
            $this->db->from($this->table);
        }
        return $this->db->count_all_results();
    }
    public function get_all_data()
    {

        //Mendefinikan variabel
        $response = array();

        $data = $this->db->query("select * from file_laporan_magang");
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
    public function get_all_by_nama($nama)
    {

        //Mendefinikan variabel
        $response = array();

        $data = $this->db->query("select * from file_laporan_magang where filter ='$nama'");
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
    public function get_by_id($id)
    {

        //Mendefinikan variabel
        $response = array();

        $data = $this->db->query("select * from file_laporan_magang where id = '$id' ");
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
    
    public function add($data)
    {
        //Mendefinikan variabel
        $response = array();
        $data = $this->db->insert('file_laporan_magang', $data);
        $str = $this->db->last_query();
        if ($this->db->trans_status() !== false) {
            $response['meta'] = array(
                "status_code" => 200,
                "status_message" => 'Data Berhasil Ditambahkan'.$str,
                "success" => true,
            );
        } else {
            $this->db->trans_rollback();

            $response['meta'] = array(
                "status_code" => 500,
                "status_message" => 'Data Gagal Ditambahkan'.$str,
                "success" => false,
            );
        }
        return $response;

    }
    public function ubah($data)
    {
        //Mendefinikan variabel
        $response = array();
        $this->db->where('id', $data['id']);
        $data = $this->db->update('file_laporan_magang', $data);
        $str = $this->db->last_query();
        if ($this->db->trans_status() !== false) {
            $response['meta'] = array(
                "status_code" => 200,
                "status_message" => 'Data Berhasil Ditambahkan'.$str,
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
    public function delete_by_id($id)
    {
        //Mendefinikan variabel
        $response = array();
        $data = $this->db->query("delete from file_laporan_magang where id = '$id'");
        $str = $this->db->last_query();
        if ($this->db->trans_status() !== false) {
            $response['meta'] = array(
                "status_code" => 200,
                "status_message" => 'Data Berhasil Di hapus'.$str,
                "success" => true,
            );
        } else {
            $this->db->trans_rollback();

            $response['meta'] = array(
                "status_code" => 500,
                "status_message" => 'Data Gagal di hapus',
                "success" => false,
            );
        }
        return $response;

    }

}
