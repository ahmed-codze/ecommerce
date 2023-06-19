<?php



if (isset($_GET['order_number'])) {
    $order_number = filter_var($_GET['order_number'], FILTER_SANITIZE_NUMBER_INT);
    include '../connect.php';

    // check if order number is exist 

    $stmt = $con->prepare("SELECT total_order_number FROM orders WHERE total_order_number = ?");
    $stmt->execute(array($order_number));
    $count = $stmt->rowCount();
    if (!$count > 0) {
        header("location: index.php");
        exit();
    }
} else {
    header('location: error.php');
    exit();
}

if (isset($_GET['confirm_order'])) {
    $stmt = $con->prepare('UPDATE orders SET status = :status WHERE total_order_number = :number');

    $stmt->execute(array(
        'status' => $_GET['confirm_order'],
        'number' => $order_number

    ));
    if ($_GET['confirm_order'] == 4) {
        // update delivery date 


        $today = date('Y-m-d');

        $stmt = $con->prepare('UPDATE orders SET shipping_date = :new_date WHERE total_order_number = :order_number');

        $stmt->execute(array(
            'new_date' => $today,
            'order_number' => $order_number
        ));
    }
    header('location: order-detail.php?order_number=' . $order_number);
    exit();
}


// delete product from the order 

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

// return one product from the order 

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
                'order_number' => $row['total_order_number']
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

// return all order 

if (isset($_POST['return_all_order'])) {


    $stmt = $con->prepare("SELECT * FROM orders WHERE total_order_number = ?");
    $stmt->execute(array($order_number));
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
                'order_number' => $row['total_order_number']
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

            $stmt = $con->prepare("DELETE FROM orders WHERE total_order_number = ? ");
            $stmt->execute(array(
                $order_number
            ));
        }


        exit();
    }
}

$serch_placeholder = " ابحث برقم الطلب";
$serch_page = 'orders.php';
include 'admin_header.php';

?>

