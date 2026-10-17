<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ManagementController extends CI_Controller
{
    public function __construct() {
        parent::__construct();
    }

    // Sidebar notifications fetch karne ka common function (Last 30 days ke payments)[cite: 8]
    private function get_sidebar_notifications() {
        $this->db->select('payments.*, customers.name as cust_name');
        $this->db->from('payments');
        $this->db->join('customers', 'customers.id = payments.customer_id', 'left');
        $this->db->where('payments.created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')));
        $this->db->order_by('payments.id', 'DESC');
        $this->db->limit(10);
        return $this->db->get()->result_array();
    }

    public function dashboard()
    {
        $data['page_title'] = "Dashboard Overview";
        
        // 1. Aaj ki sale (Today's Sale) date-wise[cite: 8]
        $today_sale = $this->db->select_sum('paid_amount')
                               ->where('payment_date', date('Y-m-d'))
                               ->get('payments')->row()->paid_amount;
        $data['today_sale'] = $today_sale ?: 0;

        // 2. Total Udhaari (Total Due)[cite: 8]
        $total_due = $this->db->select_sum('due_amount')->get('invoices')->row()->due_amount;
        $data['total_due'] = $total_due ?: 0;

        // 3. Low Stock Count[cite: 8]
        $data['low_stock_count'] = $this->db->where('stock_qty <= min_stock_alert')
                                            ->from('products')
                                            ->count_all_results();

        // 4. Low Stock Items List[cite: 8]
        $this->db->where('stock_qty <= min_stock_alert');
        $data['low_stock_items'] = $this->db->get('products')->result_array();

        // 5. Tiles aur Marble ka total stock[cite: 8]
        $data['total_tiles'] = $this->db->select_sum('stock_qty')
                                        ->where('category', 'Tiles')
                                        ->get('products')->row()->stock_qty ?: 0;

        $data['total_marble'] = $this->db->select_sum('stock_qty')
                                         ->where('category', 'Marble')
                                         ->get('products')->row()->stock_qty ?: 0;
                                         
        // 6. Recent Invoices[cite: 8]
        $this->db->select('invoices.*, customers.name as cust_name');
        $this->db->from('invoices');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->order_by('invoices.id', 'DESC');
        $this->db->limit(5);
        $data['recent_invoices'] = $this->db->get()->result_array();

        // 7. Sidebar Notifications data pass karna[cite: 8]
        $data['sidebar_notifications'] = $this->get_sidebar_notifications();

        // View load karein[cite: 8]
        $this->load->view('layout/header');
        $this->load->view('layout/sidebar', $data);
        $this->load->view('dashboard', $data);
        $this->load->view('layout/footer');
    }


   

    
   
      
        
  



   

        public function reports()
    {
        $data['page_title'] = "Sales & Dues Reports";
        
        if ($this->input->get('from_date')) {
            $from = $this->input->get('from_date');
            $to = $this->input->get('to_date');
            $this->session->set_userdata('report_from', $from);
            $this->session->set_userdata('report_to', $to);
        } else {
            $from = $this->session->userdata('report_from') ?: date('Y-m-d');
            $to = $this->session->userdata('report_to') ?: date('Y-m-d');
        }

        $this->db->select('invoices.*, customers.name as cust_name');
        $this->db->from('invoices');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        
        $this->db->where('DATE(billing_date) >=', $from);
        $this->db->where('DATE(billing_date) <=', $to);
        
        $this->db->order_by('invoices.id', 'DESC');
        $data['sales'] = $this->db->get()->result_array();

        $data['total_sales_amount'] = array_sum(array_column($data['sales'], 'total_amount'));
        $data['total_received'] = array_sum(array_column($data['sales'], 'paid_amount'));
        $data['total_pending'] = array_sum(array_column($data['sales'], 'due_amount'));

        $data['current_from'] = $from;
        $data['current_to'] = $to;
        $data['sidebar_notifications'] = $this->get_sidebar_notifications();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar', $data);
        $this->load->view('reports', $data); 
        $this->load->view('layout/footer');
    }





  

    public function settings() {
        $data['page_title'] = "Business Settings";
        $data['shop'] = $this->db->get_where('settings', ['id' => 1])->row();
        $data['sidebar_notifications'] = $this->get_sidebar_notifications();

        if ($this->input->post()) {
            $update_data = [
                'shop_name'      => $this->input->post('shop_name'),
                'mobile'         => $this->input->post('mobile'),
                'address'        => $this->input->post('address'),
                'gst_no'         => $this->input->post('gst_no'),
                'invoice_prefix' => $this->input->post('invoice_prefix')
            ];
            
            $this->db->where('id', 1);
            $this->db->update('settings', $update_data);
            
            $this->session->set_flashdata('success', 'Settings Updated!');
            redirect('ManagementController/settings');
        }

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar', $data);
        $this->load->view('settings_view', $data);
        $this->load->view('layout/footer');
    }

    public function share_on_whatsapp($id) {
        $invoice = $this->db->get_where('invoices', ['id' => $id])->row_array();
        $customer = $this->db->get_where('customers', ['id' => $invoice['customer_id']])->row_array();
        $shop = $this->db->get_where('settings', ['id' => 1])->row();

        $message = "*{$shop->shop_name}*" . PHP_EOL;
        $message .= "--------------------------" . PHP_EOL;
        $message .= "*Invoice No:* #" . $invoice['invoice_no'] . PHP_EOL;
        $message .= "*Customer:* " . $customer['name'] . PHP_EOL;
        $message .= "*Total Amount:* ₹" . number_format($invoice['total_amount'], 2) . PHP_EOL;
        $message .= "*Due Amount:* ₹" . number_format($invoice['due_amount'], 2) . PHP_EOL;
        $message .= "--------------------------" . PHP_EOL;
        $message .= "Thank you for shopping with us!";

        $final_message = urlencode($message);
        redirect("https://wa.me/?text={$final_message}");
    }













