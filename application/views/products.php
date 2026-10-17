<?php
$selectedCategory = $category ?? 'All';
?>

<div class="container-fluid mt-4">

    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-boxes text-primary me-2"></i>

                <?= html_escape(
                    $selectedCategory === 'All'
                        ? 'All Products'
                        : $selectedCategory . ' Stock'
                ) ?>
            </h4>

            <small class="text-muted">
                Manage your inventory products
            </small>
        </div>


        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#productModal"
            onclick="openProductModal()"
        >

            <i class="fas fa-plus me-1"></i>

            Add New Product

        </button>

    </div>


    <!-- CATEGORY FILTER -->

    <div class="card shadow-sm border-0 mb-3">

        <div class="card-body">

            <div class="row g-2">

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Category
                    </label>

                    <select
                        class="form-select"
                        onchange="changeCategory(this.value)"
                    >

                        <option value="All"
                            <?= $selectedCategory === 'All'
                                ? 'selected'
                                : '' ?>>
                            All Products
                        </option>

                        <option value="Tiles"
                            <?= $selectedCategory === 'Tiles'
                                ? 'selected'
                                : '' ?>>
                            Tiles
                        </option>

                        <option value="Marble"
                            <?= $selectedCategory === 'Marble'
                                ? 'selected'
                                : '' ?>>
                            Marble
                        </option>

                        <option value="Hardware"
                            <?= $selectedCategory === 'Hardware'
                                ? 'selected'
                                : '' ?>>
                            Hardware
                        </option>

                        <option value="Stationary"
                            <?= $selectedCategory === 'Stationary'
                                ? 'selected'
                                : '' ?>>
                            Stationary
                        </option>

                    </select>

                </div>

            </div>

        </div>

    </div>


    <!-- PRODUCTS -->

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between">

                <h6 class="fw-bold mb-0">

                    <i class="fas fa-box text-primary me-2"></i>

                    Product Inventory

                </h6>

                <span class="badge bg-light text-dark">

                    <?= count($products ?? []) ?>
                    Products

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Product</th>

                            <th>Category</th>

                            <th>Size</th>

                            <th>Stock</th>

                            <th>Unit</th>

                            <th>Unit Price</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (!empty($products)): ?>

                        <?php foreach ($products as $product): ?>

                            <tr>

                                <td>

                                    <strong>
                                        <?= html_escape(
                                            $product['product_name']
                                        ) ?>
                                    </strong>

                                </td>


                                <td>

                                    <span class="badge bg-primary-subtle text-primary">

                                        <?= html_escape(
                                            $product['category']
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <?= !empty($product['size'])
                                        ? html_escape($product['size'])
                                        : '-' ?>

                                </td>


                                <td>

                                    <strong>

                                        <?= number_format(
                                            (float)$product['stock_qty'],
                                            2
                                        ) ?>

                                    </strong>

                                </td>


                                <td>

                                    <span class="badge bg-success">

                                       <?= html_escape($product['stock_unit'] ?? (
    ($product['category'] ?? '') === 'Tiles' ? 'Box' :
    (($product['category'] ?? '') === 'Marble' ? 'Sq.Ft' : 'Piece')
)) ?>

                                    </span>

                                </td>


                                <td>

                                    ₹<?= number_format(
                                        (float)$product['unit_price'],
                                        2
                                    ) ?>

                                    / <?= html_escape(
                                        $product['stock_unit']
                                    ) ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5"
                            >

                                <i class="fas fa-box-open fa-2x text-muted mb-3"></i>

                                <div class="fw-bold">
                                    No products found
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


<!-- =========================================================
     ADD PRODUCT MODAL
========================================================= -->

<div
    class="modal fade"
    id="productModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form
                method="POST"
                action="<?= base_url('ProductController/save') ?>"
            >

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        <i class="fas fa-plus-circle me-2"></i>

                        Add New Product

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">


                        <!-- PRODUCT NAME -->

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Product Name
                            </label>

                            <input
                                type="text"
                                name="product_name"
                                class="form-control"
                                placeholder="Enter product name"
                                required
                            >

                        </div>


                        <!-- CATEGORY -->

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Category
                            </label>

                            <select
                                name="category"
                                id="productCategory"
                                class="form-select"
                                required
                                onchange="updateProductUnit()"
                            >

                                <option value="">
                                    Select Category
                                </option>

                                <option value="Tiles">
                                    Tiles
                                </option>

                                <option value="Marble">
                                    Marble
                                </option>

                                <option value="Hardware">
                                    Hardware
                                </option>

                                <option value="Stationary">
                                    Stationary
                                </option>

                            </select>

                        </div>


                        <!-- SIZE -->

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Size
                            </label>

                            <input
                                type="text"
                                name="size"
                                id="productSize"
                                class="form-control"
                                placeholder="Example: 12x18"
                            >

                            <small
                                class="text-muted"
                                id="sizeHelp"
                            >
                                Product size
                            </small>

                        </div>


                        <!-- STOCK -->

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Opening Stock
                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    name="stock_qty"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    placeholder="Enter stock"
                                    required
                                >

                                <span
                                    class="input-group-text"
                                    id="stockUnit"
                                >
                                    Piece
                                </span>

                            </div>

                        </div>


                        <!-- PRICE -->

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Selling Price
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="unit_price"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    placeholder="Enter price"
                                    required
                                >

                                <span
                                    class="input-group-text"
                                    id="priceUnit"
                                >
                                    / Piece
                                </span>

                            </div>

                        </div>


                        <!-- CATEGORY INFO -->

                        <div class="col-12">

                            <div
                                id="categoryInfo"
                                class="alert alert-light border mb-0"
                            >

                                Select a category to see stock unit.

                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-save me-1"></i>

                        Save Product

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

function changeCategory(category)
{
    if (category === 'All') {

        window.location.href =
            "<?= base_url('ProductController/index') ?>";

        return;
    }


    window.location.href =
        "<?= base_url('ProductController/category/') ?>" +
        encodeURIComponent(category);
}


function updateProductUnit()
{
    const category =
        document.getElementById('productCategory').value;

    const unitElement =
        document.getElementById('stockUnit');

    const priceUnitElement =
        document.getElementById('priceUnit');

    const info =
        document.getElementById('categoryInfo');

    const sizeHelp =
        document.getElementById('sizeHelp');


    let unit = 'Piece';

    let message =
        'Stock will be maintained in pieces.';


    if (category === 'Tiles') {

        unit = 'Box';

        message =
            'Tiles stock will be maintained by BOX. Example: 2x2, 4x2, 12x18, 16x16.';

        sizeHelp.innerText =
            'Example: 2x2, 4x2, 12x18, 16x16';

    }


    else if (category === 'Marble') {

        unit = 'Sq.Ft';

        message =
            'Marble stock will be maintained in Square Feet.';

        sizeHelp.innerText =
            'Example: Marble slab / size details';

    }


    else if (category === 'Hardware') {

        unit = 'Piece';

        message =
            'Hardware stock will be maintained by Piece.';

        sizeHelp.innerText =
            'Example: 1 inch, 2 inch, Standard';

    }


    else if (category === 'Stationary') {

        unit = 'Piece';

        message =
            'Stationary stock will be maintained by Piece.';

        sizeHelp.innerText =
            'Product size/details';

    }


    unitElement.innerText = unit;

    priceUnitElement.innerText =
        '/ ' + unit;

    info.innerText = message;
}


function openProductModal()
{
    updateProductUnit();
}

</script>