<?php


include 'connect.php';
// promocode 

if (isset($_POST['promocode'])) {

    $promocode = filter_var($_POST['promocode'], FILTER_SANITIZE_STRING);
    // check if promocode exist

    if (isset($_COOKIE['trade'])) {

        $stmt = $con->prepare("SELECT coupon, discount, usage_limit FROM coupons WHERE coupon = ? AND trade = 1 AND status = 1");
        $stmt->execute(array($promocode));
        $count_coupons = $stmt->rowCount();
        if ($count_coupons > 0) {

            $coupons = $stmt->fetchAll();
            foreach ($coupons as $coupon) {
                // get times user have used this coupon 
                $stmt = $con->prepare("SELECT promocode FROM orders WHERE promocode = ? AND user_key = ?");
                $stmt->execute(array($promocode, $_COOKIE['key']));
                $times = count($stmt->fetchAll());

                if ($times >= $coupon['usage_limit'] and $coupon['usage_limit'] != 0) {
                    echo '
                <div class="text-success">
                <h6 class="my-0">برومو كود</h6>
                <small>' . $promocode . '</small>
              </div>
              <span data-discount="0" class="text-danger promo-discount no-promo " >لقد وصلت للحد الأقصى لاستخدام الكوبون</span>
                ';

                    exit();
                } else {


                    echo '
            <div class="text-success">
            <h6 class="my-0">برومو كود</h6>
            <small>' . strtoupper($promocode) . '</small>
          </div>
          <span data-discount=' . $coupon['discount'] . ' class="text-success done promo-discount">' . $coupon['discount'] . '%</span>
            ';
                    exit();
                }
            }
        } else {

            $stmt = $con->prepare("SELECT coupon, discount, usage_limit FROM coupons WHERE coupon = ? AND trade = 2 AND status = 1");
            $stmt->execute(array($promocode));
            $count_coupons = $stmt->rowCount();
            if ($count_coupons > 0) {

                $coupons = $stmt->fetchAll();
                foreach ($coupons as $coupon) {
                    // get times user have used this coupon 
                    $stmt = $con->prepare("SELECT promocode FROM orders WHERE promocode = ? AND user_key = ?");
                    $stmt->execute(array($promocode, $_COOKIE['key']));
                    $times = count($stmt->fetchAll());

                    if ($times >= $coupon['usage_limit'] and $coupon['usage_limit'] != 0) {
                        echo '
                    <div class="text-success">
                    <h6 class="my-0">برومو كود</h6>
                    <small>' . $promocode . '</small>
                  </div>
                  <span data-discount="0" class="text-danger promo-discount no-promo " >لقد وصلت للحد الأقصى لاستخدام الكوبون</span>
                    ';

                        exit();
                    } else {


                        echo '
                <div class="text-success">
                <h6 class="my-0">برومو كود</h6>
                <small>' . strtoupper($promocode) . '</small>
              </div>
              <span data-discount=' . $coupon['discount'] . ' class="text-success done promo-discount">' . $coupon['discount'] . '%</span>
                ';
                        exit();
                    }
                }
            } else {
                echo '
            <div class="text-success">
            <h6 class="my-0">برومو كود</h6>
            <small>' . $promocode . '</small>
          </div>
          <span data-discount="0" class="text-danger promo-discount no-promo " >الكوبون غير صالح</span>
            ';

                exit();
            }
        }
    } else {

        $stmt = $con->prepare("SELECT coupon, discount, usage_limit FROM coupons WHERE coupon = ? AND trade = 0 AND status = 1");
        $stmt->execute(array($promocode));
        $count_coupons = $stmt->rowCount();
        if ($count_coupons > 0) {

            $coupons = $stmt->fetchAll();
            foreach ($coupons as $coupon) {
                // get times user have used this coupon 
                $stmt = $con->prepare("SELECT promocode FROM orders WHERE promocode = ? AND user_key = ?");
                $stmt->execute(array($promocode, $_COOKIE['key']));
                $times = count($stmt->fetchAll());

                if ($times >= $coupon['usage_limit'] and $coupon['usage_limit'] != 0) {
                    echo '
                <div class="text-success">
                <h6 class="my-0">برومو كود</h6>
                <small>' . $promocode . '</small>
              </div>
              <span data-discount="0" class="text-danger promo-discount no-promo " >لقد وصلت للحد الأقصى لاستخدام الكوبون</span>
                ';

                    exit();
                } else {


                    echo '
            <div class="text-success">
            <h6 class="my-0">برومو كود</h6>
            <small>' . strtoupper($promocode) . '</small>
          </div>
          <span data-discount=' . $coupon['discount'] . ' class="text-success done promo-discount">' . $coupon['discount'] . '%</span>
            ';
                    exit();
                }
            }
        } else {

            $stmt = $con->prepare("SELECT coupon, discount, usage_limit FROM coupons WHERE coupon = ? AND trade = 2 AND status = 1");
            $stmt->execute(array($promocode));
            $count_coupons = $stmt->rowCount();
            if ($count_coupons > 0) {

                $coupons = $stmt->fetchAll();
                foreach ($coupons as $coupon) {
                    // get times user have used this coupon 
                    $stmt = $con->prepare("SELECT promocode FROM orders WHERE promocode = ? AND user_key = ?");
                    $stmt->execute(array($promocode, $_COOKIE['key']));
                    $times = count($stmt->fetchAll());

                    if ($times >= $coupon['usage_limit'] and $coupon['usage_limit'] != 0) {
                        echo '
                    <div class="text-success">
                    <h6 class="my-0">برومو كود</h6>
                    <small>' . $promocode . '</small>
                  </div>
                  <span data-discount="0" class="text-danger promo-discount no-promo " >لقد وصلت للحد الأقصى لاستخدام الكوبون</span>
                    ';

                        exit();
                    } else {


                        echo '
                <div class="text-success">
                <h6 class="my-0">برومو كود</h6>
                <small>' . strtoupper($promocode) . '</small>
              </div>
              <span data-discount=' . $coupon['discount'] . ' class="text-success done promo-discount">' . $coupon['discount'] . '%</span>
                ';
                        exit();
                    }
                }
            } else {

                echo '
            <div class="text-success">
            <h6 class="my-0">برومو كود</h6>
            <small>' . $promocode . '</small>
          </div>
          <span data-discount="0" class="text-danger promo-discount no-promo " >الكوبون غير صالح</span>
            ';
                exit();
            }
        }
    }
}


