<?php
// get ecommerce main info 
include 'connect.php';
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
include 'theme_header.php';
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
            window.location.href = "login.php";
      </script>
  
        ';
        exit();
    }
} else {
    // if login is wrong 
    echo '
                <script>
                    window.location.href = "login.php";
              </script>
          
                ';
    exit();
}

$stmt = $con->prepare("SELECT * FROM users WHERE user_key = ?");
$stmt->execute(array($key));
$users = $stmt->fetchAll();

// the loop 
foreach ($users as $user) {
    $name = $user['name'];
    $email = $user['email'];
    $phone = $user['phone'];
    $address = $user['address1'];
    $governorate = $user['governorate'];
}

if ($phone == '' or $phone == 0) {
    $phone = 'لم يتم تحديد رقم الهاتف';
}
if ($address == '') {
    $address = 'لم يتم تحديد عنوانك';
}

?>
<link href="assets/css/cart.css" rel="stylesheet" />

<style>
    p,
    span {
        font-size: 16px;
    }

    .edit-profile,
    .edit-profile:hover {
        background-color: var(--maincolor);
        color: #fff;
        border: none;
    }

    .delete>div {
        margin-top: 20px;
    }

    .delete>div>i {
        color: #fff;
    }

    .product {
        box-shadow: 0 2px 5px 0 rgb(0 0 0 / 8%);
        margin: 40px 0;
        padding: 20px;
    }

    .product img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 5px;
    }

    .product-info .title {
        font-size: 1.4rem;
        color: #000;
    }

    .product-info .price {
        color: #006fcc;
        font-size: 20px;
    }

    .quantity p {
        display: inline-block;
        margin: 0px 0px 0 10px;
        font-size: 20px;
        color: #000;
    }

    .quantity input {
        font-size: 18px;
        width: 90px !important;
        display: inline-block !important;
        cursor: pointer;
        padding: 3px;
        text-align: center;
    }

    .size p {
        display: inline-block;
        margin: 0px 0px 0 10px;
        font-size: 20px;
        color: #000;
    }

    .size span {
        font-size: 22px;
    }

    .delete {
        cursor: pointer;
        padding: 0px;
        height: 50px;
        line-height: 15px;
    }

    .delete p {
        display: inline-block;
        margin: 22px 0px 0 10px;
        font-size: 20px;
    }

    .delete i {
        color: rgb(218, 33, 33);
        font-size: 19px;
    }

    @media (max-width: 767px) {
        .delete p {
            font-size: 18px;
        }
    }

    .dark .title,
    .dark .quantity p,
    .dark .size p {
        color: #cfd4da;
    }

    .card {
        font-size: 15px;
    }

    h6 {
        color: #000;
        font-size: 16px;
    }
