<!-- =========================
     DASHBOARD STAT CARDS
========================= -->
<div class="row g-3">

    <!-- Today's Sale -->
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small opacity-75">
                            <i class="fas fa-wallet me-1"></i> Today's Sale
                        </div>
                        <h3 class="fw-bold mb-0 mt-2">
                            ₹ <?= number_format($today_sale, 2) ?>
                        </h3>
                    </div>

                    <i class="fas fa-chart-line fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Amount Due -->
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small opacity-75">
                            <i class="fas fa-file-invoice-dollar me-1"></i> Amount Due
                        </div>
                        <h3 class="fw-bold mb-0 mt-2">
                            ₹ <?= number_format($total_due, 2) ?>
                        </h3>
                    </div>

                    <i class="fas fa-clock fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Alerts -->
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 border-start border-danger border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-danger fw-semibold">
                            <i class="fas fa-triangle-exclamation me-1"></i>
                            Stock Alerts
                        </div>

                        <h3 class="fw-bold text-dark mb-0 mt-2">
                            <?= $low_stock_count ?>
                            <small class="fs-6 text-muted fw-normal">Items Low</small>
                        </h3>
                    </div>

                    <i class="fas fa-box-open fa-2x text-danger opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Items -->
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small opacity-75">
                            <i class="fas fa-boxes-stacked me-1"></i>
                            Total Items
                        </div>

                        <h3 class="fw-bold mb-0 mt-2">
                            <?= number_format($total_tiles + $total_marble) ?>
                        </h3>
                    </div>

                    <i class="fas fa-cubes fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- =========================
     MAIN DASHBOARD AREA
========================= -->
<div class="row g-3 mt-1">

    <!-- ==================================
         LEFT / MAIN CONTENT
         MOBILE: AFTER QUICK ACTIONS
         DESKTOP: LEFT SIDE
    =================================== -->
    <div class="col-lg-8 order-2 order-lg-1">

        <!-- Stock Status -->
        <div class="card border-0 shadow-sm border-start border-danger border-4 mb-3">
            <div class="card-body py-3">

                <div class="d-flex flex-column flex-sm-row
                            align-items-sm-center
                            justify-content-between gap-3">

                    <div>
                        <h6 class="text-danger fw-bold mb-1">
                            <i class="fas fa-circle-exclamation me-1"></i>
                            Stock Status
                        </h6>

                        <p class="mb-0 text-muted small">
                            Total
                            <strong><?= $low_stock_count ?></strong>
                            items are running low.
                        </p>
                    </div>

                    <button type="button"
                            class="btn btn-danger btn-sm px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#lowStockModal">

                        <i class="fas fa-list-check me-1"></i>
                        View Low Stock
                    </button>

                </div>

            </div>
        </div>


        <!-- ==================================
             RECENT INVOICES
        =================================== -->
        <div class="card border-0 shadow-sm mb-3">

            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between">

                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-file-invoice me-2 text-primary"></i>
                        Recent Invoices
                    </h5>

                    <span class="badge bg-light text-secondary border">
                        Customer Status
                    </span>

                </div>
            </div>


            <!-- Horizontal scroll only when required -->
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">Invoice No.</th>
                            <th class="text-nowrap">Customer</th>
                            <th>Type</th>
                            <th class="text-nowrap">Total Amount</th>
                            <th class="text-nowrap">Due</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($recent_invoices)): ?>

                            <?php foreach ($recent_invoices as $inv): ?>

                                <tr>

                                    <td class="text-nowrap">
                                        <span class="fw-semibold text-secondary">
                                            #<?= $inv['invoice_no'] ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="fw-bold text-dark">
                                            <?= $inv['cust_name'] ?>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge bg-primary">
                                            <i class="fas fa-circle-check me-1"></i>
                                            Active
                                        </span>
                                    </td>

                                    <td class="text-nowrap">
                                        <span class="fw-semibold">
                                            ₹ <?= number_format($inv['total_amount'], 2) ?>
                                        </span>
                                    </td>

                                    <td class="text-nowrap">

                                        <?php if ($inv['due_amount'] > 0): ?>

                                            <span class="text-danger fw-bold">
                                                ₹ <?= number_format($inv['due_amount'], 2) ?>
                                            </span>

                                        <?php else: ?>

                                            <span class="text-success fw-semibold">
                                                ₹ 0.00
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td class="text-center">

                                        <a href="<?= base_url('ManagementController/print_invoice/'.$inv['id']) ?>"
                                           class="btn btn-sm btn-light border"
                                           title="View Invoice">

                                            <i class="fas fa-eye text-primary"></i>

                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center py-4">

                                    <i class="fas fa-file-circle-xmark
                                              fa-2x text-muted mb-2"></i>

                                    <div class="text-muted">
                                        No recent bills found.
                                    </div>

                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- ==================================
         RIGHT / QUICK ACTIONS
         MOBILE: FIRST
         DESKTOP: RIGHT SIDE
    =================================== -->
    <div class="col-lg-4 order-1 order-lg-2">

        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-bottom py-3">

                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-bolt me-2 text-warning"></i>
                    Quick Actions
                </h5>

            </div>


            <div class="card-body">

               <!-- New Invoice -->
