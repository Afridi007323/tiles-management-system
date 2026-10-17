<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="fas fa-truck-loading me-2"></i>
                New Purchase
            </h3>

            <p class="text-muted mb-0">
                Add products purchased from supplier
            </p>
        </div>

        <a
            href="<?= base_url('PurchaseController/history') ?>"
            class="btn btn-outline-primary"
        >
            <i class="fas fa-history me-1"></i>
            Purchase History
        </a>

    </div>


    <!-- Purchase Form -->
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                <i class="fas fa-file-invoice me-2"></i>
                Purchase Details
            </h5>

        </div>


        <div class="card-body">

            <form
                action="#"
                method="POST"
            >

                <div class="row g-3">


                    <!-- Supplier -->
                    <div class="col-md-4">

                        <label class="form-label">
                            Supplier
                        </label>

                        <select
                            name="supplier_id"
                            class="form-select"
                        >

                            <option value="">
                                Select Supplier
                            </option>

                            <?php if (!empty($suppliers)): ?>

                                <?php foreach ($suppliers as $supplier): ?>

                                    <option value="<?= $supplier['id'] ?>">

                                        <?= html_escape($supplier['name']) ?>

                                    </option>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </select>

                    </div>


                    <!-- Purchase Date -->
                    <div class="col-md-4">

                        <label class="form-label">
                            Purchase Date
                        </label>

                        <input
                            type="date"
                            name="purchase_date"
                            class="form-control"
                            value="<?= date('Y-m-d') ?>"
                        >

                    </div>


                    <!-- Invoice Number -->
                    <div class="col-md-4">

                        <label class="form-label">
                            Supplier Invoice No.
                        </label>

                        <input
                            type="text"
                            name="supplier_invoice_no"
                            class="form-control"
                            placeholder="Enter invoice number"
                        >

                    </div>

                </div>


                <hr class="my-4">


                <!-- Products -->
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h5 class="mb-0">
                        Products
                    </h5>

                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        id="addPurchaseItem"
                    >
                        <i class="fas fa-plus me-1"></i>
                        Add Product
                    </button>

                </div>


                <div
                    id="purchaseItems"
                    class="table-responsive"
                >

                    <table class="table table-bordered align-middle">

                        <thead class="table-light">

                            <tr>

                                <th style="min-width:220px;">
                                    Product
                                </th>

                                <th style="width:130px;">
                                    Quantity
                                </th>

                                <th style="width:150px;">
                                    Purchase Rate
                                </th>

                                <th style="width:160px;">
                                    Total
                                </th>

                                <th style="width:70px;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody id="purchaseItemsBody">

                            <tr class="purchase-row">

                                <td>

                                    <select
                                        name="items[0][product_id]"
                                        class="form-select product-select"
                                    >

                                        <option value="">
                                            Select Product
                                        </option>

                                        <?php if (!empty($products)): ?>

                                            <?php foreach ($products as $product): ?>

                                                <option
                                                    value="<?= $product['id'] ?>"
                                                >
                                                    <?= html_escape($product['product_name']) ?>
                                                </option>

                                            <?php endforeach; ?>

                                        <?php endif; ?>

                                    </select>

                                </td>


                                <td>

                                    <input
                                        type="number"
                                        name="items[0][quantity]"
                                        class="form-control quantity"
                                        min="1"
                                        value="1"
                                    >

                                </td>


                                <td>

                                    <input
                                        type="number"
                                        name="items[0][rate]"
                                        class="form-control rate"
                                        min="0"
                                        step="0.01"
                                        value="0"
                                    >

                                </td>


                                <td>

                                    <input
                                        type="text"
                                        class="form-control item-total"
                                        value="0.00"
                                        readonly
                                    >

                                </td>


                                <td class="text-center">

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger remove-row"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>

                            </tr>

                        </tbody>

                        <tfoot>

                            <tr>

                                <th colspan="3" class="text-end">
                                    Grand Total
                                </th>

                                <th>

                                    <input
                                        type="text"
                                        id="grandTotal"
                                        class="form-control fw-bold"
                                        value="0.00"
                                        readonly
                                    >

                                </th>

                                <th></th>

                            </tr>

                        </tfoot>

                    </table>

                </div>


                <hr class="my-4">


                <!-- Notes -->
                <div class="mb-3">

                    <label class="form-label">
                        Notes
                    </label>

                    <textarea
                        name="note"
                        class="form-control"
                        rows="3"
                        placeholder="Optional notes..."
                    ></textarea>

                </div>


                <!-- Buttons -->
                <div class="text-end">

                    <a
                        href="<?= base_url('dashboard') ?>"
                        class="btn btn-light me-2"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-save me-1"></i>
                        Save Purchase
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    let rowIndex = 1;


    /*
     * Add Product
     */
    document
        .getElementById('addPurchaseItem')
        .addEventListener('click', function () {

            const tbody =
                document.getElementById('purchaseItemsBody');

            const row =
                document.createElement('tr');

            row.className = 'purchase-row';

            row.innerHTML = `
                <td>
                    <select
                        name="items[${rowIndex}][product_id]"
                        class="form-select product-select"
                    >
                        <option value="">
                            Select Product
                        </option>

                        <?php if (!empty($products)): ?>

                            <?php foreach ($products as $product): ?>

                                <option value="<?= $product['id'] ?>">
                                    <?= html_escape($product['product_name']) ?>
                                </option>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </select>
                </td>

                <td>
                    <input
                        type="number"
                        name="items[${rowIndex}][quantity]"
                        class="form-control quantity"
                        min="1"
                        value="1"
                    >
                </td>

                <td>
                    <input
                        type="number"
                        name="items[${rowIndex}][rate]"
                        class="form-control rate"
                        min="0"
                        step="0.01"
                        value="0"
                    >
                </td>

                <td>
                    <input
                        type="text"
                        class="form-control item-total"
                        value="0.00"
                        readonly
                    >
                </td>

                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger remove-row"
                    >
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(row);

            rowIndex++;

        });


    /*
     * Quantity / Rate calculation
     */
    document.addEventListener('input', function (e) {

        if (
            e.target.classList.contains('quantity') ||
            e.target.classList.contains('rate')
        ) {

            const row =
                e.target.closest('.purchase-row');

            calculateRow(row);

        }

    });


    /*
     * Remove row
     */
    document.addEventListener('click', function (e) {

        const button =
            e.target.closest('.remove-row');

        if (!button) {
            return;
        }

        const rows =
            document.querySelectorAll('.purchase-row');

        if (rows.length <= 1) {
            return;
        }

        button
            .closest('.purchase-row')
            .remove();

        calculateGrandTotal();

    });


    /*
     * Calculate row
     */
    function calculateRow(row)
    {
        const quantity =
            parseFloat(
                row.querySelector('.quantity').value
            ) || 0;

        const rate =
            parseFloat(
                row.querySelector('.rate').value
            ) || 0;

        const total =
            quantity * rate;

        row.querySelector('.item-total').value =
            total.toFixed(2);

        calculateGrandTotal();
    }


    /*
     * Grand total
     */
    function calculateGrandTotal()
    {
        let grandTotal = 0;

        document
            .querySelectorAll('.item-total')
            .forEach(function (input) {

                grandTotal +=
                    parseFloat(input.value) || 0;

            });

        document.getElementById('grandTotal').value =
            grandTotal.toFixed(2);
    }

});

</script>