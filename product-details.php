<?php

include 'connect.php';

// check id 

if (isset($_GET['id'])) {

    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // check if id is right

    $stmt = $con->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->execute(array($id));
    $count = $stmt->rowCount();
    if (!($count > 0)) {

        // if id doesn't exist 
        header('location: error.php');
        exit();
    }
} else {
    header('location: error.php');
    exit();
}

// show reviews 

if (isset($_POST['show_reviews'])) {
    $stmt = $con->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY id DESC ");
    $stmt->execute(array($id));
    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        echo '
            <div class="col-12">
            <div class="row">
            <div class="col-12">
            <span>' . $row['name'] . '</span>
            </div>
            <div class="col-12">
                <p style="color: var(--theme-color)">' . $row['review_title'] . '</p>
            </div>
            <div class="col-12">
                <p>' . $row['review_text'] . '</p>
            </div>
            ';
        if (isset($_COOKIE['admin_key'])) {
            echo '
                    <div class="col-12">
                    <p>email : ' . $row['email'] . '</p>
                </div>
                    ';
        }
        echo '
            <hr>
            </div>
            </div>
            ';
    }
    exit();
}

// add rewview 

if (isset($_POST['add_review'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $text = filter_var($_POST['text'], FILTER_SANITIZE_STRING);

    $stmt = $con->prepare('INSERT INTO reviews (product_id, name, email, review_text, review_title) 
                        VALUES (:id, :name, :email, :text, :title)');
    $stmt->execute(array(
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'text' => $text,
        'title' => $title
    ));

    exit();
}

// delete review 

if (isset($_POST['delete_review'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $stmt = $con->prepare("DELETE FROM `reviews` WHERE `reviews`.`id` = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $count = $stmt->rowCount();
    echo 'done';
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
    $trade_price = $row['trade_price'];
    $discount = $row['discount'];
    $quantity = $row['quantity'];
    $color = $row['color'];
    $product_images = explode(',', $row['images']);
    $sizes = explode(',', $row['size']);
    $tags = $row['tags'];
    $category = $row['category'];
    $sub_category = $row['sub_category'];
}

$stmt = $con->prepare("SELECT status FROM prod_category WHERE category = ? AND status = 1");
$stmt->execute(array($category));
$count_category = $stmt->rowCount();
if ($count_category > 0 or $category == '') {
    // check sub category
    $stmt = $con->prepare("SELECT status FROM sub_category WHERE sub_category = ? AND status = 1");
    $stmt->execute(array($sub_category));
    $count_sub_category = $stmt->rowCount();
    if ($count_sub_category > 0 or $sub_category == '') {
        // check color 
        $stmt = $con->prepare("SELECT color_name, color_code FROM prod_color WHERE color_name = ? AND status = 1");
        $stmt->execute(array($color));
        $count_color = $stmt->rowCount();
        if ($count_color > 0 or $color == '') {
            $colors = $stmt->fetchAll();
            foreach ($colors as $color_codes) {
                $color_code = $color_codes['color_code'];
            }
        } else {
            header('location: error.php');
            exit();
        }
    } else {
        header('location: error.php');
        exit();
    }
} else {
    header('location: error.php');
    exit();
}
// get ecommerce main info 
include 'connect.php';
$stmt = $con->prepare("SELECT * FROM web_info");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $logo = $row['logo'];
    $color = $row['color'];
    $slogan = $row['title'];
    $real_slogan = $row['slogan'];
    $shiiping = $row['shipping'];
}
include 'theme_header.php';



?>




<!-- loader start -->
<div class="loader_skeleton">
    <header>
        <div class="top-header d-none d-sm-block">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="header-contact">
                            <ul>
                                <li> Welcome to <?php echo $title; ?></li>
                                <li><i class="fa fa-phone" aria-hidden="true"></i>call us : <?php echo $phone ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 text-end">
                        <ul class="header-dropdown">
                            <li class="mobile-wishlist"><a href="wishlist.php"><i class="fa fa-heart" aria-hidden="true"></i></a>
                            </li>
                            <?php
                            if (isset($_COOKIE['dark'])) {
                                echo '
                                <li class="onhover-dropdown mobile-account theme-light-mood"> <i class="fa fa-lightbulb-o" aria-hidden="true"></i>

                                ';
                            } else {
                                echo '
                                <li class="onhover-dropdown mobile-account theme-light-mood"> <i class="fa fa-moon-o" aria-hidden="true"></i>

                                ';
                            }
                            ?>

                            </li>
                            <li class="onhover-dropdown mobile-account"> <i class="fa fa-user" aria-hidden="true"></i>
                                My Account
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="main-menu">
                        <div class="menu-left">

                            <div class="brand-logo">
                                <a href="index.php"><img src="assets/img/logo/<?php echo $logo; ?>" class="img-fluid blur-up lazyload" alt=""></a>
                            </div>
                        </div>
                        <div class="menu-right pull-right">
                            <div>
                                <nav>
                                    <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
                                    <ul class="sm pixelstrap sm-horizontal">
                                        <li>
                                            <div class="mobile-back text-end">Back<i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                                        </li>
                                        <li>
                                            <a href="index.php">Home</a>
                                        </li>

                                        <li>
                                            <a href="shop.php">shop</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div>
                                <div class="icon-nav d-none d-sm-block">
                                    <ul>
                                        <li class="onhover-div mobile-search">
                                            <div><img src="assets/images/icon/search.png" onclick="openSearch()" class="img-fluid blur-up lazyload" alt=""> <i class="ti-search" onclick="openSearch()"></i></div>
                                        </li>
                                        <li class="onhover-div mobile-setting">
                                            <div><img src="assets/images/icon/setting.png" class="img-fluid blur-up lazyload" alt=""> <i class="ti-settings"></i></div>
                                        </li>
                                        <li class="onhover-div mobile-cart">
                                            <div><img src="assets/images/icon/cart.png" class="img-fluid blur-up lazyload" alt=""> <i class="ti-shopping-cart"></i></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="section-b-space ratio_asos">
        <div class="collection-wrapper product-page">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-sm-12 col-xs-12">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="main-product"></div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="sm-product"></div>
                                        </div>
                                        <div class="col-4">
                                            <div class="sm-product"></div>
                                        </div>
                                        <div class="col-4">
                                            <div class="sm-product"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="product-right">
                                        <h2></h2>
                                        <h4></h4>
                                        <h3></h3>
                                        <ul>
                                            <li></li>
                                            <li></li>
                                            <li></li>
                                            <li></li>
                                            <li></li>
                                        </ul>
                                        <div class="btn-group">
                                            <div class="btn-ldr"></div>
                                            <div class="btn-ldr"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <section class="tab-product m-0">
                            <div class="row">
                                <div class="col-sm-12 col-lg-12">
                                    <ul>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                    <p></p>
                                    <p></p>
                                    <p></p>
                                    <p></p>
                                    <p></p>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-sm-3 collection-filter">

                        <!-- side-bar single product slider start -->
                        <div class="theme-card">
                            <h5 class="title-border"></h5>
                            <div>
                                <div class="product-box">
                                    <div class="media">
                                        <div class="img-wrapper"></div>
                                        <div class="media-body align-self-center">
                                            <div class="product-detail">
                                                <h4></h4>
                                                <h6></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-box">
                                    <div class="media">
                                        <div class="img-wrapper"></div>
                                        <div class="media-body align-self-center">
                                            <div class="product-detail">
                                                <h4></h4>
                                                <h6></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-box">
                                    <div class="media">
                                        <div class="img-wrapper"></div>
                                        <div class="media-body align-self-center">
                                            <div class="product-detail">
                                                <h4></h4>
                                                <h6></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- side-bar single product slider end -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- loader end -->

<!-- section start -->
<section class="section-b-space">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-sm-12">
                    <div class="container-fluid p-0">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="product-slick">
                                    <?php
                                    unset($product_images[0]);
                                    foreach ($product_images as $img) {
                                        echo '
                                                <div><img src="assets/img/products/' . $img . '" alt="" class="img-fluid blur-up lazyload image_zoom_cls-0"></div>
                                                    ';
                                    }
                                    ?>
                                </div>
                                <div class="row">
                                    <div class="col-12 p-0">
                                        <div class="slider-nav">
                                            <?php
                                            foreach ($product_images as $img) {
                                                echo '
                                                    <div><img src="assets/img/products/' . $img . '" alt="" class="img-fluid blur-up lazyload"></div>

                                                    ';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 rtl-text">
                                <div class="product-right">

                                    <h2><?php echo $title; ?></h2>
                                    <?php
                                    if (isset($_COOKIE['trade'])) {
                                        echo '
                                        <h3 class="price-detail"> LE ' . $trade_price . ' </h3>
                                        ';
                                    } elseif ($discount != 0) {
                                        echo '
                                            <h3 class="price-detail"> LE ' . $discount . ' <del> LE ' . $price . '</del><span> ' . (100 - ceil($discount * 100 / $price)) . '% off</span></h3>
                                            ';
                                    } else {
                                        echo '
                                        <h3 class="price-detail"> LE ' . $price . ' </h3>
                                        ';
                                    }
                                    ?>


                                    <?php
                                    if ($count_color > 0) {
                                        echo '
                                                <ul class="color-variant">
                                                <li class="bg-light1" style="background-color: ' . $color_code . '"></li>
                                                </ul>
                                                ';
                                    }
                                    ?>


                                    <div id="selectSize" class="addeffect-section product-description border-product">
                                        <?php
                                        if (!(empty($sizes))) {

                                            // check size chart 
                                            $stmt = $con->prepare("SELECT size_chart FROM sub_category WHERE sub_category = ?");
                                            $stmt->execute(array($sub_category));
                                            $rows = $stmt->fetchAll();

                                            foreach ($rows as $row) {
                                                if ($row['size_chart'] != '') {
                                                    echo '
                                                <h6 class="product-title size-text">select size <span><a href="" data-bs-toggle="modal" data-bs-target="#sizemodal">size
                                                chart</a></span></h6>
                                                <div class="modal fade" id="sizemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            </div>
                                                            <div class="modal-body"><img src="assets/img/categories/' . $row['size_chart'] . '" alt="" class="img-fluid blur-up lazyload"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                ';
                                                }
                                            }


                                            echo '
                                            <div class="size-box">
                                            <ul class="selected">
                                            ';
                                            foreach ($sizes as $size) {
                                                $stmt = $con->prepare("SELECT size FROM prod_size WHERE size = ? AND status != 0");
                                                $stmt->execute(array($size));
                                                $count = $stmt->rowCount();
                                                if ($count > 0) {
                                                    echo '
                                                                <li><a href="javascript:void(0)">' . $size . '</a></li>
                                                            ';
                                                }
                                            }
                                            echo '
                                            </ul>
                                            </div>
                                            ';
                                        }
                                        ?>

                                        <h6 class="product-title">quantity</h6>
                                        <div class="qty-box">
                                            <?php
                                            if ($quantity == 0) {
                                                echo '
                                                <p class="primary">Unfortunately, stock is out!</p>
                                                ';
                                            } else {
                                                echo '
                                            <div class="input-group"><span class="input-group-prepend"><button type="button" class="btn quantity-left-minus" data-type="minus" data-field=""><i class="ti-angle-left"></i></button> </span>
                                            <input type="text" name="quantity" data-max=' . $quantity . ' class="form-control input-number qty-input" value="1"> <span class="input-group-prepend"><button type="button" class="btn quantity-right-plus" data-type="plus" data-field=""><i class="ti-angle-right"></i></button></span>
                                            </div>
                                            ';
                                            }
                                            ?>

                                        </div>
                                    </div>
                                    <div class="product-buttons"><a href="javascript:void(0)" id="cartEffect" class="btn btn-solid hover-solid btn-animation add-to-cart-btn"><i class="fa fa-shopping-cart me-1" aria-hidden="true"></i> add to
                                            cart</a> <a href="javascript:void(0)" data-id=<?php echo $id; ?> class="btn btn-solid whishlist-button"><i class="fa fa-bookmark fz-16 me-2" aria-hidden="true"></i>wishlist</a></div>

                                    <div class="border-product">
                                        <h6 class="product-title">shipping info</h6>
                                        <ul class="shipping-info">
                                            <li><?php echo $shiiping; ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="tab-product m-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab" href="#top-home" role="tab" aria-selected="true"><i class="icofont icofont-ui-home"></i>Details</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <li class="nav-item"><a class="nav-link reviews-tab" id="reviews-top-tab" data-bs-toggle="tab" href="#top-reviews" role="tab" aria-selected="false"><i class="icofont icofont-contacts"></i>
                                            Reviews</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <li class="nav-item"><a class="nav-link add-review-tab" id="review-top-tab" data-bs-toggle="tab" href="#top-review" role="tab" aria-selected="false"><i class="icofont icofont-contacts"></i>Write
                                            a Review</a>
                                        <div class="material-border"></div>
                                    </li>

                                </ul>
                                <div class="tab-content nav-material" id="top-tabContent">
                                    <div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                                        <style>
                                            .dark .product-tab-discription * {
                                                background-color: #2b2b2b !important;
                                                color: #777 !important;
                                            }
                                        </style>
                                        <div class="product-tab-discription">
                                            <?php echo $description; ?>
                                        </div>
                                    </div>


                                    <div class="tab-pane fade show-reviews-container" id="top-reviews" role="tabpanel" aria-labelledby="reviews-top-tab">
                                        <div class="row reviews-box">

                                            <?php
                                            // show reviews

                                            $stmt = $con->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY id DESC ");
                                            $stmt->execute(array($id));
                                            $rows = $stmt->fetchAll();

                                            foreach ($rows as $row) {
                                                echo '
                                                    <div class="col-12">
                                                    <div class="row">
                                                    <div class="col-12">
                                                    <span>' . $row['name'] . '</span>
                                                    </div>
                                                    <div class="col-12">
                                                        <p style="color: var(--theme-color)">' . $row['review_title'] . '</p>
                                                    </div>
                                                    <div class="col-12">
                                                        <p>' . $row['review_text'] . '</p>
                                                    </div>
                                                    ';
                                                if (isset($_COOKIE['admin_key'])) {
                                                    echo '
                                                        <div class="col-12">
                                                        <p>email : ' . $row['email'] . '</p>
                                                        <p data-id=' . $row['id'] . ' class="delete-review" style="color: var(--theme-color); cursor:pointer;">Delete review</p>
                                                    </div>
                                                        ';
                                                }
                                                echo '
                                                    <hr>
                                                    </div>
                                                    </div>
                                                    ';
                                            }


                                            ?>


                                        </div>
                                    </div>

                                    <div class="tab-pane fade add-reviews-container" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                                        <form class="theme-form form-review" method="POST" action="product-details.php?id=<?php echo $id; ?>">
                                            <div class="form-row row">
                                                <div class="col-md-6">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Your name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email">Email</label>
                                                    <input type="text" class="form-control" name="email" id="email" placeholder="Email" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="review">Review Title</label>
                                                    <input type="text" class="form-control" name="title" id="review" placeholder="Enter your Review Subjects" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="review">Review Text</label>
                                                    <textarea class="form-control" required name="text" placeholder="Wrire Your Testimonial Here" id="exampleFormControlTextarea1" rows="6"></textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <button class="btn btn-solid" name="add_review" type="submit">Submit Your
                                                        Review</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-sm-3 collection-filter">
                    <div class="collection-filter-block">
                        <div class="collection-mobile-back">
                            <span class="filter-back">
                                <i class="fa fa-angle-left" aria-hidden="true"></i>
                                back
                            </span>
                        </div>
                    </div>

                    <!-- side-bar single product slider start -->
                    <div class="theme-card">
                        <h5 class="title-border">people also buy : </h5>
                        <div class="offer-slider">
                            <div>

                                <?php

                                $products_arr = array();

                                $stmt = $con->prepare("SELECT * FROM products WHERE quantity != 0 AND id != ? AND tags LIKE '%$title%' ORDER BY id DESC LIMIT 3 ");
                                $stmt->execute(array($id));
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

                                                if (!in_array($row['id'], $products_arr)) {

                                                    $image = explode(',', $row['images']);
                                                    echo '
                                                <div class="media">
                                                <a href="product-details.php?id=' . $row['id'] . '"><img class="img-fluid blur-up lazyload" width="125px" src="assets/img/products/' . $image[1] . '" alt=""></a>
                                                <div class="media-body align-self-center">
                                                    <a href="product-details.php?id=' . $row['id'] . '">
                                                        <h6>' . $row['title'] . '</h6>
                                                    </a>
                                                    
                                                    ';
                                                    if (isset($_COOKIE['trade'])) {
                                                        echo '
                                                        <h3 class="price-detail"> LE ' . $row['trade_price'] . ' </h3>
                                                        ';
                                                    } elseif ($row['discount'] != 0) {
                                                        echo '
                                                        <h4> LE ' . $row['discount'] . '   <del> ' . $row['price'] . ' <del> </h4>
                                                        ';
                                                    } else {
                                                        echo '
                                                        <h4> LE ' . $row['price'] . '</h4>
                                                        ';
                                                    }

                                                    echo '
                                                </div>
                                            </div>
                                                ';

                                                    array_push($products_arr, $row['id']);
                                                }
                                            }
                                        }
                                    }
                                }


                                $stmt = $con->prepare("SELECT * FROM products WHERE quantity != 0 AND id != ? AND tags LIKE '%$tags%' order by RAND() LIMIT 3 ");
                                $stmt->execute(array($id));
                                $rows = $stmt->fetchAll();
                                foreach ($rows as $row) {
                                    if (count($products_arr) < 3) {
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

                                                    if (!in_array($row['id'], $products_arr)) {

                                                        $image = explode(',', $row['images']);
                                                        echo '
                                                <div class="media">
                                                <a href="product-details.php?id=' . $row['id'] . '"><img class="img-fluid blur-up lazyload" width="125px" src="assets/img/products/' . $image[1] . '" alt=""></a>
                                                <div class="media-body align-self-center">
                                                    <a href="product-details.php?id=' . $row['id'] . '">
                                                        <h6>' . $row['title'] . '</h6>
                                                    </a>
                                                    ';
                                                        if (isset($_COOKIE['trade'])) {
                                                            echo '
                                                        <h4 > LE ' . $row['trade_price'] . ' </h4>
                                                        ';
                                                        } elseif ($row['discount'] != 0) {
                                                            echo '
                                                        <h4> LE ' . $row['discount'] . '   <del> ' . $row['price'] . ' <del> </h4>
                                                        ';
                                                        } else {
                                                            echo '
                                                        <h4> LE ' . $row['price'] . '</h4>
                                                        ';
                                                        }

                                                        echo '
                                                </div>
                                            </div>
                                                ';

                                                        array_push($products_arr, $row['id']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                $stmt = $con->prepare("SELECT * FROM products WHERE quantity != 0 AND id != ? AND category = ? order by RAND() LIMIT 3 ");
                                $stmt->execute(array($id, $category));
                                $rows = $stmt->fetchAll();
                                foreach ($rows as $row) {
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

                                            if (!in_array($row['id'], $products_arr)) {

                                                $image = explode(',', $row['images']);
                                                echo '
                                                    <div class="media">
                                                    <a href="product-details.php?id=' . $row['id'] . '"><img class="img-fluid blur-up lazyload" width="125px" src="assets/img/products/' . $image[1] . '" alt=""></a>
                                                    <div class="media-body align-self-center">
                                                        <a href="product-details.php?id=' . $row['id'] . '">
                                                            <h6>' . $row['title'] . '</h6>
                                                        </a>
                                                        ';
                                                if (isset($_COOKIE['trade'])) {
                                                    echo '
                                                        <h4 > LE ' . $row['trade_price'] . ' </h4>
                                                        ';
                                                } elseif ($row['discount'] != 0) {
                                                    echo '
                                                        <h4> LE ' . $row['discount'] . '   <del> ' . $row['price'] . ' <del> </h4>
                                                        ';
                                                } else {
                                                    echo '
                                                        <h4> LE ' . $row['price'] . '</h4>
                                                        ';
                                                }

                                                echo '
                                                        </div>
                                                    </div>
                                                        ';

                                                array_push($products_arr, $row['id']);
                                            }
                                        }
                                    }
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- side-bar single product slider end -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Section ends -->

<!-- Product slider -->

<div class="title1 section-t-space d-md-none d-block">
    <h2 class="title-inner1">People also Buy</h2>
</div>

<section class="section-b-space pt-0 ratio_asos d-md-none d-block">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="product-4 product-m no-arrow">
                    <?php
                    $products_arr = array();

                    $stmt = $con->prepare("SELECT * FROM products WHERE quantity != 0 AND id != ? AND tags LIKE '%$title%' ORDER BY id DESC LIMIT 3 ");
                    $stmt->execute(array($id));
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

                                    if (!in_array($row['id'], $products_arr)) {

                                        $images = explode(',', $row['images']);

                                        echo '
                                        <div class="product-box">
                                        <div class="img-wrapper">
                                            <div class="front">
                                                <a href="product-details.php?id=' . $row['id'] . '"><img src="assets/img/products/' . $images[1] . '" class="img-fluid blur-up lazyload " alt="' . $row['title'] . '"></a>
                                            </div>
                                            <div class="back">
                                                <a href="product-details.php?id=' . $row['id'] . '"><img src="assets/img/products/' . $images[2] . '" class="img-fluid blur-up lazyload " alt="' . $row['title'] . '"></a>
                                            </div>
                                            <div class="cart-info cart-wrap">
                                                <button data-bs-toggle="modal" data-bs-target="#addtocart" data-id=' . $row['id'] . ' class="mini-add-to-cart" title="Add to cart">
                                                    <i class="ti-shopping-cart"></i>
                                                </button>
                                                <a href="javascript:void(0)" class="whishlist-button" data-id=' . $row['id'] . ' title="Add to Wishlist">
                                                    <i class="ti-heart" aria-hidden="true"></i>
                                                </a>
                                                <a href="#" data-bs-toggle="modal" data-id=' . $row['id'] . ' data-bs-target="#quick-view" title="Quick View" class="quick-view-icon">
                                                    <i class="ti-search" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                            </div>
                                            <a href="product-details.php?id=' . $row['id'] . '">
                                                <h6>' . $row['title'] . '</h6>
                                            </a>
                                            ';
                                        if (isset($_COOKIE['trade'])) {
                                            echo '
                                            <h4 > LE ' . $row['trade_price'] . ' </h4>
                                            ';
                                        } elseif ($row['discount'] == 0) {
                                            echo '
                                                                <h4>' . $row['price'] . '  EGP </h4>
                                                                ';
                                        } else {
                                            echo '
                                                                <h4> <del>' . $row['price'] . ' </del> ' . $row['discount'] . '  EGP  </h4>
                                                                ';
                                        }
                                        echo '
                                        </div>
                                        ';

                                        array_push($products_arr, $row['id']);
                                    }
                                }
                            }
                        }
                    }


                    $stmt = $con->prepare("SELECT * FROM products WHERE quantity != 0 AND id != ? AND tags LIKE '%$tags%' order by RAND() LIMIT 3 ");
                    $stmt->execute(array($id));
                    $rows = $stmt->fetchAll();
                    foreach ($rows as $row) {
                        if (count($products_arr) < 3) {
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

                                        if (!in_array($row['id'], $products_arr)) {

                                            $images = explode(',', $row['images']);

                                            echo '
                                            <div class="product-box">
                                            <div class="img-wrapper">
                                                <div class="front">
                                                    <a href="product-details.php?id=' . $row['id'] . '"><img src="assets/img/products/' . $images[1] . '" class="img-fluid blur-up lazyload " alt="' . $row['title'] . '"></a>
                                                </div>
                                                <div class="back">
                                                    <a href="product-details.php?id=' . $row['id'] . '"><img src="assets/img/products/' . $images[2] . '" class="img-fluid blur-up lazyload " alt="' . $row['title'] . '"></a>
                                                </div>
                                                <div class="cart-info cart-wrap">
                                                    <button data-bs-toggle="modal" data-bs-target="#addtocart" data-id=' . $row['id'] . ' class="mini-add-to-cart" title="Add to cart">
                                                        <i class="ti-shopping-cart"></i>
                                                    </button>
                                                    <a href="javascript:void(0)" class="whishlist-button" data-id=' . $row['id'] . ' title="Add to Wishlist">
                                                        <i class="ti-heart" aria-hidden="true"></i>
                                                    </a>
                                                    <a href="#" data-bs-toggle="modal" data-id=' . $row['id'] . ' data-bs-target="#quick-view" title="Quick View" class="quick-view-icon">
                                                        <i class="ti-search" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="product-detail">
                                                </div>
                                                <a href="product-details.php?id=' . $row['id'] . '">
                                                    <h6>' . $row['title'] . '</h6>
                                                </a>
                                                ';
                                            if (isset($_COOKIE['trade'])) {
                                                echo '
                                                <h4 > LE ' . $row['trade_price'] . ' </h4>
                                                ';
                                            } elseif ($row['discount'] == 0) {
                                                echo '
                                                                    <h4>' . $row['price'] . '  EGP </h4>
                                                                    ';
                                            } else {
                                                echo '
                                                                    <h4> <del>' . $row['price'] . ' </del> ' . $row['discount'] . '  EGP  </h4>
                                                                    ';
                                            }
                                            echo '
                                            </div>
                                            ';

                                            array_push($products_arr, $row['id']);
                                        }
                                    }
                                }
                            }
                        }
                    }


                    $stmt = $con->prepare("SELECT * FROM products WHERE quantity != 0 AND id != ? AND category = ? order by RAND() LIMIT 3 ");
                    $stmt->execute(array($id, $category));
                    $rows = $stmt->fetchAll();
                    foreach ($rows as $row) {
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

                                if (!in_array($row['id'], $products_arr)) {

                                    $images = explode(',', $row['images']);

                                    echo '
                                    <div class="product-box">
                                    <div class="img-wrapper">
                                        <div class="front">
                                            <a href="product-details.php?id=' . $row['id'] . '"><img src="assets/img/products/' . $images[1] . '" class="img-fluid blur-up lazyload " alt="' . $row['title'] . '"></a>
                                        </div>
                                        <div class="back">
                                            <a href="product-details.php?id=' . $row['id'] . '"><img src="assets/img/products/' . $images[2] . '" class="img-fluid blur-up lazyload " alt="' . $row['title'] . '"></a>
                                        </div>
                                        <div class="cart-info cart-wrap">
                                            <button data-bs-toggle="modal" data-bs-target="#addtocart" data-id=' . $row['id'] . ' class="mini-add-to-cart" title="Add to cart">
                                                <i class="ti-shopping-cart"></i>
                                            </button>
                                            <a href="javascript:void(0)" class="whishlist-button" data-id=' . $row['id'] . ' title="Add to Wishlist">
                                                <i class="ti-heart" aria-hidden="true"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-id=' . $row['id'] . ' data-bs-target="#quick-view" title="Quick View" class="quick-view-icon">
                                                <i class="ti-search" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-detail">
                                        </div>
                                        <a href="product-details.php?id=' . $row['id'] . '">
                                            <h6>' . $row['title'] . '</h6>
                                        </a>
                                        ';
                                    if (isset($_COOKIE['trade'])) {
                                        echo '
                                        <h4 > LE ' . $row['trade_price'] . ' </h4>
                                        ';
                                    } elseif ($row['discount'] == 0) {
                                        echo '
                                                            <h4>' . $row['price'] . '  EGP </h4>
                                                            ';
                                    } else {
                                        echo '
                                                            <h4> <del>' . $row['price'] . ' </del> ' . $row['discount'] . '  EGP  </h4>
                                                            ';
                                    }
                                    echo '
                                    </div>
                                    ';

                                    array_push($products_arr, $row['id']);
                                }
                            }
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- recently purchase product -->

