<?php

include 'connect.php';


// add products to cart 

if (isset($_POST['add'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    if (isset($_POST['quantity'])) {
        $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
    } else {
        $quantity = 0;
    }

    if (isset($_POST['size'])) {
        $size = filter_var($_POST['size'], FILTER_SANITIZE_STRING);
    } else {
        $size = '';
    }
    // check login 

    if (isset($_COOKIE['key'])) {

        $key = filter_var($_COOKIE['key'], FILTER_SANITIZE_STRING);

        // check if account is exit  

        $stmt = $con->prepare("SELECT user_key FROM users WHERE user_key = ?");
        $stmt->execute(array($key));
        $count = $stmt->rowCount();

        if ($count > 0) {

            // check id 

            // check if id is right

            $stmt = $con->prepare("SELECT id FROM products WHERE id = ? ");
            $stmt->execute(array($id));
            $count = $stmt->rowCount();
            if (($count > 0)) {

                // check if quatity is right 

                $stmt = $con->prepare("SELECT quantity FROM products WHERE id = ?");
                $stmt->execute(array($id));
                $rows = $stmt->fetchAll();

                // the loop 
                foreach ($rows as $row) {
                    $max_quantity = $row['quantity'];
                }

                if ($quantity <= $max_quantity and $quantity > 0) {
                    // if quantity is right 

                    $stmt = $con->prepare("SELECT size FROM products WHERE id = ?");
                    $stmt->execute(array($id));
                    $rows = $stmt->fetchAll();

                    // the loop 
                    foreach ($rows as $row) {
                        $sizes_from_database = explode(',', $row['size']);
                    }
                    if (in_array($size, $sizes_from_database)) {

                        // if size is right

                        // check if product is already exist in cart

                        date_default_timezone_set('Africa/Cairo');
                        $date = date("Y-m-d");
                        $user_key = $_COOKIE['key'];

                        $stmt = $con->prepare("SELECT product_id, size, user_key FROM cart WHERE product_id = ? AND size = ? AND user_key = ? ");
                        $stmt->execute(array($id, $size, $user_key));
                        $count = $stmt->rowCount();
                        if (!($count > 0)) {

                            // add to cart 

                            $stmt = $con->prepare('INSERT INTO cart (product_id, quantity, size, user_key, adding_date) 
                                                VALUES (:id, :quantity, :size, :key, :date)');
                            $stmt->execute(array(
                                'id' => $id,
                                'quantity'  => $quantity,
                                'size'      => $size,
                                'key'       => $user_key,
                                'date'      => $date
                            ));


                            // update quantity 

                            $new_quantity = $max_quantity - $quantity;

                            $stmt = $con->prepare('UPDATE products SET quantity = :quantity WHERE id = :id');

                            $stmt->execute(array(
                                'quantity' => $new_quantity,
                                'id' => $id
                            ));
                            echo 'added to your cart  <i class="fa fa-check" aria-hidden="true"></i>';
                            exit();
                        } else {

                            echo 'already in your cart ';
                            exit();
                        }
                    } else {

                        // if size is wrong

                        echo 'something went wrong!, please try again 

                <script>
                setInterval("refresh()", 2300);
            
                function refresh() {
                  location.reload();
                }
              </script>
          
                ';
                        exit();
                    }
                } else {
                    // if quantity is wrong 
                    echo 'please, Check the quantity!

            <script>
            setInterval("refresh()", 2300);
        
            function refresh() {
              location.reload();
            }
          </script>
      
            ';
                    exit();
                }
            } else {
                // if  product_id doesn't exist 
                echo '<script>location.href = "error.php";</script>';
                exit();
            }
        } else {
            // if login is wrong 
            echo 'please, login first 

                <script>
                setInterval("refresh()", 2000);
            
                function refresh() {
                    window.location.href = "login.php?product=' . $id . '";
                }
              </script>
          
                ';
            exit();
        }
    } else {
        // if login is wrong 
        echo 'please, login first 

                    <script>
                    setInterval("refresh()", 2000);
                
                    function refresh() {
                        window.location.href = "login.php?product=' . $id . '";
                    }
                  </script>
              
                    ';
        exit();
    }
}

// mini add to cart 

if (isset($_POST['mini_add_to_cart'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    // check login 

    if (isset($_COOKIE['key'])) {

        $key = filter_var($_COOKIE['key'], FILTER_SANITIZE_STRING);

        // check if account is exit  

        $stmt = $con->prepare("SELECT user_key FROM users WHERE user_key = ?");
        $stmt->execute(array($key));
        $count = $stmt->rowCount();

        if ($count > 0) {

            // check id 

            // check if id is right

            $stmt = $con->prepare("SELECT id FROM products WHERE id = ? AND quantity != 0");
            $stmt->execute(array($id));
            $count = $stmt->rowCount();
            if (($count > 0)) {


                // check if product is already exist in cart

                date_default_timezone_set('Africa/Cairo');
                $date = date("Y-m-d");
                $user_key = $_COOKIE['key'];

                $stmt = $con->prepare("SELECT product_id, user_key FROM cart WHERE product_id = ? AND user_key = ? ");
                $stmt->execute(array($id, $user_key));
                $count = $stmt->rowCount();
                if (!($count > 0)) {

                    // get size 

                    $stmt = $con->prepare("SELECT size FROM products WHERE id = ?");
                    $stmt->execute(array($id));
                    $rows = $stmt->fetchAll();

                    foreach ($rows as $row) {
                        if ($row['size'] != '') {
                            $sizes = explode(',', $row['size']);

                            foreach ($sizes as $size) {
                                $stmt = $con->prepare("SELECT size FROM prod_size WHERE size = ? AND status != 0");
                                $stmt->execute(array($size));
                                $count = $stmt->rowCount();
                                if ($count > 0) {
                                    $prod_size = $size;
                                    break;
                                }
                            }
                        } else {
                            $prod_size = '';
                        }
                    }

                    // add to cart 

                    $stmt = $con->prepare('INSERT INTO cart (product_id, quantity, size, user_key, adding_date) 
                                                VALUES (:id, :quantity, :size, :key, :date)');
                    $stmt->execute(array(
                        'id' => $id,
                        'quantity'  => 1,
                        'size'      => $prod_size,
                        'key'       => $user_key,
                        'date'      => $date
                    ));

                    echo 'added to your cart  <i class="fa fa-check" aria-hidden="true"></i>';
                    exit();
                } else {

                    echo 'already in your cart ';
                    exit();
                }
            } else {
                // if  product_id doesn't exist 
                echo '<script>location.href = "error.php";</script>';
                exit();
            }
        } else {
            // if login is wrong 
            echo 'please, login first 

                <script>
                setInterval("refresh()", 2000);
            
                function refresh() {
                    window.location.href = "login.php?product=' . $id . '";
                }
              </script>
          
                ';
            exit();
        }
    } else {
        // if login is wrong 
        echo 'please, login first 

                    <script>
                    setInterval("refresh()", 2000);
                
                    function refresh() {
                        window.location.href = "login.php?product=' . $id . '";
                    }
                  </script>
              
                    ';
        exit();
    }
}


// delete mini cart 

if (isset($_POST['delete_cart'])) {
    $id = filter_var($_POST['cart_id'], FILTER_SANITIZE_NUMBER_INT);
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

    // get new total 

    $new_total = 0;

    $stmt = $con->prepare("SELECT product_id FROM cart WHERE user_key = ?");
    $stmt->execute(array($_COOKIE['key']));
    $carts = $stmt->fetchAll();

    foreach ($carts as $cart) {

        $stmt = $con->prepare("SELECT price, discount, trade_price, quantity FROM products WHERE id = ?");
        $stmt->execute(array($cart['product_id']));
        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            if (isset($_COOKIE['trade'])) {
                $new_total = $new_total + $row['trade_price'];
            } elseif ($row['discount'] != 0) {
                $new_total = $new_total + $row['discount'];
            } else {
                $new_total = $new_total + $row['price'];
            }
        }
    }

    echo $new_total;
    exit();
}

// update mini cart 

if (isset($_POST['update_mini_cart'])) {

    echo '<div><img src="assets/img/icon/cart.png" class="img-fluid blur-up full-cart lazyload" alt=""> <i class="ti-shopping-cart"></i></div>';
    $total = 0;
    // if login 

    if (isset($_COOKIE['key'])) {

        // get cart 

        $stmt = $con->prepare("SELECT * FROM cart WHERE user_key = ?");
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

                if (isset($_COOKIE['trade'])) {
                    echo $product['trade_price'];
                    $total = $total + ($product['trade_price'] * $cart['quantity']);
                } elseif ($product['discount'] != 0) {
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
                cart</a> <a href="confirm-order.php" class="checkout">checkout</a></div>
    </li>
        </ul>
        ';

        echo "
        <script src='assets/js/jquery-3.3.1.min.js'></script>
        <script>
        $('.delete-mini-cart-item').click(function() {
            $(this).parentsUntil('ul').fadeOut();
            $('.cart_qty_cls').text($('.cart_qty_cls').text() - 1)
            $.post('cart-request.php', {
                delete_mini_cart: true,
                cart_id: $(this).data('id'),
                cart_quantity: $(this).data('quantity')
            }, function(data) {
                $('.mini-cart-total').text(data);
            })
        })
    </script>
        ";
    }
    exit();
}

// update cart quantity 

if (isset($_POST['update_cart_quantity'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $old_quantity = filter_var($_POST['old_quantity'], FILTER_SANITIZE_NUMBER_INT);
    $new_quantity = filter_var($_POST['new_quantity'], FILTER_SANITIZE_NUMBER_INT);
    $product_quantity = filter_var($_POST['product_quantity'], FILTER_SANITIZE_NUMBER_INT);
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);

    // update cart quantity 

    $stmt = $con->prepare('UPDATE cart SET quantity = :qty WHERE id = :id');

    $stmt->execute(array(
        'qty' => $new_quantity,
        'id' => $id
    ));

    // update product quantity

    $new_product_quantity = $product_quantity + $old_quantity - $new_quantity;

    $stmt = $con->prepare('UPDATE products SET quantity = :qty WHERE id = :id');

    $stmt->execute(array(
        'qty' => $new_product_quantity,
        'id' => $product_id
    ));
}
