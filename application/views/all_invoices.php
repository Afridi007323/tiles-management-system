<div class="container-fluid mt-4">

    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h5 class="text-secondary fw-bold mb-1">

                <i class="fas fa-file-invoice me-2 text-primary"></i>

                All Invoices

            </h5>

            <small class="text-muted">

                View and manage all generated invoices

            </small>

        </div>


        <a href="<?= base_url('BillingController') ?>" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i>
    Add New Invoice
</a>

    </div>


    <!-- INVOICE TABLE -->

    <div class="card border-0 shadow-sm">

        <div class="card-body">


            <?php if (!empty($invoices)): ?>


                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Invoice
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Bill Amount
                                </th>

                                <th>
                                    Old Due
                                </th>

                                <th>
                                    Received
                                </th>

                                <th>
                                    Due
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php foreach ($invoices as $invoice): ?>


                                <?php

                                $billAmount =
                                    (float)($invoice['bill_amount'] ?? 0);

                                $oldDue =
                                    (float)($invoice['old_due'] ?? 0);

                                $received =
                                    (float)($invoice['total_paid'] ?? 0);

                                $invoiceDue =
                                    (float)($invoice['invoice_due'] ?? 0);

                                $invoiceId =
                                    (int)($invoice['id'] ?? 0);

                                $invoiceNo =
                                    $invoice['invoice_no']
                                    ?? ('INV-' . $invoiceId);

                                $customerName =
                                    $invoice['customer_name']
                                    ?? 'Walk-in Customer';

                                $customerMobile =
                                    $invoice['customer_mobile']
                                    ?? '';

                                $invoiceDate =
                                    $invoice['created_at']
                                    ?? '';

                                ?>


                                <tr>


                                    <!-- INVOICE -->

                                    <td>

                                        <span
                                            class="fw-bold text-primary">

                                            #<?= html_escape(
                                                    $invoiceNo
                                                ) ?>

                                        </span>

                                    </td>


                                    <!-- DATE -->

                                    <td>

                                        <?php if ($invoiceDate): ?>

                                            <?= date(
                                                'd M Y',
                                                strtotime($invoiceDate)
                                            ) ?>

                                            <br>

                                            <small class="text-muted">

                                                <?= date(
                                                    'h:i A',
                                                    strtotime($invoiceDate)
                                                ) ?>

                                            </small>

                                        <?php else: ?>

                                            -

                                        <?php endif; ?>

                                    </td>


                                    <!-- CUSTOMER -->

                                    <td>

                                        <strong class="d-block">

                                            <?= html_escape(
                                                $customerName
                                            ) ?>

                                        </strong>


                                        <?php if ($customerMobile): ?>

                                            <small class="text-muted">

                                                <i
                                                    class="fas fa-phone me-1"></i>

                                                <?= html_escape(
                                                    $customerMobile
                                                ) ?>

                                            </small>

                                        <?php endif; ?>

                                    </td>


                                    <!-- BILL -->

                                    <td>

                                        <strong class="text-primary">

                                            ₹<?= number_format(
                                                    $billAmount,
                                                    2
                                                ) ?>

                                        </strong>

                                    </td>


                                    <!-- OLD DUE -->

                                    <td>

                                        <?php if ($oldDue > 0): ?>

                                            <span class="text-warning fw-semibold">

                                                ₹<?= number_format(
                                                        $oldDue,
                                                        2
                                                    ) ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="text-muted">

                                                ₹0.00

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- RECEIVED -->

                                    <td>

                                        <strong class="text-success">

                                            ₹<?= number_format(
                                                    $received,
                                                    2
                                                ) ?>

                                        </strong>

                                    </td>


                                    <!-- DUE -->

                                    <td>

                                        <?php if ($invoiceDue > 0): ?>

                                            <strong class="text-danger">

                                                ₹<?= number_format(
                                                        $invoiceDue,
                                                        2
                                                    ) ?>

                                            </strong>

                                        <?php else: ?>

                                            <strong class="text-success">

                                                ₹0.00

                                            </strong>

                                        <?php endif; ?>

                                    </td>


                                    <!-- STATUS -->

                                    <td>

                                        <?php if ($invoiceDue > 0): ?>

                                            <span
                                                class="badge rounded-pill bg-danger">

                                                <i
                                                    class="fas fa-clock me-1"></i>

                                                Due

                                            </span>

                                        <?php else: ?>

                                            <span
                                                class="badge rounded-pill bg-success">

                                                <i
                                                    class="fas fa-check-circle me-1"></i>

                                                Paid

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- ACTION -->

                                    <td class="text-center">

                                        <div
                                            class="d-flex
                                            justify-content-center
                                            gap-1">


                                            <!-- VIEW -->

                                            <a
                                                href="<?= base_url(
                                                            'BillingController/view_invoice/' .
                                                                $invoiceId
                                                        ) ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>


                                           <!-- PRINT -->
<a href="<?= base_url('ManagementController/print_invoice/' . $invoice['id']) ?>"
   target="_blank"
   class="btn btn-sm btn-dark">
    <i class="fas fa-print"></i>
    Print
</a>

                                        </div>

                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        </tbody>

                    </table>

                </div>


            <?php else: ?>


                <!-- EMPTY -->

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="fas fa-file-invoice fa-3x text-muted"></i>

                    </div>


                    <h5 class="text-muted">

                        No invoices found

                    </h5>


                    <p class="text-muted mb-4">

                        You have not generated any invoice yet.

                    </p>


                    <a
                        href="<?= base_url(
                                    'BillingController/billing'
                                ) ?>"
                        class="btn btn-primary">

                        <i class="fas fa-plus me-1"></i>

                        Create New Invoice

                    </a>

                </div>


            <?php endif; ?>


        </div>

    </div>

</div>


<style>
    .table th {

        white-space: nowrap;

        font-size: 13px;

    }


    .table td {

        font-size: 14px;

    }


    @media (max-width: 768px) {

        .container-fluid {

            padding-left: 10px;

            padding-right: 10px;

        }

    }
</style>