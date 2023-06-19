<?php
include '../connect.php';

// add new category

if (isset($_POST['new_category'])) {
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);

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


        $stmt = $con->prepare("SELECT category FROM prod_category WHERE category = ?");
        $stmt->execute(array($category));
        $count = $stmt->rowCount();
        if (!($count > 0)) {
            $img = '../assets/img/categories/' . $_FILES['img']['name'];
            move_uploaded_file($tmpFilePath, $img);

            $stmt = $con->prepare('INSERT INTO prod_category ( category, img) VALUES ( :category, :img)');
            $stmt->execute(array(
                'category' => $category,
                'img' => $_FILES['img']['name']
            ));
        }
    }
}


// edit category 

if (isset($_POST['edit_category'])) {
    $cat_id = filter_var($_POST['cat_id'], FILTER_SANITIZE_NUMBER_INT);
    $cat_name = filter_var($_POST['cat_name'], FILTER_SANITIZE_STRING);
    $cat_status = filter_var($_POST['cat_status'], FILTER_SANITIZE_NUMBER_INT);
    // get old category

    $stmt = $con->prepare("SELECT category, img, status FROM prod_category WHERE id = ?");
    $stmt->execute(array($cat_id));
    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $old_name = $row['category'];
        $old_img = $row['img'];
        $old_status = $row['status'];
    }

    if ($_FILES['img']['tmp_name'] != '') {

        $tmpFilePath = $_FILES['img']['tmp_name'];
        $new_img = $_FILES['img']['name'];

        $expload = explode('.', $new_img);
        $ext     = strtolower(end($expload));

        $allowed = array('jpg', 'png', 'jpeg', '');


        // check if is actually image 

        if (!(in_array($ext, $allowed))) {
            echo '<script> alert("only images allowed") </script>';
            header('location: category.php');
            exit();
        } else {
            $img = '../assets/img/categories/' . $_FILES['img']['name'];
            move_uploaded_file($tmpFilePath, $img);
            $new_img = $_FILES['img']['name'];
        }
    } else {
        $new_img = $old_img;
    }

    if ($cat_name == '') {
        $cat_name = $old_name;
    }
    if ($cat_status == '') {
        $cat_status = $old_status;
    }

    $stmt = $con->prepare('UPDATE prod_category SET category = :nname , status = :stat, img = :img WHERE id = :id');

    $stmt->execute(array(
        'nname' => $cat_name,
        'stat' => $cat_status,
        'img' => $new_img,
        'id' => $cat_id,
    ));

    // update category from products 

    $stmt = $con->prepare('UPDATE products SET category = :new_category WHERE category = :old_category');

    $stmt->execute(array(
        'new_category' => $cat_name,
        'old_category' => $old_name
    ));

    // update category from sub categories 

    $stmt = $con->prepare('UPDATE sub_category SET category = :new_category WHERE category = :old_category');

    $stmt->execute(array(
        'new_category' => $cat_name,
        'old_category' => $old_name
    ));
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
            <div class="btn btn-secondary btn-lg add-category">اضافة قسم جديد</div>
        </div>
        <div class="add-category-form">
            <form action="category.php" method="POST" class="row" enctype="multipart/form-data">
                <div class="col-5">
                    <input class="form-control" required name="category" type="text" placeholder="اكتب اسم القسم الجديد">
                </div>
                <div class="col-4">
                    <input type="file" name="img" required placeholder="صورة القسم" class="form-control">
                </div>
                <div class="col-3 ">
                    <button class="btn btn-primary w-100" name="new_category" type="submit">اضافة</button>
                </div>
            </form>
        </div>

        <?php

        if (isset($_GET['edit'])) {
            $cat_id = filter_var($_GET['edit'], FILTER_SANITIZE_NUMBER_INT);
            $stmt = $con->prepare("SELECT category, status FROM prod_category WHERE id = ?");
            $stmt->execute(array($cat_id));
            $rows = $stmt->fetchAll();

            foreach ($rows as $row) {
                $cat_name = $row['category'];
                $cat_status = $row['status'];
            }

            echo '
            <div class="add-category-form show">
            <form action="category.php" method="POST" class="row" enctype="multipart/form-data">
                <div class="col-3">
                    <input class="form-control" required value="' . $cat_name . '" name="cat_name" type="text" placeholder="تعديل اسم القسم">
                </div>
                <div class="col-3">
                    <input type="file" name="img" placeholder="صورة القسم" class="form-control">
                </div>
                <input class="form-control status-input" name="cat_status" type="hidden" placeholder="الحالة">

                <div class="col-3 ">
                <div class="dropdown w-100">
                <button onclick="myFunction()" class="dropbtn btn w-100" type="button">
                    ';

            if ($cat_status == 1) {
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
            <input class="form-control" value="' . $cat_id . '" required name="cat_id" type="hidden" >
                <div class="col-3 ">
                    <button class="btn btn-primary w-100" name="edit_category" type="submit">تعديل</button>
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
                        <th>الصورة</th>
                        <th>القسم</th>
                        <th>الحالة</th>
                        <th>خيارات</th>

                    </tr>
                </thead>
                <tbody>
                    <?php

                    $stmt = $con->prepare("SELECT * FROM prod_category WHERE category != ? ORDER BY id DESC ");
                    $stmt->execute(array(''));
                    $rows = $stmt->fetchAll();

                    // the loop 
                    foreach ($rows as $row) {
                        echo '
            <tr class="text-center">
            <td><img src="../assets/img/categories/' . $row['img'] . '" width="80px" height="55px"/></td>
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
                        <td><a style="color: #34568B;" href="category.php?edit=' . $row['id'] . '">تعديل  <i class="fa fa-edit" title="Edit"></i> </a></td>
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
        $('.status-input').attr('value', $(this).data('status'));
    })
</script>

</body>

</html>