<?php
include '../connect.php';

if (isset($_GET['key'])) {

    $key = filter_var($_GET['key'], FILTER_SANITIZE_STRING);

    // check if account is exit  

    $stmt = $con->prepare("SELECT user_key FROM users WHERE user_key = ?");
    $stmt->execute(array($key));
    $count = $stmt->rowCount();

    if ($count > 0) {

        $stmt = $con->prepare("SELECT * FROM users WHERE user_key = ?");
        $stmt->execute(array($key));
        $users = $stmt->fetchAll();

        // the loop 
        foreach ($users as $user) {
            $user_id = $user['id'];
            $name = $user['name'];
            $email = $user['email'];
            $phone = $user['phone'];
            $address1 = $user['address1'];
            $address2 = $user['address2'];
            $governorate = $user['governorate'];
            $password = $user['pass'];
            $last_buy = $user['last_buy'];
            $total_buy = $user['total_buy'];
            $coins = $user['coins'];
            $trade = $user['trade'];
        }
    } else {
        header('location: error.php');
        exit();
    }
} else {
    header('location: error.php');
    exit();
}

// chang trade system 

if (isset($_POST['change_trade'])) {
    $update_trade_number = filter_var($_POST['update_trade_number'], FILTER_SANITIZE_NUMBER_INT);
    $stmt = $con->prepare('UPDATE users SET trade = :ntrade WHERE user_key = :key');

    $stmt->execute(array(
        'ntrade' => $update_trade_number,
        'key' => $key
    ));
}




$serch_placeholder = " ابحث عميل";
$serch_page = 'user-list.php';
include 'admin_header.php';


?>

<style>

</style>

