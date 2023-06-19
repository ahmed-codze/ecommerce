<?php

include '../connect.php';

// add product 


$sub_category = '';

if (isset($_POST['add_product'])) {

    $title              = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $price              = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $discount           = filter_var($_POST['discount'], FILTER_SANITIZE_STRING);
    $trade_price        = filter_var($_POST['trade_price'], FILTER_SANITIZE_STRING);
    $description        = $_POST['description'];
    $quantity           = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
    $tags               = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
    $size               = filter_var($_POST['size'], FILTER_SANITIZE_STRING);
    $category           = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $sub_category           = filter_var($_POST['sub_category'], FILTER_SANITIZE_STRING);
    $color              = filter_var($_POST['color'], FILTER_SANITIZE_STRING);


    // Count # of uploaded files in array
    $total = count($_FILES['upload']['name']);
    $images = '';

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

                header('location: products.php');
                exit();
            } else {

                if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                    //Handle other code here
                    $images .= "," . $_FILES['upload']['name'][$i];
                }
            }
        }
    }

    // insert product to data base

    $stmt = $con->prepare('INSERT INTO products (title, description, tags, price, discount, trade_price, images, category, sub_category, size, color, quantity) 
                                    VALUES (:title, :desc, :tags, :price, :discount, :tprice, :img, :cat, :sub, :size, :color, :quant)');
    $stmt->execute(array(
        'title'             => $title,
        'desc'              => $description,
        'tags'              => $tags,
        'price'             => $price,
        'discount'          => $discount,
        'tprice'            => $trade_price,
        'img'               => $images,
        'cat'               => $category,
        'sub'               => $sub_category,
        'size'              => $size,
        'color'             => $color,
        'quant'             => $quantity

    ));

    header('location: add_product.php');
}
//end add product

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

    .show {
        display: block !important;
    }

    .page-body input {
        margin-top: 20px;
    }

    .dark .dropdown-content {
        background-color: #ddd;
    }

    .dark .dropbtn {
        border-color: #888;
        background: #888;
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

    label {
        font-size: 18px;
        margin: 20px;
    }
</style>
<link rel="stylesheet" type="text/css" href="simditor/simditor.css" />
<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">اضافة منتج جديد</h1>


        </div>

        <form action="add_product.php" method="POST" class="form-group add_product_form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <input style="background-color: #e9ecef;" type="file" name="upload[]" id="img" class="form-control" required multiple>
                </div>
                <div class="col-md-6">
                    <input type="text" placeholder="اكتب اسم المنتج" name="title" id="title" class="form-control" required>
                </div>
                <div class="col-md-12">

                    <label for="">اكتب وصف المنتج</label>
                    <style>
                        .simditor-body p {
                            text-align: left;
                        }
                    </style>
                    <textarea required name="description" id="editor" style="height: 120px;" class="form-control"></textarea>
                </div>
                <div class="col-md-6">
                    <input type="text" id="price" name="price" placeholder="اكتب سعر المنتج" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="discount" id="duscount" placeholder="السعر بعد الخصم ( اذا كان يوجد )" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <input type="text" id="trade_price" name="trade_price" placeholder="اكتب سعر الجملة" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <input type="number" name="quantity" id="quantity" placeholder="اكتب عدد القطع المتاحة " class=" form-control" required>
                </div>

                <div class="col-md-6">
                    <input type="text" name="tags" id="tags" placeholder=' ادخل الكلمات المرتبطة بهذا المنتج افصل بين كل جملة بعلامة ( , )' class=" form-control" reqiured>
                </div>


                <div class="col-12 col-lg-6">
                    <div class="dropdown w-100">
                        <button onclick="myFunction()" class="dropbtn btn " type="button">اللون ( اختياري) </button>
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
                    <input type="text" name="color" hidden class="color-input">
                </div>

                <div class="col-12 col-lg-12">
                    <input type="text" name="size" class="form-control" placeholder=" (اختياري) ادخل المقاسات المتاحة وافصل بينهم بعلامة ( , )">
                </div>

                <div class="col-12 col-lg-6">
                    <div class="dropdown w-100">
                        <button onclick="myFunction()" class="dropbtn btn " type="button">القسم (اختياري) </button>
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
                    <input type="text" name="category" hidden class="category-input">
                </div>

                <div class="col-12 col-lg-6 sub-category-box">

                </div>

                <div class="col-md-12 text-center" style="margin-top: 20px;">
                    <input type="submit" name="add_product" value="اضافة المنتج" id="add" class="btn btn-primary w-50">
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

    // select sub category 

    $('.category-item').click(function() {
        $.post('admin_product_request.php', {
            change_category: true,
            new_category: $(this).text()
        }, function(data) {
            $('.sub-category-box').html(data);
        })
    })
</script>
</body>

</html>