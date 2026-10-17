

<?php
$paymentSuccess = $this->session->flashdata('payment_success');
$paymentError   = $this->session->flashdata('payment_error');

$paidCustomerId = (int) (
    $this->session->flashdata('payment_customer_id') ?? 0
);

$paidCustomerName = (string) (
    $this->session->flashdata('payment_customer_name') ?? ''
);

$paidAmount = (float) (
    $this->session->flashdata('payment_amount') ?? 0
);
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


















<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Flash data - READ ONLY ONCE
|--------------------------------------------------------------------------
*/
$paymentSuccess = $this->session->flashdata('success');
$paymentError   = $this->session->flashdata('error');

$paidCustomerId   = (int) ($this->session->flashdata('payment_customer_id') ?? 0);
$paidCustomerName = (string) ($this->session->flashdata('payment_customer_name') ?? '');
$paidAmount       = (float) ($this->session->flashdata('payment_amount') ?? 0);
?>

<div class="container-fluid mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h5 class="text-secondary fw-bold mb-1">
                <i class="fas fa-credit-card text-primary me-2"></i>
                Payment & History
            </h5>

            <small class="text-muted">
                Manage customer pending dues and payments
            </small>
        </div>

    </div>


    <!-- SEARCH -->
    <div class="card shadow-sm border-0 mb-3">

        <div class="card-body">

            <div class="input-group">

                <span class="input-group-text bg-white">
                    <i class="fas fa-search text-primary"></i>
                </span>

                <input
                    type="text"
                    id="searchCustomer"
                    class="form-control"
                    placeholder="Search Customer Name or Mobile..."
                >

            </div>

        </div>

    </div>


    <!-- CUSTOMER DUES -->
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-wallet text-primary me-2"></i>
                    Pending Customer Dues
                </h6>

                <span class="badge bg-danger rounded-pill">
                    <?= count($due_customers ?? []) ?>
                    Customers
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th>Total Billing</th>
                            <th>Total Received</th>
                            <th>Due Amount</th>
                            <th class="text-center">Action</th>
                            <th class="text-center">History</th>
                        </tr>

                    </thead>


                    <tbody id="paymentTable">

                    <?php if (!empty($due_customers)): ?>

                        <?php foreach ($due_customers as $due): ?>

                            <?php

                            $customerId = (int) (
                                $due['customer_id']
                                ?? $due['id']
                                ?? 0
                            );

                            $customerName =
                                $due['cust_name']
                                ?? $due['name']
                                ?? '';

                            $mobile =
                                $due['mobile']
                                ?? '';

                            $totalBilling = (float) (
                                $due['total_billing']
                                ?? 0
                            );

                            $totalPaid = (float) (
                                $due['total_paid']
                                ?? 0
                            );

                            $totalDue = (float) (
                                $due['current_due']
                                ?? $due['total_due']
                                ?? 0
                            );

                            $invoiceId = (int) (
                                $due['latest_invoice_id']
                                ?? 0
                            );

                            ?>

                            <tr
                                class="payment-customer-row
                                <?= ($paidCustomerId === $customerId && $paidAmount > 0)
                                    ? 'just-paid-row'
                                    : ''
                                ?>"
                                data-customer-id="<?= $customerId ?>"
                            >

                                <!-- CUSTOMER -->

                                <td>

                                    <div class="fw-bold text-dark">

                                        <?= html_escape($customerName) ?>

                                        <?php if (
                                            $paidCustomerId === $customerId &&
                                            $paidAmount > 0
                                        ): ?>

                                            <span class="just-paid-badge">

                                                <i class="fas fa-check-circle me-1"></i>

                                                Just Paid ₹<?= number_format(
                                                    $paidAmount,
                                                    2
                                                ) ?>

                                            </span>

                                        <?php endif; ?>

                                    </div>

                                    <small class="text-muted">
                                        Customer #<?= $customerId ?>
                                    </small>

                                </td>


                                <!-- MOBILE -->

                                <td>
                                    <span class="text-muted">
                                        <?= html_escape($mobile) ?>
                                    </span>
                                </td>


                                <!-- STATUS -->

                                <td>

                                    <?php if ($totalDue > 0): ?>

                                        <span class="badge bg-warning text-dark">

                                            <i class="fas fa-clock me-1"></i>

                                            Pending Due

                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-success">

                                            <i class="fas fa-check-circle me-1"></i>

                                            Clear

                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- BILLING -->

                                <td>

                                    <strong class="text-dark">
                                        ₹ <?= number_format(
                                            $totalBilling,
                                            2
                                        ) ?>
                                    </strong>

                                </td>


                                <!-- RECEIVED -->

                                <td>

                                    <strong class="text-success">
                                        ₹ <?= number_format(
                                            $totalPaid,
                                            2
                                        ) ?>
                                    </strong>

                                </td>


                                <!-- DUE -->

                                <td>

                                    <strong class="text-danger">
                                        ₹ <?= number_format(
                                            $totalDue,
                                            2
                                        ) ?>
                                    </strong>

                                </td>


                                <!-- PAY -->

                                <td class="text-center">

                                    <?php if ($totalDue > 0): ?>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-primary"
                                            onclick="handlePay(
                                                <?= $invoiceId ?>,
                                                <?= htmlspecialchars(
                                                    json_encode($customerName),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>,
                                                <?= $totalDue ?>,
                                                <?= $customerId ?>
                                            )"
                                        >

                                            <i class="fas fa-money-bill-wave me-1"></i>

                                            Pay

                                        </button>

                                    <?php else: ?>

                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i>
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- HISTORY -->

                                <td class="text-center">

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        onclick="handleHistory(
                                            <?= $customerId ?>,
                                            <?= htmlspecialchars(
                                                json_encode($customerName),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>,
                                            <?= htmlspecialchars(
                                                json_encode($mobile),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        )"
                                    >

                                        <i class="fas fa-history me-1"></i>

                                        History

                                    </button>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="8" class="text-center py-5">

                                <div class="text-muted">

                                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>

                                    <div class="fw-bold">
                                        No Pending Customer Dues
                                    </div>

                                    <small>
                                        All customer payments are up to date.
                                    </small>

                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     PAYMENT MODAL
========================================================= -->

<div
    class="modal fade"
    id="payModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <!-- IMPORTANT: PAYMENT SAVE ENDPOINT -->

            <form
                id="paymentForm"
                action="<?= base_url('CustomerController/receive_payment') ?>"
                method="POST"
            >

                <div class="modal-header payment-modal-header text-white">

                    <h5 class="modal-title">

                        <i class="fas fa-money-bill-wave me-2"></i>

                        Receive Payment

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <input
                        type="hidden"
                        name="invoice_id"
                        id="m_inv_id"
                    >

                    <input
                        type="hidden"
                        name="customer_id"
                        id="m_cust_id"
                    >


                    <!-- CUSTOMER -->

                    <div class="mb-3">

                        <label class="small text-muted">
                            Customer
                        </label>

                        <div
                            class="fw-bold fs-5"
                            id="m_cust_name"
                        ></div>

                    </div>


                    <!-- DUE -->

                    <div class="mb-3">

                        <label class="small fw-bold text-muted">
                            Current Due
                        </label>

                        <input
                            type="text"
                            id="m_due_amt"
                            class="form-control bg-light fw-bold text-danger"
                            readonly
                        >

                    </div>


                    <!-- PAYMENT -->

                    <div class="mb-3">

                        <label class="small fw-bold text-success">
                            Amount Paying
                        </label>

                        <input
                            type="number"
                            name="amount"
                            id="p_input"
                            class="form-control border-success"
                            min="0.01"
                            step="0.01"
                            required
                        >

                    </div>


                    <!-- MODE -->

                    <div class="mb-3">

                        <label class="small fw-bold">
                            Payment Mode
                        </label>

                        <select
                            name="payment_mode"
                            class="form-select"
                            required
                        >

                            <option value="Cash">
                                💵 Cash
                            </option>

                            <option value="Online">
                                📱 Online / UPI
                            </option>

                            <option value="Bank">
                                🏦 Bank
                            </option>

                            <option value="Cheque">
                                📄 Cheque
                            </option>

                        </select>

                    </div>


                    <!-- NOTE -->

                    <div>

                        <label class="small fw-bold">
                            Note
                        </label>

                        <textarea
                            name="note"
                            class="form-control"
                            rows="2"
                            placeholder="Optional note..."
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="submit"
                        id="confirmPaymentBtn"
                        class="btn btn-success w-100 fw-bold"
                    >

                        <i class="fas fa-check me-1"></i>

                        CONFIRM PAYMENT

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- =========================================================
     HISTORY MODAL
========================================================= -->

<div
    class="modal fade"
    id="historyModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header history-modal-header text-white">

                <div>

                    <h5 class="modal-title mb-1">

                        <i class="fas fa-receipt me-2"></i>

                        Customer Payment History

                    </h5>

                    <small class="opacity-75">
                        Complete billing and payment details
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div
                class="modal-body p-0"
                id="history_body"
            >

                <div class="text-center py-5">

                    <div class="spinner-border text-primary"></div>

                    <div class="mt-3 text-muted">
                        Loading...
                    </div>

                </div>

            </div>


            <div class="modal-footer bg-light">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Close
                </button>

            </div>

        </div>

    </div>

</div>







<style>

.payment-modal-header {
    background: linear-gradient(135deg, #059669, #10b981);
}

.history-modal-header {
    background: linear-gradient(135deg, #4f46e5, #2563eb);
}

.just-paid-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-left: 8px;
    padding: 5px 10px;
    border-radius: 20px;

    background: #dcfce7;
    color: #15803d;

    border: 1px solid #86efac;

    font-size: 11px;
    font-weight: 800;

    white-space: nowrap;

    animation: justPaidPulse 1.5s ease-in-out 2;
}

@keyframes justPaidPulse {

    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.06);
    }

    100% {
        transform: scale(1);
    }

}

