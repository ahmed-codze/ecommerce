<?php

include 'connect.php';

// add new order 

if (isset($_POST['new_order'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $governorate = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
    $address1 = filter_var($_POST['address1'], FILTER_SANITIZE_STRING);
    $address2 = filter_var($_POST['address2'], FILTER_SANITIZE_STRING);
    $promocode = filter_var($_POST['promocode'], FILTER_SANITIZE_STRING);
    $coins = filter_var($_POST['coins'], FILTER_SANITIZE_STRING);
    $key = filter_var($_COOKIE['key'], FILTER_SANITIZE_STRING);


    // promocode

    if ($promocode == '') {
        $promocode = 0;
    } else {
        // check if promocode exist

        if (isset($_COOKIE['trade'])) {

            $stmt = $con->prepare("SELECT coupon, discount, usage_limit FROM coupons WHERE coupon = ? AND trade = 1 AND status = 1");
            $stmt->execute(array($promocode));
            $count = $stmt->rowCount();
            if (!$count > 0) {

                $stmt = $con->prepare("SELECT coupon, discount, usage_limit FROM coupons WHERE coupon = ? AND trade = 2 AND status = 1");
                $stmt->execute(array($promocode));
                $count = $stmt->rowCount();
                if (!$count > 0) {
                    $promocode = 0;
                } else {

                    $coupon_discount = $stmt->fetchAll();

                    foreach ($coupon_discount as $coupon) {
                        // get times user have used this coupon 
                        $stmt = $con->prepare("SELECT promocode FROM orders WHERE promocode = ? AND user_key = ?");
                        $stmt->execute(array($promocode, $_COOKIE['key']));
                        $times = count($stmt->fetchAll());

                        if ($times >= $coupon['usage_limit'] and $coupon['usage_limit'] != 0) {
                            $promocode = 0;
                        } else {
                            $promocode_discount = $coupon['discount'];
                        }
                    }
                }
            } else {
                $coupon_discount = $stmt->fetchAll();

                foreach ($coupon_discount as $coupon) {
                    // get times user have used this coupon 
                    $stmt = $con->prepare("SELECT promocode FROM orders WHERE promocode = ? AND user_key = ?");
                    $stmt->execute(array($promocode, $_COOKIE['key']));
                    $times = count($stmt->fetchAll());

                    if ($times >= $coupon['usage_limit'] and $coupon['usage_limit'] != 0) {
                        $promocode = 0;
                    } else {
                        $promocode_discount = $coupon['discount'];
                    }
                }
            }
        } else {

            $stmt = $con->prepare("SELECT coupon, discount, usage_limit FROM coupons WHERE coupon = ? AND trade = 0 AND status = 1");
            $stmt->execute(array($promocode));
            $count = $stmt->rowCount();
            if (!$count > 0) {

                $stmt = $con->prepare("SELECT coupon, discount, usage_limit FROM coupons WHERE coupon = ? AND trade = 2 AND status = 1");
                $stmt->execute(array($promocode));
                $count = $stmt->rowCount();
                if (!$count > 0) {
                    $promocode = 0;
                } else {
                    $coupon_discount = $stmt->fetchAll();

                    foreach ($coupon_discount as $coupon) {
                        // get times user have used this coupon 
                        $stmt = $con->prepare("SELECT promocode FROM orders WHERE promocode = ? AND user_key = ?");
                        $stmt->execute(array($promocode, $_COOKIE['key']));
                        $times = count($stmt->fetchAll());

                        if ($times >= $coupon['usage_limit'] and $coupon['usage_limit'] != 0) {
                            $promocode = 0;
                        } else {
                            $promocode_discount = $coupon['discount'];
                        }
                    }
                }
            } else {
                $coupon_discount = $stmt->fetchAll();

                foreach ($coupon_discount as $coupon) {
                    // get times user have used this coupon 
                    $stmt = $con->prepare("SELECT promocode FROM orders WHERE promocode = ? AND user_key = ?");
                    $stmt->execute(array($promocode, $_COOKIE['key']));
                    $times = count($stmt->fetchAll());

                    if ($times >= $coupon['usage_limit'] and $coupon['usage_limit'] != 0) {
                        $promocode = 0;
                    } else {
                        $promocode_discount = $coupon['discount'];
                    }
                }
            }
        }
    }

    // coins 

    if ($coins == '') {
        $new_coins = 0;
        $coins = 0;

        // update user info 

        $stmt = $con->prepare('UPDATE users SET 
    name = :name , email = :email, phone = :phone, governorate = :gov, address1 = :add1, address2 = :add2 WHERE user_key = :key');

        $stmt->execute(array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'gov' => $governorate,
            'add1' => $address1,
            'add2' => $address2,
            'key' => $key
        ));
    } else {

        // get new coins 

        $stmt = $con->prepare("SELECT coins FROM users WHERE user_key = ?");
        $stmt->execute(array($key));
        $user_coins = $stmt->fetchAll();

        // the loop 
        foreach ($user_coins as $coin) {
            $new_coins = $coin['coins'] - $coins;

            // update user info 

            $stmt = $con->prepare('UPDATE users SET 
            name = :name , email = :email, phone = :phone, governorate = :gov, address1 = :add1, address2 = :add2, coins = :coins WHERE user_key = :key');

            $stmt->execute(array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'gov' => $governorate,
                'add1' => $address1,
                'add2' => $address2,
                'coins' => $new_coins,
                'key' => $key
            ));
        }
    }



    // total 

    $total = '';

    $stmt = $con->prepare("SELECT * FROM cart WHERE user_key = ?");
    $stmt->execute(array($key));
    $count = $stmt->rowCount();
    if ($count > 0) {
        $rows = $stmt->fetchAll();


        // the loop 

        foreach ($rows as $row) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];
            // get products

            $stmt = $con->prepare("SELECT price, discount, trade_price FROM products WHERE id = ?");
            $stmt->execute(array($product_id));
            $products = $stmt->fetchAll();

            // the loop 
            foreach ($products as $product) {
                if (isset($_COOKIE['trade']) and $_COOKIE['trade'] == $_COOKIE['key']) {
                    $total = intval($total) + ($product['trade_price'] * $quantity);
                } else {
                    if ($product['discount'] == 0) {
                        $total = intval($total) + ($product['price'] * $quantity);
                    } else {
                        $total = intval($total) + ($product['discount'] * $quantity);
                    }
                }
            }
        }
    }

    // shipping fees 

    $stmt = $con->prepare("SELECT price, time, free_total FROM shipping WHERE governorate = ?");
    $stmt->execute(array($governorate));
    $rows = $stmt->fetchAll();

    // the loop 
    foreach ($rows as $row) {

        date_default_timezone_set('Africa/Cairo');
        $future = strtotime('friday');
        $now = time();
        $timeleft = $future - $now;
        $daysleft = round((($timeleft / 24) / 60) / 60);
        $shipping_date = $row['time'];

        if ($row['free_total'] != 0 and $total >= $row['free_total']) {
            $shipping_price = 0;
        } else {
            $shipping_price = $row['price'];
        }
    }

    // create total order number 

    $check_num = 1;

    while ($check_num = 1) {

        $total_order_number = substr(str_shuffle('' . date('md') . '0123456789'), -6);

        // check if key is exist 

        $stmt = $con->prepare("SELECT total_order_number FROM orders WHERE user_key = ?");
        $stmt->execute(array($total_order_number));
        $count = $stmt->rowCount();
        if ($count > 0) {
            $check_num = 1;
        } else {
            $check_num = 0;
            break;
        }
    }

    // add order 

    date_default_timezone_set('Africa/Cairo');
    $order_date = date("Y-m-d");
    $total_orders_price = 0;
    $stmt = $con->prepare("SELECT * FROM cart WHERE user_key = ?");
    $stmt->execute(array($key));
    $rows = $stmt->fetchAll();

    // the loop 
    foreach ($rows as $row) {

        $products_count = count($rows);

        $new_coins = $coins / $products_count;

        // get product price 

        $stmt = $con->prepare("SELECT price, discount, sells, trade_price FROM products WHERE id = ?");
        $stmt->execute(array($row['product_id']));
        $products_prices = $stmt->fetchAll();

        // the loop 
        foreach ($products_prices as $price) {
            if (isset($_COOKIE['trade']) and $_COOKIE['trade'] == $_COOKIE['key']) {
                $product_price = intval($price['trade_price']) * intval($row['quantity']);
            } else {
                if ($price['discount'] != 0) {
                    $product_price = intval($price['discount']) * intval($row['quantity']);
                } else {
                    $product_price = intval($price['price']) * intval($row['quantity']);
                }
            }
        }

        if ($promocode != 0) {
            $product_price = $product_price - round((intval($promocode_discount) / 100) * $product_price);
        }




        $stmt = $con->prepare('INSERT INTO orders 
        (product_id, quantity, user_key, order_date, promocode, size, price, shipping_fees, shipping_date, address, coins, total_order_number) 
        VALUES (:id, :quantity, :key, :date, :code, :size, :price, :fees, :shipping_date, :address, :coins, :total_order_number)');
        $stmt->execute(array(
            'id' => $row['product_id'],
            'quantity' => $row['quantity'],
            'key' => $key,
            'date' => $order_date,
            'code' => $promocode,
            'size' => $row['size'],
            'price' => $product_price,
            'fees' => $shipping_price,
            'shipping_date' => $shipping_date,
            'address' => $address1,
            'coins' => $new_coins,
            'total_order_number' => intval($total_order_number)
        ));

        $total_orders_price = intval($total_orders_price) + intval($product_price);

        // update products sells 

        $stmt = $con->prepare('UPDATE products SET sells = :sells WHERE id = :id');

        $stmt->execute(array(
            'sells' => intval($price['sells']) + intval($row['quantity']),
            'id' => $row['product_id']
        ));
    }




    // get coins 
    $total_new_coins = round(($total_orders_price * 10) / 100);

    // get old coins from account

    $stmt = $con->prepare("SELECT coins, total_buy FROM users WHERE user_key = ?");
    $stmt->execute(array($key));
    $users_coins = $stmt->fetchAll();

    foreach ($users_coins as $users_coin) {

        $new_account_coins = intval($users_coin['coins']) + $total_new_coins;
        $new_total_buy = intval($users_coin['total_buy']) + $total_orders_price;

        // update user buy info

        $stmt = $con->prepare('UPDATE users SET coins = :coins, last_buy = :lbuy , total_buy = :tbuy WHERE user_key = :key');

        $stmt->execute(array(
            'coins' => $new_account_coins,
            'lbuy' => date("Y-m-d"),
            'tbuy' => $new_total_buy,
            'key' => $key
        ));
    }

    // delete products from cart 

    $stmt = $con->prepare("DELETE FROM `cart` WHERE `cart`.`user_key` = :key");
    $stmt->bindParam(":key", $key);
    $stmt->execute();
}


// send confirmation email 

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer;

$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'Contact@spacelevels.online';
$mail->Password = '4BlZisWgsT';
$mail->setFrom('Contact@spacelevels.online', 'Space Levels');
$mail->addAddress('aalfoly18@gmail.com', 'Crazy Doctor');
$mail->addAddress($email, $name);
$mail->Subject = 'New Success Order';
$mail->IsHTML(true);

$mail->Body = '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <title> New Success Order </title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">

    <style type="text/css">
        body {
            text-align: center;
            margin: 0 auto;
            width: 650px;
            font-family: "Open Sans", sans-serif;
            background-color: #e2e2e2;
            display: block;
        }

        ul {
            margin: 0;
            padding: 0;
        }

        li {
            display: inline-block;
            text-decoration: unset;
        }

        a {
            text-decoration: none;
        }

        p {
            margin: 15px 0;
        }

        h5 {
            color: #444;
            text-align: left;
            font-weight: 400;
        }

        .text-center {
            text-align: center
        }

        .main-bg-light {
            background-color: #fafafa;
        }

        .title {
            color: #444444;
            font-size: 22px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
            padding-bottom: 0;
            text-transform: uppercase;
            display: inline-block;
            line-height: 1;
        }

        table {
            margin-top: 30px;
            wisth: 100%;
        }

        table.top-0 {
            margin-top: 0;
        }

        table.order-detail,
        .order-detail th,
        .order-detail td {
            border: 1px solid #ddd;
            border-collapse: collapse;
        }

        .order-detail th {
            font-size: 16px;
            padding: 15px;
            text-align: center;
        }

        .footer-social-icon tr td img {
            margin-left: 5px;
            margin-right: 5px;
        }
    </style>
</head>

<body style="margin: 20px auto;">
    <table align="center" border="0" cellpadding="0" cellspacing="0"
        style="padding: 0 30px;background-color: #fff; -webkit-box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);width: 100%;">
        <tbody>
            <tr>
                <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0">

                        <tr>
                            <td>
                                <p>New Success Order</p>
                                <p>ORDER ID: <strong>' . $total_order_number . '</strong></p>
                            </td>
                        </tr>
                        <tr>

                            <td>
                                <div style="border-top:1px solid #777;height:1px;margin-top: 30px;">
                            </td>
                        </tr>
                    </table>
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <h2 class="title">YOUR ORDER DETAILS</h2>
                            </td>
                        </tr>
                    </table>
                    <table class="order-detail" border="0" cellpadding="0" cellspacing="0" align="left">
                        <tr align="left">
                            <th>PRODUCT</th>
                            <th style="padding-left: 15px;">TITLE</th>
                            <th>QUANTITY</th>
                            <th>PRICE </th>
                        </tr>

                        ';

$total = 0;
$stmt = $con->prepare("SELECT product_id, quantity, price, coins, shipping_fees, promocode FROM orders WHERE total_order_number = ? ORDER BY id DESC LIMIT 1 ");
$stmt->execute(array($total_order_number));
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $mail->Body .= '
                                                        <tr>';
    $stmt = $con->prepare("SELECT title, images FROM products WHERE id = ?");
    $stmt->execute(array($row['product_id']));
    $products = $stmt->fetchAll();

    foreach ($products as $product) {
        $images = explode(',', $product['images']);
        $mail->Body .= '
        <td>
        <img src="https://' . $_SERVER['SERVER_NAME'] . '/assets/img/products/' . $images[1] . '" alt="" width="70">
        </td>
        <td valign="top" style="padding-left: 15px;"><h5 style="margin-top: 15px;">' . $product['title'] . '</h5></td>';
    }
    $product_price = $row['price'];
    $coins = $row['coins'];
    if ($coins != '0') {
        $product_price = $product_price - intval($coins);
    }
    $mail->Body .= '
                                                        <td valign="top" style="padding-left: 15px;">
                                                        <h5 style="font-size: 14px; color:#444;margin-top: 10px;">QTY : <span>' . $row['quantity'] . '</span></h5>
                                                        </td>
                                                        <td valign="top" style="padding-left: 15px;">
                                                        <h5 style="font-size: 14px; color:#444;margin-top:15px"><b>' . ($product_price * $row['quantity']) . '</b></h5>
                                                        </td>
                                                        </tr>
                                                        ';
    $total = $total + ($product_price * $row['quantity']);
    $shipping_fees = $row['shipping_fees'];
    $coupon_name = $row['promocode'];
}
if ($shipping_fees == 0) {
    $shipping_fees = 'FREE';
}
$finalTotal = $total + $shipping_fees;