// delivery fees 

if (isset($_POST['governorate'])) {
    $governorate = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
    $total = filter_var($_POST['total'], FILTER_SANITIZE_NUMBER_INT);

    // check if exist 


    $stmt = $con->prepare("SELECT governorate FROM shipping WHERE governorate = ?");
    $stmt->execute(array($governorate));
    $count_governorate = $stmt->rowCount();
    if ($count_governorate > 0) {
        $stmt = $con->prepare("SELECT price, time, free_total FROM shipping WHERE governorate = ?");
        $stmt->execute(array($governorate));
        $rows = $stmt->fetchAll();

        // the loop 
        foreach ($rows as $row) {

            $shipping_date = $row['time'];


            if ($row['free_total'] != 0 and $total >= $row['free_total']) {
                $price = 0;
            } else {
                $price = $row['price'];
            }
            echo $price . " , " . $shipping_date;
            exit();
        }
    }
}

if (isset($_POST['coins'])) {
    $total = filter_var($_POST['total'], FILTER_SANITIZE_NUMBER_INT);
    $key = $_COOKIE['key'];

    $stmt = $con->prepare("SELECT coins FROM users WHERE user_key = ?");
    $stmt->execute(array($key));
    $coins = $stmt->fetchAll();

    // the loop 
    foreach ($coins as $coin) {
        if ($coin['coins'] != 0) {
            if ($coin['coins'] < $total) {
                $final_total = $total - intval($coin['coins']);
                echo $final_total . ',' . $coin['coins'] . ',' . 0;
                exit();
            } else {
                $final_total = 0;
                $new_coins = intval($coin['coins']) - $total;
                echo $final_total . ',' . $total . ',' . $new_coins;
                exit();
            }
        } else {
            echo $total . ',' . $total . ',' . 0;
            exit();
        }
    }
}
