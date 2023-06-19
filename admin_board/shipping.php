<?php
include '../connect.php';

// add new governorate

if (isset($_POST['new_governorate'])) {

    $governorate = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
    $time = filter_var($_POST['time'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
    $free_total = filter_var($_POST['free_total'], FILTER_SANITIZE_NUMBER_INT);

    // check if exist 

    $stmt = $con->prepare("SELECT governorate FROM shipping WHERE governorate = ?");
    $stmt->execute(array($governorate));
    $count_governorate = $stmt->rowCount();
    if (!$count_governorate > 0) {

        // add governorate

        $stmt = $con->prepare('INSERT INTO shipping (governorate, time, price, free_total) VALUES (:governorate, :time, :price, :free)');
        $stmt->execute(array(
            'governorate' => $governorate,
            'time'        => $time,
            'price'       => $price,
            'free'        => $free_total
        ));

        header('location: shipping.php');
        exit();
    } else {
        header('location: shipping.php');
        exit();
    }
}


// edit governorate 

if (isset($_POST['edit_governorate'])) {
    $governorate = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $time = filter_var($_POST['time'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
    $free_total = filter_var($_POST['free_total'], FILTER_SANITIZE_NUMBER_INT);
    // check if exist 

    $stmt = $con->prepare("SELECT id FROM shipping WHERE id = ?");
    $stmt->execute(array($id));
    $count_edit_shipping = $stmt->rowCount();
    if ($count_edit_shipping > 0) {
        // update shipping

        $stmt = $con->prepare('UPDATE shipping SET governorate = :governorate , time = :time, price = :price, free_total = :free WHERE id = :id');

        $stmt->execute(array(
            'governorate' => $governorate,
            'time' => $time,
            'price' => $price,
            'free'  => $free_total,
            'id' => $id,
        ));
        header('location: shipping.php');
        exit();
    } else {
        header('location: shipping.php');
        exit();
    }
}

// delete governorate

if (isset($_GET['delete'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete if exist

    $stmt = $con->prepare("SELECT id FROM shipping WHERE id = ?");
    $stmt->execute(array($id));
    $count_delete_governorate = $stmt->rowCount();

    if ($count_delete_governorate > 0) {

        $stmt = $con->prepare("DELETE FROM `shipping` WHERE `shipping`.`id` = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        header('location: shipping.php');
        exit();
    }
}

$serch_placeholder = " ابحث برقم الطلب";
$serch_page = 'orders.php';
include 'admin_header.php';
?>

<style>
    .show {
        display: block !important;
    }

    .add-governorate-form {
        display: none;
    }
</style>

<div class="page-body">

    <div class="container-fluid">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">الشحن</h1>
            <div class="btn btn-secondary btn-lg add-governorate">اضافة محافظة جديدة</div>
        </div>
        <div class="add-governorate-form">
            <form action="shipping.php" method="POST" class="row" enctype="multipart/form-data">
                <div class="col-3">
                    <input class="form-control" required name="governorate" type="text" required placeholder="اكتب اسم المحافظة الجديدة">
                </div>
                <div class="col-3">
                    <input type="text" name="time" required placeholder="عدد الايام للتوصيل" class="form-control" required>
                </div>
                <div class="col-2">
                    <input type="number" name="price" required placeholder="مصاريف الشحن" class="form-control" required>
                </div>
                <div class="col-2">
                    <input class="form-control" name="free_total" type="number" placeholder="اقل مبلغ لشحن مجاني">
                </div>
                <div class="col-2 ">
                    <button class="btn btn-primary w-100" name="new_governorate" type="submit">اضافة</button>
                </div>
            </form>
        </div>
        <?php

        if (isset($_GET['edit']) and isset($_GET['id'])) {

            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $stmt = $con->prepare("SELECT * FROM shipping where id = ?");
            $stmt->execute(array($id));
            $rows = $stmt->fetchAll();

            // the loop 
            foreach ($rows as $row) {
                echo '
        <div>
            <form action="shipping.php" method="POST" class="row" enctype="multipart/form-data">
                <div class="col-3">
                    <input required class="form-control" required name="governorate" type="text" placeholder="اكتب اسم المحافظة الجديدة" value=' . $row['governorate'] . '>
                </div>
                <div class="col-3">
                    <input required type="text" name="time" required placeholder="عدد الايام للتوصيل" class="form-control" value=' . $row['time'] . '>
                </div>
                <div class="col-2">
                    <input required type="number" name="price" required placeholder="مصاريف الشحن" class="form-control" value=' . $row['price'] . '>
                </div>
                <div class="col-2">
                <input class="form-control" name="free_total" type="number"  placeholder="اقل مبلغ لشحن مجاني" value=' . $row['free_total'] . '>
            </div>
                <input type="hidden" name="id" required  class="form-control" value="' . $id . '" required>
                <div class="col-2 ">
                    <button class="btn btn-primary w-100" name="edit_governorate" type="submit">تعديل</button>
                </div>
            </form>
        </div>';
            }
        }

        ?>

        <br>
        <div class="card-body order-datatable">
            <table class="display" id="basic-1">
                <thead class="text-center">
                    <tr>
                        <th>المحافظة</th>
                        <th>وقت التوصيل</th>
                        <th>مصاريف الشحن</th>
                        <th>اقل مبلغ لشحن مجاني</th>
                        <th>حذف</th>
                        <th>تعديل</th>

                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php

                    $stmt = $con->prepare("SELECT * FROM shipping");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();

                    // the loop 
                    foreach ($rows as $row) {
                        echo '
            <tr>
            <td>' . $row["governorate"] . '</td>
            <td>' . $row["time"] . ' </td>
            <td>' . $row["price"] . ' جنيه</td>
            ';
                        if ($row['free_total'] == 0) {
                            echo '
                    <td>لا يوجد</td>
                    ';
                        } else {
                            echo '
            <td>' . $row["free_total"] . ' جنيه</td>
            ';
                        }
                        echo '
            <td><a href="shipping.php?delete=true&id=' . $row["id"] . '"><div class="btn btn-danger">حذف </div> </a></td>
            <td><a href="shipping.php?edit=true&id=' . $row["id"] . '"><div class="btn btn-primary">تعديل </div> </a></td>
            </tr>
            ';
                    }


                    ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

<?php include 'admin_footer.php'; ?>

<script>
    $(' .add-governorate').click(function() {
        $('.add-governorate-form').toggle('slow');
    })
</script>

</body>

</html>