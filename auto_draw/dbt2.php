<!DOCTYPE html>
<html lang="HU-hu">
<head>
	<meta charset="utf-8" />
	<title>Ticketto DB test</title>
</head>
<body>
<?php

require_once 'errorlogger.php';
require_once 'db_auth_data.php';

addToLogger("Start Ticketto AutoDraw", INFO);
$db=new mysqli($tserver,$tdbuser,$tdbpassword,$tdbname);
if ($db->connect_error) {
    addToLogger("Nem sikerült csatlakozni az adatbázishoz:".$db->connect_error, ERROR);
    writeLog();
    exit();
}
addToLogger("Adatbázis: sikeres csatlakozás.", INFO);
srand();
$sqlEvents = "SELECT `id`, `draw_time`
    FROM `events`
    WHERE `flag`<> 3
    AND `draw_time` < NOW();";
$toDraw = $db->query($sqlEvents);
if ($toDraw->num_rows > 0) {
    while ($row = $toDraw->fetch_assoc()) { $drawableEvents[]=$row; }
    foreach ($drawableEvents as $event) {
        $eventId = intval($event["id"]);
        $sqlPrizes = "SELECT `id`, `event`
            FROM `prizes`
            WHERE `event` = '$eventId'
            AND `winner_ticket_id` IS NULL;";
        $eventsPrizes = $db->query($sqlPrizes);
        if ($eventsPrizes->num_rows > 0) {
            while ($row = $eventsPrizes->fetch_assoc()) { $drawablePrizes[]=$row; }
            foreach ($drawablePrizes as $prize) {
                $sqlTickets = "SELECT `tickets`.`id`, `userevents`.`user`
                    FROM `tickets`, `userevents` WHERE `userevents`.`event` = '$eventId'
                    AND `tickets`.`userevents` = `userevents`.`id`
                    AND `tickets`.`won_prize_id` IS NULL;";
                $eventsTickets = $db->query($sqlTickets);
                if ($eventsTickets->num_rows > 0) {
                    while ($row = $eventsTickets->fetch_assoc()) { $drawableTickets[]=$row; }
                    $prizeId = intval($prize["id"]);
                    $toWin = rand(0, (count($drawableTickets)));
                    $winnerTicket = intval($drawableTickets[$toWin]["id"]);
                    $winnerUser = intval($drawableTickets[$toWin]["user"]);
                    addToLogger("Sorsolás: EventID=".$eventId."; PrizeID=".$prizeId."; TicketID=".$winnerTicket."; UserID=".$winnerUser, INFO);
                    $addPrizeToTicket = "UPDATE `tickets` SET `won_prize_id`='$prizeId' WHERE `id`='$winnerTicket'";
                    $addTicketToPrize = "UPDATE `prizes` SET `winner_ticket_id`='$winnerTicket' WHERE `id`='$prizeId'";
                    $setEventDrawed = "UPDATE `events` SET `flag` = '3' WHERE `id`='$eventId';";
                    if (!$db->query($addPrizeToTicket)) addToLogger("Hiba a szelvény frissítésekor: ".$addPrizeToTicket."\n".$db->error, ERROR);
                    if (!$db->query($addTicketToPrize)) addToLogger("Hiba a nyeremény frissítésekor: ".$addTicketToPrize."\n".$db->error, ERROR);
                    if (!$db->query($setEventDrawed)) addToLogger("Hiba az esemény frissítésekor: ".$addTicketToPrize."\n".$db->error, ERROR);
                }
                unset($drawableTickets);
            }
            echo("<br>Nincs több szabad nyeremény erre az eseményre");
        }
        unset($drawablePrizes);
    }
    echo("<br>Nincs több sorsolásra váró esemény<br><br>");
}
$db->close();
addToLogger("Exit AutoDraw", INFO);
writeLog();
?>
</body>
</html>