.just-paid-row {

    background: linear-gradient(
        90deg,
        #ecfdf5,
        #ffffff
    ) !important;

    box-shadow:
        inset 4px 0 0 #10b981;

}

.just-paid-row td {
    background: transparent !important;
}

.history-summary-card {

    min-height: 125px;
    padding: 18px;

    border-radius: 14px;

    color: #fff;

    position: relative;
    overflow: hidden;

    transition: .2s ease;

}

.history-summary-card:hover {

    transform: translateY(-3px);

    box-shadow:
        0 10px 25px rgba(0,0,0,.15);

}

.billing-card {

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #4f46e5
        );

}

.payment-card {

    background:
        linear-gradient(
            135deg,
            #059669,
            #10b981
        );

}

.due-card {

    background:
        linear-gradient(
            135deg,
            #dc2626,
            #ef4444
        );

}

.history-icon {
    font-size: 22px;
    margin-bottom: 8px;
}

.history-title {
    font-size: 13px;
    opacity: .9;
}

.history-amount {
    font-size: 24px;
    font-weight: 800;
}

.customer-history-header {

    padding: 18px 20px;

    background:
        linear-gradient(
            135deg,
            #eef2ff,
            #eff6ff
        );

    border-bottom: 1px solid #e5e7eb;

}

