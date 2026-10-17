<div class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="<?= base_url('ManagementController/reports') ?>" class="row align-items-end">
               
           <div class="col-md-3">
    <label class="form-label fw-bold">From Date</label>
    <input type="date" name="from_date" class="form-control" value="<?= $current_from ?>">
</div>
<div class="col-md-3">
    <label class="form-label fw-bold">To Date</label>
    <input type="date" name="to_date" class="form-control" value="<?= $current_to ?>">
</div>


                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4 text-center">
        <div class="col-md-4">
            <div class="p-3 bg-white border-start border-primary border-4 shadow-sm rounded">
                <small class="text-muted text-uppercase fw-bold">Total Sales</small>
                <h3 class="text-primary mb-0">₹ <?= number_format($total_sales_amount, 2) ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-white border-start border-success border-4 shadow-sm rounded">
                <small class="text-muted text-uppercase fw-bold">Total Collected</small>
                <h3 class="text-success mb-0">₹ <?= number_format($total_received, 2) ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-white border-start border-danger border-4 shadow-sm rounded">
                <small class="text-muted text-uppercase fw-bold">Total Outstanding</small>
                <h3 class="text-danger mb-0">₹ <?= number_format($total_pending, 2) ?></h3>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Sales Record</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($sales)): foreach($sales as $s): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($s['billing_date'])) ?></td>
                        <td><strong><?= $s['invoice_no'] ?></strong></td>
                        <td><?= $s['cust_name'] ?></td>
                        <td class="fw-bold">₹ <?= number_format($s['total_amount'], 2) ?></td>
                        <td class="text-success">₹ <?= number_format($s['paid_amount'], 2) ?></td>
                        <td class="text-danger">₹ <?= number_format($s['due_amount'], 2) ?></td>
                        <td>
                            <a href="<?= base_url('ManagementController/print_invoice/'.$s['id']) ?>" target="_blank" class="btn btn-sm btn-light border">
                                <i class="fas fa-print"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center py-4">No records found for the selected period.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>