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
            <div class="col-xl-6 xl-100">
                <div class="card">
                    <div class="card-header">
                        <h5>آخر الطلبات</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="icofont icofont-simple-left"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li><i class="icofont icofont-error close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="user-status table-responsive latest-order-table">
                            <table class="table table-bordernone">
                                <thead>

                                    <tr>
                                        <th scope="col">الإجمالي</th>
                                        <th scope="col">النظام</th>
                                        <th scope="col">الحالة</th>
                                        <th scope="col">تفاصيل</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders ORDER BY id DESC LIMIT 7");
                                    $stmt->execute();
                                    $orders = $stmt->fetchAll();

                                    foreach ($orders as $order) {
                                        echo '<tr>';
                                        $order_price = 0;
                                        $coins = 0;

                                        $stmt = $con->prepare("SELECT price, status, user_key, coins FROM orders WHERE total_order_number = ?");
                                        $stmt->execute(array($order['total_order_number']));
                                        $rows = $stmt->fetchAll();

                                        foreach ($rows as $row) {
                                            $order_price = $order_price + $row['price'];
                                            $coins = $coins + $row['coins'];
                                            $user_key = $row['user_key'];
                                            $status = $row['status'];
                                        }

                                        echo '<td>' . ($order_price - $coins) . ' جنيه </td>';
                                        $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                                        $stmt->execute(array($user_key));
                                        $rows = $stmt->fetchAll();

                                        foreach ($rows as $row) {
                                            $trade = $row['trade'];
                                        }
                                        if ($trade == 0) {
                                            echo '<td class="font-secondary">قطاعي</td>';
                                        } else {
                                            echo '<td class="font-primary">جملة</td>';
                                        }
                                        if ($status == 0) {
                                            echo '<td class="font-warning">لم يتم التأكيد</td>';
                                        } elseif ($status == 1) {
                                            echo '<td class="font-secondary">تم التأكيد</td>';
                                        } elseif ($status == 2) {
                                            echo '<td class="font-primary">تم التجهيز</td>';
                                        } elseif ($status == 3) {
                                            echo '<td>تم الشحن</td>';
                                        } elseif ($status == 4) {
                                            echo '<td class="font-danger">تم التوصيل</td>';
                                        }
                                        echo '
                                        <td><a style="color: #34568B;" target="_blank" href="order-detail.php?order_number=' . $order['total_order_number'] . '">تفاصيل</a></td>
                                        </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <a href="orders.php" class="btn btn-primary mt-4">عرض جميع الطلبات</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 xl-100">
                <div class="card">
                    <div class="card-header">
                        <h5>اخر المرتجعات</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="icofont icofont-simple-left"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li><i class="icofont icofont-error close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="user-status table-responsive latest-order-table">
                            <table class="table table-bordernone">
                                <thead>
                                    <tr>
                                        <th scope="col">المنتج</th>
                                        <th scope="col">الإجمالي</th>
                                        <th scope="col">عدد القطع</th>
                                        <th scope="col">النظام</th>
                                        <th scope="col">تاريخ الارجاع</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $con->prepare("SELECT price, quantity, user_key, return_date, coins, product_id FROM returns_order ORDER BY id DESC LIMIT 4");
                                    $stmt->execute();
                                    $rows = $stmt->fetchAll();

                                    foreach ($rows as $row) {
                                        echo '<tr>';

                                        $stmt = $con->prepare("SELECT images FROM products WHERE id = ?");
                                        $stmt->execute(array($row['product_id']));
                                        $images_list = $stmt->fetchAll();


                                        foreach ($images_list as $images) {
                                            $image = explode(',', $images['images']);

                                            echo '<td><img src="../assets/img/products/' . $image[1] . '" alt="" width="50px" height="55px" /></td>';
                                        }

                                        echo '
                                        <td>' . ($row['price'] - $row['coins']) . '</td>
                                        <td>' . $row['quantity'] . '</td>
                                        ';
                                        $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                                        $stmt->execute(array($row['user_key']));
                                        $trades = $stmt->fetchAll();

                                        foreach ($trades as $trade) {
                                            $trade = $trade['trade'];
                                        }
                                        if ($trade == 0) {
                                            echo '<td class="font-secondary">قطاعي</td>';
                                        } else {
                                            echo '<td class="font-primary">جملة</td>';
                                        }
                                        echo '
                                        <td>' . $row['return_date'] . '</td>
                                        </tr>
                                        ';
                                    }
                                    ?>

                                </tbody>
                            </table>
                            <a href="returns.php" class="btn btn-primary mt-4">عرض جميع المرتجعات</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row products-admin ratio_asos">
                    <?php


                    $stmt = $con->prepare("SELECT * FROM products ORDER BY id DESC LIMIT 8 ");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();

                    // the loop 
                    foreach ($rows as $row) {

                        $images = explode(',', $row['images']);
                        echo '
<div class="col-xl-3 col-sm-6">
<div class="card">
<div class="card-body product-box">
    <div class="img-wrapper">
        <div class="front">
            <a href="javascript:void(0)"><img src="../assets/img/products/' . $images[1] . '" class="img-fluid blur-up lazyload " alt=""></a>
            <div class="product-hover">
                <ul>
                <li>
                <a href="product-details.php?id=' . $row['id'] . '" target=_blank><button class="btn" type="button" data-original-title="" title=""><i class="ti-more-alt"></i></button></a>
                </li>
                <li>
                <button class="btn delete-product" data-product_id=' . $row['id'] . '><i class="ti-trash"></i></button>
                </li>
                </ul>
            </div>
            <div class="product-detail">
            <a href="javascript:void(0)">
                <h6>' . $row['title'] . '</h6>
            </a>
                    ';
                        if ($row['discount'] == 0) {
                            echo '
                        <h4>' . $row['price'] . ' جنيه</h4>
                        ';
                        } else {
                            echo '
                        <h4>' . $row['discount'] . ' جنيه <del>' . $row['price'] . ' </del></h4>
                        ';
                        }
                        echo '
</div>
</div>
</div>
</div>
</div>
</div>                                    
';
                    }
                    echo '
</div>

';

                    ?>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <?php
    include 'admin_footer.php';
    ?>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        // delete product 

        $('.delete-product').click(function() {
            swal({
                    title: "هل تريد حذف المنتج ؟",
                    text: " اذا قمت بالضغط على OK سيتم الحذف",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $(this).parentsUntil('.products-admin').hide();
                        swal("تم حذف المنتج", {
                            icon: "success",
                        });
                        $.post('product-list.php', {
                            delete_product: true,
                            product_id: $(this).data('product_id'),
                        });
                    }
                });
        })
    </script>

    </body>

    </html>