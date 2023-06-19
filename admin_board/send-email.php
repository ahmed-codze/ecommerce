<?php


if (!(isset($_POST['subject']))) {
    header('location: error.php');
    exit();
}

$subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
$message_body = $_POST['body'];
$users_array =  explode(' ', $_POST['users_array']);
include '../connect.php';



// send confirmation email 

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer;

$mail->Host = 'smtp.hostinger.com';

$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'Contact@spacelevels.online';
$mail->Password = '4BlZisWgsT';
$mail->setFrom('Contact@spacelevels.online', 'Space Levels');


//get user email 
foreach ($users_array as $user) {
    $stmt = $con->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->execute(array($user));
    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $mail->addAddress($row['email']);
    }
}
$mail->Subject = $subject;
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
    <title>' . $subject . '</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
</head>

<body style="margin: 20px auto;">

<br>

' . $message_body . '

</body>

</html>
    ';

if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo ' <h1> The email message was sent. <h1>
                <h3> you will back to Home page </h3>

                <script>
                setInterval("redirect()", 2300);
            
                function redirect() {
                    location.href = "index.php";
                }
              </script>
        ';
}
