<?php

// extends class Model
class Chat_M extends CI_Model
{
    public $table = 'Chat';
    public $column_order = array( 'nama_kelas'); //set column field database for datatable orderable
    public $column_search = array('nama_kelas'); //set column field database for datatable searchable
    public $order = array('id' => 'asc'); // defau
    // construct
    public function __construct()
    {
        parent::__construct();
        $this->menu_name = 'master';
        $this->table_name = 'kelas';
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
    public function get_last_chat_by_id_siswa($id)
    {

        //Mendefinikan variabel
        $response = array();

        // $data = $this->db->query("SELECT chat.* FROM chat, (SELECT MAX(id) as lastid
        // FROM chat
        // WHERE (chat.to_id= '$id' OR chat.from_id = '$id')

        // GROUP BY CONCAT(LEAST(chat.to_id,chat.from_id),'.',
        // GREATEST(chat.to_id, chat.from_id))) as conversations
        // WHERE id = conversations.lastid
        // ORDER BY chat.time_insert DESC");
        $data = $this->db->query("SELECT chat.*, m_siswa.nama from chat 
        INNER JOIN m_siswa on m_siswa.id = chat.to_id OR m_siswa.id = chat.from_id 
        WHERE (to_id = '$id' OR from_id = '$id') GROUP BY m_siswa.nama ORDER BY time_insert desc ");
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
    public function get_last_chat_by_id_user($id)
    {

        //Mendefinikan variabel
        $response = array();

        // $data = $this->db->query("SELECT chat.* FROM chat, (SELECT MAX(id) as lastid
        // FROM chat
        // WHERE (chat.to_id= '$id' OR chat.from_id = '$id')

        // GROUP BY CONCAT(LEAST(chat.to_id,chat.from_id),'.',
        // GREATEST(chat.to_id, chat.from_id))) as conversations
        // WHERE id = conversations.lastid
        // ORDER BY chat.time_insert DESC");
        $data = $this->db->query("SELECT chat.*, m_user.nama from chat 
        INNER JOIN m_user on m_user.id = chat.to_id OR m_user.id = chat.from_id 
        WHERE (to_id = '$id' OR from_id = '$id') GROUP BY m_user.nama ORDER BY time_insert desc ");
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
    public function get_count_unread_siswa($to_id,$from_id)
    {

        //Mendefinikan variabel
        $response = array();

        $data = $this->db->query("SELECT count(id) as unread, from_id, to_id from chat where from_id = '$from_id' AND to_id = '$to_id' AND to_id_status = 'UNREAD' ");
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
    public function get_count_unread_user($from_id,$to_id)
    {

        //Mendefinikan variabel
        $response = array();

        $data = $this->db->query("SELECT count(id) as unread, from_id, to_id from chat where from_id = '1' AND to_id = '53' AND to_id_status = 'UNREAD' ");
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
    public function get_all_percakapan($to_id, $from_id)
    {

        //Mendefinikan variabel
        $response = array();

        $data = $this->db->query("SELECT * from chat WHERE from_id = '$to_id' AND to_id = '$from_id' OR from_id = '$from_id' AND to_id = '$to_id' ORDER BY time_insert asc");
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

        $data = $this->db->query("select * from Chat where id = '$id' ");
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
        $data = $this->db->insert('chat', $data);
        $str = $this->db->last_query();
        if ($this->db->trans_status() !== false) {
            $response['meta'] = array(
                "status_code" => 200,
                "status_message" => 'Data Berhasil Ditambahkan',
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
    public function ubah($data)
    {
        //Mendefinikan variabel
        $response = array();
        $this->db->where('from_id', $data['from_id']);
        $this->db->where('to_id', $data['to_id']);
        $data = $this->db->update('chat', $data);
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
        $this->db->where('id', $id);
        $data = $this->db->delete('Chat');
        $str = $this->db->last_query();
        if ($this->db->trans_status() !== false) {
            $response['meta'] = array(
                "status_code" => 200,
                "status_message" => 'Data Berhasil Di hapus',
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
