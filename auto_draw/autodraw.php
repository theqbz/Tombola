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
    require_once 'PHPMailer/PHPMailer.php';
    require_once 'PHPMailer/SMTP.php';
    require_once 'PHPMailer/Exception.php';
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

    $mail = new PHPMailer(true);
    $mail->setLanguage('hu','PHPMailer/language/');
    $mail->isSMTP();
    $mail->CharSet    = 'UTF-8';
    $mail->SMTPDebug  = '0';     // 0: off (set 2 to check process)
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Host       = HOST;
    $mail->Port       = PORT;
    $mail->Username   = USERNAME;
    $mail->Password   = PASSWORD;
    $mail->Subject    = 'Ticketto - Értesítés a sorsolás eredményéről';
    $mail->AltBody    = 'Ezt az üzenetet azért kaptad, mert nyert azegyik szelvényed a ticketto.hu-n.\n
        A részletekért kérjük, lépj be a fiókodba a ticketto.hu oldalon!\n
        Üdvözlettel, Ticketto.hu';
    $mail->setFrom('admin@ticketto.hu', 'Ticketto.hu');
    $mail->addAddress($winnerEmail);
    $mail->msgHTML($message);
    if (!$mail->send()) { addToLogger('Nyertes nincs értesítve. Hiba: '.$mail->ErrorInfo, ERROR); return false; }
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
    $toWin = rand(0, (count($drawableTickets) - 1));
    /* DEBUG:
    echo("<br>prize: "); var_dump($prize);
    echo("<br>drawableTickets: <pre>"); var_dump($drawableTickets); echo("</pre>");
    echo("<br>ToWin=".$toWin);
     */
    $winner = new DrawResult();
    $winner->eventId = $eventId;
    $winner->prizeId = intval($prize["id"]);
    $winner->ticketId = intval($drawableTickets[$toWin]["id"]);
    $winner->userId = intval($drawableTickets[$toWin]["user"]);
    $addPrizeToTicket = "UPDATE `tickets`
        SET `won_prize_id` = '$winner->prizeId'
        WHERE `id` = '$winner->ticketId';";
    $addTicketToPrize = "UPDATE `prizes`
        SET `winner_ticket_id` = '$winner->ticketId'
        WHERE `id` = '$winner->prizeId';";
    if (!$database->query($addPrizeToTicket)) { unset($GLOBALS['drawableTickets']); return 0; }
    if (!$database->query($addTicketToPrize)) { unset($GLOBALS['drawableTickets']); return 0; }
    addToLogger("Sorsolas: EventID=".$winner->eventId.
        "; PrizeID=".$winner->prizeId.
        "; TicketID=".$winner->ticketId.
        "; UserID=".$winner->userId, INFO);
    if (SendEmail($winner)) { addToLogger('Nyertes értesítve.', INFO); }
    unset($GLOBALS['drawableTickets']);
    return 1;
}

function DrawEvent($event)
{
    global $database;
    global $drawablePrizes;
    $winCounter = 0;
    $eventId = intval($event["id"]);
    if (!SelectPrizes($eventId)) { unset($GLOBALS['drawablePrizes']); return 0; }
    /* DEBUG:
    echo("<br>event: "); var_dump($event);
    echo("<br>drawablePrizes: <pre>"); var_dump($drawablePrizes); echo("</pre>");
     */
    foreach ($drawablePrizes as $prize) { $winCounter += DrawPrize($eventId, $prize); }
    if ($winCounter === 0) { return 0; }
    $setEventDrawed = "UPDATE `events`
        SET `flag` = '3'
        WHERE `id` = '$eventId';";
    if (!$database->query($setEventDrawed)) { unset($GLOBALS['drawablePrizes']); return 0; }
    unset($GLOBALS['drawablePrizes']);
    return 1;
}

function Draw()
{
    global $database;
    global $drawableEvents;
    $drawCounter = 0;
    if (!SelectEvents()) { return false; }
    /* DEBUG:
    echo("<br>drawableEvents: <pre>"); var_dump($drawableEvents); echo("</pre>");
     */
    foreach ($drawableEvents as $event) { $drawCounter += DrawEvent($event); }
    if ($drawCounter === 0) { return false; }
    return true;
}


addToLogger("START AutoDraw", INFO);
$database = new mysqli($tserver, $tdbuser, $tdbpassword, $tdbname);
if (!$database->connect_error)
{
    addToLogger("Adatbázis: sikeres csatlakozás.", INFO);
    srand();
    if (!Draw()) { addToLogger("Nem történt sorsolás.", INFO); }
    $database->close();
}
else { addToLogger("Adatbázis: sikertelen csatlakozás. Hiba:".$database->connect_error, ERROR); }
addToLogger("EXIT AutoDraw", INFO);
writeLog();
?>