public function get_customer_summary($id)
{
    $id = (int) $id;

    if ($id <= 0) {
        echo json_encode([
            'status' => false,
            'due' => 0,
            'collected' => 0
        ]);
        return;
    }

    $customer = $this->db
        ->where('id', $id)
        ->get('customers')
        ->row_array();

    if (!$customer) {
        echo json_encode([
            'status' => false,
            'due' => 0,
            'collected' => 0
        ]);
        return;
    }

    $current_due = isset($customer['total_due'])
        ? (float) $customer['total_due']
        : 0;

    echo json_encode([
        'status' => true,
        'due' => $current_due,
        'collected' => 0
    ]);
}


/* =========================================================
   PRINT INVOICE
========================================================= */

public function print_invoice($invoice_id)
{
    $invoice_id = (int) $invoice_id;

    if ($invoice_id <= 0) {
        show_error('Invalid invoice ID.');
        return;
    }

    // Invoice + Customer
    $invoice = $this->db
        ->select('
            invoices.*,
            customers.name AS cust_name,
            customers.mobile,
            customers.address
        ')
        ->from('invoices')
        ->join(
            'customers',
            'customers.id = invoices.customer_id',
            'left'
        )
        ->where('invoices.id', $invoice_id)
        ->get()
        ->row_array();

    if (!$invoice) {
        show_error('Invoice record nahi mila.');
        return;
    }

    // Invoice Items
    $items = $this->db
        ->where('invoice_id', $invoice_id)
        ->order_by('id', 'ASC')
        ->get('invoice_items')
        ->result_array();

    // Total payments
    $payment_row = $this->db
        ->select_sum('paid_amount', 'total_paid')
        ->where('invoice_id', $invoice_id)
        ->get('payments')
        ->row_array();

    $total_paid = isset($payment_row['total_paid'])
        ? (float) $payment_row['total_paid']
        : 0;

    // Fallback
    if ($total_paid <= 0) {
        $total_paid = isset($invoice['paid_amount'])
            ? (float) $invoice['paid_amount']
            : 0;
    }

    $old_due = isset($invoice['old_due'])
        ? (float) $invoice['old_due']
        : 0;

    $new_bill = isset($invoice['total_amount'])
        ? (float) $invoice['total_amount']
        : 0;

    $total_payable = $old_due + $new_bill;

    $remaining_due = $total_payable - $total_paid;

    if ($remaining_due < 0) {
        $remaining_due = 0;
    }

    $data = [
        'invoice'       => $invoice,
        'items'         => $items,
        'old_due'       => $old_due,
        'new_bill'      => $new_bill,
        'total_payable' => $total_payable,
        'total_paid'    => $total_paid,
        'remaining'     => $remaining_due
    ];

    $this->load->view('print_invoice', $data);
}









public function daily_entries()
{
    $data['page_title'] = 'Daily Entries';

    $from_date = $this->input->get('from_date', TRUE);
    $to_date   = $this->input->get('to_date', TRUE);

    if (!$from_date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $from_date)) {
        $from_date = date('Y-m-d');
    }

    if (!$to_date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to_date)) {
        $to_date = date('Y-m-d');
    }

    if ($from_date > $to_date) {
        $temp = $from_date;
        $from_date = $to_date;
        $to_date = $temp;
    }

    $data['from_date'] = $from_date;
    $data['to_date'] = $to_date;

    // Sales summary
    $sales = $this->db
        ->select_sum('total_amount', 'total_sales')
        ->where('billing_date >=', $from_date)
        ->where('billing_date <=', $to_date)
        ->get('invoices')
        ->row_array();

    $data['total_sales'] = (float) ($sales['total_sales'] ?? 0);

    // Payment summary
    $payments = $this->db
        ->select_sum('paid_amount', 'total_received')
        ->where('payment_date >=', $from_date)
        ->where('payment_date <=', $to_date)
        ->get('payments')
        ->row_array();

    $data['total_received'] = (float) ($payments['total_received'] ?? 0);

    // Payment transaction history
    $data['entries'] = $this->db
        ->select('
            payments.id,
            payments.invoice_id,
            payments.customer_id,
            payments.paid_amount,
            payments.payment_date,
            payments.payment_mode,
            payments.note,
            invoices.invoice_no,
            invoices.total_amount AS invoice_amount,
            customers.name AS customer_name,
            customers.mobile AS customer_mobile
        ')
        ->from('payments')
        ->join('customers', 'customers.id = payments.customer_id', 'left')
        ->join('invoices', 'invoices.id = payments.invoice_id', 'left')
        ->where('payments.payment_date >=', $from_date)
        ->where('payments.payment_date <=', $to_date)
        ->order_by('payments.payment_date', 'DESC')
        ->order_by('payments.id', 'DESC')
        ->get()
        ->result_array();



        
// Calculate current remaining due for each customer
foreach ($data['entries'] as &$entry) {
    $customer_id = (int) $entry['customer_id'];

    $billing = $this->db
        ->select_sum('total_amount', 'total_billing')
        ->where('customer_id', $customer_id)
        ->get('invoices')
        ->row_array();

    $received = $this->db
        ->select_sum('paid_amount', 'total_received')
        ->where('customer_id', $customer_id)
        ->get('payments')
        ->row_array();

    $total_billing = (float) ($billing['total_billing'] ?? 0);
    $total_received = (float) ($received['total_received'] ?? 0);

    $entry['remaining_due'] = max(
        0,
        $total_billing - $total_received
    );
}
unset($entry);


    $data['sidebar_notifications'] = array();

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar', $data);
    $this->load->view('daily_entries', $data);
    $this->load->view('layout/footer');
}












