```php
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$p = $purchase;

$totalAmount = (float)($p['total_amount'] ?? 0);
$paidAmount  = (float)($p['paid_amount'] ?? 0);
$dueAmount   = (float)($p['due_amount'] ?? 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Purchase Receipt - <?= html_escape($p['purchase_no'] ?? '') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #eff6ff;
            --success: #15803d;
            --success-light: #f0fdf4;
            --danger: #dc2626;
            --danger-light: #fef2f2;
            --dark: #172033;
            --muted: #64748b;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 15px;
            background: #f1f5f9;
            color: var(--dark);
            font-family: Arial, Helvetica, sans-serif;
        }

        .receipt {
            max-width: 900px;
            margin: auto;
            padding: 38px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .08);
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding-bottom: 24px;
            border-bottom: 3px solid var(--primary);
        }

        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            margin-right: 12px;
            border-radius: 12px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 23px;
            vertical-align: middle;
        }

        .receipt-title {
            margin: 0;
            color: var(--dark);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .receipt-subtitle {
            margin-top: 6px;
            color: var(--muted);
            font-size: 13px;
        }

        .receipt-badge {
            padding: 8px 13px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .section-label {
            margin-bottom: 12px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .supplier-box,
        .invoice-box {
            height: 100%;
            padding: 20px;
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .supplier-name {
            margin-bottom: 8px;
            color: var(--dark);
            font-size: 19px;
            font-weight: 800;
        }

        .supplier-detail {
            margin-top: 5px;
            color: #475569;
            overflow-wrap: anywhere;
        }

        .invoice-detail {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px dashed var(--border);
        }

        .invoice-detail:last-child {
            border-bottom: 0;
        }

        .invoice-detail span {
            color: var(--muted);
        }

        .invoice-detail strong {
            text-align: right;
            overflow-wrap: anywhere;
        }

        .table-wrap {
            margin-top: 28px;
        }

        .table {
            margin-bottom: 0;
            border-color: var(--border);
        }

        .table thead th {
            padding: 14px 12px;
            background: #eff6ff;
            color: #1e40af;
            font-size: 13px;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 14px 12px;
            color: #334155;
            vertical-align: middle;
        }

        .product-name {
            font-weight: 700;
            color: var(--dark) !important;
        }

        .summary {
            max-width: 430px;
            margin: 25px 0 0 auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
            padding: 16px 18px;
            border: 1px solid var(--border);
            border-radius: 11px;
        }

        .summary-label {
            font-size: 14px;
            font-weight: 600;
        }

        .summary-value {
            font-size: 19px;
            font-weight: 800;
            white-space: nowrap;
        }

        /* Total amount - Blue */
        .total-row {
            background: var(--primary-light);
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        /* Paid amount - Green */
        .paid-row {
            background: var(--success-light);
            border-color: #bbf7d0;
            color: var(--success);
        }

        /* Outstanding due - Red */
        .due-row {
            background: var(--danger-light);
            border: 1px solid #fecaca;
            color: var(--danger);
        }

        .note-box {
            margin-top: 25px;
            padding: 16px;
            border-left: 4px solid #94a3b8;
            border-radius: 8px;
            background: #f8fafc;
            color: #475569;
            overflow-wrap: anywhere;
        }

        .receipt-footer {
            margin-top: 35px;
            padding-top: 18px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            text-align: center;
            font-size: 12px;
        }

        .receipt-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 0 auto 18px;
        }

        @media (max-width: 600px) {
            body {
                padding: 12px 7px;
            }

            .receipt {
                padding: 18px 13px;
                border-radius: 10px;
            }

            .receipt-header {
                align-items: flex-start;
            }

            .receipt-title {
                font-size: 19px;
            }

            .brand-icon {
                width: 38px;
                height: 38px;
                margin-right: 5px;
                font-size: 18px;
            }

            .receipt-badge {
                padding: 6px 8px;
                font-size: 10px;
            }

            .supplier-box,
            .invoice-box {
                padding: 14px;
            }

            .summary {
                max-width: 100%;
            }

            .summary-row {
                padding: 13px 11px;
            }

            .summary-value {
                font-size: 16px;
            }
        }

        @media print {
            @page {
                size: A4;
                margin: 12mm;
            }

            body {
                padding: 0;
                background: #fff;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .receipt {
                max-width: none;
                padding: 12px;
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }

            .summary-row,
            .table thead th,
            .receipt-badge,
            .brand-icon {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            tr,
            .summary-row,
            .supplier-box,
            .invoice-box {
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>

<div class="receipt">

    <div class="receipt-actions no-print">
        <button onclick="window.print()" class="btn btn-primary px-4">
            Print / Save PDF
        </button>

        <button onclick="window.close()" class="btn btn-outline-secondary">
            Close
        </button>
    </div>

    <!-- Header -->
    <div class="receipt-header">
        <div>
            <h1 class="receipt-title">
                <span class="brand-icon">&#9632;</span>
                PURCHASE RECEIPT
            </h1>
            <div class="receipt-subtitle">
                Supplier Purchase Record
            </div>
        </div>

        <span class="receipt-badge">PURCHASE</span>
    </div>

    <!-- Supplier and invoice details -->
    <div class="row g-3 mt-3">

        <div class="col-sm-6">
            <div class="supplier-box">
                <div class="section-label">SUPPLIER DETAILS</div>

                <div class="supplier-name">
                    <?= html_escape($p['supplier_name'] ?? 'Unknown') ?>
                </div>

                <?php if (!empty($p['supplier_mobile'])): ?>
                    <div class="supplier-detail">
                        <strong>Mobile:</strong>
                        <?= html_escape($p['supplier_mobile']) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($p['supplier_address'])): ?>
                    <div class="supplier-detail">
                        <?= nl2br(html_escape($p['supplier_address'])) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="invoice-box">
                <div class="section-label">PURCHASE DETAILS</div>

                <div class="invoice-detail">
                    <span>Purchase No.</span>
                    <strong><?= html_escape($p['purchase_no'] ?? '') ?></strong>
                </div>

                <div class="invoice-detail">
                    <span>Purchase Date</span>
                    <strong><?= html_escape($p['purchase_date'] ?? '') ?></strong>
                </div>
            </div>
        </div>

    </div>

    <!-- Products -->
    <div class="table-wrap">
        <h5 class="fw-bold mb-3">Purchased Products</h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Rate</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach (($items ?? array()) as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>

                        <td class="product-name">
                            <?= html_escape($item['product_name'] ?? '') ?>
                        </td>

                        <td class="text-end">
                            <?= number_format((float)($item['quantity'] ?? 0), 2) ?>
                        </td>

                        <td class="text-end">
                            ₹<?= number_format((float)($item['unit_price'] ?? 0), 2) ?>
                        </td>

                        <td class="text-end fw-bold">
                            ₹<?= number_format((float)($item['total_price'] ?? 0), 2) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">
                            No products found for this purchase.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Color-coded summary -->
    <div class="summary">

        <div class="summary-row total-row">
            <span class="summary-label">Total Amount</span>
            <strong class="summary-value">
                ₹<?= number_format($totalAmount, 2) ?>
            </strong>
        </div>

        <div class="summary-row paid-row">
            <span class="summary-label">✓ Paid Amount</span>
            <strong class="summary-value">
                ₹<?= number_format($paidAmount, 2) ?>
            </strong>
        </div>

        <div class="summary-row due-row">
            <span class="summary-label">⚠ Balance Due</span>
            <strong class="summary-value">
                ₹<?= number_format($dueAmount, 2) ?>
            </strong>
        </div>

    </div>

    <?php if (!empty($p['note'])): ?>
        <div class="note-box">
            <strong>Purchase Note</strong>
            <div class="mt-1"><?= nl2br(html_escape($p['note'])) ?></div>
        </div>
    <?php endif; ?>

    <div class="receipt-footer">
        <strong>MarblePOS</strong>
        <div class="mt-1">Thank you. Please retain this receipt for your records.</div>
        <div class="mt-1">This receipt records the purchase details shown above.</div>
    </div>

</div>

</body>
</html>
```