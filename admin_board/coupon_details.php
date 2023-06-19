<?php

include '../connect.php';

// check if isset id 

if (isset($_GET['id'])) {
    // check if id is exist 
    $coupon_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $stmt = $con->prepare("SELECT * FROM coupons WHERE id = ?");
    $stmt->execute(array($coupon_id));
    $count = $stmt->rowCount();
    if ($count > 0) {
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $coupon_name = $row['coupon'];
            $coupon_discount = $row['discount'];
        }
    } else {
        header("location: error.php");
        exit();
    }
} else {
    header('location: error.php');
    exit();
}

$serch_placeholder = " ابحث عن كوبون";
$serch_page = 'coupons-list.php';
include 'admin_header.php';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

    .coupon_details-container {
        margin: 30px 0;

    }

    .coupon-details-box {
        padding: 10px;
        border-radius: 9px;
        border: 4px solid #fff;
    }

    .coupon-details-title {
        font-size: 18px;
        margin: 5px;
        color: #fff;
    }

    .coupon-details-number {
        font-size: 25px;
        margin: 10px;
        color: #fff;
    }

    .coupon-details-number span {
        font-size: 20px;
        color: #fff;
    }

    h1 span {
        font-size: 40px;
    }
</style>

<div class="page-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">كوبون التخفيض : <span style="color: #006fcc"><?Php echo $coupon_name ?></span> <sup style="color: #004c8c"><?php echo $coupon_discount; ?></sup> </h1>
            <div class="text-center">
                <p class="lead">فلترة بتاريخ الطلب</p>
                <form action="coupon_details.php?id=<?php echo $coupon_id; ?>" method="POST">
                    <span class="lead">من</span>
                    <input id="flatpickr" name="from">
                    <span class="lead">الى</span>
                    <input id="flatpickr" name="to">
                    <input type="submit" value="بحث" class="btn btn-primary" name="filter_order_date">
                </form>
            </div>
        </div>


        <?php
        // filter by order date 

        if (isset($_POST['filter_order_date'])) {

            $form_string = filter_var($_POST['from'], FILTER_SANITIZE_STRING);
            $to_string = filter_var($_POST['to'], FILTER_SANITIZE_STRING);
            $from_date = new DateTime($form_string);
            $to_date = new DateTime($to_string);

            // if filter for one daye

            if ($from_date == $to_date) {
                echo '<div class="coupon_details-container row">';

                $stmt = $con->prepare("SELECT price FROM orders WHERE promocode = ? AND order_date = ? ");
                $stmt->execute(array($coupon_name, $form_string));
                $pays = $stmt->fetchAll();
                $total_pay = 0;
                $usage_times = count($pays);
                foreach ($pays as $pay) {
                    $total_pay = $total_pay + intval($pay['price']);
                }

                $stmt = $con->prepare("SELECT id FROM returns_order WHERE promocode = ? AND order_date = ?");
                $stmt->execute(array($coupon_name, $form_string));
                $pays = $stmt->fetchAll();
                $usage_times_returns = count($pays);

                $users_array = array();

                $stmt = $con->prepare("SELECT user_key FROM orders WHERE promocode = ? AND order_date = ? ");
                $stmt->execute(array($coupon_name, $form_string));
                $users = $stmt->fetchAll();

                foreach ($users as $user) {
                    echo '<tr>';

                    if (!in_array($user['user_key'], $users_array)) {
                        array_push($users_array, $user['user_key']);
                    }
                }

                $stmt = $con->prepare("SELECT user_key FROM returns_order WHERE promocode = ? AND order_date = ?");
                $stmt->execute(array($coupon_name, $form_string));
                $users = $stmt->fetchAll();

                foreach ($users as $user) {
                    echo '<tr>';

                    if (!in_array($user['user_key'], $users_array)) {
                        array_push($users_array, $user['user_key']);
                    }
                }

                $users_count = count($users_array);

                echo '
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #63C6E6 ">
                            <p class="coupon-details-title">اجمالي الايرادات</p>
    
                            <p class="coupon-details-number">' . $total_pay . ' <span>جنيه</span> </p>
                        </div>
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #FC8D79">
                            <p class="coupon-details-title">عدد الطلبات (لم يتم ارجاعها)</p>
    
                            <p class="coupon-details-number">' . $usage_times . '<span> طلب</span> </p>
                        </div>
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #D057C1">
                            <p class="coupon-details-title">عدد المستخدمين</p>
    
                            <p class="coupon-details-number">' . $users_count . ' <span> مستخدم </span> </p>
                        </div>
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #64D1CC">
                            <p class="coupon-details-title">عدد الطلبات التي تم ارجاعها</p>
    
                            <p class="coupon-details-number">' . $usage_times_returns . '<span> طلب</span> </p>
                        </div>
    
                    </div>
    
                    <hr>
    
                    <div class="card-body order-datatable">
                    <table class="display" id="basic-1">
                            <thead>
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th>اسم العميل</th>
                                    <th>الرقم</th>
                                    <th>المحافظة</th>
                                    <th>عدد القطع</th>
                                    <th>الاجمالي</th>
                                    <th>تاريخ الطلب</th>
                                    <th>رقم الطلب</th>
                                    <th>تفاصيل</th>
                                </tr>
                            </thead>
                            <tbody>';

                //get orders

                $stmt = $con->prepare("SELECT * FROM orders WHERE promocode = ? AND order_date = ? ORDER BY id DESC");
                $stmt->execute(array($coupon_name, $form_string));
                $rows = $stmt->fetchAll();

                // the loop 
                foreach ($rows as $row) {
                    echo '<tr>';

                    // get product name 


                    $stmt = $con->prepare("SELECT title FROM products WHERE id = ?");
                    $stmt->execute(array($row['product_id']));
                    $products = $stmt->fetchAll();

                    // the loop 
                    foreach ($products as $product) {
                        echo '
                                            <td>' . $product["title"] . '</td>
                                            ';
                    }

                    // get user data 

                    $stmt = $con->prepare("SELECT phone, name, email, governorate FROM users WHERE user_key = ?");
                    $stmt->execute(array($row['user_key']));
                    $users = $stmt->fetchAll();

                    // the loop 
                    foreach ($users as $user) {
                        echo '
                                                <td>' . $user["name"] . '</td>
                                                <td>' . $user["phone"] . '</td>
                                                <td>' . $user["governorate"] . '</td>
                                            ';
                    }
                    echo '<td>' . $row['quantity'] . '</td>';
                    echo '<td>' . $row['price'] . ' جنيه </td>';
                    echo '<td>' . $row['order_date'] . '</td>';
                    echo '<td>' . $row['total_order_number'] . '</td>';
                    echo '
                                                <td><a href="order-detail.php?order_number=' . $row['total_order_number'] . '" target="_blank"><div class="btn btn-primary">تفاصيل </div> </a></td>
                                                ';
                    echo '</tr>';
                }

                echo '
                                    </tbody>
                                    </table>
                                </div>
                                    ';
            } elseif ($from_date > $to_date) {

                $form_string = filter_var($_POST['to'], FILTER_SANITIZE_STRING);
                $to_string = filter_var($_POST['from'], FILTER_SANITIZE_STRING);

                //filter range date

                echo '<div class="coupon_details-container row">';

                $stmt = $con->prepare("SELECT price FROM orders WHERE promocode = ? AND order_date >= ? AND order_date <= ?");
                $stmt->execute(array($coupon_name, $form_string, $to_string));
                $pays = $stmt->fetchAll();
                $total_pay = 0;
                $usage_times = count($pays);
                foreach ($pays as $pay) {
                    $total_pay = $total_pay + intval($pay['price']);
                }

                $stmt = $con->prepare("SELECT id FROM returns_order WHERE promocode = ? AND order_date >= ? AND order_date <= ?");
                $stmt->execute(array($coupon_name, $form_string, $to_string));
                $pays = $stmt->fetchAll();
                $usage_times_returns = count($pays);

                $users_array = array();

                $stmt = $con->prepare("SELECT user_key FROM orders WHERE promocode = ? AND order_date >= ? AND order_date <= ? ");
                $stmt->execute(array($coupon_name, $form_string, $to_string));
                $users = $stmt->fetchAll();

                foreach ($users as $user) {
                    echo '<tr>';

                    if (!in_array($user['user_key'], $users_array)) {
                        array_push($users_array, $user['user_key']);
                    }
                }

                $stmt = $con->prepare("SELECT user_key FROM returns_order WHERE promocode = ? AND order_date >= ? AND order_date <= ?");
                $stmt->execute(array($coupon_name, $form_string, $to_string));
                $users = $stmt->fetchAll();

                foreach ($users as $user) {
                    echo '<tr>';

                    if (!in_array($user['user_key'], $users_array)) {
                        array_push($users_array, $user['user_key']);
                    }
                }

                $users_count = count($users_array);

                echo '
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #63C6E6 ">
                            <p class="coupon-details-title">اجمالي الايرادات</p>
    
                            <p class="coupon-details-number">' . $total_pay . ' <span>جنيه</span> </p>
                        </div>
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #FC8D79">
                            <p class="coupon-details-title">عدد الطلبات (لم يتم ارجاعها)</p>
    
                            <p class="coupon-details-number">' . $usage_times . '<span> طلب</span> </p>
                        </div>
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #D057C1">
                            <p class="coupon-details-title">عدد المستخدمين</p>
    
                            <p class="coupon-details-number">' . $users_count . ' <span> مستخدم </span> </p>
                        </div>
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #64D1CC">
                            <p class="coupon-details-title">عدد الطلبات التي تم ارجاعها</p>
    
                            <p class="coupon-details-number">' . $usage_times_returns . '<span> طلب</span> </p>
                        </div>
    
                    </div>
    
                    <hr>
    
                    <div class="card-body order-datatable">
                    <table class="display" id="basic-1">
                            <thead>
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th>اسم العميل</th>
                                    <th>الرقم</th>
                                    <th>المحافظة</th>
                                    <th>عدد القطع</th>
                                    <th>الاجمالي</th>
                                    <th>تاريخ الطلب</th>
                                    <th>رقم الطلب</th>
                                    <th>تفاصيل</th>
                                </tr>
                            </thead>
                            <tbody>';

                //get orders

                $stmt = $con->prepare("SELECT * FROM orders WHERE promocode = ? AND order_date >= ? AND order_date <= ? ORDER BY id DESC");
                $stmt->execute(array($coupon_name, $form_string, $to_string));
                $rows = $stmt->fetchAll();

                // the loop 
                foreach ($rows as $row) {
                    echo '<tr>';

                    // get product name 


                    $stmt = $con->prepare("SELECT title FROM products WHERE id = ?");
                    $stmt->execute(array($row['product_id']));
                    $products = $stmt->fetchAll();

                    // the loop 
                    foreach ($products as $product) {
                        echo '
                                            <td>' . $product["title"] . '</td>
                                            ';
                    }

                    // get user data 

                    $stmt = $con->prepare("SELECT phone, name, email, governorate FROM users WHERE user_key = ?");
                    $stmt->execute(array($row['user_key']));
                    $users = $stmt->fetchAll();

                    // the loop 
                    foreach ($users as $user) {
                        echo '
                                                <td>' . $user["name"] . '</td>
                                                <td>' . $user["phone"] . '</td>
                                                <td>' . $user["governorate"] . '</td>
                                            ';
                    }
                    echo '<td>' . $row['quantity'] . '</td>';
                    echo '<td>' . $row['price'] . ' جنيه </td>';
                    echo '<td>' . $row['order_date'] . '</td>';
                    echo '<td>' . $row['total_order_number'] . '</td>';
                    echo '
                                                <td><a href="order-detail.php?order_number=' . $row['total_order_number'] . '" target="_blank"><div class="btn btn-primary">تفاصيل </div> </a></td>
                                                ';
                    echo '</tr>';
                }

                echo '
                                    </tbody>
                                    </table>
                                </div>
                                    ';
            } else {
                //filter range date

                echo '<div class="coupon_details-container row">';

                $stmt = $con->prepare("SELECT price FROM orders WHERE promocode = ? AND order_date >= ? AND order_date <= ?");
                $stmt->execute(array($coupon_name, $form_string, $to_string));
                $pays = $stmt->fetchAll();
                $total_pay = 0;
                $usage_times = count($pays);
                foreach ($pays as $pay) {
                    $total_pay = $total_pay + intval($pay['price']);
                }

                $stmt = $con->prepare("SELECT id FROM returns_order WHERE promocode = ? AND order_date >= ? AND order_date <= ?");
                $stmt->execute(array($coupon_name, $form_string, $to_string));
                $pays = $stmt->fetchAll();
                $usage_times_returns = count($pays);

                $users_array = array();

                $stmt = $con->prepare("SELECT user_key FROM orders WHERE promocode = ? AND order_date >= ? AND order_date <= ? ");
                $stmt->execute(array($coupon_name, $form_string, $to_string));
                $users = $stmt->fetchAll();

                foreach ($users as $user) {
                    echo '<tr>';

                    if (!in_array($user['user_key'], $users_array)) {
                        array_push($users_array, $user['user_key']);
                    }
                }

                $stmt = $con->prepare("SELECT user_key FROM returns_order WHERE promocode = ? AND order_date >= ? AND order_date <= ?");
                $stmt->execute(array($coupon_name, $form_string, $to_string));
                $users = $stmt->fetchAll();

                foreach ($users as $user) {
                    echo '<tr>';

                    if (!in_array($user['user_key'], $users_array)) {
                        array_push($users_array, $user['user_key']);
                    }
                }

                $users_count = count($users_array);

                echo '
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #63C6E6 ">
                            <p class="coupon-details-title">اجمالي الايرادات</p>
    
                            <p class="coupon-details-number">' . $total_pay . ' <span>جنيه</span> </p>
                        </div>
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #FC8D79">
                            <p class="coupon-details-title">عدد الطلبات (لم يتم ارجاعها)</p>
    
                            <p class="coupon-details-number">' . $usage_times . '<span> طلب</span> </p>
                        </div>
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #D057C1">
                            <p class="coupon-details-title">عدد المستخدمين</p>
    
                            <p class="coupon-details-number">' . $users_count . ' <span> مستخدم </span> </p>
                        </div>
    
                        <div class="coupon-details-box col-6 col-md-3" style="background-color: #64D1CC">
                            <p class="coupon-details-title">عدد الطلبات التي تم ارجاعها</p>
    
                            <p class="coupon-details-number">' . $usage_times_returns . '<span> طلب</span> </p>
                        </div>
    
                    </div>
    
                    <hr>
    
                    <div class="card-body order-datatable">
                    <table class="display" id="basic-1">
                            <thead>
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th>اسم العميل</th>
                                    <th>الرقم</th>
                                    <th>المحافظة</th>
                                    <th>عدد القطع</th>
                                    <th>الاجمالي</th>
                                    <th>تاريخ الطلب</th>
                                    <th>رقم الطلب</th>
                                    <th>تفاصيل</th>
                                </tr>
                            </thead>
                            <tbody>';

                //get orders

                $stmt = $con->prepare("SELECT * FROM orders WHERE promocode = ? AND order_date >= ? AND order_date <= ? ORDER BY id DESC");
                $stmt->execute(array($coupon_name, $form_string, $to_string));
                $rows = $stmt->fetchAll();

                // the loop 
                foreach ($rows as $row) {
                    echo '<tr>';

                    // get product name 


                    $stmt = $con->prepare("SELECT title FROM products WHERE id = ?");
                    $stmt->execute(array($row['product_id']));
                    $products = $stmt->fetchAll();

                    // the loop 
                    foreach ($products as $product) {
                        echo '
                                            <td>' . $product["title"] . '</td>
                                            ';
                    }

                    // get user data 

                    $stmt = $con->prepare("SELECT phone, name, email, governorate FROM users WHERE user_key = ?");
                    $stmt->execute(array($row['user_key']));
                    $users = $stmt->fetchAll();

                    // the loop 
                    foreach ($users as $user) {
                        echo '
                                                <td>' . $user["name"] . '</td>
                                                <td>' . $user["phone"] . '</td>
                                                <td>' . $user["governorate"] . '</td>
                                            ';
                    }
                    echo '<td>' . $row['quantity'] . '</td>';
                    echo '<td>' . $row['price'] . ' جنيه </td>';
                    echo '<td>' . $row['order_date'] . '</td>';
                    echo '<td>' . $row['total_order_number'] . '</td>';
                    echo '
                                                <td><a href="order-detail.php?order_number=' . $row['total_order_number'] . '" target="_blank"><div class="btn btn-primary">تفاصيل </div> </a></td>
                                                ';
                    echo '</tr>';
                }

                echo '
                                    </tbody>
                                    </table>
                                </div>
                                    ';
            }
        } else {


            echo '<div class="coupon_details-container row">';

            $stmt = $con->prepare("SELECT price FROM orders WHERE promocode = ?");
            $stmt->execute(array($coupon_name));
            $pays = $stmt->fetchAll();
            $total_pay = 0;
            $usage_times = count($pays);
            foreach ($pays as $pay) {
                $total_pay = $total_pay + intval($pay['price']);
            }

            $stmt = $con->prepare("SELECT id FROM returns_order WHERE promocode = ?");
            $stmt->execute(array($coupon_name));
            $pays = $stmt->fetchAll();
            $usage_times_returns = count($pays);

            $users_array = array();

            $stmt = $con->prepare("SELECT user_key FROM orders WHERE promocode = ?");
            $stmt->execute(array($coupon_name));
            $users = $stmt->fetchAll();

            foreach ($users as $user) {
                echo '<tr>';

                if (!in_array($user['user_key'], $users_array)) {
                    array_push($users_array, $user['user_key']);
                }
            }

            $stmt = $con->prepare("SELECT user_key FROM returns_order WHERE promocode = ?");
            $stmt->execute(array($coupon_name));
            $users = $stmt->fetchAll();

            foreach ($users as $user) {
                echo '<tr>';

                if (!in_array($user['user_key'], $users_array)) {
                    array_push($users_array, $user['user_key']);
                }
            }

            $users_count = count($users_array);

            echo '

                    <div class="coupon-details-box col-6 col-md-3" style="background-color: #63C6E6 ">
                        <p class="coupon-details-title">اجمالي الايرادات</p>

                        <p class="coupon-details-number">' . $total_pay . ' <span>جنيه</span> </p>
                    </div>

                    <div class="coupon-details-box col-6 col-md-3" style="background-color: #FC8D79">
                        <p class="coupon-details-title">عدد الطلبات (لم يتم ارجاعها)</p>

                        <p class="coupon-details-number">' . $usage_times . '<span> طلب</span> </p>
                    </div>

                    <div class="coupon-details-box col-6 col-md-3" style="background-color: #D057C1">
                        <p class="coupon-details-title">عدد المستخدمين</p>

                        <p class="coupon-details-number">' . $users_count . ' <span> مستخدم </span> </p>
                    </div>

                    <div class="coupon-details-box col-6 col-md-3" style="background-color: #64D1CC">
                        <p class="coupon-details-title">عدد الطلبات التي تم ارجاعها</p>

                        <p class="coupon-details-number">' . $usage_times_returns . '<span> طلب</span> </p>
                    </div>

                </div>

                <hr>

                <div class="card-body order-datatable">
                <table class="display" id="basic-1">
                        <thead>
                            <tr>
                                <th>اسم المنتج</th>
                                <th>اسم العميل</th>
                                <th>الرقم</th>
                                <th>المحافظة</th>
                                <th>عدد القطع</th>
                                <th>الاجمالي</th>
                                <th>تاريخ الطلب</th>
                                <th>رقم الطلب</th>
                                <th>تفاصيل</th>
                            </tr>
                        </thead>
                        <tbody>';

            //get orders

            $stmt = $con->prepare("SELECT * FROM orders WHERE promocode = ? ORDER BY id DESC");
            $stmt->execute(array($coupon_name));
            $rows = $stmt->fetchAll();

            // the loop 
            foreach ($rows as $row) {
                echo '<tr>';

                // get product name 


                $stmt = $con->prepare("SELECT title FROM products WHERE id = ?");
                $stmt->execute(array($row['product_id']));
                $products = $stmt->fetchAll();

                // the loop 
                foreach ($products as $product) {
                    echo '
                                        <td>' . $product["title"] . '</td>
                                        ';
                }

                // get user data 

                $stmt = $con->prepare("SELECT phone, name, email, governorate FROM users WHERE user_key = ?");
                $stmt->execute(array($row['user_key']));
                $users = $stmt->fetchAll();

                // the loop 
                foreach ($users as $user) {
                    echo '
                                            <td>' . $user["name"] . '</td>
                                            <td>' . $user["phone"] . '</td>
                                            <td>' . $user["governorate"] . '</td>
                                        ';
                }
                echo '<td>' . $row['quantity'] . '</td>';
                echo '<td>' . $row['price'] . ' جنيه </td>';
                echo '<td>' . $row['order_date'] . '</td>';
                echo '<td>' . $row['total_order_number'] . '</td>';
                echo '
                                            <td><a href="order-detail.php?order_number=' . $row['total_order_number'] . '" target="_blank"><div class="btn btn-primary">تفاصيل </div> </a></td>
                                            ';
                echo '</tr>';
            }

            echo '
                                </tbody>
                                </table>
                            </div>
                                ';
        }
        ?>

    </div>
</div>

<?php include 'admin_footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    var example = flatpickr('#flatpickr');
</script>
</body>

</html>