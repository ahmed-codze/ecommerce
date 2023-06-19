<?php
include '../connect.php';

// add new color 

if (isset($_POST['new_color'])) {
    $color_name = filter_var($_POST['color_name'], FILTER_SANITIZE_STRING);
    $color_code = filter_var($_POST['color_code'], FILTER_SANITIZE_STRING);

    $stmt = $con->prepare("SELECT color_name FROM prod_color WHERE color_name = ?");
    $stmt->execute(array($color_name));
    $count = $stmt->rowCount();
    if (!$count > 0) {
        // add new color

        $stmt = $con->prepare('INSERT INTO prod_color (color_name, color_code) VALUES (:cname, :ccode)');
        $stmt->execute(array(
            'cname' => $color_name,
            'ccode' => $color_code
        ));
        header('location: product-list.php');
        exit();
    } else {
        header('location: product-list.php');
    }
}

// change color status 

if (isset($_GET['color_status'])) {
    $stmt = $con->prepare('UPDATE prod_color SET status = :stat WHERE id = :id');

    $stmt->execute(array(
        'stat' => $_GET['color_status'],
        'id' => $_GET['id']
    ));
    exit(header('location: product-list.php'));
}

// add new size 

if (isset($_POST['size'])) {
    $size = filter_var($_POST['size'], FILTER_SANITIZE_STRING);

    $stmt = $con->prepare("SELECT size FROM prod_size WHERE size = ?");
    $stmt->execute(array($size));
    $count = $stmt->rowCount();
    if (!$count > 0) {
        // add new size

        $stmt = $con->prepare('INSERT INTO prod_size (size) VALUES (:size)');
        $stmt->execute(array(
            'size' => $size
        ));
        header('location: product-list.php');
        exit();
    } else {
        header('location: product-list.php');
    }
}

// change size status 

if (isset($_GET['size_status'])) {
    $stmt = $con->prepare('UPDATE prod_size SET status = :stat WHERE id = :id');

    $stmt->execute(array(
        'stat' => $_GET['size_status'],
        'id' => $_GET['id']
    ));
    exit(header('location: product-list.php'));
}

// delete product 

if (isset($_POST['delete_product'])) {
    $products_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete if exist

    $stmt = $con->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->execute(array($products_id));
    $count = $stmt->rowCount();

    if ($count > 0) {

        $stmt = $con->prepare("DELETE FROM `products` WHERE `products`.`id` = :id");
        $stmt->bindParam(":id", $products_id);
        $stmt->execute();
    }
}

$serch_placeholder = " ابحث عن منتج";
$serch_page = 'product-list.php';
include 'admin_header.php';
?>