.customer-history-name {

    font-size: 20px;
    font-weight: 800;
    color: #1e293b;

}

.customer-history-mobile {

    margin-top: 4px;
    color: #64748b;

}

@media (max-width: 768px) {

    .history-amount {
        font-size: 20px;
    }

    .just-paid-badge {
        display: inline-flex;
        margin-top: 4px;
    }

}



.payment-success-popup {
    border-radius: 18px !important;
    padding: 28px !important;
}

.payment-success-popup .swal2-title {
    color: #15803d !important;
    font-weight: 800 !important;
    font-size: 26px !important;
}

</style>


<script>

const paidCustomerId =
    <?= json_encode($paidCustomerId) ?>;

const paidCustomerName =
    <?= json_encode($paidCustomerName) ?>;

const paidAmount =
    <?= json_encode($paidAmount) ?>;


function formatAmount(amount)
{
    amount = parseFloat(amount) || 0;

    return amount.toLocaleString(
        'en-IN',
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );
}


function escapeHtml(value)
{
    return String(value == null ? '' : value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}


function formatHistoryDate(value)
{
    if (!value) {
        return '-';
    }

    const date = new Date(value + 'T00:00:00');

    if (isNaN(date.getTime())) {
        return escapeHtml(value);
    }

    return date.toLocaleDateString(
        'en-IN',
        {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        }
    );
}


function formatHistoryTime(value)
{
    if (!value) {
        return '';
    }

    const date = new Date(
        value.replace(' ', 'T')
    );

    if (isNaN(date.getTime())) {
        return '';
    }

    return date.toLocaleTimeString(
        'en-IN',
        {
            hour: '2-digit',
            minute: '2-digit'
        }
    );
}


/* =========================================================
   OPEN PAYMENT MODAL
========================================================= */

function handlePay(
    invoiceId,
    customerName,
    dueAmount,
    customerId
)
{

    const modalElement =
        document.getElementById('payModal');

    if (!modalElement) {

        Swal.fire({
            icon: 'error',
            title: 'Payment Modal Missing',
            text: 'Payment modal could not be found.'
        });

        return;
    }


    document.getElementById('m_inv_id').value =
        invoiceId;

    document.getElementById('m_cust_id').value =
        customerId;

    document.getElementById('m_cust_name').textContent =
        customerName;


    const due =
        parseFloat(dueAmount) || 0;


    const dueInput =
        document.getElementById('m_due_amt');

    dueInput.value =
        '₹ ' + formatAmount(due);

    dueInput.dataset.due =
        due;


    const paymentInput =
        document.getElementById('p_input');

    paymentInput.value = '';
    paymentInput.max = due;


    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );

    modal.show();


    setTimeout(function()
    {
        paymentInput.focus();
    }, 300);

}


