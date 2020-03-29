<?php

class Chat extends BD_Controller
{

    // construct
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Chat_M');
        $this->load->model('M_siswa_M');
        $this->load->model('M_user_M');
        $this->load->library('crypt');
        // $this->auth();
    }

    /*
    Fungsi : mengambil semua data sesuai parameter
    Return : Array(Array())
     */
    public function get_all_post()
    {
        $list = $this->Chat_M->get_all();
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
            $row[] = $customers->nama_kelas;
            $row[] = $btn;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Chat_M->count_all(),
            "recordsFiltered" => $this->Chat_M->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    public function get_all_to_id_get($id)
    {
        $response = $this->Chat_M->get_all_to_id($id);
        $this->response($response);
    }
    public function get_new_percakapan_get($to_id, $from_id)
    {
        $response = $this->Chat_M->get_count_unread_siswa($to_id, $from_id);
        $this->response($response);
    }
    public function get_all_percakapan_get($to_id, $from_id)
    {
        if ($to_id != 3) {
            $percakapan = $this->Chat_M->get_all_percakapan($to_id, $from_id)['data'];
        } else {
            $percakapan = $this->Chat_M->get_all_percakapan($to_id, $from_id)['data'];
        }
        // echo json_encode($percakapan);
        foreach ($percakapan as $i => $chat) {
            if ($to_id == $chat->from_id) {
                echo '
                <div class="m-messenger__wrapper">
                    <div class="m-messenger__message m-messenger__message--out">
                        <div class="m-messenger__message-body">
                            <div class="m-messenger__message-arrow"></div>
                            <div class="m-messenger__message-content">
                                <div class="m-messenger__message-text">
                                    ' . $chat->content . '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            } else {
                echo '<div class="m-messenger__wrapper">
                    <div class="m-messenger__message m-messenger__message--in">
                        <div class="m-messenger__message-pic">
                            <img src="assets/app/media/img//users/user3.jpg" alt="">
                        </div>
                        <div class="m-messenger__message-body">
                            <div class="m-messenger__message-arrow"></div>
                            <div class="m-messenger__message-content">
                                <div class="m-messenger__message-username">
                                ' . $chat->nama . '
                                </div>
                                <div class="m-messenger__message-text">
                                ' . $chat->content . '
                                </div>
                                <input type="hidden" id"'.$chat->to_id.'" value="'.$chat->to_id_status.'"></input>
                            </div>
                        </div>
                    </div>
                </div>
        ';
            }
            // echo json_encode($chat);
        }
    }
    public function get_by_id_get($id)
    {
        $response = $this->Chat_M->get_by_id($id);
        $this->response($response);
    }
    public function get_last_chat_by_id_get($to_id = '', $id_role = "")
    {
        if ($id_role != 3) {
            $last_chat = $this->Chat_M->get_last_chat_by_id_siswa($to_id)['data'];
        } else {
            $last_chat = $this->Chat_M->get_last_chat_by_id_user($to_id)['data'];
        }

        
        // die;
        foreach ($last_chat as $i => $chat) {
            if ($id_role != 3) {
                // $unread = $this->Chat_M->get_count_unread_siswa($to_id, $chat->to_id)['data'];
                for ($i = 0; $i < count($last_chat); $i++) {
            
                    $unread = $this->Chat_M->get_count_unread_siswa($last_chat[$i]->from_id, $last_chat[$i]->to_id)['data'];
                    $object = new stdClass();
                    if($last_chat[$i]->to_id == $unread->from_id && $last_chat[$i]->from_id == $unread->to_id){
                        $last_chat[$i]->unread = $unread->unread;
                        // echo json_encode($last_chat[$i]);
                    }
                    // array_push($last_chat, $object);
                }
            } else {
                
                for ($i = 0; $i < count($last_chat); $i++) {
            
                    $unread = $this->Chat_M->get_count_unread_user($last_chat[$i]->from_id, $last_chat[$i]->to_id)['data'];
                    $object = new stdClass();
                    if($last_chat[$i]->to_id == $unread->to_id && $last_chat[$i]->from_id == $unread->from_id){
                        $last_chat[$i]->unread = $unread->unread;
                        // echo json_encode($last_chat[$i]);
                    }
                    // array_push($last_chat, $object);
                }
            }

            
            $date = $chat->time_insert;
            // $date = substr($date, 0, strpos($date, " CE"));
            $now  = date('Y-m-d');
            $datetime1 = new DateTime($date);
            $datetime2 = new DateTime($now);
            $interval = $datetime2->diff($datetime1);

            $url = "";
            if ($id_role != 3) {
                $url = $chat->to_id;
            } else {
                $url = $chat->from_id;
            }
            echo '
                <div class="m-widget3__item">
                    <div class="m-widget3__header">
                        <div class="m-widget3__user-img">
                            <img class="m-widget3__img" src="assets/app/media/img/users/user1.jpg" alt="">
                        </div>
                        <div class="m-widget3__info">
                            <a href="chat/chating/' . $url . '">
                                <span class="m-widget3__username">
                                    ' . $chat->nama . '
                            </a>
                            </span><br>
                            <span class="m-widget3__time">';
            if ($interval->format("%R%a") == "-0") {
                echo "hari ini";
            } else {
                echo $interval->format('%R%a hari yang lalu');
            }
            echo '</span>
                        </div>
                        <span class="m-widget3__status m--font-info">';
            if ($chat->unread != 0) {
                echo '<span class="m-badge m-badge--danger">' . $chat->unread . '</span>';
            }
            echo '</span>
                    </div>
                </div>
                ';
        }
    }
    public function add_post()
    {
        $dtusr = trim(file_get_contents('php://input'));
        $objx = json_decode(trim($dtusr), true);
        $response = $this->Chat_M->add($objx);
        $this->response($response);
    }
    public function ubah_post()
    {
        $dtusr = trim(file_get_contents('php://input'));
        $objx = json_decode(trim($dtusr), true);

        $response = $this->Chat_M->ubah($objx);
        $this->response($response);
    }
}
