
<style>
   .previous-due-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 16px 18px;
    border: 1px solid #fecaca;
    border-radius: 12px;
    background: #fff1f2;
}

.previous-due-value {
    color: #dc2626;
    font-size: 23px;
    font-weight: 750;
    white-space: nowrap;
}

.previous-due-card.no-due {
    border-color: #bbf7d0;
    background: #f0fdf4;
}

.previous-due-card.no-due .previous-due-value {
    color: #15803d;
}

@media (max-width: 576px) {
    .previous-due-card {
        padding: 13px;
    }

    .previous-due-value {
        font-size: 19px;
    }
} 



.purchase-summary {
    padding: 15px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
}

.summary-item {
    padding: 16px;
    border-radius: 12px;
    border: 1px solid transparent;
    height: 100%;
}

.summary-item small {
    display: block;
    font-size: 14px;
    color: #475569;
    margin-bottom: 8px;
}

.summary-item h4 {
    margin: 0;
    font-weight: 750;
    font-size: 22px;
    overflow-wrap: anywhere;
}

.summary-blue {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
}

.summary-green {
    background: #ecfdf5;
    border-color: #a7f3d0;
    color: #047857;
}

.summary-red {
    background: #fff1f2;
    border-color: #fecdd3;
    color: #e11d48;
}

@media (max-width: 576px) {
    .summary-item h4 {
        font-size: 19px;
    }
}

















.purchase-summary-card {
    height: 100%;
    padding: 20px;
    border: 1px solid transparent;
    border-radius: 16px;
    transition: transform .2s ease, box-shadow .2s ease;
}

.purchase-summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(15, 23, 42, .07);
}

.summary-label {
    color: #64748b;
    font-size: 15px;
    margin-bottom: 12px;
}

.summary-value {
    font-size: 25px;
    font-weight: 750;
    overflow-wrap: anywhere;
}

.bill-card {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
}

.paid-card {
    background: #ecfdf5;
    border-color: #a7f3d0;
    color: #047857;
}

.invoice-due-card {
    background: #f5f3ff;
    border-color: #ddd6fe;
    color: #6d28d9;
}

.remaining-card {
    background: #fff1f2;
    border-color: #fecdd3;
    color: #e11d48;
}

@media (max-width: 576px) {
    .purchase-summary-card {
        padding: 15px;
    }

    .summary-value {
        font-size: 21px;
    }
}


</style>





<form method="post"
      action="<?= site_url('ManagementController/save_purchase') ?>"
      id="purchaseForm">

<div class="row g-3 mb-3">

    <!-- Supplier Dropdown -->
    <div class="col-md-4">
        <label for="supplier_id" class="form-label">Supplier *</label>

        <select name="supplier_id"
                id="supplier_id"
                class="form-select"
                required>

            <option value="">Select Supplier</option>

            <?php foreach (($suppliers ?? []) as $s): ?>
                <?php
                    $supplierId = (int) $s['id'];
                    $totals = $supplier_totals[$supplierId] ?? [];

                    $totalBill = (float) ($totals['total_bill'] ?? 0);
                    $totalPaid = (float) ($totals['total_paid'] ?? 0);
                    $oldDue = (float) ($totals['old_due'] ?? 0);
                ?>

                <option
                    value="<?= $supplierId ?>"
                    data-total-bill="<?= $totalBill ?>"
                    data-total-paid="<?= $totalPaid ?>"
                    data-old-due="<?= $oldDue ?>">
                    <?= html_escape($s['name']) ?>
                </option>

            <?php endforeach; ?>

        </select>
    </div>

    <!-- Purchase Date -->
    <div class="col-md-4">
        <label for="purchase_date" class="form-label">
            Purchase Date *
        </label>

        <input type="date"
               name="purchase_date"
               id="purchase_date"
               class="form-control"
               value="<?= date('Y-m-d') ?>"
               required>
    </div>

    <!-- Payment Mode -->
    <div class="col-md-4">
        <label for="payment_mode" class="form-label">
            Payment Mode
        </label>

        <select name="payment_mode"
                id="payment_mode"
                class="form-select">
            <option value="Cash">Cash</option>
            <option value="UPI">UPI</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Cheque">Cheque</option>
        </select>
    </div>

</div>

