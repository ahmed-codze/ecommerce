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

// get user data
$user_key = $_COOKIE['key'];
$stmt = $con->prepare("SELECT * FROM users WHERE user_key = ?");
$stmt->execute(array($user_key));
$users = $stmt->fetchAll();

// the loop 
foreach ($users as $user) {
    $name = $user['name'];
    $email = $user['email'];
    $phone = $user['phone'];
    $user_governorate = $user['governorate'];
    $address1 = $user['address1'];
    $address2 = $user['address2'];
}


?>

<style>
    label {
        font-size: 18px;
    }

    input {
        font-size: 20px;
    }

    .confirm-price {
        font-size: 20px;
    }

    .list-group {
        padding: 0;
    }

    .coins-payment {
        background-color: #212529;
        color: #fff;
        width: 100%;
        margin-top: 20px;
    }

    .coins-payment:hover {
        color: #fff;
    }

    /* Dropdown Button */
    .dark .dropbtn {
        border-color: #3c3c3c;
        background: #3c3c3c;
        color: #6c757d;
    }


    .dark .dropdown-content {
        background-color: #ddd;
    }

    .dropbtn {
        border-color: #d5d9d9;
        border-radius: 8px;
        color: #0f1111;
        background: #f0f2f2;
        box-shadow: 0 2px 5px 0 rgb(213 217 217 / 50%);
        width: 90%;
        margin-top: 20px;
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
        min-width: 90%;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        max-height: 300px;
        z-index: 999;
        overflow: scroll;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #ddd;
    }

    .show {
        display: block !important;
    }


    .dark li {
        background-color: #2b2b2b;
    }

    .product-title {
        margin-bottom: 5px !important;
    }
</style>

