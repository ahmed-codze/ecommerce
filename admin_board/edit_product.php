<?php

include '../connect.php';


// check id 

if (isset($_GET['id'])) {

    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // check if id is right

    $stmt = $con->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->execute(array($id));
    $count = $stmt->rowCount();
    if (!($count > 0)) {

        // if id doesn't exist 
        header('location: products.php');
        exit();
    }
} else {
    header('location: products.php');
    exit();
}

$stmt = $con->prepare("SELECT * FROM products WHERE id = $id");
$stmt->execute();
$rows = $stmt->fetchAll();

// the loop 
foreach ($rows as $row) {
    $title = $row['title'];
    $description = $row['description'];
    $price = $row['price'];
    $discount = $row['discount'];
    $trade_price = $row['trade_price'];
    $quantity = $row['quantity'];
    $color = $row['color'];
    $category = $row['category'];
    $sub_category = $row['sub_category'];
    $sizes = $row['size'];
    $tags = $row['tags'];
    $images = $row['images'];
}


// update product from edit_product.php page 

if (isset($_POST['edit_product'])) {

    if ($_POST['title'] != '' and $_POST['title'] !== ' ') {
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    }
    if ($_POST['description'] != '' and $_POST['description'] !== ' ') {
        $description = $_POST['description'];
    }
    if ($_POST['price'] != '' and $_POST['price'] !== ' ') {
        $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    }
    if ($_POST['discount'] != '' and $_POST['discount'] !== ' ') {
        $discount = filter_var($_POST['discount'], FILTER_SANITIZE_STRING);
    }
    if ($_POST['trade_price'] != '' and $_POST['trade_price'] !== ' ') {
        $trade_price = filter_var($_POST['trade_price'], FILTER_SANITIZE_STRING);
    }
    if ($_POST['quantity'] != '' and $_POST['quantity'] !== ' ') {
        $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_STRING);
    }
    if ($_POST['color'] != '' and $_POST['color'] !== ' ') {
        $color = filter_var($_POST['color'], FILTER_SANITIZE_STRING);
    }
    if ($_POST['category'] != '' and $_POST['category'] !== ' ' and $_POST['sub_category'] != '' and $_POST['sub_category'] !== ' ') {
        $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    }
    if ($_POST['sub_category'] != '' and $_POST['sub_category'] !== ' ') {
        $sub_category = filter_var($_POST['sub_category'], FILTER_SANITIZE_STRING);
    }
    if ($_POST['size'] != '' and $_POST['size'] !== ' ') {
        $size = filter_var($_POST['size'], FILTER_SANITIZE_STRING);
    }
    if ($_POST['tags'] != '' and $_POST['tags'] !== ' ') {
        $tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
    }



    // Count # of uploaded files in array
    $total = count($_FILES['upload']['name']);

    // Loop through each file
    for ($i = 0; $i < $total; $i++) {

        //Get the temp file path
        $tmpFilePath = $_FILES['upload']['tmp_name'][$i];

        //Make sure we have a file path
        if ($tmpFilePath != "") {
            // Setup our new file path
            $newFilePath = "../assets/img/products/" . $_FILES['upload']['name'][$i];

            //Upload the file into the temp dir

            $expload = explode('.', $_FILES['upload']['name'][$i]);
            $ext     = strtolower(end($expload));

            $allowed = array('jpg', 'png', 'jpeg', '');
            // check if is actually image 

            if (!(in_array($ext, $allowed))) {

                $new_images = $images;
            } else {

                if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                    //Handle other code here
                    $new_images .= "," . $_FILES['upload']['name'][$i];
                }
            }
        } else {
            $new_images = $images;
        }
    }



    // update product

    $stmt = $con->prepare('UPDATE products SET 
    title = :title , description = :desc, tags = :tags, price = :price, discount = :discount, trade_price = :trade, images = :img, category = :cat, sub_category = :sub, size = :size, color = :color, quantity = :quant WHERE id = :id');

    $stmt->execute(array(
        'title'             => $title,
        'desc'              => $description,
        'tags'              => $tags,
        'price'             => $price,
        'discount'          => $discount,
        'trade'             => $trade_price,
        'img'               => $new_images,
        'cat'               => $category,
        'sub'               => $sub_category,
        'size'              => $sizes,
        'color'             => $color,
        'quant'             => $quantity,
        'id'                => $id

    ));

    header('location: product-details.php?id=' . $id);
}

$serch_placeholder = " ابحث عن منتج";
$serch_page = 'product-list.php';
include 'admin_header.php';
?>

