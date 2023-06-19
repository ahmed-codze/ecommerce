<?php
include '../connect.php';

// add new sub category

if (isset($_POST['new_sub_category'])) {
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $sub_category = filter_var($_POST['sub_category'], FILTER_SANITIZE_STRING);


    $tmpFilePath = $_FILES['img']['tmp_name'];
    $new_img = $_FILES['img']['name'];

    $expload = explode('.', $new_img);
    $ext     = strtolower(end($expload));

    $allowed = array('jpg', 'png', 'jpeg', '');


    // check if is actually image 

    if (!(in_array($ext, $allowed))) {

        echo '<script> alert("only images allowed") </script>';
    } else {

        // check if already exist 

        $stmt = $con->prepare("SELECT sub_category, category FROM sub_category WHERE sub_category = ? AND category = ? ");
        $stmt->execute(array($sub_category, $category));
        $count = $stmt->rowCount();
        if (!$count > 0) {
            $img = '../assets/img/categories/' . $_FILES['img']['name'];
            move_uploaded_file($tmpFilePath, $img);
            // add new sub category 
            $stmt = $con->prepare('INSERT INTO sub_category (sub_category, category, size_chart) VALUES (:sub_category, :category, :img)');
            $stmt->execute(array(
                'sub_category' => $sub_category,
                'category' => $category,
                'img' => $_FILES['img']['name']
            ));
        } else {
            header('location: category-sub.php');
            exit();
        }
        header('location: category-sub.php');
    }
}


// edit category 

