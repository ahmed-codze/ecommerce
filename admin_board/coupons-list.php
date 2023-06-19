<?php

include '../connect.php';

// add new coupon 

if (isset($_POST['new_coupon'])) {
    $coupon = filter_var($_POST['coupon'], FILTER_SANITIZE_STRING);
    $discount = filter_var($_POST['discount'], FILTER_SANITIZE_NUMBER_INT);
    $trade = filter_var($_POST['trade'], FILTER_SANITIZE_NUMBER_INT);
    $usage_limit = filter_var($_POST['usage_limit'], FILTER_SANITIZE_NUMBER_INT);

    // check if exist 

    $stmt = $con->prepare("SELECT coupon FROM coupons WHERE coupon = ?");
    $stmt->execute(array($coupon));
    $count = $stmt->rowCount();
    if (!($count > 0)) {

        // add new coupon 
        $stmt = $con->prepare('INSERT INTO coupons (coupon, discount, trade, usage_limit) VALUES (:coupon, :discount, :trade, :usage_limit)');
        $stmt->execute(array(
            'coupon' => $coupon,
            'discount' => $discount,
            'trade' => $trade,
            'usage_limit' => $usage_limit
        ));
        header('location: coupons-list.php');
    } else {
        header('location: coupons-list.php');
        exit();
    }
}

// update coupon status

if (isset($_POST['update_coupon_status'])) {
    $coupon_id = filter_var($_POST['coupon_id'], FILTER_SANITIZE_NUMBER_INT);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);

    $stmt = $con->prepare('UPDATE coupons SET status = :status WHERE id = :id');

    $stmt->execute(array(
        'status' => $status,
        'id' => $coupon_id,
    ));
}

$serch_placeholder = " ابحث عن كوبون";
$serch_page = 'coupons-list.php';
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

    .new-coupon {
        display: none;
        margin: 30px 0;
    }

    /* Dropdown Button */
    .dropbtn {
        border-color: #D5D9D9;
        border-radius: 8px;
        color: #0F1111;
        background: #F0F2F2;
        box-shadow: 0 2px 5px 0 rgb(213 217 217 / 50%);
        width: 100%;
    }

    .dropbtn:hover,
    .dropbtn:focus {
        background-color: #fff;
    }

    .dropbtn::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
        margin-right: 10px;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #ddd
    }


    .dark .dropdown-content {
        background-color: #ddd;
    }

    .dark .dropbtn {
        border-color: #888;
        background: #888;
    }

    .show {
        display: block !important;
    }
</style>

