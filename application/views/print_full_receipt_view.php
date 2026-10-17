







<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt - <?= $inv['invoice_no'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        @media print {
            .no-print { display: none; }
        }

        .receipt-box {
            border: 2px solid #333;
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
        }
    </style>
</head>



<body onload="window.print()">


<div id="successToast" class="toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-3">
  <div class="d-flex">
    <div class="toast-body">
      ✅ Invoice saved successfully & ready to print!
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
  </div>
</div>


    <div class="receipt-box">
        <div class="text-center mb-4">
            <h2>Classic Tarders</h2>
            <p>Address: <?= $inv['address'] ?> | Mobile: <?= $inv['mobile'] ?></p>
            <hr>
            <h4>FINAL PAYMENT RECEIPT</h4>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <strong>Customer:</strong> <?= $inv['cust_name'] ?><br>
                <strong>Invoice No:</strong> <?= $inv['invoice_no'] ?>
            </div>
            <div class="col-6 text-end">
                <strong>Date:</strong> <?= date('d-M-Y', strtotime($inv['billing_date'])) ?><br>
                <strong>Status:</strong> <span class="badge bg-success">PAID FULL</span>
            </div>
        </div>


        <hr>

<h5 class="mt-3">Items Details</h5>

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>Item</th>
            <th class="text-center">Qty</th>
            <th class="text-end">Rate</th>
            <th class="text-end">Total</th>
        </tr>
    </thead>

    <tbody>
        <!-- PREVIOUS DUE -->
        <?php if(!empty($old_due) && $old_due > 0): ?>
        <tr style="background:#fff3cd;">
            <td colspan="3"><strong>Previous Due</strong></td>
            <td class="text-end">₹ <?= number_format((float)$old_due, 2) ?></td>
        </tr>
        <?php endif; ?>

        <!-- PRODUCTS LOOP -->
        <?php if(!empty($items)): foreach($items as $item): ?>
            <?php 
                // Flexible field mappings to avoid any undefined key warning
                $qty = $item['qty'] ?? $item['quantity'] ?? 1;
                $rate = $item['unit_price'] ?? $item['rate'] ?? $item['price'] ?? 0;
                $total = $item['total_price'] ?? $item['subtotal'] ?? ($qty * $rate);
            ?>
            <tr>
                <td><?= !empty($item['product_name']) ? $item['product_name'] : 'Item #'.$item['id'] ?></td>
                <td class="text-center"><?= $qty ?></td>
                <td class="text-end">₹ <?= number_format((float)$rate, 2) ?></td>
                <td class="text-end">₹ <?= number_format((float)$total, 2) ?></td>
            </tr>
        <?php endforeach; else: ?>
            <tr>
                <td colspan="4" class="text-center">No items found in this invoice.</td>
            </tr>
        <?php endif; ?>
    </tbody>

    <tfoot>
        <tr class="fw-bold">
            <td colspan="3" class="text-end">Total Payable</td>
            <td class="text-end">₹ <?= number_format((float)($total_payable ?? $invoice['total_amount']), 2) ?></td>
        </tr>

        <tr>
            <td colspan="3" class="text-end text-success">Paid Amount</td>
            <td class="text-end text-success">₹ <?= number_format((float)($invoice['paid_amount'] ?? 0), 2) ?></td>
        </tr>

        <tr>
            <td colspan="3" class="text-end text-danger">Remaining Due</td>
            <td class="text-end text-danger">₹ <?= number_format((float)($remaining ?? $invoice['due_amount']), 2) ?></td>
        </tr>
    </tfoot>
</table>






        <hr>

<div class="row">
    <div class="col-7 text-end">Previous Due:</div>
    <div class="col-5 text-end">₹ <?= number_format($old_due, 2) ?></div>

    <div class="col-7 text-end">New Bill:</div>
    <div class="col-5 text-end">₹ <?= number_format($new_bill, 2) ?></div>

    <hr>
    <div class="col-7 text-end"><strong>Total Payable:</strong></div>
    <div class="col-5 text-end"><strong>₹ <?= number_format($total_payable, 2) ?></strong></div>

    <div class="col-7 text-end text-success">Total Paid Today:</div>
    <div class="col-5 text-end text-success">₹ <?= number_format($total_paid, 2) ?></div>

    <hr>
    <div class="col-7 text-end text-danger"><strong>Remaining Due:</strong></div>
    <div class="col-5 text-end text-danger"><strong>₹ <?= number_format($remaining, 2) ?></strong></div>
</div>






        <div class="mt-5 pt-4 text-end">
            <p>_______________________<br>Authorized Signature</p>
        </div>
    </div>
  <div class="text-center no-print mt-4 mb-5">
    <button onclick="printBill()" class="btn btn-primary">
    Print Bill
</button>


    <!-- Toast -->
<div id="printToast" class="toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-3" role="alert">
  <div class="d-flex">
    <div class="toast-body">
      ✅ Receipt ready successfully!
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
  </div>
</div>

    <?php 
        $whatsapp_msg = "Hello *" . $inv['cust_name'] . "*, your payment of ₹" . number_format($inv['paid_amount'], 2) . " for Invoice #" . $inv['invoice_no'] . " is fully settled. Thank you!";
        $whatsapp_url = "https://api.whatsapp.com/send?phone=91" . $inv['mobile'] . "&text=" . urlencode($whatsapp_msg);
    ?>
    <a href="<?= $whatsapp_url ?>" target="_blank" class="btn btn-success me-2">
        <i class="fab fa-whatsapp"></i> Share on WhatsApp
    </a>

    <a href="<?= base_url('ManagementController/reports') ?>" class="btn btn-dark ms-2">
        <i class="fas fa-home"></i> Dashboard
    </a>

  <a href="<?= base_url('BillingController') ?>" 
   class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left"></i> Back
</a>
















a

</div>








<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function showToast(){
    const toastEl = document.getElementById('printToast');
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

function printBill(){
    showToast();   // 👈 immediate feedback (BEST UX)
    window.print();
}





window.onload = function() {

    // Toast show
    let toastEl = document.getElementById('successToast');
    let toast = new bootstrap.Toast(toastEl);
    toast.show();

    // Print after delay
    setTimeout(function(){
        window.print();
    }, 1000);
};











window.addEventListener('load', function () {
    setTimeout(function () {
        window.print();
    }, 500);
})
</script>




</body>
</html>