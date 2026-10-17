
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold">Purchase History</h3>
            <p class="text-secondary mb-0">Search and review supplier purchases.</p>
        </div>
        <a href="<?= base_url('ManagementController/purchases') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Purchase
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <input id="historySearch" class="form-control mb-3"
                   placeholder="Search purchase no. or supplier...">

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="historyTable">
                    <thead class="table-light">
                        <tr>
                            <th>Purchase No.</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach (($purchases ?? array()) as $p): ?>
                        <tr>
                            <td><?= html_escape($p['purchase_no'] ?? '') ?></td>
                            <td><?= !empty($p['purchase_date']) ? html_escape(date('d M Y', strtotime($p['purchase_date']))) : '-' ?></td>
                            <td><?= html_escape($p['supplier_name'] ?? 'Unknown') ?></td>
                            <td>₹<?= number_format((float)($p['total_amount'] ?? 0), 2) ?></td>
                            <td>₹<?= number_format((float)($p['paid_amount'] ?? 0), 2) ?></td>
                            <td class="text-danger fw-bold">₹<?= number_format((float)($p['due_amount'] ?? 0), 2) ?></td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary"
                                   target="_blank"
                                   href="<?= base_url('ManagementController/purchase_receipt/' . (int)$p['id']) ?>">
                                    <i class="fas fa-receipt"></i> Receipt
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($purchases)): ?>
                        <tr><td colspan="7" class="text-center text-secondary py-4">No purchases found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('historySearch').addEventListener('input', function () {
    const query = this.value.toLowerCase();
    document.querySelectorAll('#historyTable tbody tr').forEach(function (row) {
        row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
    });
});
</script>
