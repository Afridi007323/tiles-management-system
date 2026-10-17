
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entries = $entries ?? [];
$from_date = $from_date ?? date('Y-m-d');
$to_date = $to_date ?? date('Y-m-d');
$total_sales = (float)($total_sales ?? 0);
$total_received = (float)($total_received ?? 0);
?>

<style>
.daily-wrap { padding: 10px 0 25px; }
.daily-card {
    background: #fff; border: 1px solid #e8edf4;
    border-radius: 14px; padding: 20px; height: 100%;
    box-shadow: 0 4px 15px rgba(20,40,80,.05);
}
.daily-number { font-size: 25px; font-weight: 750; margin-top: 8px; }
.daily-label { color: #64748b; font-size: 14px; }
.daily-table th { background: #f5f7fb; white-space: nowrap; }
.daily-table td { vertical-align: middle; }
.daily-filter {
    background: #fff; border: 1px solid #e8edf4;
    padding: 18px; border-radius: 14px; margin-bottom: 20px;
}
@media(max-width:767px) {
    .daily-card { padding: 15px; }
    .daily-number { font-size: 22px; }
}
</style>

<div class="container-fluid daily-wrap">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-exchange-alt text-primary me-2"></i>
                Daily Transactions
            </h2>
            <div class="text-muted">Date-wise sales and payment records</div>
        </div>
        <button type="button" class="btn btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print Report
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="daily-card">
                <div class="daily-label">Total Sales</div>
                <div class="daily-number text-primary">
                    ₹<?= number_format($total_sales, 2) ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="daily-card">
                <div class="daily-label">Payments Received</div>
                <div class="daily-number text-success">
                    ₹<?= number_format($total_received, 2) ?>
                </div>
            </div>
        </div>
    </div>

    <form method="get"
          action="<?= base_url('ManagementController/daily_entries') ?>"
          class="daily-filter">

        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">From Date</label>
                <input type="date" name="from_date" class="form-control"
                       value="<?= html_escape($from_date) ?>" required>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">To Date</label>
                <input type="date" name="to_date" class="form-control"
                       value="<?= html_escape($to_date) ?>" required>
            </div>

            <div class="col-12 col-md-4 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <a class="btn btn-light border"
                   href="<?= base_url('ManagementController/daily_entries') ?>">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between gap-2">
            <h5 class="fw-bold mb-0">Transaction History</h5>
            <span class="badge bg-primary">
                <?= count($entries) ?> Records
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover daily-table mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Customer</th>
                        <th>Reference</th>
                        <th>Payment Mode</th>
                        <th class="text-end">Sales Amount</th>
                        <th class="text-end">Received</th>
                        <th class="text-end">Remaining Due</th>
                    </tr>
                </thead>
                
<tbody>
    <?php if (!empty($entries)): ?>
        <?php foreach ($entries as $entry): ?>
            <tr>
                <td>
                    <?= !empty($entry['payment_date'])
                        ? html_escape($entry['payment_date'])
                        : '-' ?>
                </td>

                <td>
                    <span class="badge bg-success">Payment</span>
                </td>

                <td>
                    <div class="fw-semibold">
                        <?= html_escape($entry['customer_name'] ?? 'Unknown Customer') ?>
                    </div>
                    <small class="text-muted">
                        <?= html_escape($entry['customer_mobile'] ?? '') ?>
                    </small>
                </td>

                <td>
                    <?= html_escape($entry['invoice_no'] ?? '-') ?>
                </td>

                <td>
                    <?= html_escape($entry['payment_mode'] ?? 'Cash') ?>
                </td>

                <td>
                    ₹<?= number_format((float)($entry['invoice_amount'] ?? 0), 2) ?>
                </td>

                <td class="text-success fw-semibold">
                    ₹<?= number_format((float)($entry['paid_amount'] ?? 0), 2) ?>

                
                </td>



<td class="text-end text-danger fw-bold">
    ₹<?= number_format(
        (float) ($entry['remaining_due'] ?? 0),
        2
    ) ?>
</td>


            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8" class="text-center text-muted py-4">
                No transactions found for the selected date range.
            </td>
        </tr>
    <?php endif; ?>
</tbody>
            </table>
        </div>
    </div>
</div>