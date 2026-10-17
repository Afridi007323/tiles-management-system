<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$items = isset($items) && is_array($items) ? $items : [];

$suppliers = [];
$totalQuantity = 0;
$totalAmount = 0;

foreach ($items as $item) {
    $supplierName = trim((string)($item['supplier_name'] ?? ''));

    if ($supplierName !== '') {
        $suppliers[$supplierName] = $supplierName;
    }

    $totalQuantity += (float)($item['quantity'] ?? 0);
    $totalAmount += (float)($item['total_price'] ?? 0);
}

ksort($suppliers);
?>

<style>
.purchase-items-page {
    color: #1e293b;
}

.purchase-items-page .page-heading {
    font-weight: 700;
    color: #0f172a;
}

.purchase-items-page .summary-card {
    background: #fff;
    border: 1px solid #e9edf3;
    border-radius: 14px;
    padding: 20px;
    height: 100%;
    box-shadow: 0 4px 15px rgba(15, 23, 42, .04);
}

.purchase-items-page .summary-label {
    color: #64748b;
    font-size: 13px;
    margin-bottom: 8px;
}

.purchase-items-page .summary-value {
    color: #0f172a;
    font-size: 24px;
    font-weight: 750;
    overflow-wrap: anywhere;
}

.purchase-items-page .filter-card,
.purchase-items-page .table-card {
    background: #fff;
    border: 1px solid #e9edf3;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(15, 23, 42, .04);
}

.purchase-items-page .form-label {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
}

.purchase-items-page .form-control,
.purchase-items-page .form-select {
    min-height: 42px;
    border-color: #dbe2ea;
    border-radius: 9px;
}

.purchase-items-page .form-control:focus,
.purchase-items-page .form-select:focus {
    border-color: #818cf8;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
}

.purchase-items-page .table {
    vertical-align: middle;
    white-space: nowrap;
}

.purchase-items-page .table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .3px;
    padding: 14px 12px;
    border-bottom: 1px solid #e2e8f0;
}

.purchase-items-page .table tbody td {
    padding: 14px 12px;
    font-size: 13px;
}

.purchase-items-page .product-name {
    font-weight: 650;
    color: #0f172a;
}

.purchase-items-page .purchase-badge {
    display: inline-block;
    background: #eef2ff;
    color: #4338ca;
    padding: 5px 9px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 650;
}

.purchase-items-page .empty-state {
    text-align: center;
    padding: 45px 15px;
    color: #64748b;
}

.purchase-items-page .empty-state i {
    font-size: 34px;
    margin-bottom: 12px;
    color: #94a3b8;
}

@media (max-width: 767px) {
    .purchase-items-page .filter-card,
    .purchase-items-page .table-card {
        padding: 14px;
    }

    .purchase-items-page .summary-value {
        font-size: 21px;
    }
}
</style>