<div class="page-body">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-2">
                <h6 class="mb-0"> الاسم الكامل</h6>
            </div>
            <div class="col-sm-4 text-secondary">
                <?php echo $name; ?>
            </div>
            <div class="col-sm-2">
                <h6 class="mb-0">البريد الاكتروني</h6>
            </div>
            <div class="col-sm-4 text-secondary">
                <?php echo $email; ?>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-sm-2">
                <h6 class="mb-0">رقم الهاتف</h6>
            </div>
            <div class="col-sm-4 text-secondary">
                <?php echo $phone; ?>
            </div>
            <div class="col-sm-2">
                <h6 class="mb-0">العملات</h6>
            </div>
            <div class="col-sm-4 text-secondary">
                <?php echo $coins; ?> عملة
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-2">
                <h6 class="mb-0">اخر شراء</h6>
            </div>
            <div class="col-sm-4 text-secondary">
                <?php echo $last_buy; ?>
            </div>
            <div class="col-sm-2">
                <h6 class="mb-0">اجمالي الشراء</h6>
            </div>
            <div class="col-sm-4 text-secondary">
                <?php echo $total_buy; ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-2">
                <h6 class="mb-0">كلمة المرور</h6>
            </div>
            <div class="col-sm-4 text-secondary">
                <?php echo $password; ?>
            </div>
            <div class="col-sm-2">
                <h6 class="mb-0">المحافظة</h6>
            </div>
            <div class="col-sm-4 text-secondary">
                <?php echo $governorate; ?>
            </div>
        </div>
        <hr>

        <div class="row ">
            <div class="col-sm-2">
                <h6 class="mb-0">العنوان</h6>
            </div>
            <div class="col-sm-9 text-start text-secondary">
                <?php echo $address1; ?>
            </div>
        </div>
        <hr>
        <div class="row ">
            <div class="col-sm-2">
                <h6 class="mb-0">العنوان الاحتياطي </h6>
            </div>
            <div class="col-sm-9 text-start text-secondary">
                <?php echo $address2; ?>
            </div>
        </div>


        <hr>
        <div class="row text-center">
            <div class="col-sm-4">
                <a class="btn btn-secondary edit-profile " href="admin_edit_user.php?key=<?php echo $key ?>"> تعديل البيانات</a>
            </div>
            <div class="btn col-sm-4 btn-dark show-email-form">ارسال ايميل</div>

            <?php

            if ($trade == 0) {
                echo '
                                        <div class="col-sm-4">
                                        <div class="btn btn-primary edit-profile change-trade" data-update-trade= "1"  target="_blank" href="order_details.php?key=<?php echo $key; ?>">  تحويل الى نظام الجملة</div>
                                        </div>
                                        ';
            } else {
                echo '
                                    <div class="col-sm-4">
                                    <div class="btn btn-primary edit-profile change-trade" target="_blank" data-update-trade= "0" href="order_details.php?key=<?php echo $key; ?>">  تحويل الى نظام القطاعي</div>
                                    </div>
                                    ';
            }

            ?>

        </div>
        <div class=" text-center mt-5">
            <h3>طلبات العميل السابقة</h3>
            <span>ــــــــــــــــــــــــــــــ</span>
        </div>

        <div class="card-body order-datatable">
            <table class="display" id="basic-1">


                <?php


                $stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders WHERE user_key = ? ORDER BY id DESC LIMIT 40 ");
                $stmt->execute(array($key));
                $orders = $stmt->fetchAll();

                $count = $stmt->rowCount();

                if ($count > 0) {

                    echo '
                        <thead>
                        <tr class="text-center">
                            <th>عدد القطع</th>
                            <th>الاجمالي</th>
                            <th>تاريخ الطلب</th>
                            <th>تاريخ التسليم</th>
                            <th>الحالة</th>
                            <th>رقم الطلب</th>
                            <th>المزيد</th>
                        </tr>
                    </thead>
                    <tbody>
                        ';

                    foreach ($orders as $order) {
                        echo '<tr>';
                        $order_price = 0;
                        $quantity = 0;
                        $coins = 0;
                        $stmt = $con->prepare("SELECT * FROM orders WHERE total_order_number = ?");
                        $stmt->execute(array($order['total_order_number']));
                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            $order_price = $order_price + $row['price'];
                            $quantity = $quantity + $row['quantity'];
                            $coins = $coins + $row['coins'];
                            $user_key = $row['user_key'];
                            $status = $row['status'];
                            $shipping_fees = $row['shipping_fees'];
                            $shipping_date = $row['shipping_date'];
                            $order_date = $row['order_date'];
                            $total_order_number = $row['total_order_number'];
                        }

                        echo '
            <td>' . $quantity . '</td>
            <td>' . ($order_price - $coins + $shipping_fees) . ' جنيه </td>
            <td>' . $order_date . '</td>
            <td>' . $shipping_date . '</td>
            ';
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
            <td>' . $total_order_number . '</td>
            <td><a style="color: #34568B;" target="_blank" href="order-detail.php?order_number=' . $order['total_order_number'] . '">تفاصيل</a></td>
            </tr>';
                    }
                } else {
                    echo '<p class="lead">لا توجد أي طلبات لهذا العميل</p>';
                }
                ?>
                </tbody>
            </table>

        </div>

        <div class=" text-center mt-5">
            <h3> عمليات الارجاع السابقة</h3>
            <span>ــــــــــــــــــــــــــــــ</span>
        </div>

        <div class="card-body order-datatable">
            <table class="display" id="basic-2">


                <?php
                //get orders 

                $stmt = $con->prepare("SELECT * FROM returns_order WHERE user_key = ?  ORDER BY id DESC LIMIT 40 ");
                $stmt->execute(array($key));
                $rows = $stmt->fetchAll();
                $count = $stmt->rowCount();

                if ($count > 0) {

                    echo '
                        <thead>
                        <tr class="text-center">
                            <th>المنتج</th>
                            <th>عدد القطع</th>
                            <th>الاجمالي</th>
                            <th>تاريخ الطلب</th>
                            <th>تاريخ التسليم</th>
                            <th>تاريخ الارجاع</th>
                            <th>رقم الطلب</th>
                        </tr>
                    </thead>
                    <tbody>
                        ';
                    foreach ($rows as $row) {
                        echo '<tr>';

                        $stmt = $con->prepare("SELECT images FROM products WHERE id = ?");
                        $stmt->execute(array($row['product_id']));
                        $images_list = $stmt->fetchAll();


                        foreach ($images_list as $images) {
                            $image = explode(',', $images['images']);

                            echo '<td><img src="../assets/img/products/' . $image[1] . '" alt="" width="50px" height="60px" /></td>';
                        }

                        echo '
                        <td>' . $row['quantity'] . '</td>
                        <td>' . ($row['price'] - $row['coins']) . '</td>

                        ';

                        echo '
                        <td>' . $row['order_date'] . '</td>
                        <td>' . $row['shipping_date'] . '</td>
                        <td>' . $row['return_date'] . '</td>
                        <td>' . $row['order_number'] . '</td>
                        </tr>
                        ';
                    }
                } else {
                    echo '<p class="lead">لا توجد أي عمليات ارجاع لهذا العميل</p>';
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

                    <input type="hidden" value="<?php echo $user_id ?>" name="users_array">

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

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $page_url = $(location).attr('href');
    $('.change-trade').click(function() {
        swal({
                title: " هل أنت متأكد من " + $(this).text() + " ؟ ",
                text: " اذا قمت بالضغط على OK سيتم التأكيد",
                icon: "info",
                buttons: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.post($page_url, {
                        change_trade: true,
                        update_trade_number: $(this).data('update-trade'),
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
</script>

</body>

</html>