<div class="container-fluid mt-4">

    <?php if ($this->session->flashdata('success')): ?>
        <div id="customerSuccessAlert"
             class="alert alert-success alert-dismissible fade show shadow-sm border-0">

            <i class="fas fa-check-circle me-2"></i>

            <?= html_escape($this->session->flashdata('success')) ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>


    <?php if ($this->session->flashdata('error')): ?>
        <div id="customerErrorAlert"
             class="alert alert-danger alert-dismissible fade show shadow-sm border-0">

            <i class="fas fa-exclamation-circle me-2"></i>

            <?= html_escape($this->session->flashdata('error')) ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>


    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h5 class="text-secondary fw-bold mb-0">
            <i class="fas fa-users me-2 text-primary"></i>
            Customer Directory
        </h5>

        <button type="button"
                class="btn btn-primary shadow-sm"
                data-bs-toggle="modal"
                data-bs-target="#addCustomerModal">

            <i class="fas fa-user-plus me-2"></i>
            Add New Customer

        </button>

    </div>


    <!-- CUSTOMER TABLE -->
    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0 customer-table">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                ID
                            </th>

                            <th>
                                Customer Details
                            </th>

                            <th>
                                Mobile
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Total Billing
                            </th>

                            <th>
                                Total Received
                            </th>

                            <th>
                                Due Amount
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($customers)): ?>

                            <?php foreach ($customers as $c): ?>

                                <?php

                                $totalBilling = (float) (
                                    $c['total_billing'] ?? 0
                                );

                                $totalPaid = (float) (
                                    $c['total_paid'] ?? 0
                                );

                                $currentDue = (float) (
                                    $c['current_due'] ?? 0
                                );

                                ?>

                                <tr>

                                    <!-- ID -->
                                    <td class="ps-3">

                                        <span class="fw-semibold text-muted">
                                            #CUST-<?= (int)$c['id'] ?>
                                        </span>

                                    </td>


                                    <!-- CUSTOMER -->
                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="customer-avatar me-3">
                                                <i class="fas fa-user"></i>
                                            </div>

                                            <div>

                                                <strong class="d-block customer-name">
                                                    <?= html_escape(
                                                        $c['name'] ?? ''
                                                    ) ?>
                                                </strong>

                                                <?php if (!empty($c['address'])): ?>

                                                    <small class="text-muted customer-address">
                                                        <?= html_escape(
                                                            $c['address']
                                                        ) ?>
                                                    </small>

                                                <?php else: ?>

                                                    <small class="text-muted customer-address">
                                                        Address not available
                                                    </small>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- MOBILE -->
                                    <td>

                                        <span class="text-muted mobile-number">

                                            <i class="fas fa-phone me-1"></i>

                                            <?= html_escape(
                                                $c['mobile'] ?? ''
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- STATUS -->
                                    <td>

                                        <?php if ($currentDue > 0): ?>

                                            <span class="badge rounded-pill bg-danger px-3 py-2">

                                                <i class="fas fa-clock me-1"></i>

                                                Pending Due

                                            </span>

                                        <?php else: ?>

                                            <span class="badge rounded-pill bg-success px-3 py-2">

                                                <i class="fas fa-check-circle me-1"></i>

                                                Clear

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- TOTAL BILLING -->
                                    <td>

                                        <span class="fw-bold text-primary amount-cell">

                                            ₹ <?= number_format(
                                                $totalBilling,
                                                2
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- TOTAL RECEIVED -->
                                    <td>

                                        <strong class="text-success amount-cell">

                                            ₹ <?= number_format(
                                                $totalPaid,
                                                2
                                            ) ?>

                                        </strong>

                                    </td>


                                    <!-- DUE AMOUNT -->
                                    <td>

                                        <strong class="text-danger amount-cell">

                                            ₹ <?= number_format(
                                                $currentDue,
                                                2
                                            ) ?>

                                        </strong>

                                    </td>


                                    <!-- ACTION -->
                                    <td class="text-center">

                                        <button
                                            type="button"
                                            class="btn btn-outline-primary btn-sm history-btn"
                                            onclick='showPaymentHistory(
                                                <?= (int)$c["id"] ?>,
                                                <?= json_encode(
                                                    $c["name"] ?? "",
                                                    JSON_HEX_TAG |
                                                    JSON_HEX_APOS |
                                                    JSON_HEX_QUOT |
                                                    JSON_HEX_AMP
                                                ) ?>
                                            )'>

                                            <i class="fas fa-history me-1"></i>
                                            History

                                        </button>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="8"
                                    class="text-center py-5 text-muted">

                                    <i class="fas fa-users fa-2x mb-3"></i>

                                    <div>
                                        No customers found.
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
     ADD CUSTOMER MODAL
========================================================= -->

<div class="modal fade"
     id="addCustomerModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content border-0 shadow">

            <form action="<?= base_url('CustomerController/save') ?>"
                  method="POST">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        <i class="fas fa-user-plus me-2"></i>

                        New Customer Entry

                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Full Name
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="Customer name"
                               required>

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Mobile Number
                        </label>

                        <input type="text"
                               name="mobile"
                               class="form-control"
                               placeholder="Mobile number"
                               required>

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Address
                        </label>

                        <textarea name="address"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Full address"></textarea>

                    </div>

                </div>


                <div class="modal-footer bg-light">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>

                        Save Customer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- =========================================================
     PAYMENT HISTORY MODAL
========================================================= -->

<div class="modal fade"
     id="historyModal"
     tabindex="-1"
     aria-hidden="true">

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

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>

            </div>


            <div class="modal-body p-0"
                 id="paymentHistoryContent">

                <div class="text-center py-5">

                    <div class="spinner-border text-primary"></div>

                    <div class="mt-3 text-muted">
                        Loading payment history...
                    </div>

                </div>

            </div>


            <div class="modal-footer bg-light">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>


<style>

.customer-avatar {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff;
    box-shadow: 0 4px 10px rgba(79, 70, 229, .25);
}

.customer-table th {
    white-space: nowrap;
    font-weight: 700;
    color: #111827;
    vertical-align: middle;
}

.customer-table td {
    vertical-align: middle;
}

.customer-name {
    font-size: 16px;
}

.customer-address {
    display: block;
    max-width: 220px;
    line-height: 1.5;
}

.mobile-number {
    white-space: nowrap;
}

.amount-cell {
    white-space: nowrap;
    font-size: 16px;
}

.history-btn {
    white-space: nowrap;
}

.history-modal-header {
    background: linear-gradient(135deg, #4f46e5, #2563eb);
}

.history-customer-header {
    padding: 18px 20px;
    background: linear-gradient(135deg, #eef2ff, #eff6ff);
    border-bottom: 1px solid #e5e7eb;
}

.history-customer-name {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
}

.history-customer-mobile {
    color: #64748b;
    margin-top: 3px;
}

.history-summary-card {
    border-radius: 14px;
    padding: 18px;
    min-height: 125px;
    color: #fff;
    position: relative;
    overflow: hidden;
    transition: transform .2s ease, box-shadow .2s ease;
}

.history-summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, .12);
}

.history-summary-card::after {
    content: "";
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    right: -30px;
    top: -35px;
    background: rgba(255, 255, 255, .12);
}

.history-billing {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
}

.history-payment {
    background: linear-gradient(135deg, #059669, #10b981);
}

.history-due {
    background: linear-gradient(135deg, #dc2626, #ef4444);
}

.history-summary-icon {
    font-size: 22px;
    margin-bottom: 8px;
}

.history-summary-title {
    font-size: 13px;
    opacity: .9;
}

.history-summary-amount {
    font-size: 24px;
    font-weight: 800;
    margin-top: 3px;
}

@media (max-width: 768px) {

    .history-summary-amount {
        font-size: 20px;
    }

    .customer-avatar {
        width: 34px;
        height: 34px;
        min-width: 34px;
    }

    .customer-name {
        font-size: 14px;
    }

    .customer-address {
        max-width: 150px;
    }

    .customer-table {
        min-width: 1000px;
    }

}

</style>


<script>

function formatMoney(amount) {

    amount = parseFloat(amount) || 0;

    return amount.toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}


function escapeHistoryHtml(value) {

    return String(value == null ? '' : value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

}


function formatHistoryDate(value) {

    if (!value) {
        return '-';
    }

    const date = new Date(value + 'T00:00:00');

    if (isNaN(date.getTime())) {
        return escapeHistoryHtml(value);
    }

    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });

}


function formatHistoryTime(value) {

    if (!value) {
        return '';
    }

    const date = new Date(
        value.replace(' ', 'T')
    );

    if (isNaN(date.getTime())) {
        return '';
    }

    return date.toLocaleTimeString('en-IN', {
        hour: '2-digit',
        minute: '2-digit'
    });

}


function showPaymentHistory(customerId, customerName) {

    const modalElement =
        document.getElementById('historyModal');

    const content =
        document.getElementById('paymentHistoryContent');


    if (!modalElement || !content) {

        console.error(
            'History modal/content not found'
        );

        return;
    }


    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );

    modal.show();


    content.innerHTML = `

        <div class="text-center py-5">

            <div class="spinner-border text-primary"></div>

            <div class="mt-3 text-muted">
                Loading payment history...
            </div>

        </div>

    `;


    $.ajax({

        url:
            "<?= base_url('CustomerController/payment_history/') ?>" +
            encodeURIComponent(customerId),

        type: 'GET',

        dataType: 'json',


        success: function(response) {

            console.log(
                'Payment History:',
                response
            );


            if (
                !response ||
                response.status !== true
            ) {

                content.innerHTML = `

                    <div class="alert alert-danger m-3">

                        <i class="fas fa-exclamation-circle me-2"></i>

                        ${
                            response &&
                            response.message
                                ? escapeHistoryHtml(
                                    response.message
                                )
                                : 'Unable to load history.'
                        }

                    </div>

                `;

                return;
            }


            const customer =
                response.customer || {};


            const transactions =
                Array.isArray(
                    response.transactions
                )
                    ? response.transactions
                    : [];


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


            let html = '';


            /* CUSTOMER HEADER */

            html += `

                <div class="history-customer-header">

                    <div class="history-customer-name">

                        <i class="fas fa-user-circle text-primary me-2"></i>

                        ${escapeHistoryHtml(
                            customer.name ||
                            customerName ||
                            'Customer'
                        )}

                    </div>

                    <div class="history-customer-mobile">

                        <i class="fas fa-phone me-2"></i>

                        ${escapeHistoryHtml(
                            customer.mobile || ''
                        )}

                    </div>

                </div>

            `;


            /* SUMMARY */

            html += `

                <div class="row g-3 p-3">

                    <div class="col-md-4">

                        <div class="history-summary-card history-billing">

                            <div class="history-summary-icon">
                                <i class="fas fa-file-invoice"></i>
                            </div>

                            <div class="history-summary-title">
                                Total Billing
                            </div>

                            <div class="history-summary-amount">
                                ₹${formatMoney(totalBilling)}
                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="history-summary-card history-payment">

                            <div class="history-summary-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>

                            <div class="history-summary-title">
                                Total Payment Received
                            </div>

                            <div class="history-summary-amount">
                                ₹${formatMoney(totalPaid)}
                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="history-summary-card history-due">

                            <div class="history-summary-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>

                            <div class="history-summary-title">
                                Current Due
                            </div>

                            <div class="history-summary-amount">
                                ₹${formatMoney(currentDue)}
                            </div>

                        </div>

                    </div>

                </div>

            `;


            /* HISTORY */

            html += `

                <div class="px-3 pb-3">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h6 class="fw-bold mb-0">

                            <i class="fas fa-history text-primary me-2"></i>

                            Payment History

                        </h6>

                        <span class="badge bg-light text-dark">

                            ${transactions.length}
                            Transactions

                        </span>

                    </div>

            `;


            if (transactions.length === 0) {

                html += `

                    <div class="text-center py-5 text-muted">

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
                    function(payment, index) {

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

                        } else if (
                            modeLower.includes('bank')
                        ) {

                            modeClass =
                                'bg-info text-dark';

                        } else if (
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
                                    <span class="fw-semibold">
                                        ${index + 1}
                                    </span>
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

                                    <span class="fw-bold text-success">

                                        ₹${formatMoney(amount)}

                                    </span>

                                </td>


                                <td>

                                    <span class="badge ${modeClass}">

                                        ${escapeHistoryHtml(mode)}

                                    </span>

                                </td>


                                <td>

                                    <span class="fw-semibold">

                                        ${
                                            payment.invoice_no
                                                ? '#' +
                                                  escapeHistoryHtml(
                                                      payment.invoice_no
                                                  )
                                                : '-'
                                        }

                                    </span>

                                </td>


                                <td>

                                    <span class="text-muted">

                                        ${escapeHistoryHtml(
                                            payment.note || '-'
                                        )}

                                    </span>

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


            html += `

                </div>

            `;


            content.innerHTML = html;

        },


        error: function(xhr) {

            console.error(
                'History AJAX error:',
                xhr.responseText
            );


            content.innerHTML = `

                <div class="alert alert-danger m-3">

                    <i class="fas fa-exclamation-triangle me-2"></i>

                    Unable to load payment history.

                </div>

            `;

        }

    });

}


/* AUTO HIDE FLASH MESSAGE */

setTimeout(function() {

    const success =
        document.getElementById(
            'customerSuccessAlert'
        );

    const error =
        document.getElementById(
            'customerErrorAlert'
        );


    if (success) {

        const alert =
            bootstrap.Alert.getOrCreateInstance(
                success
            );

        alert.close();

    }


    if (error) {

        const alert =
            bootstrap.Alert.getOrCreateInstance(
                error
            );

        alert.close();

    }

}, 5000);

</script>