<?php

$stmt = $con->prepare("SELECT * FROM products WHERE quantity != 0 AND discount != 0 ORDER BY RAND() LIMIT 1");
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
                $image = explode(',', $row['images']);
                echo '
                <div class="media recently-purchase">
                    <img src="assets/img/products/' . $image[1] . '" width="90px" alt="' . $row['title'] . ' ">
                    <div class="media-body">
                        <div>
                            <div class="title">
                               Check our offers
                            </div>
                            <a href="product-details.php?id=' . $row['id'] . '">
                                <span class="product-name">
                                ' . $row['title'] . '
                                </span>
                            </a>
                            ';
                if (isset($_COOKIE['trade'])) {
                    echo '
                    <h4> LE ' . $row['trade_price'] . ' </h4>
                    ';
                } else {
                    echo '
                                <h4> LE ' . $row['discount'] . '   <del> ' . $row['price'] . ' <del> </h4>
                                ';
                }
                echo '
                            </div>
                    </div>
                    <a href="javascript:void(0)" class="close-popup fa fa-times"></a>
                </div>
                ';
            }
        }
    }
}


?>


<!-- recently purchase product -->



<!-- sticky cart bottom start -->
<div class="sticky-bottom-cart d-sm-block d-none">
    <div class="container">
        <div class="cart-content">
            <div class="product-image">
                <img src="assets/images/pro3/1.jpg" class="img-fluid" alt="">
                <div class="content d-lg-block d-none">
                    <h5>WOMEN PINK SHIRT</h5>
                    <h6>$32.96<del>$459.00</del><span>55% off</span></h6>
                </div>
            </div>
            <div class="selection-section">
                <div class="form-group mb-0">
                    <select id="inputState" class="form-control">
                        <option selected>Choose color...</option>
                        <option>pink</option>
                        <option>blue</option>
                        <option>grey</option>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <select id="inputState" class="form-control">
                        <option selected>Choose size...</option>
                        <option>small</option>
                        <option>medium</option>
                        <option>large</option>
                        <option>extra large</option>
                    </select>
                </div>
            </div>
            <div class="add-btn">
                <a data-bs-toggle="modal" data-bs-target="#addtocart" class="mini-add-to-cart" href="" class="btn btn-solid btn-sm">add to
                    cart</a>
            </div>
        </div>
    </div>
