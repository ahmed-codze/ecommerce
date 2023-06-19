<?php
include '../connect.php';


// check key

if (isset($_GET['key'])) {

    $user_key = $_GET['key'];

    // check if key is right

    $stmt = $con->prepare("SELECT user_key FROM users WHERE user_key = ?");
    $stmt->execute(array($user_key));
    $count = $stmt->rowCount();
    if (!($count > 0)) {

        // if id doesn't exist 
        header('location: admin-cart.php');
        exit();
    }
} else {
    header('location: admin-cart.php');
    exit();
}

// delete product from user's cart 

if (isset($_POST['delete_cart'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $cart_quantity = filter_var($_POST['cart_quantity'], FILTER_SANITIZE_NUMBER_INT);

    // udate quantity 

    $stmt = $con->prepare("SELECT product_id FROM cart WHERE id = ?");
    $stmt->execute(array($id));
    $carts = $stmt->fetchAll();

    foreach ($carts as $cart) {
        $stmt = $con->prepare("SELECT quantity FROM products WHERE id = ?");
        $stmt->execute(array($cart['product_id']));
        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {

            $new_quantity = $row['quantity'] + $cart_quantity;

            $stmt = $con->prepare('UPDATE products SET quantity = :quantity WHERE id = :id');

            $stmt->execute(array(
                'quantity' => $new_quantity,
                'id' => $cart['product_id']
            ));
        }
    }

    $stmt = $con->prepare("DELETE FROM `cart` WHERE `cart`.`id` = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    echo 'd';
    exit();
}


$serch_placeholder = " ابحث برقم الطلب";
$serch_page = 'orders.php';
include 'admin_header.php';
?>
<link rel="stylesheet" href="assets/css/cart.css" />
<style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }

    select,
    option {
        padding: 8px;
        cursor: pointer;
    }

    .arrange-span {
        display: inline-block;
        font-size: 18px;
        margin-left: 5px;
        margin-top: 5px;
        font-weight: bold;
    }
</style>

<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <i data-feather="shopping-cart"></i>
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>تفاصيل السلة
                            <small>Multikart Admin panel</small>
                        </h3>
                    </div>
                </div>
                <div class="col-lg-6 text-end">
                    <a href="#" class="btn btn-secondary show-email-form">ارسال رسالة</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->

    <!-- Container-fluid starts-->
    <div class="container-fluid">

        <!-- cart details -->

        <div class="container">
            <div class="row cart-container">

                <?php
                $stmt = $con->prepare("SELECT * FROM cart WHERE user_key = ?");
                $stmt->execute(array($user_key));
                $count = $stmt->rowCount();
                if ($count > 0) {
                    $rows = $stmt->fetchAll();

                    // the loop 

                    foreach ($rows as $row) {
                        $product_id = $row['product_id'];
                        $quantity = $row['quantity'];
                        $size = $row['size'];

                        // get products

                        $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
                        $stmt->execute(array($product_id));
                        $products = $stmt->fetchAll();

                        // the loop 
                        foreach ($products as $product) {
                            $images = explode(',', $product['images']);
                            echo '
          <div class="col-12 col-md-6 col-lg-6 product">
          <div class="row">
            <div class="col-4 img-container">
              <a href="product-details.php?id=' . $product_id . '"><img src="../assets/img/products/' . $images[1] . '" alt="" /></a>
            </div>
            <div class="col-8 product-info">

              <p class="title">' . $product['title'] . '</p>

              ';
                            $stmt = $con->prepare("SELECT trade, id FROM users WHERE user_key = ?");
                            $stmt->execute(array($user_key));
                            $users = $stmt->fetchAll();

                            // the loop 
                            foreach ($users as $user) {

                                $user_id = $user['id'];

                                if ($user['trade'] == 1) {
                                    echo '<p class="price" data-product_price=' . filter_var($product['trade_price'], FILTER_SANITIZE_NUMBER_INT) . '>' . ($product['trade_price'] * $quantity)  . ' جنيه</p>';
                                } elseif ($product['discount'] == 0) {
                                    echo '<p class="price" data-product_price=' . filter_var($product['price'], FILTER_SANITIZE_NUMBER_INT) . '>' . ($product['price'] * $quantity)  . ' جنيه</p>';
                                } else {
                                    echo '<p class="price" data-product_price=' . filter_var($product['discount'], FILTER_SANITIZE_NUMBER_INT) . '>' . ($product['discount'] * $quantity) . ' جنيه</p>';
                                }
                            }
                            echo '

              <div class="quantity">
                <p> الكمية:</p>

                <input type="number" disabled id="quantity" class="form-control quantity-count" name="quantity" value="' . $quantity . '" ><br><br>

              </div>
              <div class="size">
              <p> المقاس:</p>
                <span>' . $size . '</span>
            </div>

            <div>
            <br>

            <div class="btn btn-solid delete-from-cart" data-id=' . $row['id'] . ' data-quantity=' . $quantity . '>حذف من السلة</div>
            </div>
            </div>
          </div>
          </div>
          ';
                        }
                    }
                    echo '
        
    </div>
    </div>
        ';
                } else {
                    echo '<div class="alert-info" style="    
        text-align: center;
        font-size: 18px;
        padding: 5px;
        margin: 20px auto;
        width: 80%;">سلة المشتريات فارغة</div>';
                }

                ?>


            </div>

            <!-- end cart details -->

            <div class="email-form">
                <div class="row">
                    <form action="send-email.php" method="POST">
                        <div class="col-12">
                            <p for="subject">عنوان الايميل</p>
                            <input id="subject" type="text" name="subject" class="form-control">
                        </div>

                        <div class="col-12">
                            <p for="body">محتوى الرسالة</p>
                            <style>
                                .simditor-body p {
                                    text-align: left;
                                }
                            </style>
                            <textarea dir="ltr" required name="body" id="editor" style="height: 120px;" class="form-control"></textarea>
                        </div>

                        <input type="hidden" value="<?php echo $user_id ?>" name="users_array">

                        <div class="col-12">
                            <br>
                            <br>
                            <button class="btn btn-solid" type="submit">ارسال</button>
                        </div>

                    </form>
                </div>
                <span class="close-email-form close">X</span>
            </div>

        </div>

    </div>

    <?php include 'admin_footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        var example = flatpickr('#flatpickr');

        // delete from cart 
        $page_url = $(location).attr('href');
        $('.delete-from-cart').click(function() {
            swal({
                    title: " متأكد من حذف هذا المنتج من السلة ؟",
                    text: " اذا قمت بالضغط على OK سيتم الحذف",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $(this).parentsUntil('.cart-container').hide();
                        swal("تم حذف المنتج من السلة", {
                            icon: "success",
                        });
                        $.post($page_url, {
                            delete_cart: true,
                            id: $(this).data('id'),
                            cart_quantity: $(this).data('quantity')
                        }, function(data) {
                            swal(data, {
                                icon: "success",
                            })
                        });

                    }
                });
        })
    </script>
    </body>

    </html>