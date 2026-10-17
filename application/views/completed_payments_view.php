<div class="container-fluid">

    <?php if($this->session->flashdata('toast_success')): ?>
    <!-- 🔥 TOAST (ADDED) -->
    <div id="toastMsg" class="toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-3" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <?= $this->session->flashdata('toast_success'); ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <?php endif; ?>




<div class="p-3">
    <input type="text" id="searchCompleted" class="form-control"
           placeholder="🔍 Search Customer / Invoice No...">
</div>






    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i> Completed Payments</h5>
        </div>

        <div class="card-body p-0">
<table id="completedTable" class="table table-hover align-middle mb-0">                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Total Bill</th>
                        <th>Total Paid</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                   <?php if (!empty($completed)): ?>

    <?php foreach($completed as $c): ?>

        <tr>
            <td>
                <?= !empty($c['billing_date'])
                    ? date('d-M-y', strtotime($c['billing_date']))
                    : '-'
                ?>
            </td>

            <td>
                <strong>
                    <?= html_escape($c['invoice_no'] ?? '-') ?>
                </strong>
            </td>

            <td>
                <?= html_escape($c['cust_name'] ?? '-') ?>
            </td>

            <td class="fw-bold">
                ₹<?= number_format(
                    (float)($c['total_amount'] ?? 0),
                    2
                ) ?>
            </td>

            <td class="text-success fw-bold">
                ₹<?= number_format(
                    (float)($c['paid_amount'] ?? 0),
                    2
                ) ?>
            </td>

            <td>
                <span class="badge bg-success">
                    Full Paid
                </span>
            </td>

            <td>
                <a
                    href="javascript:void(0)"
                    onclick="openReceipt(<?= (int)$c['id'] ?>)"
                    class="btn btn-primary btn-sm"
                >
                    Print Receipt
                </a>
            </td>
        </tr>

    <?php endforeach; ?>

<?php else: ?>

    <tr>
        <td
            colspan="7"
            class="text-center text-muted py-5"
        >
            <i class="fas fa-receipt fa-2x mb-3"></i>

            <div>
                No completed payments found.
            </div>
        </td>
    </tr>

<?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 🔥 JS (ADDED ONLY) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function openReceipt(id){
    window.location.href = "<?= base_url('ManagementController/print_full_receipt/') ?>" + id;
}

// 🔥 AUTO TOAST SHOW (ADDED)
window.addEventListener("DOMContentLoaded", function () {
    let toastEl = document.getElementById('toastMsg');
    if (toastEl) {
        let toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
});









document.getElementById("searchCompleted").addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#completedTable tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();

        if (text.includes(value)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});

</script>