<style>
   .suggestion-box,
.customer-results {
    position: absolute;
    left: 0;
    right: 0;
    top: 100%;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, .12);
    max-height: 280px;
    overflow-y: auto;
    z-index: 9999;
    display: none;
}

    .customer-item,
    .product-item {
    padding: 14px 16px;
    cursor: pointer;
    border-bottom: 1px solid #f1f1f1;
}

    .customer-item:last-child,
    .product-item:last-child {
        border-bottom: 0;
    }

    .customer-item:hover,
    .product-item:hover {
    background: #f8f9fa;
}

    .summary-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 0;
    }

    .summary-total {
        font-size: 20px;
        font-weight: 700;
        color: #0d6efd;
    }

    .due-box {
        background: #fff5f5;
        border: 1px solid #f5c2c7;
        border-radius: 10px;
    }

    .selected-customer {
        background: #f8f9fa;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }

    .table th {
        white-space: nowrap;
    }

    @media(max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }

        .billing-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .billing-actions .btn {
            width: 100%;
        }
    }



    .stock-error {
    color: #dc3545;
    font-size: 13px;
    font-weight: 600;
    margin-top: 5px;
}

.stock-ok {
    color: #198754;
    font-weight: 600;
}

.stock-zero {
    color: #dc3545 !important;
    font-weight: 600;
}

.invoice-row.stock-invalid {
    background: #fff5f5;
}
</style>


<div class="container-fluid mt-3">

    <?php if ($this->session->flashdata('success')): ?>

        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle me-2"></i>
            <?= $this->session->flashdata('success') ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>

    <?php endif; ?>


    <?php if ($this->session->flashdata('error')): ?>

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <i class="fas fa-exclamation-circle me-2"></i>

            <?= $this->session->flashdata('error') ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>


   








    <form action="<?= base_url('BillingController/save_bill'); ?>"
      method="POST"
      id="invoiceForm">

        <div class="row g-3">

            <!-- =====================================================
                 LEFT SIDE - CUSTOMER
            ====================================================== -->

            <div class="col-lg-4">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white fw-bold">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Customer Details
                    </div>

                    <div class="card-body">

                        <!-- CUSTOMER SEARCH -->

                        <div class="mb-3 position-relative">

                            <label class="form-label fw-semibold">
                                Search Customer
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>

                                <input type="text"
                                    id="customerSearch"
                                    class="form-control"
                                    placeholder="Name / Mobile"
                                    autocomplete="off">

                            </div>


                            <div id="customerResults"
                                class="customer-results">
                            </div>

                        </div>


                        <!-- SELECTED CUSTOMER -->

                        <div id="selectedCustomer"
                            class="selected-customer p-3 mb-3">

                            <div class="text-muted small">
                                Selected Customer
                            </div>

                            <div class="fw-bold fs-5"
                                id="customer_name">

                                No Customer Selected

                            </div>

                            <div class="text-muted"
                                id="customer_mobile">

                                Mobile: -

                            </div>

                        </div>


                        <input type="hidden"
                            name="customer_id"
                            id="customer_id"
                            value="">


                        <!-- PREVIOUS DUE -->

                        <div class="summary-box p-3">

                            <div class="summary-row">

                                <span>
                                    <i class="fas fa-history me-2 text-danger"></i>
                                    Previous Due
                                </span>

                                <strong class="text-danger"
                                    id="old_due">

                                    ₹ 0.00

                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    <i class="fas fa-file-invoice me-2 text-primary"></i>
                                    New Bill
                                </span>

                                <strong id="new_bill">

                                    ₹ 0.00

                                </strong>

                            </div>


                            <hr>


                            <div class="summary-row">

                                <strong>
                                    Total Payable
                                </strong>

                                <strong class="summary-total"
                                    id="final_total">

                                    ₹ 0.00

                                </strong>

                            </div>

                        </div>


                        <!-- PAYMENT -->

                        <div class="mt-3">

                            <label class="form-label fw-semibold">

                                <i class="fas fa-money-bill-wave me-1 text-success"></i>

                                Payment Received Today

                            </label>

                            <input
                                type="number"
                                name="payment_amount"
                                id="payment_input"
                                class="form-control"
                                value="0"
                                min="0"
                                step="0.01">


                            <small class="text-muted">
                                Customer abhi jitna payment de raha hai.
                            </small>

                        </div>


                        <!-- PAYMENT METHOD -->

                        <div class="mt-3">

                            <label class="form-label fw-semibold">
                                Payment Method
                            </label>

                           <select
    name="payment_mode"
    class="form-select"