</div>
<!-- sticky cart bottom end -->



<!-- Add to cart modal popup start-->
<div class="modal fade bd-example-modal-lg theme-modal cart-modal" id="addtocart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body modal1">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="modal-bg addtocart">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="media">
                                    <a href="#">
                                        <img class="img-fluid blur-up lazyload pro-img" src="assets/images/fashion/product/43.jpg" alt="">
                                    </a>
                                    <div class="media-body align-self-center text-center">
                                        <a href="#">
                                            <h6>
                                                <i class="fa fa-check"></i>Item
                                                <span>men full sleeves</span>
                                                <span> successfully added to your Cart</span>
                                            </h6>
                                        </a>
                                        <div class="buttons">
                                            <a href="#" class="view-cart btn btn-solid">Your cart</a>
                                            <a href="#" class="checkout btn btn-solid">Check out</a>
                                            <a href="#" class="continue btn btn-solid">Continue shopping</a>
                                        </div>

                                        <div class="upsell_payment">
                                            <img src="assets/images/payment_cart.png" class="img-fluid blur-up lazyload" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="product-section">
                                    <div class="col-12 product-upsell text-center">
                                        <h4>Customers who bought this item also.</h4>
                                    </div>
                                    <div class="row" id="upsell_product">
                                        <div class="product-box col-sm-3 col-6">
                                            <div class="img-wrapper">
                                                <div class="front">
                                                    <a href="#">
                                                        <img src="assets/images/fashion/product/1.jpg" class="img-fluid blur-up lazyload mb-1" alt="cotton top">
                                                    </a>
                                                </div>
                                                <div class="product-detail">
                                                    <h6><a href="#"><span>cotton top</span></a></h6>
                                                    <h4><span>$25</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-box col-sm-3 col-6">
                                            <div class="img-wrapper">
                                                <div class="front">
                                                    <a href="#">
                                                        <img src="assets/images/fashion/product/34.jpg" class="img-fluid blur-up lazyload mb-1" alt="cotton top">
                                                    </a>
                                                </div>
                                                <div class="product-detail">
                                                    <h6><a href="#"><span>cotton top</span></a></h6>
                                                    <h4><span>$25</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-box col-sm-3 col-6">
                                            <div class="img-wrapper">
                                                <div class="front">
                                                    <a href="#">
                                                        <img src="assets/images/fashion/product/13.jpg" class="img-fluid blur-up lazyload mb-1" alt="cotton top">
                                                    </a>
                                                </div>
                                                <div class="product-detail">
                                                    <h6><a href="#"><span>cotton top</span></a></h6>
                                                    <h4><span>$25</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-box col-sm-3 col-6">
                                            <div class="img-wrapper">
                                                <div class="front">
                                                    <a href="#">
                                                        <img src="assets/images/fashion/product/19.jpg" class="img-fluid blur-up lazyload mb-1" alt="cotton top">
                                                    </a>
                                                </div>
                                                <div class="product-detail">
                                                    <h6><a href="#"><span>cotton top</span></a></h6>
                                                    <h4><span>$25</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add to cart modal popup end-->


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
    <img src="assets/img/products/<?php echo $product_images[1] ?>" class="img-fluid" alt="">
    <h3 class="add-to-cart-text">added to cart</h3>
