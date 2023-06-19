<?php

include 'connect.php';

// check login 

if (isset($_COOKIE['key'])) {

    $key = filter_var($_COOKIE['key'], FILTER_SANITIZE_STRING);

    // check if account is exit  

    $stmt = $con->prepare("SELECT user_key FROM users WHERE user_key = ?");
    $stmt->execute(array($key));
    $count = $stmt->rowCount();

    if (!($count > 0)) {

        // check if id isn't right
        echo '
        <script>            
            window.location.href = "error.php";
      </script>
  
        ';
        exit();
    }
} else {
    // if login is wrong 
    echo '
                <script>
                    window.location.href = "error.php";
              </script>
          
                ';
    exit();
}

// get user info 

$stmt = $con->prepare("SELECT name, phone, email FROM users WHERE user_key = ?");
$stmt->execute(array($key));
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $name = $row['name'];
    $phone = $row['phone'];
    $email = $row['email'];
}

// get order info 

$stmt = $con->prepare("SELECT DISTINCT total_order_number, order_date, address FROM orders WHERE user_key = ? ORDER BY id DESC LIMIT 1 ");
$stmt->execute(array($key));
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $order_number = $row['total_order_number'];
    $order_date = $row['order_date'];
    $address = $row['address'];
}

// get ecommerce main info 
$stmt = $con->prepare("SELECT * FROM web_info");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $title = $row['title'];
    $logo = $row['logo'];
    $color = $row['color'];
    $description = $row['description'];
    $slogan = $row['slogan'];
    $shiiping = $row['shipping'];
}
foreach ($rows as $row) {
    $title = $row['title'];
    $logo = $row['logo'];
    $color = $row['color'];
    $description = $row['description'];
    $slogan = $row['slogan'];
    $shiiping = $row['shipping'];
}

// get connection 