<!-- Supplier Due Summary -->
<div id="supplierOldDueSummary"
     class="row g-3 mb-4"
     style="display:none;">

    <div class="col-md-4">
        <div class="p-3 rounded-3 border bg-primary-subtle h-100">
            <div class="text-primary">Previous Total Bills</div>
            <h4 class="fw-bold mb-0" id="oldTotalBill">₹0.00</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="p-3 rounded-3 border bg-success-subtle h-100">
            <div class="text-success">Previously Paid</div>
            <h4 class="fw-bold mb-0" id="oldTotalPaid">₹0.00</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="p-3 rounded-3 border bg-danger-subtle h-100">
            <div class="text-danger">Old Outstanding Due</div>
            <h4 class="fw-bold mb-0" id="oldOutstandingDue">₹0.00</h4>
        </div>
    </div>

</div>

<!-- Current Purchase Calculation -->
<div id="supplierPurchaseSummary"
     class="row g-3 mb-4"
     style="display:none;">

    <div class="col-md-6 col-xl-3">
        <div class="purchase-summary-card bill-card">
            <div class="summary-label">New Purchase Bill</div>
            <div class="summary-value" id="summaryTotalBill">₹0.00</div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="purchase-summary-card paid-card">
            <div class="summary-label">Paid Now</div>
            <div class="summary-value" id="summaryTotalPaid">₹0.00</div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="purchase-summary-card invoice-due-card">
            <div class="summary-label">New Bill Remaining Due</div>
            <div class="summary-value" id="summaryInvoiceDue">₹0.00</div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="purchase-summary-card remaining-card">
            <div class="summary-label">Total Outstanding Due</div>
            <div class="summary-value" id="summaryRemainingDue">₹0.00</div>
        </div>
    </div>

    <div class="col-12">
        <div class="alert alert-light border mb-0"
             id="summaryCalculation">
            Supplier select karne par calculation yahan dikhega.
        </div>
    </div>

</div>


                <div class="table-responsive">
                    <table class="table table-bordered align-middle"
                           id="purchaseItemsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width:220px">Product</th>
                                <th>Unit</th>
                                <th style="min-width:100px">Qty</th>
                                <th style="min-width:130px">Rate (₹)</th>
                                <th>Amount (₹)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="purchaseItems">
                            <tr class="purchase-row">
                                
<td>
    <select name="product_id[]" class="form-select product-select" required>
        <option value="">Select Existing Product</option>
        <?php foreach ($products as $product): ?>
            <option
                value="<?= (int) $product['id'] ?>"
                data-unit="<?= html_escape($product['stock_unit'] ?: 'Piece') ?>"
                data-rate="<?= html_escape($product['unit_price']) ?>">
                <?= html_escape($product['product_name']) ?>
            </option>
        <?php endforeach; ?>
        <option value="0">＋ Add New Product</option>
    </select>

    <div class="new-product-fields border rounded-3 p-2 mt-2" style="display:none;">
        <label class="form-label small">New Product Name *</label>
        <input type="text" name="new_product_name[]" class="form-control new-product-name"
               maxlength="200" placeholder="Type product name">

        <label class="form-label small mt-2">Category *</label>
        <select name="new_product_category[]" class="form-select new-product-category">
            <option value="">Select Category</option>
            <option value="Tiles">Tiles</option>
            <option value="Marble">Marble</option>
            <option value="Hardware">Hardware</option>
            <option value="Stationary">Stationary</option>
        </select>

        <label class="form-label small mt-2">Unit *</label>
        <select name="new_product_unit[]" class="form-select new-product-unit">
            <option value="Piece">Piece</option>
            <option value="Box">Box</option>
            <option value="Sq.Ft">Sq.Ft</option>
            <option value="Sq.Mtr">Sq.Mtr</option>
            <option value="Kg">Kg</option>
            <option value="Bag">Bag</option>
            <option value="Packet">Packet</option>
            <option value="Roll">Roll</option>
            <option value="Length">Length</option>
        </select>

        <label class="form-label small mt-2">Brand (optional)</label>
        <input type="text" name="new_product_brand[]" class="form-control"
               maxlength="100" placeholder="Brand">

        <label class="form-label small mt-2">Size (optional)</label>
        <input type="text" name="new_product_size[]" class="form-control"
               maxlength="100" placeholder="Product size">
    </div>
