<?php
/* TICKETTO AutoDraw
 * v1.0
 */

require_once 'errorlogger.php';
require_once 'db_auth_data.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$database;
$drawableEvents;
$drawablePrizes;
$drawableTickets;

class DrawResult {
    public $eventId;
    public $prizeId;
    public $ticketId;
    public $userId;
}

function SendEmail($winner)
{
    require_once 'email_auth_data.php';
    require_once 'PHPMailer.php';
    require_once 'SMTP.php';
    require_once 'Exception.php';
    global $database;
    $sqlUsers = "SELECT `first_name`, `last_name`, `email`
        FROM `users`
        WHERE `id` = '$winner->userId';";
    $sqlEvents = "SELECT `title`
        FROM `events`
        WHERE `id` = '$winner->eventId';";
    $sqlTickets = "SELECT `color`, `value`
        FROM `tickets`
        WHERE `id` = '$winner->ticketId';";
    $winnerUserData = $database->query($sqlUsers);
    $winnerUser = $winnerUserData->fetch_assoc();
    $winnerEventData = $database->query($sqlEvents);
    $winnerEvent = $winnerEventData->fetch_assoc();
    $winnerTicketData = $database->query($sqlTickets);
    $winnerTicket = $winnerTicketData->fetch_assoc();

    $winnerEmail = $winnerUser['email'];
    $emailData = array(
        'NAME' => $winnerUser['last_name']." ".$winnerUser['first_name'],
        'EVENTTITLE' => $winnerEvent['title'],
        'TICKET' => $winnerTicket['color']." ".$winnerTicket['value'],);
    $message = file_get_contents('tmsg.html');
    foreach ($emailData as $field=>$content) { $message = str_replace('{'.$field.'}', $content, $message); }

    $mail=new PHPMailer(true);
    $mail->setLanguage('hu','PHPMailer/language/');
    //$mail->SMTPDebug='2';
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->SMTPAuth=true;
    $mail->Host=HOST;
    $mail->Username=USERNAME;
    $mail->Password=PASSWORD;
    $mail->SMTPSecure=PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port=465;
    $mail->setFrom('admin@ticketto.hu', 'Ticketto.hu');
    $mail->addAddress($winnerEmail);
    $mail->Subject='Ticketto - Ertesites a sorsolas eredmenyerol';
    $mail->msgHTML($message);
    $mail->AltBody='Ez a sima szoveges verzioja az uzenetnek';
    if (!$mail->send()) { addToLogger('Nyertes nincs ertesitve. Hiba: '.$mail->ErrorInfo, ERROR); return false; }
    return true;
}

function SelectEvents()
{
    global $database;
    global $drawableEvents;
    $sqlEvents = "SELECT `id`, `draw_time`
        FROM `events`
        WHERE `flag` <> 3
        AND `draw_time` < NOW();";
    $toDraw = $database->query($sqlEvents);
    if ($toDraw->num_rows == 0) { return false; }
    while ($row = $toDraw->fetch_assoc()) { $drawableEvents[]=$row; }
    return true;
}

function SelectPrizes($eventId)
{
    global $database;
    global $drawablePrizes;
    $sqlPrizes = "SELECT `id`, `event`
        FROM `prizes`
        WHERE `event` = '$eventId'
        AND `winner_ticket_id` IS NULL;";
    $eventsPrizes = $database->query($sqlPrizes);
    if ($eventsPrizes->num_rows == 0) { return false; }
    while ($row = $eventsPrizes->fetch_assoc()) { $drawablePrizes[]=$row; }
    return true;
}

function SelectTickets($eventId)
{
    global $database;
    global $drawableTickets;
    $sqlTickets = "SELECT `tickets`.`id`, `userevents`.`user`
        FROM `tickets`, `userevents`
        WHERE `userevents`.`event` = '$eventId'
        AND `tickets`.`userevents` = `userevents`.`id`
        AND `tickets`.`won_prize_id` IS NULL;";
    $eventsTickets = $database->query($sqlTickets);
    if ($eventsTickets->num_rows == 0) { return false; }
    while ($row = $eventsTickets->fetch_assoc()) { $drawableTickets[]=$row; }
    return true;
}

function DrawPrize($eventId, $prize)
{
    global $database;
    global $drawableTickets;
    if (!SelectTickets($eventId)) { unset($GLOBALS['drawableTickets']); return 0; }
    echo("<br>drawableTickets: <pre>");
    var_dump($drawableTickets);
    echo("</pre><br>");
    $toWin = rand(0, (count($drawableTickets) - 1));
    echo("ToWin=".$toWin."<br>");
    $winner = new DrawResult();
    $winner->eventId = $eventId;
    $winner->prizeId = intval($prize["id"]);
    $winner->ticketId = intval($drawableTickets[$toWin]["id"]);
    $winner->userId = intval($drawableTickets[$toWin]["user"]);
    unset($GLOBALS['drawableTickets']);
    $addPrizeToTicket = "UPDATE `tickets`
        SET `won_prize_id`='$winner->prizeId'
        WHERE `id`='$winner->ticketId';";
    $addTicketToPrize = "UPDATE `prizes`
        SET `winner_ticket_id`='$winner->ticketId'
        WHERE `id`='$winner->prizeId';";
    if (!$database->query($addPrizeToTicket)) { return 0; }
    if (!$database->query($addTicketToPrize)) { return 0; }
    addToLogger("Sorsolas: EventID=".$winner->eventId."; PrizeID=".$winner->prizeId."; TicketID=".$winner->ticketId."; UserID=".$winner->userId, INFO);
    if (SendEmail($winner)) { addToLogger('Nyertes ertesitve', INFO); }
    return 1;
}

function DrawEvent($event)
{
    global $database;
    global $drawablePrizes;
    echo("<br>event: ");
    var_dump($event);
    $winCounter = 0;
    $eventId = intval($event["id"]);
    if (!SelectPrizes($eventId)) { unset($GLOBALS['drawablePrizes']); return 0; }
    echo("<br>drawablePrizes: <pre>");
    var_dump($drawablePrizes);
    echo("</pre><br>");
    foreach ($drawablePrizes as $prize) { $winCounter += DrawPrize($eventId, $prize); }
    unset($GLOBALS['drawablePrizes']);
    if ($winCounter === 0) { return 0; }
    $setEventDrawed = "UPDATE `events`
        SET `flag` = '3'
        WHERE `id`='$eventId';";
    if (!$database->query($setEventDrawed)) { return 0; }
    return 1;
}

function Draw()
{
    global $database;
    global $drawableEvents;
    $drawCounter = 0;
    if (!SelectEvents()) { return false; }
    echo("<br>drawableEvents: <pre>");
    var_dump($drawableEvents);
    echo("</pre><br>");
    foreach ($drawableEvents as $event) { $drawCounter += DrawEvent($event); }
    if ($drawCounter === 0) { return false; }
    return true;
}


addToLogger("Start Ticketto AutoDraw", INFO);
$database = new mysqli($tserver, $tdbuser, $tdbpassword, $tdbname);
if (!$database->connect_error)
{
    addToLogger("Adatbazis: sikeres csatlakozas.", INFO);
    srand();
    if (!Draw()) { addToLogger("Nem tortent sorsolas.", INFO); }
    $database->close();
}
else { addToLogger("Nem sikerult csatlakozni az adatbazishoz:".$database->connect_error, ERROR); }
addToLogger("Exit AutoDraw", INFO);
writeLog();
?>
