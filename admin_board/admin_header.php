<?php
// check login

if (isset($_COOKIE['admin_key'])) {

    $admin_key = filter_var($_COOKIE['admin_key'], FILTER_SANITIZE_STRING);

    // check if account is admin  

    $stmt = $con->prepare("SELECT admin_key FROM admin WHERE admin_key = ? AND manager = 0");
    $stmt->execute(array($admin_key));
    $count = $stmt->rowCount();

    if (!($count > 0)) {
        // check if account is manager 

        $stmt = $con->prepare("SELECT admin_key FROM admin WHERE admin_key = ? AND manager = 1");
        $stmt->execute(array($admin_key));
        $count = $stmt->rowCount();
        if (!($count > 0)) {
            header('location: login.php');
            exit();
        }
    }
} else {
    header('location: login.php');
    exit();
}

// end check login


?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Multikart admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Multikart admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="assets/images/dashboard/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/dashboard/favicon.png" type="image/x-icon">
    <title>Multikart - Premium Admin Template</title>

    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri&display=swap" rel="stylesheet">


    <!-- Datatables css-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatables.css">

    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">

    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">

    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">

    <!-- Prism css-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/prism.css">

    <!-- Chartist css -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/chartist.css">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">

    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify-icons.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- simditor -->
    <link rel="stylesheet" type="text/css" href="simditor/simditor.css" />

    <style>
        * {
            font-family: "El Messiri", sans-serif;
        }

        .custom-theme {
            display: none !important;
        }

        .page-body {
            margin-top: 110px;
        }


        .email-form {
            border: 2px solid #000;
            width: 80%;
            position: absolute;
            top: 10%;
            left: 15%;
            z-index: 999;
            text-align: center;
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 8%);
            min-height: 250px;
            padding: 20px;
            display: none;
            background-color: #eee;
        }

        .email-form p {
            font-size: 18px;
            font-weight: bold;
            color: #2f2f2f;
            margin-top: 20px;
        }

        .email-form .close {
            display: block;
            position: absolute;
            top: 0;
            right: 10px;
            font-size: 25px;
            color: #f00;
            font-weight: bold;
            padding: 20px;
            cursor: pointer;
        }

        .show {
            display: block !important;
        }
    </style>
</head>

