<?php
include '../connect.php';

// get old info

$stmt = $con->prepare("SELECT * FROM web_info");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $old_title = $row['title'];
    $old_logo = $row['logo'];
    $old_color = $row['color'];
    $old_description = $row['description'];
    $old_slogan = $row['slogan'];
    $old_shipping = $row['shipping'];
}

$stmt = $con->prepare("SELECT * FROM connection");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $old_phone = $row['phone'];
    $old_email = $row['email'];
    $old_whatsapp = $row['whatsapp'];
    $old_address = $row['address'];
    $old_facebook = $row['facebook'];
    $old_instagram = $row['instagram'];
    $old_twitter = $row['twitter'];
}

if (isset($_POST['web-info'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $color = filter_var($_POST['color'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $slogan = filter_var($_POST['slogan'], FILTER_SANITIZE_STRING);
    $shipping = $_POST['shipping'];


    if ($_FILES['img']['tmp_name'] != '') {

        $tmpFilePath = $_FILES['img']['tmp_name'];
        $new_img = $_FILES['img']['name'];

        $expload = explode('.', $new_img);
        $ext     = strtolower(end($expload));

        $allowed = array('jpg', 'png', 'jpeg', '');


        // check if is actually image 

        if (!(in_array($ext, $allowed))) {
            echo '<script> alert("only images allowed") </script>';
            header('location: web-info.php');
            exit();
        } else {
            $img = '../assets/img/logo/' . $_FILES['img']['name'];
            move_uploaded_file($tmpFilePath, $img);
            $new_logo = $_FILES['img']['name'];
        }
    } else {
        $new_logo = $old_logo;
    }

    if ($title == '') {
        $title = $old_title;
    }
    if ($description == '') {
        $description = $old_description;
    }

    if ($color == '') {
        $color = $old_color;
    }
    if ($slogan == '') {
        $slogan = $old_slogan;
    }
    if ($shipping == '') {
        $shipping = $old_shipping;
    }

    $stmt = $con->prepare('UPDATE web_info SET title = :title, description = :desc, color = :color, logo = :logo, slogan = :slogan, shipping = :shipping');

    $stmt->execute(array(
        'title' => $title,
        'desc' => $description,
        'color' => $color,
        'logo' => $new_logo,
        'slogan' => $slogan,
        'shipping' => $shipping
    ));

    $stmt = $con->prepare('UPDATE connection 
    SET phone = :phone, email = :email, whatsapp = :whatss,
     address = :add, facebook = :face, instagram = :insta, twitter = :twit');

    $stmt->execute(array(
        ':phone' => $_POST['phone'],
        ':email' => $_POST['email'],
        ':whatss' => $_POST['whatsapp'],
        ':add' => $_POST['address'],
        ':face' => $_POST['facebook'],
        ':insta' => $_POST['insta'],
        ':twit' => $_POST['twitter'],
    ));

    header('location: web-info.php');
    exit();
}

$serch_placeholder = " ابحث برقم الطلب";
$serch_page = 'orders.php';
include 'admin_header.php';
?>
<link href="simditor/simditor.css" rel="stylesheet" type="text/css" />

<style>
    label {
        font-size: 18px;
        margin: 20px 10px 10px;
    }
</style>
<div class="page-body" dir="rtl">

    <div class="container">
        <form action="web-info.php" method="POST" class="form-group" enctype="multipart/form-data">
            <div class="row">
                <div class="col-12">
                    <label>لوجو الموقع</label>
                    <input type="file" class="form-control" name="img">
                </div>
                <div class="col-12">
                    <label>اللون </label>
                    <input type="color" class="form-control" name="color" required value="<?php echo $old_color ?>">
                </div>
                <div class="col-12">
                    <label>اسم الموقع</label>
                    <input type="text" class="form-control" name="title" required value="<?php echo $old_title ?>">
                </div>
                <div class="col-12">
                    <label>شعار الموقع</label>
                    <input type="text" class="form-control" name="slogan" required value="<?php echo $old_slogan ?>">
                </div>
                <div class="col-12">
                    <label>الوصف </label>
                    <input type="text" class="form-control" name="description" required value="<?php echo $old_description ?>">
                </div>
                <div class="col-12">
                    <label> العنوان </label>
                    <input type="text" class="form-control" name="address" value="<?php echo $old_address ?>">
                </div>
                <div class="col-12">
                    <label> تفاصيل الشحن </label>
                    <style>
                        .simditor-body p {
                            text-align: left;
                        }
                    </style>
                    <textarea name="shipping" id="editor" style="height: 120px;" placeholder="<?php echo $old_shipping; ?>" class="form-control"><?php echo $old_shipping; ?>
                    </textarea>
                </div>

                <div class="col-12">
                    <label>رقم الهاتف </label>
                    <input type="text" class="form-control" name="phone" required value="<?php echo $old_phone ?>">
                </div>
                <div class="col-12">
                    <label>الايميل </label>
                    <input type="email" class="form-control" name="email" required value="<?php echo $old_email ?>">
                </div>
                <div class="col-12">
                    <label>رقم واتساب </label>
                    <input type="text" class="form-control" name="whatsapp" value="<?php echo $old_whatsapp ?>">
                </div>
                <div class="col-12">
                    <label>فيسبوك </label>
                    <input type="text" class="form-control" name="facebook" value="<?php echo $old_facebook ?>">
                </div>
                <div class="col-12">
                    <label>انستاجرام </label>
                    <input type="text" class="form-control" name="insta" value="<?php echo $old_instagram ?>">
                </div>
                <div class="col-12">
                    <label>تويتر </label>
                    <input type="text" class="form-control" name="twitter" value="<?php echo $old_twitter ?>">
                </div>

                <div class="col-12 text-center me-auto mt-4">
                    <button class="btn btn-solid" type="submit" name="web-info">ارسال</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include 'admin_footer.php'; ?>
<script type="text/javascript" src="simditor/module.js"></script>
<script type="text/javascript" src="simditor/hotkeys.js"></script>
<script type="text/javascript" src="simditor/uploader.js"></script>
<script type="text/javascript" src="simditor/toolbar.js"></script>
<script type="text/javascript" src="simditor/simditor.js"></script>
<script>
    Simditor.locale = 'en-US';
    var editor = new Simditor({
        textarea: $('#editor'),
        toolbar: [
            'title',
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'fontScale',
            'color',
            'blockquote',
            'table',
            'link',
            'hr',
            'alignment'

        ],

    });
</script>

</body>

</html>