<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Model {

    public function get(){
        $q = $this->db->get('produk');
		return $q->result();
    }

    public function get_by_id($id){
        $this->db->where('id', $id);
        $q = $this->db->get('produk');
		return $q->result();
    }

    public function insert($data){
        $q = $this->db->insert('produk', $data);
		return $q;
    }
    
    public function update($id,$value,$data){
		$this->db->where($id, $value);
        $q = $this->db->update('produk', $data);
        return $q;
    }

    public function delete($id,$value){
        $q = $this->db->delete('produk', array($id => $value));
        return $q;
    }
}