<link rel="stylesheet" type="text/css" href="../assets/css/style.css">
<style>
    /* Dropdown Button */
    .dropbtn {
        border-color: #D5D9D9;
        border-radius: 8px;
        color: #0F1111;
        background: #F0F2F2;
        box-shadow: 0 2px 5px 0 rgb(213 217 217 / 50%);
        width: 60%;
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
        min-width: 190px;
        box-shadow: 0px 8px 16px 0px rgb(0 0 0 / 20%);
        z-index: 1;
        left: 45%;
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

    .form-add {
        display: none;
    }
</style>
<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">المنتجات</h1>
                <div class="dropdown w-50">
                    <button onclick="myFunction()" class="dropbtn btn " type="button">
                        <?php
                        if (isset($_GET['arrange_type'])) {
                            echo $_GET['arrange_type'];
                        } else {
                            echo 'ترتيب حسب';
                        }
                        ?>
                    </button>
                    <div id="myDropdown3" class="dropdown-content w-50">
                        <a class="dropdown-item" href="product-list.php?reverse=true&arrange=id&arrange_type=الأحدث">الأحدث</a>
                        <a class="dropdown-item" href="product-list.php?arrange=id&arrange_type=الأقدم">الأقدم</a>
                        <a class="dropdown-item" href="product-list.php?reverse=true&arrange=price&arrange_type=اعلى سعر">اعلى سعر</a>
                        <a class="dropdown-item" href="product-list.php?arrange=price&arrange_type=اقل سعر">اقل سعر</a>
                        <a class="dropdown-item" href="product-list.php?arrange=quantity&arrange_type=اقل كمية">اقل كمية</a>
                        <a class="dropdown-item" href="product-list.php?reverse=true&arrange=quantity&arrange_type=اعلى كمية">اعلى كمية</a>
                        <a class="dropdown-item" href="product-list.php?reverse=true&arrange=sells&arrange_type=الأكثر مبيعا على الاطلاق"> الأكثر مبيعا</a>
                    </div>
                </div>
                <a href="add_product.php">
                    <div class="btn btn-primary btn-lg">اضافة منتج جديد</div>
                </a>
            </div>
            <br>
            <div class="row text-center">
                <h4>تصنيفات المنتجات</h2>
                    <div class="col-12  row catagories">
                        <div class="col-12 col-lg-6">
                            <div class="dropdown" style="width: 80%;">
                                <button onclick="myFunction()" class="dropbtn btn w-50">المقاس </button>
                                <div id="myDropdown2" class="dropdown-content">
                                    <?php
                                    $stmt = $con->prepare("SELECT * FROM prod_size");
                                    $stmt->execute();
                                    $rows = $stmt->fetchAll();
                                    // the loop 
                                    foreach ($rows as $row) {
                                        if ($row['status'] != 0) {
                                            echo '
                                                <a style="display: inline;" class="dropdown-item" href="javascript:void(0)">' . $row["size"]  . '
                                                <a href="product-list.php?size_status=0&id=' . $row['id'] .  '" style="display:inline; color: red;">اخفاء</a>
                                                <br><br></a>
                                                ';
                                        } else {
                                            echo '
                                                    <a style="display: inline;" class="dropdown-item" href="javascript:void(0)">' . $row["size"]  . '
                                                    <a href="product-list.php?size_status=1&id=' . $row['id'] .  '" style="display:inline;  color: green;">اظهار</a>
                                                    <br><br></a>
                                                    ';
                                        }
                                    }
                                    ?>
                                </div>
                                <ul class="dropdown-menu w-100 text-end" aria-labelledby="dropdownMenuButton1">

                                </ul>
                                <div class="btn btn-secondary form-show" data-open=".add-size">اضافة مقاس</div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="dropdown" style="width: 80%;">
                                <button onclick="myFunction()" class="dropbtn btn w-50">اللون </button>
                                <div id="myDropdown3" class="dropdown-content">
                                    <?php
                                    $stmt = $con->prepare("SELECT * FROM prod_color");
                                    $stmt->execute();
                                    $rows = $stmt->fetchAll();
                                    // the loop 
                                    foreach ($rows as $row) {
                                        if ($row['status'] != 0) {
                                            echo '
                                                <a style="display: inline;" class="dropdown-item" href="javascript:void(0)">' . $row["color_name"]  . '
                                                <a href="product-list.php?color_status=0&id=' . $row['id'] .  '" style="display:inline; color: red;">اخفاء</a>
                                                <br><br></a>
                                                ';
                                        } else {
                                            echo '
                                                    <a style="display: inline;" class="dropdown-item" href="javascript:void(0)">' . $row["color_name"]  . '
                                                    <a href="product-list.php?color_status=1&id=' . $row['id'] .  '" style="display:inline;  color: green;">اظهار</a>
                                                    <br><br></a>
                                                    ';
                                        }
                                    }
                                    ?>
                                </div>
                                <ul class="dropdown-menu w-100 text-end" aria-labelledby="dropdownMenuButton1">

                                </ul>
                                <div class="btn btn-secondary form-show" data-open=".add-color">اضافة لون</div>
                            </div>
                        </div>
                    </div>

            </div>

            <br>

            <br>
            <div class="add-size form-add">
                <form action="product-list.php" method="POST" class="row">
                    <div class="col-8">
                        <input class="form-control" name="size" type="text" placeholder="اكتب المقاس الجديد">
                    </div>
                    <div class="col-4 ">
                        <button class="btn btn-primary w-100" name="new_size" type="submit">اضافة</button>
                    </div>
                </form>
            </div>
            <div class="add-color form-add">
                <form action="product-list.php" method="POST" class="row">
                    <div class="col-4">
                        <input class="form-control" name="color_name" type="text" placeholder="اسم اللون الجديد">
                    </div>
                    <div class="col-4">
                        <label>اختر درجة اللون</label>
                        <input class="form-control" name="color_code" type="color" style="height: 50px;" placeholder="اختر درجة اللون الجديد">
                    </div>
                    <div class="col-4 ">
                        <button class="btn btn-primary w-100" name="new_color" type="submit">اضافة</button>
                    </div>
                </form>

            </div>
            <br>
            <hr>
        </div>
    </div>
    <!-- Container-fluid Ends-->

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row products-admin ratio_asos">
            <?php
            $products_limit = 25;
            if (isset($_GET['search'])) {
                $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING);

                $products_id = array();

                if (isset($_GET['page'])) {
                    $number_offset = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) * $products_limit - $products_limit;
                    $stmt = $con->prepare("SELECT * FROM products WHERE title LIKE '%$search%' ORDER BY id DESC LIMIT $products_limit OFFSET $number_offset  ");
                } else {
                    $stmt = $con->prepare("SELECT * FROM products WHERE title LIKE '%$search%' ORDER BY id DESC LIMIT $products_limit ");
                }
                $stmt->execute();
                $rows = $stmt->fetchAll();
                $count = $stmt->rowCount();

                foreach ($rows as $row) {

                    $images = explode(',', $row['images']);
                    echo '
            <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body product-box">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="javascript:void(0)"><img src="../assets/img/products/' . $images[1] . '" class="img-fluid blur-up lazyload " alt=""></a>
                            <div class="product-hover">
                                <ul>
                                <li>
                                <a href="product-details.php?id=' . $row['id'] . '" target=_blank><button class="btn" type="button" data-original-title="" title=""><i class="ti-more-alt"></i></button></a>
                                </li>
                                <li>
                                <button class="btn delete-product" data-product_id=' . $row['id'] . '><i class="ti-trash"></i></button>
                                </li>
                                </ul>
                            </div>
                            <div class="product-detail">
                            <a href="javascript:void(0)">
                                <h6>' . $row['title'] . '</h6>
                            </a>
                                    ';
                    if ($row['discount'] == 0) {
                        echo '
                                        <h4>' . $row['price'] . ' جنيه</h4>
                                        ';
                    } else {
                        echo '
                                        <h4>' . $row['discount'] . ' جنيه <del>' . $row['price'] . ' </del></h4>
                                        ';
                    }
                    echo '
    </div>
</div>
</div>
</div>
</div>
</div>                                    
';
                    // add id
                    array_push($products_id, $row['id']);
                }

                if (isset($_GET['page'])) {
                    $number_offset = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) * $products_limit - $products_limit;
                    $stmt = $con->prepare("SELECT * FROM products WHERE tags LIKE '%$search%' ORDER BY id DESC LIMIT $products_limit OFFSET $number_offset  ");
                } else {
                    $stmt = $con->prepare("SELECT * FROM products WHERE tags LIKE '%$search%' ORDER BY id DESC LIMIT $products_limit ");
                }
                $stmt->execute();
                $rows = $stmt->fetchAll();
                $count = $stmt->rowCount();

                foreach ($rows as $row) {

                    if (in_array($row['id'], $products_id)) {
                    } else {

                        $images = explode(',', $row['images']);
                        echo '
                <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body product-box">
                        <div class="img-wrapper">
                            <div class="front">
                                <a href="javascript:void(0)"><img src="../assets/img/products/' . $images[1] . '" class="img-fluid blur-up lazyload " alt=""></a>
                                <div class="product-hover">
                                    <ul>
                                    <li>
                                    <a href="product-details.php?id=' . $row['id'] . '" target=_blank><button class="btn" type="button" data-original-title="" title=""><i class="ti-more-alt"></i></button></a>
                                    </li>
                                    <li>
                                    <button class="btn delete-product" data-product_id=' . $row['id'] . '><i class="ti-trash"></i></button>
                                    </li>
                                    </ul>
                                </div>
                                <div class="product-detail">
                                <a href="javascript:void(0)">
                                    <h6>' . $row['title'] . '</h6>
                                </a>
                                        ';
                        if ($row['discount'] == 0) {
                            echo '
                                            <h4>' . $row['price'] . ' جنيه</h4>
                                            ';
                        } else {
                            echo '
                                            <h4>' . $row['discount'] . ' جنيه <del>' . $row['price'] . ' </del></h4>
                                            ';
                        }
                        echo '
        </div>
    </div>
</div>
</div>
</div>
</div>                                    
';
                        // add id
                        array_push($products_id, $row['id']);
                    }
                }

                if (isset($_GET['page'])) {
                    $number_offset = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) * $products_limit - $products_limit;
                    $stmt = $con->prepare("SELECT * FROM products WHERE description LIKE '%$search%' ORDER BY id DESC LIMIT $products_limit OFFSET $number_offset  ");
                } else {
                    $stmt = $con->prepare("SELECT * FROM products WHERE description LIKE '%$search%' ORDER BY id DESC LIMIT $products_limit ");
                }
                $stmt->execute();
                $rows = $stmt->fetchAll();
                $count = $stmt->rowCount();

                foreach ($rows as $row) {

                    if (in_array($row['id'], $products_id)) {
                    } else {

                        $images = explode(',', $row['images']);
                        echo '
                <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body product-box">
                        <div class="img-wrapper">
                            <div class="front">
                                <a href="javascript:void(0)"><img src="../assets/img/products/' . $images[1] . '" class="img-fluid blur-up lazyload " alt=""></a>
                                <div class="product-hover">
                                    <ul>
                                    <li>
                                    <a href="product-details.php?id=' . $row['id'] . '" target=_blank><button class="btn" type="button" data-original-title="" title=""><i class="ti-more-alt"></i></button></a>
                                    </li>
                                    <li>
                                    <button class="btn delete-product" data-product_id=' . $row['id'] . '><i class="ti-trash"></i></button>
                                    </li>
                                    </ul>
                                </div>
                                <div class="product-detail">
                                <a href="javascript:void(0)">
                                    <h6>' . $row['title'] . '</h6>
                                </a>
                                        ';
                        if ($row['discount'] == 0) {
                            echo '
                                            <h4>' . $row['price'] . ' جنيه</h4>
                                            ';
                        } else {
                            echo '
                                            <h4>' . $row['discount'] . ' جنيه <del>' . $row['price'] . ' </del></h4>
                                            ';
                        }
                        echo '
        </div>
    </div>
</div>
</div>
</div>
</div>                                    
';
                    }
                }

                echo '
                </div>
                <div class="product-pagination">
                    <div class="theme-paggination-block">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                ';
                if ($products_limit <= (count($products_id) + 1)) {
                    echo '
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination first-page">
                                        ';

                    if (isset($_GET['page']) and $_GET['page'] != 1) {
                        echo '<li class="page-item"><a class="page-link" href="product-list.php?search=' . $_GET['search'] . '&page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) - 1 . '" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                        ';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                        ';
                    }
                    for ($i = 1; $i <= (count($products_id) + 1) / $products_limit; $i++) {
                        echo '<li class="page-item"><a class="page-link" href="product-list.php?search=' . $_GET['search'] . '&page=' . $i . '">' . $i . '</a></li>';
                    }

                    if (isset($_GET['page']) and $_GET['page'] != ((count($products_id) + 1) / $products_limit)) {
                        echo '<li class="page-item"><a class="page-link" href="product-list.php?search=' . $_GET['search'] . '&page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) + 1 . '" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="product-list.php?page=2" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                    }
                    echo '
                                        </ul>
                                    </nav>
                                    ';
                }
                echo '
                                </div>
        
                            </div>
                        </div>
                    </div>
                </div>
                ';
            } else {

                // get products number 

                $stmt = $con->prepare("SELECT trade_price FROM products");
                $stmt->execute();
                $products_number = count($stmt->fetchAll());

                if (isset($_GET['arrange'])) {
                    $arrange = filter_var($_GET['arrange'], FILTER_SANITIZE_STRING);

                    if (isset($_GET['reverse'])) {
                        // reverse

                        if (isset($_GET['page'])) {
                            $number_offset = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) * $products_limit - $products_limit;
                            $stmt = $con->prepare("SELECT * FROM products ORDER BY $arrange DESC LIMIT $products_limit OFFSET $number_offset  ");
                        } else {
                            $stmt = $con->prepare("SELECT * FROM products ORDER BY $arrange DESC LIMIT $products_limit ");
                        }
                    } else {

                        if (isset($_GET['page'])) {
                            $stmt = $con->prepare("SELECT * FROM products ORDER BY $arrange LIMIT $products_limit OFFSET $number_offset  ");
                        } else {
                            $stmt = $con->prepare("SELECT * FROM products ORDER BY $arrange LIMIT $products_limit ");
                        }
                    }

                    $stmt->execute();
                    $rows = $stmt->fetchAll();

                    // the loop 
                    foreach ($rows as $row) {

                        $images = explode(',', $row['images']);
                        echo '
                    <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body product-box">
                            <div class="img-wrapper">
                                <div class="front">
                                    <a href="javascript:void(0)"><img src="../assets/img/products/' . $images[1] . '" class="img-fluid blur-up lazyload " alt=""></a>
                                    <div class="product-hover">
                                        <ul>
                                        <li>
                                        <a href="product-details.php?id=' . $row['id'] . '" target=_blank><button class="btn" type="button" data-original-title="" title=""><i class="ti-more-alt"></i></button></a>
                                        </li>
                                        <li>
                                        <button class="btn delete-product" data-product_id=' . $row['id'] . '><i class="ti-trash"></i></button>
                                        </li>
                                        </ul>
                                    </div>
                                    <div class="product-detail">
                                    <a href="javascript:void(0)">
                                        <h6>' . $row['title'] . '</h6>
                                    </a>
                                            ';
                        if ($row['discount'] == 0) {
                            echo '
                                                <h4>' . $row['price'] . ' جنيه</h4>
                                                ';
                        } else {
                            echo '
                                                <h4>' . $row['discount'] . ' جنيه <del>' . $row['price'] . ' </del></h4>
                                                ';
                        }
                        echo '
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>                                    
    ';
                    }
                    echo '
                </div>
                <div class="product-pagination">
                    <div class="theme-paggination-block">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                ';
                    if ($products_limit <= $products_number) {
                        echo '
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination first-page">
                                        ';

                        if (isset($_GET['page']) and $_GET['page'] != 1) {
                            echo '<li class="page-item"><a class="page-link" href="product-list.php?page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) - 1 . '" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                        ';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                        ';
                        }
                        for ($i = 1; $i <= $products_number / $products_limit; $i++) {
                            echo '<li class="page-item"><a class="page-link" href="product-list.php?page=' . $i . '">' . $i . '</a></li>';
                        }

                        if (isset($_GET['page']) and $_GET['page'] != ($products_number / $products_limit)) {
                            echo '<li class="page-item"><a class="page-link" href="product-list.php?page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) + 1 . '" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="product-list.php?page=2" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                        }
                        echo '
                                        </ul>
                                    </nav>
                                    ';
                    }
                    echo '
                                </div>
        
                            </div>
                        </div>
                    </div>
                </div>
                ';
                } else {

                    if (isset($_GET['page'])) {
                        $number_offset = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) * $products_limit - $products_limit;
                        $stmt = $con->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $products_limit OFFSET $number_offset  ");
                    } else {
                        $stmt = $con->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $products_limit ");
                    }
                    $stmt->execute();
                    $rows = $stmt->fetchAll();

                    // the loop 
                    foreach ($rows as $row) {

                        $images = explode(',', $row['images']);
                        echo '
                <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body product-box">
                        <div class="img-wrapper">
                            <div class="front">
                                <a href="javascript:void(0)"><img src="../assets/img/products/' . $images[1] . '" class="img-fluid blur-up lazyload " alt=""></a>
                                <div class="product-hover">
                                    <ul>
                                    <li>
                                    <a href="product-details.php?id=' . $row['id'] . '" target=_blank><button class="btn" type="button" data-original-title="" title=""><i class="ti-more-alt"></i></button></a>
                                    </li>
                                    <li>
                                    <button class="btn delete-product" data-product_id=' . $row['id'] . '><i class="ti-trash"></i></button>
                                    </li>
                                    </ul>
                                </div>
                                <div class="product-detail">
                                <a href="javascript:void(0)">
                                    <h6>' . $row['title'] . '</h6>
                                </a>
                                        ';
                        if ($row['discount'] == 0) {
                            echo '
                                            <h4>' . $row['price'] . ' جنيه</h4>
                                            ';
                        } else {
                            echo '
                                            <h4>' . $row['discount'] . ' جنيه <del>' . $row['price'] . ' </del></h4>
                                            ';
                        }
                        echo '
        </div>
    </div>
</div>
</div>
</div>
</div>                                    
';
                    }
                    echo '
            </div>
            <div class="product-pagination">
                <div class="theme-paggination-block">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-xl-6 col-md-6 col-sm-12">
                            ';
                    if ($products_limit <= $products_number) {
                        echo '
                                <nav aria-label="Page navigation">
                                    <ul class="pagination first-page">
                                    ';

                        if (isset($_GET['page']) and $_GET['page'] != 1) {
                            echo '<li class="page-item"><a class="page-link" href="product-list.php?page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) - 1 . '" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                    ';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                    ';
                        }
                        for ($i = 1; $i <= ceil($products_number / $products_limit); $i++) {
                            echo '<li class="page-item"><a class="page-link" href="product-list.php?page=' . $i . '">' . $i . '</a></li>';
                        }

                        if (isset($_GET['page']) and $_GET['page'] != ($products_number / $products_limit)) {
                            echo '<li class="page-item"><a class="page-link" href="product-list.php?page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) + 1 . '" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="product-list.php?page=2" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                        }
                        echo '
                                    </ul>
                                </nav>
                                ';
                    }
                    echo '
                            </div>
    
                        </div>
                    </div>
                </div>
            </div>
            ';
                }
            }
            ?>

        </div>
        <!-- Container-fluid Ends-->
    </div>

    <?php include 'admin_footer.php'; ?>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        // delete product 

        $('.delete-product').click(function() {
            swal({
                    title: "هل تريد حذف المنتج ؟",
                    text: " اذا قمت بالضغط على OK سيتم الحذف",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $(this).parentsUntil('.products-admin').hide();
                        swal("تم حذف المنتج", {
                            icon: "success",
                        });
                        $.post('product-list.php', {
                            delete_product: true,
                            product_id: $(this).data('product_id'),
                        });
                    }
                });
        })

        // add active class to pagination

        <?php if (isset($_GET['page'])) {
            echo '$("ul.first-page li:nth-child(' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) + 1 . ')").addClass("active");';
        } else {
            echo '$("ul.first-page li:nth-child(2)").addClass("active");';
        } ?>


        $('.form-show').click(function() {
            $('' + $(this).data('open') + '').toggle('slow');

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
    </script>

    </body>

    </html>