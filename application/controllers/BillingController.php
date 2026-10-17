<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BillingController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
    }


    /* =====================================================
       NEW BILL PAGE
    ===================================================== */

    public function index()
    {
        $data['page_title'] = 'Create New Invoice';

        /*
         * New Invoice:
         * ALL customers searchable
         */
        $data['customers'] = $this->db
            ->order_by('name', 'ASC')
            ->get('customers')
            ->result_array();

        /*
         * ALL products
         */
        $data['products'] = $this->db
            ->order_by('product_name', 'ASC')
            ->get('products')
            ->result_array();

        /*
         * Sidebar notifications
         */
        $data['sidebar_notifications'] =
            $this->get_sidebar_notifications();

        /*
         * Layout
         */
        $this->load->view(
            'layout/header'
        );

        $this->load->view(
            'layout/sidebar',
            $data
        );

        $this->load->view(
            'billing',
            $data
        );

        $this->load->view(
            'layout/footer'
        );
    }


    /* =====================================================
       SIDEBAR NOTIFICATIONS
    ===================================================== */

    private function get_sidebar_notifications()
    {
        /*
         * Last 30 days payments
         */
        $this->db->select(
            'payments.*, customers.name as cust_name'
        );

        $this->db->from('payments');

        $this->db->join(
            'customers',
            'customers.id = payments.customer_id',
            'left'
        );

        $this->db->where(
            'payments.created_at >=',
            date(
                'Y-m-d H:i:s',
                strtotime('-30 days')
            )
        );

        $this->db->order_by(
            'payments.id',
            'DESC'
        );

        $this->db->limit(10);

        return $this->db
            ->get()
            ->result_array();
    }


    /* =====================================================
       CUSTOMER DETAILS
    ===================================================== */

    public function customer_details($customer_id)
    {
        $customer_id = (int) $customer_id;

        if ($customer_id <= 0) {

            $this->json_response(array(
                'status'  => false,
                'message' => 'Invalid customer.'
            ));

            return;
        }

        $customer = $this->db
            ->where('id', $customer_id)
            ->get('customers')
            ->row_array();

        if (!$customer) {

            $this->json_response(array(
                'status'  => false,
                'message' => 'Customer not found.'
            ));

            return;
        }

        $due = isset($customer['total_due'])
            ? (float) $customer['total_due']
            : 0;

        if ($due < 0) {
            $due = 0;
        }

        $this->json_response(array(
            'status'   => true,
            'customer' => $customer,
            'due'      => $due
        ));
    }


    /* =====================================================
       CUSTOMER SUMMARY

       Billing page AJAX:
       BillingController/get_customer_summary/{id}
    ===================================================== */

    public function get_customer_summary($id)
    {
        $id = (int) $id;

        if ($id <= 0) {

            $this->json_response(array(
                'status'    => false,
                'due'       => 0,
                'collected' => 0
            ));

            return;
        }

        $customer = $this->db
            ->where('id', $id)
            ->get('customers')
            ->row_array();

        if (!$customer) {

            $this->json_response(array(
                'status'    => false,
                'due'       => 0,
                'collected' => 0
            ));

            return;
        }

        $current_due = isset($customer['total_due'])
            ? (float) $customer['total_due']
            : 0;

        if ($current_due < 0) {
            $current_due = 0;
        }

        $this->json_response(array(
            'status'    => true,
            'due'       => $current_due,
            'collected' => 0
        ));
    }


    /* =====================================================
       SAVE BILL
       
       IMPORTANT:
       Billing form must use:

       action="<?= base_url('BillingController/save_bill') ?>"

       Flow:

       Customer
          ↓
       Products
          ↓
       New Bill
          ↓
       Old Due + New Bill
          ↓
       Payment
          ↓
       Remaining Due
          ↓
       Invoice Save
          ↓
       Items Save
          ↓
       Stock Reduce
          ↓
       Payment Save
          ↓
       Customer Due Update
          ↓
       Commit
          ↓
       Full Receipt
    ===================================================== */

    public function save_bill()
    {
        /* =================================================
           1. CUSTOMER
        ================================================= */

        $customer_id =
            (int) $this->input->post('customer_id');

        if ($customer_id <= 0) {

            $this->session->set_flashdata(
                'error',
                'Please select a customer.'
            );

            redirect('BillingController');

            return;
        }


        /* =================================================
           2. GET CUSTOMER
        ================================================= */

        $customer = $this->db
            ->where(
                'id',
                $customer_id
            )
            ->get('customers')
            ->row_array();

        if (!$customer) {

            $this->session->set_flashdata(
                'error',
                'Customer not found.'
            );

            redirect('BillingController');

            return;
        }


        /* =================================================
           3. OLD CUSTOMER DUE
        ================================================= */

        $old_due = isset(
            $customer['total_due']
        )
            ? (float) $customer['total_due']
            : 0;

        if ($old_due < 0) {
            $old_due = 0;
        }


        /* =================================================
           4. BILLING DATE
        ================================================= */

        $billing_date = trim(
            (string)
            $this->input->post('billing_date')
        );

        if (empty($billing_date)) {
            $billing_date = date('Y-m-d');
        }


        /*
         * Basic date validation
         */
        $date_object =
            DateTime::createFromFormat(
                'Y-m-d',
                $billing_date
            );

        if (
            !$date_object ||
            $date_object->format('Y-m-d') !== $billing_date
        ) {
            $billing_date = date('Y-m-d');
        }


        /* =================================================
           5. PAYMENT AMOUNT
        ================================================= */

        /*
         * Current billing.php:
         * paid_amount
         *
         * Old versions:
         * payment_amount
         */

        $payment_raw =
            $this->input->post('paid_amount');

        if (
            $payment_raw === null ||
            $payment_raw === ''
        ) {
            $payment_raw =
                $this->input->post('payment_amount');
        }

        $payment =
            (float) $payment_raw;

        if ($payment < 0) {
            $payment = 0;
        }


        /* =================================================
           6. PAYMENT MODE
        ================================================= */

        /*
         * Current:
         * payment_method
         *
         * Old:
         * payment_mode
         */

        $payment_mode =
            trim(
                (string)
                $this->input->post('payment_method')
            );

        if ($payment_mode === '') {

            $payment_mode =
                trim(
                    (string)
                    $this->input->post('payment_mode')
                );
        }

        if ($payment_mode === '') {
            $payment_mode = 'Cash';
        }


        /* =================================================
           7. PRODUCT ARRAYS
        ================================================= */

        $product_ids =
            $this->input->post('product_id');

        $qtys =
            $this->input->post('qty');

        $rates =
            $this->input->post('rate');


        if (!is_array($product_ids)) {
            $product_ids = array();
        }

        if (!is_array($qtys)) {
            $qtys = array();
        }

        if (!is_array($rates)) {
            $rates = array();
        }


        /* =================================================
           8. BUILD VALID ITEMS
        ================================================= */

        $items = array();

        $new_bill = 0;


        foreach (
            $product_ids as $index => $product_id
        ) {

            $product_id =
                (int) $product_id;

            /*
             * Empty row ignore
             */
            if ($product_id <= 0) {
                continue;
            }


            /*
             * Quantity
             */
            $quantity =
                isset($qtys[$index])
                    ? (float) $qtys[$index]
                    : 0;

            if ($quantity <= 0) {
                continue;
            }


            /*
             * Rate
             */
            $rate =
                isset($rates[$index])
                    ? (float) $rates[$index]
                    : 0;

            if ($rate < 0) {
                $rate = 0;
            }


            /*
             * Product database
             */
            $product = $this->db
                ->where(
                    'id',
                    $product_id
                )
                ->get('products')
                ->row_array();

            if (!$product) {

                $this->session->set_flashdata(
                    'error',
                    'Selected product was not found.'
                );

                redirect('BillingController');

                return;
            }


            /*
             * Product name
             */
            $product_name =
                isset($product['product_name'])
                    ? $product['product_name']
                    : 'Product';


            /*
             * Stock
             *
             * Actual column:
             * stock_qty
             */
            $available_stock =
                isset($product['stock_qty'])
                    ? (float) $product['stock_qty']
                    : 0;


            if ($quantity > $available_stock) {

                $this->session->set_flashdata(
                    'error',
                    $product_name .
                    ' ka stock sirf ' .
                    $available_stock .
                    ' available hai.'
                );

                redirect('BillingController');

                return;
            }


            /*
             * Server-side subtotal
             */
            $subtotal =
                $quantity * $rate;

            $new_bill += $subtotal;


            /*
             * Clean item
             */
            $items[] = array(
                'product_id'   => $product_id,
                'product_name' => $product_name,
                'quantity'     => $quantity,
                'unit_price'   => $rate,
                'total_price'  => $subtotal
            );
        }


        /* =================================================
           9. PRODUCT VALIDATION
        ================================================= */

        if (
            empty($items) ||
            $new_bill <= 0
        ) {

            $this->session->set_flashdata(
                'error',
                'Please add at least one valid product.'
            );

            redirect('BillingController');

            return;
        }


        /* =================================================
           10. TOTAL CALCULATION
        ================================================= */

        /*
         * Old Due + New Bill
         */
        $total_payable =
            $old_due +
            $new_bill;


        /*
         * Payment cannot exceed total payable
         */
        if ($payment > $total_payable) {
            $payment = $total_payable;
        }


        /*
         * Remaining customer due
         */
        $remaining_due =
            $total_payable -
            $payment;


        if ($remaining_due < 0) {
            $remaining_due = 0;
        }


        /* =================================================
           11. START DATABASE TRANSACTION
        ================================================= */

        $this->db->trans_begin();


        /* =================================================
           12. CREATE INVOICE NUMBER
        ================================================= */

        /*
         * Temporary unique invoice number.
         * Actual invoice ID milne ke baad
         * INV-000001 type number update karenge.
         */

        $temporary_invoice_no =
            'INV-TEMP-' .
            date('YmdHis') .
            '-' .
            mt_rand(1000, 9999);


        /* =================================================
           13. INSERT INVOICE
        ================================================= */

        $invoice_data = array(
            'invoice_no'   => $temporary_invoice_no,
            'customer_id'  => $customer_id,
            'total_amount' => $new_bill,
            'old_due'      => $old_due,
            'paid_amount'  => $payment,
            'due_amount'   => $remaining_due,
            'billing_date' => $billing_date
        );


        $this->db->insert(
            'invoices',
            $invoice_data
        );


        if (
            $this->db->affected_rows() <= 0
        ) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Invoice could not be created.'
            );

            redirect('BillingController');

            return;
        }


        /*
         * Invoice ID
         */
        $invoice_id =
            (int) $this->db->insert_id();


        if ($invoice_id <= 0) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Invoice ID could not be generated.'
            );

            redirect('BillingController');

            return;
        }


        /* =================================================
           14. FINAL INVOICE NUMBER
        ================================================= */

        $final_invoice_no =
            'INV-' .
            str_pad(
                $invoice_id,
                6,
                '0',
                STR_PAD_LEFT
            );


        $this->db
            ->where(
                'id',
                $invoice_id
            )
            ->update(
                'invoices',
                array(
                    'invoice_no' =>
                        $final_invoice_no
                )
            );


        /* =================================================
           15. INSERT INVOICE ITEMS
               + REDUCE STOCK
        ================================================= */

        foreach (
            $items as $item
        ) {

            /* ---------------------------------------------
               Invoice Item
            --------------------------------------------- */

            $invoice_item = array(
                'invoice_id'   => $invoice_id,
                'product_id'   => $item['product_id'],
                'product_name' => $item['product_name'],
                'quantity'     => $item['quantity'],
                'unit_price'   => $item['unit_price'],
                'total_price'  => $item['total_price']
            );


            $this->db->insert(
                'invoice_items',
                $invoice_item
            );


            if (
                $this->db->affected_rows() <= 0
            ) {

                $this->db->trans_rollback();

                $this->session->set_flashdata(
                    'error',
                    'Invoice item could not be saved.'
                );

                redirect('BillingController');

                return;
            }


            /* ---------------------------------------------
               Reduce Stock
            --------------------------------------------- */

            $this->db
                ->set(
                    'stock_qty',
                    'stock_qty - ' .
                    (float)
                    $item['quantity'],
                    false
                )
                ->where(
                    'id',
                    (int)
                    $item['product_id']
                )
                ->where(
                    'stock_qty >=',
                    (float)
                    $item['quantity']
                )
                ->update(
                    'products'
                );


            /*
             * Agar stock update fail hua
             */
            if (
                $this->db->affected_rows() <= 0
            ) {

                $this->db->trans_rollback();

                $this->session->set_flashdata(
                    'error',
                    $item['product_name'] .
                    ' ka stock update nahi ho paya.'
                );

                redirect('BillingController');

                return;
            }
        }


        /* =================================================
           16. SAVE PAYMENT
        ================================================= */

        if ($payment > 0) {

            /*
             * Payments table
             */
            $payment_data = array(
                'invoice_id'   => $invoice_id,
                'customer_id'  => $customer_id,
                'paid_amount'  => $payment,
                'payment_date' => $billing_date,
                'payment_mode' => $payment_mode,
                'note'         =>
                    'Payment received during billing',
                'created_at'   =>
                    date('Y-m-d H:i:s')
            );


            $this->db->insert(
                'payments',
                $payment_data
            );


            if (
                $this->db->affected_rows() <= 0
            ) {

                $this->db->trans_rollback();

                $this->session->set_flashdata(
                    'error',
                    'Payment could not be saved.'
                );

                redirect('BillingController');

                return;
            }


            /*
             * Payment History
             */
            $history_data = array(
                'invoice_id'   => $invoice_id,
                'amount_paid'  => $payment,
                'payment_date' => $billing_date,
                'created_at'   =>
                    date('Y-m-d H:i:s')
            );


            $this->db->insert(
                'payment_history',
                $history_data
            );


            if (
                $this->db->affected_rows() <= 0
            ) {

                $this->db->trans_rollback();

                $this->session->set_flashdata(
                    'error',
                    'Payment history could not be saved.'
                );

                redirect('BillingController');

                return;
            }
        }


        /* =================================================
           17. UPDATE CUSTOMER DUE
        ================================================= */

        $this->db
            ->where(
                'id',
                $customer_id
            )
            ->update(
                'customers',
                array(
                    'total_due' =>
                        $remaining_due
                )
            );


        if (
            $this->db->affected_rows() < 0
        ) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Customer due could not be updated.'
            );

            redirect('BillingController');

            return;
        }


        /* =================================================
           18. CHECK TRANSACTION
        ================================================= */

        if (
            $this->db->trans_status() === false
        ) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Invoice save nahi ho paya. Database error.'
            );

            redirect('BillingController');

            return;
        }


        /* =================================================
           19. COMMIT
        ================================================= */

        $this->db->trans_commit();


        /* =================================================
           20. SUCCESS → FULL RECEIPT
        ================================================= */

        redirect(
            base_url(
                'BillingController/print_full_receipt/' .
                (int) $invoice_id
            )
        );

        exit;
    }


    /* =====================================================
       PRINT FULL RECEIPT
    ===================================================== */

    public function print_full_receipt($invoice_id = 0)
    {
        $invoice_id =
            (int) $invoice_id;


        if ($invoice_id <= 0) {

            show_error(
                'Invalid invoice ID.'
            );

            return;
        }


        /* =================================================
           1. INVOICE + CUSTOMER
        ================================================= */

        $this->db->select(
            'invoices.*, ' .
            'customers.name AS cust_name, ' .
            'customers.mobile, ' .
            'customers.address'
        );

        $this->db->from(
            'invoices'
        );

        $this->db->join(
            'customers',
            'customers.id = invoices.customer_id',
            'left'
        );

        $this->db->where(
            'invoices.id',
            $invoice_id
        );


        $invoice =
            $this->db
                ->get()
                ->row_array();


        if (!$invoice) {

            show_error(
                'Invoice record nahi mila.'
            );

            return;
        }


        /* =================================================
           2. INVOICE ITEMS
        ================================================= */

        $items =
            $this->db
                ->where(
                    'invoice_id',
                    $invoice_id
                )
                ->order_by(
                    'id',
                    'ASC'
                )
                ->get(
                    'invoice_items'
                )
                ->result_array();


        /* =================================================
           3. PAYMENT HISTORY
        ================================================= */

        $payment_history =
            $this->db
                ->where(
                    'invoice_id',
                    $invoice_id
                )
                ->order_by(
                    'id',
                    'ASC'
                )
                ->get(
                    'payment_history'
                )
                ->result_array();


        /* =================================================
           4. ACTUAL PAYMENT
        ================================================= */

        $payment_row =
            $this->db
                ->select_sum(
                    'paid_amount',
                    'total_paid'
                )
                ->where(
                    'invoice_id',
                    $invoice_id
                )
                ->get(
                    'payments'
                )
                ->row_array();


        $total_paid =
            isset(
                $payment_row['total_paid']
            )
                ? (float)
                    $payment_row['total_paid']
                : 0;


        /*
         * Fallback to invoice paid_amount
         */
        if ($total_paid <= 0) {

            $total_paid =
                isset(
                    $invoice['paid_amount']
                )
                    ? (float)
                        $invoice['paid_amount']
                    : 0;
        }


        /* =================================================
           5. CALCULATIONS
        ================================================= */

        $old_due =
            isset(
                $invoice['old_due']
            )
                ? (float)
                    $invoice['old_due']
                : 0;


        $new_bill =
            isset(
                $invoice['total_amount']
            )
                ? (float)
                    $invoice['total_amount']
                : 0;


        $total_payable =
            $old_due +
            $new_bill;


        $remaining_due =
            $total_payable -
            $total_paid;


        if ($remaining_due < 0) {
            $remaining_due = 0;
        }


        /*
         * Current invoice payment
         */
        $paid_today =
            isset(
                $invoice['paid_amount']
            )
                ? (float)
                    $invoice['paid_amount']
                : 0;


        /* =================================================
           6. VIEW DATA
        ================================================= */

        $data['invoice'] =
            $invoice;

        $data['items'] =
            $items;

        /*
         * Compatibility:
         * old print_invoice.php
         */
        $data['payments'] =
            $payment_history;

        $data['payment_history'] =
            $payment_history;


        /*
         * Amounts
         */
        $data['old_due'] =
            $old_due;

        $data['new_bill'] =
            $new_bill;

        $data['total_payable'] =
            $total_payable;

        $data['paid_today'] =
            $paid_today;

        $data['total_paid'] =
            $total_paid;

        $data['remaining'] =
            $remaining_due;


        /* =================================================
           7. SHOP SETTINGS
        ================================================= */

        $data['shop'] =
            $this->db
                ->where(
                    'id',
                    1
                )
                ->get(
                    'settings'
                )
                ->row_array();


        /* =================================================
           8. LOAD RECEIPT
        ================================================= */

        $this->load->view(
            'print_invoice',
            $data
        );
    }


    /* =====================================================
       ALL INVOICES
    ===================================================== */

    public function invoices()
    {
        $data['page_title'] =
            'All Invoices';


        $data['invoices'] =
            $this->db
                ->select(
                    'invoices.*, ' .
                    'customers.name as customer_name'
                )
                ->from(
                    'invoices'
                )
                ->join(
                    'customers',
                    'customers.id = invoices.customer_id',
                    'left'
                )
                ->order_by(
                    'invoices.id',
                    'DESC'
                )
                ->get()
                ->result_array();


        $data['sidebar_notifications'] =
            $this->get_sidebar_notifications();


        $this->load->view(
            'layout/header'
        );

        $this->load->view(
            'layout/sidebar',
            $data
        );

        $this->load->view(
            'invoices',
            $data
        );

        $this->load->view(
            'layout/footer'
        );
    }


    /* =====================================================
       JSON RESPONSE HELPER
    ===================================================== */

    private function json_response($data)
    {
        $this->output
            ->set_content_type(
                'application/json'
            )
            ->set_output(
                json_encode($data)
            );
    }































