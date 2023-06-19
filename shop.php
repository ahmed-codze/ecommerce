<?php
// get ecommerce main info 
include 'connect.php';
$stmt = $con->prepare("SELECT * FROM web_info");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $title = $row['title'];
    $logo = $row['logo'];
    $color = $row['color'];
    $description = $row['description'];
    $slogan = $row['slogan'];
    $shiiping = $row['shipping'];
}
include 'theme_header.php'; ?>

<!-- section start -->
<section class="section-b-space ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 collection-filter">
                    <!-- side-bar colleps block stat -->
                    <div class="collection-filter-block">
                        <!-- category filter start -->
                        <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>

                        <?php
                        // get categories
                        $stmt = $con->prepare("SELECT category FROM prod_category WHERE status != 0");
                        $stmt->execute();
                        $categories = $stmt->fetchAll();

                        foreach ($categories as $category) {
                            echo '
                            <div class="collection-collapse-block category-filter-box">
                            <h3 class="collapse-block-title">' . $category['category'] . '</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">
                            ';
                            // getsub category 
                            $stmt = $con->prepare("SELECT sub_category FROM sub_category WHERE category = ? AND status != 0");
                            $stmt->execute(array($category['category']));
                            $sub_category = $stmt->fetchAll();

                            foreach ($sub_category as $sub) {
                                echo '
                                <div class="form-check collection-filter-checkbox">
                                ';
                                if (isset($_GET['filter']) and isset($_GET['sub_category']) and $_GET['sub_category'] == $sub['sub_category']) {
                                    echo '
                                    <input data-input="main-category-input" checked type="radio" class="filtered form-check-input custom-filter category-filter-input" data-val="' . $sub['sub_category'] . '"  id="' . $sub['sub_category'] . '">
                                    <label data-input="main-category-input" class="form-check-label filtered custom-filter category-filter-label" data-val="' . $sub['sub_category'] . '"  for="' . $sub['sub_category'] . '">' . $sub['sub_category'] . '</label>
                                    ';
                                } else {
                                    echo '
                                    <input  type="radio" class="form-check-input custom-filter category-filter-input" data-val="' . $sub['sub_category'] . '"  id="' . $sub['sub_category'] . '">
                                    <label class="form-check-label custom-filter category-filter-label" data-val="' . $sub['sub_category'] . '"  for="' . $sub['sub_category'] . '">' . $sub['sub_category'] . '</label>
                                    ';
                                }
                                echo '        
                                </div>
                                ';
                            }

                            echo '
                            </div>
                        </div>
                    </div>
                            ';
                        }

                        if (isset($_GET['sub_category'])) {
                            echo '<input type="hidden" value="' . $_GET['sub_category'] . '" class="main-category-input">';
                        } else {
                            echo '<input type="hidden" value="0" class="main-category-input">';
                        }
                        ?>


                        <!-- color filter start here -->
                        <div class="collection-collapse-block open">
                            <h3 class="collapse-block-title">colors</h3>
                            <div class="collection-collapse-block-content">
                                <div class="color-selector">
                                    <ul>
                                        <?php
                                        $stmt = $con->prepare("SELECT color_name, color_code FROM prod_color WHERE status != 0");
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll();

                                        foreach ($rows as $row) {

                                            if (isset($_GET['filter']) and isset($_GET['color']) and $_GET['color'] == $row['color_name']) {
                                                echo '<li data-input="main-color-input" data-val="' . $row['color_name'] . '" style="background-color: ' . $row['color_code'] . ';" title="' . $row['color_name'] . '" class=" filtered custom-filter color-filter color-1 active"></li>';
                                            } else {
                                                echo '<li data-input="main-color-input" data-val="' . $row['color_name'] . '" style="background-color: ' . $row['color_code'] . ';" title="' . $row['color_name'] . '" class=" custom-filter color-filter color-1"></li>';
                                            }
                                        }

                                        if (isset($_GET['color'])) {
                                            echo '<input type="hidden" value="' . $_GET['color'] . '" class="main-color-input">';
                                        } else {
                                            echo '<input type="hidden" value="0" class="main-color-input">';
                                        }
                                        ?>

                                    </ul>

                                </div>
                            </div>
                        </div>
                        <!-- size filter start here -->
                        <div class="collection-collapse-block border-0 open">
                            <h3 class="collapse-block-title">size</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">
                                    <?php
                                    $stmt = $con->prepare("SELECT size FROM prod_size WHERE status != 0");
                                    $stmt->execute();
                                    $rows = $stmt->fetchAll();

                                    foreach ($rows as $row) {
                                        echo '
                                        <div class="form-check collection-filter-checkbox">
                                        ';
                                        if (isset($_GET['filter']) and isset($_GET['size']) and $_GET['size'] == $row['size']) {
                                            echo '
                                            <input data-input="main-size-input" checked data-val="' . $row['size'] . '" type="radio"  class=" filtered custom-filter form-check-input size-input" id="' . $row['size'] . '">
                                            <label data-input="main-size-input" data-val="' . $row['size'] . '" class=" filtered custom-filter form-check-label size-label" for="' . $row['size'] . '">' . $row['size'] . '</label>
                                            ';
                                        } else {
                                            echo '
                                            <input data-val="' . $row['size'] . '" type="radio"  class=" custom-filter form-check-input size-input" id="' . $row['size'] . '">
                                            <label data-val="' . $row['size'] . '" class=" custom-filter form-check-label size-label" for="' . $row['size'] . '">' . $row['size'] . '</label>
                                            ';
                                        }

                                        echo '
                                        </div>
                                            ';

                                        if (isset($_GET['size'])) {
                                            echo '<input type="hidden" value="' . $_GET['size'] . '" class="main-size-input">';
                                        } else {
                                            echo '<input type="hidden" value="0" class="main-size-input">';
                                        }
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- side-bar banner end here -->
                </div>
                <div class="collection-content col">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                // get parallax 

                                $stmt = $con->prepare("SELECT img FROM images  order by RAND() LIMIT 1");
                                $stmt->execute();
                                $rows = $stmt->fetchAll();
                                if (count($rows) > 0) {
                                    foreach ($rows as $row) {
                                        echo '
                                        <div class="top-banner-wrapper">
                                        <a href="javascript:void(0)"><img src="assets/img/promo-images/' . $row['img'] . '" class="img-fluid blur-up lazyloaded" alt=""></a>
                                    </div>
                                    ';
                                    }
                                }
                                ?>

                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn"><span class="filter-btn btn btn-theme"><i class="fa fa-filter" aria-hidden="true"></i> Filter</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-wrapper-grid">
                                        <div class="row margin-res">

                                            <?php
                                            $products_limit = 25;
                                            $products_id_arr = array();

                                            // start filter

                                            if (isset($_GET['search'])) {

                                                $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING);

                                                $stmt = $con->prepare("SELECT id, category, sub_category, color FROM products WHERE quantity != 0 AND ( title LIKE '%$search%' OR tags LIKE '%$search%' OR description LIKE '%$search%' ) ORDER BY id DESC ");
                                                $stmt->execute();
                                                $rows = $stmt->fetchAll();
                                                $products_id_arr = array();
                                                foreach ($rows as $row) {
                                                    // check category status 
                                                    $stmt = $con->prepare("SELECT status FROM prod_category WHERE category = ? AND status = 1");
                                                    $stmt->execute(array($row['category']));
                                                    $count_category = $stmt->rowCount();
                                                    if ($count_category > 0 or $row['category'] == '') {
                                                        // check sub category
                                                        $stmt = $con->prepare("SELECT status FROM sub_category WHERE sub_category = ? AND status = 1");
                                                        $stmt->execute(array($row['sub_category']));
                                                        $count_sub_category = $stmt->rowCount();
                                                        if ($count_sub_category > 0 or $row['sub_category'] == '') {
                                                            // check color 
                                                            $stmt = $con->prepare("SELECT color_name FROM prod_color WHERE color_name = ? AND status = 1");
                                                            $stmt->execute(array($row['color']));
                                                            $count_color = $stmt->rowCount();
                                                            if ($count_color > 0 or $row['color'] == '') {
                                                                array_push($products_id_arr, $row['id']);
                                                            }
                                                        }
                                                    }
                                                }

                                                // start filter
                                            } elseif (isset($_GET['filter'])) {

                                                if (isset($_GET['category']) and $_GET['category'] != '0') {

                                                    $category = filter_var($_GET['category'], FILTER_SANITIZE_STRING);

                                                    $stmt = $con->prepare("SELECT id, category, sub_category, color FROM products WHERE quantity != 0 AND ( category LIKE '%$category%' ) ORDER BY id DESC ");
                                                    $stmt->execute();
                                                    $rows = $stmt->fetchAll();
                                                    foreach ($rows as $row) {


                                                        //check category status 
                                                        $stmt = $con->prepare("SELECT status FROM prod_category WHERE category = ? AND status = 1");
                                                        $stmt->execute(array($row['category']));
                                                        $count_category = $stmt->rowCount();
                                                        if ($count_category > 0 or $row['category'] == '') {
                                                            //check sub category
                                                            $stmt = $con->prepare("SELECT status FROM sub_category WHERE sub_category = ? AND status = 1");
                                                            $stmt->execute(array($row['sub_category']));
                                                            $count_sub_category = $stmt->rowCount();
                                                            if ($count_sub_category > 0 or $row['sub_category'] == '') {
                                                                //check color 
                                                                $stmt = $con->prepare("SELECT color_name FROM prod_color WHERE color_name = ? AND status = 1");
                                                                $stmt->execute(array($row['color']));
                                                                $count_color = $stmt->rowCount();
                                                                if ($count_color > 0 or $row['color'] == '') {
                                                                    array_push($products_id_arr, $row['id']);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                                if (isset($_GET['sub_category']) and $_GET['sub_category'] != '0') {
                                                    $sub_category = filter_var($_GET['sub_category'], FILTER_SANITIZE_STRING);

                                                    $stmt = $con->prepare("SELECT id, category, sub_category, color FROM products WHERE quantity != 0 AND sub_category = ? ORDER BY id DESC ");
                                                    $stmt->execute(array($sub_category));
                                                    $rows = $stmt->fetchAll();
                                                    foreach ($rows as $row) {
                                                        // check category status 
                                                        $stmt = $con->prepare("SELECT status FROM prod_category WHERE category = ? AND status = 1");
                                                        $stmt->execute(array($row['category']));
                                                        $count_category = $stmt->rowCount();
                                                        if ($count_category > 0 or $row['category'] == '') {
                                                            // check sub category
                                                            $stmt = $con->prepare("SELECT status FROM sub_category WHERE sub_category = ? AND status = 1");
                                                            $stmt->execute(array($row['sub_category']));
                                                            $count_sub_category = $stmt->rowCount();
                                                            if ($count_sub_category > 0 or $row['sub_category'] == '') {
                                                                // check color 
                                                                $stmt = $con->prepare("SELECT color_name FROM prod_color WHERE color_name = ? AND status = 1");
                                                                $stmt->execute(array($row['color']));
                                                                $count_color = $stmt->rowCount();
                                                                if ($count_color > 0 or $row['color'] == '') {
                                                                    if (!in_array($row['id'], $products_id_arr)) {
                                                                        array_push($products_id_arr, $row['id']);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if (isset($_GET['color']) and $_GET['color'] != '0') {

                                                    $color = filter_var($_GET['color'], FILTER_SANITIZE_STRING);

                                                    if (empty($products_id_arr)) {
                                                        $stmt = $con->prepare("SELECT id, category, sub_category, color FROM products WHERE quantity != 0 AND color = ? ORDER BY id DESC ");
                                                        $stmt->execute(array($color));
                                                        $rows = $stmt->fetchAll();
                                                        foreach ($rows as $row) {
                                                            // check category status 
                                                            $stmt = $con->prepare("SELECT status FROM prod_category WHERE category = ? AND status = 1");
                                                            $stmt->execute(array($row['category']));
                                                            $count_category = $stmt->rowCount();
                                                            if ($count_category > 0 or $row['category'] == '') {
                                                                // check sub category
                                                                $stmt = $con->prepare("SELECT status FROM sub_category WHERE sub_category = ? AND status = 1");
                                                                $stmt->execute(array($row['sub_category']));
                                                                $count_sub_category = $stmt->rowCount();
                                                                if ($count_sub_category > 0 or $row['sub_category'] == '') {
                                                                    // check color 
                                                                    $stmt = $con->prepare("SELECT color_name FROM prod_color WHERE color_name = ? AND status = 1");
                                                                    $stmt->execute(array($row['color']));
                                                                    $count_color = $stmt->rowCount();
                                                                    if ($count_color > 0 or $row['color'] == '') {
                                                                        if (!in_array($row['id'], $products_id_arr)) {
                                                                            array_push($products_id_arr, $row['id']);
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $color_products_arr = array();
                                                        foreach ($products_id_arr as $products_id) {
                                                            $stmt = $con->prepare("SELECT id, category, sub_category, color FROM products WHERE quantity != 0 AND color = ? AND id = ? ORDER BY id DESC ");
                                                            $stmt->execute(array($color, $products_id));
                                                            $rows = $stmt->fetchAll();
                                                            foreach ($rows as $row) {
                                                                // check category status 
                                                                $stmt = $con->prepare("SELECT status FROM prod_category WHERE category = ? AND status = 1");
                                                                $stmt->execute(array($row['category']));
                                                                $count_category = $stmt->rowCount();
                                                                if ($count_category > 0 or $row['category'] == '') {
                                                                    // check sub category
                                                                    $stmt = $con->prepare("SELECT status FROM sub_category WHERE sub_category = ? AND status = 1");
                                                                    $stmt->execute(array($row['sub_category']));
                                                                    $count_sub_category = $stmt->rowCount();
                                                                    if ($count_sub_category > 0 or $row['sub_category'] == '') {
                                                                        // check color 
                                                                        $stmt = $con->prepare("SELECT color_name FROM prod_color WHERE color_name = ? AND status = 1");
                                                                        $stmt->execute(array($row['color']));
                                                                        $count_color = $stmt->rowCount();
                                                                        if ($count_color > 0 or $row['color'] == '') {
                                                                            if (!in_array($row['id'], $color_products_arr)) {
                                                                                array_push($color_products_arr, $row['id']);
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $products_id_arr = $color_products_arr;
                                                    }
                                                }
                                                if (isset($_GET['size']) and $_GET['size'] != '0') {
                                                    $size = filter_var($_GET['size'], FILTER_SANITIZE_STRING);

                                                    if (empty($products_id_arr)) {

                                                        $stmt = $con->prepare("SELECT id, category, sub_category, color FROM products WHERE quantity != 0 AND size LIKE '%$size%' ORDER BY id DESC ");
                                                        $stmt->execute();
                                                        $rows = $stmt->fetchAll();
                                                        foreach ($rows as $row) {
                                                            // check category status 
                                                            $stmt = $con->prepare("SELECT status FROM prod_category WHERE category = ? AND status = 1");
                                                            $stmt->execute(array($row['category']));
                                                            $count_category = $stmt->rowCount();
                                                            if ($count_category > 0 or $row['category'] == '') {
                                                                // check sub category
                                                                $stmt = $con->prepare("SELECT status FROM sub_category WHERE sub_category = ? AND status = 1");
                                                                $stmt->execute(array($row['sub_category']));
                                                                $count_sub_category = $stmt->rowCount();
                                                                if ($count_sub_category > 0 or $row['sub_category'] == '') {
                                                                    // check color 
                                                                    $stmt = $con->prepare("SELECT color_name FROM prod_color WHERE color_name = ? AND status = 1");
                                                                    $stmt->execute(array($row['color']));
                                                                    $count_color = $stmt->rowCount();
                                                                    if ($count_color > 0 or $row['color'] == '') {
                                                                        if (!in_array($row['id'], $products_id_arr)) {
                                                                            array_push($products_id_arr, $row['id']);
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $size_products_arr = array();
                                                        foreach ($products_id_arr as $products_id) {

                                                            $stmt = $con->prepare("SELECT id, category, sub_category, color FROM products WHERE quantity != 0 AND size LIKE '%$size%' AND id = ? ORDER BY id DESC ");
                                                            $stmt->execute(array($products_id));
                                                            $rows = $stmt->fetchAll();
                                                            foreach ($rows as $row) {
                                                                // check category status 
                                                                $stmt = $con->prepare("SELECT status FROM prod_category WHERE category = ? AND status = 1");
                                                                $stmt->execute(array($row['category']));
                                                                $count_category = $stmt->rowCount();
                                                                if ($count_category > 0 or $row['category'] == '') {
                                                                    // check sub category
                                                                    $stmt = $con->prepare("SELECT status FROM sub_category WHERE sub_category = ? AND status = 1");
                                                                    $stmt->execute(array($row['sub_category']));
                                                                    $count_sub_category = $stmt->rowCount();
                                                                    if ($count_sub_category > 0 or $row['sub_category'] == '') {
                                                                        // check color 
                                                                        $stmt = $con->prepare("SELECT color_name FROM prod_color WHERE color_name = ? AND status = 1");
                                                                        $stmt->execute(array($row['color']));
                                                                        $count_color = $stmt->rowCount();
                                                                        if ($count_color > 0 or $row['color'] == '') {
                                                                            if (!in_array($row['id'], $size_products_arr)) {
                                                                                array_push($size_products_arr, $row['id']);
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $products_id_arr = $size_products_arr;
                                                    }
                                                }

                                                if (empty($products_id_arr)) {
                                                    echo ("<script>location.href = 'shop.php';</script>");
                                                    exit();
                                                }
                                            } else {

                                                $stmt = $con->prepare("SELECT id, category, sub_category, color FROM products WHERE quantity != 0 ORDER BY id DESC ");
                                                $stmt->execute();
                                                $rows = $stmt->fetchAll();
                                                foreach ($rows as $row) {
                                                    // check category status 
                                                    $stmt = $con->prepare("SELECT status FROM prod_category WHERE category = ? AND status = 1");
                                                    $stmt->execute(array($row['category']));
                                                    $count_category = $stmt->rowCount();
                                                    if ($count_category > 0 or $row['category'] == '') {
                                                        // check sub category
                                                        $stmt = $con->prepare("SELECT status FROM sub_category WHERE sub_category = ? AND status = 1");
                                                        $stmt->execute(array($row['sub_category']));
                                                        $count_sub_category = $stmt->rowCount();
                                                        if ($count_sub_category > 0 or $row['sub_category'] == '') {
                                                            // check color 
                                                            $stmt = $con->prepare("SELECT color_name FROM prod_color WHERE color_name = ? AND status = 1");
                                                            $stmt->execute(array($row['color']));
                                                            $count_color = $stmt->rowCount();
                                                            if ($count_color > 0 or $row['color'] == '') {
                                                                array_push($products_id_arr, $row['id']);
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            // end filter and get products 
                                            if (isset($_GET['page'])) {
                                                $i = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) * $products_limit - $products_limit;
                                            } else {
                                                $i = 0;
                                            }
                                            $loop_limit = $products_limit + $i;
                                            for ($i; $i < $loop_limit; $i++) {
                                                if ($i < count($products_id_arr)) {
                                                    $stmt = $con->prepare("SELECT DISTINCT * FROM products WHERE id = ?");

                                                    $stmt->execute(array($products_id_arr[$i]));
                                                    $rows = $stmt->fetchAll();

                                                    foreach ($rows as $row) {

                                                        $images = explode(',', $row['images']);
                                                        unset($images[0]);
                                                        echo '
                                                            <div class="col-xl-3 col-6 col-grid-box">
                                                        <div class="product-box">
                                                        <div class="img-wrapper">
                                                            <div class="front">
                                                                <a href="product-details.php?id=' . $row['id'] . '"><img src="assets/img/products/' . $images[1] . '" class="img-fluid blur-up lazyload" alt="' . $row['title'] . '"></a>
                                                            </div>
                                                            <div class="back">
                                                                <a href="product-details.php?id=' . $row['id'] . '"><img src="assets/img/products/' . $images[2] . '" class="img-fluid blur-up lazyload" alt="' . $row['title'] . '"></a>
                                                            </div>
                                                            <div class="cart-info cart-wrap">
                                                                <button data-bs-toggle="modal" data-bs-target="#addtocart" data-id=' . $row['id'] . ' class="mini-add-to-cart" title="Add to cart">
                                                                    <i class="ti-shopping-cart"></i>
                                                                </button>
                                                                <a href="javascript:void(0)" class="whishlist-button" data-id=' . $row['id'] . ' title="Add to Wishlist">
                                                                    <i class="ti-heart" aria-hidden="true"></i>
                                                                </a>
                                                                <a href="#" data-bs-toggle="modal" data-id=' . $row['id'] . ' class="quick-view-icon" data-bs-target="#quick-view" title="Quick View">
                                                                    <i class="ti-search" aria-hidden="true"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="product-detail">
                                                            </div>
                                                            <a href="product-page.php?id=' . $row['id'] . '">
                                                                <h6>' . $row['title'] . '</h6>
                                                            </a>
                                                            ';
                                                        if (isset($_COOKIE['trade'])) {
                                                            echo '
                                                                <h4>' . $row['trade_price'] . ' LE</h4>
                                                                ';
                                                        } elseif ($row['discount'] == 0) {
                                                            echo '
                                                            <h4>' . $row['price'] . ' LE</h4>
                                                            ';
                                                        } else {
                                                            echo '
                                                            <h4> <del>' . $row['price'] . ' </del> ' . $row['discount'] . ' LE </h4>
                                                            ';
                                                        }
                                                        echo '
                                                        </div>
                                                        </div>
                                                            ';
                                                    }
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
                                            if ($products_limit <= count($products_id_arr)) {
                                                echo '
                                                                <nav aria-label="Page navigation">
                                                                    <ul class="pagination first-page">
                                                                    ';

                                                if (isset($_GET['page']) and $_GET['page'] != 1) {
                                                    if (isset($_GET['search'])) {
                                                        echo '<li class="page-item"><a class="page-link" href="shop.php?search=' . $search . '&page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) - 1 . '" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                                                        ';
                                                    } elseif (isset($_GET['filter'])) {
                                                        echo '<li class="page-item"><a class="page-link" href="' . $_SERVER["REQUEST_URI"] . '&page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) - 1 . '" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                                                        ';
                                                    } else {
                                                        echo '<li class="page-item"><a class="page-link" href="shop.php?page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) - 1 . '" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                                                    ';
                                                    }
                                                } else {
                                                    echo '<li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                                                    ';
                                                }
                                                for ($i = 1; $i <= ceil(count($products_id_arr) / $products_limit); $i++) {
                                                    if (isset($_GET['search'])) {
                                                        echo '<li class="page-item"><a class="page-link" href="shop.php?search=' . $search . '&page=' . $i . '">' . $i . '</a></li>';
                                                    } elseif (isset($_GET['filter'])) {
                                                        echo '<li class="page-item"><a class="page-link" href="' . $_SERVER["REQUEST_URI"] . '&page=' . $i . '">' . $i . '</a></li>';
                                                    } else {
                                                        echo '<li class="page-item"><a class="page-link" href="shop.php?page=' . $i . '">' . $i . '</a></li>';
                                                    }
                                                }

                                                if (isset($_GET['page'])) {
                                                    if ($_GET['page'] != ceil(count($products_id_arr) / $products_limit)) {
                                                        if (isset($_GET['search'])) {
                                                            echo '<li class="page-item"><a class="page-link" href="shop.php?search=' . $search . '&page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) + 1 . '" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                                                        } elseif (isset($_GET['filter'])) {
                                                            echo '<li class="page-item"><a class="page-link" href="' . $_SERVER["REQUEST_URI"] . '&page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) + 1 . '" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                                                        } else {
                                                            echo '<li class="page-item"><a class="page-link" href="shop.php?page=' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) + 1 . '" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                                                        }
                                                    } else {
                                                        echo '<li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                                                    }
                                                } else {
                                                    if (isset($_GET['search'])) {
                                                        echo '<li class="page-item"><a class="page-link" href="shop.php?search=' . $search . '&page=2" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                                                    } elseif (isset($_GET['filter'])) {
                                                        echo '<li class="page-item"><a class="page-link" href="' . $_SERVER["REQUEST_URI"] . '&page=2" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                                                    } else {
                                                        echo '<li class="page-item"><a class="page-link" href="shop.php?page=2" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>';
                                                    }
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


                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</section>
<!-- section End -->


<!-- Quick-view modal popup start-->
<div class="modal fade bd-example-modal-lg theme-modal" id="quick-view" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content quick-view-modal">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="quick-view-img"><img src="assets/images/pro3/1.jpg" alt="" class="img-fluid blur-up lazyload"></div>
                    </div>
                    <div class="col-lg-6 rtl-text">
                        <div class="product-right">
                            <h2>Women Pink Shirt</h2>
                            <h3>$32.96</h3>
                            <ul class="color-variant">
                                <li class="bg-light0"></li>
                                <li class="bg-light1"></li>
                                <li class="bg-light2"></li>
                            </ul>
                            <div class="border-product">
                                <h6 class="product-title">product details</h6>
                                <p>Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium
                                    doloremque laudantium</p>
                            </div>
                            <div class="product-description border-product">
                                <div class="size-box">
                                    <ul>
                                        <li><a href="javascript:void(0)">s</a></li>
                                        <li><a href="javascript:void(0)">m</a></li>
                                        <li><a href="javascript:void(0)">l</a></li>
                                        <li><a href="javascript:void(0)">xl</a></li>
                                    </ul>
                                </div>
                                <h6 class="product-title">quantity</h6>
                                <div class="qty-box">
                                    <div class="input-group"><span class="input-group-prepend"><button type="button" class="btn quantity-left-minus" data-type="minus" data-field=""><i class="ti-angle-left"></i></button> </span>
                                        <input type="text" name="quantity" class="form-control input-number" value="1"> <span class="input-group-prepend"><button type="button" class="btn quantity-right-plus" data-type="plus" data-field=""><i class="ti-angle-right"></i></button></span>
                                    </div>
                                </div>
                            </div>
                            <div class="product-buttons"><a href="#" class="btn btn-solid">add to cart</a> <a href="#" class="btn btn-solid">view detail</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Quick-view modal popup end-->

<!-- added to cart notification -->
<div class="added-notification">
    <h3 class="add-to-cart-text">added to cart</h3>
</div>
<!-- added to cart notification -->

<?php include 'theme_footer.php'; ?>


<script>
    function openSearch() {
        document.getElementById("search-overlay").style.display = "block";
    }

    function closeSearch() {
        document.getElementById("search-overlay").style.display = "none";
    }

    // close filter category bar 

    $('.category-filter-box .collection-collapse-block-content').hide();

    // check and uncheck filter 

    $('.category-filter-input, .category-filter-label').click(function() {
        $('.category-filter-input').prop('checked', false);
        $(this).prop('checked', true);
        $(this).siblings('.category-filter-input').prop('checked', true);
        $('.main-category-input').val($(this).data('val'));
    })


    $('.size-input, .size-label').click(function() {
        $('.size-input').prop('checked', false);
        $(this).prop('checked', true);
        $(this).siblings('.size-input').prop('checked', true);
        $('.main-size-input').val($(this).data('val'));
    })

    $('.color-filter').click(function() {
        $('.main-color-input').val($(this).data('val'));
    })

    // filter request 

    $('.custom-filter').click(function() {
        if ($(this).hasClass('filtered')) {
            $(this).prop('checked', false);
            $(this).siblings('.size-input').prop('checked', false);
            $(this).siblings('.category-filter-input').prop('checked', false);
            $('.' + $(this).data('input')).val(0);
            window.location.replace(
                `shop.php?filter=1&sub_category=${$('.main-category-input').val()}&color=${$('.main-color-input').val()}&size=${$('.main-size-input').val()}`
            );
        } else {
            window.location.replace(
                `shop.php?filter=1&sub_category=${$('.main-category-input').val()}&color=${$('.main-color-input').val()}&size=${$('.main-size-input').val()}`
            );
        }

    })

    // products navigation 

    <?php if (isset($_GET['page'])) {
        echo '$("ul.first-page li:nth-child(' . filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) + 1 . ')").addClass("active");';
    } else {
        echo '$("ul.first-page li:nth-child(2)").addClass("active");';
    } ?>

    // customize quick view modal 

    $('.quick-view-icon').click(function() {
        $.post('shop-request.php', {
            quick_view: true,
            product_id: $(this).data('id')
        }, function(data) {
            $('.theme-modal .modal-body').html(data);
        })
    })
</script>

</body>

</html>