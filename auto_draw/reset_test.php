<?php
//Tesztadatok resetelése
require_once 'errorlogger.php';
require_once 'db_auth_data.php';

$dbconnect=new mysqli($tserver,$tdbuser,$tdbpassword,$tdbname);
if ($dbconnect->connect_error) {
    addToLogger("Nem sikerült csatlakozni az adatbázishoz:".$dbconnect->connect_error, ERROR);
    writeLog();
    exit();
}
addToLogger("Adatbázis: sikeres csatlakozás.", INFO);

$sqlPrizes = "UPDATE `prizes` SET `winner_ticket_id`= NULL;";
$sqlTickets = "UPDATE `tickets` SET `won_prize_id`= NULL;";
$sqlEvents = "UPDATE `events` SET `flag`=0;";

if (!$dbconnect->query($sqlPrizes)) addToLogger("Nyeremények resetelése sikertelen", ERROR);
if (!$dbconnect->query($sqlTickets)) addToLogger("Szelvények resetelése sikertelen", ERROR);
if (!$dbconnect->query($sqlEvents)) addToLogger("Események resetelése sikertelen", ERROR);

addToLogger("Adatok resetelése sikeres", INFO);
writeLog();
$dbconnect->close();

?>
