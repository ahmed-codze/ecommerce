<?php
include '../connect.php';
$serch_placeholder = " ابحث برقم الطلب";
$serch_page = 'orders.php';
include 'admin_header.php';
?>

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
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="page-body" dir="rtl">
    <!-- Container-fluid starts-->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <p class="text-center lead">فلترة بموعد الاضافة</p>
            <form action="admin-cart.php" method="POST">
                <span class="lead">من</span>
                <input id="flatpickr" name="from">
                <span class="lead">الى</span>
                <input id="flatpickr" name="to">
                <input type="submit" value="بحث" class="btn btn-primary" name="filter_date">
            </form>
        </div>

        <div class="btn btn-dark show-email-form">ارسال رسالة</div>


    </div>



    <div class="card-body order-datatable">
        <table class="display" id="basic-1">
            <thead>
                <tr class="text-center">
                    <th>الاسم</th>
                    <th>الرقم</th>
                    <th>الايميل</th>
                    <th>عدد القطع</th>
                    <th>الاجمالي</th>
                    <th>النظام</th>
                    <th>موعدالاضافة</th>
                    <th>المزيد</th>
                </tr>
            </thead>
            <tbody>

                <?php

                $users_id = array();

                // filter by order date 

                if (isset($_POST['filter_date'])) {

                    $form_string = filter_var($_POST['from'], FILTER_SANITIZE_STRING);
                    $to_string = filter_var($_POST['to'], FILTER_SANITIZE_STRING);
                    $from_date = new DateTime($form_string);
                    $to_date = new DateTime($to_string);

                    // if filter for one daye

                    if ($from_date == $to_date) {
                        $users_array = array();

                        $stmt = $con->prepare("SELECT * FROM cart WHERE adding_date = ?");
                        $stmt->execute(array($form_string));
                        $rows = $stmt->fetchAll();

                        // the loop 
                        foreach ($rows as $row) {
                            // get user data 

                            $stmt = $con->prepare("SELECT phone, name, email, id FROM users WHERE user_key = ?");
                            $stmt->execute(array($row['user_key']));
                            $users = $stmt->fetchAll();

                            // the loop 
                            foreach ($users as $user) {
                                if (!in_array($row['user_key'], $users_array)) {
                                    echo '
        
                                    <tr>
                                    <td>' . $user["name"] . '</td>
                                    <td>' . $user["phone"] . '</td>
                                    <td>' . $user["email"] . '</td>

                                  ';
                                    // get number of pieces

                                    $stmt = $con->prepare("SELECT quantity, product_id, adding_date FROM cart WHERE user_key = ?");
                                    $stmt->execute(array($row['user_key']));
                                    $numbers = $stmt->fetchAll();
                                    $pieces_number = 0;
                                    $total_money = 0;
                                    foreach ($numbers as $number) {
                                        $pieces_number = $pieces_number + ($number['quantity']);

                                        // get product price 

                                        $stmt = $con->prepare("SELECT price, discount FROM products WHERE id = ?");
                                        $stmt->execute(array($number['product_id']));
                                        $price = $stmt->fetchAll();

                                        // the loop 
                                        foreach ($price as $p) {

                                            if ($p['discount'] != 0) {
                                                $total_money = $total_money + ($p['discount'] * $number['quantity']);
                                            } else {
                                                $total_money = $total_money + $p['price'];
                                            }
                                        }
                                    }
                                    echo '<td>' . $pieces_number . '</td>';
                                    echo '<td>' . $total_money . '</td>';
                                    echo '<td>' . $number['adding_date'] . '</td>';
                                    echo '
                                        <td><a href="cart_details.php?key=' . $row["user_key"] . '"><div class="btn btn-primary">تفاصيل </div> </a></td>
                                        ';
                                    echo '</tr>';
                                    array_push($users_array, $row['user_key']);
                                    array_push($users_id, $user['id']);
                                }
                            }
                        }
                    } elseif ($from_date > $to_date) {

                        $form_string = filter_var($_POST['to'], FILTER_SANITIZE_STRING);
                        $to_string = filter_var($_POST['from'], FILTER_SANITIZE_STRING);

                        //filter range date

                        $users_array = array();

                        $stmt = $con->prepare("SELECT * FROM cart WHERE adding_date >= ? AND adding_date <= ? ");
                        $stmt->execute(array($form_string, $to_string));
                        $rows = $stmt->fetchAll();

                        // the loop 
                        foreach ($rows as $row) {
                            // get user data 

                            $stmt = $con->prepare("SELECT phone, name, email, id FROM users WHERE user_key = ?");
                            $stmt->execute(array($row['user_key']));
                            $users = $stmt->fetchAll();

                            // the loop 
                            foreach ($users as $user) {
                                if (!in_array($row['user_key'], $users_array)) {
                                    echo '
                                    <tr>

                                    <td>' . $user["name"] . '</td>
                                    <td>' . $user["phone"] . '</td>
                                    <td>' . $user["email"] . '</td>

                                  ';
                                    // get number of pieces

                                    $stmt = $con->prepare("SELECT quantity, product_id, adding_date FROM cart WHERE user_key = ?");
                                    $stmt->execute(array($row['user_key']));
                                    $numbers = $stmt->fetchAll();
                                    $pieces_number = 0;
                                    $total_money = 0;
                                    foreach ($numbers as $number) {
                                        $pieces_number = $pieces_number + ($number['quantity']);

                                        // get product price 

                                        $stmt = $con->prepare("SELECT price, discount FROM products WHERE id = ?");
                                        $stmt->execute(array($number['product_id']));
                                        $price = $stmt->fetchAll();

                                        // the loop 
                                        foreach ($price as $p) {

                                            if ($p['discount'] != 0) {
                                                $total_money = $total_money + ($p['discount'] * $number['quantity']);
                                            } else {
                                                $total_money = $total_money + $p['price'];
                                            }
                                        }
                                    }
                                    echo '<td>' . $pieces_number . '</td>';
                                    echo '<td>' . $total_money . '</td>';
                                    echo '<td>' . $number['adding_date'] . '</td>';
                                    echo '
                                        <td><a href="cart_details.php?key=' . $row["user_key"] . '"><div class="btn btn-primary">تفاصيل </div> </a></td>
                                        ';
                                    echo '</tr>';
                                    array_push($users_array, $row['user_key']);
                                    array_push($users_id, $user['id']);
                                }
                            }
                        }
                    } else {
                        //filter range date

                        $users_array = array();

                        $stmt = $con->prepare("SELECT * FROM cart WHERE adding_date >= ? AND adding_date <= ? ");
                        $stmt->execute(array($form_string, $to_string));
                        $rows = $stmt->fetchAll();

                        // the loop 
                        foreach ($rows as $row) {
                            // get user data 

                            $stmt = $con->prepare("SELECT phone, name, email, id FROM users WHERE user_key = ?");
                            $stmt->execute(array($row['user_key']));
                            $users = $stmt->fetchAll();

                            // the loop 
                            foreach ($users as $user) {
                                if (!in_array($row['user_key'], $users_array)) {
                                    echo '
                                    <tr>
                            
                                                                        <td>' . $user["name"] . '</td>
                                                                        <td>' . $user["phone"] . '</td>
                                                                        <td>' . $user["email"] . '</td>
                            
                                                                      ';
                                    // get number of pieces

                                    $stmt = $con->prepare("SELECT quantity, product_id, adding_date FROM cart WHERE user_key = ?");
                                    $stmt->execute(array($row['user_key']));
                                    $numbers = $stmt->fetchAll();
                                    $pieces_number = 0;
                                    $total_money = 0;
                                    foreach ($numbers as $number) {
                                        $pieces_number = $pieces_number + ($number['quantity']);

                                        // get product price 

                                        $stmt = $con->prepare("SELECT price, discount FROM products WHERE id = ?");
                                        $stmt->execute(array($number['product_id']));
                                        $price = $stmt->fetchAll();

                                        // the loop 
                                        foreach ($price as $p) {

                                            if ($p['discount'] != 0) {
                                                $total_money = $total_money + ($p['discount'] * $number['quantity']);
                                            } else {
                                                $total_money = $total_money + $p['price'];
                                            }
                                        }
                                    }
                                    echo '<td>' . $pieces_number . '</td>';
                                    echo '<td>' . $total_money . '</td>';
                                    echo '<td>' . $number['adding_date'] . '</td>';
                                    echo '
                                                                            <td><a href="cart_details.php?key=' . $row["user_key"] . '"><div class="btn btn-primary">تفاصيل </div> </a></td>
                                                                            ';
                                    echo '</tr>';
                                    array_push($users_array, $row['user_key']);
                                    array_push($users_id, $user['id']);
                                }
                            }
                        }
                    }
                } else {
                    //get cart 

                    $users_array = array();

                    $stmt = $con->prepare("SELECT * FROM cart");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();

                    // the loop 
                    foreach ($rows as $row) {
                        // get user data 

                        $stmt = $con->prepare("SELECT phone, name, email, trade, id FROM users WHERE user_key = ?");
                        $stmt->execute(array($row['user_key']));
                        $users = $stmt->fetchAll();

                        // the loop 
                        foreach ($users as $user) {
                            if (!in_array($row['user_key'], $users_array)) {
                                $trade = $user['trade'];
                                echo '
                                <tr>

                                            <td>' . $user["name"] . '</td>
                                            <td>' . $user["phone"] . '</td>
                                            <td>' . $user["email"] . '</td>

                                          ';
                                // get number of pieces

                                $stmt = $con->prepare("SELECT quantity, product_id, adding_date FROM cart WHERE user_key = ?");
                                $stmt->execute(array($row['user_key']));
                                $numbers = $stmt->fetchAll();
                                $pieces_number = 0;
                                $total_money = 0;
                                foreach ($numbers as $number) {
                                    $pieces_number = $pieces_number + ($number['quantity']);

                                    // get product price 

                                    $stmt = $con->prepare("SELECT price, discount, trade_price FROM products WHERE id = ?");
                                    $stmt->execute(array($number['product_id']));
                                    $price = $stmt->fetchAll();

                                    // the loop 
                                    foreach ($price as $p) {
                                        if ($trade == 1) {
                                            $total_money = $total_money + ($p['trade_price'] * $number['quantity']);
                                        } elseif ($p['discount'] != 0) {
                                            $total_money = $total_money + ($p['discount'] * $number['quantity']);
                                        } else {
                                            $total_money = $total_money + $p['price'];
                                        }
                                    }
                                }
                                echo '<td>' . $pieces_number . '</td>
                                <td>' . $total_money . '</td>';
                                if ($trade == 0) {
                                    echo '<td class="font-secondary">قطاعي</td>';
                                } else {
                                    echo '<td class="font-primary">جملة</td>';
                                }
                                echo '<td>' . $number['adding_date'] . '</td>';
                                echo '
                                        <td><a href="cart_details.php?key=' . $row["user_key"] . '"><div class="btn btn-primary">تفاصيل </div> </a></td>
                                    ';
                                echo '</tr>';
                                array_push($users_array, $row['user_key']);
                                array_push($users_id, $user['id']);
                            }
                        }
                    }
                }

                ?>

            </tbody>
        </table>

    </div>


    <div class="email-form">
        <div class="row">
            <form action="send-email.php" method="POST">

                <div class="col-12">
                    <p for="subject">عنوان الايميل</p>
                    <input id="subject" type="text" name="subject" class="form-control">
                </div>

                <div class="col-12">
                    <p for="body">محتوى الرسالة</p>
                    <style>
                        .simditor-body p {
                            text-align: left;
                        }
                    </style>
                    <textarea dir="ltr" required name="body" id="editor" style="height: 120px;" class="form-control"></textarea>
                </div>


                <?php
                $users_array_id = implode(' ', $users_id);
                ?>

                <input type="hidden" value="<?php echo $users_array_id ?>" name="users_array">

                <div class="col-12">
                    <br>
                    <br>
                    <button class="btn btn-solid" type="submit">ارسال</button>
                </div>

            </form>
        </div>
        <span class="close-email-form close">X</span>
    </div>

</div>


</div>

<?php include 'admin_footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    var example = flatpickr('#flatpickr');
</script>
</body>

</html>