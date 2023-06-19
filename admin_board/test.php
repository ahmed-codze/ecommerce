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
    $trade_price = $row['trade_price'];
    $discount = $row['discount'];
    $quantity = $row['quantity'];
    $color = $row['color'];
    $images = $row['images'];
    $color_status = $row['color_status'];
    $category_status = $row['category_status'];
    $images = explode(',', $row['images']);
    $sizes = explode(',', $row['size']);
    $tags = $row['tags'];
    $category = $row['category'];
}

if ($color_status != 'visible' or $category_status != 'visible' or $quantity == 0) {
    header('location: products.php');
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri&display=swap" rel="stylesheet">
    <!--font -->
    <title><?php echo $title; ?> | ecommerce</title>
    <meta name="description" content="<?php echo $description; ?> | ecommerce">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendor/fontawesome/css/all.min.css" rel="stylesheet" />
    <link href="assets/vendor/wow/css/animate.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/vendor/nice select/css/nice-select.css" />
    <link href="assets/css/main.css" rel="stylesheet" />
    <link href="assets/css/product-details.css" rel="stylesheet" />
</head>

<body dir="rtl">

    <!-- start animated div  -->

    <div class="animate-container text-center"></div>

    <!-- end div -->

    <!-- start navbar -->

    <nav class="navbar navbar-expand-md navbar-light relative-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="assets/img/logo.png" alt="barnd logo" class="img-fluid" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav  mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link" title="الصفحة الرئيسية" aria-current="page" href="index.php">الرئيسية</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" title="الأقسام" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">الأقسام</a>
                        <ul class="dropdown-menu text-center" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" title="قسم التيشيرتات" href="products.php">تيشيرت</a></li>
                            <li><a class="dropdown-item" title="قسم البنطلونات" href="products.php">بنطلون</a></li>
                            <li><a class="dropdown-item" title="قسم الترينجات" href="products.php">ترينج</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" title="الملف الشخصي" href="profile.php">البروفايل</a>
                    </li>
                    <li class="nav-item  d-md-none">
                        <a class="nav-link" title="قائمة ملابسك المفضلة" href="profile.php">الأمنيات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="الأسئلة الشائعة" href="index.php#faq">الأسئلة الشائعة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="تواصل معنا" href="#contact">تواصل معنا</a>
                    </li>
                    <?php

                    // check login
                    if (isset($_COOKIE['key'])) {
                        echo '
            <li class="nav-item">
            <a class="nav-link" title="تسجيل الخروج من موقع ecommerce" href="login.php?logOut=true">تسجيل الخروج</a>
          </li>
            ';
                    } else {
                        echo '
            <li class="nav-item">
            <a class="nav-link" title="تسجيل دخولك في موقع ecommerce" href="login.php">تسجيل الدخول</a>
          </li>
            ';
                    }

                    ?>
                </ul>

                <div class="d-flex me-auto nav-icons d-none d-md-block">
                    <a href="cart.php" class="cart-link" title="سلة المشتريات">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-counter">
                            <?php

                            if (isset($_COOKIE['key'])) {
                                $user_key = $_COOKIE['key'];
                                $stmt = $con->prepare("SELECT * FROM cart WHERE user_key = ?");
                                $stmt->execute(array($user_key));
                                $count = $stmt->rowCount();
                                $rows = $stmt->fetchAll();
                                echo count($rows);
                            } else {
                                echo '0';
                            }

                            ?>
                        </span>
                    </a>
                    <a href="favourites.php" title="الأمنيات"><i class="fas fa-heart"></i></a>
                    <a class="search-icon" title="بحث"> <i class="fas fa-search"></i> </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- fixed navbar  -->

    <nav class="navbar navbar-expand-md fixed-top fixed-nav navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="assets/img/logo.png" alt="barnd logo" class="img-fluid" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav  mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link" title="الصفحة الرئيسية" aria-current="page" href="index.php">الرئيسية</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" title="الأقسام" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">الأقسام</a>
                        <ul class="dropdown-menu text-center" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" title="قسم التيشيرتات" href="products.php">تيشيرت</a></li>
                            <li><a class="dropdown-item" title="قسم البنطلونات" href="products.php">بنطلون</a></li>
                            <li><a class="dropdown-item" title="قسم الترينجات" href="products.php">ترينج</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" title="الملف الشخصي" href="profile.php">البروفايل</a>
                    </li>
                    <li class="nav-item  d-md-none">
                        <a class="nav-link" title="قائمة ملابسك المفضلة" href="profile.php">الأمنيات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="الأسئلة الشائعة" href="index.php#faq">الأسئلة الشائعة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="تواصل معنا" href="#contact">تواصل معنا</a>
                    </li>
                    <?php

                    // check login
                    if (isset($_COOKIE['key'])) {
                        echo '
            <li class="nav-item">
            <a class="nav-link" title="تسجيل الخروج من موقع ecommerce" href="login.php?logOut=true">تسجيل الخروج</a>
          </li>
            ';
                    } else {
                        echo '
            <li class="nav-item">
            <a class="nav-link" title="تسجيل دخولك في موقع ecommerce" href="login.php">تسجيل الدخول</a>
          </li>
            ';
                    }

                    ?>
                </ul>

                <div class="d-flex me-auto nav-icons d-none d-md-block">
                    <a href="cart.php" class="cart-link" title="سلة المشتريات">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-counter">
                            <?php

                            if (isset($_COOKIE['key'])) {
                                $user_key = $_COOKIE['key'];
                                $stmt = $con->prepare("SELECT * FROM cart WHERE user_key = ?");
                                $stmt->execute(array($user_key));
                                $count = $stmt->rowCount();
                                $rows = $stmt->fetchAll();
                                echo count($rows);
                            } else {
                                echo '0';
                            }

                            ?>
                        </span>
                    </a>
                    <a href="favourites.php" title="الأمنيات"><i class="fas fa-heart"></i></a>
                    <a class="search-icon" title="بحث"> <i class="fas fa-search"></i> </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- end fixed navbar -->

    <!-- start search bar -->
    <form class=" me-auto search-bar" method="get" action="products.php">
        <input class="form-control me-2" name="search" type="search" placeholder="ابحث عن منتج" aria-label="Search" required>
        <button class="btn btn-outline-success" type="submit">بحث</button>
    </form>

    <!-- end search bar -->

    <!-- end navbar  -->
    <!-- start mobile nav -->
    <div class="d-flex me-auto mobile-nav-icons d-block d-md-none">
        <div class="row w-100 text-center">
            <div class="col-3">
                <a href="products.php" title="المنتجات"><i class="fas fa-tshirt"></i></a>
            </div>
            <div class="col-3">
                <a href="coins.php" class="coins" title="لديك 0 عملة اضغط للمزيد من التفاصيل"><i class="fas fa-coins"></i> <span> 30 </span> </a>
            </div>
            <div class="col-3">
                <a class="search-icon" title="بحث"> <i class="fas fa-search"></i> </a>
            </div>

            <div class="col-3">
                <a href="cart.php" class="mobile-cart-link" title="سلة المشتريات">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-counter">
                        <?php

                        if (isset($_COOKIE['key'])) {
                            $user_key = $_COOKIE['key'];
                            $stmt = $con->prepare("SELECT * FROM cart WHERE user_key = ?");
                            $stmt->execute(array($user_key));
                            $count = $stmt->rowCount();
                            $rows = $stmt->fetchAll();
                            echo count($rows);
                        } else {
                            echo '0';
                        }

                        ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <!-- end mobile nav -->


    <!-- start product details -->

    <section class="product">
        <div class="row ">

            <?php

            $stmt = $con->prepare("SELECT * FROM products WHERE id = $id");
            $stmt->execute();
            $rows = $stmt->fetchAll();

            // the loop 
            foreach ($rows as $row) {
                unset($images[0]);
                // images count
                echo '
        <div class="col-md-4 col-sm-6">
        <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
          <ol class="carousel-indicators">
        ';

                for ($i = 0; $i < count($images); $i++) {
                    echo '
          <li data-bs-target="#myCarousel" class="' . $i . '" data-bs-slide-to="' . $i . '" ></li>
          ';
                }

                echo '

            </ol>
            <div class="carousel-inner">
            ';

                foreach ($images as $img) {
                    echo '
    
              <div class="carousel-item ">
                <img src="assets/img/products/' . $img . '" width="100%" height="550px" alt="" />
              </div>
                    ';
                }
                echo '
            </div>
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" role="button" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </a>
          </div>

          </div>

        ';
            }

            ?>

            <div class="col-md-7 col-sm-6 details">
                <div class="name">
                    <h1><?php echo $title; ?></h1>
                </div>
                <div class="description">
                    <p>
                        <?php echo $description; ?>
                    </p>
                </div>
                <div class="price">
                    <span class="price-word">السعر: </span>&nbsp;
                    <?php

                    if (isset($_COOKIE['trade']) and $_COOKIE['trade'] == $_COOKIE['key']) {
                        echo '
            <span>' . $trade_price . ' جنيه</span>
            ';
                    } else {
                        if ($discount == 0) {
                            echo '
              <span>' . $price . ' جنيه</span>
              ';
                        } else {
                            echo '
              <span>' . $discount . ' جنيه</span>
              <span class="before-discount">بدلا من <span>' . $price . '</span></span>
              ';
                        }
                    }



                    ?>

                </div>
                <div class="color">
                    <span class="color-word">اللون: </span>&nbsp;

                    <span><?php echo $color; ?></span>

                </div>
                <div class="size row">
                    <p>اختر المقاس:</p>
                    <?php

                    foreach ($sizes as $size) {
                        echo '
            <div class="col-4 col-lg-2">
            <div class="' . $size . '">' . $size . '</div>
            </div>
            ';
                    }
                    ?>


                </div>

                <div class="quantity row">

                    <div class="dropdown w-50  col-6 col-md-3 col-lg-2">
                        <button onclick="myFunction()" class="dropbtn btn " type="button">
                            اختر عدد القطع
                        </button>
                        <div id="myDropdown1" class="dropdown-content w-50">

                        </div>
                    </div>
                </div>

                <div class="cart-container" id="add_to_cart">
                    <div class="cart ">اضافة للسلة</div>
                </div>
            </div>
        </div>
    </section>
    <!-- end product details -->

    <!-- Slider main container -->
    <section class="trending" style="position: relative;">
        <h3 class="side-title "> يشتري الناس أيضا مع هذا المنتج </h3>
        <div class="container">
            <div class="swiper-container mySwiper disable-select">
                <div class="swiper-wrapper">




                    <?php

                    $products_id = array($id);


                    $stmt = $con->prepare("SELECT images, id FROM products WHERE tags LIKE '%$title%' AND category_status = ? AND color_status = ? AND quantity != 0");
                    $stmt->execute(array('visible', 'visible'));
                    $products = $stmt->fetchAll();

                    // the loop 
                    foreach ($products as $product) {
                        if (in_array($product['id'], $products_id)) {
                        } else {
                            $images = explode(',', $product['images']);
                            echo '
  
            <div class="swiper-slide">
            <img src="assets/img/products/' . $images[1] . '" width="100%" height="100%" alt="" />
            <div class="product-details row">
              <div class="col-4 text-center">
                <a href="product-details.php?id=' . $product['id'] . '" title="عرض تفاصيل المنتج"><i class="fas fa-search-plus"></i></a>
              </div>
              <div class="col-4 text-center">
                <a title="اضافة لقائمةالأمنيات"><i class="fas fa-heart add-favourite"></i></a>
              </div>
              <div class="col-4 text-center">
                <a href="#" title="اضافة للسلة"><i class="fas fa-cart-plus"></i></a>
              </div>
            </div>

          </div> 
  ';

                            array_push($products_id, $product['id']);
                        }
                    }

                    // get more items 

                    if (count($products_id) != 10) {

                        $stmt = $con->prepare("SELECT images, id FROM products WHERE category = ?  AND category_status = ? AND color_status = ? AND quantity != 0");
                        $stmt->execute(array($category, 'visible', 'visible'));
                        $products = $stmt->fetchAll();

                        // the loop 
                        foreach ($products as $product) {
                            if (in_array($product['id'], $products_id) or count($products_id) == 10) {
                            } else {
                                $images = explode(',', $product['images']);
                                echo '
    
              <div class="swiper-slide">
              <img src="assets/img/products/' . $images[1] . '" width="100%" height="100%" alt="" />
              <div class="product-details row">
                <div class="col-4 text-center">
                  <a href="product-details.php?id=' . $product['id'] . '" title="عرض تفاصيل المنتج"><i class="fas fa-search-plus"></i></a>
                </div>
                <div class="col-4 text-center">
                  <a title="اضافة لقائمةالأمنيات"><i class="fas fa-heart add-favourite"></i></a>
                </div>
                <div class="col-4 text-center">
                  <a href="#" title="اضافة للسلة"><i class="fas fa-cart-plus"></i></a>
                </div>
              </div>
  
            </div> 
    ';

                                array_push($products_id, $product['id']);
                            }
                        }
                    }

                    ?>

                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
            <div class="add-success text-center">
                <div>تم الاضافة لقائمة الأمنيات</div>
            </div>
            <div class="remove-success text-center">
                <div>تم الإزالة من قائمة الأمنيات</div>
            </div>
        </div>

    </section>

    <!-- end products slider -->

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-4 col-md-6" id="contact">
                        <div class="footer-info">
                            <p>
                            <h4>تواصل معنا في اي وقت </h4>

                            <strong>رقم الهاتف : </strong> +966559275722 <br> <br>
                            <strong>البريد الالكتروني : </strong> info@store.co<br>
                            </p>
                            <div class="social-links mt-3">
                                <a href="#" class="twitter"><i class="fab fa-whatsapp"></i></a>
                                <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="linkedin"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 footer-links">
                        <h4>روابط سريعة</h4>
                        <ul>
                            <li><a title="الرئيسية" href="index.php">الرئيسية</a></li>
                            <li> <a title="الأسئلة الشائعة" href="index.php#faq">الأسئلة الشائعة</a></li>
                            <li> <a title="الأمنيات" href="favourites.php">المفضلة</a></li>
                            <li> <a title="سلة المشتريات" href="cart.php">سلة المشتريات</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-md-6 footer-links">
                        <h4>الأقسام</h4>
                        <ul>
                            <li> <a title="قسم الرجالي" href="products.php">قسم الرجالي</a></li>
                            <li> <a title="قسم الحريمي" href="products.php">قسم الحريمي</a></li>
                            <li> <a title="قسم الأطفال" href="products.php">قسم الأطفال </a></li>
                        </ul>
                    </div>

                    <div class="col-lg-4 col-md-6 footer-newsletter">
                        <h4>انضم لقائمتنا البريدية</h4>
                        <form class=" me-auto mail-list">
                            <input class="form-control me-2" type="text" placeholder="ادخل البريد الالكتروني" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">اشترك</button>
                        </form>

                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright">
                جميع الحقوق محفوظة &copy; <strong> <span> store</span></strong>.
            </div>
            <div class="credits">
            </div>
        </div>
    </footer><!-- End Footer -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/product-details.js"></script>
    <script>
        // quantity

        $maxNumber = <?php echo $quantity; ?>;
        for (let i = 1; i <= $maxNumber; i++) {
            $("#myDropdown1").append('<a class="dropdown-item" class="' + i + '">' + i + '</a>');
        }


        // active carousel 

        $('.carousel-indicators .0').addClass('active');

        // first image carousel active
        $('.carousel-inner div:first-child').addClass('active');
    </script>


    <script>
        $cart_counter = $('.cart_caounter').text();
        $('#add_to_cart').click(function() {
            <?php

            if (isset($_COOKIE['key'])) {

                $key = filter_var($_COOKIE['key'], FILTER_SANITIZE_STRING);

                // check if account is exit  

                $stmt = $con->prepare("SELECT user_key FROM users WHERE user_key = ?");
                $stmt->execute(array($key));
                $count = $stmt->rowCount();

                if ($count > 0) {

                    echo "

    // add to cart 


      if ($('.size div div').hasClass('active')) {

        // check quantity 

        if ($('.dropbtn').hasClass('choosen')) {

          // send to cart 

          $.post('cart.php', {
            add: 1,
            id: " . $id . ",
            quantity: $('.dropbtn').text(),
            size: $('.size div .active').text()
          }, function(data) {
            $('.animate-container').html(data).addClass(data);
            if ($('.info-animate').hasClass('done')) {
              $('.cart-counter').text(parseInt($('.cart-counter').text()) + 1 );
            }
            $('.info-animate').animate({
              opacity: 1,
              top: '12%',
            }, 1000, function() {
              $('.info-animate').animate({
                opacity: 0,
                top: '8%',
              }, 1200);
            });
          });

        } else {
          alert('من فضلك اختر عدد القطع');
        }

      } else {
        alert('من فضلك قم باختيار المقاس !');
      }

    ";
                } else {

                    echo '
          window.location.replace("login.php?product=' . $id . '");
          ';
                }
            } else {
                echo '
        window.location.replace("login.php?product=' . $id . '");
        ';
            }

            ?>
        });
    </script>

</body>

</html>