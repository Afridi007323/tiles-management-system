
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$customers = $customers ?? [];
$ledger = $ledger ?? [];
$selected_customer = (int)($selected_customer ?? 0);
$total_billing = (float)($total_billing ?? 0);
$total_paid = (float)($total_paid ?? 0);
$current_due = (float)($current_due ?? 0);
$from_date = $from_date ?? '';
$to_date = $to_date ?? '';
?>

<style>
.ledger-summary {
    background:#fff;
    border:1px solid #e8edf4;
    border-radius:14px;
    padding:20px;
    height:100%;
    box-shadow:0 4px 15px rgba(20,40,80,.05);
}
.ledger-label {color:#64748b;font-size:14px}
.ledger-number {font-size:24px;font-weight:750;margin-top:8px}
.ledger-filter {
    background:#fff;
    border:1px solid #e8edf4;
    padding:20px;
    border-radius:14px;
    margin-bottom:20px;
}
.ledger-table th {
    background:#f5f7fb;
    white-space:nowrap;
}
.ledger-table td {vertical-align:middle}
.product-details {
    white-space:pre-line;
    min-width:220px;
    font-size:13px;
}
@media print {
    .no-print, .sidebar, nav, footer {display:none!important}
    .container-fluid {padding:0!important}
    .card, .ledger-summary {box-shadow:none!important}
    body {background:#fff!important}
    .table {font-size:11px}
}
</style>

<div class="container-fluid py-3">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-book text-primary me-2"></i> Party Ledger
            </h2>
            <p class="text-muted mb-0">Customer-wise billing and payment statement</p>
        </div>

        <?php if ($selected_customer > 0): ?>
            <button type="button" class="btn btn-outline-primary no-print"
                    onclick="window.print()">
                <i class="fas fa-print me-1"></i> Print Ledger
            </button>
        <?php endif; ?>
    </div>

    <form method="get"
          action="<?= base_url('ManagementController/party_ledger') ?>"
          class="ledger-filter no-print">

        <div class="row g-3">

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Select Customer</label>
                <select name="customer_id" class="form-select" required>
                    <option value="">-- Select Customer --</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?= (int)$customer['id'] ?>"
                            <?= $selected_customer === (int)$customer['id'] ? 'selected' : '' ?>>
                            <?= html_escape($customer['name']) ?>
                            <?= !empty($customer['mobile'])
                                ? ' - ' . html_escape($customer['mobile']) : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold">From Date</label>
                <input type="date" name="from_date" class="form-control"
                       value="<?= html_escape($from_date) ?>">
            </div>

            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold">To Date</label>
                <input type="date" name="to_date" class="form-control"
                       value="<?= html_escape($to_date) ?>">
            </div>

            <div class="col-12 col-md-2 d-grid align-self-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i> View Ledger
                </button>
            </div>

        </div>
    </form>

    <?php if ($selected_customer > 0): ?>

        <div class="row g-3 mb-4">

            <div class="col-12 col-md-4">
                <div class="ledger-summary">
                    <div class="ledger-label">Total Billing (All Time)</div>
                    <div class="ledger-number text-primary">
                        ₹<?= number_format($total_billing, 2) ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="ledger-summary">
                    <div class="ledger-label">Total Payments (All Time)</div>
                    <div class="ledger-number text-success">
                        ₹<?= number_format($total_paid, 2) ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="ledger-summary">
                    <div class="ledger-label">Current Due</div>
                    <div class="ledger-number text-danger">
                        ₹<?= number_format($current_due, 2) ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Customer Ledger Entries</h5>
                <small class="text-muted">
                    Running balance for the transactions shown below
                </small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover ledger-table mb-0">

                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Reference</th>
                        <th>Products / Notes</th>
                        <th>Payment Mode</th>
                        <th class="text-end">Bill (Debit)</th>
                        <th class="text-end">Payment (Credit)</th>
                        <th class="text-end">Balance</th>
                        <th class="no-print">Receipt</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (!empty($ledger)): ?>

                        <?php foreach ($ledger as $row): ?>
                            <tr>
                                <td>
                                    <?= !empty($row['transaction_date'])
                                        ? html_escape(date('d M Y',
                                            strtotime($row['transaction_date'])))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?php if (($row['type'] ?? '') === 'Sale'): ?>
                                        <span class="badge bg-primary">Bill</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Payment</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= html_escape($row['reference'] ?? '-') ?></td>

                                <td class="product-details">
                                    <?= html_escape($row['details'] ?? '-') ?>
                                </td>

                                <td><?= html_escape($row['payment_mode'] ?? '-') ?></td>

                                <td class="text-end">
                                    <?php if ((float)$row['debit'] > 0): ?>
                                        ₹<?= number_format((float)$row['debit'], 2) ?>
                                    <?php else: ?>-<?php endif; ?>
                                </td>

                                <td class="text-end text-success">
                                    <?php if ((float)$row['credit'] > 0): ?>
                                        ₹<?= number_format((float)$row['credit'], 2) ?>
                                    <?php else: ?>-<?php endif; ?>
                                </td>

                                <td class="text-end fw-bold">
                                    ₹<?= number_format((float)$row['balance'], 2) ?>
                                </td>

                                <td class="no-print">
                                    <?php if (
                                        ($row['type'] ?? '') === 'Sale'
                                        && !empty($row['invoice_id'])
                                    ): ?>
                                        <a class="btn btn-sm btn-outline-primary"
                                           target="_blank"
                                           href="<?= base_url(
                                               'ManagementController/print_full_receipt/'
                                               . (int)$row['invoice_id']
                                           ) ?>">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                    <?php else: ?>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                onclick="window.print()"
                                                title="Print ledger">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                No ledger entries found for this customer and date range.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>

    <?php else: ?>

        <div class="alert alert-info border-0 rounded-4">
            <i class="fas fa-info-circle me-2"></i>
            Select a customer to view their billing and payment ledger.
        </div>

    <?php endif; ?>
</div>