<body dir="rtl" class="rtl">

    <!-- page-wrapper Start-->
    <div class="page-wrapper">

        <!-- Page Header Start-->
        <div class="page-main-header">
            <div class="main-header-right row">
                <div class="main-header-left d-lg-none w-auto">
                    <div class="logo-wrapper">
                        <a href="index.html">
                            <img class="blur-up lazyloaded d-block d-lg-none" src="assets/images/dashboard/multikart-logo-black.png" alt="">
                        </a>
                    </div>
                </div>
                <div class="mobile-sidebar w-auto">
                    <div class="media-body text-end switch-sm">
                        <label class="switch">
                            <a href="javascript:void(0)">
                                <i id="sidebar-toggle" data-feather="align-left"></i>
                            </a>
                        </label>
                    </div>
                </div>
                <div class="nav-right col">
                    <ul class="nav-menus">
                        <li>
                            <form class="form-inline search-form" method="GET" action="<?php echo $serch_page ?>">
                                <div class="form-group">
                                    <input class="form-control-plaintext" name="search" type="search" placeholder="<?php echo $serch_placeholder ?>">
                                    <span class="d-sm-none mobile-search">
                                        <i data-feather="search"></i>
                                    </span>
                                </div>
                            </form>
                        </li>
                        <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-maximize-2">
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <polyline points="9 21 3 21 3 15"></polyline>
                                    <line x1="21" y1="3" x2="14" y2="10"></line>
                                    <line x1="3" y1="21" x2="10" y2="14"></line>
                                </svg></a></li>
                    </ul>
                    <div class="d-lg-none mobile-toggle pull-right">
                        <i data-feather="more-horizontal"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header Ends -->

        <!-- Page Body Start-->
        <div class="page-body-wrapper">

            <!-- Page Sidebar Start-->
            <div class="page-sidebar">
                <div class="main-header-left d-none d-lg-block">
                    <div class="logo-wrapper">
                        <a href="index.php">
                            <img class="d-none d-lg-block blur-up lazyloaded" src="assets/images/dashboard/multikart-logo.png" alt="">
                        </a>
                    </div>
                </div>
                <div class="sidebar custom-scrollbar">
                    <a href="javascript:void(0)" class="sidebar-back d-lg-none d-block"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <div class="sidebar-user">
                        <img class="img-60" src="assets/images/dashboard/user3.jpg" alt="#">
                        <div>
                            <h6 class="f-14">JOHN</h6>
                            <p>general manager.</p>
                        </div>
                    </div>
                    <ul class="sidebar-menu">
                        <li>
                            <a class="sidebar-header" href="index.php">
                                <i data-feather="home"></i>
                                <span>لوحة التحكم</span>
                            </a>
                        </li>

                        <li>
                            <a class="sidebar-header" href="javascript:void(0)">
                                <i data-feather="box"></i>
                                <span>المنتجات</span>
                                <i class="fa fa-angle-right pull-right"></i>
                            </a>

                            <ul class="sidebar-submenu">

                                <li>
                                    <a href="product-list.php">
                                        <i class="fa fa-circle"></i>
                                        <span>قائمة المنتجات</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="category.php">
                                        <i class="fa fa-circle"></i>
                                        <span>الأقسام</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="category-sub.php">
                                        <i class="fa fa-circle"></i>
                                        <span>الأقسام الفرعية</span>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li>
                            <a class="sidebar-header" href="javascript:void(0)">
                                <i data-feather="archive"></i>
                                <span>الطلبات</span>
                                <i class="fa fa-angle-right pull-right"></i>
                            </a>

                            <ul class="sidebar-submenu">
                                <li>
                                    <a href="orders.php">
                                        <i class="fa fa-circle"></i>
                                        <span>قائمة الطلبات</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="returns.php">
                                        <i class="fa fa-circle"></i>
                                        <span>قائمة المرتجعات</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="active">
                            <a class="sidebar-header active" href="user-list.php">
                                <i data-feather="user-plus"></i>
                                <span>العملاء</span>
                            </a>
                        </li>


                        <li class="active">
                            <a class="sidebar-header active" href="admin-cart.php">
                                <i data-feather="shopping-cart"></i>
                                <span>السلات المتروكة</span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="sidebar-header active" href="coupons-list.php">
                                <i data-feather="tag"></i>
                                <span>كوبونات الخصم</span>
                            </a>
                        </li>

                        <li class="active">
                            <a class="sidebar-header active" href="shipping.php">
                                <i data-feather="truck"></i>
                                <span>الشحن</span>
                            </a>
                        </li>

                        <!-- <li>
                            <a class="sidebar-header" href="reports.html"><i data-feather="bar-chart"></i><span>التقارير</span>
                            </a>
                        </li> -->

                        <li class="active">
                            <a class="sidebar-header active" href="web-info.php">
                                <i data-feather="info"></i>
                                <span>المعلومات الاساسية</span>
                            </a>
                        </li>

                        <li class="active">
                            <a class="sidebar-header active" href="web-content.php">
                                <i data-feather="image"></i>
                                <span>صور الموقع </span>
                            </a>
                        </li>

                        <li>
                            <a class="sidebar-header" href="login.php?logout=1">
                                <i data-feather="log-out"></i>
                                <span>تسجيل الخروج</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Page Sidebar Ends-->

            <!-- Right sidebar Start-->
            <div class="right-sidebar" id="right_side_bar">
                <div>
                    <div class="container p-0">
                        <div class="modal-header p-l-20 p-r-20">
                            <div class="col-sm-8 p-0">
                                <h6 class="modal-title font-weight-bold">FRIEND LIST</h6>
                            </div>
                            <div class="col-sm-4 text-end p-0">
                                <i class="me-2" data-feather="settings"></i>
                            </div>
                        </div>
                    </div>
                    <div class="friend-list-search mt-0">
                        <input type="text" placeholder="search friend">
                        <i class="fa fa-search"></i>
                    </div>

                </div>
            </div>
            <!-- Right sidebar Ends-->