<style>
    /* Dropdown Button */
    .dropbtn {
        border-color: #D5D9D9;
        border-radius: 8px;
        color: #0F1111;
        background: #F0F2F2;
        box-shadow: 0 2px 5px 0 rgb(213 217 217 / 50%);
        width: 60%;
        margin-top: 20px;
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
        min-width: 50%;
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

    label {
        margin: 20px 0 !important;
        font-size: 18px;
        color: #5f5f5f;
    }

    .box {
        position: absolute;
        top: 60%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .box select {
        background-color: #dbdbdb;
        color: black;
        padding: 10px;
        width: 250px;
        border: none;
        font-size: 20px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        -webkit-appearance: button;
        appearance: button;
        outline: none;
    }

    .box::before {
        content: "\f13a";
        font-family: FontAwesome;
        position: absolute;
        top: 0;
        left: 0;
        width: 20%;
        height: 100%;
        text-align: center;
        font-size: 28px;
        line-height: 45px;
        color: rgba(255, 255, 255, 0.5);
        background-color: rgba(255, 255, 255, 0.1);
        pointer-events: none;
    }

    .box:hover::before {
        color: rgba(255, 255, 255, 0.6);
        background-color: rgba(255, 255, 255, 0.2);
    }

    .box select option {
        padding: 30px;
    }
</style>
<link href="simditor/simditor.css" rel="stylesheet" type="text/css" />

<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">تعديل بيانات المنتج</h1>


        </div>
        <form action="edit_product.php?id=<?php echo $_GET['id']; ?>" method="POST" class="form-group edit_product_form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>صور المنتج</label>
                    <input style="background-color: #e9ecef;" type="file" name="upload[]" id="img" class="form-control images" multiple accept="image/*">
                </div>
                <div class="col-md-6">
                    <label for="">اسم المنتج</label>
                    <input type="text" placeholder="<?php echo $title; ?>" name="title" id="title" class="form-control" reqiured="">
                </div>
                <div class="col-md-12">
                    <label for="">وصف المنتج</label>
                    <style>
                        .simditor-body p {
                            text-align: left;
                        }
                    </style>
                    <textarea name="description" id="editor" cols="30" rows="10" reqiured><?php echo $description; ?></textarea>
                </div>
                <div class="col-md-6">
                    <label>سعر القطاعي</label>
                    <input type="number" id="price" name="price" placeholder="<?php echo $price; ?>" class="form-control price" reqiured="">
                </div>
                <div class="col-md-6">
                    <label for="">السعر بعد الخصم</label>
                    <input type="number" name="discount" id="duscount" placeholder="<?php echo $discount; ?>" class="form-control" reqiured="">
                </div>
                <div class="col-md-6">
                    <label>سعر الجملة</label>
                    <input type="number" id="trade_price" name="trade_price" placeholder="<?php echo $trade_price; ?>" class="form-control price" reqiured="">
                </div>

                <div class="col-md-6">
                    <label for="">عدد القطع المتاحة</label>
                    <input type="number" name="quantity" id="quantity" placeholder="<?php echo $quantity; ?>" class=" form-control" reqiured="">
                </div>

                <label for=""> الكلمات المرتبطة بهذا المنتج افصل بين كل جملة بعلامة ( , )</label>
                <div class="col-md-12">
                    <input type="text" name="tags" id="tags" placeholder="<?php echo $tags; ?>" class=" form-control" reqiured="">
                </div>

                <div class="col-12 col-lg-6">
                    <div class="dropdown w-100">
                        <button onclick="myFunction()" class="dropbtn btn " type="button"><?php echo $category; ?> </button>
                        <div id="myDropdown1" class="dropdown-content">
                            <?php
                            $stmt = $con->prepare("SELECT * FROM prod_category");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            // the loop 
                            foreach ($rows as $row) {
                                $stmt = $con->prepare("SELECT category FROM sub_category WHERE category = ? AND status = 1");
                                $stmt->execute(array($row['category']));
                                $count = $stmt->rowCount();
                                if ($row['status'] != 0 and $count > 0) {
                                    echo '
                                                <a class="dropdown-item category-item" data-input=".category-input" href="javascript:void(0)">' . $row["category"]  . '</a>
                                                ';
                                }
                            }

                            ?>
                        </div>
                        <ul class="dropdown-menu w-100 text-end" aria-labelledby="dropdownMenuButton1">

                        </ul>
                    </div>
                    <input type="text" name="category" hidden value="<?php echo $category; ?>" class="category-input">
                </div>


                <div class="col-12 col-lg-6 sub-category-box">
                    <div class="box">
                        <select name="sub_category">
                            <?php
                            $stmt = $con->prepare("SELECT * FROM sub_category WHERE category = ?");
                            $stmt->execute(array($category));
                            $rows = $stmt->fetchAll();
                            // the loop 
                            echo '
                            <option value="' . $sub_category . '">' . $sub_category . '</option>
                            ';
                            foreach ($rows as $row) {
                                if ($row['status'] != 0) {
                                    echo '
                            <option value="' . $row['sub_category'] . '">' . $row['sub_category'] . '</option>
                            ';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-lg-12">
                    <label for=""> المقاسات المتاحة وافصل بينهم بعلامة ( , )</label>
                    <input type="text" name="size" class="form-control" placeholder="<?php echo $sizes; ?>">
                </div>



                <div class="col-12 col-lg-6">
                    <div class="dropdown w-100">
                        <button onclick="myFunction()" class="dropbtn btn " type="button"><?php echo $color; ?></button>
                        <div id="myDropdown3" class="dropdown-content">
                            <?php
                            $stmt = $con->prepare("SELECT * FROM prod_color");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            // the loop 
                            foreach ($rows as $row) {
                                if ($row['status'] != 0) {
                                    echo '
                                                <a class="dropdown-item" data-input = ".color-input" href="javascript:void(0)">' . $row["color_name"]  . '</a>
                                                ';
                                }
                            }
                            ?>
                        </div>
                        <ul class="dropdown-menu w-100 text-end" aria-labelledby="dropdownMenuButton1">

                        </ul>
                    </div>
                    <input type="text" name="color" value="<?php echo $color; ?>" hidden class="color-input">
                </div>

                <div class="col-md-12 text-center" style="margin: 20px 0 30px;">
                    <input type="submit" name="edit_product" value="تحديث البيانات" id="update" class="btn btn-primary w-50">
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

    // change category 

    $('.category-item').click(function() {
        $.post('admin_product_request.php', {
            change_category: true,
            new_category: $(this).text()
        }, function(data) {
            $('.sub-category-box').html(data);
        })
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
        $('' + $(this).data('input') + '').attr('value', $(this).text());
    })
</script>

</body>

</html>