/* =========================================================
   PAYMENT FORM
========================================================= */

$(document).ready(function()
{

    $('#paymentForm').on(
        'submit',
        function(e)
        {

            e.preventDefault();

            const form = this;


            const amount =
                parseFloat(
                    $('#p_input').val()
                ) || 0;


            const due =
                parseFloat(
                    $('#m_due_amt').data('due')
                ) || 0;


            if (amount <= 0) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Invalid Amount',

                    text:
                        'Please enter a valid payment amount.',

                    timer: 3000,

                    timerProgressBar: true,

                    showConfirmButton: false

                });

                return;
            }


            if (amount > due) {

                Swal.fire({

                    icon: 'error',

                    title: 'Amount Exceeded',

                    text:
                        'Payment cannot be greater than current customer due.',

                    timer: 3000,

                    timerProgressBar: true,

                    showConfirmButton: false

                });

                return;
            }


            $('#confirmPaymentBtn')
                .prop('disabled', true);


            Swal.fire({

    title: 'Processing Payment...',

    html: `
        <div style="
            font-size:15px;
            color:#64748b;
            margin-top:5px;
        ">
            Please wait while we save the payment.
        </div>
    `,

    allowOutsideClick: false,

    allowEscapeKey: false,

    showConfirmButton: false,

    didOpen: function () {

        Swal.showLoading();

    }

});


            /*
             * Submit normally.
             * Controller will save payment and redirect
             * back to CustomerController/due_reports.
             */

            setTimeout(function()
            {
                form.submit();
            }, 300);

        }
    );


    /* =====================================================
       SEARCH
    ===================================================== */

    $('#searchCustomer').on(
        'keyup',
        function()
        {

            const search =
                $(this)
                    .val()
                    .toLowerCase()
                    .trim();


            $('#paymentTable .payment-customer-row')
                .each(
                    function()
                    {

                        const rowText =
                            $(this)
                                .text()
                                .toLowerCase();


                        $(this).toggle(
                            rowText.includes(search)
                        );

                    }
                );

        }
    );


    /* =====================================================
       SUCCESS MESSAGE
    ===================================================== */

   <?php if (!empty($paymentSuccess)): ?>