$mail->Body .= '

                        <tr>
                            <td colspan="2"
                                style="line-height: 49px;font-size: 13px;color: #000000;padding-left: 20px;text-align:left;border-right: unset;">
                                Products:</td>
                            <td colspan="3" class="price"
                                style="line-height: 49px;text-align: right;padding-right: 28px;font-size: 13px;color: #000000;text-align:right;border-left: unset;">
                                <b>' . $total . ' EGP</b></td>
                        </tr>';


if ($coupon_name != '0') {
    $stmt = $con->prepare("SELECT discount FROM coupons WHERE coupon = ? ");
    $stmt->execute(array($coupon_name));
    $coupons = $stmt->fetchAll();

    foreach ($coupons as $coupon) {
        $coupon_discount = $coupon['discount'];
    }
    $mail->Body .= '

                                                                <tr>
                                                                <td colspan="2"
                                                                    style="line-height: 49px;font-size: 13px;color: #000000;padding-left: 20px;text-align:left;border-right: unset;">
                                                                    Coupon :</td>
                                                                <td colspan="3" class="price"
                                                                    style="line-height: 49px;text-align: right;padding-right: 28px;font-size: 13px;color: #000000;text-align:right;border-left: unset;">
                                                                    <b>' . $coupon_name . ' <sup>%' . $coupon_discount . ' </sup> </b></td>
                                                                    </tr>
                                                                    ';
}

