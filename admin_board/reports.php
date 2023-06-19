<?php
include '../connect.php';
$serch_placeholder = " ابحث برقم الطلب";
$serch_page = 'orders.php';
include 'admin_header.php';

// get revenues

$order_price = 0;
$return_price = 0;
$this_month =  date('Y-m-01');
$stmt = $con->prepare("SELECT price FROM orders WHERE order_date >= ?");
$stmt->execute(array($this_month));
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $order_price = $order_price + $row['price'];
}

$stmt = $con->prepare("SELECT price FROM returns_order WHERE order_date >= ?");
$stmt->execute(array($this_month));
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $return_price = $return_price + $row['price'];
}
$total_price = $order_price;

// get orders for this month

$stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders WHERE order_date >= ?");
$stmt->execute(array($this_month));
$rows = $stmt->fetchAll();

$orders_count = count($rows);

// get count returns for this month 

$stmt = $con->prepare("SELECT order_number FROM returns_order WHERE order_date >= ?");
$stmt->execute(array($this_month));
$rows = $stmt->fetchAll();

$returns_count = count($rows);

// get clients who ordered this month 


$stmt = $con->prepare("SELECT DISTINCT user_key FROM orders WHERE order_date >= ?");
$stmt->execute(array($this_month));
$rows = $stmt->fetchAll();

$clients_count = count($rows);




?>

<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>لوحة التحكم
                            <small>Multikart Admin panel</small>
                        </h3>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ol class="breadcrumb pull-left">
                        <li class="breadcrumb-item">
                            <a href="index.php">
                                <i data-feather="home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active"> لوحة التحكم </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-3 col-md-6 xl-50">
                <div class="card o-hidden widget-cards">
                    <div class="warning-box card-body">
                        <div class="media static-top-widget align-items-center">
                            <div class="icons-widgets">
                                <div class="align-self-center text-center">
                                    <i data-feather="navigation" class="font-warning"></i>
                                </div>
                            </div>

                            <div class="media-body media-doller">
                                <span class="m-0"> الإيرادات </span>
                                <h3 class="mb-0">جنيه <span class="counter"><?Php echo $total_price; ?> </span><small> هذا الشهر
                                    </small>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6 xl-50">
                <div class="card o-hidden widget-cards">
                    <div class="secondary-box card-body">
                        <div class="media static-top-widget align-items-center">
                            <div class="icons-widgets">
                                <div class="align-self-center text-center">
                                    <i data-feather="archive"></i>
                                </div>
                            </div>
                            <div class="media-body media-doller">
                                <span class="m-0"> الطلبات </span>
                                <h3 class="mb-0">طلب <span class="counter"><?php echo $orders_count ?> </span><small> هذا الشهر
                                    </small>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6 xl-50">
                <div class="card o-hidden widget-cards">
                    <div class="primary-box card-body">
                        <div class="media static-top-widget align-items-center">
                            <div class="icons-widgets">
                                <div class="align-self-center text-center"> <i data-feather="clipboard"></i>
                                </div>
                            </div>
                            <div class="media-body media-doller"><span class="m-0">ارجاع المنتجات</span>
                                <h3 class="mb-0">مرة <span class="counter"><?php echo $returns_count; ?></span><small> هذا الشهر
                                    </small></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6 xl-50">
                <div class="card o-hidden widget-cards">
                    <div class="danger-box card-body">
                        <div class="media static-top-widget align-items-center">
                            <div class="icons-widgets">
                                <div class="align-self-center text-center"><i data-feather="users" class="font-danger"></i></div>
                            </div>
                            <div class="media-body media-doller"><span class="m-0">عملاء قاموا بطلب</span>
                                <h3 class="mb-0"> <span class="counter"><?Php echo $clients_count; ?></span> عميل <small> هذا الشهر
                                    </small></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">


                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5> Sales / Purchase</h5>
                        </div>
                        <div class="card-body">
                            <div class="sales-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5> Sales / Purchase Return</h5>
                        </div>
                        <div class="card-body sell-graph">
                            <canvas id="myLineCharts"></canvas>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <?php
    include 'admin_footer.php';
    ?>

    <!--Report chart-->
    <script src="assets/js/admin-reports.js"></script>

    </body>

    </html>