<a href="<?= base_url('billing') ?>"
   class="btn btn-primary w-100 mb-3 py-3 shadow-sm">
    <i class="fas fa-file-circle-plus me-2"></i>
    Generate New Invoice
</a>

<!-- Add Stock -->
<a href="<?= base_url('ProductController/index') ?>"
   class="btn btn-outline-success w-100 mb-3 py-2">
    <i class="fas fa-boxes-stacked me-2"></i>
    Add New Stock
</a>


                <!-- Sales Report -->
                <a href="<?= base_url('ManagementController/reports') ?>"
                   class="btn btn-outline-danger w-100 py-2">

                    <i class="fas fa-chart-column me-2"></i>
                    View Sales Report

                </a>

            </div>

        </div>


        <!-- Welcome Card -->
        <div class="card border-0 shadow-sm mt-3 bg-light">

            <div class="card-body text-center py-3">

                <div class="mb-2">
                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                </div>

                <div class="small text-muted">
                    Welcome back,
                </div>

                <div class="fw-bold text-dark">
                    Afridi Ansari
                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================
     LOW STOCK MODAL
========================= -->
<div class="modal fade"
     id="lowStockModal"
     tabindex="-1"
     aria-labelledby="lowStockModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow">

            <!-- Modal Header -->
            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title" id="lowStockModalLabel">
                    <i class="fas fa-triangle-exclamation me-2"></i>
                    Low Stock Reorder List
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>


            <!-- Modal Body -->
            <div class="modal-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th class="ps-3 text-nowrap">Item Details</th>
                                <th>Category</th>
                                <th class="text-center text-nowrap">
                                    Current Stock
                                </th>
                                <th class="text-center text-nowrap">
                                    Min. Alert
                                </th>
                                <th class="text-center">Status</th>
                            </tr>

                        </thead>


                        <tbody>

                            <?php if (!empty($low_stock_items)): ?>

                                <?php foreach ($low_stock_items as $item): ?>

                                    <tr>

                                        <td class="ps-3">

                                            <div class="fw-bold text-dark">
                                                <?= $item['product_name'] ?>
                                            </div>

                                            <small class="text-muted">
                                                <?= $item['brand'] ?>
                                                |
                                                <?= $item['size'] ?>
                                            </small>

                                        </td>


                                        <td>
                                            <?= $item['category'] ?>
                                        </td>


                                        <td class="text-center">

                                            <span class="badge bg-danger-subtle text-danger">
                                                <?= $item['stock_qty'] ?>
                                            </span>

                                        </td>


                                        <td class="text-center text-muted">
                                            <?= $item['min_stock_alert'] ?>
                                        </td>


                                        <td class="text-center">

                                            <i class="fas fa-arrow-down text-danger"></i>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>

                                    <td colspan="5"
                                        class="text-center py-5 text-success">

                                        <i class="fas fa-circle-check fa-3x mb-3"></i>

                                        <h5 class="mb-1">
                                            Everything is in Stock!
                                        </h5>

                                        <p class="small mb-0 text-muted">
                                            No items need reordering right now.
                                        </p>

                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>


            <!-- Modal Footer -->
            <div class="modal-footer bg-light py-2">

                <button type="button"
                        class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">

                    Close

                </button>


                <a href="<?= base_url('ManagementController/tiles_stock') ?>"
                   class="btn btn-primary btn-sm">

                    <i class="fas fa-boxes-stacked me-1"></i>
                    Manage All Stock

                </a>

            </div>

        </div>

    </div>

</div>


<!-- =========================
     MINIMAL CUSTOM CSS
========================= -->
<style>
    /* Better mobile table experience */
    .table-responsive {
        -webkit-overflow-scrolling: touch;
    }

    /* Keep dashboard cards visually consistent */
    .card {
        border-radius: 10px;
    }

    /* Mobile spacing */
    @media (max-width: 575.98px) {
        .card-body {
            padding: 1rem;
        }

        .table {
            font-size: 13px;
        }

        .table th,
        .table td {
            padding: 10px 8px;
        }
    }
</style>