if ($coins != 0) {
    $mail->Body .= '

                                                                <tr>
                                                                <td colspan="2"
                                                                    style="line-height: 49px;font-size: 13px;color: #000000;padding-left: 20px;text-align:left;border-right: unset;">
                                                                    Coins :</td>
                                                                <td colspan="3" class="price"
                                                                    style="line-height: 49px;text-align: right;padding-right: 28px;font-size: 13px;color: #000000;text-align:right;border-left: unset;">
                                                                    <b>' . $coins . '</b></td>
                                                                    </tr>
                                                                    ';
}

$mail->Body .= '

                        <tr>
                            <td colspan="2" style="line-height: 49px;font-size: 13px;color: #000000;
                                    padding-left: 20px;text-align:left;border-right: unset;">Shipping :</td>
                            <td colspan="3" class="price"
                                style="
                                        line-height: 49px;text-align: right;padding-right: 28px;font-size: 13px;color: #000000;text-align:right;border-left: unset;">
                                <b>' . $shipping_fees . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="line-height: 49px;font-size: 13px;color: #000000;
                                    padding-left: 20px;text-align:left;border-right: unset;">TOTAL PAID :</td>
                            <td colspan="3" class="price"
                                style="line-height: 49px;text-align: right;padding-right: 28px;font-size: 13px;color: #000000;text-align:right;border-left: unset;">
                                <b>' . $finalTotal . ' EGP</b></td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0" border="0" align="left"
                        style="width: 100%;margin-top: 30px;    margin-bottom: 30px;">
                        <tbody>
                            <tr>
                                <td
                                    style="font-size: 13px; font-weight: 400; color: #444444; letter-spacing: 0.2px;width: 50%;">
                                    <h5
                                        style="font-size: 16px; font-weight: 500;color: #000; line-height: 16px; padding-bottom: 13px; border-bottom: 1px solid #e6e8eb; letter-spacing: -0.65px; margin-top:0; margin-bottom: 13px;">
                                        SHIPPING ADDRESS</h5>
                                    <p style="text-align: left;font-weight: normal; font-size: 14px; color: #000000;line-height: 21px;    margin-top: 0;">
                                        ' . $address1 . '
                                    </p>
                                </td>

                                <td class="user-info"
                                    style="font-size: 13px; font-weight: 400; color: #444444; letter-spacing: 0.2px;width: 50%;">
                                    <h5
                                        style="font-size: 16px;font-weight: 500;color: #000; line-height: 16px; padding-bottom: 13px; border-bottom: 1px solid #e6e8eb; letter-spacing: -0.65px; margin-top:0; margin-bottom: 13px;">
                                        SHIPPING TIME</h5>
                                    <p style="text-align: left;font-weight: normal; font-size: 14px; color: #000000;line-height: 21px;    margin-top: 0;">
                                        ' . $shipping_date . '
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
    ';

if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    header('location: invoice.php');
    exit();
}
