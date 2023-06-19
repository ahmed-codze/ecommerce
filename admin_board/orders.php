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
            <p class="text-center lead">فلترة بموعد الطلبات</p>
            <form action="orders.php" method="POST">
                <span class="lead">من</span>
                <input id="flatpickr" name="from">
                <span class="lead">الى</span>
                <input id="flatpickr" name="to">
                <input type="submit" value="بحث" class="btn btn-primary" name="filter_orders_date">
            </form>
        </div>

    </div>


    <div class="card-body order-datatable">
        <table class="display" id="basic-1">
            <thead>
                <tr class="text-center">
                    <th>الاسم</th>
                    <th>الرقم</th>
                    <th>المحافظة</th>
                    <th>النظام</th>
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

                <?php

                // filter by order date 

                if (isset($_POST['filter_orders_date'])) {

                    $form_string = filter_var($_POST['from'], FILTER_SANITIZE_STRING);
                    $to_string = filter_var($_POST['to'], FILTER_SANITIZE_STRING);
                    $from_date = new DateTime($form_string);
                    $to_date = new DateTime($to_string);

                    // if filter for one daye

                    if ($from_date == $to_date) {
                        $stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders WHERE order_date = ? ORDER BY id DESC  ");
                        $stmt->execute(array($form_string));
                        $orders = $stmt->fetchAll();

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

                            $stmt = $con->prepare("SELECT name, phone, governorate, trade FROM users WHERE user_key = ?");
                            $stmt->execute(array($user_key));
                            $rows = $stmt->fetchAll();

                            foreach ($rows as $row) {
                                $name = $row['name'];
                                $phone = $row['phone'];
                                $governorate = $row['governorate'];
                                $trade = $row['trade'];
                            }
                            echo '
                            <td>' . $name . '</td>
                            <td>' . $phone . '</td>
                            <td>' . $governorate . '</td>
                            ';
                            if ($trade == 0) {
                                echo '<td class="font-secondary">قطاعي</td>';
                            } else {
                                echo '<td class="font-primary">جملة</td>';
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
                    } elseif ($from_date > $to_date) {

                        $form_string = filter_var($_POST['to'], FILTER_SANITIZE_STRING);
                        $to_string = filter_var($_POST['from'], FILTER_SANITIZE_STRING);

                        //filter range date

                        $stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders WHERE order_date >= ? AND order_date <= ? ORDER BY id DESC  ");
                        $stmt->execute(array($form_string, $to_string));
                        $orders = $stmt->fetchAll();

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

                            $stmt = $con->prepare("SELECT name, phone, governorate, trade FROM users WHERE user_key = ?");
                            $stmt->execute(array($user_key));
                            $rows = $stmt->fetchAll();

                            foreach ($rows as $row) {
                                $name = $row['name'];
                                $phone = $row['phone'];
                                $governorate = $row['governorate'];
                                $trade = $row['trade'];
                            }
                            echo '
                            <td>' . $name . '</td>
                            <td>' . $phone . '</td>
                            <td>' . $governorate . '</td>
                            ';
                            if ($trade == 0) {
                                echo '<td class="font-secondary">قطاعي</td>';
                            } else {
                                echo '<td class="font-primary">جملة</td>';
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
                        //filter range date

                        $stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders WHERE order_date >= ? AND order_date <= ? ORDER BY id DESC  ");
                        $stmt->execute(array($form_string, $to_string));
                        $orders = $stmt->fetchAll();

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

                            $stmt = $con->prepare("SELECT name, phone, governorate, trade FROM users WHERE user_key = ?");
                            $stmt->execute(array($user_key));
                            $rows = $stmt->fetchAll();

                            foreach ($rows as $row) {
                                $name = $row['name'];
                                $phone = $row['phone'];
                                $governorate = $row['governorate'];
                                $trade = $row['trade'];
                            }
                            echo '
                            <td>' . $name . '</td>
                            <td>' . $phone . '</td>
                            <td>' . $governorate . '</td>
                            ';
                            if ($trade == 0) {
                                echo '<td class="font-secondary">قطاعي</td>';
                            } else {
                                echo '<td class="font-primary">جملة</td>';
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
                    }
                } elseif (isset($_GET['search'])) {

                    $search = filter_var($_GET['search'], FILTER_SANITIZE_NUMBER_INT);

                    $stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders WHERE total_order_number LIKE '%$search%' ORDER BY id DESC  ");
                    $stmt->execute();
                    $orders = $stmt->fetchAll();

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

                        $stmt = $con->prepare("SELECT name, phone, governorate, trade FROM users WHERE user_key = ?");
                        $stmt->execute(array($user_key));
                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            $name = $row['name'];
                            $phone = $row['phone'];
                            $governorate = $row['governorate'];
                            $trade = $row['trade'];
                        }
                        echo '
                        <td>' . $name . '</td>
                        <td>' . $phone . '</td>
                        <td>' . $governorate . '</td>
                        ';
                        if ($trade == 0) {
                            echo '<td class="font-secondary">قطاعي</td>';
                        } else {
                            echo '<td class="font-primary">جملة</td>';
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

                    //get orders 

                    $stmt = $con->prepare("SELECT DISTINCT total_order_number FROM orders ORDER BY id DESC LIMIT 40 ");
                    $stmt->execute();
                    $orders = $stmt->fetchAll();

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

                        $stmt = $con->prepare("SELECT name, phone, governorate, trade FROM users WHERE user_key = ?");
                        $stmt->execute(array($user_key));
                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            $name = $row['name'];
                            $phone = $row['phone'];
                            $governorate = $row['governorate'];
                            $trade = $row['trade'];
                        }
                        echo '
                        <td>' . $name . '</td>
                        <td>' . $phone . '</td>
                        <td>' . $governorate . '</td>
                        ';
                        if ($trade == 0) {
                            echo '<td class="font-secondary">قطاعي</td>';
                        } else {
                            echo '<td class="font-primary">جملة</td>';
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
                }

                ?>

            </tbody>
        </table>

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