//All invoice //


    public function all_invoices()
{
    $invoices = $this->db
        ->select('
            invoices.*,
            customers.name AS customer_name,
            customers.mobile AS customer_mobile
        ')
        ->from('invoices')
        ->join(
            'customers',
            'customers.id = invoices.customer_id',
            'left'
        )
        ->order_by('invoices.id', 'DESC')
        ->get()
        ->result_array();


    foreach ($invoices as &$invoice) {

        $payment_row = $this->db
            ->select_sum('paid_amount', 'total_paid')
            ->where(
                'invoice_id',
                $invoice['id']
            )
            ->get('payments')
            ->row_array();


        $invoice['total_paid'] =
            (float)($payment_row['total_paid'] ?? 0);


        $invoice['bill_amount'] =
            (float)($invoice['total_amount'] ?? 0);


        $invoice['old_due'] =
            (float)($invoice['old_due'] ?? 0);


        $invoice['invoice_due'] =
            (float)($invoice['due_amount'] ?? 0);
    }

    unset($invoice);


    $data['invoices'] = $invoices;


    $this->load->view(
        'layout/header'
    );

    $this->load->view(
        'layout/sidebar'
    );

    $this->load->view(
        'all_invoices',
        $data
    );

    $this->load->view(
        'layout/footer'
    );
}


public function view_invoice($id)
{
    $id = (int)$id;

    if ($id <= 0) {
        redirect('BillingController/all_invoices');
        return;
    }

    $invoice = $this->db
        ->select('
            invoices.*,
            customers.name AS customer_name,
            customers.mobile AS customer_mobile,
            customers.address AS customer_address
        ')
        ->from('invoices')
        ->join(
            'customers',
            'customers.id = invoices.customer_id',
            'left'
        )
        ->where('invoices.id', $id)
        ->get()
        ->row_array();

    if (!$invoice) {
        $this->session->set_flashdata(
            'error',
            'Invoice not found.'
        );

        redirect('BillingController/all_invoices');
        return;
    }

   $items = $this->db
    ->select('invoice_items.*')
    ->from('invoice_items')
    ->where('invoice_items.invoice_id', $id)
    ->order_by('invoice_items.id', 'ASC')
    ->get()
    ->result_array();

    $payments = $this->db
        ->where('invoice_id', $id)
        ->order_by('id', 'DESC')
        ->get('payments')
        ->result_array();

    $data['invoice'] = $invoice;
    $data['items'] = $items;
    $data['payments'] = $payments;

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar');
    $this->load->view('invoice_view', $data);
    $this->load->view('layout/footer');
}
}