</style>
<main id="main" class="container">

    <section class="inner-page">

        <div class="row gutters-sm main-card ">

            <div class="col-md-12 d-none d-md-block ">
                <div class="card mb-3 text-center">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <h6 class="mb-0"> Full name</h6>
                            </div>
                            <div class="col-sm-4 text-secondary">
                                <?php echo $name; ?>
                            </div>
                            <div class="col-sm-2">
                                <h6 class="mb-0">Email </h6>
                            </div>
                            <div class="col-sm-4 text-secondary">
                                <?php echo $email; ?>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-2">
                                <h6 class="mb-0">Phone</h6>
                            </div>
                            <div class="col-sm-4 text-secondary">
                                <?php echo $phone; ?>
                            </div>
                            <div class="col-sm-2">
                                <h6 class="mb-0">City</h6>
                            </div>
                            <div class="col-sm-4 text-secondary">
                                <?php echo $governorate; ?>
                            </div>
                        </div>
                        <hr>

                        <div class="row ">
                            <div class="col-sm-2">
                                <h6 class="mb-0">address</h6>
                            </div>
                            <div class="col-sm-9 text-s text-secondary">
                                <?php echo $address; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <a class="btn btn-solid " href="edit_profile.php"> Edit Your Info </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- mobile version !-->

            <div class="col-md-12 d-md-none d-block ">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0"> Full name </h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $name; ?>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email </h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $email; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Phone </h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $phone; ?>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Address</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $address; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <a class="btn btn-info edit-profile " href="edit_profile.php"> Edit Your Info </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <?Php

            $stmt = $con->prepare("SELECT * FROM orders WHERE user_key = ? ORDER BY id DESC");
            $stmt->execute(array($key));
            $rows = $stmt->fetchAll();

            if (count($rows) > 0) {

                echo '
                        <br>
                        <br>
                        <div class="title1">
                        <h2 class="title-inner1"> Your Orders</h2>
                       </div>
                        ';

                foreach ($rows as $row) {

                    $product_id = $row['product_id'];
                    $quantity = $row['quantity'];
                    $size = $row['size'];
                    $status = $row['status'];

                    $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
                    $stmt->execute(array($product_id));
                    $products = $stmt->fetchAll();

                    // the loop 
                    foreach ($products as $product) {

                        $images = explode(',', $product['images']);
                        echo '
                            <div class="col-12 col-md-6 col-lg-6 product">
                            <div class="row">
                                <div class="col-4 img-container">
                                <a href="product-details.php?id=' . $product_id . '"><img src="assets/img/products/' . $images[1] . '" alt="" /></a>
                                </div>
                                <div class="col-8 product-info">

                                <p class="title">' . $product['title'] . '</p>

                                ';

                        $product_price = $row['price'];
                        if ($row['coins'] != 0) {
                            $product_price = $product_price - intval($row['coins']);
                        }
                        echo '
                                <p class="price" data-product_price="200">200 LE</p>

                                <div class="row">
                                <div class="quantity col-6 col-md-6 col-12">
                                    <p> quantity : ' . $quantity . ' </p>
                                </div>
                                
                                <div class="size col-md-6 col-12">
                                ';
                        if ($size != '') {
                            echo '
                                <p> Size: ' . $size . ' </p>
                                ';
                        }
                        echo '
                                </div>
                                <br>
                                <br>
                                <div  style="font-size:19px;"> Order Number:   <span  style="color: var(--theme-color); font-size: 18px;">' . $row['total_order_number'] . '</span></div>
                                </div>
                                <br>
                                <div style="font-size: 18px;">
                                Order Status :
                                ';
                        if ($status == 0) {
                            echo '<span class="text-warning">Pending  </span>';
                        } elseif ($status == 1) {
                            echo '<span class="text-secondary">Confirmed </span>';
                        } elseif ($status == 2) {
                            echo '<span class="text-primary">Packaged </span>';
                        } elseif ($status == 3) {
                            echo '<span style="color: var(--theme-color)">On Way</span>';
                        } elseif ($status == 4) {
                            echo '<span class="text-danger">Arrived </span>';
                        }
                        echo '
                                </div>
                                <br>
                                <div>
                                <strong> Order Date : ' . $row['order_date'] . ' </strong>
                                </div>
                                <br>
                                <div>
                                <strong> Shiping Date  : ' . $row['shipping_date'] . ' </strong>
                                </div>
                            </div>
                        </div>
                        </div>
                            ';
                    }
                }
            }

            ?>


            <?Php

            $stmt = $con->prepare("SELECT * FROM returns_order WHERE user_key = ? ORDER BY id DESC");
            $stmt->execute(array($key));
            $rows = $stmt->fetchAll();

            if (count($rows) > 0) {

                echo '

            <hr style="width: 80%;margin:auto">
            <br>
            <br>
            <div class="title1">
            <h2 class="title-inner1"> Your Returns</h2>
           </div>
            ';

                foreach ($rows as $row) {

                    $product_id = $row['product_id'];
                    $quantity = $row['quantity'];
                    $size = $row['size'];

                    $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
                    $stmt->execute(array($product_id));
                    $products = $stmt->fetchAll();

                    // the loop 
                    foreach ($products as $product) {

                        $images = explode(',', $product['images']);
                        echo '
                <div class="col-12 col-md-6 col-lg-6 product">
                <div class="row">
                    <div class="col-4 img-container">
                    <a href="product-details.php?id=' . $product_id . '"><img src="assets/img/products/' . $images[1] . '" alt="" /></a>
                    </div>
                    <div class="col-8 product-info">

                    <p class="title">' . $product['title'] . '</p>

                    ';

                        $product_price = $row['price'];
                        if ($row['coins'] != 0) {
                            $product_price = $product_price - intval($row['coins']);
                        }
                        echo '
                    <p class="price" data-product_price="200">200 LE</p>

                    <div class="row">
                    <div class="quantity col-6 col-md-6 col-12">
                        <p> quantity : ' . $quantity . ' </p>
                    </div>
                    
                    <div class="size col-md-6 col-12">
                    ';
                        if ($size != '') {
                            echo '
                    <p> Size: ' . $size . ' </p>
                    ';
                        }
                        echo '
                    </div>
                    <br>
                    <br>
                    <div  style="font-size:19px;"> Order Number:   <span  style="color: var(--theme-color); font-size: 18px;">' . $row['order_number'] . '</span></div>
                    </div>
                    
                    <br>
                    <div>
                    <strong> Order Date : ' . $row['order_date'] . ' </strong>
                    </div>
                    <br>
                    <div>
                    <strong> Return Date  : ' . $row['return_date'] . ' </strong>
                    </div>
                </div>
            </div>
            </div>
                ';
                    }
                }

                echo '
</div>

            ';
            }

            ?>


            <?php include 'theme_footer.php'; ?>


            </body>

            </html>