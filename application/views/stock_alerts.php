
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-bell text-warning me-2"></i>Stock Alerts
            </h2>
            <p class="text-secondary mb-0">
                Products jinka stock minimum limit par ya usse neeche hai.
            </p>
        </div>

        <span class="badge bg-danger fs-6">
            <?= count($products ?? array()) ?> Alerts
        </span>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">

            <?php if (empty($products)): ?>

                <div class="text-center py-5">
                    <i class="fas fa-circle-check text-success fa-3x mb-3"></i>
                    <h5 class="fw-bold">All Stock Levels Are Good!</h5>
                    <p class="text-secondary mb-0">
                        Abhi kisi product ka stock minimum limit se neeche nahi hai.
                    </p>
                </div>

            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Product Name</th>
                                <th>Current Stock</th>
                                <th>Minimum Stock</th>
                                <th>Alert Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $index => $product): ?>
                                <?php
                                    $stock = (float) ($product['stock_qty'] ?? 0);
                                    $minimum = (float) ($product['min_stock_alert'] ?? 0);
                                    $outOfStock = $stock <= 0;
                                ?>
                                <tr>
                                    <td class="ps-4"><?= $index + 1 ?></td>

                                    <td class="fw-semibold">
                                        <?= html_escape($product['name'] ?? $product['product_name'] ?? 'Unnamed Product') ?>
                                    </td>

                                    <td>
                                        <strong class="<?= $outOfStock ? 'text-danger' : 'text-warning' ?>">
                                            <?= number_format($stock, 0) ?>
                                            <?= html_escape($product['stock_unit'] ?? 'Units') ?>
                                        </strong>
                                    </td>

                                    <td><?= number_format($minimum, 0) ?></td>

                                    <td>
                                        <?php if ($outOfStock): ?>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Low Stock</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>
