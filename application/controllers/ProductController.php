<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
    }


    /*
    |--------------------------------------------------------------------------
    | ALL PRODUCTS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data['page_title'] = 'All Products';

        $data['products'] = $this->db
            ->order_by('product_name', 'ASC')
            ->get('products')
            ->result_array();

        $data['category'] = 'All';

        $data['sidebar_notifications'] = array();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar', $data);
        $this->load->view('products', $data);
        $this->load->view('layout/footer');
    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORY PRODUCTS
    |--------------------------------------------------------------------------
    */

    public function category($category)
{
    $allowed = array(
        'Tiles',
        'Marble',
        'Hardware',
        'Stationary'
    );

    if (!in_array($category, $allowed, true)) {
        show_404();
        return;
    }

    $data['page_title'] = $category . ' Stock';
    $data['category'] = $category;

    $data['products'] = $this->db
        ->where('category', $category)
        ->order_by('product_name', 'ASC')
        ->get('products')
        ->result_array();

    $data['sidebar_notifications'] = array();

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar', $data);
    $this->load->view('products', $data);
    $this->load->view('layout/footer');
}


    /*
    |--------------------------------------------------------------------------
    | SAVE PRODUCT
    |--------------------------------------------------------------------------
    */

    public function save()
    {
        $product_name = trim(
            $this->input->post('product_name', TRUE)
        );

        $category = trim(
            $this->input->post('category', TRUE)
        );

        $size = trim(
            $this->input->post('size', TRUE)
        );

        $stock_qty = (float) $this->input->post('stock_qty');

        $unit_price = (float) $this->input->post('unit_price');

        if ($product_name === '') {

            $this->session->set_flashdata(
                'error',
                'Product name is required.'
            );

            redirect('ProductController/index');
            return;
        }


        $allowed = array(
            'Tiles',
            'Marble',
            'Hardware',
            'Stationary'
        );

        if (!in_array($category, $allowed)) {

            $this->session->set_flashdata(
                'error',
                'Please select a valid category.'
            );

            redirect('ProductController/index');
            return;
        }


        if ($stock_qty < 0) {
            $stock_qty = 0;
        }


        if ($unit_price < 0) {
            $unit_price = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORY BASED UNIT
        |--------------------------------------------------------------------------
        */

        switch ($category) {

            case 'Tiles':
                $stock_unit = 'Box';
                break;

            case 'Marble':
                $stock_unit = 'Sq.Ft';
                break;

            case 'Hardware':
                $stock_unit = 'Piece';
                break;

            case 'Stationary':
                $stock_unit = 'Piece';
                break;

            default:
                $stock_unit = 'Piece';
        }


        $product_data = array(
            'product_name' => $product_name,
            'category'     => $category,
            'stock_unit'   => $stock_unit,
            'size'         => $size,
            'stock_qty'    => $stock_qty,
            'unit_price'   => $unit_price
        );


        $inserted = $this->db->insert(
            'products',
            $product_data
        );


        if (!$inserted) {

            $db_error = $this->db->error();

            $this->session->set_flashdata(
                'error',
                'Product could not be added. ' .
                $db_error['message']
            );

            redirect('ProductController/index');
            return;
        }


        $this->session->set_flashdata(
            'success',
            $product_name . ' added successfully.'
        );


        /*
        |--------------------------------------------------------------------------
        | RETURN TO SAME CATEGORY
        |--------------------------------------------------------------------------
        */

        if ($category !== '') {

            redirect(
                'ProductController/category/' .
                rawurlencode($category)
            );

            return;
        }


        redirect('ProductController/index');
    }


    /*
    |--------------------------------------------------------------------------
    | BILLING PRODUCTS
    |--------------------------------------------------------------------------
    */

    public function billing_products()
    {
        $products = $this->db
            ->where('stock_qty >', 0)
            ->order_by('product_name', 'ASC')
            ->get('products')
            ->result_array();


        echo json_encode(
            array(
                'status'   => true,
                'products' => $products
            )
        );
    }
}