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
include 'theme_header.php';
// check login 

if (isset($_COOKIE['key'])) {

    $key = filter_var($_COOKIE['key'], FILTER_SANITIZE_STRING);

    // check if account is exit  

    $stmt = $con->prepare("SELECT user_key FROM users WHERE user_key = ?");
    $stmt->execute(array($key));
    $count = $stmt->rowCount();

    if (!($count > 0)) {

        // check if id isn't right
        echo '
        <script>            
            window.location.href = "login.php";
      </script>
  
        ';
        exit();
    }
} else {
    // if login is wrong 
    echo '
                <script>
                    window.location.href = "login.php";
              </script>
          
                ';
    exit();
}


?>


<!--section start-->
<section class="wishlist-section section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 table-responsive-xs">
                <table class="table cart-table">
                    <thead>
                        <tr class="table-head">
                            <th scope="col">image</th>
                            <th scope="col">product name</th>
                            <th scope="col">price</th>
                            <th scope="col">availability</th>
                            <th scope="col">action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $stmt = $con->prepare("SELECT * FROM wishlist WHERE user_key = ?");
                        $stmt->execute(array($key));
                        $lists = $stmt->fetchAll();

                        foreach ($lists as $list) {

                            $stmt = $con->prepare("SELECT * FROM products WHERE id = ? ORDER BY id DESC ");
                            $stmt->execute(array($list['product_id']));
                            $rows = $stmt->fetchAll();
                            foreach ($rows as $row) {

                                $images = explode(',', $row['images']);
                                echo '
                                <tr>
                                <td>
                                    <a href="product-details.php?id=' . $row['id'] . '"><img src="assets/img/products/' . $images[1] . '" alt=""></a>
                                </td>
                                <td><a href="product-details.php?id=' . $row['id'] . '">' . $row['title'] . '</a>
                                    <div class="mobile-cart-content row">
                                        <div class="col">
                                        ';
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
                                            if ($row['quantity'] != 0) {
                                                echo '
                                                In Stock
                                                ';
                                            } else {
                                                echo '
                                                <p>Out of stock</p>
                                                ';
                                            }
                                        } else {
                                            echo '
                                            <p>Not available now</p>
                                            ';
                                        }
                                    } else {
                                        echo '
                                        <p>Not available now</p>
                                        ';
                                    }
                                } else {
                                    echo '
                                    <p>Not available now</p>
                                    ';
                                }


                                echo '
                                            
                                        </div>
                                        <div class="col">
                                        ';
                                if (isset($_COOKIE['trade'])) {
                                    echo '
                                            <h2 class="td-color">' . $row['trade_price'] . '</h2>
                                            ';
                                } elseif ($row['discount'] != 0) {
                                    echo '
                                            <h2 class="td-color">' . $row['discount'] . '</h2>
                                            ';
                                } else {
                                    echo '
                                            <h2 class="td-color">' . $row['price'] . '</h2>
                                            ';
                                }

                                echo '
                                        </div>
                                        <div class="col">
                                            <h2 class="td-color"><a href="javascript:void(0)" class="icon me-1 remove-from-wishlist"><i class="ti-close"></i>
                                                </a></h2>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                ';
                                if (isset($_COOKIE['trade'])) {
                                    echo '
                                            <h2 class="td-color">' . $row['trade_price'] . ' LE</h2>
                                            ';
                                } elseif ($row['discount'] != 0) {
                                    echo '
                                            <h2 class="td-color">' . $row['discount'] . ' LE</h2>
                                            ';
                                } else {
                                    echo '
                                            <h2 class="td-color">' . $row['price'] . ' LE</h2>
                                            ';
                                }
                                echo '
                                </td>
                                <td>
                                ';
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
                                            if ($row['quantity'] != 0) {
                                                echo '
                                                                                In Stock
                                                                                ';
                                            } else {
                                                echo '
                                                                                <p>Out of stock</p>
                                                                                ';
                                            }
                                        } else {
                                            echo '
                                                                            <p>Not available now</p>
                                                                            ';
                                        }
                                    } else {
                                        echo '
                                                                        <p>Not available now</p>
                                                                        ';
                                    }
                                } else {
                                    echo '
                                                                    <p>Not available now</p>
                                                                    ';
                                }

                                echo '
                                </td>
                                <td><a href="javascript:void(0)" data-id=' . $list['id'] . ' class="icon me-3 remove-from-wishlist"><i class="ti-close"></i> </a></td>
                            </tr>
                                ';
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row wishlist-buttons">
            <div class="col-12"><a href="shop.php" class="btn btn-solid">continue shopping</a></div>
        </div>
    </div>
</section>
<!--section end-->

<!-- added to cart notification -->
<div class="added-notification">
    <h3 class="add-to-cart-text">added to cart</h3>
</div>
<!-- added to cart notification -->
<?php include 'theme_footer.php'; ?>

<script>
    $('.remove-from-wishlist').click(function() {
        $(this).parentsUntil('tbody').fadeOut();
        $.post('wishlist-request.php', {
            delete_wishlist: true,
            id: $(this).data('id')
        }, function(data) {
            $('.add-to-cart-text').html(data);
            $('.added-notification').addClass('show');
            setTimeout(function() {
                $('.added-notification').removeClass('show');
            }, 5000);
        })
    })
</script>

</body>

</html>