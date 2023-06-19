<?php
session_start();

if (!(isset($_SESSION['code']))) {
    header('location: error.php');
    exit();
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

<?php

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
$mail->addAddress($_SESSION['email']);
$mail->addAddress($email, $name);
$mail->Subject = 'Confirmation Code';
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
            <title> Confirmation Code </title>
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
        
                .text-center {
                    text-align: center
                }
        
                .main-bg-light {
                    background-color: #fafafa;
                }
        
            </style>
        </head>
        
        <body style="margin: 20px auto;">
        
        <h1 class="text-center"> Your confirmation code is : </h1>
        
        <br>
        <br>
        
        <h2 class="text-center" style="color: blue;">' . $_SESSION['code'] . '</h2
        
        </body>
        
        </html>
            ';

$mail->send();

if (isset($_POST['confirmation_code'])) {
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
            <form action="confirmation_code.php" method="POST">
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
} else {
    echo '
                        <main class="form-signup text-center bg-light">
                        <div class="container">
                            <form action="confirmation_code.php" method="POST">
                                <h1 class="h3 mb-3 fw-normal ">we have sent you a confirmation code </h1>
                    
                                <br>
                                <label for="inputemail" >type the code you have received in your email </label>
                                <input type="number" id="inputemail" class="form-control" name="confirmation_code" required autofocus>
                    
                                <button class="btn btn-solid" name="code" type="submit">submit</button>
                    
                            </form>
                        </div>
                    </main>
                        ';
}

include 'theme_footer.php'; ?>

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