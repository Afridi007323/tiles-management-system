<div class="container-fluid py-4">

    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                <i class="fas fa-hand-holding-dollar me-2"></i>
                Receive Payment
            </h3>

            <p class="text-muted mb-0">
                Receive pending payment from customer
            </p>

        </div>


        <a
            href="<?= base_url('PaymentController/completed') ?>"
            class="btn btn-outline-success"
        >
            <i class="fas fa-check-circle me-1"></i>
            Completed Payments
        </a>

    </div>


    <!-- =====================================================
         SUCCESS MESSAGE
    ====================================================== -->

    <?php if ($this->session->flashdata('toast_success')): ?>

        <div
            id="paymentSuccessAlert"
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            <i class="fas fa-check-circle me-2"></i>

            <?= html_escape(
                $this->session->flashdata('toast_success')
            ); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         ERROR MESSAGE
    ====================================================== -->

    <?php if ($this->session->flashdata('toast_error')): ?>

        <div
            id="paymentErrorAlert"
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >

            <i class="fas fa-exclamation-circle me-2"></i>

            <?= html_escape(
                $this->session->flashdata('toast_error')
            ); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         PAYMENT CARD
    ====================================================== -->

    <div class="row justify-content-center">

        <div class="col-lg-7 col-xl-6">

            <div class="card border-0 shadow-sm">


                <!-- CARD HEADER -->

                <div class="card-header bg-primary text-white py-3">

                    <h5 class="mb-0">

                        <i class="fas fa-money-bill-wave me-2"></i>

                        Receive Customer Payment

                    </h5>

                </div>


                <!-- CARD BODY -->

                <div class="card-body p-4">


                    <form
                        action="<?= base_url('PaymentController/save') ?>"
                        method="POST"
                        id="receivePaymentForm"
                    >


                        <!-- =================================================
                             CUSTOMER
                        ================================================== -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold"
                                for="customerSearch"
                            >

                                Customer

                            </label>


                            <!-- SEARCH CUSTOMER -->

                            <div class="input-group mb-2">

                                <span class="input-group-text">

                                    <i class="fas fa-search"></i>

                                </span>


                                <input
                                    type="text"
                                    id="customerSearch"
                                    class="form-control"
                                    placeholder="Search customer by name or mobile..."
                                    autocomplete="off"
                                >

                            </div>


                            <!-- CUSTOMER DROPDOWN -->

                            <select
                                name="customer_id"
                                id="customer_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Customer
                                </option>


                                <?php if (!empty($customers)): ?>

                                    <?php foreach ($customers as $customer): ?>

                                        <option
                                            value="<?= (int)$customer['id'] ?>"
                                            data-name="<?= html_escape(
                                                strtolower(
                                                    $customer['name']
                                                )
                                            ) ?>"
                                            data-mobile="<?= html_escape(
                                                $customer['mobile'] ?? ''
                                            ) ?>"
                                            data-due="<?= (float)(
                                                $customer['total_due'] ?? 0
                                            ) ?>"
                                        >

                                            <?= html_escape(
                                                $customer['name']
                                            ) ?>


                                            <?php if (!empty($customer['mobile'])): ?>

                                                -
                                                <?= html_escape(
                                                    $customer['mobile']
                                                ) ?>

                                            <?php endif; ?>


                                            —
                                            Due ₹<?= number_format(
                                                (float)(
                                                    $customer['total_due'] ?? 0
                                                ),
                                                2
                                            ) ?>

                                        </option>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <option
                                        value=""
                                        disabled
                                    >

                                        No customer has pending due

                                    </option>

                                <?php endif; ?>

                            </select>

                            <!-- SEARCH RESULT MESSAGE -->

                            <small
                                id="customerSearchMessage"
                                class="text-muted d-none"
                            ></small>

                        </div>


                        <!-- =================================================
                             CURRENT DUE
                        ================================================== -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Current Due

                            </label>


                            <div
                                class="alert alert-warning mb-0"
                                id="currentDueBox"
                            >

                                <i class="fas fa-clock me-2"></i>

                                Current Due:

                                <strong id="currentDue">
                                    ₹0.00
                                </strong>

                            </div>

                        </div>


                        <!-- =================================================
                             PAYMENT AMOUNT
                        ================================================== -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold"
                                for="amount"
                            >

                                Payment Amount

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>


                                <input
                                    type="number"
                                    name="amount"
                                    id="amount"
                                    class="form-control"
                                    min="0.01"
                                    step="0.01"
                                    placeholder="Enter payment amount"
                                    required
                                >

                            </div>


                            <small
                                id="amountHelp"
                                class="text-muted"
                            >
                                Select a customer first.
                            </small>

                        </div>


                        <!-- =================================================
                             PAYMENT MODE
                        ================================================== -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold"
                                for="payment_mode"
                            >

                                Payment Mode

                            </label>


                            <select
                                name="payment_mode"
                                id="payment_mode"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Payment Mode
                                </option>


                                <option value="Cash">
                                    Cash
                                </option>


                                <option value="UPI">
                                    UPI
                                </option>


                                <option value="Bank Transfer">
                                    Bank Transfer
                                </option>


                                <option value="Cheque">
                                    Cheque
                                </option>

                            </select>

                        </div>


                        <!-- =================================================
                             NOTE
                        ================================================== -->

                        <div class="mb-4">

                            <label
                                class="form-label fw-semibold"
                                for="note"
                            >

                                Note

                            </label>


                            <textarea
                                name="note"
                                id="note"
                                class="form-control"
                                rows="3"
                                placeholder="Optional note..."
                            ></textarea>

                        </div>


                        <!-- =================================================
                             SUBMIT BUTTON
                        ================================================== -->

                        <button
                            type="submit"
                            id="receivePaymentBtn"
                            class="btn btn-primary w-100"
                        >

                            <i class="fas fa-check-circle me-2"></i>

                            Receive Payment

                        </button>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =====================================================
       ELEMENTS
    ====================================================== */

    const customerSearch =
        document.getElementById('customerSearch');

    const customerSelect =
        document.getElementById('customer_id');

    const currentDue =
        document.getElementById('currentDue');

    const amount =
        document.getElementById('amount');

    const amountHelp =
        document.getElementById('amountHelp');

    const searchMessage =
        document.getElementById('customerSearchMessage');

    const form =
        document.getElementById('receivePaymentForm');

    const submitButton =
        document.getElementById('receivePaymentBtn');


    /* =====================================================
       UPDATE CURRENT DUE
    ====================================================== */

    function updateCustomerDue() {

        const selected =
            customerSelect.options[
                customerSelect.selectedIndex
            ];


        if (!selected || !selected.value) {

            currentDue.textContent =
                '₹0.00';

            amount.removeAttribute('max');

            amount.value = '';

            amountHelp.textContent =
                'Select a customer first.';

            amountHelp.className =
                'text-muted';

            return;
        }


        const due =
            parseFloat(
                selected.getAttribute('data-due')
            ) || 0;


        currentDue.textContent =
            '₹' + due.toFixed(2);


        amount.max = due;

        amount.value = '';


        amountHelp.textContent =
            'Maximum payment: ₹' +
            due.toFixed(2);

        amountHelp.className =
            'text-muted';

    }


    /* =====================================================
       CUSTOMER SEARCH
    ====================================================== */

    customerSearch.addEventListener(
        'input',
        function () {

            const search =
                this.value
                    .toLowerCase()
                    .trim();


            const options =
                customerSelect.querySelectorAll(
                    'option'
                );


            let visibleCount = 0;


            options.forEach(
                function (option, index) {

                    /*
                     * First option always visible
                     */

                    if (index === 0) {

                        option.hidden = false;

                        return;

                    }


                    const name =
                        (
                            option.getAttribute(
                                'data-name'
                            ) || ''
                        ).toLowerCase();


                    const mobile =
                        (
                            option.getAttribute(
                                'data-mobile'
                            ) || ''
                        ).toLowerCase();


                    if (
                        search === '' ||
                        name.includes(search) ||
                        mobile.includes(search)
                    ) {

                        option.hidden = false;

                        visibleCount++;

                    } else {

                        option.hidden = true;

                    }

                }
            );


            /*
             * Clear current selection
             */

            customerSelect.value = '';

            currentDue.textContent =
                '₹0.00';

            amount.value = '';

            amount.removeAttribute('max');


            /*
             * Search result message
             */

            if (search !== '') {

                if (visibleCount === 0) {

                    searchMessage.textContent =
                        'No customer found.';

                    searchMessage.className =
                        'text-danger';

                } else {

                    searchMessage.textContent =
                        visibleCount +
                        ' customer found.';

                    searchMessage.className =
                        'text-muted';

                }

            } else {

                searchMessage.textContent = '';

                searchMessage.className =
                    'text-muted d-none';

            }

        }
    );


    /* =====================================================
       CUSTOMER SELECT
    ====================================================== */

    customerSelect.addEventListener(
        'change',
        function () {

            updateCustomerDue();


            const selected =
                this.options[
                    this.selectedIndex
                ];


            /*
             * Selected customer ka naam
             * search box me show karna
             */

            if (
                selected &&
                selected.value
            ) {

                const name =
                    selected.getAttribute(
                        'data-name'
                    ) || '';


                const mobile =
                    selected.getAttribute(
                        'data-mobile'
                    ) || '';


                customerSearch.value =
                    mobile
                        ? name + ' - ' + mobile
                        : name;

            }

        }
    );


    /* =====================================================
       PAYMENT AMOUNT VALIDATION
    ====================================================== */

    amount.addEventListener(
        'input',
        function () {

            const selected =
                customerSelect.options[
                    customerSelect.selectedIndex
                ];


            if (
                !selected ||
                !selected.value
            ) {

                return;

            }


            const due =
                parseFloat(
                    selected.getAttribute(
                        'data-due'
                    )
                ) || 0;


            const entered =
                parseFloat(
                    this.value
                ) || 0;


            if (entered > due) {

                this.value =
                    due.toFixed(2);

            }


            if (entered > 0 && entered <= due) {

                amountHelp.textContent =
                    'Remaining after payment: ₹' +
                    (due - entered).toFixed(2);

                amountHelp.className =
                    'text-success';

            } else {

                amountHelp.textContent =
                    'Maximum payment: ₹' +
                    due.toFixed(2);

                amountHelp.className =
                    'text-muted';

            }

        }
    );


    /* =====================================================
       FORM SUBMIT
    ====================================================== */

    form.addEventListener(
        'submit',
        function (event) {

            const selected =
                customerSelect.options[
                    customerSelect.selectedIndex
                ];


            if (
                !selected ||
                !selected.value
            ) {

                event.preventDefault();

                alert(
                    'Please select a customer.'
                );

                return;

            }


            const due =
                parseFloat(
                    selected.getAttribute(
                        'data-due'
                    )
                ) || 0;


            const entered =
                parseFloat(
                    amount.value
                ) || 0;


            if (entered <= 0) {

                event.preventDefault();

                alert(
                    'Please enter payment amount.'
                );

                amount.focus();

                return;

            }


            if (entered > due) {

                event.preventDefault();

                alert(
                    'Payment cannot be greater than customer due.'
                );

                amount.focus();

                return;

            }


            /*
             * Button disable
             * double payment prevent karega
             */

            submitButton.disabled = true;

            submitButton.innerHTML =
                '<i class="fas fa-spinner fa-spin me-2"></i>' +
                'Saving Payment...';

        }
    );


    /* =====================================================
       SUCCESS MESSAGE - 10 SECONDS
    ====================================================== */

    setTimeout(
        function () {

            const alertBox =
                document.getElementById(
                    'paymentSuccessAlert'
                );


            if (alertBox) {

                if (
                    typeof bootstrap !== 'undefined'
                ) {

                    const alert =
                        bootstrap.Alert
                            .getOrCreateInstance(
                                alertBox
                            );

                    alert.close();

                } else {

                    alertBox.remove();

                }

            }

        },
        10000
    );


    /* =====================================================
       ERROR MESSAGE - 10 SECONDS
    ====================================================== */

    setTimeout(
        function () {

            const alertBox =
                document.getElementById(
                    'paymentErrorAlert'
                );


            if (alertBox) {

                if (
                    typeof bootstrap !== 'undefined'
                ) {

                    const alert =
                        bootstrap.Alert
                            .getOrCreateInstance(
                                alertBox
                            );

                    alert.close();

                } else {

                    alertBox.remove();

                }

            }

        },
        10000
    );

});

</script>  