





<style>









/* =========================================================
   MARBLE POS SIDEBAR
========================================================= */

* {
    box-sizing: border-box;
}

html,
body {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

body.sidebar-open {
    overflow: hidden;
}


/* =========================================================
   MAIN WRAPPER
========================================================= */

.wrapper-container {
    display: flex;
    width: 100%;
    min-height: 100vh;
    position: relative;
}


/* =========================================================
   SIDEBAR
========================================================= */

.sidebar {
    position: fixed;
    top: 0;
    left: 0;

    width: 260px;
    height: 100vh;

    background: #212529;
    color: #fff;

    overflow-y: auto;
    overflow-x: hidden;

    z-index: 1050;

    transform: translateX(0);
    transition:
        transform .28s ease,
        box-shadow .28s ease;
}


/* Scrollbar */

.sidebar::-webkit-scrollbar {
    width: 5px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #495057;
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-track {
    background: transparent;
}


/* =========================================================
   SIDEBAR BRAND
========================================================= */

.sidebar-brand {
    height: 72px;

    padding: 0 20px;

    display: flex;
    align-items: center;

    color: #fff;

    font-size: 1.25rem;
    font-weight: 700;

    border-bottom: 1px solid #373b3e;

    background: #212529;

    position: sticky;
    top: 0;

    z-index: 20;
}

.sidebar-brand i {
    font-size: 21px;
}


/* =========================================================
   SIDEBAR MENU
========================================================= */

.sidebar-menu {
    padding: 15px 10px 30px;
    margin: 0;
}

.sidebar-menu > li {
    margin: 4px 0;
}


/* =========================================================
   MAIN MENU LINKS
========================================================= */

.sidebar-menu > li > a {
    position: relative;

    min-height: 46px;

    padding: 11px 14px;

    display: flex;
    align-items: center;

    color: #ced4da;

    text-decoration: none;

    border-radius: 10px;

    font-size: 14px;
    font-weight: 500;

    transition:
        background .2s ease,
        color .2s ease,
        transform .2s ease;
}

.sidebar-menu > li > a > i:first-child {
    width: 25px;
    min-width: 25px;

    margin-right: 10px;

    text-align: center;

    font-size: 15px;
}

.sidebar-menu > li > a > span {
    flex: 1;
}


/* Hover */

.sidebar-menu > li > a:hover {
    color: #fff;
    background: #2c3034;
}


/* =========================================================
   DROPDOWN ARROW
========================================================= */

.sidebar-menu .dropdown-toggle::after {
    margin-left: auto;

    border: 0;

    content: "\f107";

    font-family: "Font Awesome 5 Free";
    font-weight: 900;

    font-size: 13px;

    transition: transform .25s ease;
}

.sidebar-menu .dropdown-toggle[aria-expanded="true"]::after {
    transform: rotate(180deg);
}


/* =========================================================
   ACTIVE PARENT
========================================================= */

.sidebar-menu > li > a.parent-active {
    background: #eef0ff !important;
    color: #4f46e5 !important;

    font-weight: 600;
}

.sidebar-menu > li > a.parent-active > i {
    color: #4f46e5 !important;
}


/* =========================================================
   SUBMENU
========================================================= */

.sidebar .submenu {
    padding: 5px 0 5px 34px;

    margin: 0;

    list-style: none;

    position: relative;
}


/* Vertical line */

.sidebar .submenu::before {
    content: "";

    position: absolute;

    left: 24px;
    top: 8px;
    bottom: 8px;

    width: 2px;

    background: #343a40;

    border-radius: 5px;
}


/* Submenu item */

.sidebar .submenu li {
    margin: 3px 0;
}


/* Submenu link */

.sidebar .submenu li a {
    position: relative;

    display: flex;
    align-items: center;

    min-height: 40px;

    padding: 9px 12px;

    border-radius: 9px;

    color: #adb5bd;

    text-decoration: none;

    font-size: 13px;

    transition:
        background .2s ease,
        color .2s ease;
}

.sidebar .submenu li a:hover {
    color: #fff;

    background: #2c3034;
}


/* =========================================================
   ACTIVE CHILD
========================================================= */

.sidebar .submenu li a.active-child {
    background: #eef0ff !important;
    color: #4f46e5 !important;

    font-weight: 600;
}

.sidebar .submenu li a.active-child::before {
    content: "";

    width: 6px;
    height: 6px;

    margin-right: 8px;

    flex-shrink: 0;

    background: #4f46e5;

    border-radius: 50%;
}


/* =========================================================
   SINGLE ACTIVE MENU
========================================================= */

.sidebar-menu > li > a.single-active {
    background: #eef0ff !important;
    color: #4f46e5 !important;

    font-weight: 600;
}

.sidebar-menu > li > a.single-active > i {
    color: #4f46e5 !important;
}


/* =========================================================
   LOGOUT
========================================================= */

.sidebar-menu > li > a.logout-link {
    color: #ff7777;
}

.sidebar-menu > li > a.logout-link:hover {
    color: #fff;

    background: #dc3545;
}


/* =========================================================
   MAIN CONTENT
========================================================= */

.main-content {
    flex: 1;

    width: calc(100% - 260px);

    margin-left: 260px;

    padding: 20px;

    min-width: 0;

    overflow-x: hidden;
}


/* =========================================================
   TOP NAVBAR
========================================================= */

.navbar-top {
    min-height: 62px;

    padding: 0 20px;

    margin-bottom: 20px;

    background: #fff;

    border-radius: 14px;

    box-shadow: 0 3px 15px rgba(0, 0, 0, .06);
}


/* =========================================================
   MOBILE SIDEBAR - POLISHED
========================================================= */

.mobile-menu-button {
    width: 42px;
    height: 42px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #dee2e6;
    border-radius: 10px;

    background: #ffffff;
    color: #212529;

    font-size: 18px;

    cursor: pointer;

    flex-shrink: 0;

    transition:
        background .2s ease,
        color .2s ease,
        transform .15s ease,
        box-shadow .2s ease;
}

.mobile-menu-button:hover {
    background: #f1f3f5;
}

.mobile-menu-button:active {
    transform: scale(.94);
}

.mobile-menu-button:focus {
    outline: none;

    box-shadow:
        0 0 0 3px rgba(13, 110, 253, .15);
}


/* =========================================================
   SIDEBAR CLOSE BUTTON
========================================================= */

.sidebar-close-button {
    width: 38px;
    height: 38px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    margin-left: auto;

    border: 1px solid rgba(255,255,255,.18);
    border-radius: 9px;

    background: rgba(255,255,255,.08);
    color: #ffffff;

    font-size: 17px;

    cursor: pointer;

    transition:
        background .2s ease,
        transform .15s ease;
}

.sidebar-close-button:hover {
    background: rgba(255,255,255,.18);
}

.sidebar-close-button:active {
    transform: scale(.92);
}


/* =========================================================
   OVERLAY
========================================================= */

.sidebar-overlay {
    position: fixed;

    inset: 0;

    width: 100%;
    height: 100%;

    background: rgba(15, 23, 42, .55);

    opacity: 0;
    visibility: hidden;

    pointer-events: none;

    z-index: 1040;

    transition:
        opacity .25s ease,
        visibility .25s ease;

    backdrop-filter: blur(2px);
}

.sidebar-overlay.active {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}


/* =========================================================
   DESKTOP
========================================================= */

@media (min-width: 769px) {

    .sidebar {
        left: 0 !important;
        transform: translateX(0) !important;
    }

    .sidebar-overlay {
        display: none !important;
    }

    .mobile-menu-button,
    .sidebar-close-button {
        display: none !important;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    body {
        overflow-x: hidden;
    }

    body.sidebar-open {
        overflow: hidden;
    }


    /* Sidebar hidden */

    .sidebar {
        position: fixed;

        top: 0;
        left: 0;

        width: 280px;
        max-width: 85vw;

        height: 100dvh;

        transform: translateX(-105%);

        z-index: 1050;

        box-shadow: none;

        transition:
            transform .28s cubic-bezier(.4,0,.2,1),
            box-shadow .28s ease;
    }


    /* Sidebar open */

    .sidebar.active {
        transform: translateX(0);

        box-shadow:
            8px 0 30px rgba(0,0,0,.28);
    }


    /* Main content */

    .main-content {
        width: 100%;

        margin-left: 0;

        padding: 10px;

        min-width: 0;
    }


    /* Top navbar */

    .navbar-top {
        min-height: 58px;

        padding: 0 12px;

        margin-bottom: 12px;

        border-radius: 12px;
    }


    /* Menu button */

    .mobile-menu-button {
        display: inline-flex !important;

        margin-right: 10px;
    }


    /* Sidebar brand */

    .sidebar-brand {
        height: 64px;

        padding: 0 14px;
    }


    /* Sidebar menu */

    .sidebar-menu {
        padding: 12px 10px 30px;
    }


    .sidebar-menu > li {
        margin: 3px 0;
    }


    .sidebar-menu > li > a {
        min-height: 46px;

        padding: 11px 13px;

        font-size: 14px;
    }


    /* Submenu */

    .sidebar .submenu {
        padding-left: 30px;
    }


    .sidebar .submenu li a {
        min-height: 40px;

        padding: 9px 11px;

        font-size: 13px;
    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 380px) {

    .sidebar {
        width: 255px;
    }

    .main-content {
        padding: 8px;
    }

    .navbar-top {
        padding: 0 10px;
    }

}






/* Active parent menu */
.sidebar-menu > li > a.parent-active,
.sidebar .sidebar-menu > li > a.parent-active {
    background: #eef0ff !important;
    color: #4f46e5 !important;
    font-weight: 600;
}

.sidebar-menu > li > a.parent-active i {
    color: #4f46e5 !important;
}

/* Active submenu category */
.sidebar .submenu li a.active-child {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #eef0ff !important;
    color: #4f46e5 !important;
    font-weight: 600;
}

.sidebar .submenu li a.active-child i {
    color: #4f46e5 !important;
}
</style>



<?php
$current_uri = strtolower(trim(uri_string(), '/'));
$segments = array_values(array_filter(explode('/', $current_uri), 'strlen'));

$is_dashboard = (
    $current_uri === '' ||
    $current_uri === 'dashboard' ||
    $current_uri === 'managementcontroller/dashboard'
);

/* Customers */
$is_customers_page = in_array($current_uri, array(
    'customercontroller/customers',
    'customercontroller/index'
), true);

$is_customer_dues = in_array($current_uri, array(
    'customercontroller/due_reports',
    'customercontroller/customer_dues'
), true);

$is_customers_open = $is_customers_page || $is_customer_dues;

/* Sales & Billing */
$is_new_invoice = in_array($current_uri, array(
    'billingcontroller/billing',
    'billingcontroller/index'
), true);

$is_all_invoices = in_array($current_uri, array(
    'billingcontroller/invoices',
    'billingcontroller/all_invoices'
), true);

$is_sales_billing_open = $is_new_invoice || $is_all_invoices;

/* Inventory */
$is_products = in_array($current_uri, array(
    'productcontroller/index',
    'products',
    'productcontroller'
), true);

$category = '';
if (
    count($segments) >= 3 &&
    $segments[0] === 'productcontroller' &&
    $segments[1] === 'category'
) {
    $category = $segments[2];
}

$is_tiles = ($category === 'tiles');
$is_marble = ($category === 'marble');
$is_hardware = ($category === 'hardware');
$is_stationary = ($category === 'stationary');

$is_inventory_open = (
    $is_products ||
    $is_tiles ||
    $is_marble ||
    $is_hardware ||
    $is_stationary
);



/* Payments & Dues */
$is_receive_payment = ($current_uri === 'paymentcontroller/receive');
$is_completed_payment = ($current_uri === 'paymentcontroller/completed');

$is_payments_open = (
    $is_receive_payment ||
    $is_completed_payment ||
    $is_customer_dues
);

/* Daily Transactions */
$current_uri = strtolower(trim(uri_string(), '/'));

$is_daily_entries = (
    $current_uri === 'managementcontroller/daily_entries'
);

$is_party_ledger = (
    $current_uri === 'managementcontroller/party_ledger'
);

$is_transactions_open = (
    $is_daily_entries || $is_party_ledger
);









/* =========================================================
   PURCHASE MANAGEMENT - ACTIVE MENU
========================================================= */

$is_new_purchase = in_array($current_uri, array(
    'managementcontroller/purchases',
    'managementcontroller/new_purchase',
    'purchasecontroller/new_purchase'
), true);

$is_purchase_history = in_array($current_uri, array(
    'managementcontroller/purchase_history',
    'purchasecontroller/history',
    'purchasecontroller/purchase_history'
), true);

/* All Products Purchase */
$is_purchase_items = (
    $current_uri === 'managementcontroller/purchase_items'
);

$is_supplier_payment = (
    $current_uri === 'managementcontroller/pay_supplier_due'
);

$is_supplier_ledger = (
    $current_uri === 'managementcontroller/supplier_ledger'
);

$is_purchase_open = (
    $is_new_purchase ||
    $is_purchase_history ||
    $is_purchase_items ||
    $is_supplier_payment ||
    $is_supplier_ledger
);






/* =========================================================
   REPORTS
========================================================= */

$is_reports = (
    $current_uri === 'ManagementController/reports'
);


/* =========================================================
   SETTINGS
========================================================= */

$is_settings = (
    $current_uri === 'ManagementController/settings'
);

?>
















<div class="wrapper-container">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar" id="sidebarMenu">


        <!-- BRAND -->

       <div class="sidebar-brand">

    <span>
        <i class="fas fa-cubes text-primary me-2"></i>
        MarblePOS
    </span>

    <button
        type="button"
        id="sidebarCloseButton"
        class="sidebar-close-button d-md-none"
        aria-label="Close menu"
    >
        <i class="fas fa-times"></i>
    </button>

</div>


        <!-- MENU -->

        <ul class="sidebar-menu list-unstyled mb-0">


            <!-- =================================================
                 DASHBOARD
            ================================================== -->

            <li>

                <a
                    href="<?= base_url('dashboard') ?>"
                    class="<?= $is_dashboard ? 'single-active' : '' ?>">

                    <i class="fas fa-tachometer-alt"></i>

                    <span>Dashboard</span>

                </a>

            </li>



            <!-- =================================================
                 CUSTOMERS
            ================================================== -->

            <li>

                <a
                    href="#customerSubmenu"
                    class="dropdown-toggle <?= $is_customers_open ? 'parent-active' : '' ?>"
                    data-bs-toggle="collapse"
                    aria-expanded="<?= $is_customers_open ? 'true' : 'false' ?>">

                    <i class="fas fa-users"></i>

                    <span>Customers</span>

                </a>


                <div
                    id="customerSubmenu"
                    class="collapse <?= $is_customers_open ? 'show' : '' ?>">

                    <ul class="submenu">

                        <li>

                            <a
                                href="<?= base_url('CustomerController/index') ?>"
                                class="<?= $is_customers_page ? 'active-child' : '' ?>">

                                All Customers

                            </a>

                        </li>


                        <li>

                            <a href="<?= base_url('customers/dues') ?>">
                                Customer Dues
                            </a>

                        </li>

                    </ul>

                </div>

            </li>



            <!-- =================================================
                 SALES & BILLING
            ================================================== -->

            <li>

                <a
                    href="#billingSubmenu"
                    class="dropdown-toggle <?= $is_sales_billing_open ? 'parent-active' : '' ?>"
                    data-bs-toggle="collapse"
                    aria-expanded="<?= $is_sales_billing_open ? 'true' : 'false' ?>">

                    <i class="fas fa-file-invoice-dollar"></i>

                    <span>Sales & Billing</span>

                </a>


                <div
                    id="billingSubmenu"
                    class="collapse <?= $is_sales_billing_open ? 'show' : '' ?>">

                    <ul class="submenu">

                        <li>

                            <a href="<?= base_url('billing') ?>">
                                New Invoice
                            </a>
                        </li>


                        <li>

                            <a
                                href="<?= base_url('BillingController/all_invoices') ?>"
                                class="sidebar-link">
                                <i class="fas fa-file-invoice me-2"></i>
                                All Invoices
                            </a>

                        </li>
                    </ul>

                </div>

            </li>



            <!-- =====================================================
     INVENTORY
===================================================== -->

            <li class="sidebar-parent">

                <!-- Inventory Parent -->
                
<a href="#inventorySubmenu"
   class="dropdown-toggle <?= $is_inventory_open ? 'parent-active' : '' ?>"
   data-bs-toggle="collapse"
   role="button"
   aria-expanded="<?= $is_inventory_open ? 'true' : 'false' ?>"
   aria-controls="inventorySubmenu">
    <i class="fas fa-boxes-stacked"></i>
    <span>Inventory</span>
</a>


                <!-- Inventory Submenu -->
               
<ul id="inventorySubmenu"
    class="submenu collapse <?= $is_inventory_open ? 'show' : '' ?>">

    <li>
        <a href="<?= base_url('ProductController/index') ?>"
           class="<?= $is_products ? 'active-child' : '' ?>">
            <i class="fas fa-box"></i>
            <span>All Products</span>
        </a>
    </li>

    <li>
        <a href="<?= base_url('ProductController/category/Tiles') ?>"
           class="<?= $is_tiles ? 'active-child' : '' ?>">
            <i class="fas fa-border-all"></i>
            <span>Tiles Stock</span>
        </a>
    </li>

    <li>
        <a href="<?= base_url('ProductController/category/Marble') ?>"
           class="<?= $is_marble ? 'active-child' : '' ?>">
            <i class="fas fa-gem"></i>
            <span>Marble Stock</span>
        </a>
    </li>

    <li>
        <a href="<?= base_url('ProductController/category/Hardware') ?>"
           class="<?= $is_hardware ? 'active-child' : '' ?>">
            <i class="fas fa-tools"></i>
            <span>Hardware Items</span>
        </a>
    </li>

    <li>
        <a href="<?= base_url('ProductController/category/Stationary') ?>"
           class="<?= $is_stationary ? 'active-child' : '' ?>">
            <i class="fas fa-pen"></i>
            <span>Stationary Stock</span>
        </a>
    </li>

</ul>






            <!-- =================================================
                 PAYMENTS & DUES
            ================================================== -->

            <li>

                <a
                    href="#paymentSubmenu"
                    class="dropdown-toggle <?= $is_payments_open ? 'parent-active' : '' ?>"
                    data-bs-toggle="collapse"
                    aria-expanded="<?= $is_payments_open ? 'true' : 'false' ?>">

                    <i class="fas fa-wallet"></i>

                    <span>Payments & Dues</span>

                </a>


                <div
                    id="paymentSubmenu"
                    class="collapse <?= $is_payments_open ? 'show' : '' ?>">

                    <ul class="submenu">

                        <li>

                            <a
                                href="<?= base_url('PaymentController/receive') ?>"
                                class="<?= $is_receive_payment ? 'active-child' : '' ?>">

                                Receive Payment

                            </a>

                        </li>


                        <li>

                            <a
                                href="<?= base_url('PaymentController/completed') ?>"
                                class="<?= $is_completed_payment ? 'active-child' : '' ?>">

                                Completed Payments

                            </a>

                        </li>

                    </ul>

                </div>

            </li>



            <!-- =================================================
                 DAILY TRANSACTIONS
            ================================================== -->

            <li>

                <a
                    href="#transactionSubmenu"
                    class="dropdown-toggle <?= $is_transactions_open ? 'parent-active' : '' ?>"
                    data-bs-toggle="collapse"
                    aria-expanded="<?= $is_transactions_open ? 'true' : 'false' ?>">

                    <i class="fas fa-exchange-alt"></i>

                    <span>Daily Transactions</span>

                </a>


                <div
                    id="transactionSubmenu"
                    class="collapse <?= $is_transactions_open ? 'show' : '' ?>">

                    <ul class="submenu">

                        <li>

                            <a href="<?= base_url('ManagementController/daily_entries') ?>"
   class="<?= $is_daily_entries ? 'active-child' : '' ?>">
    Daily Entries
</a>

                        </li>


                        <li>

                           <a href="<?= base_url('ManagementController/party_ledger') ?>"
   class="<?= $is_party_ledger ? 'active-child' : '' ?>">
    Party Ledger
</a>

                        </li>

                    </ul>

                </div>

            </li>



















          <!-- =================================================
     PURCHASE MANAGEMENT
================================================== -->

<li class="sidebar-parent">

    <a
        href="#purchaseSubmenu"
        class="dropdown-toggle <?= $is_purchase_open ? 'parent-active' : '' ?>"
        data-bs-toggle="collapse"
        role="button"
        aria-expanded="<?= $is_purchase_open ? 'true' : 'false' ?>"
        aria-controls="purchaseSubmenu">

        <i class="fas fa-truck-loading"></i>
        <span>Purchase Management</span>

    </a>

    <div
        id="purchaseSubmenu"
        class="collapse <?= $is_purchase_open ? 'show' : '' ?>">

        <ul class="submenu">




 <!-- All Products Purchase -->
            <li>
                <a
                    href="<?= base_url('ManagementController/purchase_items') ?>"
                    class="<?= $is_purchase_items ? 'active-child' : '' ?>">

                    <i class="fas fa-boxes me-2"></i>
                    <span>All Products Purchase</span>
                </a>
            </li>


            <!-- New Purchase -->
            <li>
                <a
                    href="<?= base_url('ManagementController/purchases') ?>"
                    class="<?= $is_new_purchase ? 'active-child' : '' ?>">

                    <i class="fas fa-plus-circle me-2"></i>
                    <span>New Purchase</span>
                </a>
            </li>

            <!-- Purchase History -->
            <li>
                
<a
    href="<?= base_url('ManagementController/purchase_history') ?>"
    class="<?= $is_purchase_history ? 'active-child' : '' ?>">

    <i class="fas fa-history me-2 "></i>
    <span>Purchase History</span>
</a>

            </li>

           

            <!-- Pay Supplier Due -->
            <li>
                <a
                    href="<?= base_url('ManagementController/pay_supplier_due') ?>"
                    class="<?= $is_supplier_payment ? 'active-child' : '' ?>">

                    <i class="fas fa-money-bill-wave me-2"></i>
                    <span>Pay Supplier Due</span>
                </a>
            </li>





<li>
    <a
        href="<?= base_url('ManagementController/suppliers') ?>"
        class="<?= $current_uri === 'managementcontroller/suppliers' ? 'active-child' : '' ?>">

        <i class="fas fa-users me-2"></i>
        <span>Suppliers / Vendors</span>
    </a>
</li>




            <!-- Supplier Ledger -->
            <li>
                <a
                    href="<?= base_url('ManagementController/supplier_ledger') ?>"
                    class="<?= $is_supplier_ledger ? 'active-child' : '' ?>">

                    <i class="fas fa-book me-2"></i>
                    <span>Supplier Ledger</span>
                </a>
            </li>

        </ul>

    </div>

</li>



            <!-- =================================================
                 STOCK ALERTS
            ================================================== -->

            <li>

                <a href="<?= site_url('ManagementController/stock_alerts') ?>"
   class="nav-link">
    <i class="fas fa-bell"></i>
    <span>Stock Alerts</span>
</a>

            </li>



            <!-- =================================================
                 REPORTS
            ================================================== -->

            <li>

                <a
                    href="<?= base_url('ManagementController/reports') ?>"
                    class="<?= $is_reports ? 'single-active' : '' ?>">

                    <i class="fas fa-chart-bar"></i>

                    <span>Reports</span>

                </a>

            </li>



            <!-- =================================================
                 SETTINGS
            ================================================== -->

            <li>

                <a
                    href="<?= base_url('ManagementController/settings') ?>"
                    class="<?= $is_settings ? 'single-active' : '' ?>">

                    <i class="fas fa-cog"></i>

                    <span>Settings</span>

                </a>

            </li>



            <!-- =================================================
                 LOGOUT
            ================================================== -->

            <li>
               <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger">
    <i class="fa-solid fa-right-from-bracket me-2"></i>
    Logout
</a>

            </li>


        </ul>

    </aside>



    <!-- MOBILE OVERLAY -->

<div
    class="sidebar-overlay"
    id="sidebarOverlay">
</div>



    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main class="main-content">

    <div class="navbar-top d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">

            <button
                type="button"
                id="mobileMenuButton"
                class="mobile-menu-button d-md-none"
                aria-label="Open menu"
                aria-expanded="false"
                aria-controls="sidebarMenu"
            >
                <i class="fas fa-bars"></i>
            </button>

            <h4
                class="m-0 text-dark"
                style="font-size:1.1rem;"
            >
                <?= isset($page_title)
                    ? htmlspecialchars($page_title)
                    : 'Dashboard Overview'; ?>
            </h4>

        </div>

        <div>

            <span class="me-3 fw-bold text-secondary d-none d-sm-inline">
                <i class="fas fa-calendar-day text-primary"></i>
                <?= date('d M Y') ?>
            </span>

            <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger">
    <i class="fas fa-sign-out-alt me-1"></i> Logout
</a>

        </div>

    </div>











       <script>
(function () {

    'use strict';

    function initMobileSidebar() {

        const sidebar =
            document.getElementById('sidebarMenu');

        const menuButton =
            document.getElementById('mobileMenuButton');

        const closeButton =
            document.getElementById('sidebarCloseButton');

        const overlay =
            document.getElementById('sidebarOverlay');


        if (!sidebar) {
            console.error(
                'Mobile Sidebar Error: #sidebarMenu not found.'
            );
            return;
        }


        if (!menuButton) {
            console.error(
                'Mobile Sidebar Error: #mobileMenuButton not found.'
            );
            return;
        }


        /* =====================================================
           OPEN SIDEBAR
        ===================================================== */

        function openSidebar() {

            if (window.innerWidth > 768) {
                return;
            }

            sidebar.classList.add('active');

            if (overlay) {
                overlay.classList.add('active');
            }

            document.body.classList.add('sidebar-open');


            menuButton.setAttribute(
                'aria-expanded',
                'true'
            );

            menuButton.setAttribute(
                'aria-label',
                'Close menu'
            );


            const icon =
                menuButton.querySelector('i');

            if (icon) {

                icon.classList.remove(
                    'fa-bars'
                );

                icon.classList.add(
                    'fa-times'
                );

            }

        }


        /* =====================================================
           CLOSE SIDEBAR
        ===================================================== */

        function closeSidebar() {

            sidebar.classList.remove('active');

            if (overlay) {
                overlay.classList.remove('active');
            }

            document.body.classList.remove(
                'sidebar-open'
            );


            menuButton.setAttribute(
                'aria-expanded',
                'false'
            );

            menuButton.setAttribute(
                'aria-label',
                'Open menu'
            );


            const icon =
                menuButton.querySelector('i');

            if (icon) {

                icon.classList.remove(
                    'fa-times'
                );

                icon.classList.add(
                    'fa-bars'
                );

            }

        }


        /* =====================================================
           TOGGLE
        ===================================================== */

        function toggleSidebar() {

            if (
                sidebar.classList.contains('active')
            ) {

                closeSidebar();

            } else {

                openSidebar();

            }

        }


        /* =====================================================
           OPEN MENU BUTTON
        ===================================================== */

        menuButton.addEventListener(
            'click',
            function (event) {

                event.preventDefault();
                event.stopPropagation();

                toggleSidebar();

            }
        );


        /* =====================================================
           CLOSE BUTTON
        ===================================================== */

        if (closeButton) {

            closeButton.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    closeSidebar();

                }
            );

        }


        /* =====================================================
           OVERLAY CLICK
        ===================================================== */

        if (overlay) {

            overlay.addEventListener(
                'click',
                function () {

                    closeSidebar();

                }
            );

        }


        /* =====================================================
           ESC KEY
        ===================================================== */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape' &&
                    sidebar.classList.contains('active')
                ) {

                    closeSidebar();

                }

            }
        );


        /* =====================================================
           SIDEBAR LINK CLICK
        ===================================================== */

        sidebar.addEventListener(
            'click',
            function (event) {

                const link =
                    event.target.closest('a');

                if (!link) {
                    return;
                }


                /*
                 * Dropdown parent par click hone par
                 * sidebar close nahi hoga.
                 */

                if (
                    link.classList.contains(
                        'dropdown-toggle'
                    )
                ) {

                    return;

                }


                const href =
                    link.getAttribute('href');


                if (
                    !href ||
                    href === '#' ||
                    href.startsWith('javascript:')
                ) {

                    return;

                }


                if (
                    window.innerWidth <= 768
                ) {

                    setTimeout(
                        closeSidebar,
                        150
                    );

                }

            }
        );


        /* =====================================================
           RESIZE
        ===================================================== */

        window.addEventListener(
            'resize',
            function () {

                if (
                    window.innerWidth > 768
                ) {

                    closeSidebar();

                }

            }
        );


        /* =====================================================
           GLOBAL FUNCTION
        ===================================================== */

        window.toggleSidebar =
            toggleSidebar;


        console.log(
            'Mobile sidebar initialized successfully.'
        );

    }


    /* =========================================================
       DOM READY
    ========================================================= */

    if (
        document.readyState === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            initMobileSidebar
        );

    } else {

        initMobileSidebar();

    }

})();
</script>