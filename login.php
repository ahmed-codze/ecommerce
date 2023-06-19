<?php

include 'connect.php';

// check product

if (isset($_GET['product'])) {
    $product_id = filter_var($_GET['product'], FILTER_SANITIZE_NUMBER_INT);
    session_start();
    $_SESSION['product'] = $product_id;
}


// logout 

if (isset($_GET['logOut'])) {
    if (isset($_COOKIE['key'])) {
        unset($_COOKIE['key']);
        setcookie('key', null, -1, '/');
        if (isset($_COOKIE['trade'])) {
            unset($_COOKIE['trade']);
            setcookie('trade', null, -1, '/');
        }
        header('location: login.php');
        return true;
    }
}


$form_signin_message = 'Welcome Back!';
$form_signin_email = '';
$form_signin_password = '';
$form_signin_email_alert = '';
$form_signin_password_alert = '';

$form_signup_message = 'Create New Account';
$form_signup_name = '';
$form_signup_email = '';
$form_signup_password = '';
$form_signup_email_alert = '';
$form_signup_password_alert = '';


$show_class = '';

// signup 

if (isset($_POST['signup'])) {

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);

    // check if account is exist

    $stmt = $con->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->execute(array($email));
    $count = $stmt->rowCount();
    if ($count > 0) {
        $form_signup_name = $name;
        $form_signup_password = $pass;
        $form_signup_message = 'The Email is already exist, you can check it or sign in';
    } else {

        $check_key = 1;

        while ($check_key = 1) {

            $user_key = str_shuffle('d2c63a605ae27c13e43e26fe2c97a36c4556846dd3ef');

            // check if key is exist 

            $stmt = $con->prepare("SELECT user_key FROM users WHERE user_key = ?");
            $stmt->execute(array($user_key));
            $count = $stmt->rowCount();
            if ($count > 0) {
                $check_key = 1;
            } else {
                setcookie("key", $user_key, time() + 3600 * 24 * 90, "/");
                $check_key = 0;
                break;
            }
        }

        $stmt = $con->prepare('INSERT INTO users ( name, email, pass, user_key) VALUES ( :name, :email, :pass, :user_key)');
        $stmt->execute(array(
            'name'      => $name,
            'email'     => $email,
            'pass'      => $pass,
            'user_key'  => $user_key
        ));



        session_start();
        if (isset($_SESSION['product'])) {
            $product = $_SESSION['product'];
            header('location: product-details.php?id=' . $product);
            exit();
        } else {

            header("location: index.php");
            exit();
        }
    }
}


// signin 


if (isset($_POST['signin'])) {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);

    // check if account exist 
    $stmt = $con->prepare("SELECT email, pass FROM users WHERE email = ? AND pass = ?");
    $stmt->execute(array($email, $pass));
    $count = $stmt->rowCount();

    if ($count > 0) {

        // if account is exist 

        //get user key 

        $stmt = $con->prepare("SELECT user_key, trade FROM users WHERE email = ? AND pass = ?");
        $stmt->execute(array($email, $pass));
        $rows = $stmt->fetchAll();

        // the loop 
        foreach ($rows as $row) {
            $user_key = $row["user_key"];
            $trade = $row['trade'];
        }

        setcookie("key", $user_key, time() + 3600 * 24 * 90, "/");
        if ($trade == 1) {
            setcookie("trade", $user_key, time() + 3600 * 24 * 90, "/");
        }
        session_start();
        if (isset($_SESSION['product'])) {
            $product = $_SESSION['product'];
            header('location: product-details.php?id=' . $product);
            exit();
        } else {

            header("location: index.php");
            exit();
        }
    } else {

        // if account is not exist

        $form_signin_message = 'Wrong data! If you don\'t have an account you can create one';
        $form_signin_email = $email;
        $form_signin_password = $pass;
        $show_class = 'show';
    }
}
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
?>
<link href="assets/css/login.css" rel="stylesheet" />
<!-- start signin -->
<main class="form-signin text-center bg-light <?php echo $show_class; ?> ">
    <div class="container">
        <form action="login.php" method="POST">
            <h1 class="h3 mb-3 fw-normal "><?php echo $form_signin_message; ?></h1>

            <label for="inputemail" class="visually-hidden">Your Email</label>
            <input type="email" id="inputemail" class="form-control" name="email" value="<?php echo $form_signin_email; ?>" placeholder="Your Email " required autofocus>

            <label for="inputPassword" class="visually-hidden">Password</label>
            <div class="password-container text-center">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                <input type="password" id="inputPassword" name="pass" class="form-control" value="<?php echo $form_signin_password; ?>" placeholder="Your Password" required>
            </div>
            <div class="alert-danger" style="width: 80%"><?php echo $form_signin_password_alert;  ?></div>
            <button class="btn btn-solid" name="signin" type="submit">Sign In</button>
            <br>
            <a href="forget-pass.php">Forget password!</a>
            <br>
            <br>
            <p class="signup-convert">You don't have an account! click to join us</p>
        </form>
    </div>
