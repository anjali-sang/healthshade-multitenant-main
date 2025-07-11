<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>
        Health Shade - Inventory Management Software for Medical Inventory &amp;
        Supplies
    </title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/stellarnav.min.css" />
    <link rel="stylesheet" href="css/animate.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
</head>

<style>
    body {
        font-size: 0.875rem;
    }

    .feather {
        width: 16px;
        height: 16px;
        vertical-align: text-bottom;
    }

    /*
 * Sidebar
 */

    .sidebar {
        position: fixed;
        top: 0;
        /* rtl:raw:
  right: 0;
  */
        bottom: 0;
        /* rtl:remove */
        left: 0;
        z-index: 100;
        /* Behind the navbar */
        padding: 48px 0 0;
        /* Height of navbar */
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 767.98px) {
        .sidebar {
            top: 5rem;
        }
    }

    .sidebar-sticky {
        position: relative;
        top: 0;
        height: calc(100vh - 48px);
        padding-top: 0.5rem;
        overflow-x: hidden;
        overflow-y: auto;
        /* Scrollable contents if viewport is shorter than content. */
    }

    .sidebar .nav-link {
        font-weight: 500;
        color: #333;
    }

    .sidebar .nav-link .feather {
        margin-right: 4px;
        color: #727272;
    }

    .sidebar .nav-link.active {
        color: #2470dc;
    }

    .sidebar .nav-link:hover .feather,
    .sidebar .nav-link.active .feather {
        color: inherit;
    }

    .sidebar-heading {
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    /*
 * Navbar
 */

    .navbar-brand {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        font-size: 1rem;
        background-color: rgba(0, 0, 0, 0.25);
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, 0.25);
    }

    .navbar .navbar-toggler {
        top: 0.25rem;
        right: 1rem;
    }

    .navbar .form-control {
        padding: 0.75rem 1rem;
        border-width: 0;
        border-radius: 0;
    }

    .form-control-dark {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.1);
    }

    .form-control-dark:focus {
        border-color: transparent;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.25);
    }

    /* contact form css */
    *,
    *:before,
    *:after {
        box-sizing: border-box;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }





    .background {
        display: flex;
        min-height: 100vh;
        background: linear-gradient(to right, #ea1d6f 0%, #eb466b 100%);
        font-size: 12px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        letter-spacing: 1.4px;
    }

    .container {
        flex: 0 1 700px;
        margin: auto;
        padding: 10px;
    }

    .screen {
        position: relative;
        background: #3e3e3e;
        border-radius: 15px;
    }

    .screen:after {
        content: '';
        display: block;
        position: absolute;
        top: 0;
        left: 20px;
        right: 20px;
        bottom: 0;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, .4);
        z-index: -1;
    }

    .screen-header {
        display: flex;
        align-items: center;
        padding: 10px 20px;
        background: #4d4d4f;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .screen-header-left {
        margin-right: auto;
    }

    .screen-header-button {
        display: inline-block;
        width: 8px;
        height: 8px;
        margin-right: 3px;
        border-radius: 8px;
        background: white;
    }

    .screen-header-button.close {
        background: #ed1c6f;
    }

    .screen-header-button.maximize {
        background: #e8e925;
    }

    .screen-header-button.minimize {
        background: #74c54f;
    }

    .screen-header-right {
        display: flex;
    }

    .screen-header-ellipsis {
        width: 3px;
        height: 3px;
        margin-left: 2px;
        border-radius: 8px;
        background: #999;
    }

    .screen-body {
        display: flex;
    }

    .screen-body-item {
        flex: 1;
        padding: 50px;
    }

    .screen-body-item.left {
        display: flex;
        flex-direction: column;
    }

    .app-title {
        display: flex;
        flex-direction: column;
        position: relative;
        color: #ea1d6f;
        font-size: 26px;
    }

    .app-title:after {
        content: '';
        display: block;
        position: absolute;
        left: 0;
        bottom: -10px;
        width: 25px;
        height: 4px;
        background: #ea1d6f;
    }

    .app-contact {
        margin-top: auto;
        font-size: 8px;
        color: #888;
    }

    .app-form-group {
        margin-bottom: 15px;
    }

    .app-form-group.message {
        margin-top: 40px;
    }

    .app-form-group.buttons {
        margin-bottom: 0;
        text-align: right;
    }

    .app-form-control {
        width: 100%;
        padding: 10px 0;
        background: none;
        border: none;
        border-bottom: 1px solid #666;
        color: #ddd;
        font-size: 14px;
        outline: none;
        transition: border-color .2s;
    }

    .app-form-control::placeholder {
        color: #666;
    }

    .app-form-control:focus {
        border-bottom-color: #ddd;
    }

    .app-form-button {
        background: none;
        border: none;
        color: #ea1d6f;
        font-size: 14px;
        cursor: pointer;
        outline: none;
    }

    .app-form-button:hover {
        color: #b9134f;
    }

    .credits {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
        color: #ffa4bd;
        font-family: 'Roboto Condensed', sans-serif;
        font-size: 16px;
        font-weight: normal;
    }

    .credits-link {
        display: flex;
        align-items: center;
        color: #fff;
        font-weight: bold;
        text-decoration: none;
    }

    .dribbble {
        width: 20px;
        height: 20px;
        margin: 0 5px;
    }

    @media screen and (max-width: 520px) {
        .screen-body {
            flex-direction: column;
        }

        .screen-body-item.left {
            margin-bottom: 30px;
        }

        .app-title {
            flex-direction: row;
        }

        .app-title span {
            margin-right: 12px;
        }

        .app-title:after {
            display: none;
        }
    }

    @media screen and (max-width: 600px) {
        .screen-body {
            padding: 40px;
        }

        .screen-body-item {
            padding: 0;
        }
    }
