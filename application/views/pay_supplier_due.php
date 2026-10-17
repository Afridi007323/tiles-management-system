<style>
    .supplier-due-summary {
        padding: 20px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
    }

    .supplier-summary-title {
        color: #0f172a;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .supplier-stat-card {
        height: 100%;
        padding: 16px;
        border-radius: 12px;
        border: 1px solid transparent;
        background: #fff;
    }

    .supplier-stat-label {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .supplier-stat-value {
        font-size: 22px;
        font-weight: 750;
        overflow-wrap: anywhere;
    }

    .stat-bill {
        background: #eff6ff;
        border-color: #bfdbfe;
    }

    .stat-bill .supplier-stat-value {
        color: #1d4ed8;
    }

    .stat-paid {
        background: #ecfdf5;
        border-color: #a7f3d0;
    }

    .stat-paid .supplier-stat-value {
        color: #047857;
    }

    .stat-due {
        background: #fff1f2;
        border-color: #fecdd3;
    }

    .stat-due .supplier-stat-value {
        color: #e11d48;
    }

    .stat-current {
        background: #f5f3ff;
        border-color: #ddd6fe;
    }

    .stat-current .supplier-stat-value {
        color: #6d28d9;
    }

    #dueText {
        display: block;
        margin-top: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
    }

    #supplierId:focus,
    #purchaseId:focus,
    #paymentAmount:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .14);
    }

    .payment-page .card {
        border-radius: 16px;
    }

    .payment-page .table thead th {
        background: #f1f5f9;
        color: #334155;
        font-size: 13px;
        white-space: nowrap;
        padding: 14px;
        border-bottom: 2px solid #e2e8f0;
    }

    .payment-page .table tbody td {
        padding: 14px;
        vertical-align: middle;
    }

    .payment-page .table tbody tr:hover {
        background: #f8fafc;
    }

    .purchase-number {
        display: inline-block;
        padding: 6px 9px;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
    }

    .due-badge {
        display: inline-block;
        background: #fff1f2;
        color: #be123c;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 700;
        white-space: nowrap;
    }

    .paid-amount {
        color: #047857;
        font-weight: 700;
        white-space: nowrap;
    }

    .bill-amount {
        color: #1d4ed8;
        font-weight: 700;
        white-space: nowrap;
    }

    @media (max-width: 767px) {
        .supplier-due-summary {
            padding: 14px;
        }

        .supplier-stat-card {
            padding: 13px;
        }

        .supplier-stat-value {
            font-size: 19px;
        }

        .payment-page .card-body {
            padding: 16px !important;
        }
    }
</style>


</style>