Swal.fire({

    icon: 'success',

    title: 'Payment Successful!',

    html: `
        <div style="
            margin-top:8px;
            text-align:center;
        ">

            <div style="
                font-size:17px;
                font-weight:700;
                color:#1e293b;
                margin-bottom:8px;
            ">
                <?= htmlspecialchars(
                    $paidCustomerName ?: 'Customer',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </div>

            <div style="
                font-size:30px;
                font-weight:800;
                color:#16a34a;
                margin-bottom:8px;
            ">
                ₹<?= number_format($paidAmount, 2) ?>
            </div>

            <div style="
                font-size:14px;
                color:#64748b;
            ">
                Payment successfully recorded.
            </div>

        </div>
    `,

    timer: 10000,

    timerProgressBar: true,

    showConfirmButton: false,

    allowOutsideClick: true,

    allowEscapeKey: true,

    customClass: {
        popup: 'payment-success-popup'
    }

});

<?php endif; ?>


    /* =====================================================
       ERROR MESSAGE
    ===================================================== */

    <?php if (!empty($paymentError)): ?>

    Swal.fire({

        icon: 'error',

        title: 'Payment Failed!',

        text:
            <?= json_encode($paymentError) ?>,

        timer: 10000,

        timerProgressBar: true,

        showConfirmButton: false

    });

    <?php endif; ?>


    /* =====================================================
       AUTO REMOVE JUST PAID HIGHLIGHT
    ===================================================== */

    <?php if ($paidCustomerId > 0 && $paidAmount > 0): ?>

    setTimeout(function()
    {

        const row =
            document.querySelector(
                '.payment-customer-row[data-customer-id="<?= $paidCustomerId ?>"]'
            );

        if (!row) {
            return;
        }

        row.classList.remove('just-paid-row');

        const badge =
            row.querySelector('.just-paid-badge');

        if (badge) {
            badge.style.display = 'none';
        }

    }, 10000);

    <?php endif; ?>

});


/* =========================================================
   HISTORY
========================================================= */

