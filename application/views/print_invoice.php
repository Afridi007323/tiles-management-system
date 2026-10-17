<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= isset($invoice['invoice_no']) ? $invoice['invoice_no'] : $invoice['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    /* Screen styling */
    body { background: #f4f4f4; margin: 0; padding: 0; }
    
    .invoice-box { 
        padding: 25px; 
        max-width: 900px; 
        margin: 20px auto; 
        background: #fff; 
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    /* PDF & Print Settings */
    @media print {
        .no-print { display: none !important; }
        body, html { background: #fff; margin: 0; padding: 0; width: 100%; }
        .invoice-box { 
            border: none; 
            margin: 0 !important; 
            padding: 10px !important; 
            width: 100% !important; 
            max-width: 100% !important; 
            box-shadow: none !important;
        }
    }
    </style>
</head>
<body>

    <div class="invoice-box mt-4" id="invoice-content">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-primary fw-bold">Classic Traders Tiles & Marbles</h2>
                <p class="mb-1">Main Road, Nandgaon, Maharashtra</p>
                <p class="mb-0">Contact: +91 8421664986</p>
            </div>
            <div class="text-end">
                <h4>INVOICE</h4>
                <p class="mb-0">#<?= isset($invoice['invoice_no']) ? $invoice['invoice_no'] : 'INV-'.$invoice['id'] ?></p>
                <p>Date: <?= date('d-m-Y', strtotime(isset($invoice['billing_date']) ? $invoice['billing_date'] : (isset($invoice['created_at']) ? $invoice['created_at'] : 'now'))) ?></p>
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-6">
                <strong>Bill To:</strong><br>
                <?= isset($invoice['cust_name']) ? $invoice['cust_name'] : 'N/A' ?><br>
                <?= isset($invoice['address']) ? $invoice['address'] : '' ?><br>
                Phone: <?= isset($invoice['mobile']) ? $invoice['mobile'] : 'N/A' ?>
            </div>
        </div>

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
                <?php if(isset($old_due) && (float)$old_due > 0): ?>
                <tr style="background:#fff3cd;">
                    <td colspan="3"><strong>Previous Due</strong></td>
                    <td class="text-end">₹ <?= number_format((float)$old_due, 2) ?></td>
                </tr>
                <?php endif; ?>

                <!-- PRODUCTS LIST -->
                <?php if(!empty($items)): foreach($items as $item): ?>
                    <?php 
                        // Robust Variable Fallbacks to prevent Undefined Key Errors
                        $qty = isset($item['quantity']) ? $item['quantity'] : (isset($item['qty']) ? $item['qty'] : 1);
                        $rate = isset($item['unit_price']) ? $item['unit_price'] : (isset($item['rate']) ? $item['rate'] : 0);
                        $subtotal = isset($item['total_price']) ? $item['total_price'] : (isset($item['subtotal']) ? $item['subtotal'] : ($qty * $rate));
                        $p_name = !empty($item['product_name']) ? $item['product_name'] : 'Product #'.$item['product_id'];
                    ?>
                    <tr>
                        <td><?= $p_name ?></td>
                        <td class="text-center"><?= $qty ?></td>
                        <td class="text-end">₹ <?= number_format((float)$rate, 2) ?></td>
                        <td class="text-end">₹ <?= number_format((float)$subtotal, 2) ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No items added to this invoice.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Total Payable</td>
                    <td class="text-end">₹ <?= number_format((float)(isset($total_payable) ? $total_payable : $invoice['total_amount']), 2) ?></td>
                </tr>

                <tr>
                    <td colspan="3" class="text-end text-success">Paid Amount</td>
                    <td class="text-end text-success">₹ <?= number_format((float)(isset($invoice['paid_amount']) ? $invoice['paid_amount'] : 0), 2) ?></td>
                </tr>

                <tr>
                    <td colspan="3" class="text-end text-danger">Remaining Due</td>
                    <td class="text-end text-danger">₹ <?= number_format((float)(isset($remaining) ? $remaining : $invoice['due_amount']), 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-5 pt-3 border-top text-center text-muted">
            <p>Thank you for your business! <br>
               Visit Again 😊</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="no-print mt-3 mb-5 text-center">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Bill
        </button>
        
        <a href="<?= base_url('ManagementController/share_on_whatsapp/'.(isset($invoice['id']) ? $invoice['id'] : '')) ?>" class="btn btn-success" target="_blank">
            <i class="fab fa-whatsapp"></i> Share on WhatsApp
        </a>

        <button onclick="downloadPDF()" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Download PDF
        </button>
        
       <a href="<?= base_url('ManagementController/reports') ?>" 
   class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left"></i> Back
</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
    function downloadPDF() {
        const element = document.getElementById('invoice-content');
        
        const opt = {
            margin:       0.2, 
            filename:     'Invoice_<?= isset($invoice['invoice_no']) ? $invoice['invoice_no'] : $invoice['id'] ?>.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
    }
    </script>
</body>
</html>