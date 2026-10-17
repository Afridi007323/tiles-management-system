<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
    }


    /* =====================================================
       RECEIVE PAYMENT PAGE
    ===================================================== */

    public function receive()
    {
        $data['page_title'] = 'Receive Payment';

        $data['customers'] = $this->db
            ->where('total_due >', 0)
            ->order_by('name', 'ASC')
            ->get('customers')
            ->result_array();

        $data['sidebar_notifications'] = array();

        $this->load->view('layout/header');

        $this->load->view(
            'layout/sidebar',
            $data
        );

        $this->load->view(
            'receive_payment',
            $data
        );

        $this->load->view('layout/footer');
    }


    /* =====================================================
       CUSTOMER DUE AJAX
    ===================================================== */

    public function customer_due($customer_id)
    {
        $customer_id = (int) $customer_id;

        if ($customer_id <= 0) {

            echo json_encode(array(
                'status' => false,
                'message' => 'Invalid customer.'
            ));

            return;
        }

        $customer = $this->db
            ->where('id', $customer_id)
            ->get('customers')
            ->row_array();

        if (!$customer) {

            echo json_encode(array(
                'status' => false,
                'message' => 'Customer not found.'
            ));

            return;
        }

        $due = (float) (
            $customer['total_due'] ?? 0
        );

        if ($due < 0) {
            $due = 0;
        }

        echo json_encode(array(
            'status' => true,
            'customer_id' => $customer_id,
            'customer_name' => $customer['name'],
            'mobile' => $customer['mobile'] ?? '',
            'total_due' => $due
        ));
    }


    /* =====================================================
       SAVE CUSTOMER PAYMENT
    ===================================================== */

    public function save()
    {
        $customer_id = (int) $this->input->post('customer_id');

        $amount = (float) $this->input->post('amount');

        $payment_mode = trim(
            (string) $this->input->post('payment_mode')
        );

        if ($payment_mode === '') {
            $payment_mode = 'Cash';
        }

        $note = trim(
            (string) $this->input->post('note')
        );

        if ($note === '') {
            $note = 'Customer due payment';
        }


        /* CUSTOMER VALIDATION */

        if ($customer_id <= 0) {

            $this->session->set_flashdata(
                'error',
                'Please select customer.'
            );

            redirect('CustomerController/due_reports');
            return;
        }


        /* AMOUNT VALIDATION */

        if ($amount <= 0) {

            $this->session->set_flashdata(
                'error',
                'Please enter a valid payment amount.'
            );

            redirect('PaymentController/receive');
            return;
        }


        /* CUSTOMER */

        $customer = $this->db
            ->where('id', $customer_id)
            ->get('customers')
            ->row_array();

        if (!$customer) {

            $this->session->set_flashdata(
                'error',
                'Customer not found.'
            );

            redirect('PaymentController/receive');
            return;
        }


        /*
         * Customer ka current due.
         * Cache value use kar rahe hain kyunki
         * Receive Payment page isi value se load hota hai.
         */

        $current_due = (float) (
            $customer['total_due'] ?? 0
        );

        if ($current_due <= 0) {

            $this->session->set_flashdata(
                'error',
                $customer['name'] .
                ' ka koi pending due nahi hai.'
            );

            redirect('PaymentController/receive');
            return;
        }


        /* PAYMENT LIMIT */

        if ($amount > $current_due) {
            $amount = $current_due;
        }


        /* START TRANSACTION */

        $this->db->trans_begin();


        /* =================================================
           FIND OLDEST DUE INVOICE
        ================================================= */

        $invoice = $this->db
            ->where('customer_id', $customer_id)
            ->where('due_amount >', 0)
            ->order_by('id', 'ASC')
            ->limit(1)
            ->get('invoices')
            ->row_array();

        $invoice_id = null;


        if ($invoice) {

            $invoice_id = (int) $invoice['id'];

            $invoice_due = (float) (
                $invoice['due_amount'] ?? 0
            );

            $invoice_paid = (float) (
                $invoice['paid_amount'] ?? 0
            );


            /*
             * Selected oldest invoice mein
             * payment adjust karo.
             */

            $invoice_payment = min(
                $amount,
                $invoice_due
            );


            $new_invoice_due =
                $invoice_due - $invoice_payment;


            $new_invoice_paid =
                $invoice_paid + $invoice_payment;


            if ($new_invoice_due < 0) {
                $new_invoice_due = 0;
            }


            $invoice_update = array(
                'paid_amount' => $new_invoice_paid,
                'due_amount' => $new_invoice_due
            );


            $this->db
                ->where('id', $invoice_id)
                ->update(
                    'invoices',
                    $invoice_update
                );


            if ($this->db->affected_rows() < 0) {

                $this->db->trans_rollback();

                $this->session->set_flashdata(
                    'error',
                    'Invoice update nahi ho paya.'
                );

                redirect(
                    'PaymentController/receive'
                );

                return;
            }
        }


        /* =================================================
           PAYMENTS TABLE
        ================================================= */

        $payment_data = array(
            'invoice_id' => $invoice_id,
            'customer_id' => $customer_id,
            'paid_amount' => $amount,
            'payment_date' => date('Y-m-d'),
            'payment_mode' => $payment_mode,
            'note' => $note,
            'created_at' => date('Y-m-d H:i:s')
        );


        $payment_insert = $this->db->insert(
            'payments',
            $payment_data
        );


        if (!$payment_insert) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Payment save nahi ho paya.'
            );

            redirect(
                'PaymentController/receive'
            );

            return;
        }


        /* =================================================
           PAYMENT HISTORY
        ================================================= */

        $history_data = array(
            'invoice_id' => $invoice_id,
            'amount_paid' => $amount,
            'payment_date' => date('Y-m-d'),
            'created_at' => date('Y-m-d H:i:s')
        );


        $history_insert = $this->db->insert(
            'payment_history',
            $history_data
        );


        if (!$history_insert) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Payment history save nahi ho payi.'
            );

            redirect(
                'PaymentController/receive'
            );

            return;
        }


        /* =================================================
           CUSTOMER DUE UPDATE
        ================================================= */

        $remaining_customer_due =
            $current_due - $amount;


        if ($remaining_customer_due < 0) {
            $remaining_customer_due = 0;
        }


        $this->db
            ->where('id', $customer_id)
            ->update(
                'customers',
                array(
                    'total_due' =>
                        $remaining_customer_due
                )
            );


        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Payment transaction failed.'
            );

            redirect(
                'PaymentController/receive'
            );

            return;
        }


        /* COMMIT */

        $this->db->trans_commit();


        /* =================================================
           SUCCESS MESSAGE DATA
        ================================================= */

        $customer_name = $customer['name'];

        $this->session->set_flashdata(
            'success',
            $customer_name .
            ' ka ₹' .
            number_format($amount, 2) .
            ' payment successfully received.'
        );


        $this->session->set_flashdata(
            'payment_customer_id',
            $customer_id
        );


        $this->session->set_flashdata(
            'payment_customer_name',
            $customer_name
        );


        $this->session->set_flashdata(
            'payment_amount',
            $amount
        );


        $this->session->set_flashdata(
            'payment_mode',
            $payment_mode
        );


        redirect(
            'PaymentController/receive'
        );
    }


    /* =====================================================
       OLD URL SUPPORT
       
       Agar kahin:
       PaymentController/save
       ki jagah
       PaymentController/receive_payment
       use ho raha hai.
    ===================================================== */

    public function receive_payment()
    {
        $this->save();
    }


    /* =====================================================
       COMPLETED PAYMENTS
    ===================================================== */

    public function completed()
    {
        $data['page_title'] = 'Completed Payments';


        $this->db->select('
            invoices.id,
            invoices.invoice_no,
            invoices.billing_date,
            invoices.total_amount,
            invoices.paid_amount,
            invoices.due_amount,
            customers.name AS cust_name,
            customers.mobile
        ');

        $this->db->from('invoices');

        $this->db->join(
            'customers',
            'customers.id = invoices.customer_id',
            'left'
        );

        $this->db->where(
            'invoices.due_amount <=',
            0
        );

        $this->db->order_by(
            'invoices.id',
            'DESC'
        );


        $data['completed'] =
            $this->db
                ->get()
                ->result_array();


        $data['sidebar_notifications'] =
            array();


        $this->load->view(
            'layout/header'
        );

        $this->load->view(
            'layout/sidebar',
            $data
        );

        $this->load->view(
            'completed_payments_view',
            $data
        );

        $this->load->view(
            'layout/footer'
        );
    }
}