</div>
<!-- added to cart notification -->

<?php
$description = $real_slogan;
include 'theme_footer.php';
if (isset($_COOKIE['admin_key'])) {
    echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
}
?>

<script>
    $('.size-box .selected li:first-child').addClass('active');

    // change quantity 

    $(".quantity-right-plus").on("click", function() {
        var $qty = $(".qty-input");
        var currentVal = parseInt($qty.val());
        if (!isNaN(currentVal) && currentVal < $qty.data("max")) {
            $qty.val(currentVal + 1);
        }
    });
    $(".quantity-left-minus").on("click", function() {
        var $qty = $(".qty-input");
        var _val = $($qty).val();

        var currentVal = parseInt($qty.val());
        if (!isNaN(currentVal) && currentVal > 1) {
            $qty.val(currentVal - 1);
        }
    });
    $(".qty-input").change(function() {
        if ($(this).val() > $(this).data("max")) {
            $(this).val($(this).data("max"));
        }
    })

    // customize quick view modal 

    $('.quick-view-icon').click(function() {
        $.post('shop-request.php', {
            quick_view: true,
            product_id: $(this).data('id')
        }, function(data) {
            $('.theme-modal .modal-body').html(data);
        })
    })

    // add to cart

    $('.add-to-cart-btn').click(function() {

        $('.sk-circle').show();

        $.post('cart-request.php', {
            add: 1,
            id: <?php echo $id; ?>,
            quantity: $('.qty-input').val(),
            size: $('.size-box ul .active a').text()
        }, function(data) {
            $('.sk-circle').fadeOut();
            $('.add-to-cart-text').html(data);
            $('.added-notification').addClass('show');
            setTimeout(function() {
                $('.added-notification').removeClass('show');
            }, 5000);
            // update mini cart 
            $.post('cart-request.php', {
                update_mini_cart: true
            }, function(data) {
                $('.mini-cart-content').html(data);
            })
        })

    });
    $page_url = $(location).attr('href');

    // add review 

    $('.theme-form').submit(function(e) {
        e.preventDefault();

        $.post($page_url, {
            add_review: true,
            name: $('.theme-form input[name="name"]').val(),
            email: $('.theme-form input[name="email"]').val(),
            title: $('.theme-form input[name="title"]').val(),
            text: $('.theme-form textarea').val(),
        }, function(data) {
            $.post($page_url, {
                show_reviews: true,
            }, function(data) {
                $('.reviews-box').html(data);
                $('.add-review-tab').removeClass('active');
                $('.reviews-tab').addClass('active');
                $('.show-reviews-container').addClass('show active');
                $('.add-reviews-container').removeClass('show active');
                $('.add-to-cart-text').html('Your review has been sent!');
                $('.added-notification').addClass('show');
                setTimeout(function() {
                    $('.added-notification').removeClass('show');
                }, 2500);
                $('.theme-form').find(':input').each(function() {
                    $(this).val('');
                });
            })

        })
    })

    $('.delete-review').click(function() {
        swal({
                title: "Are you sure !?",
                text: "press ok and it will be deleted",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $(this).parentsUntil('.reviews-box').fadeOut('slow');

                    $.post($page_url, {
                        delete_review: true,
                        id: $(this).data('id')
                    });

                }
            });
    })
</script>
</body>

</html>