function handleHistory(
    customerId,
    customerName,
    customerMobile
)
{

    const modalElement =
        document.getElementById('historyModal');

    const body =
        document.getElementById('history_body');


    if (!modalElement || !body) {
        return;
    }


    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );

    modal.show();


    body.innerHTML = `

        <div class="text-center py-5">

            <div class="spinner-border text-primary"></div>

            <div class="mt-3 text-muted">
                Loading payment history...
            </div>

        </div>

    `;


    const url =
        "<?= base_url('CustomerController/payment_history/') ?>" +
        encodeURIComponent(customerId);


    $.ajax({

        url: url,

        type: 'GET',

        dataType: 'json',

        success: function(response)
        {

            if (
                !response ||
                response.status !== true
            ) {

                body.innerHTML = `

                    <div class="alert alert-danger m-3">

                        ${
                            response &&
                            response.message
                                ? escapeHtml(response.message)
                                : 'Unable to load history.'
                        }

                    </div>

                `;

                return;
            }


            const customer =
                response.customer || {};


            const totalBilling =
                parseFloat(
                    response.total_billing
                ) || 0;


            const totalPaid =
                parseFloat(
                    response.total_paid
                ) || 0;


            const currentDue =
                parseFloat(
                    response.current_due
                ) || 0;


            const transactions =
                Array.isArray(
                    response.transactions
                )
                    ? response.transactions
                    : [];


            let html = '';


            html += `

                <div class="customer-history-header">

                    <div class="customer-history-name">

                        <i class="fas fa-user-circle text-primary me-2"></i>

                        ${escapeHtml(
                            customer.name || customerName
                        )}

                    </div>

                    <div class="customer-history-mobile">

                        <i class="fas fa-phone me-2"></i>

                        ${escapeHtml(
                            customer.mobile ||
                            customerMobile ||
                            '-'
                        )}

                    </div>

                </div>

            `;


            html += `

                <div class="row g-3 p-3">

                    <div class="col-md-4">

                        <div class="history-summary-card billing-card">

                            <div class="history-icon">
                                <i class="fas fa-file-invoice"></i>
                            </div>

                            <div class="history-title">
                                Total Billing
                            </div>

                            <div class="history-amount">
                                ₹${formatAmount(totalBilling)}
                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="history-summary-card payment-card">

                            <div class="history-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>

                            <div class="history-title">
                                Total Payment Received
                            </div>

                            <div class="history-amount">
                                ₹${formatAmount(totalPaid)}
                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="history-summary-card due-card">

                            <div class="history-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>

                            <div class="history-title">
                                Current Due
                            </div>

                            <div class="history-amount">
                                ₹${formatAmount(currentDue)}
                            </div>

                        </div>

                    </div>

                </div>

            `;


            html += `

                <div class="px-3 pb-3">

                    <h6 class="fw-bold mb-3">

                        <i class="fas fa-history text-primary me-2"></i>

                        Payment History

                    </h6>

            `;


            if (!transactions.length) {

                html += `

                    <div class="text-center text-muted py-5">

                        <i class="fas fa-receipt fa-2x mb-3"></i>

                        <div>
                            No payment history found.
                        </div>

                    </div>

                `;

            } else {

                html += `

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Mode</th>
                                    <th>Invoice</th>
                                    <th>Note</th>

                                </tr>

                            </thead>

                            <tbody>

                `;


                transactions.forEach(
                    function(payment, index)
                    {

                        const mode =
                            payment.payment_mode ||
                            'Cash';


                        let modeClass =
                            'bg-success';


                        const modeLower =
                            mode.toLowerCase();


                        if (
                            modeLower.includes('upi') ||
                            modeLower.includes('online')
                        ) {

                            modeClass =
                                'bg-primary';

                        }
                        else if (
                            modeLower.includes('bank')
                        ) {

                            modeClass =
                                'bg-info text-dark';

                        }
                        else if (
                            modeLower.includes('cheque')
                        ) {

                            modeClass =
                                'bg-warning text-dark';

                        }


                        const amount =
                            parseFloat(
                                payment.paid_amount
                            ) || 0;


                        html += `

                            <tr>

                                <td>
                                    ${index + 1}
                                </td>

                                <td>

                                    <strong>
                                        ${formatHistoryDate(
                                            payment.payment_date
                                        )}
                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        ${formatHistoryTime(
                                            payment.created_at
                                        )}

                                    </small>

                                </td>

                                <td>

                                    <strong class="text-success">

                                        ₹${formatAmount(amount)}

                                    </strong>

                                </td>

                                <td>

                                    <span class="badge ${modeClass}">

                                        ${escapeHtml(mode)}

                                    </span>

                                </td>

                                <td>

                                    ${
                                        payment.invoice_no
                                            ? '#' +
                                              escapeHtml(
                                                  payment.invoice_no
                                              )
                                            : '-'
                                    }

                                </td>

                                <td class="text-muted">

                                    ${escapeHtml(
                                        payment.note || '-'
                                    )}

                                </td>

                            </tr>

                        `;

                    }
                );


                html += `

                            </tbody>

                        </table>

                    </div>

                `;

            }


            html += `</div>`;


            body.innerHTML = html;

        },


        error: function(xhr)
        {

            console.error(
                xhr.responseText
            );


            body.innerHTML = `

                <div class="alert alert-danger m-3">

                    <i class="fas fa-exclamation-triangle me-2"></i>

                    Unable to load payment history.

                </div>

            `;

        }

    });

}

</script>