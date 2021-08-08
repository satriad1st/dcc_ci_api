<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class User extends REST_Controller {

    protected function middleware()
    {
        return array('admin_auth');
    }
    
    function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->Model('Users');
        $this->load->database();
    }

    function index_get() {
        $id = $this->get('id');
        $response['code'] = 200;
        $response['status'] = true;
        if ($id == '') {
            $response['data'] = $this->Users->get();
        } else {
            $response['data'] =  $this->Users->get_by_id($id);
        }
        
        $this->response($response, 200);
    }

    function index_post() {
        $email = str_replace(" ","",$this->post('email'));
        $password = $this->post('password');

        if ($email=='') {
            $response['code'] = 400;
            $response['message'] = 'Email Tidak Boleh Kosong';
            $this->response($response, 400);
            return;
        }

        if($password=='' || strlen($password) < 6){
            $response['code'] = 400;
            $response['message'] = 'Password Tidak Boleh Kurang Dari 6 Karakter';
            $this->response($response, 400);
            return;
        }

        $emailChecking = $this->Users->get_by_email($email);
        
        if(count($emailChecking) > 0) {
            $response['code'] = 400;
            $response['message'] = 'Email Sudah Digunakan Oleh Orang Lain';
            $this->response($response, 400);
            return;
        }

        $data = array(
            'nama'=> $this->post('nama'),
            'email' => $email,
            'password' => hash('sha512', $password . config_item('encryption_key'))
        );
        $insert = $this->Users->insert($data);
        $response['code'] = 200;
        $response['status'] = true;
        if ($insert) {
            $response['data'] = $data;
            $this->response($response, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function login_post() {
        if($this->input->method(TRUE) == 'POST' && !empty($_POST)) {
			$in['email'] = $this->input->post('email');
			$in['password'] = $this->input->post('password');
			$id = $this->Users->login($in);

            if($id != null){
                $response['code'] = 200;
                $response['message'] = 'Berhasil Login';
                $tokenData = array();
                $tokenData['id'] = $id; 
                $response['token'] = AUTHORIZATION::generateToken($tokenData);
                $this->response($response, 200);
            }else{
                $response['code'] = 400;
                $response['message'] = 'Informasi Login Tidak Sesuai';
                $this->response($response, 400);
                return;
            }
		} else {
            $response['code'] = 400;
            $response['message'] = 'Terdapat Kesalahan Pada Form Input Anda';
            $this->response($response, 400);
            return;
		}
    }

}
?>