</style>
<!-- body -->

<body>
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"
                                    aria-hidden="true">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#masterCatalog">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"
                                    aria-hidden="true">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <polyline points="13 2 13 9 20 9"></polyline>
                                </svg>
                                Master Catalog
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#locationInventory">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"
                                    aria-hidden="true">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                Location Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#purchasing">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"
                                    aria-hidden="true">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                Purchasing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#picking">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-bar-chart-2" aria-hidden="true">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                                Picking
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#orderPicking">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8m-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5" />
                                </svg>
                                Order Picking
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#shipping">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
                                    <path
                                        d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                                </svg>
                                Shipping
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#createBarcode">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-upc" viewBox="0 0 16 16">
                                    <path
                                        d="M3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0z" />
                                </svg>
                                Create Barcode
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div class="header-one__logo">
                        <a href={{config('app.url');}}><img src="{{ asset('logo.jpg') }}" alt=""
                                class="img-fluid" style="height: 35px; width: auto" /></a>
                    </div>
                    <div>

                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0"></div>
                </div>
                <div class="content container">
                    <div id="dashboard" class="section mt-3">
                        <h3>Dashboard</h3>
                        <div class="accordion mt-3" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true"
                                        aria-controls="collapseOne">
                                        What is the Inventory Summary?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        The Inventory Summary provides an overview of the current
                                        status of inventory and includes several key metrics:
                                        <ul>
                                            <li>
                                                <strong>Stock On Hand:</strong> This is the total
                                                number of items currently available in the inventory.
                                                It helps in understanding the quantity of products
                                                that are ready to be sold or used.
                                            </li>
                                            <li>
                                                <strong>Value On Hand:</strong> This metric represents
                                                the total monetary value of the stock currently held
                                                in the inventory. It is calculated by summing the cost
                                                of all items in stock and is crucial for financial
                                                analysis and inventory valuation.
                                            </li>
                                            <li>
                                                <strong>Stock to be Received:</strong> This indicates
                                                the quantity of products that have been ordered from
                                                suppliers but have not yet been delivered. Monitoring
                                                this helps in anticipating incoming inventory and
                                                planning for future sales or production needs.
                                            </li>
                                            <li>
                                                <strong>Value to be Received:</strong> Similar to
                                                stock to be received, this is the total cost of the
                                                products that are expected to arrive in the future. It
                                                is important for budgeting and financial forecasting.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        What Does Product Details Consist Of?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse"
                                    aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        The Product Details section provides detailed information
                                        about the products in your inventory, including:
                                        <ul>
                                            <li>
                                                <strong>Low Stock Count:</strong> This metric shows
                                                the number of products that have fallen below a
                                                pre-defined alert quantity. It helps in identifying
                                                products that need to be reordered to avoid stockouts.
                                            </li>
                                            <li>
                                                <strong>Product Availability:</strong> This indicates
                                                the total number of products currently available in
                                                the inventory. It helps in assessing the inventory's
                                                readiness to meet customer demands.
                                            </li>
                                            <li>
                                                <strong>Total Manufacturers:</strong> This represents
                                                the total number of manufacturers or suppliers that
                                                are registered in the inventory system. It provides
                                                insight into the diversity of the supply chain.
                                            </li>
                                            <li>
                                                <strong>Total Products:</strong> This is the total
                                                number of different products registered in the
                                                inventory. It gives a sense of the variety of items
                                                managed within the inventory.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                        aria-expanded="false" aria-controls="collapseThree">
                                        What Are Purchase Order Stats?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        The Purchase Order Stats section provides insights into
                                        the status of various purchase orders, including:
                                        <ul>
                                            <li>
                                                <strong>Pending Orders:</strong> These are the orders
                                                that have been placed but are not yet processed or
                                                approved. Tracking pending orders helps in managing
                                                supplier follow-ups and ensuring timely processing.
                                            </li>
                                            <li>
                                                <strong>Incomplete Orders:</strong> These orders have
                                                been initiated but are not fully completed or sent for
                                                approval. Monitoring incomplete orders ensures that no
                                                orders are left unattended or forgotten.
                                            </li>
                                            <li>
                                                <strong>Partially Completed Orders:</strong> These are
                                                orders that have been placed and partially received.
                                                It indicates that some items from the order have been
                                                delivered, while others are still pending. This helps
                                                in tracking partially fulfilled orders and ensuring
                                                complete delivery.
                                            </li>
                                            <li>
                                                <strong>Ordered:</strong> These are the orders that
                                                have been placed but no items have been received yet.
                                                Tracking ordered items helps in anticipating inventory
                                                levels and planning for incoming stock.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                        aria-expanded="false" aria-controls="collapseFour">
                                        What Are the Most Purchased Products?
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse"
                                    aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        This section highlights the most purchased products in the
                                        inventory. Users can adjust the time frame to view data
                                        over different periods such as:
                                        <ul>
                                            <li>
                                                <strong>All Time:</strong> Shows the most purchased
                                                products since the beginning of record-keeping.
                                            </li>
                                            <li>
                                                <strong>Last Week:</strong> Displays the most
                                                purchased products over the past week.
                                            </li>
                                            <li>
                                                <strong>Last Month:</strong> Highlights the most
                                                purchased products over the past month.
                                            </li>
                                            <li>
                                                <strong>Last Six Months:</strong> Shows the most
                                                purchased products over the last six months.
                                            </li>
                                            <li>
                                                <strong>Last Year:</strong> Provides data on the most
                                                purchased products over the past year.
                                            </li>
                                            <li>
                                                <strong>Previous Year:</strong> Compares the most
                                                purchased products from the previous year.
                                            </li>
                                        </ul>
                                        This feature helps in identifying trends and making
                                        informed purchasing decisions.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                        aria-expanded="false" aria-controls="collapseFive">
                                        Low on Stock Section
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse"
                                    aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        The Low on Stock section lists products that are below
                                        their alert quantity threshold. This helps in:
                                        <ul>
                                            <li>
                                                <strong>Identifying Reorder Needs:</strong> Quickly
                                                spotting items that need to be reordered to avoid
                                                stockouts.
                                            </li>
                                            <li>
                                                <strong>Prioritizing Restocking:</strong> Ensuring
                                                that critical items are replenished in a timely manner
                                                to maintain inventory levels and meet customer
                                                demands.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="masterCatalog" class="section mt-3">
                        <h3>Master Catalog</h3>
                        <div class="accordion mt-3" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSix">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseSix" aria-expanded="true"
                                        aria-controls="collapseSix">
                                        How to Add Products?
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse show"
                                    aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        To add products to the Master Catalog:
                                        <ul>
                                            <li><strong>Add Products Button:</strong> At the top left of the Master
                                                Catalog section, there is
                                                a button labeled "Add Products." Clicking this button will redirect you
                                                to a page where you can
                                                fill in all the necessary details to add a new product.</li>
                                            <li><strong>Import Products:</strong> Next to the "Add Products" button,
                                                there is another button
                                                labeled "Import Products." This option allows you to import multiple
                                                products at once using an
                                                Excel spreadsheet, making the process efficient for bulk additions.</li>
                                        </ul>
                                        Using these options ensures that adding products to your catalog is
                                        straightforward and efficient.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSeven">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseSeven"
                                        aria-expanded="false" aria-controls="collapseSeven">
                                        How to Delete Products?
                                    </button>
                                </h2>
                                <div id="collapseSeven" class="accordion-collapse collapse"
                                    aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Deleting products from the Master Catalog can be done in two ways:
                                        <ul>
                                            <li><strong>Action Button:</strong> Each product listing has an action
                                                button with a dropdown
                                                menu. Select "Delete" from the dropdown to remove the specific product.
                                            </li>
                                            <li><strong>Checkbox Selection:</strong> Each product has a checkbox next to
                                                it. You can select
                                                multiple products by checking these boxes, then use the delete option to
                                                remove all selected
                                                products at once. This method is useful for bulk deletions.</li>
                                        </ul>
                                        These methods provide flexibility, whether you need to delete a single product
                                        or multiple products
                                        simultaneously.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEight">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseEight"
                                        aria-expanded="false" aria-controls="collapseEight">
                                        How to View Product Details?
                                    </button>
                                </h2>
                                <div id="collapseEight" class="accordion-collapse collapse"
                                    aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Viewing product details can be done in the following ways:
                                        <ul>
                                            <li><strong>Click on the List:</strong> Clicking on a product in the list
                                                will trigger a popup
                                                that displays all the details of the product, including the image, name,
                                                code, and other
                                                necessary information. This allows for a quick view of product details
                                                without navigating away
                                                from the list.</li>
                                            <li><strong>View Section:</strong> Alternatively, you can go to the
                                                dedicated product section and
                                                click on the "View" button to see all the details of the product on a
                                                separate page. This option
                                                provides a more comprehensive view of the product information.</li>
                                        </ul>
                                        These options ensure that you have easy access to detailed product information,
                                        whether you need a
                                        quick glance or a detailed review.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="locationInventory" class="section mt-3">
                        <h3>Location Inventory</h3>
                        <div class="accordion mt-3" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingNine">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseNine" aria-expanded="true"
                                        aria-controls="collapseNine">
                                        What is Location Inventory?
                                    </button>
                                </h2>
                                <div id="collapseNine" class="accordion-collapse collapse show"
                                    aria-labelledby="headingNine" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Location Inventory is similar to the Master Catalog but focuses on products
                                        specific to individual
                                        warehouses. It provides additional information such as:
                                        <ul>
                                            <li><strong>On-Hand Quantity:</strong> The current number of items available
                                                in a particular
                                                warehouse. This helps in tracking stock levels and ensuring sufficient
                                                inventory to meet demand.
                                            </li>
                                            <li><strong>Alert Quantity:</strong> A pre-set threshold that triggers a
                                                restocking alert when the
                                                on-hand quantity falls below this level. It helps maintain optimal
                                                inventory levels and prevents
                                                stockouts.</li>
                                            <li><strong>Par Quantity:</strong> The desired quantity that should be
                                                maintained in the warehouse
                                                at all times. This serves as a guideline for replenishing inventory to
                                                ensure stock levels are
                                                neither too low nor excessively high.</li>
                                        </ul>
                                        This section aids in managing and tracking inventory across different warehouse
                                        locations, providing
                                        a comprehensive overview of the stock status for each specific warehouse.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTen">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false"
                                        aria-controls="collapseTen">
                                        How to Change Location?
                                    </button>
                                </h2>
                                <div id="collapseTen" class="accordion-collapse collapse"
                                    aria-labelledby="headingTen" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        To change the location and view the inventory for a different warehouse:
                                        <ul>
                                            <li><strong>Location Dropdown:</strong> At the top of the Location Inventory
                                                section, there is a
                                                dropdown menu labeled "Choose Location." Use this dropdown to select the
                                                desired warehouse
                                                location.</li>
                                            <li><strong>Automatic Table Update:</strong> Once a location is selected,
                                                the inventory table will
                                                automatically update to show the details for the chosen warehouse,
                                                including on-hand quantity,
                                                alert quantity, and par quantity.</li>
                                            <li><strong>All Locations Option:</strong> By selecting the "All Locations"
                                                option from the
                                                dropdown, the table will display the combined inventory details for all
                                                warehouses. This
                                                includes the total number of products available across all locations,
                                                providing a holistic view
                                                of the overall inventory.</li>
                                        </ul>
                                        This feature allows users to easily switch between different warehouse locations
                                        and view the
                                        corresponding inventory data, facilitating efficient inventory management.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="purchasing" class="section mt-3">
                        <h3>Purchasing</h3>
                        <div class="accordion mt-3" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEleven">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseEleven" aria-expanded="true"
                                        aria-controls="collapseEleven">
                                        What is Purchasing?
                                    </button>
                                </h2>
                                <div id="collapseEleven" class="accordion-collapse collapse show"
                                    aria-labelledby="headingEleven" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        The Purchasing section allows users to view all purchase orders created within
                                        the system. Each
                                        purchase order can be in one of five statuses:
                                        <ul>
                                            <li><strong>Incomplete:</strong> The order is not yet complete, or the user
                                                wants to edit it
                                                before sending it for approval.</li>
                                            <li><strong>Ordered:</strong> The order has been reviewed and approved by
                                                the admin, indicating
                                                that it has been placed.</li>
                                            <li><strong>Partial:</strong> The order has been partially received, meaning
                                                some of the items
                                                have arrived, but the order is not yet fully completed.</li>
                                            <li><strong>Pending:</strong> The order has been submitted but is awaiting
                                                approval from the
                                                admin.</li>
                                            <li><strong>Completed:</strong> The order has been fully received and
                                                processed.</li>
                                        </ul>
                                        This section helps manage and track the status of purchase orders efficiently.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwelve">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTwelve"
                                        aria-expanded="false" aria-controls="collapseTwelve">
                                        What happens to Completed orders?
                                    </button>
                                </h2>
                                <div id="collapseTwelve" class="accordion-collapse collapse"
                                    aria-labelledby="headingTwelve" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Completed orders are removed from the active list in the Purchasing section.
                                        However, they can still
                                        be accessed and reviewed in the Reports section. This helps keep the Purchasing
                                        section focused on
                                        orders that are still in process, while allowing completed orders to be archived
                                        and referenced as
                                        needed.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThirteen">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseThirteen"
                                        aria-expanded="false" aria-controls="collapseThirteen">
                                        How to receive orders?
                                    </button>
                                </h2>
                                <div id="collapseThirteen" class="accordion-collapse collapse"
                                    aria-labelledby="headingThirteen" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        To receive orders:
                                        <ul>
                                            <li>At the end of each purchase order in the list, there is an action
                                                button.</li>
                                            <li>Clicking this action button will display a dropdown menu with various
                                                options.</li>
                                            <li>Select the "Receive" option from the dropdown menu.</li>
                                            <li>A popup window will appear, allowing the user to enter the quantity of
                                                items being received.
                                            </li>
                                            <li>Once the receiving quantities are entered, the order status will be
                                                updated accordingly.</li>
                                        </ul>
                                        This process ensures that received items are accurately recorded and the
                                        inventory is updated in
                                        real-time.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="picking" class="section mt-3">
                        <h3>Picking</h3>
                        <div class="accordion mt-3" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFourteen">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFourteen" aria-expanded="true"
                                        aria-controls="collapseFourteen">
                                        What is Picking?
                                    </button>
                                </h2>
                                <div id="collapseFourteen" class="accordion-collapse collapse show"
                                    aria-labelledby="headingFourteen" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        The Picking section allows users to pick products from their existing warehouses
                                        efficiently. The
                                        process involves several steps to ensure accurate and effective picking:
                                        <ul>
                                            <li><strong>Select Location:</strong> Users must first select the location
                                                (warehouse) from which
                                                they want to pick products. This is crucial for ensuring that the
                                                correct inventory is accessed.
                                            </li>
                                            <li><strong>Search:</strong> Users can search for the products they need to
                                                pick. This search
                                                function helps quickly locate the desired items within the warehouse
                                                inventory.</li>
                                            <li><strong>Select Multiple Products:</strong> Users can select multiple
                                                products for picking.
                                                They can do this by searching for and selecting each product, making it
                                                convenient to pick
                                                several items in one session.</li>
                                            <li><strong>Adjust Quantities:</strong> After selecting the products, users
                                                can adjust the
                                                respective quantities they need to pick. This ensures that the correct
                                                amount of each product is
                                                picked according to the requirement.</li>
                                            <li><strong>Submit:</strong> Once all products are selected and their
                                                quantities are adjusted,
                                                users must click on the submit button. This finalizes the picking list
                                                and updates the system
                                                accordingly.</li>
                                        </ul>
                                        This section is designed to help manage and track the status of picking orders
                                        efficiently, ensuring
                                        that inventory is accurately updated and products are correctly allocated for
                                        use or shipment.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="orderPicking" class="section mt-3">
                        <h3>Order Picking</h3>
                        <div class="accordion mt-3" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOrderPicking">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#WhatorderPicking" aria-expanded="true"
                                        aria-controls="WhatorderPicking">
                                        What is Order Picking?
                                    </button>
                                </h2>
                                <div id="WhatorderPicking" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOrderPicking" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        The Order Picking section displays all the picking orders created by users.
                                        Here, users can also
                                        create a new picking order for customers to ship products. By clicking on the
                                        "Add Picking" button,
                                        users can initiate the creation of a new picking order.
                                        <br><br>
                                        This section is designed to help manage and track the status of picking orders
                                        efficiently. It
                                        ensures that inventory is accurately updated and products are correctly
                                        allocated for use or
                                        shipment. Users can monitor the progress of picking orders and make necessary
                                        adjustments as needed.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingDownloadInvoice">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#orderPickingInvoice" aria-expanded="true"
                                        aria-controls="orderPickingInvoice">
                                        What is in the Action Button?
                                    </button>
                                </h2>
                                <div id="orderPickingInvoice" class="accordion-collapse collapse show"
                                    aria-labelledby="headingDownloadInvoice" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        In the table, at the end of each list, there is an action button. Upon clicking
                                        it, a dropdown menu
                                        will appear, showing various options to perform different functions:
                                        <ul>
                                            <li><strong>Download Invoice:</strong> This option allows users to download
                                                the invoice for the
                                                picking order.</li>
                                            <li><strong>View Details:</strong> Users can view detailed information about
                                                the picking order.
                                            </li>
                                            <li><strong>Edit Order:</strong> This option enables users to edit the
                                                details of the picking
                                                order if any changes are needed.</li>
                                            <li><strong>View Shipping Details:</strong> Users can view the shipping
                                                details related to the
                                                picking order.</li>
                                            <li><strong>Delete Picking:</strong> This option allows users to delete the
                                                picking order from the
                                                list.</li>
                                        </ul>
                                        These functions provide flexibility and control over managing picking orders,
                                        making the process
                                        more efficient and user-friendly.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="shipping" class="section mt-3">
                        <h3>Shipping</h3>
                        <div class="accordion mt-3" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="ShippingDetails">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#What_is_shipping" aria-expanded="true"
                                        aria-controls="What_is_shipping">
                                        What Does Shipping Consist Of?
                                    </button>
                                </h2>
                                <div id="What_is_shipping" class="accordion-collapse collapse show"
                                    aria-labelledby="ShippingDetails" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        The Shipping section allows users to manage and track all shipping activities
                                        within the system.
                                        Each shipment can be in one of several statuses:
                                        <ul>
                                            <li><strong>Pending:</strong> The shipment has been created but not yet
                                                processed. It may be
                                                awaiting further actions or approvals.</li>
                                            <li><strong>In Transit:</strong> The shipment has been dispatched and is
                                                currently on its way to
                                                the destination.</li>
                                            <li><strong>Delivered:</strong> The shipment has reached its destination and
                                                has been confirmed as
                                                received.</li>
                                            <li><strong>Returned:</strong> The shipment was returned to the sender due
                                                to issues such as
                                                delivery failure or customer returns.</li>
                                            <li><strong>Cancelled:</strong> The shipment was cancelled and will not be
                                                processed further.</li>
                                        </ul>
                                        This section provides a comprehensive overview of the shipping process, ensuring
                                        that users can
                                        monitor the progress of each shipment and handle any issues that may arise.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwelve">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTwelve"
                                        aria-expanded="false" aria-controls="collapseTwelve">
                                        What Happens to Completed Shipments?
                                    </button>
                                </h2>
                                <div id="collapseTwelve" class="accordion-collapse collapse"
                                    aria-labelledby="headingTwelve" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Completed shipments are moved from the active list in the Shipping section.
                                        However, they remain
                                        accessible in the Reports section for review and record-keeping. This helps keep
                                        the Shipping
                                        section focused on shipments that are still in process, while allowing users to
                                        reference completed
                                        shipments as needed. This archive of completed shipments provides valuable
                                        insights and historical
                                        data for reporting and analysis purposes.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThirteen">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseThirteen"
                                        aria-expanded="false" aria-controls="collapseThirteen">
                                        How to Create a New Shipment?
                                    </button>
                                </h2>
                                <div id="collapseThirteen" class="accordion-collapse collapse"
                                    aria-labelledby="headingThirteen" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        To create a new shipment:

                                        This process ensures that all shipments are accurately recorded and processed,
                                        providing a
                                        streamlined and efficient shipping workflow.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="createBarcode" class="section mt-3">
                        <h3>Create Barcode</h3>
                        <div class="accordion mt-3" id="accordionBarcode">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingBarcodeImportance">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseBarcodeImportance" aria-expanded="true"
                                        aria-controls="collapseBarcodeImportance">
                                        Importance of Barcodes
                                    </button>
                                </h2>
                                <div id="collapseBarcodeImportance" class="accordion-collapse collapse show"
                                    aria-labelledby="headingBarcodeImportance" data-bs-parent="#accordionBarcode">
                                    <div class="accordion-body">
                                        Barcodes are essential for efficient inventory management and product tracking.
                                        They offer numerous
                                        benefits:
                                        <ul>
                                            <li><strong>Speed and Accuracy:</strong> Scanning barcodes significantly
                                                reduces the time required
                                                for data entry and minimizes human errors.</li>
                                            <li><strong>Improved Inventory Management:</strong> Barcodes enable precise
                                                tracking of products,
                                                ensuring that inventory levels are always accurate.</li>
                                            <li><strong>Cost-Effective:</strong> Implementing barcode systems reduces
                                                labor costs associated
                                                with manual entry and inventory tracking.</li>
                                            <li><strong>Enhanced Customer Experience:</strong> Faster checkouts and
                                                accurate inventory ensure
                                                that customers receive their products on time.</li>
                                            <li><strong>Real-Time Data:</strong> Barcodes provide real-time updates on
                                                inventory status,
                                                helping in making informed business decisions.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingGenerateBarcode">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseGenerateBarcode"
                                        aria-expanded="false" aria-controls="collapseGenerateBarcode">
                                        How to Generate a Barcode?
                                    </button>
                                </h2>
                                <div id="collapseGenerateBarcode" class="accordion-collapse collapse"
                                    aria-labelledby="headingGenerateBarcode" data-bs-parent="#accordionBarcode">
                                    <div class="accordion-body">
                                        To generate a barcode for your products, follow these steps:
                                        <ul>
                                            <li><strong>Search for the Product:</strong> Use the search bar to find the
                                                product for which you
                                                want to create a barcode. Enter the product name or SKU to quickly
                                                locate the item.</li>
                                            <li><strong>Select the Product:</strong> Once you have found the product,
                                                select it from the list.
                                                Ensure that you have selected the correct item to avoid any
                                                discrepancies.</li>
                                            <li><strong>Generate Barcode:</strong> Click on the "Generate Barcode"
                                                button. The system will
                                                create a unique barcode for the selected product.</li>
                                            <li><strong>Review and Print:</strong> Review the generated barcode to
                                                ensure accuracy. Once
                                                confirmed, you can print the barcode label to attach it to the product.
                                            </li>
                                        </ul>
                                        This process ensures that each product is accurately labeled with a unique
                                        barcode, facilitating
                                        easy tracking and management within your inventory system.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

        </div>
        </main>
        <form action="{{ route('contact.submit') }}" method="POST">
            @csrf
            <div class="mt-4 col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="background">
                    <div class="container">
                        <div class="screen">
                            <div class="screen-header">
                                <div class="screen-header-left">
                                    <div class="screen-header-button close"></div>
                                    <div class="screen-header-button maximize"></div>
                                    <div class="screen-header-button minimize"></div>
                                </div>
                                <div class="screen-header-right">
                                    <div class="screen-header-ellipsis"></div>
                                    <div class="screen-header-ellipsis"></div>
                                    <div class="screen-header-ellipsis"></div>
                                </div>
                            </div>
                            <div class="screen-body">
                                <div class="screen-body-item left">
                                    <div class="app-title">
                                        <span>CONTACT</span>
                                        <span>US</span>
                                    </div>
                                    <div class="app-contact">CONTACT INFO : +1 615-437-4300</div>
                                </div>
                                <div class="screen-body-item">
                                    <div class="app-form">
                                        <div class="app-form-group">
                                            <input type="text" class="app-form-control" placeholder="NAME"
                                                name="name">
                                        </div>
                                        <div class="app-form-group">
                                            <input type="email" class="app-form-control" placeholder="EMAIL"
                                                name="email">
                                        </div>
                                        <div class="app-form-group">
                                            <input type="text" class="app-form-control" placeholder="CONTACT NO"
                                                name="contact">
                                        </div>
                                        <div class="app-form-group message">
                                            <input type="text" class="app-form-control" placeholder="MESSAGE"
                                                name="message">
                                        </div>
                                        <div class="app-form-group buttons">
                                            <button class="app-form-button">CANCEL</button>
                                            <button type="submit" class="app-form-button">SEND</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
    </div>

    <!-- Javascript files-->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.0.0.min.js"></script>
    <!-- sidebar -->
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
        integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
        integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
    </script>
    <script src="dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
        window.addEventListener("DOMContentLoaded", (event) => {
            const sections = document.querySelectorAll(".section");
            const sidebarLinks = document.querySelectorAll(".sidebar a");

            // Function to highlight the sidebar link of the current section
            const highlightSidebarLink = () => {
                sections.forEach((section) => {
                    const sectionTop = section.offsetTop - 100; // Adjust for better precision
                    const sectionId = section.getAttribute("id");
                    const sidebarLink = document.querySelector(
                        `.sidebar a[href="#${sectionId}"]`
                    );

                    if (window.scrollY >= sectionTop) {
                        sidebarLinks.forEach((link) => link.classList.remove("active"));
                        sidebarLink.classList.add("active");
                    }
                });
            };

            // Listen for scroll events and highlight the appropriate sidebar link
            window.addEventListener("scroll", highlightSidebarLink);

            // Smooth scrolling when clicking on sidebar links
            sidebarLinks.forEach((link) => {
                link.addEventListener("click", (event) => {
                    event.preventDefault();
                    const targetId = link.getAttribute("href");
                    const targetSection = document.querySelector(targetId);
                    const targetTop = targetSection.offsetTop;

                    window.scrollTo({
                        top: targetTop,
                        behavior: "smooth",
                    });
                });
            });
        });
    </script>
</body>

</html>