<div class="page-body">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">كوبونات التخفيض</h1>
        <div class="dropdown w-50">
            <button onclick="myFunction()" class="dropbtn btn" style="width: 80%" type="button">
                <?php
                if (isset($_GET['arrange_type'])) {
                    echo $_GET['arrange_type'];
                } else {
                    echo 'فلترة';
                }
                ?>
            </button>
            <div id="myDropdown3" class="dropdown-content" style="width: 80%">

                <a class="dropdown-item" href="coupons-list.php?trade=0&arrange_type=قطاعي فقط">قطاعي فقط</a>
                <a class="dropdown-item" href="coupons-list.php?trade=1&arrange_type=جملة فقط">جملة فقط</a>
                <a class="dropdown-item" href="coupons-list.php?trade=2&arrange_type= جملة و قطاعي فقط"> جملة و قطاعي فقط </a>
                <a class="dropdown-item" href="coupons-list.php">عرض الكل</a>
            </div>
        </div>
        <div class="btn-secondary btn add-coupon">اضافة كوبون</div>
    </div>

    <div class="new-coupon">
        <form action="coupons-list.php" method="POST" class="row">
            <div class="col-4">
                <input class="form-control" required name="coupon" type="text" placeholder="اكتب اسم الكوبون الجديد">
            </div>
            <div class="col-4">
                <input type="number" name="discount" required placeholder="نسبة الخصم" class="form-control">
            </div>
            <div class="col-4">
                <div class="dropdown w-100">
                    <button onclick="myFunction()" class="dropbtn btn " type="button">
                        اختر النظام
                    </button>
                    <div id="myDropdown3" class="dropdown-content w-100">

                        <a class="dropdown-item" data-trade="0" href="#">قطاعي</a>
                        <a class="dropdown-item" data-trade="1" href="#">جملة</a>
                        <a class="dropdown-item" data-trade="2" href="#">جملة وقطاعي</a>

                    </div>
                </div>
            </div>
            <input type="hidden" name="trade" value="" required class="trade-input">

            <div class="col-4" style="margin-top: 10px;">
                <input type="number" name="usage_limit" required placeholder="الحد الأقصى للاستخدام" class="form-control">
            </div>


            <div class="col-4 " style="margin-top: 10px;">
                <button class="btn btn-secondary w-100" name="new_coupon" type="submit">اضافة</button>
            </div>
        </form>
        <hr>
    </div>

    <div class="card-body order-datatable">
        <table class="display" id="basic-1">
            <thead>
                <tr>
                    <th>الكوبون</th>
                    <th>نسبة الخصم</th>
                    <th> مرات الاستخدام</th>
                    <th>اجمالي المبلغ</th>
                    <th>الحد الأقصى للاستخدام</th>
                    <th>النظام</th>
                    <th>تفاصيل</th>
                    <th>الحالة (انقر لتغييرها)</th>
                </tr>
            </thead>
            <tbody>

                <?php

                if (isset($_GET['search'])) {

                    $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING);

                    //get coupons 

                    $stmt = $con->prepare("SELECT * FROM coupons WHERE coupon LIKE '%$search%' ORDER BY id DESC");
                    $stmt->execute();
                    $coupons = $stmt->fetchAll();

                    // the loop 
                    foreach ($coupons as $coupon) {
                        echo '
                
                                <tr>
                                <td>' . $coupon["coupon"] . '</td>
                                <td>' . $coupon["discount"] . '</td>';

                        $stmt = $con->prepare("SELECT price FROM orders WHERE promocode = ?");
                        $stmt->execute(array($coupon["coupon"]));
                        $pays = $stmt->fetchAll();
                        $total_pay = 0;
                        $usage_times = count($pays);
                        foreach ($pays as $pay) {
                            $total_pay = $total_pay + intval($pay['price']);
                        }

                        echo '
                                    <td>' . $usage_times . '</td>
                                    <td>' . $total_pay . '</td>';
                        if ($coupon['usage_limit'] == 0) {
                            echo '<td>لا يوجد </td>';
                        } else {
                            echo '<td>' . $coupon['usage_limit'] . ' مرة </td>';
                        }
                        if ($coupon['trade'] == 0) {
                            echo '<td class="text-info">قطاعي</td>';
                        } elseif ($coupon['trade'] == 1) {
                            echo '<td class="text-danger">جملة</td>';
                        } else {
                            echo '<td class="text-success">جملة وقطاعي</td>';
                        }
                        echo '
                                    <td><a href="coupon_details.php?id=' . $coupon["id"] . '"><div class="btn btn-primary">المزيد </div> </a></td>
                                    ';
                        if ($coupon['status'] == 1) {
                            echo '<td><div class="btn btn-success status" data-id="' . $coupon['id'] . '" data-update="0"> تم التفعيل </div></td>';
                        } else {
                            echo '<td><div class="btn btn-danger status" data-id="' . $coupon['id'] . '" data-update="1"> تم التعطيل </div></td>';
                        }
                        echo '
                                    </tr>
                                    ';
                    }
                } elseif (isset($_GET['trade'])) {
                    //get coupons 

                    $stmt = $con->prepare("SELECT * FROM coupons WHERE trade = ? ORDER BY id DESC");
                    $stmt->execute(array($_GET['trade']));
                    $coupons = $stmt->fetchAll();

                    // the loop 
                    foreach ($coupons as $coupon) {
                        echo '
                                                
                                                                <tr>
                                                                <td>' . $coupon["coupon"] . '</td>
                                                                <td>' . $coupon["discount"] . '</td>';

                        $stmt = $con->prepare("SELECT price FROM orders WHERE promocode = ?");
                        $stmt->execute(array($coupon["coupon"]));
                        $pays = $stmt->fetchAll();
                        $total_pay = 0;
                        $usage_times = count($pays);
                        foreach ($pays as $pay) {
                            $total_pay = $total_pay + intval($pay['price']);
                        }

                        echo '
                                                                    <td>' . $usage_times . '</td>
                                                                    <td>' . $total_pay . '</td>';
                        if ($coupon['usage_limit'] == 0) {
                            echo '<td>لا يوجد </td>';
                        } else {
                            echo '<td>' . $coupon['usage_limit'] . ' مرة </td>';
                        }
                        if ($coupon['trade'] == 0) {
                            echo '<td class="text-info">قطاعي</td>';
                        } elseif ($coupon['trade'] == 1) {
                            echo '<td class="text-danger">جملة</td>';
                        } else {
                            echo '<td class="text-success">جملة وقطاعي</td>';
                        }
                        echo '
                                                                    <td><a href="coupon_details.php?id=' . $coupon["id"] . '"><div class="btn btn-primary">المزيد </div> </a></td>
                                                                    
                                                                    ';
                        if ($coupon['status'] == 1) {
                            echo '<td><div class="btn btn-success status" data-id="' . $coupon['id'] . '" data-update="0"> تم التفعيل </div></td>';
                        } else {
                            echo '<td><div class="btn btn-danger status" data-id="' . $coupon['id'] . '" data-update="1"> تم التعطيل </div></td>';
                        }
                        echo '
                                                                    </tr>
                                                                    ';
                    }
                } else {
                    //get coupons 

                    $stmt = $con->prepare("SELECT * FROM coupons ORDER BY id DESC");
                    $stmt->execute();
                    $coupons = $stmt->fetchAll();

                    // the loop 
                    foreach ($coupons as $coupon) {
                        echo '
                
                                <tr>
                                <td>' . $coupon["coupon"] . '</td>
                                <td>' . $coupon["discount"] . '</td>';

                        $stmt = $con->prepare("SELECT price FROM orders WHERE promocode = ?");
                        $stmt->execute(array($coupon["coupon"]));
                        $pays = $stmt->fetchAll();
                        $total_pay = 0;
                        $usage_times = count($pays);
                        foreach ($pays as $pay) {
                            $total_pay = $total_pay + intval($pay['price']);
                        }

                        echo '
                                    <td>' . $usage_times . '</td>
                                    <td>' . $total_pay . '</td>';
                        if ($coupon['usage_limit'] == 0) {
                            echo '<td>لا يوجد </td>';
                        } else {
                            echo '<td>' . $coupon['usage_limit'] . ' مرة </td>';
                        }
                        if ($coupon['trade'] == 0) {
                            echo '<td class="text-info">قطاعي</td>';
                        } elseif ($coupon['trade'] == 1) {
                            echo '<td class="text-danger">جملة</td>';
                        } else {
                            echo '<td class="text-success">جملة وقطاعي</td>';
                        }
                        echo '
                                    <td><a href="coupon_details.php?id=' . $coupon["id"] . '"><div class="btn btn-primary">المزيد </div> </a></td>
                                    
                                    ';
                        if ($coupon['status'] == 1) {
                            echo '<td><div class="btn btn-success status" data-id="' . $coupon['id'] . '" data-update="0"> تم التفعيل </div></td>';
                        } else {
                            echo '<td><div class="btn btn-danger status" data-id="' . $coupon['id'] . '" data-update="1"> تم التعطيل </div></td>';
                        }
                        echo '
                                    </tr>
                                    ';
                    }
                }




                ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'admin_footer.php'; ?>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $('.add-coupon').click(function() {
        $('.new-coupon').show('slow');
    })

    $('.dropbtn').click(function() {
        $('.dropdown-content').not($(this).siblings('.dropdown-content')).removeClass('show');
        $(this).siblings('.dropdown-content').toggleClass('show');
    });


    // Close the dropdown menu if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

    $('.dropdown-content a').click(function() {
        $(this).parent(".dropdown-content").siblings('.dropbtn').text($(this).text());
        $(this).parent(".dropdown-content").siblings('.dropbtn').addClass('choosen');
        $('.trade-input').attr('value', $(this).data('trade'));
    })


    $('.status').click(function() {
        swal({
                title: " هل أنت متأكد من تغيير حالة الكوبون  ؟ ",
                text: " اذا قمت بالضغط على OK سيتم التأكيد",
                icon: "info",
                buttons: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.post('coupons-list.php', {
                        status: $(this).data('update'),
                        coupon_id: $(this).data('id'),
                        update_coupon_status: 1,
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