</td>

                                <td>
                                    <span class="unit-label">—</span>
                                </td>
                                <td>
                                    <input type="number" name="quantity[]"
                                           class="form-control qty-input"
                                           min="1" step="1" value="1" required>
                                </td>
                                <td>
                                    <input type="number" name="unit_price[]"
                                           class="form-control rate-input"
                                           min="0" step="0.01" value="0" required>
                                </td>
                                <td class="line-total">₹0.00</td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger remove-row">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" id="addPurchaseRow"
                        class="btn btn-outline-primary mb-3">
                    + Add Product
                </button>

                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <label class="form-label">Total Amount (₹)</label>
                        <input type="number" id="totalAmount"
                               class="form-control" value="0.00" readonly>

                        <label class="form-label mt-2">Paid Amount (₹)</label>
                        <input type="number" name="paid_amount" id="paidAmount"
                               class="form-control" min="0" step="0.01"
                               value="0" required>


                               
<label class="form-label mt-2">Old Due (₹)</label>
<input type="text" id="oldDueAmount"
       class="form-control" value="0.00" readonly>


                        
<label class="form-label mt-2">New Bill Remaining Due (₹)</label>
<input type="number" id="dueAmount"
       class="form-control" value="0.00" readonly>

<label class="form-label mt-2">Total Outstanding Due (₹)</label>
<input type="text" id="totalOutstandingDue"
       class="form-control fw-bold text-danger"
       value="0.00" readonly>


                        <label class="form-label mt-2">Note</label>
                        <textarea name="note" class="form-control"
                                  rows="2"></textarea>

                       


<button type="submit" id="savePurchaseBtn"
        class="btn btn-success w-100 mt-3">
    Save Purchase
</button>


                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header"><strong>Purchase History</strong></div>
        <div class="card-body table-responsive">
            
<table class="table table-striped table-hover align-middle">
    <thead>
        <tr>
            <th>Purchase No.</th>
            <th>Date</th>
            <th>Supplier</th>
            <th>Total Bill</th>
            <th>Paid for Bill</th>
            <th>Bill Due</th>
            <th>Receipt</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($purchases)): ?>
            <?php foreach ($purchases as $purchase): ?>
                <?php
                    $billTotal = (float) $purchase['total_amount'];
                    $billPaid  = (float) $purchase['paid_amount'];
                    $billDue   = max(0, round($billTotal - $billPaid, 2));
                ?>

                <tr>
                    <td>
                        <?= html_escape($purchase['purchase_no']) ?>
                    </td>

                    <td>
                        <?= html_escape($purchase['purchase_date']) ?>
                    </td>

                    <td>
                        <?= html_escape($purchase['supplier_name']) ?>
                    </td>

                    <td>
                        ₹<?= number_format($billTotal, 2) ?>
                    </td>

                    <td>
                        ₹<?= number_format($billPaid, 2) ?>
                    </td>

                    <td>
                        <span class="badge <?= $billDue > 0 ? 'bg-danger' : 'bg-success' ?>">
                            ₹<?= number_format($billDue, 2) ?>
                        </span>
                    </td>

                    <td>
                        <a
                            href="<?= site_url('ManagementController/print_purchase_receipt/' . (int) $purchase['id']) ?>"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-receipt me-1"></i>
                            Receipt
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center text-muted">
                    No purchases found.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

        </div>
    </div>
</div>


