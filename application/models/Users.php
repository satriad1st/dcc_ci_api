<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Model {

    public function get(){
        $q = $this->db->get('users');
		return $q->result();
    }

    public function get_by_id($id){
        $this->db->where('id', $id);
        $q = $this->db->get('users');
		return $q->result();
    }

    public function get_by_email($email){
        $this->db->where('email', $email);
        $q = $this->db->get('users');
		return $q->result();
    }

    public function insert($data){
        $q = $this->db->insert('users', $data);
		return $q;
    }
    
    public function update($id,$value,$data){
		$this->db->where($id, $value);
        $q = $this->db->update('users', $data);
        return $q;
    }

    public function delete($id,$value){
        $q = $this->db->delete('users', array($id => $value));
        return $q;
    }

    public function login($in) {
		$email = $in['email'];
		$password = $in['password'];
		$this->db->where('email',$email);
		$this->db->where('password',hash('sha512',$password . config_item('encryption_key')));
		$query = $this->db->get('users');

		if($query->num_rows() > 0) {
			foreach($query->result() as $data) {
				$id = $data->id;
			}
            return $id;
		}  else {
			return null;
		}
	}

    public function not_authorized(){
        $response['code'] = 401;
        $response['status'] = false;
        $response['message'] = 'Anda Tidak Memiliki Akses Untuk Mengakses Resource Ini';

        return $response;
    }
}