<div class="container">
    <main>
        <div class="py-5 text-center">
            <div class="title1">
                <h2 class="title-inner1">Confirm Order</h2>
            </div>

        </div>
        <form action="thanks.php" method="POST" class="order-form" onkeydown="return event.key != 'Enter';">
            <div class="row g-3">

                <div class="col-md-7 col-lg-8">
                    <h3 class="mb-3">Your info</h3>


                    <div class="row g-3">
                        <div class="col-sm-12">
                            <label for="name" class="form-label">name </label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="" value="<?php echo $name; ?>" required>
                        </div>

                        <div class="col-12">
                            <label for="username" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="email" class="form-control" name="email" id="email" placeholder="example@gmail.com" required value="<?php echo $email; ?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="username" class="form-label">phone number </label>
                            <div class="input-group">
                                <input type="tel" class="form-control" name="phone" id="phone" placeholder="phone number" required value="<?php echo $phone; ?>">
                            </div>
                        </div>

                        <div class="dropdown col-12 w-100">

                            <?php
                            if ($user_governorate == '') {
                                echo '
                    <button onclick="myFunction()" class="dropbtn btn w-100" type="button">

                    Choose Your City 
                    </button>
                  ';
                            } else {
                                echo '
                  <button onclick="myFunction()" class="dropbtn btn w-100 choosen" type="button">

                  ' . $user_governorate . ' 
                  </button>';
                            }

                            ?>


                            <input type="hidden" id="governorate" class="governinput" value="<?php echo $user_governorate; ?>" name="governorate">
                            <div id="myDropdown3" class="dropdown-content w-50">

                                <?php




                                // get governorates 

                                $stmt = $con->prepare("SELECT governorate FROM shipping");
                                $stmt->execute();
                                $governorates = $stmt->fetchAll();

                                // the loop 
                                foreach ($governorates as $governorate) {
                                    echo '
                <a class="dropdown-item">' . $governorate['governorate'] . '</a>
                ';
                                }

                                ?>

                            </div>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Your address</label>
                            <input type="text" class="form-control" name="address1" id="address" placeholder="Your address" required value="<?php echo $address1; ?>">
                        </div>

                        <div class="col-12">
                            <label for="address2" class="form-label">additional address <span class="text-muted"> (optional) </span></label>
                            <input type="text" class="form-control" name="address2" id="address2" placeholder="if you have additional address" value="<?php echo $address2; ?>">
                        </div>
                        <input type="hidden" name="promocode" value="" class="promoinput">
                        <input type="hidden" name="coins" value="" class="coinsinput">
                    </div>

                    <hr class="my-4">


                </div>

                <div class="col-md-5 col-lg-4 order-md-last">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted" style="font-size:18px">your cart</span>
                    </h4>


                    <ul class="list-group mb-3">

                        <?php

                        $user_key = $_COOKIE['key'];
                        $total = '';

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
                                    echo '
                  
                      <li class="list-group-item d-flex justify-content-between lh-sm">
                      <div>
                        <h4 class="my-0 product-title">' . $product['title'] . '</h4>
                        <span class="text-muted">size: ' . $size . '  </span>
                        &nbsp;
                        <span class="text-muted">quantity : ' . $quantity . '  </span>
                      </div>';
                                    if (isset($_COOKIE['trade']) and $_COOKIE['trade'] == $_COOKIE['key']) {
                                        echo '<span class="text-muted">' . ($product['trade_price'] * $quantity)  . ' LE</span>';
                                        $total = intval($total) + ($product['trade_price'] * $quantity);
                                    } else {
                                        if ($product['discount'] == 0) {
                                            echo '<span class="text-muted">' . $product['price'] * $quantity  . ' LE</span>';
                                            $total = intval($total) + ($product['price'] * $quantity);
                                        } else {
                                            echo '<span class="text-muted">' . $product['discount'] * $quantity . ' LE</span>';
                                            $total = intval($total) + ($product['discount'] * $quantity);
                                        }
                                    }
                                    echo '
                    </li>
                  ';
                                }
                            }
                        } else {
                            echo '
                <script>
                window.location.href = "error.php";
                </script>
                ';
                        }
                        ?>
                        <li class="list-group-item d-flex justify-content-between bg-light promocode-result">

                        </li>

                        <li class="list-group-item d-flex justify-content-between" style="font-size: 18px">
                            <span> Subtotal: </span>
                            <?php
                            if ($user_governorate == '') {
                                echo '
              <strong class="total sub-total" data-shipping=0 data-old_total=' . $total . ' data-total= '  . $total . '>  ' . $total . '</strong>
              ';
                            } else {
                                echo '
                <strong class="total sub-total" data-shipping=0 data-old_total='  . intval($total) . ' data-total= '  . intval($total) . '> ' . intval($total) . '</strong>
                ';
                            }
                            ?>
                        </li>
                        <div class="input-group promocode-group" style="margin: 20px 0;">
                            <input type="text" class="form-control promocode-input" placeholder="Coupon code">
                            <button type="button" class="btn btn-secondary promocode-button">Use</button>
                        </div>
                        <button class="w-100 btn btn-dark coins-pay btn-lg" type="button">Pay with your coins</button>
                        <hr>

                        <!-- delivery fees and total !-->


                        <?php
                        if ($user_governorate == '') {
                            echo '
                  <li style="font-size: 18px" class="list-group-item bg-light d-flex justify-content-between">
                  <span> delivery fees: </span>
                  <span class="text-muted shipping-fees 0" > please select your city first </span>
                  </li>
                  <li style="font-size: 18px" class="list-group-item  bg-light d-flex justify-content-between">
                  <span> delivery time : </span>
                  <strong class="shipping-day"> please select your city first </strong>
                  </li>
                  <hr>
                  <li class="list-group-item bg-light d-flex justify-content-between final-total">
                  <span></span>
                  <strong></strong>
                  </li>
              ';
                        } else {
                            // get governorate delivery price 

                            $stmt = $con->prepare("SELECT price, free_total, time FROM shipping WHERE governorate = ?");
                            $stmt->execute(array($user_governorate));
                            $governorate_info = $stmt->fetchAll();

                            // the loop 
                            foreach ($governorate_info as $info) {
                                if ($info['free_total'] != 0 and $total >= $info['free_total']) {
                                    $fees = 0;

                                    echo '
                      <li style="font-size: 18px" class="list-group-item bg-light d-flex justify-content-between">
                      <span> delivery fees: </span>
                      <span class="text-muted shipping-fees" >FREE</span>
                      </li>
                      ';
                                } else {
                                    $fees = $info['price'];
                                    echo '
                    <li style="font-size: 18px" class="list-group-item bg-light d-flex justify-content-between">
                    <span> delivery fees: </span>
                    <span class="text-muted shipping-fees" >' . $fees . ' LE </span>
                    </li>
                    ';
                                }

                                // delivery time 

                                $shipping_date = $info['time'];
                                echo '
                  <li style="font-size: 18px" class="list-group-item bg-light d-flex justify-content-between">
                  <span> delivery time : </span>
                  <strong class="shipping-day"> Your order will arrive ' . $shipping_date . ' </strong>
                  </li>
                  <hr>
                  <li style="font-size: 18px" class="list-group-item bg-light d-flex justify-content-between done-governoment final-total">
                  <span>Total:</span>
                  <strong>' . (intval($total) + intval($fees)) .  ' LE </strong>
                  </li>
                  ';
                            }
                        }
                        ?>



                    </ul>

                </div>

            </div>
            <div class="text-center">
                <button style="max-width: 300px !important; text-align:center;" class="w-100 btn btn-solid confirm-order" name="new_order" type="submit">Confirm Order </button>
                <br>
                <br>

            </div>
        </form>
    </main>
</div>

<div class="added-notification">
    <h3 class="add-to-cart-text">added to cart</h3>
</div>

<?php include 'theme_footer.php'; ?>