public function party_ledger()
{
    $data['page_title'] = 'Party Ledger';

    // Selected customer and date filters
    $customer_id = (int) $this->input->get('customer_id');
    $from_date = trim((string) $this->input->get('from_date'));
    $to_date = trim((string) $this->input->get('to_date'));

    // Validate dates
    if ($from_date !== '' &&
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $from_date)) {
        $from_date = '';
    }

    if ($to_date !== '' &&
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to_date)) {
        $to_date = '';
    }

    $data['selected_customer'] = $customer_id;
    $data['from_date'] = $from_date;
    $data['to_date'] = $to_date;
    $data['ledger'] = array();
    $data['total_billing'] = 0;
    $data['total_paid'] = 0;
    $data['current_due'] = 0;

    // Customer dropdown
    $data['customers'] = $this->db
        ->select('id, name, mobile')
        ->from('customers')
        ->order_by('name', 'ASC')
        ->get()
        ->result_array();

    if ($customer_id > 0) {

        // Verify that the customer exists
        $customer = $this->db
            ->where('id', $customer_id)
            ->get('customers')
            ->row_array();

        if ($customer) {

            /*
             * Use payments as the single payment source.
             * Do not also add payment_history because it duplicates
             * the same transactions.
             */

            // All-time billing total
            $billing = $this->db
                ->select('COALESCE(SUM(total_amount), 0) AS total', false)
                ->where('customer_id', $customer_id)
                ->get('invoices')
                ->row_array();

            // All-time payments total
            $payments = $this->db
                ->select('COALESCE(SUM(paid_amount), 0) AS total', false)
                ->where('customer_id', $customer_id)
                ->get('payments')
                ->row_array();

            $data['total_billing'] = (float) ($billing['total'] ?? 0);
            $data['total_paid'] = (float) ($payments['total'] ?? 0);

            // Calculate due using the existing invoice due amounts
            // so that old_due is not counted twice.
            $due = $this->db
                ->select('COALESCE(SUM(due_amount), 0) AS total', false)
                ->where('customer_id', $customer_id)
                ->get('invoices')
                ->row_array();

            $data['current_due'] = max(0, (float) ($due['total'] ?? 0));

            // Get invoices for this customer
            $invoice_query = $this->db
                ->select('id, invoice_no, total_amount, billing_date')
                ->from('invoices')
                ->where('customer_id', $customer_id);

            if ($from_date !== '') {
                $invoice_query->where('billing_date >=', $from_date);
            }

            if ($to_date !== '') {
                $invoice_query->where('billing_date <=', $to_date);
            }

            $invoices = $invoice_query
                ->order_by('billing_date', 'ASC')
                ->order_by('id', 'ASC')
                ->get()
                ->result_array();

            // Add one bill entry per invoice with its product details
            foreach ($invoices as $invoice) {

                $items = $this->db
                    ->select('product_name, quantity, unit_price, total_price')
                    ->from('invoice_items')
                    ->where('invoice_id', $invoice['id'])
                    ->get()
                    ->result_array();

                $product_lines = array();

                foreach ($items as $item) {
                    $product_lines[] =
                        $item['product_name'] .
                        ' (Qty: ' . $item['quantity'] .
                        ' × ₹' . number_format((float) $item['unit_price'], 2) .
                        ' = ₹' . number_format((float) $item['total_price'], 2) . ')';
                }

                $data['ledger'][] = array(
                    'sort_date' => $invoice['billing_date'],
                    'sort_id' => (int) $invoice['id'],
                    'type' => 'Sale',
                    'transaction_date' => $invoice['billing_date'],
                    'reference' => $invoice['invoice_no'],
                    'details' => !empty($product_lines)
                        ? implode("\n", $product_lines)
                        : 'Invoice items not found',
                    'payment_mode' => '-',
                    'debit' => (float) $invoice['total_amount'],
                    'credit' => 0,
                    'invoice_id' => (int) $invoice['id']
                );
            }

            // Get payments ONLY from payments table
            $payment_query = $this->db
                ->select('id, invoice_id, paid_amount, payment_date, payment_mode, note')
                ->from('payments')
                ->where('customer_id', $customer_id);

            if ($from_date !== '') {
                $payment_query->where('payment_date >=', $from_date);
            }

            if ($to_date !== '') {
                $payment_query->where('payment_date <=', $to_date);
            }

            $payment_rows = $payment_query
                ->order_by('payment_date', 'ASC')
                ->order_by('id', 'ASC')
                ->get()
                ->result_array();

            foreach ($payment_rows as $payment) {
                $data['ledger'][] = array(
                    'sort_date' => $payment['payment_date'],
                    'sort_id' => (int) $payment['id'],
                    'type' => 'Payment',
                    'transaction_date' => $payment['payment_date'],
                    'reference' => !empty($payment['invoice_id'])
                        ? 'Invoice #' . $payment['invoice_id']
                        : 'Payment #' . $payment['id'],
                    'details' => $payment['note'] ?? '',
                    'payment_mode' => $payment['payment_mode'] ?? '-',
                    'debit' => 0,
                    'credit' => (float) $payment['paid_amount'],
                    'invoice_id' => (int) ($payment['invoice_id'] ?? 0)
                );
            }

            // Combine bills and payments chronologically
            usort($data['ledger'], function ($a, $b) {
                $date_compare = strcmp($a['sort_date'], $b['sort_date']);

                if ($date_compare !== 0) {
                    return $date_compare;
                }

                // For the same date, show bills before payments
                if ($a['type'] !== $b['type']) {
                    return $a['type'] === 'Sale' ? -1 : 1;
                }

                return $a['sort_id'] <=> $b['sort_id'];
            });

            // Running balance
            $balance = 0;

            foreach ($data['ledger'] as &$entry) {
                $balance += $entry['debit'] - $entry['credit'];
                $entry['balance'] = $balance;
            }
            unset($entry);
        }
    }

    $data['sidebar_notifications'] = array();

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar', $data);
    $this->load->view('party_ledger', $data);
    $this->load->view('layout/footer');
}











