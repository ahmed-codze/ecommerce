<?Php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer;

$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'Contact@spacelevels.online';
$mail->Password = '4BlZisWgsT';
$mail->setFrom('Contact@spacelevels.online', 'Space Levels');
$mail->addReplyTo('Contact@spacelevels.online', 'Space levels');
$mail->addAddress('mostafakhaled2442004@gmail.com', 'Crazy Doctor');
$mail->Subject = 'Checking if PHPMailer works';
// $mail->msgHTML(file_get_contents('message.html'), __DIR__);
// $mail->AddEmbeddedImage('./assets/img/products/bfff476065d7a7cd86474ebc2004c2c0db576274.jpg', 'bfff476065d7a7cd86474ebc2004c2c0db576274.jpg');

$mail->Body = '
<html>
<head>
  <title>Review Request Reminder</title>
</head>
<body>
  <p>Here are the cases requiring your review in December:</p>
  <table>
    <tr>
      <th>Case title</th><th>Category</th><th>Status</th><th>Due date</th>
    </tr>
    <tr>
      <td>Case 1</td><td>Development</td><td>pending</td><td>Dec-20</td>
    </tr>
    <tr>
      <td>Case 1</td><td>DevOps</td><td>pending</td><td>Dec-21</td>
    </tr>
  </table>
  <img src="https://' . $_SERVER['SERVER_NAME'] .  '/assets/img/products/bfff476065d7a7cd86474ebc2004c2c0db576274.jpg" />
</body>
</html>
';
$mail->IsHTML(true);
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'The email message was sent.';
}
