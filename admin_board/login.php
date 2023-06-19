<?php

$form_signin_message = 'من فضلك قم بتسجيل الدخول أولا';
$form_signin_username = '';
$form_signin_password = '';
$form_signin_password_alert = '';

if (isset($_GET['logout'])) {
    if (isset($_COOKIE['admin_key'])) {
        unset($_COOKIE['admin_key']);
        setcookie('admin_key', null, -1, '/');
        header('location: login.php');
        return true;
    }
}

// signin 

if (isset($_POST['signin'])) {

    include '../connect.php';

    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);

    // check if account is admin  

    $stmt = $con->prepare("SELECT username, pass FROM admin WHERE username = ? AND pass = ? AND manager = 0");
    $stmt->execute(array($username, $pass));
    $count = $stmt->rowCount();

    if ($count > 0) {

        // if account is admin 

        while ($check_key = 1) {

            $admin_key = str_shuffle('d2c63a605ae27c13e43e26fe2c97a36c4556846dd3ef');

            // check if key is exist 

            $stmt = $con->prepare("SELECT admin_key FROM admin WHERE admin_key = ?");
            $stmt->execute(array($admin_key));
            $count = $stmt->rowCount();

            if ($count > 0) {
                $check_key = 1;
            } else {
                //update admin key 
                $stmt = $con->prepare('UPDATE admin SET admin_key = :key WHERE username = :username');

                $stmt->execute(array(
                    'key' => $admin_key,
                    'username' => $username
                ));

                setcookie("admin_key", $admin_key, time() + 3600 * 8, "/");
                $check_key = 0;
                break;
            }
        }
        header("location: index.php");
        exit();
    } else {

        // check if account is manager  

        $stmt = $con->prepare("SELECT username, pass FROM admin WHERE username = ? AND pass = ? AND manager = 1");
        $stmt->execute(array($username, $pass));
        $count = $stmt->rowCount();

        if ($count > 0) {

            // if account is manager  

            //update manager key 

            while ($check_key = 1) {

                $admin_key = str_shuffle('d2c63a605ae27c13e43e26fe2c97a36c4556846dd3ef');

                // check if key is exist 

                $stmt = $con->prepare("SELECT admin_key FROM admin WHERE admin_key = ?");
                $stmt->execute(array($admin_key));
                $count = $stmt->rowCount();

                if ($count > 0) {
                    $check_key = 1;
                } else {
                    //update manager key 
                    $stmt = $con->prepare('UPDATE admin SET admin_key = :key WHERE username = :username');

                    $stmt->execute(array(
                        'key' => $admin_key,
                        'username' => $username
                    ));

                    setcookie("admin_key", $admin_key, time() + 3600 * 8, "/");
                    $check_key = 0;
                    break;
                }
            }


            header("location: index.php");
            exit();
        } else {

            // if account is not exist

            $form_signin_message = 'خطأ في البيانات ! ';
            $form_signin_username = $username;
            $form_signin_password = $pass;
            $show_class = 'show';
        }
    }
}


?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri&display=swap" rel="stylesheet">
    <!--font -->
    <title>Ecommerce | لوحة التحكم تسجيل الدخول </title>

    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">

    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <style>
        .form-signin {
            padding: 20px;
        }

        .form-signin input,
        .form-signin button {
            width: 80%;
            margin: 20px auto;
        }

        @media (max-width: 500px) {

            .form-signin input,
            .form-signin button {
                width: 100%;
            }
        }

        .form-signin button {
            background-color: #0d6efd;
            border: none;
            color: #fff;
        }

        .form-signin button:hover {
            background-color: #0d6efd;
            color: #fff;
            border: none;
            opacity: 0.9;
        }


        .form-signin .password-container {
            position: relative;
        }

        .form-signin .password-container i {
            position: absolute;
            left: 12%;
            top: 10px;
            cursor: pointer;
        }

        @media (max-width: 500px) {
            .form-signin .password-container i {
                left: 10px;
            }
        }
    </style>
</head>

<body dir="rtl">


    <!-- start signin -->
    <main class="form-signin text-center">
        <div class="container">
            <form action="login.php" method="POST">
                <h1 class="h3 mb-3 fw-normal "><?php echo $form_signin_message; ?></h1>

                <label for="inputusername" class="visually-hidden">يوزر نيم</label>
                <input type="text" id="inputusername" class="form-control" name="username" value="<?php echo $form_signin_username; ?>" placeholder="يوزر نيم" required autofocus>

                <label for="inputPassword" class="visually-hidden">الباسورد</label>
                <div class="password-container text-center">
                    <i class="fas fa-eye-slash"></i>
                    <input type="password" id="inputPassword" name="pass" class="form-control" value="<?php echo $form_signin_password; ?>" placeholder="الباسورد" required>
                </div>
                <div class="alert-danger" style="width: 80%"><?php echo $form_signin_password_alert;  ?></div>
                <button class=" btn btn-lg " name="signin" type="submit">تسجيل الدخول</button>
            </form>
        </div>
    </main>
    <!-- end signin -->

    <!-- latest jquery-->
    <script src="assets/js/jquery-3.3.1.min.js"></script>

    <!-- Bootstrap js-->
    <script src="assets/js/bootstrap.bundle.min.js"></script>

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
    </script>
</body>

</html>