$stmt = $con->prepare("SELECT * FROM connection");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $phone = $row['phone'];
    $email = $row['email'];
    $whatsapp = $row['whatsapp'];
    $address = $row['address'];
    $facebook = $row['facebook'];
    $instagram = $row['instagram'];
    $twitter = $row['twitter'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="multikart">
    <meta name="keywords" content="multikart">
    <meta name="author" content="multikart">
    <link rel="icon" href="../assets/images/favicon/1.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/favicon/1.png" type="image/x-icon">
    <title><?php echo $title . ' | ' . $slogan; ?></title>

    <!--Google font-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">

    <!-- Animate icon -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/animate.css">

    <!-- Themify icon -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify-icons.css">

    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">

    <!-- Theme css -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <style>
        @media print {

            html,
            body {
                height: 100%;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden;
            }

            .print-div {
                display: none;
            }

        }

        thead {
            background-color: black !important;
            background-image: none !important;
        }
    </style>
</head>

<body class="theme-color-1 bg-light" style="overflow-x: hidden;--theme-color:<?php echo $color; ?>">


    <!-- invoice start -->
    <section class="theme-invoice-3 section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 m-auto">
                    <div class="invoice-wrapper">
                        <div class="invoice-header">
                            <ul>
                                <li>
                                    <img src="assets/img/logo/<?php echo $logo; ?>" class="img-fluid" alt="logo">
                                </li>
                                <li>
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    <h4><?php echo $title ?></h4>
                                </li>
                                <li>
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    <h4><?php echo $email; ?> </h4>
                                </li>
                            </ul>
                        </div>
                        <div class="invoice-body">
                            <div class="top-sec">
                                <div class="row">
                                    <div class="col-lg-8 col-sm-6">
                                        <div class="address-detail">
                                            <h2>invoice</h2>
                                            <div class="mt-3">
                                                <h4 class="mb-2" style="color:var(--theme-color);">
                                                    <?php echo $name ?>
                                                </h4>
                                            </div>
                                            <div class="mt-3">
                                                <h4 class="mb-2">
                                                    <?php echo $address ?>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 mt-md-0 mt-2">
                                        <ul class="date-detail">
                                            <li><span>Order Number :</span>
                                                <h4> <?php echo $order_number ?></h4>
                                            </li>
                                            <li><span>Order Date :</span>
                                                <h4> <?php echo $order_date ?></h4>
                                            </li>
                                            <li><span>phone :</span>
                                                <h4> <?php echo $phone ?></h4>
                                            </li>
                                            <li><span>email :</span>
                                                <h4> <?php echo $email ?></h4>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive-md">
                                <table class="table table-borderless mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">price</th>
                                            <th scope="col">quantity</th>
                                            <th scope="col">total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $auto_id = 0;
                                        $total = 0;
                                        $stmt = $con->prepare("SELECT product_id, quantity, price, coins, shipping_fees, promocode FROM orders WHERE total_order_number = ? ORDER BY id DESC LIMIT 1 ");
                                        $stmt->execute(array($order_number));
                                        $rows = $stmt->fetchAll();

                                        foreach ($rows as $row) {
                                            echo '
                                            <tr>
                                            <th scope="row">' . ++$auto_id . '</th>';
                                            $stmt = $con->prepare("SELECT title FROM products WHERE id = ?");
                                            $stmt->execute(array($row['product_id']));
                                            $products = $stmt->fetchAll();

                                            foreach ($products as $product) {
                                                echo '<td>' . $product['title'] . '</td>';
                                            }
                                            $product_price = $row['price'];
                                            $coupon_name = $row['promocode'];
                                            $coins = $row['coins'];
                                            if ($coins != '0') {
                                                $product_price = $product_price - intval($coins);
                                            }
                                            echo '
                                            <td>' . $product_price . ' LE </td>
                                            <td>' . $row['quantity'] . '</td>
                                            <td>' . $product_price * $row['quantity'] . '</td>
                                            </tr>
                                            ';
                                            $total = $total + ($product_price * $row['quantity']);
                                            $shipping_fees = $row['shipping_fees'];
                                        }
                                        $total = $total + $shipping_fees;

                                        if ($coupon_name != 0) {
                                            $stmt = $con->prepare("SELECT discount FROM coupons WHERE coupon = ? ");
                                            $stmt->execute(array($coupon_name));
                                            $coupons = $stmt->fetchAll();

                                            foreach ($coupons as $coupon) {
                                                $coupon_discount = $coupon['discount'];
                                            }
                                            echo '
                                            <tr>
                                            <td colspan="1"></td>
                                            <td class="font-bold text-dark" colspan="2">Coupon</td>
                                            <td class="font-bold text-theme">
                                            <h4 class="text-uppercase text-theme">' . $coupon_name . ' <sup>%' . $coupon_discount . ' </sup>  </h4>
                                            </tr>
                                                ';
                                        }

                                        if ($coins != '0') {
                                            echo '
                                            <tr class="table-order">
                                            <td colspan="1"></td>
                                            <td class="font-bold text-dark" colspan="2">
                                                Coins 
                                            </td>
                                            <td class="font-bold text-theme">
                                            <h4 class=" text-theme">' . $coins . ' coin </h4>
                                        </td>
                                        </tr>
                                            ';
                                        }

                                        ?>

                                        <tr>
                                            <td colspan="1"></td>
                                            <td class="font-bold text-dark" colspan="2">Shipping Fees</td>
                                            <td class="font-bold text-theme"><?php
                                                                                if ($shipping_fees == 0) {
                                                                                    $shipping_fees = 'FREE';
                                                                                }
                                                                                echo $shipping_fees; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="font-bold text-dark" colspan="2">GRAND TOTAL</td>
                                            <td class="font-bold text-theme"><?php echo $total ?> LE</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="invoice-footer pt-0">
                            <div class="row">

                                <div class="col-6 text-start print-div">
                                    <a href="#" class="btn btn-solid rounded-2" onclick="window.print();">print</a>
                                </div>
                                <div class="col-6 text-start print-div">
                                    <a href="index.php" class="btn btn-solid rounded-2">Home Page</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- invoice end -->


    <!-- latest jquery-->
    <script src="assets/js/jquery-3.3.1.min.js"></script>

    <!-- Bootstrap js-->
    <script src="assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>