public function suppliers()
{
    $data['page_title'] = 'Vendor Management';

    $data['suppliers'] = $this->db
        ->order_by('id', 'DESC')
        ->get('suppliers')
        ->result_array();

    $data['success'] = $this->session->flashdata('success');
    $data['error'] = $this->session->flashdata('error');

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar', $data);
    $this->load->view('suppliers', $data);
    $this->load->view('layout/footer');
}

public function save_supplier()
{
    if ($this->input->method(TRUE) !== 'POST') {
        show_404();
        return;
    }

    $name = trim((string) $this->input->post('name', TRUE));
    $mobile = trim((string) $this->input->post('mobile', TRUE));
    $address = trim((string) $this->input->post('address', TRUE));
    $opening_balance = $this->input->post('opening_balance');

    if (
        $name === '' ||
        $mobile === '' ||
        !is_numeric($opening_balance) ||
        (float) $opening_balance < 0
    ) {
        $this->session->set_flashdata(
            'error',
            'Please enter valid supplier details.'
        );

        redirect('ManagementController/suppliers');
        return;
    }

    $this->db->insert('suppliers', [
        'name' => $name,
        'mobile' => $mobile,
        'address' => $address,
        'opening_balance' => (float) $opening_balance
    ]);

    if ($this->db->affected_rows() > 0) {
        $this->session->set_flashdata(
            'success',
            'Supplier added successfully.'
        );
    } else {
        $this->session->set_flashdata(
            'error',
            'Supplier could not be saved.'
        );
    }

    redirect('ManagementController/suppliers');
}

























