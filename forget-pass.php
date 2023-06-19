<?php
session_start();

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

<?php

// send confirmation email 

if (isset($_POST['submit_email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // check if email exist

    // check if account exist 
    $stmt = $con->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->execute(array($email));
    $count = $stmt->rowCount();

    if ($count > 0) {

        $_SESSION['email'] = $email;

        //create rondom code

        $code = rand(1000, 9999);


        $_SESSION['code'] = $code;

        echo '<script>window.location.href = "http://' . $_SERVER['SERVER_NAME'] . '/confirmation_code.php";</script>';
    } else {

        // if email doesn't exist 

        echo '
        <main class="form-signup text-center bg-light">
        <div class="container">
            <form action="forget-pass.php" method="POST">
                <h1 class="h3 mb-3 fw-normal ">Email doesn\'t exist, Please Check your email</h1>
    
                <br>
                <label for="inputemail" class="visually-hidden">Your Email </label>
                <input type="email" id="inputemail" class="form-control" name="email" placeholder=" Your Email" required autofocus>
    
                <button class="btn btn-solid" name="submit_email" type="submit">submit</button>
    
            </form>
        </div>
    </main>
        ';
    }

    // create code 
} elseif (isset($_POST['confirmation_code'])) {
    $confirmation_code = filter_var($_POST['confirmation_code'], FILTER_SANITIZE_NUMBER_INT);

    if ($confirmation_code == $_SESSION['code']) {
        echo '
        <main class="form-signup  text-center bg-light">
        <div class="container">
            <form action="forget-pass.php" method="POST">
                <h1 class="h3 mb-3 fw-normal ">Create new Password</h1>
    
                <br>
                <label for="inputPassword" class="visually-hidden"> Create new Password </label>
                <div class="password-container text-center">
                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    <input type="password" id="inputPassword" name="pass" class="form-control create-password"  placeholder="Create Password" required>
                </div>
                <label for="inputPassword" class="visually-hidden"> Confirm Password </label>
                <div class="password-container text-center">
                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    <input type="password" id="inputPassword" name="pass" class="form-control confirm-password"  placeholder="Confirm Password" required>
                </div>
                <div class="alert-danger password-alert"></div>

                <button class="btn btn-solid" name="new_password" type="Create">submit</button>

            </form>
        </div>
    </main>
        ';
    } else {
        echo '
        <main class="form-signup text-center bg-light">
        <div class="container">
            <form action="forget-pass.php" method="POST">
                <h1 class="h3 mb-3 fw-normal ">Code is wrong, Please try again</h1>
    
                <br>
                <label for="inputemail" class="visually-hidden">type the code you have received in your email </label>
                <input type="number" id="inputemail" class="form-control" name="confirmation_code" required autofocus>
    
                <button class="btn btn-solid" name="code" type="submit">submit</button>
    
            </form>
        </div>
    </main>
        ';
    }
} elseif (isset($_POST['pass'])) {

    $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);

    // update password

    $stmt = $con->prepare('UPDATE users SET pass = :pass WHERE email = :email');

    $stmt->execute(array(
        'pass' => $pass,
        'email' => $_SESSION['email']
    ));

    echo '
    <main class="form-signup text-center bg-light ">
    <div class="container">
        <form action="login.php" method="POST">
            <h1 class="h3 mb-3 fw-normal ">Log in with your new password</h1>

            <label for="inputemail" class="visually-hidden">Your Email</label>
            <input type="email" id="inputemail" class="form-control" name="email"  placeholder="Your Email " required autofocus>

            <label for="inputPassword" class="visually-hidden">Password</label>
            <div class="password-container text-center">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                <input type="password" id="inputPassword" name="pass" class="form-control"  placeholder="Your Password" required>
            </div>
            <button class="btn btn-solid" name="signin" type="submit">Sign In</button>
            <br>
            <a href="forget-pass.php">Forget password!</a>
            <br>
            <br>
        </form>
    </div>
</main>
    ';
} else {
    echo '
    <main class="form-signup text-center bg-light">
    <div class="container">
        <form action="forget-pass.php" method="POST">
            <h1 class="h3 mb-3 fw-normal ">Type Your Email</h1>

            <br>
            <label for="inputemail" class="visually-hidden">Your Email </label>
            <input type="email" id="inputemail" class="form-control" name="email" placeholder=" Your Email" required autofocus>

            <button class="btn btn-solid" name="submit_email" type="submit">submit</button>

        </form>
    </div>
    </main>
    ';
}

?>


<?php include 'theme_footer.php'; ?>

<script>
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

    $('.form-signup form').submit(function(e) {

        if ($('.create-password').val() !== $('.confirm-password').val()) {
            e.preventDefault();
            $('.password-alert').text('Password doesn\'t match');

        } else {
            $(this).submit();
        }
    })
</script>

</body>

</html>