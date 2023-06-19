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
            window.location.href = "error.php";
      </script>
  
        ';
        exit();
    }
} else {
    // if login is wrong 
    echo '
                <script>
                    window.location.href = "error.php";
              </script>
          
                ';
    exit();
}
?>

<style>
    .product {
        box-shadow: 0 2px 5px 0 rgb(0 0 0 / 8%);
        margin: 40px 0;
        padding: 20px;
    }

    .product img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 5px;
    }

    .product-info .title {
        font-size: 1.4rem;
        color: #000;
    }

    .product-info .price {
        color: #006fcc;
        font-size: 20px;
    }

    .quantity p {
        display: inline-block;
        margin: 0px 0px 0 10px;
        font-size: 20px;
        color: #000;
    }

    .quantity input {
        font-size: 18px;
        width: 90px !important;
        display: inline-block !important;
        cursor: pointer;
        padding: 3px;
        text-align: center;
    }

    .size p {
        display: inline-block;
        margin: 0px 0px 0 10px;
        font-size: 20px;
        color: #000;
    }

    .size span {
        font-size: 22px;
    }

    .delete {
        cursor: pointer;
        padding: 0px;
        height: 50px;
        line-height: 15px;
    }

    .delete p {
        display: inline-block;
        margin: 22px 0px 0 10px;
        font-size: 20px;
    }

    .delete i {
        color: rgb(218, 33, 33);
        font-size: 19px;
    }

    @media (max-width: 767px) {
        .delete p {
            font-size: 18px;
        }
    }

    .dark .title,
    .dark .quantity p,
    .dark .size p {
        color: #cfd4da;
    }

    /* end cart details */
</style>
<!-- cart details -->

<div class="container">
    <div class="row products-cart-container">

        <?php
        $user_key = $_COOKIE['key'];
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

                $stmt = $con->prepare("SELECT title, size, price, discount, images, trade_price, quantity FROM products WHERE id = ?");
                $stmt->execute(array($product_id));
                $products = $stmt->fetchAll();

                // the loop 
                foreach ($products as $product) {
                    $images = explode(',', $product['images']);
                    echo '
          <div class="col-12 col-md-6 col-lg-6 product">
          <div class="row">
            <div class="col-4 img-container">
              <a href="product-details.php?id=' . $product_id . '"><img src="assets/img/products/' . $images[1] . '" alt="" /></a>
            </div>
            <div class="col-8 product-info">

              <p class="title">' . $product['title'] . '</p>

              ';
                    if (isset($_COOKIE['trade']) and $_COOKIE['trade'] == $_COOKIE['key']) {

                        echo '<p class="price" data-product_price=' . $product['trade_price'] . '>' . $product['trade_price'] * $quantity  . ' LE</p>';
                    } else {
                        if ($product['discount'] == 0) {
                            echo '<p class="price" data-product_price=' . $product['price'] . '>' . $product['price'] * $quantity  . ' LE</p>';
                        } else {
                            echo '<p class="price" data-product_price=' . $product['discount'] . '>' . $product['discount'] * $quantity . ' LE</p>';
                        }
                    }
                    echo '

              <div class="quantity">
                <p> quantity: </p>

                <input type="number" data-max-quantity= ' . ($quantity + $product['quantity']) . ' data-product_id=' . $product_id . ' data-id=' . $row['id'] . ' data-product_quantity=' . $product['quantity'] . ' data-quantity=' . $quantity . ' id="quantity" class="form-control quantity-count" data-product_id=' . $product_id . ' name="quantity" value="' . $quantity . '" min="1" max="' . ($product['quantity'] + $quantity) . '"><br><br>

              </div>

              ';

                    if ($size != '') {
                        echo '
                <div class="size">
                <p> size:</p>

                <span>' . $size . '</span>
              </div>
                ';
                    }

                    echo '
              <div class="delete" data-id=' . $row['id'] . ' data-quantity=' . $quantity . '>
                <p>Remove from cart</p>&nbsp;&nbsp;<i class="fa fa-trash-o" aria-hidden="true"></i>
              </div>
            </div>
          </div>
          </div>
          ';
                }
            }
            echo '
        
    </div>

    <div class="text-center">
      <a href="confirm-order.php" class="btn btn-solid btn-animation" title="check and confirm your order">
            Continue your order
      </a>
      <br>
      <br>
    </div>
        ';
        } else {
            echo '<div class="alert-info" style="    
        text-align: center;
        font-size: 18px;
        padding: 5px;
        margin: 20px auto;
        width: 80%;">Your cart is empty</div>';
        }

        ?>


    </div>

    <!-- end cart details -->
    <!--section end-->

    <?php include 'theme_footer.php'; ?>
    <script>
        // change quantity 
        $('.quantity-count').change(function() {
            $qty_val = $(this).val();
            $price = $(this).parent().siblings('.price');
            if ($qty_val > $(this).data('max-quantity')) {
                $(this).val($(this).data('max-quantity'));
            } else if ($qty_val == 0) {
                $(this).val(1);
            }
            $price.text(parseInt($(this).val()) * parseInt($price.data('product_price')) + ' LE');
            $.post('cart-request.php', {
                update_cart_quantity: true,
                id: $(this).data('id'),
                new_quantity: $(this).val(),
                old_quantity: $(this).data('quantity'),
                product_quantity: $(this).data('product_quantity'),
                product_id: $(this).data('product_id')
            })
        })

        // delete cart item 

        $('.delete').click(function() {
            $(this).parentsUntil('.products-cart-container').fadeOut();
            $.post('cart-request.php', {
                delete_cart: true,
                cart_id: $(this).data('id'),
                cart_quantity: $(this).data('quantity')
            })
            $('.cart_qty_cls').text($('.cart_qty_cls').text() - 1)
        })
    </script>
    </body>

    </html>