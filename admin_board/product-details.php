<?php

include '../connect.php';

// check id 

if (isset($_GET['id'])) {

    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // check if id is right

    $stmt = $con->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->execute(array($id));
    $count = $stmt->rowCount();
    if (!($count > 0)) {

        // if id doesn't exist 
        header('location: products.php');
        exit();
    }
} else {
    header('location: products.php');
    exit();
}

$serch_placeholder = " ابحث عن منتج";
$serch_page = 'product-list.php';
include 'admin_header.php';
?>

<style>
    .products-section {
        padding: 30px;
    }

    .product-box {
        height: 350px;
    }

    .product-box img {
        height: 100%;
        width: 100%;
    }

    .products-section div {
        margin-top: 20px;
    }

    .size {
        font-size: 22px;
        margin-right: 8px;
    }

    .info {
        font-size: 18px;
    }

    .tags {
        font-family: inherit;
        font-weight: bold;
        font-size: 20px;
        color: #5f5f5f;
        margin-top: 15px;
    }

    .product_details-container {

        margin: 30px 0;

    }

    .product-details-box {
        color: #fff;
        padding: 10px;
        border-radius: 9px;
        border: 4px solid #fff;
    }



    .product-details-title {
        color: #fff;

        font-size: 18px;
        margin: 5px;
    }

    .product-details-number {
        color: #fff;

        font-size: 25px;
        margin: 10px;
    }

    .product-details-number span {
        color: #fff;

        font-size: 20px;
    }

    h6 {
        color: #000;
    }

    body.dark .product-details-box {
        border: 4px solid #2b2b2b;
    }

    body.dark p,
    body.dark span {
        color: #cfd4da !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">

        <br>

        <div class="products-section">

            <div class="row">

                <?php

                $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute(array($id));
                $rows = $stmt->fetchAll();

                // the loop 
                foreach ($rows as $row) {

                    $images = explode(',', $row['images']);
                    unset($images[0]);

                    foreach ($images as $img) {
                        echo '

                                    <div class="product-box col-lg-4 col-sm-6 col-12">
                                    <img src="../assets/img/products/' . $img . '" class="img-fluid img-thumbnail"  alt="" />
                                    </div>
                                    <br>
                                    ';
                    }



                ?>

                    <div class="row gutters-sm main-card ">

                        <div class="col-md-12 d-none d-md-block ">
                            <div class="card mb-3 text-center">
                                <div class="card-body">

                                <?php
                                echo '
                                        <div class="row ">
                                        <div class="col-sm-2">
                                            <h6 class="mb-0">اسم المنتج</h6>
                                        </div>
                                        <div class="col-sm-9 text-start text-secondary">
                                            ' . $row['title'] . '
                                        </div>
                                        </div>

                                        <hr>

                                        <div class="row ">
                                        <div class="col-sm-2">
                                            <h6 class="mb-0">وصف المنتج</h6>
                                        </div>
                                        <div class="col-sm-9 text-start text-secondary">
                                            ' . $row['description'] . '
                                        </div>
                                        </div>

                                        <hr>

                                        <div class="row">
                                        <div class="col-sm-2">
                                            <h6 class="mb-0"> سعر القطاعي</h6>
                                        </div>
                                        <div class="col-sm-4 text-secondary">
                                        ' . $row['price']  . ' جنيه
                                        </div>
                                        <div class="col-sm-2">
                                            <h6 class="mb-0">خصم</h6>
                                        </div>
                                        <div class="col-sm-4 text-secondary">
                                        ';

                                if ($row['discount'] == 0) {
                                    echo '
                                    لا يوجد خصم
                                    ';
                                } else {
                                    echo '
                                    السعر بعد الخصم <span class="lead">' . $row['discount'] . ' جنيه </span>
                                    ';
                                }

                                echo '
                                        </div>
                                        </div>
                                        
                                        <hr>

                                        <div class="row">
                                        <div class="col-sm-2">
                                            <h6 class="mb-0"> سعر الجملة</h6>
                                        </div>
                                        <div class="col-sm-4 text-secondary">
                                        ' . $row['trade_price']  . '
                                        </div>
                                        <div class="col-sm-2">
                                            <h6 class="mb-0">الكمية المتاحة</h6>
                                        </div>
                                        <div class="col-sm-4 text-secondary">
                                        ' . $row['quantity'] . ' قطعة
                                        </div>
                                        </div>

                                        <hr>

                                        <div class="row ">
                                            <div class="col-sm-2">
                                                <h6 class="mb-0">الاقسام</h6>
                                            </div>
                                            <div class="col-sm-4 text-center text-secondary">
                                            ' . $row['category'] . '
                                            </div>
                                            <div class="col-sm-2">
                                            <h6 class="mb-0">قسم فرعي</h6>
                                        </div>
                                        <div class="col-sm-4 text-center text-secondary">
                                        ' . $row['sub_category'] . '
                                        </div>
                                        </div>

                                        <hr>

                                        <div class="row">
                                        <div class="col-sm-2">
                                            <h6 class="mb-0"> اللون</h6>
                                        </div>
                                        <div class="col-sm-4 text-secondary">
                                        ' . $row['color']  . '
                                        </div>
                                        <div class="col-sm-2">
                                            <h6 class="mb-0">المقاسات المتاحة :</h6>
                                        </div>
                                        <div class="col-sm-4 text-secondary">
                                        ';

                                $sizes = explode(',', $row['size']);

                                foreach ($sizes as $size) {
                                    echo '
                                <span class="size lead">' . $size . '</span>
                                        ';
                                }
                                echo '
                                        </div>
                                        </div>
                            ';



                                echo '
                            <hr >

                            <div class="row">
                            <div class="col-12 text-start info"> الجمل المرتبطة بهذا المنتج : </div>
                            ';

                                $tags = explode(',', $row['tags']);

                                foreach ($tags as $tag) {
                                    echo '
                                <div class="col-6 col-lg-4 tags">' . $tag . '</div>
                                ';
                                }
                                echo '
                                        </div>
                                        <hr>
                            <div class="col-12 text-center">
                            <a href="edit_product.php?id=' . $row['id'] . '"><div class="btn btn-secondary w-50">تعديل معلومات المنتج</div></a>
                            </div>
                            ';

                                $sell = $row['sells'];
                                $product_id = $row['id'];
                            }

                                ?>


                                </div>

                            </div>
                        </div>
                    </div>



            </div>
            <hr>
            <br>
            <br>

            <div class="text-center">
                <h4>فلترة حسب التاريخ</h4>
                <form action="product-details.php?id=<?php echo $product_id; ?>#statistics" method="POST">
                    <span class="lead">من</span>
                    <input id="flatpickr" name="from">
                    <span class="lead">الى</span>
                    <input id="flatpickr" name="to">
                    <input type="submit" value="تطبيق" class="btn btn-primary" name="filter_order_date">
                </form>
                <a href="product-details.php?id=<?php echo $product_id; ?>">
                    <div class="btn btn-info">الغاء الفلتر</div>
                </a>
            </div>
            <br>
            <?php

            if (isset($_POST['filter_order_date'])) {

                $form_string = filter_var($_POST['from'], FILTER_SANITIZE_STRING);
                $to_string = filter_var($_POST['to'], FILTER_SANITIZE_STRING);
                $from_date = new DateTime($form_string);
                $to_date = new DateTime($to_string);

                // if filter for one daye

                if ($from_date == $to_date) {
                    echo '<div class="product_details-container row" id="statistics">';

                    // get total money 

                    $stmt = $con->prepare("SELECT price, coins, quantity, user_key FROM orders WHERE product_id = ? AND order_date = ?");
                    $stmt->execute(array($product_id, $form_string));
                    $numbers = $stmt->fetchAll();
                    $pieces_number = 0;
                    $trade_number = 0;
                    $total_money = 0;
                    $coins = 0;
                    $total_money_trade = 0;
                    $coins_trade = 0;
                    foreach ($numbers as $number) {
                        $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                        $stmt->execute(array($number['user_key']));
                        $users = $stmt->fetchAll();

                        foreach ($users as $user) {
                            if ($user['trade'] == 0) {
                                $pieces_number = $pieces_number + ($number['quantity']);
                                $total_money = $total_money + $number['price'];
                                $coins = $coins + $number['coins'];
                            } else {
                                $trade_number = $trade_number + ($number['quantity']);
                                $total_money_trade = $total_money + $number['price'];
                                $coins_trade = $coins + $number['coins'];
                            }
                        }
                    }

                    $total_money = $total_money - $coins;
                    $total_money_trade = $total_money_trade - $coins_trade;

                    // get returns 

                    $stmt = $con->prepare("SELECT id FROM returns_order WHERE product_id = ? AND order_date = ?");
                    $stmt->execute(array($product_id, $form_string));
                    $returns = $stmt->fetchAll();

                    $returns_times = count($returns);

                    // customers who buy 

                    $stmt = $con->prepare("SELECT DISTINCT user_key FROM orders WHERE product_id = ?  AND order_date = ?");
                    $stmt->execute(array($product_id, $form_string));
                    $customers = 0;
                    $trade_customers = 0;
                    $buy_back_customers = 0;
                    $trade_buy_back_customers = 0;
                    $users = $stmt->fetchAll();

                    foreach ($users as $user) {
                        $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                        $stmt->execute(array($user['user_key']));
                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            if ($row['trade'] == 0) {
                                $customers++;
                            } else {
                                $trade_customers++;
                            }

                            // get buy back customers

                            $stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders WHERE product_id = ? AND user_key = ?  AND order_date = ?");
                            $stmt->execute(array($product_id, $user['user_key'], $form_string));

                            $buy_times = 0;
                            $trade_buy_times = 0;
                            $users_back = $stmt->fetchAll();

                            foreach ($users_back as $user_back) {
                                $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                                $stmt->execute(array($user['user_key']));
                                $rows_back = $stmt->fetchAll();

                                foreach ($rows_back as $row_back) {
                                    if ($row_back['trade'] == 0) {
                                        $buy_times++;
                                    } else {
                                        $trade_buy_times++;
                                    }
                                }
                            }
                            if ($buy_times > 1) {
                                $buy_back_customers++;
                            } elseif ($trade_buy_times > 1) {
                                $trade_buy_back_customers++;
                            }
                        }
                    }

                    // get sells 

                    $stmt = $con->prepare("SELECT id FROM orders WHERE product_id = ? AND order_date = ?");
                    $stmt->execute(array($product_id, $form_string));
                    $rows = $stmt->fetchAll();
                    $sell = count($rows);




                    echo '
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #4736c2">
                                <p class="product-details-title">عدد  الذين اشتروا المنتج (قطاعي)</p>
        
                                <p class="product-details-number">' . $customers . ' <span> مستخدم </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #22bc6c">
                                <p class="product-details-title">عدد المستخدمين الذين اشتروا المنتج اكثر من مرة (قطاعي)</p>
        
                                <p class="product-details-number">' . $buy_back_customers . '<span> مستخدم</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #6657d0">
                                <p class="product-details-title">عدد القطع التي تم بيعها (قطاعي)</p>
        
                                <p class="product-details-number">' . $pieces_number . ' <span> قطعة </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #FC8D79">
                                <p class="product-details-title">اجمالي الايرادات (قطاعي)</p>
        
                                <p class="product-details-number">' . $total_money . '<span> جنيه</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #1240c1">
                                <p class="product-details-title">عدد  الذين اشتروا المنتج (جملة)</p>
        
                                <p class="product-details-number">' . $trade_customers . ' <span> مستخدم </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #8f2e0f">
                                <p class="product-details-title">عدد المستخدمين الذين اشتروا المنتج اكثر من مرة (جملة)</p>
        
                                <p class="product-details-number">' . $trade_buy_back_customers . '<span> مستخدم</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #D057C1">
                                <p class="product-details-title">عدد القطع التي تم بيعها (جملة)</p>
        
                                <p class="product-details-number">' . $trade_number . ' <span> قطعة </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #9e431d">
                                <p class="product-details-title">اجمالي الايرادات (جملة)</p>
        
                                <p class="product-details-number">' . $total_money_trade . '<span> جنيه</span> </p>
                                </div>
            
                                <div class="product-details-box col-6 col-md-3" style="background-color: #63C6E6 ">
                                    <p class="product-details-title">عدد مرات البيع (لم يتم ارجاعها)</p>
            
                                    <p class="product-details-number">' . $sell . ' <span>مرة</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #64D1CC">
                                <p class="product-details-title">عدد الطلبات التي تم ارجاعها</p>
        
                                <p class="product-details-number">' . $returns_times . '<span> طلب</span> </p>
                                </div>
            
                            </div>';
                } elseif ($from_date > $to_date) {
                    $form_string = filter_var($_POST['to'], FILTER_SANITIZE_STRING);
                    $to_string = filter_var($_POST['from'], FILTER_SANITIZE_STRING);

                    echo '<div class="product_details-container row" id="statistics">';

                    // get total money 

                    $stmt = $con->prepare("SELECT price, coins, quantity, user_key FROM orders WHERE product_id = ? AND order_date >= ? AND order_date <= ?");
                    $stmt->execute(array($product_id, $form_string, $to_string));
                    $numbers = $stmt->fetchAll();
                    $pieces_number = 0;
                    $trade_number = 0;
                    $total_money = 0;
                    $coins = 0;
                    $total_money_trade = 0;
                    $coins_trade = 0;
                    foreach ($numbers as $number) {
                        $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                        $stmt->execute(array($number['user_key']));
                        $users = $stmt->fetchAll();

                        foreach ($users as $user) {
                            if ($user['trade'] == 0) {
                                $pieces_number = $pieces_number + ($number['quantity']);
                                $total_money = $total_money + $number['price'];
                                $coins = $coins + $number['coins'];
                            } else {
                                $trade_number = $trade_number + ($number['quantity']);
                                $total_money_trade = $total_money + $number['price'];
                                $coins_trade = $coins + $number['coins'];
                            }
                        }
                    }

                    $total_money = $total_money - $coins;
                    $total_money_trade = $total_money_trade - $coins_trade;

                    // get returns 

                    $stmt = $con->prepare("SELECT id FROM returns_order WHERE product_id = ? AND order_date >= ? AND order_date <= ?");
                    $stmt->execute(array($product_id, $form_string, $to_string));
                    $returns = $stmt->fetchAll();

                    $returns_times = count($returns);

                    // customers who buy 

                    $stmt = $con->prepare("SELECT DISTINCT user_key FROM orders WHERE product_id = ? AND order_date >= ? AND order_date <= ?");
                    $stmt->execute(array($product_id, $form_string, $to_string));
                    $customers = 0;
                    $trade_customers = 0;
                    $buy_back_customers = 0;
                    $trade_buy_back_customers = 0;
                    $users = $stmt->fetchAll();

                    foreach ($users as $user) {
                        $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                        $stmt->execute(array($user['user_key']));
                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            if ($row['trade'] == 0) {
                                $customers++;
                            } else {
                                $trade_customers++;
                            }

                            // get buy back customers

                            $stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders WHERE product_id = ? AND user_key = ? AND order_date >= ? AND order_date <= ?");
                            $stmt->execute(array($product_id, $user['user_key'], $form_string, $to_string));

                            $buy_times = 0;
                            $trade_buy_times = 0;
                            $users_back = $stmt->fetchAll();

                            foreach ($users_back as $user_back) {
                                $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                                $stmt->execute(array($user['user_key']));
                                $rows_back = $stmt->fetchAll();

                                foreach ($rows_back as $row_back) {
                                    if ($row_back['trade'] == 0) {
                                        $buy_times++;
                                    } else {
                                        $trade_buy_times++;
                                    }
                                }
                            }
                            if ($buy_times > 1) {
                                $buy_back_customers++;
                            } elseif ($trade_buy_times > 1) {
                                $trade_buy_back_customers++;
                            }
                        }
                    }

                    // get sells 

                    $stmt = $con->prepare("SELECT id FROM orders WHERE product_id = ? AND order_date >= ? AND order_date <= ?");
                    $stmt->execute(array($product_id, $form_string, $to_string));
                    $rows = $stmt->fetchAll();
                    $sell = count($rows);




                    echo '
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #4736c2">
                                <p class="product-details-title">عدد  الذين اشتروا المنتج (قطاعي)</p>
        
                                <p class="product-details-number">' . $customers . ' <span> مستخدم </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #22bc6c">
                                <p class="product-details-title">عدد المستخدمين الذين اشتروا المنتج اكثر من مرة (قطاعي)</p>
        
                                <p class="product-details-number">' . $buy_back_customers . '<span> مستخدم</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #6657d0">
                                <p class="product-details-title">عدد القطع التي تم بيعها (قطاعي)</p>
        
                                <p class="product-details-number">' . $pieces_number . ' <span> قطعة </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #FC8D79">
                                <p class="product-details-title">اجمالي الايرادات (قطاعي)</p>
        
                                <p class="product-details-number">' . $total_money . '<span> جنيه</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #1240c1">
                                <p class="product-details-title">عدد  الذين اشتروا المنتج (جملة)</p>
        
                                <p class="product-details-number">' . $trade_customers . ' <span> مستخدم </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #8f2e0f">
                                <p class="product-details-title">عدد المستخدمين الذين اشتروا المنتج اكثر من مرة (جملة)</p>
        
                                <p class="product-details-number">' . $trade_buy_back_customers . '<span> مستخدم</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #D057C1">
                                <p class="product-details-title">عدد القطع التي تم بيعها (جملة)</p>
        
                                <p class="product-details-number">' . $trade_number . ' <span> قطعة </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #9e431d">
                                <p class="product-details-title">اجمالي الايرادات (جملة)</p>
        
                                <p class="product-details-number">' . $total_money_trade . '<span> جنيه</span> </p>
                                </div>
            
                                <div class="product-details-box col-6 col-md-3" style="background-color: #63C6E6 ">
                                    <p class="product-details-title">عدد مرات البيع (لم يتم ارجاعها)</p>
            
                                    <p class="product-details-number">' . $sell . ' <span>مرة</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #64D1CC">
                                <p class="product-details-title">عدد الطلبات التي تم ارجاعها</p>
        
                                <p class="product-details-number">' . $returns_times . '<span> طلب</span> </p>
                                </div>
            
                            </div>';
                } else {
                    echo '<div class="product_details-container row" id="statistics">';

                    // get total money 

                    $stmt = $con->prepare("SELECT price, coins, quantity, user_key FROM orders WHERE product_id = ? AND order_date >= ? AND order_date <= ?");
                    $stmt->execute(array($product_id, $form_string, $to_string));
                    $numbers = $stmt->fetchAll();
                    $pieces_number = 0;
                    $trade_number = 0;
                    $total_money = 0;
                    $coins = 0;
                    $total_money_trade = 0;
                    $coins_trade = 0;
                    foreach ($numbers as $number) {
                        $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                        $stmt->execute(array($number['user_key']));
                        $users = $stmt->fetchAll();

                        foreach ($users as $user) {
                            if ($user['trade'] == 0) {
                                $pieces_number = $pieces_number + ($number['quantity']);
                                $total_money = $total_money + $number['price'];
                                $coins = $coins + $number['coins'];
                            } else {
                                $trade_number = $trade_number + ($number['quantity']);
                                $total_money_trade = $total_money + $number['price'];
                                $coins_trade = $coins + $number['coins'];
                            }
                        }
                    }

                    $total_money = $total_money - $coins;
                    $total_money_trade = $total_money_trade - $coins_trade;

                    // get returns 

                    $stmt = $con->prepare("SELECT id FROM returns_order WHERE product_id = ? AND order_date >= ? AND order_date <= ?");
                    $stmt->execute(array($product_id, $form_string, $to_string));
                    $returns = $stmt->fetchAll();

                    $returns_times = count($returns);

                    // customers who buy 

                    $stmt = $con->prepare("SELECT DISTINCT user_key FROM orders WHERE product_id = ? AND order_date >= ? AND order_date <= ?");
                    $stmt->execute(array($product_id, $form_string, $to_string));
                    $customers = 0;
                    $trade_customers = 0;
                    $buy_back_customers = 0;
                    $trade_buy_back_customers = 0;
                    $users = $stmt->fetchAll();

                    foreach ($users as $user) {
                        $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                        $stmt->execute(array($user['user_key']));
                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            if ($row['trade'] == 0) {
                                $customers++;
                            } else {
                                $trade_customers++;
                            }

                            // get buy back customers

                            $stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders WHERE product_id = ? AND user_key = ? AND order_date >= ? AND order_date <= ?");
                            $stmt->execute(array($product_id, $user['user_key'], $form_string, $to_string));

                            $buy_times = 0;
                            $trade_buy_times = 0;
                            $users_back = $stmt->fetchAll();

                            foreach ($users_back as $user_back) {
                                $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                                $stmt->execute(array($user['user_key']));
                                $rows_back = $stmt->fetchAll();

                                foreach ($rows_back as $row_back) {
                                    if ($row_back['trade'] == 0) {
                                        $buy_times++;
                                    } else {
                                        $trade_buy_times++;
                                    }
                                }
                            }
                            if ($buy_times > 1) {
                                $buy_back_customers++;
                            } elseif ($trade_buy_times > 1) {
                                $trade_buy_back_customers++;
                            }
                        }
                    }

                    // get sells 

                    $stmt = $con->prepare("SELECT id FROM orders WHERE product_id = ? AND order_date >= ? AND order_date <= ?");
                    $stmt->execute(array($product_id, $form_string, $to_string));
                    $rows = $stmt->fetchAll();
                    $sell = count($rows);




                    echo '
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #4736c2">
                                <p class="product-details-title">عدد  الذين اشتروا المنتج (قطاعي)</p>
        
                                <p class="product-details-number">' . $customers . ' <span> مستخدم </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #22bc6c">
                                <p class="product-details-title">عدد المستخدمين الذين اشتروا المنتج اكثر من مرة (قطاعي)</p>
        
                                <p class="product-details-number">' . $buy_back_customers . '<span> مستخدم</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #6657d0">
                                <p class="product-details-title">عدد القطع التي تم بيعها (قطاعي)</p>
        
                                <p class="product-details-number">' . $pieces_number . ' <span> قطعة </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #FC8D79">
                                <p class="product-details-title">اجمالي الايرادات (قطاعي)</p>
        
                                <p class="product-details-number">' . $total_money . '<span> جنيه</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #1240c1">
                                <p class="product-details-title">عدد  الذين اشتروا المنتج (جملة)</p>
        
                                <p class="product-details-number">' . $trade_customers . ' <span> مستخدم </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #8f2e0f">
                                <p class="product-details-title">عدد المستخدمين الذين اشتروا المنتج اكثر من مرة (جملة)</p>
        
                                <p class="product-details-number">' . $trade_buy_back_customers . '<span> مستخدم</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #D057C1">
                                <p class="product-details-title">عدد القطع التي تم بيعها (جملة)</p>
        
                                <p class="product-details-number">' . $trade_number . ' <span> قطعة </span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #9e431d">
                                <p class="product-details-title">اجمالي الايرادات (جملة)</p>
        
                                <p class="product-details-number">' . $total_money_trade . '<span> جنيه</span> </p>
                                </div>
            
                                <div class="product-details-box col-6 col-md-3" style="background-color: #63C6E6 ">
                                    <p class="product-details-title">عدد مرات البيع (لم يتم ارجاعها)</p>
            
                                    <p class="product-details-number">' . $sell . ' <span>مرة</span> </p>
                                </div>
    
                                <div class="product-details-box col-6 col-md-3" style="background-color: #64D1CC">
                                <p class="product-details-title">عدد الطلبات التي تم ارجاعها</p>
        
                                <p class="product-details-number">' . $returns_times . '<span> طلب</span> </p>
                                </div>
            
                            </div>';
                }
            } else {
                echo '<div class="product_details-container row" id="statistics">';

                // get total money 

                $stmt = $con->prepare("SELECT price, coins, quantity, user_key FROM orders WHERE product_id = ?");
                $stmt->execute(array($product_id));
                $numbers = $stmt->fetchAll();
                $pieces_number = 0;
                $trade_number = 0;
                $total_money = 0;
                $coins = 0;
                $total_money_trade = 0;
                $coins_trade = 0;
                foreach ($numbers as $number) {
                    $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                    $stmt->execute(array($number['user_key']));
                    $users = $stmt->fetchAll();

                    foreach ($users as $user) {
                        if ($user['trade'] == 0) {
                            $pieces_number = $pieces_number + ($number['quantity']);
                            $total_money = $total_money + $number['price'];
                            $coins = $coins + $number['coins'];
                        } else {
                            $trade_number = $trade_number + ($number['quantity']);
                            $total_money_trade = $total_money + $number['price'];
                            $coins_trade = $coins + $number['coins'];
                        }
                    }


                    // get promocode value 


                }

                $total_money = $total_money - $coins;
                $total_money_trade = $total_money_trade - $coins_trade;

                // get returns 

                $stmt = $con->prepare("SELECT id FROM returns_order WHERE product_id = ?");
                $stmt->execute(array($product_id));
                $returns = $stmt->fetchAll();

                $returns_times = count($returns);

                // customers who buy 

                $stmt = $con->prepare("SELECT DISTINCT user_key FROM orders WHERE product_id = ?");
                $stmt->execute(array($product_id));
                $customers = 0;
                $trade_customers = 0;
                $buy_back_customers = 0;
                $trade_buy_back_customers = 0;
                $users = $stmt->fetchAll();

                foreach ($users as $user) {
                    $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                    $stmt->execute(array($user['user_key']));
                    $rows = $stmt->fetchAll();

                    foreach ($rows as $row) {
                        if ($row['trade'] == 0) {
                            $customers++;
                        } else {
                            $trade_customers++;
                        }

                        // get buy back customers

                        $stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders WHERE product_id = ? AND user_key = ?");
                        $stmt->execute(array($product_id, $user['user_key']));

                        $buy_times = 0;
                        $trade_buy_times = 0;
                        $users_back = $stmt->fetchAll();

                        foreach ($users_back as $user_back) {
                            $stmt = $con->prepare("SELECT trade FROM users WHERE user_key = ?");
                            $stmt->execute(array($user['user_key']));
                            $rows_back = $stmt->fetchAll();

                            foreach ($rows_back as $row_back) {
                                if ($row_back['trade'] == 0) {
                                    $buy_times++;
                                } else {
                                    $trade_buy_times++;
                                }
                            }
                        }
                        if ($buy_times > 1) {
                            $buy_back_customers++;
                        } elseif ($trade_buy_times > 1) {
                            $trade_buy_back_customers++;
                        }
                    }
                }






                echo '

                            <div class="product-details-box col-6 col-md-3" style="background-color: #4736c2">
                            <p class="product-details-title">عدد  الذين اشتروا المنتج (قطاعي)</p>
    
                            <p class="product-details-number">' . $customers . ' <span> مستخدم </span> </p>
                            </div>

                            <div class="product-details-box col-6 col-md-3" style="background-color: #22bc6c">
                            <p class="product-details-title">عدد المستخدمين الذين اشتروا المنتج اكثر من مرة (قطاعي)</p>
    
                            <p class="product-details-number">' . $buy_back_customers . '<span> مستخدم</span> </p>
                            </div>

                            <div class="product-details-box col-6 col-md-3" style="background-color: #6657d0">
                            <p class="product-details-title">عدد القطع التي تم بيعها (قطاعي)</p>
    
                            <p class="product-details-number">' . $pieces_number . ' <span> قطعة </span> </p>
                            </div>

                            <div class="product-details-box col-6 col-md-3" style="background-color: #FC8D79">
                            <p class="product-details-title">اجمالي الايرادات (قطاعي)</p>
    
                            <p class="product-details-number">' . $total_money . '<span> جنيه</span> </p>
                            </div>

                            <div class="product-details-box col-6 col-md-3" style="background-color: #1240c1">
                            <p class="product-details-title">عدد  الذين اشتروا المنتج (جملة)</p>
    
                            <p class="product-details-number">' . $trade_customers . ' <span> مستخدم </span> </p>
                            </div>

                            <div class="product-details-box col-6 col-md-3" style="background-color: #8f2e0f">
                            <p class="product-details-title">عدد المستخدمين الذين اشتروا المنتج اكثر من مرة (جملة)</p>
    
                            <p class="product-details-number">' . $trade_buy_back_customers . '<span> مستخدم</span> </p>
                            </div>

                            <div class="product-details-box col-6 col-md-3" style="background-color: #D057C1">
                            <p class="product-details-title">عدد القطع التي تم بيعها (جملة)</p>
    
                            <p class="product-details-number">' . $trade_number . ' <span> قطعة </span> </p>
                            </div>

                            <div class="product-details-box col-6 col-md-3" style="background-color: #9e431d">
                            <p class="product-details-title">اجمالي الايرادات (جملة)</p>
    
                            <p class="product-details-number">' . $total_money_trade . '<span> جنيه</span> </p>
                            </div>
        
                            <div class="product-details-box col-6 col-md-3" style="background-color: #63C6E6 ">
                                <p class="product-details-title">عدد مرات البيع (لم يتم ارجاعها)</p>
        
                                <p class="product-details-number">' . $sell . ' <span>مرة</span> </p>
                            </div>

                            <div class="product-details-box col-6 col-md-3" style="background-color: #64D1CC">
                            <p class="product-details-title">عدد الطلبات التي تم ارجاعها</p>
    
                            <p class="product-details-number">' . $returns_times . '<span> طلب</span> </p>
                            </div>
        
                        </div>';
            }

            ?>
        </div>
    </div>
</div>
<br>
<?php include 'admin_footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    var example = flatpickr('#flatpickr');
</script>
</body>

</html>