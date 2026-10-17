
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Vendor Management</h2>
            <p class="text-muted mb-0">Manage your suppliers and opening balances.</p>
        </div>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= html_escape($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?= html_escape($error) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Add New Supplier</h5>
        </div>

        <div class="card-body">
            <form method="post"
                  action="<?= site_url('ManagementController/save_supplier') ?>">

                <input type="hidden"
                       name="<?= $this->security->get_csrf_token_name() ?>"
                       value="<?= $this->security->get_csrf_hash() ?>">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Supplier Name *</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               maxlength="150"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Mobile Number *</label>
                        <input type="text"
                               name="mobile"
                               class="form-control"
                               maxlength="20"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Opening Balance (₹)</label>
                        <input type="number"
                               name="opening_balance"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="0"
                               required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Address</label>
                        <textarea name="address"
                                  class="form-control"
                                  rows="2"></textarea>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            Save Supplier
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Supplier List</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Supplier Name</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th class="text-end">Opening Balance</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($suppliers)): ?>
                        <?php foreach ($suppliers as $index => $supplier): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= html_escape($supplier['name']) ?></td>
                                <td><?= html_escape($supplier['mobile']) ?></td>
                                <td><?= html_escape($supplier['address'] ?? '') ?></td>
                                <td class="text-end">
                                    ₹<?= number_format(
                                        (float) $supplier['opening_balance'],
                                        2
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No suppliers added yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
