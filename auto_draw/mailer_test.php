<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';
require_once 'errorlogger.php';
require_once 'email_auth_data.php';

$mail=new PHPMailer(true);

$mail->setLanguage('hu','PHPMailer/language/');

//$mail->SMTPDebug='2';
$mail->CharSet = 'UTF-8';
$mail->isSMTP();
$mail->Host=HOST;
$mail->Username=USERNAME;
$mail->Password=PASSWORD;
$mail->SMTPAuth=true;
$mail->SMTPSecure=PHPMailer::ENCRYPTION_SMTPS;
$mail->Port=PORT;

$mail->setFrom('admin@ticketto.hu', 'Ticketto Admin');
$mail->addAddress('borsodi.zoltan@gmail.com');
//$mail->addAddress('tenk.nobee@gmail.com');

$mail->Subject='Cron teszt uzenet';
$mail->msgHTML(file_get_contents('tmsg.html'),__DIR__);
$mail->AltBody='Ez a sima szoveges verzioja az uzenetnek';

if ($mail->send()) {
    addToLogger('Üzenet elküldve', INFO);
} else {
    addToLogger('Üzenet nincs elküldve: ' . $mail->ErrorInfo, ERROR);
}
writeLog();

?>