</main>
<!-- end signin -->

<!-- start signup -->
<main class="form-signup text-center bg-light">
    <div class="container">
        <form action="login.php" method="POST">
            <h1 class="h3 mb-3 fw-normal "><?php echo $form_signup_message; ?></h1>

            <label for="inputname" class="visually-hidden">Your Name</label>
            <input type="text" id="inputname" name="name" value="<?php echo $form_signup_name; ?>" class="form-control" placeholder="Your Name" required autofocus>

            <label for="inputemail" class="visually-hidden">Your Email </label>
            <input type="email" id="inputemail" class="form-control" name="email" value="<?php echo $form_signup_email; ?>" placeholder=" Your Email" required autofocus>
            <div class="alert-danger" style="width: 80%"><?php echo $form_signup_email_alert;  ?></div>

            <label for="inputPassword" class="visually-hidden"> Create Password </label>
            <div class="password-container text-center">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                <input type="password" id="inputPassword" name="pass" class="form-control signup-password" value="<?php echo $form_signup_password; ?>" placeholder="Create Password" required>
            </div>
            <label for="inputPassword" class="visually-hidden"> Confirm Password </label>
            <div class="password-container text-center">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                <input type="password" id="inputPassword" name="pass" class="form-control confirm-password" value="<?php echo $form_signup_password; ?>" placeholder="Confirm Password" required>
            </div>
            <div class="alert-danger password-alert"></div>

            <button class="btn btn-solid" name="signup" type="submit">Create account</button>

            <p class="signin-convert">You already hava an account! Sign In</p>
        </form>
    </div>
</main>
<!-- end signup -->

<?php include 'theme_footer.php'; ?>
<script>
    $('.signin-convert').click(function() {
        $('.form-signup').fadeOut('slow', function() {
            $('.form-signin').fadeIn('slow')
        })
    })

    $('.signup-convert').click(function() {
        $('.form-signin').fadeOut('slow', function() {
            $('.form-signup').fadeIn('slow');
        })
    })

    // show and hide password

    $('.password-container i').click(function() {
        if ($(this).hasClass('show')) {

            $(this).removeClass('fa-eye');
            $(this).addClass('fa-eye-slash');
            $(this).siblings('input').attr('type', 'password');
            $(this).removeClass('show');

        } else {
            $(this).removeClass('fa-eye-slash');
            $(this).addClass('fa-eye');
            $(this).siblings('input').attr('type', 'text');
            $(this).addClass('show');
        }
    })

    // confirm password before signup 

    $('.form-signup form').submit(function(e) {


        if ($('.signup-password').val() !== $('.confirm-password').val()) {
            e.preventDefault();
            $('.password-alert').text('كلمة المرور غير متطابقة');

        }
    })

    // show sign in form if sign in is wrong 

    if ($('.form-signin').hasClass('show')) {
        $('.form-signup').fadeOut('fast')
        $('.form-signin').fadeIn('fast');
    }
</script>
</body>

</html>