>
    <option value="Cash">Cash</option>
    <option value="UPI">UPI</option>
    <option value="Bank">Bank</option>
    <option value="Cheque">Cheque</option>
</select>

                        </div>


                        <!-- REMAINING DUE -->

                        <div class="due-box p-3 mt-3">

                            <div class="d-flex justify-content-between">

                                <span class="fw-semibold">
                                    Remaining Due
                                </span>

                                <strong class="text-danger fs-5"
                                    id="remaining_due">

                                    ₹ 0.00

                                </strong>

                            </div>

                        </div>


                        <!-- DATE -->

                        <div class="mt-3">

                            <label class="form-label fw-semibold">
                                Billing Date
                            </label>

                            <input type="date"
                                name="billing_date"
                                class="form-control"
                                value="<?= date('Y-m-d') ?>">

                        </div>


                        <!-- HIDDEN CALCULATION VALUES -->

                        <input type="hidden"
                            name="old_due"
                            id="old_due_input"
                            value="0">

                        <input type="hidden"
                            name="total_amount"
                            id="total_input"
                            value="0">

                        <input type="hidden"
                            name="final_total"
                            id="final_total_input"
                            value="0">

                        <input type="hidden"
                            name="due_amount"
                            id="due_input"
                            value="0">

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 RIGHT SIDE - ITEMS
            ====================================================== -->

            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white d-flex justify-content-between align-items-center">

                        <strong>
                            <i class="fas fa-boxes me-2 text-primary"></i>
                            Invoice Items
                        </strong>

                        <button type="button"
                            class="btn btn-primary btn-sm"
                            id="add-item-row">

                            <i class="fas fa-plus me-1"></i>
                            Add Item

                        </button>

                    </div>


                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-bordered align-middle"
                                id="billing-table">

                                <thead class="table-light">

                                    <tr>

                                        <th style="min-width:240px;">
                                            Product
                                        </th>

                                        <th style="width:100px;">
                                            Qty
                                        </th>

                                        <th style="width:130px;">
                                            Rate
                                        </th>

                                        <th style="width:150px;">
                                            Total
                                        </th>

                                        <th style="width:50px;">
                                            #
                                        </th>

                                    </tr>

                                </thead>


                                <tbody id="invoice-items">

                                    <tr class="invoice-row">

                                        <td>

                                            <div class="position-relative">

                                                <input type="text"
                                                    name="product_name[]"
                                                    class="form-control product_input"
                                                    placeholder="Search product..."
                                                    autocomplete="off">

                                                <input type="hidden"
                                                    name="product_id[]"
                                                    class="product_id">


                                                <div class="product-results suggestion-box">
                                                </div>

                                            </div>


                                            <small class="text-muted">

                                                Available:
                                                <span class="avail-stock">
                                                    0
                                                </span>

                                            </small>

                                        </td>


                                        <td>

                                            <input type="number"
                                                name="qty[]"
                                                class="form-control qty"
                                                min="1"
                                                step="0.01"
                                                value="1">

                                        </td>


                                        <td>

                                            <input type="number"
                                                name="rate[]"
                                                class="form-control rate"
                                                min="0"
                                                step="0.01"
                                                value="0">

                                        </td>


                                        <td>

                                            <input type="number"
                                                name="subtotal[]"
                                                class="form-control subtotal"
                                                value="0"
                                                readonly>

                                        </td>


                                        <td>

                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm remove-row">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>


                        <!-- TOTAL -->

                        <div class="row justify-content-end">

                            <div class="col-md-6">

                                <div class="d-flex justify-content-between border-top pt-3">

                                    <strong class="fs-5">
                                        New Bill Total
                                    </strong>

                                    <strong class="fs-5 text-primary"
                                        id="grand-total-display">

                                        ₹ 0.00

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- ACTIONS -->

                    <div class="card-footer bg-white">

                        <div class="billing-actions d-flex justify-content-end gap-2">

                            <button type="reset"
                                class="btn btn-light border">

                                <i class="fas fa-rotate-left me-1"></i>
                                Reset

                            </button>


                            <button type="submit"
                                class="btn btn-success btn-lg">

                                <i class="fas fa-save me-1"></i>
                                Save & Print Bill

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        </form>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function () {

    const allProducts = <?= json_encode(
        $products ?? [],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    ); ?>;

    let customerDue = 0;


    /* =========================================================
       MONEY
    ========================================================= */

    function money(v) {

        return '₹ ' + Number(v || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    }


    /* =========================================================
       ESCAPE HTML
    ========================================================= */

    function esc(v) {

        return String(v ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /* =========================================================
       CUSTOMER SEARCH
    ========================================================= */

    $('#customerSearch').on('input', function () {

        const q = $(this).val().trim();

        const box = $('#customerResults');

        if (!q) {

            box
                .hide()
                .empty();

            return;
        }


        $.ajax({

            url: "<?= base_url('CustomerController/search'); ?>",

            type: 'GET',

            dataType: 'json',

            data: {
                q: q
            },

            cache: false,


            success: function (r) {

                const customers =
                    r && Array.isArray(r.customers)
                        ? r.customers
                        : [];


                if (!customers.length) {

                    box
                        .html(`
                            <div class="p-3 text-muted text-center">
                                <i class="fas fa-user-slash me-1"></i>
                                Customer not found
                            </div>
                        `)
                        .show();

                    return;
                }


                let html = '';


                customers.forEach(function (c) {

                    const id =
                        Number(c.id || 0);

                    const name =
                        esc(c.name || '');

                    const mobile =
                        esc(c.mobile || '');

                    const due =
                        Number(c.total_due || 0);


                    html += `

                        <div
                            class="customer-item"
                            data-id="${id}"
                            data-name="${name}"
                            data-mobile="${mobile}"
                        >

                            <div class="d-flex justify-content-between align-items-center gap-2">

                                <div>

                                    <div class="fw-bold">

                                        <i class="fas fa-user me-1 text-primary"></i>

                                        ${name}

                                    </div>

                                    <small class="text-muted">

                                        <i class="fas fa-phone me-1"></i>

                                        ${mobile || '-'}

                                    </small>

                                </div>


                                <div class="text-end">

                                    <small class="text-muted d-block">

                                        Current Due

                                    </small>

                                    <strong class="${due > 0 ? 'text-danger' : 'text-success'}">

                                        ${money(due)}

                                    </strong>

                                </div>

                            </div>

                        </div>

                    `;

                });


                box
                    .html(html)
                    .show();

            },


            error: function (xhr) {

                console.error(
                    'Customer Search Error:',
                    xhr.responseText
                );


                box
                    .html(`
                        <div class="p-3 text-danger text-center">
                            Unable to search customer.
                        </div>
                    `)
                    .show();

            }

        });

    });


    /* =========================================================
       CUSTOMER SELECT
    ========================================================= */

    $(document).on('click', '.customer-item', function () {

        const item = $(this);

        const id =
            Number(item.attr('data-id')) || 0;

        if (!id) {
            return;
        }


        const name =
            item.attr('data-name') || '';

        const mobile =
            item.attr('data-mobile') || '';


        $('#customer_id').val(id);

        $('#customer_name').text(name);

        $('#customer_mobile').text(
            'Mobile: ' + (mobile || '-')
        );

        $('#customerSearch').val(name);


        $('#customerResults')
            .hide()
            .empty()
            .css('display', 'none');


        getCustomerDue(id);

    });


    /* =========================================================
       GET CUSTOMER DUE
    ========================================================= */

    function getCustomerDue(id) {

        id = Number(id) || 0;


        if (!id) {

            customerDue = 0;

            updateDue();

            return;
        }


        $.ajax({

            url:
                "<?= base_url('BillingController/get_customer_summary/'); ?>" +
                id,

            type: "GET",

            dataType: "json",

            cache: false,


            success: function (response) {

                console.log(
                    'Customer Due Response:',
                    response
                );


                if (
                    response &&
                    response.status === true
                ) {

                    customerDue =
                        Number(response.due) || 0;

                } else {

                    customerDue = 0;

                }


                updateDue();

            },


            error: function (xhr) {

                console.error(
                    'Customer Due Error:',
                    xhr.status,
                    xhr.responseText
                );


                customerDue = 0;

                updateDue();

            }

        });

    }


    /* =========================================================
       UPDATE DUE
    ========================================================= */

    function updateDue() {

        $('#old_due').text(
            money(customerDue)
        );


        $('#old_due_input').val(
            customerDue.toFixed(2)
        );


        calculateInvoice();

    }


    /* =========================================================
       PRODUCT SEARCH
    ========================================================= */

    $(document).on('input', '.product_input', function () {

        const input = $(this);

        const row =
            input.closest('tr');

        const box =
            input
                .closest('.position-relative')
                .find('.product-results');

        const q =
            input
                .val()
                .trim()
                .toLowerCase();


        /* Reset selected product */

        row.find('.product_id').val('');

        row
            .removeData('product-id')
            .removeData('stock');

        row
            .find('.avail-stock')
            .text('0')
            .removeClass('stock-zero stock-ok');

        row
            .find('.stock-error')
            .remove();

        row
            .removeClass('stock-invalid');


        /* Empty search */

        if (!q) {

            box
                .stop(true, true)
                .hide()
                .empty()
                .css('display', 'none');

            return;
        }


        /* Find products */

        const results =
            allProducts.filter(function (product) {

                return String(
                    product.product_name || ''
                )
                    .toLowerCase()
                    .includes(q);

            });


        /* Product not found */

        if (!results.length) {

            box
                .html(`
                    <div class="p-3 text-muted text-center">

                        <i class="fas fa-box-open me-1"></i>

                        Product not found

                    </div>
                `)
                .stop(true, true)
                .show();

            return;
        }


        let html = '';


        results
            .slice(0, 15)
            .forEach(function (product) {

                const id =
                    Number(product.id || 0);

                const name =
                    esc(product.product_name || '');

                const price =
                    Number(product.unit_price || 0);

                const stock =
                    Number(product.stock_qty || 0);


                html += `

                    <div
                        class="product-item"
                        data-id="${id}"
                        data-name="${name}"
                        data-price="${price}"
                        data-stock="${stock}"
                    >

                        <div class="d-flex justify-content-between align-items-center gap-2">

                            <div>

                                <div class="fw-bold">

                                    <i class="fas fa-box me-1 text-primary"></i>

                                    ${name}

                                </div>

                                <small class="text-muted">

                                    Stock: ${stock}

                                </small>

                            </div>


                            <strong class="text-primary">

                                ₹${price.toFixed(2)}

                            </strong>

                        </div>

                    </div>

                `;

            });


        box
            .html(html)
            .stop(true, true)
            .show();

    });


    /* =========================================================
       PRODUCT SELECT
       IMPORTANT: ONLY ONE HANDLER
    ========================================================= */

    $(document).on('mousedown', '.product-item', function (e) {

        e.preventDefault();

        e.stopPropagation();


        const item =
            $(this);

        const row =
            item.closest('tr');

        const box =
            item.closest('.product-results');


        const productId =
            Number(item.attr('data-id')) || 0;

        const productName =
            item.attr('data-name') || '';

        const price =
            Number(item.attr('data-price')) || 0;

        const stock =
            Number(item.attr('data-stock')) || 0;


        if (!productId) {
            return;
        }


        /* =========================================
           SET PRODUCT
        ========================================= */

        row
            .find('.product_input')
            .val(productName);


        row
            .find('.product_id')
            .val(productId);


        row
            .find('.rate')
            .val(price.toFixed(2));


        row
            .find('.avail-stock')
            .text(stock)
            .removeClass(
                'stock-zero stock-ok'
            );


        /* =========================================
           STORE PRODUCT DATA
        ========================================= */

        row.data(
            'stock',
            stock
        );


        row.data(
            'product-id',
            productId
        );


        row.removeClass(
            'stock-invalid'
        );


        row
            .find('.stock-error')
            .remove();


        /* =========================================
           STOCK VALIDATION
        ========================================= */

        if (stock <= 0) {

            row
                .find('.avail-stock')
                .addClass('stock-zero');


            row.addClass(
                'stock-invalid'
            );


            row
                .find('.avail-stock')
                .after(`
                    <div class="stock-error">

                        <i class="fas fa-times-circle me-1"></i>

                        This product is currently out of stock.

                    </div>
                `);


            row
                .find('.subtotal')
                .val('0.00');

        } else {

            row
                .find('.avail-stock')
                .addClass('stock-ok');


            validateStockRow(row);

        }


        /* =========================================
           CLOSE PRODUCT DROPDOWN
        ========================================= */

        box
            .stop(true, true)
            .hide()
            .empty()
            .css('display', 'none');


        /* Remove focus */

        row
            .find('.product_input')
            .blur();


        /* =========================================
           RECALCULATE
        ========================================= */

        calculateRow(row);

        validateAllStock();

    });


    /* =========================================================
       CALCULATE ROW
    ========================================================= */

    function calculateRow(row) {

        const qty =
            Number(
                row.find('.qty').val()
            ) || 0;


        const rate =
            Number(
                row.find('.rate').val()
            ) || 0;


        const subtotal =
            qty * rate;


        row
            .find('.subtotal')
            .val(
                subtotal.toFixed(2)
            );


        calculateInvoice();

    }


    /* =========================================================
       QTY / RATE CHANGE
    ========================================================= */

    $(document).on(
        'input',
        '.qty,.rate',
        function () {

            calculateRow(
                $(this).closest('tr')
            );

            validateAllStock();

        }
    );


    /* =========================================================
       ADD NEW ITEM ROW
    ========================================================= */

    $('#add-item-row').on('click', function () {

        $('#invoice-items').append(`

            <tr class="invoice-row">

                <td>

                    <div class="position-relative">

                        <input
                            type="text"
                            name="product_name[]"
                            class="form-control product_input"
                            placeholder="Search product..."
                            autocomplete="off"
                        >

                        <input
                            type="hidden"
                            name="product_id[]"
                            class="product_id"
                        >

                        <div
                            class="product-results suggestion-box">
                        </div>

                    </div>


                    <small class="text-muted">

                        Available:

                        <span class="avail-stock">
                            0
                        </span>

                    </small>

                </td>


                <td>

                    <input
                        type="number"
                        name="qty[]"
                        class="form-control qty"
                        min="0.01"
                        step="0.01"
                        value="1"
                    >

                </td>


                <td>

                    <input
                        type="number"
                        name="rate[]"
                        class="form-control rate"
                        min="0"
                        step="0.01"
                        value="0"
                    >

                </td>


                <td>

                    <input
                        type="number"
                        name="subtotal[]"
                        class="form-control subtotal"
                        value="0"
                        readonly
                    >

                </td>


                <td>

                    <button
                        type="button"
                        class="btn btn-outline-danger btn-sm remove-row"
                    >

                        <i class="fas fa-trash"></i>

                    </button>

                </td>

            </tr>

        `);

    });


    /* =========================================================
       REMOVE ITEM ROW
    ========================================================= */

    $(document).on(
        'click',
        '.remove-row',
        function () {

            if (
                $('#invoice-items .invoice-row').length <= 1
            ) {

                alert(
                    'At least one product row is required.'
                );

                return;
            }


            $(this)
                .closest('tr')
                .remove();


            calculateInvoice();

            validateAllStock();

        }
    );


    /* =========================================================
       VALIDATE SINGLE STOCK ROW
    ========================================================= */

    function validateStockRow(row) {

        const productId =
            Number(
                row.find('.product_id').val()
            ) || 0;


        const qty =
            Number(
                row.find('.qty').val()
            ) || 0;


        const stock =
            Number(
                row.data('stock')
            ) || 0;


        if (!productId) {

            return true;

        }


        row
            .find('.stock-error')
            .remove();


        if (qty > stock) {

            row.addClass(
                'stock-invalid'
            );


            row
                .find('.avail-stock')
                .removeClass('stock-ok')
                .addClass('stock-zero');


            row
                .find('.avail-stock')
                .after(`
                    <div class="stock-error">

                        <i class="fas fa-exclamation-circle me-1"></i>

                        Quantity (${qty}) exceeds available stock (${stock}).

                    </div>
                `);


            return false;

        }


        if (stock <= 0) {

            row.addClass(
                'stock-invalid'
            );


            return false;

        }


        row.removeClass(
            'stock-invalid'
        );


        row
            .find('.avail-stock')
            .removeClass('stock-zero')
            .addClass('stock-ok');


        return true;

    }


    /* =========================================================
       VALIDATE ALL STOCK
    ========================================================= */

    function validateAllStock() {

        let valid = true;

        const productQty = {};


        $('#invoice-items .invoice-row')
            .each(function () {

                const row =
                    $(this);


                const productId =
                    Number(
                        row.find('.product_id').val()
                    ) || 0;


                const qty =
                    Number(
                        row.find('.qty').val()
                    ) || 0;


                const stock =
                    Number(
                        row.data('stock')
                    ) || 0;


                if (!productId) {
                    return;
                }


                if (!productQty[productId]) {

                    productQty[productId] = 0;

                }


                productQty[productId] += qty;


                if (
                    !validateStockRow(row)
                ) {

                    valid = false;

                }

            });


        $('#invoice-items .invoice-row')
            .each(function () {

                const row =
                    $(this);


                const productId =
                    Number(
                        row.find('.product_id').val()
                    ) || 0;


                if (!productId) {
                    return;
                }


                const totalQty =
                    productQty[productId];


                const stock =
                    Number(
                        row.data('stock')
                    ) || 0;


                if (
                    totalQty > stock
                ) {

                    row
                        .find('.stock-error')
                        .remove();


                    row.addClass(
                        'stock-invalid'
                    );


                    row
                        .find('.avail-stock')
                        .removeClass('stock-ok')
                        .addClass('stock-zero');


                    row
                        .find('.avail-stock')
                        .after(`
                            <div class="stock-error">

                                <i class="fas fa-exclamation-circle me-1"></i>

                                Total quantity (${totalQty})
                                exceeds available stock (${stock}).

                            </div>
                        `);


                    valid = false;

                }

            });


        const submitBtn =
            $('#invoiceForm button[type="submit"]');


        if (!valid) {

            submitBtn
                .prop('disabled', true)
                .addClass('disabled');

        } else {

            submitBtn
                .prop('disabled', false)
                .removeClass('disabled');

        }


        return valid;

    }


    /* =========================================================
       CALCULATE INVOICE
    ========================================================= */

    function calculateInvoice() {

        let bill = 0;


        $('.subtotal').each(function () {

            bill +=
                Number(
                    $(this).val()
                ) || 0;

        });


        /* Old due + new bill */

        const total =
            customerDue + bill;


        let pay =
            Number(
                $('#payment_input').val()
            ) || 0;


        if (pay < 0) {

            pay = 0;

        }


        if (pay > total) {

            pay = total;

            $('#payment_input')
                .val(
                    pay.toFixed(2)
                );

        }


        let due =
            total - pay;


        if (due < 0) {

            due = 0;

        }


        /* Display */

        $('#old_due').text(
            money(customerDue)
        );


        $('#new_bill').text(
            money(bill)
        );


        $('#grand-total-display').text(
            money(bill)
        );


        $('#final_total').text(
            money(total)
        );


        $('#remaining_due').text(
            money(due)
        );


        /* Hidden values */

        $('#old_due_input').val(
            customerDue.toFixed(2)
        );


        $('#total_input').val(
            bill.toFixed(2)
        );


        $('#final_total_input').val(
            total.toFixed(2)
        );


        $('#due_input').val(
            due.toFixed(2)
        );

    }


    /* =========================================================
       PAYMENT INPUT
    ========================================================= */

    $('#payment_input').on(
        'input',
        function () {

            calculateInvoice();

        }
    );


    /* =========================================================
       OUTSIDE CLICK
    ========================================================= */

    $(document).on(
        'click',
        function (e) {

            if (
                !$(e.target)
                    .closest(
                        '#customerSearch,#customerResults'
                    )
                    .length
            ) {

                $('#customerResults')
                    .hide()
                    .empty();

            }


            if (
                !$(e.target)
                    .closest(
                        '.product_input,.product-results'
                    )
                    .length
            ) {

                $('.product-results')
                    .stop(true, true)
                    .hide()
                    .empty()
                    .css('display', 'none');

            }

        }
    );


    /* =========================================================
       FORM SUBMIT
    ========================================================= */

    $('#invoiceForm').on(
        'submit',
        function (e) {

            const customerId =
                $('#customer_id').val();


            const bill =
                Number(
                    $('#total_input').val()
                ) || 0;


            const payment =
                Number(
                    $('#payment_input').val()
                ) || 0;


            /* Customer */

            if (!customerId) {

                e.preventDefault();

                alert(
                    'Please select a customer.'
                );

                return;

            }


            /* Bill */

            if (bill <= 0) {

                e.preventDefault();

                alert(
                    'Please add at least one product.'
                );

                return;

            }


            /* Payment */

            if (payment < 0) {

                e.preventDefault();

                alert(
                    'Payment cannot be negative.'
                );

                return;

            }


            /* Product */

            let valid = true;


            $('#invoice-items .invoice-row')
                .each(function () {

                    const row =
                        $(this);


                    const productId =
                        Number(
                            row.find('.product_id').val()
                        ) || 0;


                    const qty =
                        Number(
                            row.find('.qty').val()
                        ) || 0;


                    const rate =
                        Number(
                            row.find('.rate').val()
                        ) || 0;


                    if (
                        !productId ||
                        qty <= 0 ||
                        rate < 0
                    ) {

                        valid = false;

                        return false;

                    }

                });


            if (!valid) {

                e.preventDefault();

                alert(
                    'Please select product and enter valid quantity/rate for every row.'
                );

                return;

            }


            /* Stock */

            if (!validateAllStock()) {

                e.preventDefault();

                alert(
                    'Please fix the stock quantity before saving the invoice.'
                );

                return;

            }

        }
    );


    /* =========================================================
       INITIAL CALCULATION
    ========================================================= */

    calculateInvoice();

    validateAllStock();

});
</script>