<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductModel extends CI_Model {

    // Saare products lane ke liye
    public function get_all_products() {
        return $this->db->get('products')->result_array();
    }

    // Naya stock add karne ke liye
    public function insert_product($data) {
        return $this->db->insert('products', $data);
    }



    
}