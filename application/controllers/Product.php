<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Product extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->Model('Products');
		$this->load->Model('Users');
        $this->load->helper(array('url','download'));
        $this->load->helper(array('form', 'url'));
        $this->load->database();
    }
    
    protected function middleware()
    {
        return array('admin_auth');
    }
    
    function index_get($id = '') {
        if($this->middlewares['admin_auth']->is_auth == null){
            $response = $this->Users->not_authorized();
            $this->response($response, 401);
            return;
        }

        $response['code'] = 200;
        $response['status'] = true;
        if ($id == '') {
            $response['message'] = 'Berhasil Mendapatkan Data';
            $response['data'] = $this->Products->get();
            $this->response($response, 200);
        } else {
            $data = $this->Products->get_by_id($id);
            if(count($data) > 0){
                $response['message'] = 'Berhasil Mendapatkan Data';
                $response['data'] =  $this->Products->get_by_id($id)[0];
                $this->response($response, 200);
            }else{
                $response['code'] = 400;
                $response['status'] = false;
                $response['message'] = 'Data Tidak Ditemukan';
                $this->response($response, 400);
            }
        }
        
    }

    function index_post() {
        if($this->middlewares['admin_auth']->is_auth == null){
            $response = $this->Users->not_authorized();
            $this->response($response, 401);
            return;
        }
        
        $data = array(
            'nama'=> $this->post('nama'),
            'deskripsi' => $this->post('deskripsi'),
            'harga' => $this->post('harga'),
            'stok' => $this->post('stok'),
            'gambar' => $this->post('gambar')
        );
        $insert = $this->Products->insert($data);
        if ($insert) {
            $response['code'] = 200;
            $response['status'] = true;
            $response['message'] = 'Berhasil Menambahkan Data Produk';
            $response['data'] = $data;
            $this->response($response, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
	
    function index_put($id = '') {

        if($this->middlewares['admin_auth']->is_auth == null){
            $response = $this->Users->not_authorized();
            $this->response($response, 401);
            return;
        }

        $data = array(
            'nama'=> $this->put('nama'),
            'deskripsi' => $this->put('deskripsi'),
            'harga' => $this->put('harga'),
            'stok' => $this->put('stok'),
            'gambar' => $this->put('gambar')
        );

        if ($id != '') {
            $update = $this->Products->update('id', $id , $data);
            if ($update) {
                $response['code'] = 200;
                $response['status'] = true;
                $response['message'] = 'Berhasil Mengupdate Data Produk';
                $response['data'] = $data;
                $this->response($response, 200);
            } else {
                $this->response(array('status' => 'fail', 502));
            }
        }else{
            $response['code'] = 400;
            $response['status'] = false;
            $response['message'] = 'Id Produk Dibutuhkan';
            $this->response($response, 400);
        }
    }

    function index_delete($id = '') {
        
        if($this->middlewares['admin_auth']->is_auth == null){
            $response = $this->Users->not_authorized();
            $this->response($response, 401);
            return;
        }

        if ($id != '') {
            $delete = $this->Products->delete('id',$id);
            if ($delete) {
                $response['code'] = 200;
                $response['status'] = true;
                $response['message'] = 'Berhasil Menghapus Data Produk';
                $this->response($response, 200);
            } else {
                $this->response(array('status' => 'fail', 400));
            }
        }else{
            $response['code'] = 400;
            $response['status'] = false;
            $response['message'] = 'Id Produk Dibutuhkan';
            $this->response($response, 400);
        }
    }

    function upload_image_post(){
        if($this->middlewares['admin_auth']->is_auth == null){
            $response = $this->Users->not_authorized();
            $this->response($response, 401);
            return;
        }

        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['file_name']            = generateRandomString(20);
        $config['max_size']             = 100000;
        $config['max_width']            = 8000;
        $config['max_height']           = 8000;
        $image = "";
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('image'))
        {
            $response['code'] = 400;
            $response['status'] = false;
            $response['message'] = 'Failed To Upload Image';
            $this->response($response, 400);
        } else {
            sleep(4);
            $string = $this->upload->data();
            $image = base_url()."uploads/".$string['file_name'];
            $response['code'] = 200;
            $response['status'] = true;
            $response['message'] = 'Berhasil Mengupload Image';
            $response['data'] = $image;
            $this->response($response, 200);
        }
           
    }
}
?>