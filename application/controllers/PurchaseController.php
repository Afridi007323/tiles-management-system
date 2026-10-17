<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
    }


    /* =====================================================
       NEW PURCHASE
    ===================================================== */

    public function new_purchase()
    {
        $data['page_title'] = 'New Purchase';

        /*
         * Products
         */
        $data['products'] = $this->db
            ->order_by('product_name', 'ASC')
            ->get('products')
            ->result_array();


        /*
         * Suppliers
         *
         * Agar suppliers table abhi nahi bana hai,
         * to neeche wali query temporarily hata sakte ho.
         */
        $data['suppliers'] = array();

        if ($this->db->table_exists('suppliers')) {

            $data['suppliers'] = $this->db
                ->order_by('name', 'ASC')
                ->get('suppliers')
                ->result_array();
        }


        /*
         * Sidebar notifications
         */
        $data['sidebar_notifications'] = array();


        /*
         * Load layout
         */
        $this->load->view(
            'layout/header'
        );

        $this->load->view(
            'layout/sidebar',
            $data
        );

        $this->load->view(
            'purchases/new_purchase',
            $data
        );

        $this->load->view(
            'layout/footer'
        );
    }


    /* =====================================================
       PURCHASE HISTORY
    ===================================================== */

    public function history()
    {
        $data['page_title'] = 'Purchase History';

        $data['purchases'] = array();


        /*
         * Agar purchases table exist karta hai
         */
        if ($this->db->table_exists('purchases')) {

            $data['purchases'] = $this->db
                ->order_by('id', 'DESC')
                ->get('purchases')
                ->result_array();
        }


        $data['sidebar_notifications'] = array();


        /*
         * Load layout
         */
        $this->load->view(
            'layout/header'
        );

        $this->load->view(
            'layout/sidebar',
            $data
        );

        $this->load->view(
            'purchases/history',
            $data
        );

        $this->load->view(
            'layout/footer'
        );
    }
}