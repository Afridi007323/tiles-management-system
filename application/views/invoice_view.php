<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$invoice_id = (int)($invoice['id'] ?? 0);
$invoice_no = $invoice['invoice_no'] ?? ('INV-' . str_pad($invoice_id, 6, '0', STR_PAD_LEFT));

$customer_name = $invoice['customer_name'] ?? 'Walk-in Customer';
$customer_mobile = $invoice['customer_mobile'] ?? '-';
$customer_address = $invoice['customer_address'] ?? '-';

$billing_date = $invoice['billing_date'] ?? '';
$bill_amount = (float)($invoice['total_amount'] ?? 0);
$old_due = (float)($invoice['old_due'] ?? 0);
$invoice_due = (float)($invoice['due_amount'] ?? 0);

$total_paid = 0;

foreach ($payments as $payment) {
    $total_paid += (float)($payment['paid_amount'] ?? 0);
}
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-file-invoice me-2"></i>
                Invoice Details
            </h4>

            <div class="text-muted">
                <?= htmlspecialchars($invoice_no) ?>
            </div>
        </div>

        <div class="d-flex gap-2">

            <a
                href="<?= base_url('BillingController/all_invoices') ?>"
                class="btn btn-outline-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

            <a
                href="<?= base_url('ManagementController/print_full_receipt/' . $invoice_id) ?>"
                target="_blank"
                class="btn btn-dark"
            >
                <i class="fas fa-print me-1"></i>
                Print
            </a>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <!-- HEADER -->

            <div class="row mb-4">

                <div class="col-md-6">

                    <h3 class="fw-bold mb-1">
                        Classic Traders
                    </h3>

                    <div class="text-muted">
                        Tiles & Marbles
                    </div>

                </div>


                <div class="col-md-6 text-md-end mt-3 mt-md-0">

                    <h4 class="fw-bold mb-1">
                        INVOICE
                    </h4>

                    <div>
                        <strong>
                            <?= htmlspecialchars($invoice_no) ?>
                        </strong>
                    </div>

                    <div class="text-muted">

                        <?php if ($billing_date): ?>

                            <?= date('d-M-Y', strtotime($billing_date)) ?>

                        <?php else: ?>

                            -

                        <?php endif; ?>

                    </div>

                </div>

            </div>


            <hr>


            <!-- CUSTOMER -->

            <div class="row mb-4">

                <div class="col-md-6">

                    <h6 class="fw-bold text-uppercase text-muted mb-3">
                        Bill To
                    </h6>

                    <div class="fw-semibold fs-5">
                        <?= htmlspecialchars($customer_name) ?>
                    </div>

                    <div class="text-muted mt-1">
                        <i class="fas fa-phone me-1"></i>
                        <?= htmlspecialchars($customer_mobile) ?>
                    </div>

                    <div class="text-muted mt-1">
                        <i class="fas fa-location-dot me-1"></i>
                        <?= htmlspecialchars($customer_address) ?>
                    </div>

                </div>


                <div class="col-md-6 mt-4 mt-md-0">

                    <h6 class="fw-bold text-uppercase text-muted mb-3">
                        Invoice Summary
                    </h6>

                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Bill Amount
                        </span>

                        <strong>
                            ₹ <?= number_format($bill_amount, 2) ?>
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Previous Due
                        </span>

                        <strong>
                            ₹ <?= number_format($old_due, 2) ?>
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Received
                        </span>

                        <strong class="text-success">
                            ₹ <?= number_format($total_paid, 2) ?>
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between pt-2 border-top">

                        <span class="fw-bold">
                            Invoice Due
                        </span>

                        <strong class="text-danger">
                            ₹ <?= number_format($invoice_due, 2) ?>
                        </strong>

                    </div>

                </div>

            </div>


            <!-- ITEMS -->

            <h5 class="fw-bold mb-3">
                Items
            </h5>


            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-light">

                        <tr>

                            <th style="width:60px;">
                                #
                            </th>

                            <th>
                                Product
                            </th>

                            <th class="text-center">
                                Qty
                            </th>

                            <th class="text-end">
                                Rate
                            </th>

                            <th class="text-end">
                                Total
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($items)): ?>

                            <?php $sr = 1; ?>

                            <?php foreach ($items as $item): ?>

                                <?php
                                $product_name = $item['product_name'] ?? 'Product';
                                $quantity = (float)($item['quantity'] ?? 0);
                                $unit_price = (float)($item['unit_price'] ?? 0);
                                $total_price = (float)($item['total_price'] ?? ($quantity * $unit_price));
                                ?>

                                <tr>

                                    <td>
                                        <?= $sr++ ?>
                                    </td>

                                    <td class="fw-semibold">
                                        <?= htmlspecialchars($product_name) ?>
                                    </td>

                                    <td class="text-center">
                                        <?= rtrim(rtrim(number_format($quantity, 2), '0'), '.') ?>
                                    </td>

                                    <td class="text-end">
                                        ₹ <?= number_format($unit_price, 2) ?>
                                    </td>

                                    <td class="text-end fw-semibold">
                                        ₹ <?= number_format($total_price, 2) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="5" class="text-center text-muted py-4">

                                    <i class="fas fa-box-open fa-2x mb-2"></i>

                                    <div>
                                        No items found in this invoice.
                                    </div>

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>


                    <tfoot>

                        <tr>

                            <th colspan="4" class="text-end">
                                Bill Amount
                            </th>

                            <th class="text-end">
                                ₹ <?= number_format($bill_amount, 2) ?>
                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>


            <!-- PAYMENT HISTORY -->

            <?php if (!empty($payments)): ?>

                <h5 class="fw-bold mt-5 mb-3">
                    Payment History
                </h5>

                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Date
                                </th>

                                <th class="text-end">
                                    Amount
                                </th>

                                <th>
                                    Payment Mode
                                </th>

                                <th>
                                    Note
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php $payment_sr = 1; ?>

                            <?php foreach ($payments as $payment): ?>

                                <tr>

                                    <td>
                                        <?= $payment_sr++ ?>
                                    </td>

                                    <td>

                                        <?php
                                        $payment_date = $payment['payment_date'] ?? '';
                                        ?>

                                        <?= $payment_date
                                            ? date('d-M-Y', strtotime($payment_date))
                                            : '-'
                                        ?>

                                    </td>


                                    <td class="text-end fw-semibold text-success">

                                        ₹ <?= number_format(
                                            (float)($payment['paid_amount'] ?? 0),
                                            2
                                        ) ?>

                                    </td>


                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            <?= htmlspecialchars(
                                                $payment['payment_mode'] ?? '-'
                                            ) ?>

                                        </span>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $payment['note'] ?? '-'
                                        ) ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>


            <!-- FINAL SUMMARY -->

            <div class="row justify-content-end mt-4">

                <div class="col-md-5 col-lg-4">

                    <div class="card bg-light border-0">

                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-2">

                                <span>
                                    New Bill
                                </span>

                                <strong>
                                    ₹ <?= number_format($bill_amount, 2) ?>
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mb-2">

                                <span>
                                    Previous Due
                                </span>

                                <strong>
                                    ₹ <?= number_format($old_due, 2) ?>
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mb-2">

                                <span>
                                    Total Payable
                                </span>

                                <strong>
                                    ₹ <?= number_format(
                                        $bill_amount + $old_due,
                                        2
                                    ) ?>
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mb-2">

                                <span>
                                    Received
                                </span>

                                <strong class="text-success">
                                    ₹ <?= number_format($total_paid, 2) ?>
                                </strong>

                            </div>


                            <hr>


                            <div class="d-flex justify-content-between">

                                <span class="fw-bold">
                                    Due
                                </span>

                                <strong class="text-danger fs-5">
                                    ₹ <?= number_format(
                                        $invoice_due,
                                        2
                                    ) ?>
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>