<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container-fluid py-3">
    <h3 class="fw-bold mb-1">Pay Supplier Due</h3>
    <p class="text-secondary mb-4">Record a payment against an outstanding purchase.</p>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= html_escape($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= html_escape($error) ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="post" action="<?= base_url('ManagementController/save_supplier_payment') ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                    value="<?= $this->security->get_csrf_hash() ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" id="supplierId" class="form-select" required>
                            <option value="">Select supplier</option>
                            <?php foreach (($suppliers ?? array()) as $s): ?>
                                <option value="<?= (int)$s['id'] ?>">
                                    <?= html_escape($s['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>





                        <div id="supplierDueSummary" class="supplier-due-summary mt-3" style="display:none;">

                            <div class="supplier-summary-title">
                                <i class="fas fa-chart-pie me-2 text-primary"></i>
                                Supplier Account Summary
                                <div class="small text-secondary fw-normal mt-1" id="supplierDueName"></div>
                            </div>

                            <div class="row g-3">

                                <div class="col-6">
                                    <div class="supplier-stat-card stat-bill">
                                        <div class="supplier-stat-label">
                                            <i class="fas fa-file-invoice me-1"></i> Total Bill
                                        </div>
                                        <div class="supplier-stat-value" id="supplierTotalBill">₹0.00</div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="supplier-stat-card stat-paid">
                                        <div class="supplier-stat-label">
                                            <i class="fas fa-circle-check me-1"></i> Total Paid
                                        </div>
                                        <div class="supplier-stat-value" id="supplierTotalPaid">₹0.00</div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="supplier-stat-card stat-current">
                                        <div class="supplier-stat-label">
                                            <i class="fas fa-wallet me-1"></i> Selected Invoice Due
                                        </div>
                                        <div class="supplier-stat-value" id="selectedInvoiceDue">₹0.00</div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="supplier-stat-card stat-due">
                                        <div class="supplier-stat-label">
                                            <i class="fas fa-hourglass-half me-1"></i> Total Remaining Due
                                        </div>
                                        <div class="supplier-stat-value" id="supplierDueAmount">₹0.00</div>
                                    </div>
                                </div>

                            </div>
                        </div>




                    </div>


                    <div class="col-md-6">
                        <label class="form-label">Payment Against</label>

                        



<select name="purchase_id" id="purchaseId" class="form-select" required>
    <option value="">Select payment option</option>

    <?php foreach (($dues ?? array()) as $d): ?>
        <option
            value="<?= (int)$d['id'] ?>"
            data-supplier="<?= (int)$d['supplier_id'] ?>"
            data-total="<?= (float)$d['total_amount'] ?>"
            data-paid="<?= (float)$d['paid_amount'] ?>"
            data-due="<?= (float)$d['due_amount'] ?>"
        >
            <?= html_escape($d['purchase_no']) ?>
            — Due ₹<?= number_format((float)$d['due_amount'], 2) ?>
        </option>
    <?php endforeach; ?>
</select>



                        <small class="text-secondary" id="dueText">
                            Overall Supplier Due select karne par payment outstanding bills mein adjust hogi.
                        </small>
                    </div>







                    <div class="col-md-4">
                        <label class="form-label">Payment Amount (₹)</label>
                        <input type="number" name="amount" id="paymentAmount"
                            class="form-control" min="0.01" step="0.01" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control"
                            value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Payment Mode</label>
                        <select name="payment_mode" class="form-select" required>
                            <option>Cash</option>
                            <option>UPI</option>
                            <option>Bank</option>
                            <option>Cheque</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Note (optional)</label>
                        <input name="note" class="form-control" maxlength="500"
                            placeholder="Transaction reference or remarks">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="fas fa-money-bill-wave me-1"></i> Save Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Outstanding Purchase Dues</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Purchase No.</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (($dues ?? array()) as $d): ?>
                            <tr>
                                <td><?= html_escape($d['purchase_no']) ?></td>
                                <td><?= html_escape($d['supplier_name'] ?? '') ?></td>
                                <td><?= html_escape($d['purchase_date'] ?? '') ?></td>
                                <td>₹<?= number_format((float)$d['total_amount'], 2) ?></td>
                                <td>₹<?= number_format((float)$d['paid_amount'], 2) ?></td>
                                <td class="text-danger fw-bold">₹<?= number_format((float)$d['due_amount'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($dues)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-secondary">No outstanding purchase dues.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
(function () {
    'use strict';

    const supplier = document.getElementById('supplierId');
    const purchase = document.getElementById('purchaseId');
    const amount = document.getElementById('paymentAmount');
    const dueText = document.getElementById('dueText');

    const summary = document.getElementById('supplierDueSummary');
    const summaryName = document.getElementById('supplierDueName');

    const totalBillEl = document.getElementById('supplierTotalBill');
    const totalPaidEl = document.getElementById('supplierTotalPaid');
    const totalDueEl = document.getElementById('supplierDueAmount');
    const selectedDueEl = document.getElementById('selectedInvoiceDue');

    if (!supplier || !purchase || !amount) {
        console.error('Supplier payment form fields not found. Check element IDs.');
        return;
    }

    // Controller se aane wali invoice details.
    const allOptions = <?= json_encode(
        array_values($dues ?? array()),
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    ) ?>;

    function money(value) {
        const number = Number(value) || 0;

        return '₹' + number.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function updatePurchases() {
        const supplierId = String(supplier.value || '').trim();
        const supplierName = supplier.selectedIndex >= 0
            ? supplier.options[supplier.selectedIndex].text.trim()
            : '';

        purchase.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Select payment option';
        purchase.appendChild(placeholder);

        amount.value = '';
        amount.removeAttribute('max');

        if (selectedDueEl) selectedDueEl.textContent = money(0);
        if (dueText) dueText.textContent = '';

        if (!supplierId) {
            if (summary) summary.style.display = 'none';
            return;
        }

        let totalBill = 0;
        let totalPaid = 0;
        let totalDue = 0;

        const supplierInvoices = allOptions.filter(function (invoice) {
            return String(invoice.supplier_id || '').trim() === supplierId;
        });

        supplierInvoices.forEach(function (invoice) {
            totalBill += Number(invoice.total_amount) || 0;
            totalPaid += Number(invoice.paid_amount) || 0;
            totalDue += Number(invoice.due_amount) || 0;
        });

        totalBill = Math.round(totalBill * 100) / 100;
        totalPaid = Math.round(totalPaid * 100) / 100;
        totalDue = Math.round(totalDue * 100) / 100;

        if (summary) summary.style.display = 'block';
        if (summaryName) summaryName.textContent = supplierName;
        if (totalBillEl) totalBillEl.textContent = money(totalBill);
        if (totalPaidEl) totalPaidEl.textContent = money(totalPaid);

        if (totalDueEl) {
            totalDueEl.textContent = money(totalDue);
            totalDueEl.classList.toggle('text-success', totalDue <= 0);
            totalDueEl.classList.toggle('text-danger', totalDue > 0);
        }

        const outstandingInvoices = supplierInvoices.filter(function (invoice) {
            return (Number(invoice.due_amount) || 0) > 0;
        });

        if (totalDue > 0 && outstandingInvoices.length > 0) {
            const overallOption = document.createElement('option');
            overallOption.value = '0';
            overallOption.textContent =
                'Overall Supplier Due — ' + money(totalDue);

            overallOption.dataset.supplier = supplierId;
            overallOption.dataset.total = totalBill;
            overallOption.dataset.paid = totalPaid;
            overallOption.dataset.due = totalDue;

            purchase.appendChild(overallOption);
        }

        outstandingInvoices.forEach(function (invoice) {
            const option = document.createElement('option');

            option.value = String(invoice.id);
            option.dataset.supplier = String(invoice.supplier_id);
            option.dataset.total = Number(invoice.total_amount) || 0;
            option.dataset.paid = Number(invoice.paid_amount) || 0;
            option.dataset.due = Number(invoice.due_amount) || 0;

            option.textContent =
                String(invoice.purchase_no || ('Purchase #' + invoice.id)) +
                ' — Due ' + money(invoice.due_amount);

            purchase.appendChild(option);
        });

        if (supplierInvoices.length === 0) {
            if (dueText) {
                dueText.textContent =
                    'Is supplier ki koi purchase invoice nahi mili.';
            }
        } else if (totalDue <= 0) {
            if (dueText) {
                dueText.textContent =
                    'All purchase invoices are fully paid.';
            }
        } else if (dueText) {
            dueText.textContent =
                outstandingInvoices.length +
                ' outstanding invoice(s). Total due: ' + money(totalDue);
        }

        console.log('Selected supplier:', supplierId);
        console.log('Supplier invoices:', supplierInvoices);
        console.log('Total bill:', totalBill, 'Paid:', totalPaid, 'Due:', totalDue);
    }

    supplier.addEventListener('change', updatePurchases);

    purchase.addEventListener('change', function () {
        const option = purchase.options[purchase.selectedIndex];

        amount.value = '';
        amount.removeAttribute('max');

        if (!option || option.value === '') {
            if (selectedDueEl) selectedDueEl.textContent = money(0);
            return;
        }

        const due = Number(option.dataset.due) || 0;

        if (selectedDueEl) selectedDueEl.textContent = money(due);

        if (option.value !== '0') {
            amount.max = due.toFixed(2);
        }

        if (dueText) {
            dueText.textContent = option.value === '0'
                ? 'Total supplier outstanding: ' + money(due)
                : 'Selected invoice remaining due: ' + money(due);
        }
    });

    amount.addEventListener('input', function () {
        const option = purchase.options[purchase.selectedIndex];

        if (!option || option.value === '') return;

        const due = Number(option.dataset.due) || 0;
        const paying = Number(amount.value) || 0;

        if (dueText) {
            dueText.textContent =
                'Due: ' + money(due) +
                ' | After payment: ' + money(Math.max(0, due - paying));
        }
    });

    // Initial display, including after a page reload.
    updatePurchases();
})();
</script>