<script>
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

    $('.dropdown-content a').click(function() {
        $(this).parent(".dropdown-content").siblings('.dropbtn').text($(this).text());
        $(this).parent(".dropdown-content").siblings('.dropbtn').addClass('choosen');
        $('' + $(this).data('input') + '').attr('value', $(this).text());
    })

    // check government before send 

    $('.order-form').submit(function(e) {
        if (!$('button.dropbtn').hasClass('choosen')) {
            e.preventDefault();
        }
    })


    $('.promocode-button').click(function() {

        if ($('.promocode-input').val() != '') {

            $('.coins-pay').hide();

            $('input[type=hidden].promoinput').val($('.promocode-input').val());

            // check promocode 
            $('.sk-circle').show();
            $.post('order-request.php', {
                promocode: $('.promocode-input').val()

            }, function(data) {
                $('.sk-circle').fadeOut();
                $('.promocode-result').html(data);
                if (!$('.promo-discount').hasClass('no-promo')) {


                    $total = parseInt($('.sub-total').data('old_total'));
                    $text = Math.floor($total - ((parseInt($('.promo-discount').data('discount')) / 100) * $total));

                    $('.total').text($text).attr('data-total', $text);

                    if ($('.final-total').hasClass('done-governoment')) {
                        $('.final-total span').text(' Total:');
                        $('.final-total strong').text(parseInt($text) + parseInt($('.shipping-fees').text()) + ' LE');
                    }

                    $('.promocode-input').val('').blur();

                } else {
                    $('.total').text($('.sub-total').data('old_total'));
                    $('.total').attr('data-total', $('.sub-total').data('old_total'));

                    if ($('.final-total').hasClass('done-governoment')) {
                        $('.final-total span').text(' total:');
                        $('.final-total strong').text(parseInt($('.sub-total').data('old_total')) + parseInt($('.shipping-fees').text()) + ' LE');
                    }

                }
            })
        }


    })

    // delivery fees

    $('.dropdown-content a').click(function() {
        $('input[type=hidden].governinput').val($(this).text());
        $('.sk-circle').show();
        $.post('order-request.php', {

            governorate: $(this).text(),
            total: $('.sub-total').data('old_total')
        }, function(data) {
            $('.sk-circle').fadeOut();
            $data_array = data.split(',');
            if ($data_array[0] == 0) {
                $('.add-to-cart-text').html('Order will arrive within ' + $data_array[1] + '  <strong> & delivery fees: FREE </strong>  ');
            } else {
                $('.add-to-cart-text').html('Order will arrive within ' + $data_array[1] + '');
            }
            $('.added-notification').addClass('show');
            setTimeout(function() {
                $('.added-notification').removeClass('show');
            }, 5000);

            $('.shipping-fees').text($data_array[0] + ' LE ');
            $('.shipping-day').text(' Order Will arrive within ' + $data_array[1]);
            $('.final-total').addClass('done-governoment');
            $('.final-total span').text('Total:');
            $('.final-total strong').text((parseInt($('.sub-total').text()) + parseInt($data_array[0])) + ' LE');
            $('.governorate-input').val($(this).text());

        });
    })


    // coins-pay 

    $('.coins-pay').click(function() {

        if ($('.coins span').hasClass('0')) {
            $('.add-to-cart-text').html('You do not have coins yet, Order to win more');

            $('.added-notification').addClass('show');
            setTimeout(function() {
                $('.added-notification').removeClass('show');
            }, 5000);
        } else {



            $('.promocode-group').hide();

            if ($('.coins-pay').hasClass('done')) {
                $('.add-to-cart-text').html('coins have been used');

                $('.added-notification').addClass('show');
                setTimeout(function() {
                    $('.added-notification').removeClass('show');
                }, 5000);
            } else {
                $('.sk-circle').show();
                $.post('order-request.php', {
                    coins: true,
                    total: parseInt($('.sub-total').data('old_total'))

                }, function(data) {
                    $('.sk-circle').fadeOut();
                    $coins_array = data.split(',');
                    $('.add-to-cart-text').html('<strong>' + $coins_array[1] + '</strong>' + ' Coins have been used');

                    $('.added-notification').addClass('show');
                    setTimeout(function() {
                        $('.added-notification').removeClass('show');
                    }, 5000);

                    $('input[type=hidden].coinsinput').val($coins_array[1]);

                    $('.sub-total').text($coins_array[0]);
                    $('.final-total strong').text(parseInt($('.final-total strong').text()) - parseInt($coins_array[1]) + ' LE');
                    $('.coins span').text($coins_array[2]);
                    $('.coins-pay').css('marginTop', '20px').addClass('done');
                    $('.sub-total').attr('data-old_total', (parseInt($('.total').data('old')) - parseInt($coins_array[1])))
                })
            }

        }
    })
</script>

</body>

</html>