<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <i data-feather="archive"></i>
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>تفاصيل الطلب
                            <small>Multikart Admin panel</small>
                        </h3>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ol class="breadcrumb pull-right">
                        <li class="breadcrumb-item">
                            <a href="index.php">
                                <i data-feather="home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">القوائم</li>
                        <li class="breadcrumb-item active">تفاصيل الطلب</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <?php
            echo '
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="bg-inner cart-section order-details-table">
                            <div class="row g-4">
                                <div class="col-xl-8">
                                    <div class="card-details-title">
                                        <h3>رقم الطلب <span>#' . $order_number . '</span></h3>
                                        <br>

                                        ';
            $stmt = $con->prepare("SELECT status FROM orders WHERE total_order_number = ?");
            $stmt->execute(array($order_number));
            $rows = $stmt->fetchAll();
            $sub_total = 0;
            foreach ($rows as $row) {
                $status = $row['status'];
            }
            if ($status == 0) {
                echo '<a href="order-detail.php?order_number=' . $order_number .  '&confirm_order=1"><button type="button" class="btn btn-primary confirm-order">تأكيد الطلب</button></a>';
            } elseif ($status == 1) {
                echo '<a href="order-detail.php?order_number=' . $order_number .  '&confirm_order=2"><button type="button" class="btn btn-secondary confirm-order">تجهيز الطلب</button></a>';
            } elseif ($status == 2) {
                echo '<a href="order-detail.php?order_number=' . $order_number .  '&confirm_order=3"><button type="button" class="btn btn-secondary confirm-order">شحن الطلب</button></a>';
            } elseif ($status == 3) {
                echo '<a href="order-detail.php?order_number=' . $order_number .  '&confirm_order=4"><button type="button" class="btn btn-success confirm-order">توصيل الطلب</button></a>';
            } else {
                echo '<span class="font-danger">تم توصيل الطلب</span>';
            }

            if ($status == 4) {
                echo '&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-danger return-all-order">ارجاع الطلب</button>';
            }

            echo '
                                    </div>

                                    <div class="table-responsive table-details">
                                        <table class="table cart-table table-borderless">
                                            <thead>
                                                <tr>
                                                    <th colspan="4">المنتجات</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                
                ';

            $stmt = $con->prepare("SELECT * FROM orders WHERE total_order_number = ?");
            $stmt->execute(array($order_number));
            $rows = $stmt->fetchAll();
            $sub_total = 0;
            $coins = 0;
            foreach ($rows as $row) {

                echo '
                <tr class="table-order">
                ';
                $stmt = $con->prepare("SELECT images, title FROM products WHERE id = ?");
                $stmt->execute(array($row['product_id']));
                $products = $stmt->fetchAll();

                foreach ($products as $product) {
                    $images = explode(',', $product['images']);
                    echo '
                    <td>
                    <a href="javascript:void(0)">
                        <img src="../assets/img/products/' . $images[1] . '" class="img-fluid blur-up lazyload" alt="">
                    </a>
                </td>
                <td>
                    <p>اسم المنتج</p>
                    <h5>' . $product['title'] . '</h5>
                </td>
                    ';
                }
                $product_price = $row['price'];
                if ($row['coins'] != 0) {
                    $product_price = $product_price - intval($row['coins']);
                }
                echo '
                <td>
                    <p>الكمية</p>
                    <h5>' . $row['quantity'] . '</h5>
                </td>
                <td>
                    <p>اجمالي السعر</p>
                    <h5>' . $product_price . '</h5>
                </td>
                ';

                if ($status < 4) {
                    echo '<td><button type="button" class="btn btn-danger delete-order" data-id=' . $row['id'] . '>حذف المنتج</button></td>';
                } else {
                    echo '<td><button type="button" class="btn btn-danger return-order" data-id=' . $row['id'] . '>ارجاع المنتج</button></td>';
                }


                echo '
                    </tr>
                ';

                $sub_total = $sub_total + $product_price;
                $shipping_fees = $row['shipping_fees'];
                $order_date = $row['order_date'];
                $shipping_date = $row['shipping_date'];
                $shipping_address = $row['address'];
                $coupon_name = $row['promocode'];
                $coins = $coins + $row['coins'];

                // get client data 

                $stmt = $con->prepare("SELECT name, phone, email, address1, address2, trade, governorate FROM users WHERE user_key = ?");
                $stmt->execute(array($row['user_key']));
                $users = $stmt->fetchAll();

                foreach ($users as $user) {
                    $client_name = $user['name'];
                    $client_phone = $user['phone'];
                    $client_email = $user['email'];
                    $client_address1 = $user['address1'];
                    $client_address2 = $user['address2'];
                    $client_governorate = $user['governorate'];
                    if ($user['trade'] == 0) {
                        $client_trade = 'قطاعي';
                    } else {
                        $client_trade = 'جملة';
                    }
                }
            }

            echo '

                                            </tbody>

                                            <tfoot>

                                            ';

            if ($coupon_name != 0) {
                $stmt = $con->prepare("SELECT discount FROM coupons WHERE coupon = ? ");
                $stmt->execute(array($coupon_name));
                $coupons = $stmt->fetchAll();

                foreach ($coupons as $coupon) {
                    $coupon_discount = $coupon['discount'];
                }
                echo '
                                                <tr class="table-order">

                                                <td colspan="3">
                                                    <h5> كوبون الخصم </h5>
                                                </td>
                                                <td>
                                                <h4 class="text-uppercase font-secondary">' . $coupon_name . ' <sup>%' . $coupon_discount . ' </sup>  </h4>
                                            </td>
                                            </tr>
                                                ';
            }

            if ($coins != 0) {
                echo '
                <tr class="table-order">

                <td colspan="3">
                    <h5> العملات  </h5>
                </td>
                <td>
                <h4 class="text-uppercase font-secondary">' . $coins . ' </h4>
            </td>
            </tr>
                ';
            }

            echo '

                                                <tr class="table-order">
                                                    <td colspan="3">
                                                        <h5>الاجمالي الفرعي :</h5>
                                                    </td>
                                                    <td>
                                                        <h4>' . $sub_total . ' جنيه </h4>
                                                    </td>
                                                </tr>

                                                <tr class="table-order">
                                                    <td colspan="3">
                                                        <h5>مصاريف الشحن :</h5>
                                                    </td>
                                                    <td>
                                                        <h4>' . $shipping_fees . ' جنيه </h4>
                                                    </td>
                                                </tr>

                                                <tr class="table-order">
                                                    <td colspan="3">
                                                        <h4 class="theme-color fw-bold">الإجمالي :</h4>
                                                    </td>
                                                    <td>
                                                        <h4 class="theme-color fw-bold">' . ($shipping_fees + $sub_total) . ' جنيه </h4>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="order-success">
                                                <h4>ملخص</h4>
                                                <ul class="order-details">
                                                    <li> تاريخ الطلب :  ' . $order_date . '</li>
                                                    <li>تاريخ التسليم : ' . $shipping_date . ' </li>
                                                    <li> اجمالي الطلب : ' . ($shipping_fees + $sub_total) . ' جنيه </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="order-success">
                                                <h4>بيانات العميل</h4>
                                                <ul class="order-details">
                                                <li> الاسم :  ' . $client_name . '</li>
                                                <li> رقم الهاتف:  ' . $client_phone . '</li>
                                                <li> الايميل:  ' . $client_email . '</li>
                                                <li> عنوان 1 :  ' . $client_address1 . '</li>
                                                <li> عنوان 2:  ' . $client_address2 . '</li>
                                                <li> النظام:  ' . $client_trade . '</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="order-success">
                                                <h4>shipping address</h4>
                                                <ul class="order-details">
                                                    <li>' . $client_governorate . '</li>
                                                    <li>' . $shipping_address . '</li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <a href="#"><div class="btn btn-primary">جميع طلبات العميل</div><a>
                        </div>
                        <!-- section end -->
                    </div>
                </div>
            </div>
            ';
            ?>
            <ol class="progtrckr">
                <li class=" order-status" data-status="1">
                    <h5>تأكيد الطلب</h5>
                </li>
                <li class="order-status" data-status="2">
                    <h5>تجهيز الطلب</h5>
                </li>
                <li class="order-status" data-status="3">
                    <h5>شحن الطلب</h5>
                </li>
                <li class="order-status" data-status="4">
                    <h5>توصيل الطلب</h5>
                </li>
                <!-- progtrckr-todo  progtrckr-done -->
            </ol>
        </div>
    </div>
    <!-- Container-fluid Ends-->
