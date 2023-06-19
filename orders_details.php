<?php

// check login

include '../connect.php';

if (isset($_COOKIE['admin_key'])) {

    $admin_key = filter_var($_COOKIE['admin_key'], FILTER_SANITIZE_STRING);

    // check if account is admin  

    $stmt = $con->prepare("SELECT admin_key FROM admin WHERE admin_key = ? AND manager = 0");
    $stmt->execute(array($admin_key));
    $count = $stmt->rowCount();

    if (!($count > 0)) {
        // check if account is manager 

        $stmt = $con->prepare("SELECT admin_key FROM admin WHERE admin_key = ? AND manager = 1");
        $stmt->execute(array($admin_key));
        $count = $stmt->rowCount();
        if (!($count > 0)) {
            header('location: index.php');
            exit();
        }
    }
} else {
    header('location: index.php');
    exit();
}

// end check login


// delete order 

if (isset($_POST['delete_order'])) {
    $order_id = filter_var($_POST['order_id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete if exist

    $stmt = $con->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute(array($order_id));
    $check_orders = $stmt->rowCount();

    if ($check_orders > 0) {

        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {

            // remove coins from account 

            $product_coins =  round(($row['price'] * 10) / 100);

            $stmt = $con->prepare("SELECT coins, total_buy FROM users WHERE user_key = ?");
            $stmt->execute(array($row['user_key']));
            $users = $stmt->fetchAll();

            foreach ($users as $user) {
                $old_coins = $user['coins'];
                $old_total_buy = $user['total_buy'];
            }

            $new_coins = $old_coins - $product_coins;
            $new_total_buy = $old_total_buy - $row['price'];

            // if client has paied coins

            if ($row['coins'] > 0) {
                $new_coins = $new_coins + $row['coins'];
            }

            $stmt = $con->prepare('UPDATE users SET coins = :coins, total_buy = :total_buy WHERE user_key = :key');

            $stmt->execute(array(
                'coins' => $new_coins,
                'total_buy' => $new_total_buy,
                'key' => $row['user_key']
            ));

            // update sells product 

            // get old sells 

            $stmt = $con->prepare("SELECT sells FROM products WHERE id  = ?");
            $stmt->execute(array($row['product_id']));
            $products_sell = $stmt->fetchAll();

            foreach ($products_sell as $product_sell) {
                $old_product_sell = $product_sell['sells'];
            }

            $new_product_sell = $old_product_sell - intval($row['quantity']);

            // set new product sell

            $stmt = $con->prepare('UPDATE products SET sells = :sells WHERE id = :id');

            $stmt->execute(array(
                'sells' => $new_product_sell,
                'id' => $row['product_id']
            ));

            // get old quantity 

            $stmt = $con->prepare("SELECT quantity FROM products WHERE id = ?");
            $stmt->execute(array($row['product_id']));
            $old_quantitys = $stmt->fetchAll();

            foreach ($old_quantitys as $old_quantity) {
                $new_quantity = intval($old_quantity['quantity']) + intval($row['quantity']);
            }
            // update product quantity

            $stmt = $con->prepare('UPDATE products SET quantity = :quantity WHERE id = :id');

            $stmt->execute(array(
                'quantity' => $new_quantity,
                'id' => $row['product_id'],
            ));
        }

        $stmt = $con->prepare("DELETE FROM `orders` WHERE `orders`.`id` = :id");
        $stmt->bindParam(":id", $order_id);
        $stmt->execute();

        exit();
    }
}

// deliver order 

if (isset($_POST['deliver_order'])) {
    $order_id = filter_var($_POST['order_id'], FILTER_SANITIZE_NUMBER_INT);

    $stmt = $con->prepare("SELECT id FROM orders WHERE id = ?");
    $stmt->execute(array($order_id));
    $check_orders = $stmt->rowCount();

    if ($check_orders > 0) {
        $today = date('Y-m-d');
        $stmt = $con->prepare('UPDATE orders SET done = :done, shipping_date = :new_date WHERE id = :id');

        $stmt->execute(array(
            'done' => 1,
            'new_date' => $today,
            'id' => $order_id
        ));
    }
}

// deliver day orders

if (isset($_POST['deliver_orders_day'])) {
    $orders_day = filter_var($_POST['orders_day'], FILTER_SANITIZE_NUMBER_INT);
    $orders_user_key =  $_GET['key'];


    // Delete if exist

    $stmt = $con->prepare("SELECT shipping_date FROM orders WHERE shipping_date = ?");
    $stmt->execute(array($orders_day));
    $check_orders_date = $stmt->rowCount();

    if ($check_orders_date > 0) {

        $today = date('Y-m-d');

        $stmt = $con->prepare('UPDATE orders SET done = :done, shipping_date = :new_date WHERE shipping_date = :day AND user_key = :key ');

        $stmt->execute(array(
            'done' => 1,
            'new_date' => $today,
            'day' => $orders_day,
            'key' => $orders_user_key
        ));
        echo 'done';
        exit();
    }
}

// return order 

if (isset($_POST['return_order'])) {
    $order_id = filter_var($_POST['order_id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete if exist

    $stmt = $con->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute(array($order_id));
    $check_orders = $stmt->rowCount();

    if ($check_orders > 0) {

        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {

            // remove coins from account 
            $product_coins =  round(($row['price'] * 10) / 100);

            $stmt = $con->prepare("SELECT coins, total_buy FROM users WHERE user_key = ?");
            $stmt->execute(array($row['user_key']));
            $users = $stmt->fetchAll();

            foreach ($users as $user) {
                $old_coins = $user['coins'];
                $old_total_buy = $user['total_buy'];
            }

            $new_coins = $old_coins - $product_coins;
            $new_total_buy = $old_total_buy - intval($row['price']);

            // if client has paied coins

            if ($row['coins'] > 0) {
                $new_coins = $new_coins + $row['coins'];
            }

            $stmt = $con->prepare('UPDATE users SET coins = :coins, total_buy = :total_buy WHERE user_key = :key');

            $stmt->execute(array(
                'coins' => $new_coins,
                'total_buy' => $new_total_buy,
                'key' => $row['user_key']
            ));

            // update product sells

            // get old sells 

            $stmt = $con->prepare("SELECT sells FROM products WHERE id  = ?");
            $stmt->execute(array($row['product_id']));
            $products_sell = $stmt->fetchAll();

            foreach ($products_sell as $product_sell) {
                $old_product_sell = $product_sell['sells'];
            }

            $new_product_sell = $old_product_sell - intval($row['quantity']);

            // set new product sell

            $stmt = $con->prepare('UPDATE products SET sells = :sells WHERE id = :id');

            $stmt->execute(array(
                'sells' => $new_product_sell,
                'id' => $row['product_id']
            ));

            // add to return database 
            $today = date('Y-m-d');
            $stmt = $con->prepare('INSERT INTO returns_order (product_id, quantity, size, price, order_date, shipping_date, return_date, user_key, coins, promocode, order_number) 
            VALUES (:prod_id, :quantity, :size, :price, :order_date, :shipping_date, :return_date, :key, :coins, :promocode, :order_number)');
            $stmt->execute(array(
                'prod_id' => $row['product_id'],
                'quantity' => $row['quantity'],
                'size' => $row['size'],
                'price' => $row['price'],
                'order_date' => $row['order_date'],
                'shipping_date' => $row['shipping_date'],
                'return_date' => $today,
                'key' => $row['user_key'],
                'coins' => $row['coins'],
                'promocode' => $row['promocode'],
                'order_number' => $row['order_number']
            ));


            // get old quantity 

            $stmt = $con->prepare("SELECT quantity FROM products WHERE id = ?");
            $stmt->execute(array($row['product_id']));
            $old_quantitys = $stmt->fetchAll();

            foreach ($old_quantitys as $old_quantity) {
                $new_quantity = intval($old_quantity['quantity']) + intval($row['quantity']);
            }
            // update product quantity

            $stmt = $con->prepare('UPDATE products SET quantity = :quantity WHERE id = :id');

            $stmt->execute(array(
                'quantity' => $new_quantity,
                'id' => $row['product_id'],

            ));

            // delete from orders

            $stmt = $con->prepare("DELETE FROM `orders` WHERE `orders`.`id` = :id");
            $stmt->bindParam(":id", $order_id);
            $stmt->execute();
        }


        exit();
    }
}

// return day order 

if (isset($_POST['return_orders_day'])) {
    $orders_day = filter_var($_POST['orders_day'], FILTER_SANITIZE_NUMBER_INT);
    $orders_user_key =  $_GET['key'];


    $stmt = $con->prepare("SELECT * FROM orders WHERE shipping_date = ? AND user_key = ?");
    $stmt->execute(array($orders_day, $_GET['key']));
    $check_orders = $stmt->rowCount();

    if ($check_orders > 0) {

        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {

            // remove coins from account 

            $product_coins =  round(($row['price'] * 10) / 100);

            $stmt = $con->prepare("SELECT coins, total_buy FROM users WHERE user_key = ?");
            $stmt->execute(array($row['user_key']));
            $users = $stmt->fetchAll();

            foreach ($users as $user) {
                $old_coins = $user['coins'];
                $old_total_buy = $user['total_buy'];
            }

            $new_coins = $old_coins - $product_coins;
            $new_total_buy = $old_total_buy - $row['price'];

            // if client has paied coins

            if ($row['coins'] > 0) {
                $new_coins = $new_coins + $row['coins'];
            }

            $stmt = $con->prepare('UPDATE users SET coins = :coins, total_buy = :total_buy WHERE user_key = :key');

            $stmt->execute(array(
                'coins' => $new_coins,
                'total_buy' => $new_total_buy,
                'key' => $row['user_key']
            ));

            // update product sells

            // get old sells 

            $stmt = $con->prepare("SELECT sells FROM products WHERE id  = ?");
            $stmt->execute(array($row['product_id']));
            $products_sell = $stmt->fetchAll();

            foreach ($products_sell as $product_sell) {
                $old_product_sell = $product_sell['sells'];
            }

            $new_product_sell = $old_product_sell - intval($row['quantity']);

            // set new product sell

            $stmt = $con->prepare('UPDATE products SET sells = :sells WHERE id = :id');

            $stmt->execute(array(
                'sells' => $new_product_sell,
                'id' => $row['product_id']
            ));

            // add to return database 
            $today = date('Y-m-d');
            $stmt = $con->prepare('INSERT INTO returns_order (product_id, quantity, size, price, order_date, shipping_date, return_date, user_key, coins, promocode, order_number) 
                VALUES (:prod_id, :quantity, :size, :price, :order_date, :shipping_date, :return_date, :key, :coins, :promocode, :order_number)');
            $stmt->execute(array(
                'prod_id' => $row['product_id'],
                'quantity' => $row['quantity'],
                'size' => $row['size'],
                'price' => $row['price'],
                'order_date' => $row['order_date'],
                'shipping_date' => $row['shipping_date'],
                'return_date' => $today,
                'key' => $row['user_key'],
                'coins' => $row['coins'],
                'promocode' => $row['promocode'],
                'order_number' => $row['order_number']
            ));


            // get old quantity 

            $stmt = $con->prepare("SELECT quantity FROM products WHERE id = ?");
            $stmt->execute(array($row['product_id']));
            $old_quantitys = $stmt->fetchAll();

            foreach ($old_quantitys as $old_quantity) {
                $new_quantity = intval($old_quantity['quantity']) + intval($row['quantity']);
            }
            // update product quantity

            $stmt = $con->prepare('UPDATE products SET quantity = :quantity WHERE id = :id');

            $stmt->execute(array(
                'quantity' => $new_quantity,
                'id' => $row['product_id'],

            ));

            // delete from orders

            $stmt = $con->prepare("DELETE FROM orders WHERE shipping_date = ? AND user_key = ? AND done = 1 ");
            $stmt->execute(array(
                $orders_day,
                $orders_user_key
            ));
        }


        exit();
    }
}


if (isset($_GET['key'])) {

    $user_key = filter_var($_GET['key'], FILTER_SANITIZE_STRING);

    // check if id is right

    $stmt = $con->prepare("SELECT user_key FROM users WHERE user_key = ?");
    $stmt->execute(array($user_key));
    $count = $stmt->rowCount();
    if (!($count > 0)) {

        // if id doesn't exist 
        header('location: orders.php');
        exit();
    }
} else {
    header('location: orders.php');
    exit();
}

// update order seen 

$stmt = $con->prepare('UPDATE orders SET seen = :seen WHERE user_key = :key');

$stmt->execute(array(
    'seen' => 1,
    'key' => $user_key
));


?>
<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.79.0">
    <title>صفحة الادمن | ecommerce</title>


    <!-- Bootstrap core CSS -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/cart.css" rel="stylesheet">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        select,
        option {
            padding: 8px;
            cursor: pointer;
        }

        .arrange-span {
            display: inline-block;
            font-size: 18px;
            margin-left: 5px;
            margin-top: 5px;
            font-weight: bold;
        }

        .title {
            font-weight: normal;
            font-size: 25px;
        }

        .title::after {
            content: '';
            width: 150px;
            background-color: #454545;
            height: 1.2px;
            display: block;
            margin-top: 8px;
        }

        .order-pay-info {
            font-size: 22px;
            margin-top: 20px;
            color: #7b7b7b;
            font-weight: bold;
        }

        .orders-number {
            border: 1px solid #8f8f8f;
            width: 30px;
            height: 30px;
            display: inline-block;
            text-align: center;
            background-color: #8f8f8f;
            line-height: 30px;
        }

        .customer-data {
            width: 50%;
            position: fixed;
            top: 20%;
            left: 25%;
            z-index: 999;
            text-align: center;
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 8%);
            min-height: 250px;
            padding: 20px;
            display: none;
        }

        .customer-data p {
            font-size: 18px;
            font-weight: bold;
            color: #2f2f2f;
            margin-top: 20px;
        }

        .customer-data span {
            display: block;
            position: absolute;
            top: 0;
            right: 10px;
            font-size: 25px;
            color: #f00;
            font-weight: bold;
            padding: 20px;
            cursor: pointer;
        }

        .show {
            display: block !important;
        }
    </style>

</head>

<body>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">اسم الشركة</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="تبديل التنقل">
            <span class="navbar-toggler-icon"></span>
        </button>
        <form class="w-100" method="GET" action="users.php">
            <input class="form-control form-control-dark w-100" type="text" name="search" placeholder="ابحث باستخدام الرقم او الايميل او الاسم" aria-label="بحث">
        </form>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="#">خروج</a>
            </li>
        </ul>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="users.php">
                                <span data-feather="home"></span>
                                عرض كل العملاء
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">
                                <span data-feather="file"></span>
                                المنتجات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <span data-feather="shopping-cart"></span>
                                السلة
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="shipping.php">
                                <span data-feather="shipping"></span>
                                الشحن
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orders.php">
                                <span data-feather="bar-chart-2"></span>
                                الطلبات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="delivered.php">
                                <span data-feather="shipping"></span>
                                طلبات تم تسليمها
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="returns.php">
                                <span data-feather="shipping"></span>
                                المرتجعات
                            </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>خاص بالمديرين</span>

                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="copons.php">
                                <span data-feather="file-text"></span>
                                كوبونات الخصم
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text"></span>
                                الربع الأخير
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text"></span>
                                ارتباط اجتماعي
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text"></span>
                                بيع نهاية العام
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">تفاصيل الطلبات</h1>
                    <div class="btn btn-dark show-customer-data">عرض بيانات العميل</div>
                    <div class="btn btn-info">ارسال رسالة</div>
                </div>
                <!-- orders details -->

                <div class="container">

                    <div class="row">

                        <div class="col-12">
                            <div class="row product-containers">

                                <?php

                                // orders for today
                                $stmt = $con->prepare("SELECT * FROM orders WHERE user_key = ? AND order_date = ? AND done = 0 ORDER BY id DESC");
                                $stmt->execute(array($user_key, date("Y-m-d")));
                                $count = $stmt->rowCount();
                                if ($count > 0) {
                                    echo '
                                    <div class="col-8"> <h4 class="title"> طلبات اليوم <span class="rounded-circle orders-number"> ' . $count . '</span>  </h4> </div>
                                    ';
                                    $rows = $stmt->fetchAll();

                                    // the loop 

                                    foreach ($rows as $row) {
                                        $product_id = $row['product_id'];
                                        $quantity = $row['quantity'];
                                        $size = $row['size'];

                                        // get products

                                        $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
                                        $stmt->execute(array($product_id));
                                        $products = $stmt->fetchAll();

                                        // the loop 
                                        foreach ($products as $product) {
                                            $images = explode(',', $product['images']);
                                            echo '
                                            <div class="col-12 col-md-6 col-lg-6 product" id="' . $row['order_number'] . '">
                                            <div class="row">
                                            <div class="col-4 img-container">
                                            <a href="product-details.php?id=' . $product_id . '"><img src="../assets/img/products/' . $images[1] . '" alt="" /></a>
                                            </div>
                                            <div class="col-8 product-info">

                                            <p class="title">' . $product['title'] . '</p>

                                            ';

                                            $product_price = $row['price'];
                                            if ($row['coins'] != 0) {
                                                $product_price = $product_price - intval($row['coins']);
                                            }


                                            echo '
                                            <p class="price">' . $product_price . ' جنيه</p>
                                            <div class="row">

                                            <div class="col-6">
                                                <div class="quantity">
                                                <p> الكمية:</p>

                                                <span style="font-size: 22px;"> ' . $quantity . '</span>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="size">
                                                <p> المقاس:</p>
                                                <span>' . $size . '</span>
                                                </div>
                                            </div>

                                            <br>
                                            <br>
                                            <div class="col-12">
                                            <strong>مصاريف الشحن ' . $row['shipping_fees'] . ' جنيه </strong>
                                            </div>
                                            <br>
                                            
                                            <div class="col-12">
                                            <p class="lead">' . $row['address'] . '</p>
                                            </div>
                                            
                                            <br>
                                            
                                            ';
                                            if ($row['promocode'] != 0) {
                                                echo '
                                                <div class="col-12">
                                                <p class="order-pay-info ">تم استخدام كوبون  <strong>' . $row['promocode'] . '</strong> </p>
                                                </div>
                                                ';
                                            } elseif ($row['coins'] != 0) {
                                                echo '
                                                <div class="col-12">
                                                <p class="order-pay-info ">تم استخدام   <strong>' . $row['coins'] . '</strong> عملة </p>
                                                </div>
                                                ';
                                            }

                                            echo '
                                            <div class="col-12">رقم الطلب:  <p class="lead" style="color: brown">' . $row['order_number'] . '</p></div>
                                            <div class="col-6">
                                                <div class="btn btn-danger delete-order" data-order= ' . $row['id'] . ' style="margin-top: 20px;">حذف الطلب</div>
                                            </div>
                                            <div class="col-6">
                                            <div class="btn btn-success delivered" data-order= ' . $row['id'] . ' style="margin-top: 20px;">تم تسليم الطلب</div>
                                            </div>
                                            </div>
                                                </div>
                                                </div>
                                                </div>
                                                ';
                                        }
                                    }
                                    echo '
                                    <hr>
                                    ';
                                }

                                // orders will arrive today

                                $stmt = $con->prepare("SELECT * FROM orders WHERE user_key = ? AND shipping_date = ? AND done = 0");
                                $stmt->execute(array($user_key, date("Y-m-d")));
                                $orders_arrive_today = $stmt->rowCount();
                                if ($orders_arrive_today > 0) {
                                    echo '
                                    <div class="col-8"> <h4 class="title"> طلبات ستصل اليوم <span class="rounded-circle orders-number"> ' . $count . '</span>  </h4> </div>
                                    <div class="col-4 text-start"><div class="btn btn-success dliver-orders-day" data-day=' . date('Y-m-d') . '>تسليم جميع طلبات اليوم</div></div>
                                    ';
                                    $rows = $stmt->fetchAll();

                                    // the loop 

                                    foreach ($rows as $row) {
                                        $product_id = $row['product_id'];
                                        $quantity = $row['quantity'];
                                        $size = $row['size'];

                                        // get products

                                        $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
                                        $stmt->execute(array($product_id));
                                        $products = $stmt->fetchAll();

                                        // the loop 
                                        foreach ($products as $product) {
                                            $images = explode(',', $product['images']);
                                            echo '
                                            <div class="col-12 col-md-6 col-lg-6 product"  id="' . $row['order_number'] . '">
                                            <div class="row">
                                            <div class="col-4 img-container">
                                            <a href="product-details.php?id=' . $product_id . '"><img src="../assets/img/products/' . $images[1] . '" alt="" /></a>
                                            </div>
                                            <div class="col-8 product-info">

                                            <p class="title">' . $product['title'] . '</p>

                                            ';
                                            $product_price = $row['price'];
                                            if ($row['coins'] != 0) {
                                                $product_price = $product_price - intval($row['coins']);
                                            }
                                            if ($row['coins'] != 0) {
                                                $product_price = $product_price - intval($row['coins']);
                                            }


                                            echo '
                                            <p class="price">' . $product_price . ' جنيه</p>
                                            <div class="row">

                                            <div class="col-6">
                                                <div class="quantity">
                                                <p> الكمية:</p>

                                                <span style="font-size: 22px;"> ' . $quantity . '</span>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="size">
                                                <p> المقاس:</p>
                                                <span>' . $size . '</span>
                                                </div>
                                            </div>

                                            <br>
                                            <br>
                                            <div class="col-12">
                                            <strong>مصاريف الشحن ' . $row['shipping_fees'] . ' جنيه </strong>
                                            </div>
                                            <br>
                                            
                                            <div class="col-12">
                                            <p class="lead">' . $row['address'] . '</p>
                                            </div>
                                            
                                            <br>
                                            
                                            ';
                                            if ($row['promocode'] != 0) {
                                                echo '
                                                <div class="col-12">
                                                <p class="order-pay-info ">تم استخدام كوبون  <strong>' . $row['promocode'] . '</strong> </p>
                                                </div>
                                                ';
                                            } elseif ($row['coins'] != 0) {
                                                echo '
                                                <div class="col-12">
                                                <p class="order-pay-info ">تم استخدام   <strong>' . $row['coins'] . '</strong> عملة </p>
                                                </div>
                                                ';
                                            }

                                            echo '
                                            <div class="col-12">رقم الطلب:  <p class="lead" style="color: brown">' . $row['order_number'] . '</p></div>

                                            <div class="col-6">
                                                <div class="btn btn-danger delete-order" data-order= ' . $row['id'] . ' style="margin-top: 20px;">حذف الطلب</div>
                                            </div>
                                            <div class="col-6">
                                            <div class="btn btn-success delivered" data-order= ' . $row['id'] . ' style="margin-top: 20px;">تم تسليم الطلب</div>
                                            </div>
                                            </div>
                                                </div>
                                                </div>
                                                </div>
                                                ';
                                        }
                                    }

                                    echo '
                                    <hr>
                                    ';
                                }

                                // orders will arrive in other dayes
                                $stmt = $con->prepare("SELECT DISTINCT shipping_date FROM orders WHERE user_key = ? ORDER BY id DESC");
                                $stmt->execute(array($user_key));
                                $orders_deliver_dates = $stmt->rowCount();
                                if ($orders_deliver_dates > 0) {
                                    $dayes = $stmt->fetchAll();

                                    // the loop 

                                    foreach ($dayes as $day) {
                                        $stmt = $con->prepare("SELECT * FROM orders WHERE user_key = ? AND shipping_date = ? AND done = 0");
                                        $stmt->execute(array($user_key, $day['shipping_date']));
                                        $count = $stmt->rowCount();
                                        if ($count > 0) {
                                            echo '
                                    <div class="col-8"> <h4 class="title"> طلبات ستصل يوم' . $day['shipping_date'] . ' <span class="rounded-circle orders-number"> ' . $count . '</span>  </h4> </div>
                                    <div class="col-4 text-start"><div class="btn btn-success dliver-orders-day" data-day=' . $day['shipping_date'] . ' >تسليم جميع طلبات اليوم</div></div>
                                    ';
                                            $rows = $stmt->fetchAll();

                                            // the loop 

                                            foreach ($rows as $row) {
                                                $product_id = $row['product_id'];
                                                $quantity = $row['quantity'];
                                                $size = $row['size'];

                                                // get products

                                                $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
                                                $stmt->execute(array($product_id));
                                                $products = $stmt->fetchAll();

                                                // the loop 
                                                foreach ($products as $product) {
                                                    $images = explode(',', $product['images']);
                                                    echo '
                                            <div class="col-12 col-md-6 col-lg-6 product"  id="' . $row['order_number'] . '">
                                            <div class="row">
                                            <div class="col-4 img-container">
                                            <a href="product-details.php?id=' . $product_id . '"><img src="../assets/img/products/' . $images[1] . '" alt="" /></a>
                                            </div>
                                            <div class="col-8 product-info">

                                            <p class="title">' . $product['title'] . '</p>

                                            ';

                                                    $product_price = $row['price'];
                                                    if ($row['coins'] != 0) {
                                                        $product_price = $product_price - intval($row['coins']);
                                                    }



                                                    echo '
                                            <p class="price">' . $product_price . ' جنيه</p>
                                            <div class="row">

                                            <div class="col-6">
                                                <div class="quantity">
                                                <p> الكمية:</p>

                                                <span style="font-size: 22px;"> ' . $quantity . '</span>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="size">
                                                <p> المقاس:</p>
                                                <span>' . $size . '</span>
                                                </div>
                                            </div>

                                            <br>
                                            <br>
                                            <div class="col-12">
                                            <strong>مصاريف الشحن ' . $row['shipping_fees'] . ' جنيه </strong>
                                            </div>
                                            <br>
                                            
                                            <div class="col-12">
                                            <p class="lead">' . $row['address'] . '</p>
                                            </div>
                                            
                                            <br>
                                            
                                            ';
                                                    if ($row['promocode'] != 0) {
                                                        echo '
                                                <div class="col-12">
                                                <p class="order-pay-info ">تم استخدام كوبون  <strong>' . $row['promocode'] . '</strong> </p>
                                                </div>
                                                ';
                                                    } elseif ($row['coins'] != 0) {
                                                        echo '
                                                <div class="col-12">
                                                <p class="order-pay-info ">تم استخدام   <strong>' . $row['coins'] . '</strong> عملة </p>
                                                </div>
                                                ';
                                                    }

                                                    echo '
                                                    <div class="col-12">رقم الطلب:  <p class="lead" style="color: brown">' . $row['order_number'] . '</p></div>
                                            <div class="col-6">
                                                <div class="btn btn-danger delete-order" data-order= ' . $row['id'] . ' style="margin-top: 20px;">حذف الطلب</div>
                                            </div>
                                            <div class="col-6">
                                            <div class="btn btn-success delivered" data-order= ' . $row['id'] . ' style="margin-top: 20px;">تم تسليم الطلب</div>
                                            </div>
                                            </div>
                                                </div>
                                                </div>
                                                </div>
                                                ';
                                                }
                                            }

                                            echo '
                                    <hr>
                                    ';
                                        }
                                    }
                                }

                                echo '
                                <hr>
                                <hr>
                                <br>
                                ';

                                // orders has been delivered 

                                $stmt = $con->prepare("SELECT DISTINCT shipping_date FROM orders WHERE user_key = ? ORDER BY id DESC");
                                $stmt->execute(array($user_key));
                                $orders_deliver_dates = $stmt->rowCount();
                                if ($orders_deliver_dates > 0) {
                                    $dayes = $stmt->fetchAll();

                                    // the loop 

                                    foreach ($dayes as $day) {
                                        $stmt = $con->prepare("SELECT * FROM orders WHERE user_key = ? AND shipping_date = ? AND done = 1");
                                        $stmt->execute(array($user_key, $day['shipping_date']));
                                        $count = $stmt->rowCount();
                                        if ($count > 0) {
                                            echo '
                                    <div class="col-8"> <h4 class="title"> طلبات تم تسليمها يوم' . $day['shipping_date'] . ' <span class="rounded-circle orders-number"> ' . $count . '</span>  </h4> </div>
                                    <div class="col-4 text-start"><div class="btn btn-secondary return-orders-day" data-day=' . $day['shipping_date'] . ' >ارجاع جميع طلبات اليوم</div></div>
                                    ';
                                            $rows = $stmt->fetchAll();

                                            // the loop 

                                            foreach ($rows as $row) {
                                                $product_id = $row['product_id'];
                                                $quantity = $row['quantity'];
                                                $size = $row['size'];

                                                // get products

                                                $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
                                                $stmt->execute(array($product_id));
                                                $products = $stmt->fetchAll();

                                                // the loop 
                                                foreach ($products as $product) {
                                                    $images = explode(',', $product['images']);
                                                    echo '
                                            <div class="col-12 col-md-6 col-lg-6 product"  id="' . $row['order_number'] . '">
                                            <div class="row">
                                            <div class="col-4 img-container">
                                            <a href="product-details.php?id=' . $product_id . '"><img src="../assets/img/products/' . $images[1] . '" alt="" /></a>
                                            </div>
                                            <div class="col-8 product-info">

                                            <p class="title">' . $product['title'] . '</p>

                                            ';

                                                    $product_price = $row['price'];
                                                    if ($row['coins'] != 0) {
                                                        $product_price = $product_price - intval($row['coins']);
                                                    }



                                                    echo '
                                            <p class="price">' . $product_price . ' جنيه</p>
                                            <div class="row">

                                            <div class="col-6">
                                                <div class="quantity">
                                                <p> الكمية:</p>

                                                <span style="font-size: 22px;"> ' . $quantity . '</span>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="size">
                                                <p> المقاس:</p>
                                                <span>' . $size . '</span>
                                                </div>
                                            </div>

                                            <br>
                                            <br>
                                            <div class="col-12">
                                            <strong>مصاريف الشحن ' . $row['shipping_fees'] . ' جنيه </strong>
                                            </div>
                                            <br>
                                            
                                            <div class="col-12">
                                            <p class="lead">' . $row['address'] . '</p>
                                            </div>
                                            
                                            <br>
                                            
                                            ';
                                                    if ($row['promocode'] != 0) {
                                                        echo '
                                                <div class="col-12">
                                                <p class="order-pay-info ">تم استخدام كوبون  <strong>' . $row['promocode'] . '</strong> </p>
                                                </div>
                                                ';
                                                    } elseif ($row['coins'] != 0) {
                                                        echo '
                                                <div class="col-12">
                                                <p class="order-pay-info ">تم استخدام   <strong>' . $row['coins'] . '</strong> عملة </p>
                                                </div>
                                                ';
                                                    }

                                                    echo '
                                                    <div class="col-12">رقم الطلب:  <p class="lead" style="color: brown">' . $row['order_number'] . '</p></div>

                                            <div class="col-12">
                                            <div class="btn btn-secondary return-order" data-order= ' . $row['id'] . ' style="margin-top: 20px;">ارجاع الطلب</div>
                                            </div>
                                            </div>
                                                </div>
                                                </div>
                                                </div>
                                                ';
                                                }
                                            }

                                            echo '
                                    <hr>
                                    ';
                                        }
                                    }
                                }

                                echo '
                                <hr>
                                <hr>
                                <br>
                                ';

                                // orders has been returned 

                                $stmt = $con->prepare("SELECT DISTINCT return_date FROM returns_order WHERE user_key = ? ORDER BY id DESC");
                                $stmt->execute(array($user_key));
                                $orders_deliver_dates = $stmt->rowCount();
                                if ($orders_deliver_dates > 0) {
                                    $dayes = $stmt->fetchAll();

                                    // the loop 

                                    foreach ($dayes as $day) {
                                        $stmt = $con->prepare("SELECT * FROM returns_order WHERE user_key = ? AND return_date = ? ");
                                        $stmt->execute(array($user_key, $day['return_date']));
                                        $count = $stmt->rowCount();
                                        if ($count > 0) {
                                            echo '
                                    <div class="col-8"> <h4 class="title"> طلبات تم ارجاعها يوم' . $day['return_date'] . ' <span class="rounded-circle orders-number"> ' . $count . '</span>  </h4> </div>
                                    ';
                                            $rows = $stmt->fetchAll();

                                            // the loop 

                                            foreach ($rows as $row) {
                                                $product_id = $row['product_id'];
                                                $quantity = $row['quantity'];
                                                $size = $row['size'];

                                                // get products

                                                $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
                                                $stmt->execute(array($product_id));
                                                $products = $stmt->fetchAll();

                                                // the loop 
                                                foreach ($products as $product) {
                                                    $images = explode(',', $product['images']);
                                                    echo '
                                            <div class="col-12 col-md-6 col-lg-6 product"  id="' . $row['order_number'] . '">
                                            <div class="row">
                                            <div class="col-4 img-container">
                                            <a href="product-details.php?id=' . $product_id . '"><img src="../assets/img/products/' . $images[1] . '" alt="" /></a>
                                            </div>
                                            <div class="col-8 product-info">

                                            <p class="title">' . $product['title'] . '</p>

                                            ';

                                                    $product_price = $row['price'];

                                                    if ($row['coins'] != 0) {
                                                        $product_price = $product_price - intval($row['coins']);
                                                    }


                                                    echo '
                                            <p class="price">' . $product_price . ' جنيه</p>
                                            <div class="row">

                                            <div class="col-6">
                                                <div class="quantity">
                                                <p> الكمية:</p>

                                                <span style="font-size: 22px;"> ' . $quantity . '</span>

                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="size">
                                                <p> المقاس:</p>
                                                <span>' . $size . '</span>
                                                </div>
                                            </div>

                                            <br>
                                            <br>
                                            <div class="col-12">
                                            <strong>تاريخ الطلب ' . $row['order_date'] . ' </strong>
                                            </div>
                                            <br>
                                            <br>
                                            <div class="col-12">
                                            <strong>تاريخ التسليم ' . $row['shipping_date'] . ' </strong>
                                            </div>
                                            ';
                                                    if ($row['promocode'] != 0) {
                                                        echo '
                                                <div class="col-12">
                                                <p class="order-pay-info ">تم استخدام كوبون  <strong>' . $row['promocode'] . '</strong> </p>
                                                </div>
                                                ';
                                                    } elseif ($row['coins'] != 0) {
                                                        echo '
                                                <div class="col-12">
                                                <p class="order-pay-info ">تم استخدام   <strong>' . $row['coins'] . '</strong> عملة </p>
                                                </div>
                                                ';
                                                    }

                                                    echo '
                                                    <br>
                                                    <br>
                                                    <div class="col-12">رقم الطلب:  <p class="lead" style="color: brown">' . $row['order_number'] . '</p></div>

                                            </div>
                                                </div>
                                                </div>
                                                </div>
                                                ';
                                                }
                                            }

                                            echo '
                                    <hr>
                                    ';
                                        }
                                    }
                                }

                                ?>


                            </div>
                        </div>


                    </div>

                    <!-- end orders details -->

            </main>
        </div>
    </div>
    <?Php
    $stmt = $con->prepare("SELECT name, phone, email, address1, trade FROM users WHERE user_key = ?");
    $stmt->execute(array($_GET['key']));
    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        echo '
        <div class="customer-data bg-light">
        <p>الاسم : ' . $row['name'] . '</p>
        <p>الرقم : ' . $row['phone'] . '</p>
        <p>الايميل : ' . $row['email'] . '</p>
        <p>العنوان : ' . $row['address1'] . '</p>
        ';
        if ($row['trade'] == 0) {
            echo '<p>النظام : قطاعي</p>';
        } else {
            echo '<p>النظام : جملة</p>';
        };
        echo '
        <span class="close-customer-data">X</span>
    </div>
        ';
    }
    ?>


    <div class="test"></div>

    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery-3.2.1.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $page_url = $(location).attr('href');
        // delete product 

        $('.delete-order').click(function() {
            swal({
                    title: "هل أنت متأكد من حذف الطلب ؟",
                    text: " اذا قمت بالضغط على OK سيتم الحذف",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $(this).parentsUntil('.product-containers').hide();
                        swal("تم حذف الطلب", {
                            icon: "success",
                        });
                        $.post('order_details.php', {
                            delete_order: true,
                            order_id: $(this).data('order'),
                        }, function() {
                            swal("تم بنجاح", {
                                    icon: "success",
                                })
                                .then((value) => {
                                    location.reload()
                                });
                        });

                    }
                });
        })
        $('.delivered').click(function() {
            swal({
                    title: "هل أنت متأكد من وصول الطلب؟",
                    text: " اذا قمت بالضغط على OK سيتم التأكيد",
                    icon: "info",
                    buttons: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("تم بنجاح", {
                            icon: "success",
                        }).then((value) => {
                            location.reload()
                        });
                        $.post('order_details.php', {
                            deliver_order: true,
                            order_id: $(this).data('order'),
                        }, function() {
                            swal("تم بنجاح", {
                                    icon: "success",
                                })
                                .then((value) => {
                                    location.reload()
                                });
                        });
                    }
                });
        })

        $('.dliver-orders-day').click(function() {
            swal({
                    title: " هل أنت متأكد من توصيل جميع طلبات يوم " + $(this).data('day') + " ؟ ",
                    text: " اذا قمت بالضغط على OK سيتم التأكيد",
                    icon: "info",
                    buttons: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.post($page_url, {
                            deliver_orders_day: true,
                            orders_day: $(this).data('day'),
                        }, function() {
                            swal("تم بنجاح", {
                                    icon: "success",
                                })
                                .then((value) => {
                                    location.reload()
                                });
                        });

                    }
                });
        })

        // return order 

        $('.return-order').click(function() {
            swal({
                    title: "هل أنت متأكد من ارجاع الطلب؟",
                    text: " اذا قمت بالضغط على OK سيتم التأكيد",
                    icon: "info",
                    buttons: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("تم بنجاح", {
                            icon: "success",
                        }).then((value) => {
                            location.reload()
                        });
                        $.post('order_details.php', {
                            return_order: true,
                            order_id: $(this).data('order'),
                        }, function() {
                            swal("تم بنجاح", {
                                    icon: "success",
                                })
                                .then((value) => {
                                    location.reload()
                                });
                        });
                    }
                });
        })

        // return all-day orders

        $('.return-orders-day').click(function() {
            swal({
                    title: " هل أنت متأكد من ارجاع جميع طلبات يوم " + $(this).data('day') + " ؟ ",
                    text: " اذا قمت بالضغط على OK سيتم التأكيد",
                    icon: "info",
                    buttons: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.post($page_url, {
                            return_orders_day: true,
                            orders_day: $(this).data('day'),
                        }, function() {
                            swal("تم بنجاح", {
                                    icon: "success",
                                })
                                .then((value) => {
                                    location.reload()
                                });
                        });

                    }
                });
        })

        // show customer data

        $('.show-customer-data').click(function() {
            $('.customer-data').toggleClass('show');
        })
        $('.close-customer-data').click(function() {
            $('.customer-data').removeClass('show');
        })
    </script>
</body>

</html>