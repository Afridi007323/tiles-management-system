
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid py-3">
    <h3 class="fw-bold mb-1">Supplier Ledger</h3>
    <p class="text-secondary mb-4">View supplier purchases and payments.</p>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="get" action="<?= base_url('ManagementController/supplier_ledger') ?>">
                <label class="form-label">Select Supplier</label>
                <div class="d-flex flex-wrap gap-2">
                    <select name="supplier_id" class="form-select" style="max-width:400px" required>
                        <option value="">Choose supplier</option>
                        <?php foreach (($suppliers ?? array()) as $s): ?>
                            <option value="<?= (int)$s['id'] ?>"
                                <?= (int)$selected_supplier === (int)$s['id'] ? 'selected' : '' ?>>
                                <?= html_escape($s['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-primary">View Ledger</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($supplier)): ?>
        <?php
        $totalPurchases = 0;
        $totalPayments = 0;
        foreach ($purchases as $purchase) {
            $totalPurchases += (float)$purchase['total_amount'];
        }
        foreach ($payments as $payment) {
            $totalPayments += (float)$payment['amount'];
        }

        $openingBalance = (float)($supplier['opening_balance'] ?? 0);
        $outstanding = $openingBalance + $totalPurchases - $totalPayments;
        ?>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <h5 class="fw-bold"><?= html_escape($supplier['name']) ?></h5>
                <div class="text-secondary"><?= html_escape($supplier['mobile'] ?? '') ?></div>
                <div class="row g-3 mt-2">
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <small>Opening Balance</small>
                            <h5 class="fw-bold mb-0">₹<?= number_format($openingBalance, 2) ?></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <small>Total Purchases</small>
                            <h5 class="fw-bold mb-0">₹<?= number_format($totalPurchases, 2) ?></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <small>Total Payments Recorded</small>
                            <h5 class="fw-bold mb-0">₹<?= number_format($totalPayments, 2) ?></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-warning-subtle rounded-3">
                            <small>Calculated Outstanding</small>
                            <h5 class="fw-bold mb-0">₹<?= number_format($outstanding, 2) ?></h5>
                        </div>
                    </div>
                </div>
                <p class="small text-secondary mt-3 mb-0">
                    This calculation assumes the opening balance is money owed to the supplier.
                    It includes only payments recorded in supplier_payments.
                </p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Purchase Invoices</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr><th>Date</th><th>Purchase No.</th><th>Total</th><th>Paid</th><th>Due</th><th>Receipt</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($purchases as $p): ?>
                            <tr>
                                <td><?= html_escape($p['purchase_date'] ?? '') ?></td>
                                <td><?= html_escape($p['purchase_no'] ?? '') ?></td>
                                <td>₹<?= number_format((float)$p['total_amount'], 2) ?></td>
                                <td>₹<?= number_format((float)$p['paid_amount'], 2) ?></td>
                                <td>₹<?= number_format((float)$p['due_amount'], 2) ?></td>
                                <td>
                                    <a target="_blank" class="btn btn-sm btn-outline-primary"
                                       href="<?= base_url('ManagementController/purchase_receipt/' . (int)$p['id']) ?>">
                                        Receipt
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($purchases)): ?>
                            <tr><td colspan="6" class="text-center text-secondary">No purchase invoices found.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Supplier Payment History</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr><th>Date</th><th>Purchase ID</th><th>Mode</th><th>Note</th><th>Amount</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($payments as $p): ?>
                            <tr>
                                <td><?= html_escape($p['payment_date'] ?? '') ?></td>
                                <td><?= !empty($p['purchase_id']) ? '#' . (int)$p['purchase_id'] : 'Opening / General' ?></td>
                                <td><?= html_escape($p['payment_mode'] ?? '') ?></td>
                                <td><?= html_escape($p['note'] ?? '') ?></td>
                                <td class="fw-bold">₹<?= number_format((float)$p['amount'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($payments)): ?>
                            <tr><td colspan="5" class="text-center text-secondary">No supplier payments recorded.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