public function purchases()
{
    $data['page_title'] = 'Purchase Management';

    // Suppliers list
    $data['suppliers'] = $this->db
        ->order_by('name', 'ASC')
        ->get('suppliers')
        ->result_array();

    // Products list
    $data['products'] = $this->db
        ->order_by('product_name', 'ASC')
        ->get('products')
        ->result_array();


        
$data['supplier_dues'] = $this->db
    ->select('supplier_id, SUM(due_amount) AS total_due')
    ->from('purchase_invoices')
    ->group_by('supplier_id')
    ->get()
    ->result_array();


    // Purchase history
    $data['purchases'] = $this->db
        ->select('pi.*, s.name AS supplier_name')
        ->from('purchase_invoices pi')
        ->join('suppliers s', 's.id = pi.supplier_id')
        ->order_by('pi.id', 'DESC')
        ->get()
        ->result_array();

    // Supplier-wise old purchase totals
    $supplier_totals = $this->db
        ->select('
            supplier_id,
            COALESCE(SUM(total_amount), 0) AS total_bill,
            COALESCE(SUM(paid_amount), 0) AS total_paid,
            COALESCE(SUM(due_amount), 0) AS old_due
        ', false)
        ->from('purchase_invoices')
        ->group_by('supplier_id')
        ->get()
        ->result_array();

    $data['supplier_totals'] = array();

    foreach ($supplier_totals as $row) {
        $data['supplier_totals'][(int) $row['supplier_id']] = array(
            'total_bill' => (float) $row['total_bill'],
            'total_paid' => (float) $row['total_paid'],
            'old_due'    => (float) $row['old_due']
        );
    }

    $data['success'] = $this->session->flashdata('success');
    $data['error']   = $this->session->flashdata('error');

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar', $data);
    $this->load->view('purchases', $data);
    $this->load->view('layout/footer');
}




public function save_purchase()
{
    if ($this->input->method(TRUE) !== 'POST') {
        show_404();
        return;
    }

    $supplier_id  = (int) $this->input->post('supplier_id');
    $purchase_date = trim((string) $this->input->post('purchase_date', TRUE));
    $product_ids   = $this->input->post('product_id');
    $quantities    = $this->input->post('quantity');
    $rates         = $this->input->post('unit_price');
    $paid_amount   = $this->input->post('paid_amount');
    $payment_mode  = trim((string) $this->input->post('payment_mode', TRUE));
    $note          = trim((string) $this->input->post('note', TRUE));

    // New product fields
    $new_names      = $this->input->post('new_product_name');
    $new_categories = $this->input->post('new_product_category');
    $new_units      = $this->input->post('new_product_unit');
    $new_brands     = $this->input->post('new_product_brand');
    $new_sizes      = $this->input->post('new_product_size');

    $valid_date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $purchase_date)
        && strtotime($purchase_date) !== false
        && date('Y-m-d', strtotime($purchase_date)) === $purchase_date;

    if (
        $supplier_id < 1 ||
        !$valid_date ||
        !is_array($product_ids) ||
        !is_array($quantities) ||
        !is_array($rates) ||
        !is_numeric($paid_amount) ||
        (float) $paid_amount < 0
    ) {
        $this->session->set_flashdata('error', 'Please enter valid purchase details.');
        redirect('ManagementController/purchases');
        return;
    }

    if (!$this->db->where('id', $supplier_id)->get('suppliers')->row()) {
        $this->session->set_flashdata('error', 'Supplier not found.');
        redirect('ManagementController/purchases');
        return;
    }

    $allowed_categories = array('Tiles', 'Marble', 'Hardware', 'Stationary');
    $allowed_units = array('Piece', 'Box', 'Sq.Ft', 'Sq.Mtr', 'Kg', 'Bag', 'Packet', 'Roll', 'Length');

    $rows = array();
    $total = 0;

    foreach ($product_ids as $i => $posted_id) {
        $product_id = (int) $posted_id;
        $qty = $quantities[$i] ?? null;
        $rate = $rates[$i] ?? null;

        if (
            !is_numeric($qty) ||
            (float) $qty <= 0 ||
            floor((float) $qty) != (float) $qty ||
            !is_numeric($rate) ||
            (float) $rate < 0
        ) {
            $this->session->set_flashdata('error', 'Enter a valid quantity and rate for every item.');
            redirect('ManagementController/purchases');
            return;
        }

        $qty = (int) $qty;
        $rate = round((float) $rate, 2);

        if ($product_id > 0) {
            $product = $this->db
                ->where('id', $product_id)
                ->get('products')
                ->row_array();

            if (!$product) {
                $this->session->set_flashdata('error', 'A selected product was not found.');
                redirect('ManagementController/purchases');
                return;
            }

            $rows[] = array(
                'type' => 'existing',
                'product_id' => $product_id,
                'product_name' => $product['product_name'],
                'quantity' => $qty,
                'unit_price' => $rate,
                'total_price' => round($qty * $rate, 2)
            );
        } else {
            $name = trim((string) ($new_names[$i] ?? ''));
            $category = trim((string) ($new_categories[$i] ?? ''));
            $unit = trim((string) ($new_units[$i] ?? 'Piece'));
            $brand = trim((string) ($new_brands[$i] ?? ''));
            $size = trim((string) ($new_sizes[$i] ?? ''));

            if (
                $name === '' ||
                !in_array($category, $allowed_categories, true) ||
                !in_array($unit, $allowed_units, true)
            ) {
                $this->session->set_flashdata(
                    'error',
                    'For a new product, enter its name, valid category and unit.'
                );
                redirect('ManagementController/purchases');
                return;
            }

            // Reuse an existing product with the same name, ignoring case.
            $same_product = $this->db
                ->where('LOWER(product_name) = ' . $this->db->escape(strtolower($name)), null, false)
                ->get('products')
                ->row_array();

            if ($same_product) {
                $rows[] = array(
                    'type' => 'existing',
                    'product_id' => (int) $same_product['id'],
                    'product_name' => $same_product['product_name'],
                    'quantity' => $qty,
                    'unit_price' => $rate,
                    'total_price' => round($qty * $rate, 2)
                );
            } else {
                $rows[] = array(
                    'type' => 'new',
                    'product_id' => 0,
                    'product_name' => $name,
                    'category' => $category,
                    'stock_unit' => $unit,
                    'brand' => $brand,
                    'size' => $size,
                    'quantity' => $qty,
                    'unit_price' => $rate,
                    'total_price' => round($qty * $rate, 2)
                );
            }
        }

        $total = round($total + round($qty * $rate, 2), 2);
    }

    if (empty($rows) || (float) $paid_amount > $total) {
        $this->session->set_flashdata(
            'error',
            'Add purchase items and ensure paid amount does not exceed the total.'
        );
        redirect('ManagementController/purchases');
        return;
    }

    $paid_amount = round((float) $paid_amount, 2);
    $due = round($total - $paid_amount, 2);
    $purchase_no = 'PUR-' . date('YmdHis') . '-' . mt_rand(100, 999);

    $this->db->trans_begin();

    $this->db->insert('purchase_invoices', array(
        'purchase_no' => $purchase_no,
        'supplier_id' => $supplier_id,
        'purchase_date' => $purchase_date,
        'total_amount' => $total,
        'paid_amount' => $paid_amount,
        'due_amount' => $due,
        'note' => $note
    ));

    $purchase_id = $this->db->insert_id();

    if ($purchase_id) {
        foreach ($rows as $item) {
            $product_id = (int) $item['product_id'];

            if ($item['type'] === 'new') {
                $this->db->insert('products', array(
                    'category' => $item['category'],
                    'product_name' => $item['product_name'],
                    'brand' => $item['brand'],
                    'size' => $item['size'],
                    'stock_qty' => 0,
                    'min_stock_alert' => 5,
                    'unit_price' => $item['unit_price'],
                    'stock_unit' => $item['stock_unit']
                ));

                $product_id = (int) $this->db->insert_id();

                if (!$product_id) {
                    break;
                }
            }

            $this->db->insert('purchase_items', array(
                'purchase_id' => $purchase_id,
                'product_id' => $product_id,
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price']
            ));

            $this->db->set(
                'stock_qty',
                'stock_qty + ' . (int) $item['quantity'],
                false
            );
            $this->db->where('id', $product_id);
            $this->db->update('products');
        }

        if ($paid_amount > 0 && $this->db->trans_status() !== false) {
            $this->db->insert('supplier_payments', array(
                'supplier_id' => $supplier_id,
                'purchase_id' => $purchase_id,
                'amount' => $paid_amount,
                'payment_date' => $purchase_date,
                'payment_mode' => $payment_mode ?: 'Cash',
                'note' => 'Initial payment for ' . $purchase_no
            ));
        }
    }

    
if ($this->db->trans_status() === false || !$purchase_id) {
    $db_error = $this->db->error();

    $this->db->trans_rollback();

    log_message(
        'error',
        'Purchase save failed. Database error: ' .
        print_r($db_error, true)
    );

    $this->session->set_flashdata(
        'error',
        'Purchase save failed. Check application/logs for the database error.'
    );
} else {
    $this->db->trans_commit();

    $this->session->set_flashdata(
        'success',
        'Purchase saved successfully. Inventory updated. Purchase No: ' . $purchase_no
    );
}

redirect('ManagementController/purchases');
return;

}












