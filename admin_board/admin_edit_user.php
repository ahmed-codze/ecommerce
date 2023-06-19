<?php
include '../connect.php';

if (isset($_GET['key'])) {

    $key = filter_var($_GET['key'], FILTER_SANITIZE_STRING);

    // check if account is exit  

    $stmt = $con->prepare("SELECT user_key FROM users WHERE user_key = ?");
    $stmt->execute(array($key));
    $count = $stmt->rowCount();

    if (!$count > 0) {
        header('location: users.php');
        exit();
    }
} else {
    header('location: users.php');
    exit();
}


$stmt = $con->prepare("SELECT * FROM users WHERE user_key = ?");
$stmt->execute(array($key));
$users = $stmt->fetchAll();

// the loop 
foreach ($users as $user) {
    $name = $user['name'];
    $email = $user['email'];
    $phone = $user['phone'];
    $address1 = $user['address1'];
    $address2 = $user['address2'];
    $user_governorate = $user['governorate'];
    $password = $user['pass'];
    $coins = $user['coins'];
}

// edit profile data 

if (isset($_POST['update_profile'])) {

    $new_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $new_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $new_phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $new_address1 = filter_var($_POST['address1'], FILTER_SANITIZE_STRING);
    $new_address2 = filter_var($_POST['address2'], FILTER_SANITIZE_STRING);
    $new_governorate = filter_var($_POST['governorate'], FILTER_SANITIZE_STRING);
    $new_password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $new_coins = filter_var($_POST['coins'], FILTER_SANITIZE_NUMBER_INT);


    // check that email doesn't exist in another account

    $stmt = $con->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->execute(array($new_email));
    $count = $stmt->rowCount();
    if ($count > 0) {
        if ($new_email == $email) {

            if (empty($new_name)) {
                $new_name = $name;
            }
            if (empty($new_email)) {
                $new_email = $email;
            }
            if (empty($new_phone)) {
                $new_phone = $phone;
            }
            if (empty($new_address1)) {
                $new_address1 = $address1;
            }
            if (empty($new_address2)) {
                $new_address2 = $address2;
            }
            if (empty($new_governorate)) {
                $new_governorate = $governorate;
            }
            if (empty($new_password)) {
                $new_password = $password;
            }
            if (empty($new_coins)) {
                $new_coins = $coins;
            }

            $stmt = $con->prepare('UPDATE users SET 
    name = :name , email = :email, phone = :phone, governorate = :gov, address1 = :add1, address2 = :add2, pass = :pass, coins = :coins WHERE user_key = :key');

            $stmt->execute(array(
                'name' => $new_name,
                'email' => $new_email,
                'phone' => $new_phone,
                'gov' => $new_governorate,
                'add1' => $new_address1,
                'add2' => $new_address2,
                'pass' => $new_password,
                'coins' => $new_coins,
                'key' => $key
            ));

            header('location: user_details.php?key=' . $key);
            exit();
        } else {
            echo "<script>alert('البريد الالكتروني موجود بالفعل لمستخدم اخر')
            </script>";
        }
    } else {


        if (empty($new_name)) {
            $new_name = $name;
        }
        if (empty($new_email)) {
            $new_email = $email;
        }
        if (empty($new_phone)) {
            $new_phone = $phone;
        }
        if (empty($new_address1)) {
            $new_address1 = $address1;
        }
        if (empty($new_address2)) {
            $new_address2 = $address2;
        }
        if (empty($new_governorate)) {
            $new_governorate = $governorate;
        }

        $stmt = $con->prepare('UPDATE users SET 
        name = :name , email = :email, phone = :phone, governorate = :gov, address1 = :add1, address2 = :add2, pass = :pass, coins = :coins WHERE user_key = :key');

        $stmt->execute(array(
            'name' => $new_name,
            'email' => $new_email,
            'phone' => $new_phone,
            'gov' => $new_governorate,
            'add1' => $new_address1,
            'add2' => $new_address2,
            'pass' => $new_password,
            'coins' => $new_coins,
            'key' => $key
        ));

        header('location: user_details.php?key=' . $key);
        exit();
    }
}

$serch_placeholder = " ابحث عميل";
$serch_page = 'user-list.php';
include 'admin_header.php';
?>

<style>
    .page-body input {
        margin: 10px 0;
    }


    .page-body label {
        margin-top: 10px;
    }

    /* Dropdown Button */
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
</style>

<div class="page-body">
    <section style="margin-top: 1em;" class="container">
        <form action="#" method="POST" class="form-group">
            <div class="row">

                <div class="col-md-6">
                    <label for="name">الاسم</label>
                    <input type="text" name="name" value="<?php echo $name ?>" id="name" class="form-control" reqiured>
                </div>

                <div class="col-md-6">
                    <label for="email">البريد الالكتروني</label>
                    <input type="email" name="email" value="<?php echo $email ?>" id="email" class="form-control" reqiured>
                </div>

                <div class="col-md-6">
                    <label for="phone">رقم الهاتف</label>
                    <input type="text" name="phone" value="<?php echo $phone ?>" id="phone" class="form-control" reqiured>
                </div>

                <div class="col-md-6">
                    <label for="coins">العملات</label>
                    <input type="text" name="coins" value="<?php echo $coins ?>" id="coins" class="form-control" reqiured>
                </div>

                <div class="col-md-6">
                    <label for="password">كلمة المرور</label>
                    <input type="text" name="password" value="<?php echo $password ?>" id="password" class="form-control" reqiured>
                </div>

                <div class="dropdown col-md-6 ">
                    <label for="governorate">اختر المحافظة</label>
                    <?php
                    if ($user_governorate == '') {
                        echo '
                            <button onclick="myFunction()" class="dropbtn btn w-100" type="button">

                            اختر المحافظة (يمكننا التوصيل لهذه المحافظات فقط في الوقت الحالي)
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

                <div class="col-md-12">
                    <label for="address1">العنوان الاساسي</label>
                    <input type="text" name="address1" value="<?php echo $address1 ?>" id="address1" class="form-control" reqiured>
                </div>

                <div class="col-md-12">
                    <label for="address2">العنوان الاحتياطي</label>
                    <input type="text" name="address2" value="<?php echo $address2 ?>" id="address2" class="form-control" reqiured>
                </div>

                <div class="col-md-12 text-center" style="margin-top: 20px;">
                    <input type="submit" name="update_profile" value="حفظ البيانات" id="update" class="btn btn-primary w-50">
                </div>

            </div>
        </form>
    </section>
</div>

<?php include 'admin_footer.php'; ?>

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

    $('.dropdown-content a').click(function() {
        $('input[type=hidden].governinput').val($(this).text());
    })
</script>
</body>

</html>