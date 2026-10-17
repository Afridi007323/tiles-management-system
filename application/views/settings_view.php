<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-cog me-2"></i> Shop Settings</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('ManagementController/settings') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label small">Shop Name</label>
                            <input type="text" name="shop_name" class="form-control" value="<?= $shop->shop_name ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Contact Number</label>
                            <input type="text" name="mobile" class="form-control" value="<?= $shop->mobile ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Address</label>
                            <textarea name="address" class="form-control" rows="3"><?= $shop->address ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">GST Number (Optional)</label>
                                <input type="text" name="gst_no" class="form-control" value="<?= $shop->gst_no ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Invoice Prefix</label>
                                <input type="text" name="invoice_prefix" class="form-control" value="<?= $shop->invoice_prefix ?>">
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <h6><i class="fas fa-lightbulb text-warning"></i> Tip:</h6>
                    <p class="small text-muted">Yahan jo bhi shop name aap save karenge, wo automatic aapke Bills aur Dashboard ke title par dikhne lagega.</p>
                </div>
            </div>
        </div>
    </div>
</div>