if (isset($_POST['edit_sub_category'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $sub_category = filter_var($_POST['sub_category'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
    // get old category

    $stmt = $con->prepare("SELECT * FROM sub_category WHERE id = ?");
    $stmt->execute(array($id));
    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $old_category = $row['category'];
        $old_sub_category = $row['sub_category'];
        $old_status = $row['status'];
        $old_img = $row['size_chart'];
    }
    $new_img = $old_img;
    if ($_FILES['img']['size'] != 0) {

        $tmpFilePath = $_FILES['img']['tmp_name'];
        $new_img = $_FILES['img']['name'];

        $expload = explode('.', $new_img);
        $ext     = strtolower(end($expload));

        $allowed = array('jpg', 'png', 'jpeg', '');


        // check if is actually image 

        if (!(in_array($ext, $allowed))) {
            echo '<script> alert("only images allowed") </script>';
            header('location: category-sub.php');
            exit();
        } else {
            $img = '../assets/img/categories/' . $_FILES['img']['name'];
            move_uploaded_file($tmpFilePath, $img);
            $new_img = $_FILES['img']['name'];
        }
    }

    if (empty($sub_category)) {
        $sub_category = $old_sub_category;
    }

    if (empty($category)) {
        $category = $old_category;
    }
    if (empty($status)) {
        $status = $old_status;
    }

    $stmt = $con->prepare('UPDATE sub_category SET sub_category = :sub, category = :cat, status = :stat, size_chart = :img WHERE id = :id');
    $stmt->execute(array(
        'sub' => $sub_category,
        'cat' => $category,
        'stat' => $status,
        'img' => $new_img,
        'id' => $id,
    ));

    // update category from products 

    $stmt = $con->prepare('UPDATE products SET sub_category = :new_category WHERE sub_category = :old_category');

    $stmt->execute(array(
        'new_category' => $sub_category,
        'old_category' => $old_sub_category
    ));

    exit(header('location: category-sub.php'));
}



$serch_placeholder = " ابحث عن منتج";
$serch_page = 'product-list.php';
include 'admin_header.php';
?>

<style>
    .show {
        display: block !important;
    }

    .add-category-form {
        display: none;
    }

    td.category-success span {
        background-color: rgba(66, 186, 150, 0.1);
        border: 1px solid #42ba96;
        color: #42ba96;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 700;
        position: relative;
        text-transform: capitalize;
    }

    td.category-cancle span {
        background-color: rgba(255, 76, 59, 0.1);
        color: var(--theme-color);
        border: 1px solid var(--theme-color);
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 700;
        position: relative;
        text-transform: capitalize;
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
</style>

<div class="page-body">

    <div class="container-fluid">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">الأقسام</h1>
            <div class="btn btn-secondary btn-lg add-category">اضافة قسم فرعي</div>
        </div>
        <div class="add-category-form">
            <form action="category-sub.php" method="POST" class="row" enctype="multipart/form-data">
                <div class="col-4">
                    <input class="form-control" required name="sub_category" type="text" placeholder="اكتب اسم القسم الفرعي">
                </div>
                <div class="col-2">
                    <input type="file" name="img" placeholder="جدول المقاسات" class="form-control">
                </div>
                <div class="col-3">
                    <div class="dropdown w-100">
                        <button onclick="myFunction()" class="dropbtn btn w-100" type="button">
                            اختر القسم الرئيسي
                        </button>
                        <div id="myDropdown3" class="dropdown-content w-100">
                            <?php
                            $stmt = $con->prepare("SELECT distinct category FROM prod_category WHERE category != ? ");
                            $stmt->execute(array(''));
                            $rows = $stmt->fetchAll();

                            foreach ($rows as $row) {
                                echo '<a class="dropdown-item" data-category="' . $row['category'] . '" href="javascript:void(0)">' . $row['category'] . '</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <input class="form-control main-category" name="category" type="hidden" placeholder="القسم الرئيسي">
                <div class="col-3 ">
                    <button class="btn btn-primary w-100" name="new_sub_category" type="submit">اضافة</button>
                </div>
            </form>
        </div>

        <?php

        if (isset($_GET['edit'])) {
            $cat_id = filter_var($_GET['edit'], FILTER_SANITIZE_NUMBER_INT);
            $stmt = $con->prepare("SELECT * FROM sub_category WHERE id = ?");
            $stmt->execute(array($cat_id));
            $rows = $stmt->fetchAll();

            foreach ($rows as $row) {
                $sub_category = $row['sub_category'];
                $category = $row['category'];
                $status = $row['status'];
            }

            echo '
            <div class="add-category-form show">
            <form action="category-sub.php" method="POST" class="row" enctype="multipart/form-data">
                <div class="col-3">
                    <input class="form-control" required value="' . $sub_category . '" name="sub_category" type="text" placeholder=" تعديل اسم القسم الفرعي">
                </div>
                <div class="col-2">
                    <input type="file" name="img" placeholder="جدول المقاسات" class="form-control">
                </div>
                <input class="form-control category-input" name="category" type="hidden" placeholder="الحالة">

                <div class="col-3 ">
                <div class="dropdown w-100">
                <button onclick="myFunction()" class="dropbtn btn w-100" type="button">
                    ' . $category . '
                </button>
                <div id="myDropdown3" class="dropdown-content w-100" >
                ';
            $stmt = $con->prepare("SELECT category FROM sub_category");
            $stmt->execute();
            $rows = $stmt->fetchAll();

            foreach ($rows as $row) {
                echo '<a class="dropdown-item" data-category = "' . $row['category'] . '" href="javascript:void(0)">' . $row['category'] . '</a>';
            }
            echo '
                </div>
            </div>
            </div>

                <input class="form-control status-input" name="status" type="hidden" placeholder="الحالة">

                <div class="col-2 ">
                <div class="dropdown w-100">
                <button onclick="myFunction()" class="dropbtn btn w-100" type="button">
                    ';

            if ($status == 1) {
                echo 'نشط';
            } else {
                echo 'غير نشط';
            }
            echo '
                </button>
                <div id="myDropdown3" class="dropdown-content w-100" >
                    <a class="dropdown-item" data-status = "1" href="javascript:void(0)">نشط</a>
                    <a class="dropdown-item" data-status = "0" href="javascript:void(0)">غير نشط</a>
                </div>
            </div>
            </div>
            <input class="form-control" value="' . $cat_id . '" required name="id" type="hidden" >
                <div class="col-2 ">
                    <button class="btn btn-primary w-100" name="edit_sub_category" type="submit">تعديل</button>
                </div>
            </form>
        </div>
            ';
        }

        ?>

        <br>
        <div class="card-body order-datatable">
            <table class="display" id="basic-1">
                <thead>
                    <tr class="text-center">
                        <th>جدول المقاسات</th>
                        <th>القسم الفرعي</th>
                        <th>القسم الرئيسي</th>
                        <th>الحالة</th>
                        <th>خيارات</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    // get sub categories 

                    $stmt = $con->prepare("SELECT * FROM sub_category ORDER BY id DESC ");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();

                    // the loop 
                    foreach ($rows as $row) {

                        echo '
                        <tr class="text-center">
                        ';
                        if ($row['size_chart'] != '') {
                            echo '
                        <td><img src="../assets/img/categories/' . $row['size_chart'] . '" width="80px" height="55px"/></td>
                        ';
                        } else {
                            echo '<td></td>';
                        }
                        echo '
                                <td>' . $row['sub_category'] . '</td>
                                <td>' . $row["category"] . '</td>
                                ';
                        if ($row['status'] == 1) {
                            echo '
                                    <td class="category-success" data-field="status">
                                    <span>نشط</span>
                                    </td>
                                    ';
                        } else {
                            echo '
                                    <td class="category-cancle" data-field="status">
                                        <span>غير نشط</span>
                                    </td>
                                    ';
                        }
                        echo '
                                <td><a style="color: #34568B;" href="category-sub.php?edit=' . $row['id'] . '">تعديل  <i class="fa fa-edit" sub_category="Edit"></i> </a></td>
                                </tr>
                    ';
                    }


                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

</div>

<?php include 'admin_footer.php'; ?>

<script>
    $(' .add-category').click(function() {
        $('.add-category-form').toggle('slow');
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
        $('.main-category').attr('value', $(this).data('category'));
        $('.status-input').attr('value', $(this).data('status'));
        $('.category-input').attr('value', $(this).data('category'));
    })
</script>

</body>

</html>