public function purchase_items()
{
    $data['page_title'] = 'All Products Purchase';

    $data['items'] = $this->db
        ->select('
            pi.id AS purchase_item_id,
            pi.purchase_id,
            pi.product_id,
            pi.product_name,
            pi.quantity,
            pi.unit_price,
            pi.total_price,
            p.purchase_no,
            p.purchase_date,
            s.name AS supplier_name
        ')
        ->from('purchase_items pi')
        ->join('purchase_invoices p', 'p.id = pi.purchase_id', 'left')
        ->join('suppliers s', 's.id = p.supplier_id', 'left')
        ->order_by('pi.id', 'DESC')
        ->get()
        ->result_array();

    $data['success'] = $this->session->flashdata('success');
    $data['error'] = $this->session->flashdata('error');

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar', $data);
    $this->load->view('purchase_items', $data);
    $this->load->view('layout/footer');
}











/* =========================================================
   PURCHASE HISTORY
========================================================= */
public function purchase_history()
{
    $data['page_title'] = 'Purchase History';

    $data['purchases'] = $this->db
        ->select('pi.*, s.name AS supplier_name, s.mobile AS supplier_mobile')
        ->from('purchase_invoices pi')
        ->join('suppliers s', 's.id = pi.supplier_id', 'left')
        ->order_by('pi.id', 'DESC')
        ->get()
        ->result_array();

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar', $data);
    $this->load->view('purchase_history', $data);
    $this->load->view('layout/footer');
}


/* =========================================================
   PAY SUPPLIER DUE
========================================================= */

public function pay_supplier_due()
{
    $data['page_title'] = 'Pay Supplier Due';

    // Load all suppliers.
    $data['suppliers'] = $this->db
        ->order_by('name', 'ASC')
        ->get('suppliers')
        ->result_array();

    $data['success'] = $this->session->flashdata('success');
    $data['error']   = $this->session->flashdata('error');

    // IMPORTANT:
    // Load ALL purchase invoices, including fully paid invoices,
    // so Total Bill and Total Paid summaries are calculated correctly.
    $data['dues'] = $this->db
        ->select('
            pi.id,
            pi.purchase_no,
            pi.supplier_id,
            pi.purchase_date,
            pi.total_amount,
            pi.paid_amount,
            pi.due_amount,
            s.name AS supplier_name
        ')
        ->from('purchase_invoices pi')
        ->join('suppliers s', 's.id = pi.supplier_id', 'left')
        ->order_by('pi.purchase_date', 'ASC')
        ->order_by('pi.id', 'ASC')
        ->get()
        ->result_array();

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar', $data);
    $this->load->view('pay_supplier_due', $data);
    $this->load->view('layout/footer');
}



/* =========================================================
   SAVE SUPPLIER DUE PAYMENT
========================================================= */

public function save_supplier_payment()
{
    if ($this->input->method(TRUE) !== 'POST') {
        show_404();
        return;
    }

    $supplier_id = (int) $this->input->post('supplier_id');
    $purchase_id = (int) $this->input->post('purchase_id');
    $amount = round((float) $this->input->post('amount'), 2);
    $payment_date = trim((string) $this->input->post('payment_date'));
    $payment_mode = trim((string) $this->input->post('payment_mode'));
    $note = trim((string) $this->input->post('note', TRUE));

    $allowed_modes = array('Cash', 'UPI', 'Bank', 'Cheque');

    if (
        $supplier_id <= 0 ||
        $purchase_id < 0 ||
        $amount <= 0 ||
        !in_array($payment_mode, $allowed_modes, true) ||
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $payment_date) ||
        date('Y-m-d', strtotime($payment_date)) !== $payment_date
    ) {
        $this->session->set_flashdata(
            'error',
            'Please enter valid payment details.'
        );
        redirect('ManagementController/pay_supplier_due');
        return;
    }

    // Validate supplier.
    $supplier = $this->db
        ->where('id', $supplier_id)
        ->get('suppliers')
        ->row_array();

    if (!$supplier) {
        $this->session->set_flashdata('error', 'Supplier not found.');
        redirect('ManagementController/pay_supplier_due');
        return;
    }

    $this->db->trans_begin();

    // Overall supplier payment: allocate oldest outstanding invoices first.
    if ($purchase_id === 0) {
        $invoices = $this->db
            ->where('supplier_id', $supplier_id)
            ->where('due_amount >', 0)
            ->order_by('purchase_date', 'ASC')
            ->order_by('id', 'ASC')
            ->get('purchase_invoices')
            ->result_array();

        $total_due = 0;

        foreach ($invoices as $invoice) {
            $total_due += (float) $invoice['due_amount'];
        }

        $total_due = round($total_due, 2);

        if ($total_due <= 0 || $amount > $total_due) {
            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'No outstanding due found, or payment exceeds total supplier due of ₹' .
                number_format($total_due, 2)
            );

            redirect('ManagementController/pay_supplier_due');
            return;
        }

        $remaining_payment = $amount;

        foreach ($invoices as $invoice) {
            if ($remaining_payment <= 0) {
                break;
            }

            $invoice_due = round((float) $invoice['due_amount'], 2);
            $allocated = min($remaining_payment, $invoice_due);

            if ($allocated <= 0) {
                continue;
            }

            // Record the allocated payment against this invoice.
            $this->db->insert('supplier_payments', array(
                'supplier_id'  => $supplier_id,
                'purchase_id'  => (int) $invoice['id'],
                'amount'       => $allocated,
                'payment_date' => $payment_date,
                'payment_mode' => $payment_mode,
                'note'         => ($note !== '' ? $note . ' | ' : '') .
                                  'Overall supplier payment'
            ));

            if ($this->db->affected_rows() !== 1) {
                break;
            }

            $new_paid = round((float) $invoice['paid_amount'] + $allocated, 2);
            $new_due = round(max(0, $invoice_due - $allocated), 2);

            $this->db
                ->where('id', (int) $invoice['id'])
                ->where('supplier_id', $supplier_id)
                ->update('purchase_invoices', array(
                    'paid_amount' => $new_paid,
                    'due_amount'  => $new_due
                ));

            $remaining_payment = round($remaining_payment - $allocated, 2);
        }

        if ($remaining_payment > 0) {
            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Payment could not be fully allocated. No changes were saved.'
            );

            redirect('ManagementController/pay_supplier_due');
            return;
        }

    } else {
        // Payment against one selected invoice.
        $purchase = $this->db
            ->where('id', $purchase_id)
            ->where('supplier_id', $supplier_id)
            ->where('due_amount >', 0)
            ->get('purchase_invoices')
            ->row_array();

        if (!$purchase || $amount > (float) $purchase['due_amount']) {
            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Purchase not found or payment exceeds the invoice due.'
            );

            redirect('ManagementController/pay_supplier_due');
            return;
        }

        $this->db->insert('supplier_payments', array(
            'supplier_id'  => $supplier_id,
            'purchase_id'  => $purchase_id,
            'amount'       => $amount,
            'payment_date' => $payment_date,
            'payment_mode' => $payment_mode,
            'note'         => $note
        ));

        $new_paid = round((float) $purchase['paid_amount'] + $amount, 2);
        $new_due = round(max(0, (float) $purchase['due_amount'] - $amount), 2);

        $this->db
            ->where('id', $purchase_id)
            ->where('supplier_id', $supplier_id)
            ->update('purchase_invoices', array(
                'paid_amount' => $new_paid,
                'due_amount'  => $new_due
            ));
    }

    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();

        $this->session->set_flashdata(
            'error',
            'Payment could not be saved. Please check application logs.'
        );
    } else {
        $this->db->trans_commit();

        $this->session->set_flashdata(
            'success',
            'Supplier payment saved successfully.'
        );
    }

    redirect('ManagementController/pay_supplier_due');
}



