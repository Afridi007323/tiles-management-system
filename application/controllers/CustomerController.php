<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Required services
        $this->load->database();
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
    }


    /* =========================================================
       COMMON CUSTOMER LEDGER

       Total Billing  = SUM(invoices.total_amount)
       Total Received = SUM(payments.paid_amount)
       Current Due    = Total Billing - Total Received

       Ye calculation Customers, Due Reports aur
       Payment History sab jagah same rahegi.
    ========================================================= */

    private function getCustomerLedger($customer_id)
    {
        // Total billing
        $billing_row = $this->db
            ->select_sum('total_amount', 'total_billing')
            ->where('customer_id', $customer_id)
            ->get('invoices')
            ->row_array();

        // Total received
        $payment_row = $this->db
            ->select_sum('paid_amount', 'total_paid')
            ->where('customer_id', $customer_id)
            ->get('payments')
            ->row_array();

        $total_billing = (float) ($billing_row['total_billing'] ?? 0);
        $total_paid    = (float) ($payment_row['total_paid'] ?? 0);

        // Current customer due
        $current_due = $total_billing - $total_paid;

        if ($current_due < 0) {
            $current_due = 0;
        }

        return array(
            'total_billing' => $total_billing,
            'total_paid'    => $total_paid,
            'current_due'   => $current_due
        );
    }


    /* =========================================================
       ALL CUSTOMERS
    ========================================================= */

    public function index()
    {
        $customers = $this->db
            ->select('customers.*')
            ->from('customers')
            ->order_by('customers.id', 'DESC')
            ->get()
            ->result_array();

        // Add live billing/payment/due totals
        foreach ($customers as &$customer) {

            $ledger = $this->getCustomerLedger(
                $customer['id']
            );

            $customer['total_billing'] = $ledger['total_billing'];
            $customer['total_paid']    = $ledger['total_paid'];
            $customer['current_due']   = $ledger['current_due'];
        }

        $data['customers'] = $customers;

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('customers', $data);
        $this->load->view('layout/footer');
    }


    /* =========================================================
       SAVE NEW CUSTOMER
    ========================================================= */

    public function save()
    {
        $name = trim(
            $this->input->post('name', TRUE)
        );

        $mobile = trim(
            $this->input->post('mobile', TRUE)
        );

        $address = trim(
            $this->input->post('address', TRUE)
        );


        // Validate name
        if ($name === '') {

            $this->session->set_flashdata(
                'error',
                'Customer name is required.'
            );

            redirect('CustomerController/index');
            return;
        }


        // Validate mobile
        if ($mobile === '') {

            $this->session->set_flashdata(
                'error',
                'Mobile number is required.'
            );

            redirect('CustomerController/index');
            return;
        }


        // Check duplicate mobile
        $existing = $this->db
            ->where('mobile', $mobile)
            ->get('customers')
            ->row_array();


        if ($existing) {

            $this->session->set_flashdata(
                'error',
                'This mobile number is already registered.'
            );

            redirect('CustomerController/index');
            return;
        }


        // Customer data
        $customer_data = array(
            'name'           => $name,
            'mobile'         => $mobile,
            'address'        => $address,
            'total_purchase' => 0,
            'total_due'      => 0
        );


        // Insert customer
        $inserted = $this->db->insert(
            'customers',
            $customer_data
        );


        if (!$inserted) {

            $db_error = $this->db->error();

            $this->session->set_flashdata(
                'error',
                'Customer could not be added. ' .
                $db_error['message']
            );

            redirect('CustomerController/index');
            return;
        }


        // Success
        $this->session->set_flashdata(
            'success',
            'Customer added successfully.'
        );

        redirect('CustomerController/index');
    }


    /* =========================================================
       CUSTOMER DUES

       Sirf wahi customers show honge jinka
       current_due > 0 hai.
    ========================================================= */

    public function due_reports()
    {
        $customers = $this->db
            ->select('customers.*')
            ->from('customers')
            ->order_by('customers.id', 'DESC')
            ->get()
            ->result_array();

        $due_customers = array();


        foreach ($customers as $customer) {

            $ledger = $this->getCustomerLedger(
                $customer['id']
            );


            // Only customers with pending due
            if ($ledger['current_due'] > 0) {

                $customer['total_billing'] =
                    $ledger['total_billing'];

                $customer['total_paid'] =
                    $ledger['total_paid'];

                $customer['current_due'] =
                    $ledger['current_due'];


                // Latest invoice is used for payment linking
                $latest_invoice = $this->db
                    ->where(
                        'customer_id',
                        $customer['id']
                    )
                    ->order_by('id', 'DESC')
                    ->limit(1)
                    ->get('invoices')
                    ->row_array();


                $customer['latest_invoice_id'] =
                    $latest_invoice['id'] ?? 0;


                $due_customers[] = $customer;
            }
        }


        $data['due_customers'] = $due_customers;


        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('due_reports', $data);
        $this->load->view('layout/footer');
    }


    /* =========================================================
       RECEIVE CUSTOMER DUE PAYMENT

       Payment flow:

       1. Validate customer/payment
       2. Calculate real customer due
       3. Find invoice
       4. Save payment
       5. Save payment history
       6. Update invoice due
       7. Update customer cached due
       8. Commit transaction
       9. Show success message
    ========================================================= */

    public function receive_payment()
{
    $invoice_id = (int) $this->input->post('invoice_id');
    $amount_received = (float) $this->input->post('amount');
    $customer_id = (int) $this->input->post('customer_id');

    $payment_mode = trim(
        $this->input->post('payment_mode', TRUE)
    );

    $note = trim(
        $this->input->post('note', TRUE)
    );


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    if ($customer_id <= 0) {

        $this->session->set_flashdata(
            'payment_error',
            'Invalid customer.'
        );

        redirect('CustomerController/due_reports');
        return;
    }


    if ($amount_received <= 0) {

        $this->session->set_flashdata(
            'payment_error',
            'Please enter a valid payment amount.'
        );

        redirect('CustomerController/due_reports');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER
    |--------------------------------------------------------------------------
    */

    $customer = $this->db
        ->where('id', $customer_id)
        ->get('customers')
        ->row_array();


    if (!$customer) {

        $this->session->set_flashdata(
            'payment_error',
            'Customer not found.'
        );

        redirect('CustomerController/due_reports');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | REAL CUSTOMER LEDGER
    |--------------------------------------------------------------------------
    */

    $ledger = $this->getCustomerLedger(
        $customer_id
    );


    $current_due = (float) $ledger['current_due'];


    if ($current_due <= 0) {

        $this->session->set_flashdata(
            'payment_error',
            'This customer has no pending due.'
        );

        redirect('CustomerController/due_reports');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT CANNOT EXCEED DUE
    |--------------------------------------------------------------------------
    */

    if ($amount_received > $current_due) {

        $amount_received = $current_due;
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT MODE
    |--------------------------------------------------------------------------
    */

    if ($payment_mode === '') {

        $payment_mode = 'Cash';
    }


    /*
    |--------------------------------------------------------------------------
    | NOTE
    |--------------------------------------------------------------------------
    */

    if ($note === '') {

        $note = 'Payment received against due';
    }


    /*
    |--------------------------------------------------------------------------
    | FIND INVOICE
    |--------------------------------------------------------------------------
    */

    $invoice = null;


    if ($invoice_id > 0) {

        $invoice = $this->db
            ->where('id', $invoice_id)
            ->where('customer_id', $customer_id)
            ->get('invoices')
            ->row_array();
    }


    /*
    |--------------------------------------------------------------------------
    | FALLBACK: FIND LATEST INVOICE
    |--------------------------------------------------------------------------
    */

    if (!$invoice) {

        $invoice = $this->db
            ->where('customer_id', $customer_id)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('invoices')
            ->row_array();
    }


    if (!$invoice) {

        $this->session->set_flashdata(
            'payment_error',
            'Invoice not found for this customer.'
        );

        redirect('CustomerController/due_reports');
        return;
    }


    $invoice_id = (int) $invoice['id'];


    /*
    |--------------------------------------------------------------------------
    | START TRANSACTION
    |--------------------------------------------------------------------------
    */

    $this->db->trans_begin();


    /*
    |--------------------------------------------------------------------------
    | SAVE PAYMENT
    |--------------------------------------------------------------------------
    */

    $payment_data = array(
        'invoice_id'   => $invoice_id,
        'customer_id'  => $customer_id,
        'paid_amount'  => $amount_received,
        'payment_date' => date('Y-m-d'),
        'payment_mode' => $payment_mode,
        'note'         => $note,
        'created_at'   => date('Y-m-d H:i:s')
    );


    $this->db->insert(
        'payments',
        $payment_data
    );


    if ($this->db->affected_rows() <= 0) {

        $this->db->trans_rollback();

        $this->session->set_flashdata(
            'payment_error',
            'Payment could not be saved.'
        );

        redirect('CustomerController/due_reports');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | SAVE PAYMENT HISTORY
    |--------------------------------------------------------------------------
    */

    $history_data = array(
        'invoice_id'  => $invoice_id,
        'amount_paid' => $amount_received,
        'payment_date' => date('Y-m-d'),
        'created_at'  => date('Y-m-d H:i:s')
    );


    $this->db->insert(
        'payment_history',
        $history_data
    );


    if ($this->db->affected_rows() <= 0) {

        $this->db->trans_rollback();

        $this->session->set_flashdata(
            'payment_error',
            'Payment history could not be saved.'
        );

        redirect('CustomerController/due_reports');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE INVOICE DUE
    |--------------------------------------------------------------------------
    */

    $invoice_due = (float) (
        $invoice['due_amount'] ?? 0
    );


    if ($invoice_due > 0) {

        $invoice_payment = min(
            $amount_received,
            $invoice_due
        );


        $new_invoice_due =
            $invoice_due - $invoice_payment;


        if ($new_invoice_due < 0) {

            $new_invoice_due = 0;
        }


        $this->db
            ->where('id', $invoice_id)
            ->update(
                'invoices',
                array(
                    'due_amount' => $new_invoice_due
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE CUSTOMER CACHE
    |--------------------------------------------------------------------------
    */

    $new_customer_due =
        $current_due - $amount_received;


    if ($new_customer_due < 0) {

        $new_customer_due = 0;
    }


    $this->db
        ->where('id', $customer_id)
        ->update(
            'customers',
            array(
                'total_due' => $new_customer_due
            )
        );


    /*
    |--------------------------------------------------------------------------
    | CHECK TRANSACTION
    |--------------------------------------------------------------------------
    */

    if ($this->db->trans_status() === FALSE) {

        $this->db->trans_rollback();

        $this->session->set_flashdata(
            'payment_error',
            'Payment could not be completed.'
        );

        redirect('CustomerController/due_reports');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | COMMIT
    |--------------------------------------------------------------------------
    */

    $this->db->trans_commit();


    /*
    |--------------------------------------------------------------------------
    | PAYMENT SUCCESS DATA
    |--------------------------------------------------------------------------
    */

    $this->session->set_flashdata(
        'payment_success',
        'Payment successfully received.'
    );


    $this->session->set_flashdata(
        'payment_customer_id',
        $customer_id
    );


    $this->session->set_flashdata(
        'payment_customer_name',
        $customer['name']
    );


    $this->session->set_flashdata(
        'payment_amount',
        $amount_received
    );


    /*
    |--------------------------------------------------------------------------
    | BACK TO SAME DUE PAGE
    |--------------------------------------------------------------------------
    */

    redirect(
        'CustomerController/due_reports'
    );

    return;
}












    /* =========================================================
       CUSTOMER SEARCH

       Name ya mobile se customer search.
    ========================================================= */

    public function search()
    {
        $search = trim(
            $this->input->get('q')
        );


        // Empty search
        if ($search === '') {

            echo json_encode(
                array(
                    'status'    => true,
                    'customers' => array()
                )
            );

            return;
        }


        // Search name OR mobile
        $this->db->group_start();

        $this->db->like(
            'name',
            $search
        );

        $this->db->or_like(
            'mobile',
            $search
        );

        $this->db->group_end();


        $customers = $this->db
            ->order_by(
                'name',
                'ASC'
            )
            ->limit(20)
            ->get('customers')
            ->result_array();


        echo json_encode(
            array(
                'status'    => true,
                'customers' => $customers
            )
        );
    }


    /* =========================================================
       CUSTOMER PAYMENT HISTORY

       History ke saath same customer ledger bhi return hota hai:

       Total Billing
       Total Received
       Current Due
    ========================================================= */

    public function payment_history($customer_id)
    {
        $customer_id = (int) $customer_id;


        // Validate customer ID
        if ($customer_id <= 0) {

            echo json_encode(
                array(
                    'status'  => false,
                    'message' => 'Invalid customer ID.'
                )
            );

            return;
        }


        // Get customer
        $customer = $this->db
            ->where(
                'id',
                $customer_id
            )
            ->get('customers')
            ->row_array();


        if (!$customer) {

            echo json_encode(
                array(
                    'status'  => false,
                    'message' => 'Customer not found.'
                )
            );

            return;
        }


        // Get same ledger used everywhere
        $ledger = $this->getCustomerLedger(
            $customer_id
        );


        // Get payment transactions
        $this->db->select('
            payments.id,
            payments.customer_id,
            payments.invoice_id,
            payments.paid_amount,
            payments.payment_date,
            payments.payment_mode,
            payments.note,
            payments.created_at,
            invoices.invoice_no
        ');


        $this->db->from('payments');


        $this->db->join(
            'invoices',
            'invoices.id = payments.invoice_id',
            'left'
        );


        $this->db->where(
            'payments.customer_id',
            $customer_id
        );


        $this->db->order_by(
            'payments.payment_date',
            'DESC'
        );


        $this->db->order_by(
            'payments.id',
            'DESC'
        );


        $payments = $this->db
            ->get()
            ->result_array();


        // Return customer + ledger + history
        echo json_encode(
            array(
                'status' => true,

                'customer' => array(
                    'id' =>
                        $customer['id'],

                    'name' =>
                        $customer['name'],

                    'mobile' =>
                        $customer['mobile']
                ),

                'total_billing' =>
                    $ledger['total_billing'],

                'total_paid' =>
                    $ledger['total_paid'],

                'current_due' =>
                    $ledger['current_due'],

                'transactions' =>
                    $payments
            )
        );
    }
}