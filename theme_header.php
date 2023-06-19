<?php


if (isset($_POST['switch_mood'])) {
    if (isset($_COOKIE['dark'])) {
        unset($_COOKIE['dark']);
        setcookie('dark', null, -1, '/');
    } else {
        setcookie("dark", 0, time() + 3600 * 24, "/");
    }
}

// get connection 

$stmt = $con->prepare("SELECT * FROM connection");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $phone = $row['phone'];
    $email = $row['email'];
    $whatsapp = $row['whatsapp'];
    $address = $row['address'];
    $facebook = $row['facebook'];
    $instagram = $row['instagram'];
    $twitter = $row['twitter'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">


    <meta name="keywords" content="multikart">
    <meta name="author" content="multikart">
    <link rel="icon" href="assets/img/logo/<?php echo $logo; ?>" type="image/x-icon">
    <link rel="shortcut icon" href="assets/img/logo/<?php echo $logo; ?>" type="image/x-icon">
    <title><?php echo $title; ?> | <?php echo $slogan; ?></title>
    <meta name="description" content=<?php echo filter_var($description, FILTER_SANITIZE_STRING); ?> />

    <!--Google font-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Yellowtail&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">

    <!--Slick slider css-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/slick.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/slick-theme.css">

    <!-- Animate icon -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/animate.css">

    <!-- Themify icon -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify-icons.css">

    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">

    <!-- Theme css -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <style>
        body {
            overflow-x: hidden !important;
        }

        .coins span {
            position: absolute;
            background: var(--theme-color);
            width: 20px;
            height: 20px;
            color: #fff;
            border-radius: 20px;
            text-align: center;
            font-size: 12px;
            line-height: 14px;
            font-weight: 600;
            top: 20%;
            right: -8px;
            padding: 3px;
        }

        @media (max-width: 577px) {
            .coins span {
                top: -10px;
            }
        }

        .dark .coin-icon-img {
            filter: invert(1) !important;
        }

        .dark-icon i:before {
            content: '\f186' !important;
        }

        .light-icon i:before {
            content: '\f0eb' !important;
        }

        @media (max-width: 580px) {
            .light-icon i:before {
                font-size: 24px;
            }
        }

        .dark .light-icon i {
            color: #999 !important;

        }


        @media (min-width: 580px) {

            .theme-light-mood i {
                font-size: 18px;
            }

        }

        /* spinner */

        .sk-circle {
            width: 100%;
            height: 100%;
            position: fixed;
            z-index: 9999;
            background-color: rgba(1, 1, 1, 0.3);
            display: none;
        }

        .sk-circle .sk-child {
            width: 55px;
            height: 55px;
            position: absolute;
            left: 49%;
            top: 49%;
        }

        .sk-circle .sk-child:before {
            content: '';
            display: block;
            margin: 0 auto;
            width: 15%;
            height: 15%;
            background-color: var(--theme-color);
            border-radius: 100%;
            -webkit-animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
            animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
        }

        .sk-circle .sk-circle2 {
            -webkit-transform: rotate(30deg);
            -ms-transform: rotate(30deg);
            transform: rotate(30deg);
        }

        .sk-circle .sk-circle3 {
            -webkit-transform: rotate(60deg);
            -ms-transform: rotate(60deg);
            transform: rotate(60deg);
        }

        .sk-circle .sk-circle4 {
            -webkit-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            transform: rotate(90deg);
        }

        .sk-circle .sk-circle5 {
            -webkit-transform: rotate(120deg);
            -ms-transform: rotate(120deg);
            transform: rotate(120deg);
        }

        .sk-circle .sk-circle6 {
            -webkit-transform: rotate(150deg);
            -ms-transform: rotate(150deg);
            transform: rotate(150deg);
        }

        .sk-circle .sk-circle7 {
            -webkit-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            transform: rotate(180deg);
        }

        .sk-circle .sk-circle8 {
            -webkit-transform: rotate(210deg);
            -ms-transform: rotate(210deg);
            transform: rotate(210deg);
        }

        .sk-circle .sk-circle9 {
            -webkit-transform: rotate(240deg);
            -ms-transform: rotate(240deg);
            transform: rotate(240deg);
        }

        .sk-circle .sk-circle10 {
            -webkit-transform: rotate(270deg);
            -ms-transform: rotate(270deg);
            transform: rotate(270deg);
        }

        .sk-circle .sk-circle11 {
            -webkit-transform: rotate(300deg);
            -ms-transform: rotate(300deg);
            transform: rotate(300deg);
        }

        .sk-circle .sk-circle12 {
            -webkit-transform: rotate(330deg);
            -ms-transform: rotate(330deg);
            transform: rotate(330deg);
        }

        .sk-circle .sk-circle2:before {
            -webkit-animation-delay: -1.1s;
            animation-delay: -1.1s;
        }

        .sk-circle .sk-circle3:before {
            -webkit-animation-delay: -1s;
            animation-delay: -1s;
        }

        .sk-circle .sk-circle4:before {
            -webkit-animation-delay: -0.9s;
            animation-delay: -0.9s;
        }

        .sk-circle .sk-circle5:before {
            -webkit-animation-delay: -0.8s;
            animation-delay: -0.8s;
        }

        .sk-circle .sk-circle6:before {
            -webkit-animation-delay: -0.7s;
            animation-delay: -0.7s;
        }

        .sk-circle .sk-circle7:before {
            -webkit-animation-delay: -0.6s;
            animation-delay: -0.6s;
        }

        .sk-circle .sk-circle8:before {
            -webkit-animation-delay: -0.5s;
            animation-delay: -0.5s;
        }

        .sk-circle .sk-circle9:before {
            -webkit-animation-delay: -0.4s;
            animation-delay: -0.4s;
        }

        .sk-circle .sk-circle10:before {
            -webkit-animation-delay: -0.3s;
            animation-delay: -0.3s;
        }

        .sk-circle .sk-circle11:before {
            -webkit-animation-delay: -0.2s;
            animation-delay: -0.2s;
        }

        .sk-circle .sk-circle12:before {
            -webkit-animation-delay: -0.1s;
            animation-delay: -0.1s;
        }

        @-webkit-keyframes sk-circleBounceDelay {

            0%,
            80%,
            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            40% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }

        @keyframes sk-circleBounceDelay {

            0%,
            80%,
            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            40% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }
    </style>

</head>

<body class="theme-color-1 <?php if (isset($_COOKIE['dark'])) {
                                echo 'dark';
                            } ?>" style="overflow-x: hidden;--theme-color:<?php echo $color; ?>">

    <!-- sopinner loader -->
    <div class="sk-circle">
        <div class="sk-circle1 sk-child"></div>
        <div class="sk-circle2 sk-child"></div>
        <div class="sk-circle3 sk-child"></div>
        <div class="sk-circle4 sk-child"></div>
        <div class="sk-circle5 sk-child"></div>
        <div class="sk-circle6 sk-child"></div>
        <div class="sk-circle7 sk-child"></div>
        <div class="sk-circle8 sk-child"></div>
        <div class="sk-circle9 sk-child"></div>
        <div class="sk-circle10 sk-child"></div>
        <div class="sk-circle11 sk-child"></div>
        <div class="sk-circle12 sk-child"></div>
    </div>

    <!-- loader start -->
    <div class="loader_skeleton">
        <div class="top-header">
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
        <header>
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
                                                <a href="shop.php">Shop</a>
                                            </li>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div>
                                    <div class="icon-nav d-none d-sm-block">
                                        <ul>

                                            <li class="onhover-div mobile-search">
                                                <div><img src="assets/img/icon/search.png" onclick="openSearch()" class="img-fluid blur-up lazyload" alt=""> <i class="ti-search" onclick="openSearch()"></i></div>
                                            </li>
                                            <li class="onhover-div mobile-coins">
                                                <div><img src="assets/img/icon/coin.png" class="img-fluid blur-up lazyload " alt=""> <i class=" d-sm-none ti-wallet"></i></div>
                                            </li>
                                            <li class="onhover-div mobile-cart">
                                                <div><img src="assets/img/icon/cart.png" class="img-fluid blur-up lazyload " alt=""> <i class="ti-shopping-cart"></i></div>
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
        <div class="home-slider">
            <div class="home"></div>
        </div>


    </div>
    <!-- loader end -->


    <!-- header start -->
    <header>
        <div class="mobile-fix-option"></div>
        <div class="top-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="header-contact">
                            <ul>
                                <li>Welcome to <?php echo $title; ?></li>
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
                                <li class="mobile-account light-icon theme-light-mood"><a href="javascript:void(0)"><i style="font-family: \'FontAwesome\';" class="fa fa-moon-o" aria-hidden="true"></i></a>
                                ';
                            } else {
                                echo '
                                <li class="mobile-account dark-icon theme-light-mood"><a href="javascript:void(0)"><i style="font-family: \'FontAwesome\';" class="fa fa-moon-o" aria-hidden="true"></i></a>
                                ';
                            }
                            ?>
                            </li>
                            <li class="onhover-dropdown mobile-account d-none d-sm-inline"> <i class="fa fa-user" aria-hidden="true"></i>
                                My Account
                                <ul class="onhover-show-div">
                                    <?php
                                    if (isset($_COOKIE['key'])) {
                                        echo '
                                        <li><a href="profile.php">Profile</a></li>
                                        <li><a href="login.php?logOut=true">logout</a></li>
                                        ';
                                    } else {
                                        echo '
                                        <li><a href="login.php">login</a></li>
                                        ';
                                    }
                                    ?>
                                </ul>
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
                                <a href="index.php"><img src="assets/img/logo/<?php echo $logo; ?>" class="img-fluid blur-up lazyload" alt="<?php echo $title; ?>"></a>
                            </div>
                        </div>
                        <div class="menu-right pull-right">
                            <div>
                                <!-- real nav -->
                                <nav id="main-nav">
                                    <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
                                    <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                                        <li>
                                            <div class="mobile-back text-end">Back<i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                                        </li>
                                        <li><a href="index.php">Home</a></li>
                                        <li><a href="shop.php">Shop</a></li>

                                        <?php
                                        // get categories 
                                        $stmt = $con->prepare("SELECT category FROM prod_category WHERE status != 0");
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll();

                                        foreach ($rows as $row) {
                                            echo '
                                            <li>
                                            <a href="shop.php?filter=1&category=' . $row['category'] . '">' . $row['category'] . '</a>
                                            <ul>
                                            ';

                                            // get sub categories 
                                            $stmt = $con->prepare("SELECT sub_category FROM sub_category WHERE category = ? AND status != 0");
                                            $stmt->execute(array($row['category']));
                                            $subs = $stmt->fetchAll();

                                            foreach ($subs as $sub) {
                                                echo '
                                                <li><a href="shop.php?filter=1&sub_category=' . $sub['sub_category'] . '">' . $sub['sub_category'] . '</a></li>
                                                ';
                                            }
                                            echo '
                                            </ul>
                                        </li>
                                            ';
                                        }
                                        ?>

                                        <li class="d-sm-none">
                                            <a href="shop.php">My Account</a>
                                            <ul>
                                                <?php
                                                if (isset($_COOKIE['key'])) {
                                                    echo '
                                                <li><a href="profile.php">Profile</a></li>
                                                <li><a href="login.php?logOut=true">logout</a></li>
                                                ';
                                                } else {
                                                    echo '
                                                <li><a href="login.php">login</a></li>
                                                ';
                                                }
                                                ?>
                                            </ul>
                                        </li>

                                    </ul>
                                </nav>
                            </div>
                            <div>
                                <div class="icon-nav">
                                    <ul>
                                        <li class="onhover-div mobile-search">
                                            <div><img src="assets/img/icon/search.png" onclick="openSearch()" class="img-fluid blur-up lazyload" alt=""> <i class="ti-search" onclick="openSearch()"></i></div>
                                            <div id="search-overlay" class="search-overlay">
                                                <div> <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
                                                    <div class="overlay-content">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-xl-12">
                                                                    <form action="shop.php" method="GET">
                                                                        <div class="form-group">
                                                                            <input type="text" name="search" class="form-control" id="exampleInputPassword1" placeholder="Search a Product">
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="onhover-div mobile-setting coins">
                                            <div><img src="assets/img/icon/coin.png" class="img-fluid blur-up lazyloaded coin-icon-img" alt="Your coins"> <i class=" ti-wallet"></i></div>
                                            <?php
                                            if (isset(($_COOKIE['key']))) {
                                                $coins = 0;
                                                $stmt = $con->prepare("SELECT coins FROM users WHERE user_key = ? ");
                                                $stmt->execute(array($_COOKIE['key']));
                                                $rows = $stmt->fetchAll();

                                                foreach ($rows as $row) {
                                                    echo '<span class="' . ($coins + $row['coins']) . '">' . ($coins + $row['coins']) . '</span>';
                                                }
                                            } else {
                                                echo '<span class="0">0</span>';
                                            }
                                            ?>
                                        </li>

                                        <li class="onhover-div mobile-cart mini-cart-content">
                                            <div><img src="assets/img/icon/cart.png" class="img-fluid blur-up full-cart lazyload" alt=""> <i class="ti-shopping-cart"></i></div>
                                            <?php
                                            $total = 0;
                                            // if login 

                                            if (isset($_COOKIE['key'])) {

                                                // get cart 

                                                $stmt = $con->prepare("SELECT product_id, quantity, id FROM cart WHERE user_key = ? ORDER BY id DESC");
                                                $stmt->execute(array($_COOKIE['key']));
                                                $carts = $stmt->fetchAll();
                                                echo '<span class="cart_qty_cls">' . count($carts) . '</span>
                                                <ul class="show-div shopping-cart">
                                                ';
                                                foreach ($carts as $cart) {
                                                    echo '
                                                    <li>
                                                    
                                                    ';
                                                    // get product info 

                                                    $stmt = $con->prepare("SELECT title, price, discount, trade_price, images FROM products WHERE id = ?");
                                                    $stmt->execute(array($cart['product_id']));
                                                    $products = $stmt->fetchAll();

                                                    foreach ($products as $product) {
                                                        $img = explode(',', $product['images']);
                                                        echo '
                                                        <div class="media">
                                                        <a href="product-details.php?id=' . $cart['product_id'] . '"><img width="90px" class="me-3" src="assets/img/products/' . $img[1] . '" alt="' . $product['title'] . '"></a>
                                                        <div class="media-body">
                                                            <a href="#">
                                                                <h4>' . $product['title'] . '</h4>
                                                            </a>
                                                            <h4><span>' . $cart['quantity'] . ' x LE ';

                                                        if ($product['discount'] != 0) {
                                                            echo $product['discount'];
                                                            $total = $total + ($product['discount'] * $cart['quantity']);
                                                        } else {
                                                            echo $product['price'];
                                                            $total = $total + ($product['price'] * $cart['quantity']);
                                                        };

                                                        echo '</span></h4>
                                                        </div>
                                                    </div>
                                                    <div class="close-circle">
                                                        <a href="javascript:void(0)" data-quantity=' . $cart['quantity'] . ' class="delete-mini-cart-item" data-id=' . $cart['id'] . ' ><i class="fa fa-times"  aria-hidden="true"></i></a>
                                                    </div>
                                                        ';
                                                    }
                                                    echo '
                                                </li>
                                                    ';
                                                }
                                                echo '
                                                <li>
                                                <div class="total">
                                                    <h5>subtotal : <span class="mini-cart-total">LE' . $total . '</span></h5>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="buttons"><a href="cart.php" class="view-cart">view
                                                        cart</a>
                                                        ';
                                                if (count($carts) != 0) {
                                                    echo '
                                                            <a href="confirm-order.php" class="checkout">checkout</a>
                                                            ';
                                                }
                                                echo ' </div>
                                            </li>
                                                </ul>
                                                ';
                                            }
                                            ?>

                                    </ul>
                                    </li>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header end -->