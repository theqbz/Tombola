<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';
require_once 'errorlogger.php';

$mail=new PHPMailer(true);

$mail->setLanguage('hu','PHPMailer/language/');

//$mail->SMTPDebug='2';
$mail->CharSet = 'UTF-8';
$mail->isSMTP();
require_once 'ticketto_auth_data.php';
$mail->SMTPAuth=true;
$mail->SMTPSecure=PHPMailer::ENCRYPTION_SMTPS;
$mail->Port=465;

$mail->setFrom('admin@ticketto.hu', 'Ticketto Admin');
$mail->addAddress('borsodi.zoltan@gmail.com');
//$mail->addAddress('tenk.nobee@gmail.com');

$mail->Subject='Cron teszt uzenet';
$mail->msgHTML(file_get_contents('tmsg.html'),__DIR__);
$mail->AltBody='Ez a sima szoveges verzioja az uzenetnek';

if ($mail->send()) {
    addToLogger('Üzenet elküldve külön authdata.php-vel', INFO);
} else {
    addToLogger('Üzenet nincs elküldve: ' . $mail->ErrorInfo, ERROR);
}
writeLog();

?>