</div>

<?php
include 'admin_footer.php';
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $page_url = $(location).attr('href');
    $.each($('.order-status'), function() {
        if ($(this).data('status') <= <?php echo intval($status); ?>) {
            $(this).addClass('progtrckr-done');
        } else {
            $(this).addClass('progtrckr-todo');
        }
    })


    // delete one product from the order 

    $(".delete-order").click(function() {
        swal({
                title: " متأكد من حذف هذا المنتج من الطلب ؟",
                text: " اذا قمت بالضغط على OK سيتم الحذف",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $(this).parentsUntil('tbody').hide();
                    swal("تم حذف المنتج من الطلب", {
                        icon: "success",
                    });
                    $.post($page_url, {
                        delete_order: true,
                        order_id: $(this).data('id'),
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

    // return one product from the order
    $(".return-order").click(function() {
        swal({
                title: " متأكد من ارجاع هذا المنتج ؟",
                text: " اذا قمت بالضغط على OK سيتم الارجاع",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $(this).parentsUntil('tbody').hide();
                    swal("تم ارجاع المنتج من الطلب", {
                        icon: "success",
                    });
                    $.post($page_url, {
                        return_order: true,
                        order_id: $(this).data('id'),
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

    // return all the order 

    $('.return-all-order').click(function() {
        swal({
                title: "هل أنت متأكد من ارجاع الطلب كامل ؟",
                text: " اذا قمت بالضغط على OK سيتم الارجاع",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {

                    $.post($page_url, {
                        return_all_order: true,
                    }, function() {
                        window.location.replace("index.php");
                    });

                }
            });
    })
</script>

</body>

</html>