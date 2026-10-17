<div class="container-fluid py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                <i class="fas fa-history me-2"></i>
                Purchase History
            </h3>

            <p class="text-muted mb-0">
                View all purchase records
            </p>

        </div>


        <a
            href="<?= base_url('PurchaseController/new_purchase') ?>"
            class="btn btn-primary"
        >
            <i class="fas fa-plus me-1"></i>
            New Purchase
        </a>

    </div>


    <!-- Purchase Table -->
    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>ID</th>

                            <th>Purchase Date</th>

                            <th>Supplier</th>

                            <th>Invoice No.</th>

                            <th>Total</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($purchases)): ?>

                            <?php foreach ($purchases as $purchase): ?>

                                <tr>

                                    <td>
                                        <?= $purchase['id'] ?? '-' ?>
                                    </td>

                                    <td>
                                        <?= $purchase['purchase_date'] ?? '-' ?>
                                    </td>

                                    <td>
                                        <?= $purchase['supplier_name'] ?? '-' ?>
                                    </td>

                                    <td>
                                        <?= $purchase['invoice_no'] ?? '-' ?>
                                    </td>

                                    <td>
                                        ₹<?= number_format(
                                            (float)($purchase['total_amount'] ?? 0),
                                            2
                                        ) ?>
                                    </td>

                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-5"
                                >

                                    <i class="fas fa-box-open fa-2x mb-3"></i>

                                    <div>
                                        No purchase records found.
                                    </div>

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>