/* =========================================================
   SUPPLIER LEDGER
========================================================= */
public function supplier_ledger()
{
    $data['page_title'] = 'Supplier Ledger';

    $data['suppliers'] = $this->db
        ->order_by('name', 'ASC')
        ->get('suppliers')
        ->result_array();

    $supplier_id = (int) $this->input->get('supplier_id');
    $data['selected_supplier'] = $supplier_id;
    $data['supplier'] = null;
    $data['purchases'] = array();
    $data['payments'] = array();

    if ($supplier_id > 0) {
        $data['supplier'] = $this->db
            ->where('id', $supplier_id)
            ->get('suppliers')
            ->row_array();

        if ($data['supplier']) {
            $data['purchases'] = $this->db
                ->where('supplier_id', $supplier_id)
                ->order_by('purchase_date', 'ASC')
                ->order_by('id', 'ASC')
                ->get('purchase_invoices')
                ->result_array();

            $data['payments'] = $this->db
                ->where('supplier_id', $supplier_id)
                ->order_by('payment_date', 'ASC')
                ->order_by('id', 'ASC')
                ->get('supplier_payments')
                ->result_array();
        }
    }

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar', $data);
    $this->load->view('supplier_ledger', $data);
    $this->load->view('layout/footer');
}


/* =========================================================
   PURCHASE RECEIPT
========================================================= */
public function purchase_receipt($id = 0)
{
    $id = (int) $id;

    $data['purchase'] = $this->db
        ->select('pi.*, s.name AS supplier_name, s.mobile AS supplier_mobile, s.address AS supplier_address')
        ->from('purchase_invoices pi')
        ->join('suppliers s', 's.id = pi.supplier_id', 'left')
        ->where('pi.id', $id)
        ->get()
        ->row_array();

    if (!$data['purchase']) {
        show_404();
        return;
    }

    $data['items'] = $this->db
        ->where('purchase_id', $id)
        ->get('purchase_items')
        ->result_array();

    $data['page_title'] = 'Purchase Receipt';

    $this->load->view('purchase_receipt', $data);
}













public function stock_alerts()
{
    $data['page_title'] = 'Stock Alerts';

    $data['products'] = $this->db
        ->where('stock_qty <= min_stock_alert', null, false)
        ->order_by('stock_qty', 'ASC')
        ->get('products')
        ->result_array();

    if ($this->db->error()['code'] != 0) {
        show_error(
            'Stock Alerts database error: ' .
            $this->db->error()['message']
        );
        return;
    }

    $data['sidebar_notifications'] = array();

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar', $data);
    $this->load->view('stock_alerts', $data);
    $this->load->view('layout/footer');
}



   
}