<div class="purchase-items-page">

    <!-- PAGE HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="page-heading mb-1">
                <i class="fas fa-boxes text-primary me-2"></i>
                All Products Purchase
            </h3>

            <p class="text-secondary mb-0 small">
                Purchased products, supplier details, quantities and rates.
            </p>
        </div>

        <a
            href="<?= base_url('ManagementController/purchases') ?>"
            class="btn btn-primary rounded-3 px-3 py-2">

            <i class="fas fa-plus me-1"></i>
            New Purchase
        </a>

    </div>

    <!-- SUMMARY CARDS -->
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <div>
                        <div class="summary-label">Purchase Item Entries</div>
                        <div class="summary-value">
                            <?= number_format(count($items)) ?>
                        </div>
                    </div>

                    <i class="fas fa-box-open fa-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <div>
                        <div class="summary-label">Total Quantity</div>
                        <div class="summary-value">
                            <?= number_format($totalQuantity, 2) ?>
                        </div>
                    </div>

                    <i class="fas fa-cubes fa-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <div>
                        <div class="summary-label">Total Purchase Amount</div>
                        <div class="summary-value">
                            ₹<?= number_format($totalAmount, 2) ?>
                        </div>
                    </div>

                    <i class="fas fa-indian-rupee-sign fa-2x text-warning opacity-75"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- FILTERS -->
    <div class="filter-card mb-4">

        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-filter text-primary"></i>
            <h6 class="mb-0 fw-bold">Search &amp; Filters</h6>
        </div>

        <div class="row g-3">

            <div class="col-12 col-md-6 col-xl-4">
                <label for="purchaseSearch" class="form-label">
                    Search Product / Supplier / Purchase No.
                </label>

                <input
                    type="search"
                    id="purchaseSearch"
                    class="form-control"
                    placeholder="Type to search...">
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <label for="supplierFilter" class="form-label">
                    Supplier
                </label>

                <select id="supplierFilter" class="form-select">
                    <option value="">All Suppliers</option>

                    <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= html_escape($supplier) ?>">
                            <?= html_escape($supplier) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-xl-2">
                <label for="dateFrom" class="form-label">
                    From Date
                </label>

                <input
                    type="date"
                    id="dateFrom"
                    class="form-control">
            </div>

            <div class="col-6 col-xl-2">
                <label for="dateTo" class="form-label">
                    To Date
                </label>

                <input
                    type="date"
                    id="dateTo"
                    class="form-control">
            </div>

            <div class="col-12 col-xl-1 d-flex align-items-end">
                <button
                    type="button"
                    id="resetFilters"
                    class="btn btn-outline-secondary w-100"
                    title="Reset filters">

                    <i class="fas fa-undo"></i>
                </button>
            </div>

        </div>

        <div class="small text-secondary mt-3">
            Showing <strong id="visibleCount"><?= count($items) ?></strong>
            of <?= count($items) ?> purchase item entries.
        </div>

    </div>

    <!-- PURCHASE ITEMS TABLE -->
    <div class="table-card">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">

            <h6 class="fw-bold mb-0">
                <i class="fas fa-list text-primary me-2"></i>
                Purchased Products
            </h6>

            <button
                type="button"
                onclick="window.print()"
                class="btn btn-outline-primary btn-sm rounded-3">

                <i class="fas fa-print me-1"></i>
                Print List
            </button>

        </div>

        <div class="table-responsive">

            <table class="table table-hover mb-0" id="purchaseItemsTable">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Purchase No.</th>
                        <th>Date</th>
                        <th>Product Name</th>
                        <th>Supplier</th>
                        <th class="text-end">Quantity</th>
                        <th class="text-end">Purchase Rate</th>
                        <th class="text-end">Total Amount</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $index => $item): ?>

                            <?php
                            $purchaseNo = $item['purchase_no'] ?? '';
                            $purchaseDate = $item['purchase_date'] ?? '';
                            $productName = $item['product_name'] ?? '';
                            $supplierName = $item['supplier_name'] ?? '—';
                            $quantity = (float)($item['quantity'] ?? 0);
                            $rate = (float)($item['unit_price'] ?? 0);
                            $lineTotal = (float)($item['total_price'] ?? 0);

                            $dateOnly = $purchaseDate
                                ? substr($purchaseDate, 0, 10)
                                : '';
                            ?>

                            <tr
                                class="purchase-item-row"
                                data-search="<?= html_escape(strtolower(
                                    $purchaseNo . ' ' .
                                    $productName . ' ' .
                                    $supplierName
                                )) ?>"
                                data-supplier="<?= html_escape($supplierName) ?>"
                                data-date="<?= html_escape($dateOnly) ?>">

                                <td><?= $index + 1 ?></td>

                                <td>
                                    <span class="purchase-badge">
                                        <?= html_escape($purchaseNo !== '' ? $purchaseNo : ('Purchase #' . ($item['purchase_id'] ?? ''))) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= $dateOnly !== ''
                                        ? html_escape(date('d M Y', strtotime($dateOnly)))
                                        : '—' ?>
                                </td>

                                <td>
                                    <span class="product-name">
                                        <?= html_escape($productName) ?>
                                    </span>
                                </td>

                                <td><?= html_escape($supplierName) ?></td>

                                <td class="text-end">
                                    <?= number_format($quantity, 2) ?>
                                </td>

                                <td class="text-end">
                                    ₹<?= number_format($rate, 2) ?>
                                </td>

                                <td class="text-end fw-bold">
                                    ₹<?= number_format($lineTotal, 2) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-box-open d-block"></i>
                                    <h6 class="fw-bold text-dark">No purchase items found</h6>
                                    <p class="mb-3">
                                        Purchase records will appear here after you save a purchase.
                                    </p>

                                    <a
                                        href="<?= base_url('ManagementController/purchases') ?>"
                                        class="btn btn-primary btn-sm rounded-3">

                                        <i class="fas fa-plus me-1"></i>
                                        Create New Purchase
                                    </a>
                                </div>
                            </td>
                        </tr>

                    <?php endif; ?>

                    <tr id="noFilterResults" style="display:none;">
                        <td colspan="8" class="text-center text-secondary py-4">
                            No products match your search or filters.
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
(function () {
    'use strict';

    const searchInput = document.getElementById('purchaseSearch');
    const supplierFilter = document.getElementById('supplierFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const resetButton = document.getElementById('resetFilters');
    const visibleCount = document.getElementById('visibleCount');
    const noResults = document.getElementById('noFilterResults');

    const rows = Array.from(
        document.querySelectorAll('.purchase-item-row')
    );

    function applyFilters() {
        const query = searchInput.value.trim().toLowerCase();
        const selectedSupplier = supplierFilter.value;
        const from = dateFrom.value;
        const to = dateTo.value;

        let visible = 0;

        rows.forEach(function (row) {
            const searchText = row.dataset.search || '';
            const supplier = row.dataset.supplier || '';
            const date = row.dataset.date || '';

            const matchesSearch =
                query === '' || searchText.includes(query);

            const matchesSupplier =
                selectedSupplier === '' ||
                supplier === selectedSupplier;

            const matchesFrom =
                from === '' || (date !== '' && date >= from);

            const matchesTo =
                to === '' || (date !== '' && date <= to);

            const show =
                matchesSearch &&
                matchesSupplier &&
                matchesFrom &&
                matchesTo;

            row.style.display = show ? '' : 'none';

            if (show) {
                visible++;
            }
        });

        visibleCount.textContent = visible;

        if (noResults) {
            noResults.style.display =
                rows.length > 0 && visible === 0 ? '' : 'none';
        }
    }

    searchInput.addEventListener('input', applyFilters);
    supplierFilter.addEventListener('change', applyFilters);
    dateFrom.addEventListener('change', applyFilters);
    dateTo.addEventListener('change', applyFilters);

    resetButton.addEventListener('click', function () {
        searchInput.value = '';
        supplierFilter.value = '';
        dateFrom.value = '';
        dateTo.value = '';

        applyFilters();
    });
})();
</script>

<style>
@media print {
    .sidebar,
    .sidebar-overlay,
    .navbar-top,
    .filter-card,
    .purchase-items-page > .d-flex:first-child a,
    .table-card button {
        display: none !important;
    }

    .main-content {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .purchase-items-page .summary-card,
    .purchase-items-page .table-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    body {
        overflow: visible !important;
    }
}
</style>