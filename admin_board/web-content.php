<?php
include '../connect.php';

// add new slider 

if (isset($_POST['new_slider'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $text = filter_var($_POST['text'], FILTER_SANITIZE_STRING);
    $link = filter_var($_POST['link'], FILTER_SANITIZE_STRING);

    $tmpFilePath = $_FILES['img']['tmp_name'];
    $new_img = $_FILES['img']['name'];

    $expload = explode('.', $new_img);
    $ext     = strtolower(end($expload));

    $allowed = array('jpg', 'png', 'jpeg', '');


    // check if is actually image 

    if (!(in_array($ext, $allowed))) {

        echo '<script> alert("only images allowed") </script>';
    } else {

        $img = '../assets/img/home-banner/' . $_FILES['img']['name'];
        move_uploaded_file($tmpFilePath, $img);

        $stmt = $con->prepare('INSERT INTO slider ( title, text, link, img) VALUES ( :title, :text, :link, :img)');
        $stmt->execute(array(
            'title' => $title,
            'text' => $text,
            'link' => $link,
            'img' => $_FILES['img']['name']
        ));
    }
    header('location: web-content.php');
    exit();
}

// delete slider 

if (isset($_POST['delete_promo_img'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $stmt = $con->prepare("DELETE FROM `images` WHERE `images`.`id` = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
}



// add image 

if (isset($_POST['new_img'])) {
    $tmpFilePath = $_FILES['img']['tmp_name'];
    $new_img = $_FILES['img']['name'];

    $expload = explode('.', $new_img);
    $ext     = strtolower(end($expload));

    $allowed = array('jpg', 'png', 'jpeg', '');


    // check if is actually image 

    if (!(in_array($ext, $allowed))) {

        echo '<script> alert("only images allowed") </script>';
    } else {

        $img = '../assets/img/promo-images/' . $_FILES['img']['name'];
        move_uploaded_file($tmpFilePath, $img);

        $stmt = $con->prepare('INSERT INTO images ( img) VALUES ( :img)');
        $stmt->execute(array(
            'img' => $_FILES['img']['name']
        ));
    }
    header('location: web-content.php');
    exit();
}

// delete promo image 

if (isset($_POST['delete_slider'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $stmt = $con->prepare("DELETE FROM `slider` WHERE `slider`.`id` = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
}

$serch_placeholder = " ابحث برقم الطلب";
$serch_page = 'orders.php';
include 'admin_header.php';
?>

<style>
    label {
        font-size: 18px;
        margin: 20px 10px 15px;
    }

    h3 {
        margin: 20px auto;
        color: var(--theme-color);
    }
</style>
<div class="page-body" dir="rtl">

    <div class="container">

        <!-- images slider -->

        <form action="web-content.php" method="POST" class="form-group" enctype="multipart/form-data">
            <div class="row">
                <div class="col-12 text-center">
                    <h3>اضافة الصور في بداية المتجر</h3>
                </div>
                <div class="col-6">
                    <label for="">اختر صورة (موصى بأبعاد 1920 * 718)</label>
                    <input type="file" name="img" class="form-control" required>
                </div>
                <div class="col-6">
                    <label for="">عنوان رئيسي</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-6">
                    <label for=""> نص توضيحي</label>
                    <input type="text" name="text" class="form-control" required>
                </div>
                <div class="col-6">
                    <label for=""> رابط </label>
                    <input type="text" name="link" class="form-control" required>
                </div>
                <div class="col-12 text-center me-auto mt-4">
                    <button class="btn btn-solid w-25" type="submit" name="new_slider">ارسال</button>
                </div>
            </div>
        </form>
        <br>
        <hr>
        <br>

        <div class="container">
            <div class="text-center">
                <h3>الصور الموجودة </h3>
            </div>
            <div class="row sliders">
                <?php
                // get slider images 

                $stmt = $con->prepare("SELECT * FROM slider");
                $stmt->execute();
                $rows = $stmt->fetchAll();

                foreach ($rows as $row) {
                    echo '
                    <div class="col-12 slider-container">
                    <div class="row">
                        <div class="col-4">
                            <img src="../assets/img/home-banner/' . $row['img'] . '" height="50px" width="100px" class="img-fluid blur-up lazyload bg-img" alt="' . $row['title'] . '">
                        </div>
                        <div class="row col-8">
                            <div class="col-12">
                                <p>' . $row['title'] . '</p>
                            </div>
                            <div class="col-12">
                                <p>' . $row['text'] . '</P>
                            </div>
                            <div class="col-12 ">
                                <a href="javascript:void(0)" class="btn btn-solid delete-slider" data-id=' . $row['id'] . ' >حذف الصورة</a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                    ';
                }
                ?>

            </div>
        </div>
        <br>
        <hr>
        <br>

        <div class="container">
            <div class="text-center">
                <h3>اضافة صور أخرى للمتجر </h3>
                <P>ستظهر الصور داخل المتجر في اماكن متفرقة يفضل أن تحتوي على عروض او نصوص توضح الميزات للمتجر</P>
            </div>
            <form action="web-content.php" method="POST" class="form-group" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-9">
                        <input type="file" name="img" class="form-control" required>
                    </div>
                    <div class="col-3">
                        <button class="btn btn-solid w-100" type="submit" name="new_img">ارسال صورة</button>
                    </div>
                </div>
            </form>
        </div>

        <br>
        <hr>
        <br>
        <div class="container">
            <div class="text-center">
                <h3>الصور الموجودة </h3>
            </div>
            <div class="row sliders">
                <?php
                // get slider images 

                $stmt = $con->prepare("SELECT * FROM images");
                $stmt->execute();
                $rows = $stmt->fetchAll();

                foreach ($rows as $row) {
                    echo '
                    <div class="col-12 promo-container">
                    <div class="row">
                        <div class="col-10">
                            <img src="../assets/img/promo-images/' . $row['img'] . '" class="img-fluid w-100 d-block blur-up lazyload bg-img" alt="">
                        </div>
                        <div class="row col-2">
                                <a href="javascript:void(0)" class="btn btn-solid delete-promo-img" style="max-height: 50px; " data-id=' . $row['id'] . ' >حذف الصورة</a>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                    ';
                }
                ?>

            </div>
        </div>
    </div>

</div>


<?php
include 'admin_footer.php';
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $('.delete-promo-img').click(function() {
        swal({
                title: "هل أنت متأكد من الحذف ؟",
                text: " اذا قمت بالضغط على OK سيتم الارجاع",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.post('web-content.php', {
                        delete_promo_img: true,
                        id: $(this).data('id')
                    }, function(data) {});
                    $(this).parentsUntil('.promo-container').hide();

                }
            });
    })

    $('.delete-slider').click(function() {
        swal({
                title: "هل أنت متأكد من الحذف ؟",
                text: " اذا قمت بالضغط على OK سيتم الارجاع",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.post('web-content.php', {
                        delete_slider: true,
                        id: $(this).data('id')
                    }, function(data) {});
                    $(this).parentsUntil('.slider-container').hide();

                }
            });
    })
</script>

</body>

</html>