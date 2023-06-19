<?php
include '../connect.php';
$serch_placeholder = " ابحث برقم الطلب";
$serch_page = 'returns.php';
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
            <p class="text-center lead">فلترة بموعد الارجاع</p>
            <form action="returns.php" method="POST">
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
                    <th>المنتج</th>
                    <th>الاسم</th>
                    <th>الرقم</th>
                    <th>النظام</th>
                    <th>عدد القطع</th>
                    <th>الاجمالي</th>
                    <th>تاريخ الطلب</th>
                    <th>تاريخ التسليم</th>
                    <th>تاريخ الارجاع</th>
                    <th>رقم الطلب</th>
                    <th>تفاصيل العميل</th>
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
                        $stmt = $con->prepare("SELECT * FROM returns_order WHERE order_date = ? ORDER BY id DESC  ");
                        $stmt->execute(array($form_string));
                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            echo '<tr>';

                            $stmt = $con->prepare("SELECT images FROM products WHERE id = ?");
                            $stmt->execute(array($row['product_id']));
                            $images_list = $stmt->fetchAll();


                            foreach ($images_list as $images) {
                                $image = explode(',', $images['images']);

                                echo '<td><img src="../assets/img/products/' . $image[1] . '" alt="" width="50px" height="60px" /></td>';
                            }

                            $stmt = $con->prepare("SELECT name, phone, trade FROM users WHERE user_key = ?");
                            $stmt->execute(array($row['user_key']));
                            $users = $stmt->fetchAll();

                            foreach ($users as $user) {
                                $trade = $user['trade'];
                                $name = $user['name'];
                                $phone = $user['phone'];
                            }
                            echo '
                            <td>' . $name . '</td>
                            <td>' . $phone . '</td>
                            ';
                            if ($trade == 0) {
                                echo '<td class="font-secondary">قطاعي</td>';
                            } else {
                                echo '<td class="font-primary">جملة</td>';
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
                            <td><a style="color: #34568B;" target="_blank" href="user_details.php?key=' . $row['user_key'] . '">تفاصيل</a></td>
                            </tr>
                            ';
                        }
                    } elseif ($from_date > $to_date) {

                        $form_string = filter_var($_POST['to'], FILTER_SANITIZE_STRING);
                        $to_string = filter_var($_POST['from'], FILTER_SANITIZE_STRING);

                        //filter range date

                        $stmt = $con->prepare("SELECT * FROM returns_order WHERE return_date >= ? AND return_date <= ? ORDER BY id DESC  ");
                        $stmt->execute(array($form_string, $to_string));
                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            echo '<tr>';

                            $stmt = $con->prepare("SELECT images FROM products WHERE id = ?");
                            $stmt->execute(array($row['product_id']));
                            $images_list = $stmt->fetchAll();


                            foreach ($images_list as $images) {
                                $image = explode(',', $images['images']);

                                echo '<td><img src="../assets/img/products/' . $image[1] . '" alt="" width="50px" height="60px" /></td>';
                            }

                            $stmt = $con->prepare("SELECT name, phone, trade FROM users WHERE user_key = ?");
                            $stmt->execute(array($row['user_key']));
                            $users = $stmt->fetchAll();

                            foreach ($users as $user) {
                                $trade = $user['trade'];
                                $name = $user['name'];
                                $phone = $user['phone'];
                            }
                            echo '
                            <td>' . $name . '</td>
                            <td>' . $phone . '</td>
                            ';
                            if ($trade == 0) {
                                echo '<td class="font-secondary">قطاعي</td>';
                            } else {
                                echo '<td class="font-primary">جملة</td>';
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
                            <td><a style="color: #34568B;" target="_blank" href="user_details.php?key=' . $row['user_key'] . '">تفاصيل</a></td>
                            </tr>
                            ';
                        }
                    } else {
                        //filter range date

                        $stmt = $con->prepare("SELECT * FROM returns_order WHERE return_date >= ? AND return_date <= ? ORDER BY id DESC ");
                        $stmt->execute(array($form_string, $to_string));
                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            echo '<tr>';

                            $stmt = $con->prepare("SELECT images FROM products WHERE id = ?");
                            $stmt->execute(array($row['product_id']));
                            $images_list = $stmt->fetchAll();


                            foreach ($images_list as $images) {
                                $image = explode(',', $images['images']);

                                echo '<td><img src="../assets/img/products/' . $image[1] . '" alt="" width="50px" height="60px" /></td>';
                            }

                            $stmt = $con->prepare("SELECT name, phone, trade FROM users WHERE user_key = ?");
                            $stmt->execute(array($row['user_key']));
                            $users = $stmt->fetchAll();

                            foreach ($users as $user) {
                                $trade = $user['trade'];
                                $name = $user['name'];
                                $phone = $user['phone'];
                            }
                            echo '
                            <td>' . $name . '</td>
                            <td>' . $phone . '</td>
                            ';
                            if ($trade == 0) {
                                echo '<td class="font-secondary">قطاعي</td>';
                            } else {
                                echo '<td class="font-primary">جملة</td>';
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
                            <td><a style="color: #34568B;" target="_blank" href="user_details.php?key=' . $row['user_key'] . '">تفاصيل</a></td>
                            </tr>
                            ';
                        }
                    }
                } elseif (isset($_GET['search'])) {

                    $search = filter_var($_GET['search'], FILTER_SANITIZE_NUMBER_INT);

                    //get orders 

                    $stmt = $con->prepare("SELECT * FROM returns_order WHERE order_number LIKE '%$search%' ORDER BY id DESC  ");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();


                    foreach ($rows as $row) {
                        echo '<tr>';

                        $stmt = $con->prepare("SELECT images FROM products WHERE id = ?");
                        $stmt->execute(array($row['product_id']));
                        $images_list = $stmt->fetchAll();


                        foreach ($images_list as $images) {
                            $image = explode(',', $images['images']);

                            echo '<td><img src="../assets/img/products/' . $image[1] . '" alt="" width="50px" height="60px" /></td>';
                        }

                        $stmt = $con->prepare("SELECT name, phone, trade FROM users WHERE user_key = ?");
                        $stmt->execute(array($row['user_key']));
                        $users = $stmt->fetchAll();

                        foreach ($users as $user) {
                            $trade = $user['trade'];
                            $name = $user['name'];
                            $phone = $user['phone'];
                        }
                        echo '
                        <td>' . $name . '</td>
                        <td>' . $phone . '</td>
                        ';
                        if ($trade == 0) {
                            echo '<td class="font-secondary">قطاعي</td>';
                        } else {
                            echo '<td class="font-primary">جملة</td>';
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
                        <td><a style="color: #34568B;" target="_blank" href="user_details.php?key=' . $row['user_key'] . '">تفاصيل</a></td>
                        </tr>
                        ';
                    }
                } else {

                    //get orders 

                    $stmt = $con->prepare("SELECT * FROM returns_order ORDER BY id DESC LIMIT 40 ");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();


                    foreach ($rows as $row) {
                        echo '<tr>';

                        $stmt = $con->prepare("SELECT images FROM products WHERE id = ?");
                        $stmt->execute(array($row['product_id']));
                        $images_list = $stmt->fetchAll();


                        foreach ($images_list as $images) {
                            $image = explode(',', $images['images']);

                            echo '<td><img src="../assets/img/products/' . $image[1] . '" alt="" width="50px" height="60px" /></td>';
                        }

                        $stmt = $con->prepare("SELECT name, phone, trade FROM users WHERE user_key = ?");
                        $stmt->execute(array($row['user_key']));
                        $users = $stmt->fetchAll();

                        foreach ($users as $user) {
                            $trade = $user['trade'];
                            $name = $user['name'];
                            $phone = $user['phone'];
                        }
                        echo '
                        <td>' . $name . '</td>
                        <td>' . $phone . '</td>
                        ';
                        if ($trade == 0) {
                            echo '<td class="font-secondary">قطاعي</td>';
                        } else {
                            echo '<td class="font-primary">جملة</td>';
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
                        <td><a style="color: #34568B;" target="_blank" href="user_details.php?key=' . $row['user_key'] . '">تفاصيل</a></td>
                        </tr>
                        ';
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