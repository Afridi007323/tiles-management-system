<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>










    <div class="header">
        <h1><?= $shop->shop_name ?></h1>
        <p><?= $shop->address ?> | Mob: <?= $shop->mobile ?></p>
        <hr>
        <h3>INVOICE</h3>
    </div>

    <p><strong>Invoice No:</strong> #<?= $invoice['invoice_no'] ?></p>
    <p><strong>Customer:</strong> <?= $invoice['cust_name'] ?></p>

    <table>
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Item Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item): ?>
            <tr>
                <td><?= $item['item_name'] ?></td>
                <td><?= $item['qty'] ?></td>
                <td>₹<?= number_format($item['price'], 2) ?></td>
                <td>₹<?= number_format($item['qty'] * $item['price'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right">Grand Total:</th>
                <th>₹<?= number_format($invoice['total_amount'], 2) ?></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>