<script>
(function () {
    'use strict';

    // Main elements
    const tbody = document.getElementById('purchaseItems');
    const totalInput = document.getElementById('totalAmount');
    const paidInput = document.getElementById('paidAmount');
    const dueInput = document.getElementById('dueAmount');
    const addButton = document.getElementById('addPurchaseRow');
    const oldDueInput = document.getElementById('oldDueAmount');
const totalOutstandingInput = document.getElementById('totalOutstandingDue');

    const supplierSelect = document.getElementById('supplier_id');
    const oldDueSummary = document.getElementById('supplierOldDueSummary');
    const purchaseSummary = document.getElementById('supplierPurchaseSummary');

    // Controller se supplier-wise totals
    const supplierTotals = <?= json_encode(
        $supplier_totals ?? array(),
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    ) ?>;

    function money(value) {
        return '₹' + Number(value || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function getSupplierTotals() {
        if (!supplierSelect || !supplierSelect.value) {
            return {
                total_bill: 0,
                total_paid: 0,
                old_due: 0
            };
        }

        const option =
            supplierSelect.options[supplierSelect.selectedIndex];

        // Prefer data attributes if present; otherwise use controller data.
        const id = supplierSelect.value;
        const totals = supplierTotals[id] || {};

        return {
            total_bill: option.dataset.totalBill !== undefined
                ? Number(option.dataset.totalBill)
                : Number(totals.total_bill || 0),

            total_paid: option.dataset.totalPaid !== undefined
                ? Number(option.dataset.totalPaid)
                : Number(totals.total_paid || 0),

            old_due: option.dataset.oldDue !== undefined
                ? Number(option.dataset.oldDue)
                : Number(totals.old_due || 0)
        };
    }

    // Calculate current purchase from product rows.
    function updateTotals() {
        if (!tbody || !totalInput || !paidInput || !dueInput) {
            return;
        }

        let total = 0;

        tbody.querySelectorAll('.purchase-row').forEach(function (row) {
            const qtyInput = row.querySelector('.qty-input');
            const rateInput = row.querySelector('.rate-input');
            const lineTotal = row.querySelector('.line-total');

            if (!qtyInput || !rateInput || !lineTotal) return;

            const qty = Math.max(0, parseFloat(qtyInput.value) || 0);
            const rate = Math.max(0, parseFloat(rateInput.value) || 0);
            const amount = qty * rate;

            lineTotal.textContent = money(amount);
            total += amount;
        });

        total = Math.round((total + Number.EPSILON) * 100) / 100;

        const paid = Math.max(0, parseFloat(paidInput.value) || 0);

        
totalInput.value = total.toFixed(2);

const newBillDue = Math.max(0, total - paid);
dueInput.value = newBillDue.toFixed(2);

// Selected supplier ka purana due
const supplierData = getSupplierTotals();
const oldDue = supplierSelect && supplierSelect.value
    ? Number(supplierData.old_due || 0)
    : 0;

// Old Due + New Bill Remaining Due
const totalOutstanding = oldDue + newBillDue;

if (oldDueInput) {
    oldDueInput.value = oldDue.toFixed(2);
}

if (totalOutstandingInput) {
    totalOutstandingInput.value = totalOutstanding.toFixed(2);
}

updateSupplierSummary();

    }

    // Update the product row when existing/new product is selected.
    function bindRow(row) {
        const productSelect = row.querySelector('.product-select');
        const fields = row.querySelector('.new-product-fields');
        const newName = row.querySelector('.new-product-name');
        const newCategory = row.querySelector('.new-product-category');
        const newUnit = row.querySelector('.new-product-unit');
        const qtyInput = row.querySelector('.qty-input');
        const rateInput = row.querySelector('.rate-input');
        const unitLabel = row.querySelector('.unit-label');
        const removeButton = row.querySelector('.remove-row');

        if (!productSelect || !qtyInput || !rateInput || !unitLabel) {
            return;
        }

        function updateProductMode() {
            const isNew = productSelect.value === '0';

            if (fields) {
                fields.style.display = isNew ? 'block' : 'none';
            }

            if (newName) newName.required = isNew;
            if (newCategory) newCategory.required = isNew;
            if (newUnit) newUnit.required = isNew;

            if (isNew) {
                unitLabel.textContent = newUnit ? newUnit.value : 'Piece';
            } else {
                const option =
                    productSelect.options[productSelect.selectedIndex];

                unitLabel.textContent = option && option.value
                    ? (option.dataset.unit || 'Piece')
                    : '—';

                if (option && option.value) {
                    rateInput.value = option.dataset.rate || '0';
                } else {
                    rateInput.value = '0';
                }
            }

            updateTotals();
        }

        productSelect.addEventListener('change', updateProductMode);

        if (newUnit) {
            newUnit.addEventListener('change', function () {
                if (productSelect.value === '0') {
                    unitLabel.textContent = newUnit.value;
                }
            });
        }

        qtyInput.addEventListener('input', updateTotals);
        rateInput.addEventListener('input', updateTotals);

        if (removeButton) {
            removeButton.addEventListener('click', function () {
                const rows = tbody.querySelectorAll('.purchase-row');

                if (rows.length > 1) {
                    row.remove();
                } else {
                    productSelect.value = '';

                    if (newName) newName.value = '';
                    if (newCategory) newCategory.value = '';
                    if (newUnit) newUnit.value = 'Piece';

                    if (fields) fields.style.display = 'none';
                    if (newName) newName.required = false;
                    if (newCategory) newCategory.required = false;
                    if (newUnit) newUnit.required = false;

                    unitLabel.textContent = '—';
                    qtyInput.value = '1';
                    rateInput.value = '0';
                }

                updateTotals();
            });
        }

        updateProductMode();
    }

    // Initialize first product row.
    if (tbody) {
        tbody.querySelectorAll('.purchase-row').forEach(bindRow);
    }

    // Add product row.
    if (addButton && tbody) {
        addButton.addEventListener('click', function () {
            const firstRow = tbody.querySelector('.purchase-row');
            if (!firstRow) return;

            const newRow = firstRow.cloneNode(true);

            newRow.querySelector('.product-select').value = '';
            newRow.querySelector('.qty-input').value = '1';
            newRow.querySelector('.rate-input').value = '0';
            newRow.querySelector('.unit-label').textContent = '—';
            newRow.querySelector('.line-total').textContent = '₹0.00';

            const fields = newRow.querySelector('.new-product-fields');
            if (fields) fields.style.display = 'none';

            newRow.querySelectorAll(
                '.new-product-name, .new-product-brand, ' +
                '.new-product-size, .new-product-category'
            ).forEach(function (input) {
                input.value = '';
                input.required = false;
            });

            const unit = newRow.querySelector('.new-product-unit');

            if (unit) {
                unit.value = 'Piece';
                unit.required = false;
            }

            tbody.appendChild(newRow);
            bindRow(newRow);
            updateTotals();
        });
    }

    // Update old due + current purchase summary.
    function updateSupplierSummary() {
        if (!supplierSelect) return;

        const selected = Boolean(supplierSelect.value);

        if (!selected) {
            if (oldDueSummary) oldDueSummary.style.display = 'none';
            if (purchaseSummary) purchaseSummary.style.display = 'none';
            return;
        }

        const totals = getSupplierTotals();

        const oldBill = totals.total_bill;
        const oldPaid = totals.total_paid;
        const oldDue = totals.old_due;

        const oldBillElement = document.getElementById('oldTotalBill');
        const oldPaidElement = document.getElementById('oldTotalPaid');
        const oldDueElement = document.getElementById('oldOutstandingDue');

        if (oldBillElement) oldBillElement.textContent = money(oldBill);
        if (oldPaidElement) oldPaidElement.textContent = money(oldPaid);
        if (oldDueElement) oldDueElement.textContent = money(oldDue);

        if (oldDueSummary) oldDueSummary.style.display = 'flex';

        const currentBill = Number(totalInput ? totalInput.value : 0);
        const paidNow = Number(paidInput ? paidInput.value : 0);

        const newBillDue = Math.max(0, currentBill - paidNow);
        const totalOutstanding = oldDue + newBillDue;

        const summaryTotalBill =
            document.getElementById('summaryTotalBill');
        const summaryTotalPaid =
            document.getElementById('summaryTotalPaid');
        const summaryInvoiceDue =
            document.getElementById('summaryInvoiceDue');
        const summaryRemainingDue =
            document.getElementById('summaryRemainingDue');
        const calculation =
            document.getElementById('summaryCalculation');

        if (summaryTotalBill) {
            summaryTotalBill.textContent = money(currentBill);
        }

        if (summaryTotalPaid) {
            summaryTotalPaid.textContent = money(paidNow);
        }

        if (summaryInvoiceDue) {
            summaryInvoiceDue.textContent = money(newBillDue);
        }

        if (summaryRemainingDue) {
            summaryRemainingDue.textContent = money(totalOutstanding);
        }

        if (calculation) {
            calculation.textContent =
                'Calculation: ' + money(oldDue) +
                ' old due + ' + money(currentBill) +
                ' new bill − ' + money(paidNow) +
                ' paid = ' + money(totalOutstanding) +
                ' total outstanding due.';
        }

        if (purchaseSummary) {
            purchaseSummary.style.display = 'flex';
        }
    }

    // Supplier selection and payment changes.
    
if (supplierSelect) {
    supplierSelect.addEventListener('change', function () {
        updateTotals();
        updateSupplierSummary();
    });
}


    if (paidInput) {
        paidInput.addEventListener('input', updateTotals);
    }

    // Initial calculations.
    updateTotals();
    updateSupplierSummary();

})();







document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('purchaseForm');
    const button = document.getElementById('savePurchaseBtn');

    if (!form || !button) {
        console.error('Purchase form or Save button not found.');
        return;
    }

    form.addEventListener('submit', function (event) {
        console.log('Save Purchase form submitted.');

        if (!form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
            return;
        }

        button.disabled = true;
        button.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    });
});

</script>
