<?php
//Tesztadatok resetelése
require_once '../errorlogger.php';
require_once '../db_auth_data.php';
setLoggerMode(_INFO);

$dbconnect=new mysqli($tserver,$tdbuser,$tdbpassword,$tdbname);
if ($dbconnect->connect_error) {
    addToLogger("Adatbázis: sikertelen csatlakozás. Hiba:".$dbconnect->connect_error, _ERROR);
    writeLog();
    exit();
}
addToLogger("Adatbázis: sikeres csatlakozás.", _INFO);

$sqlPrizes = "UPDATE `prizes` SET `winner_ticket_id`= NULL;";
$sqlTickets = "UPDATE `tickets` SET `won_prize_id`= NULL;";
$sqlEvents = "UPDATE `events` SET `status`=0;";

if (!$dbconnect->query($sqlPrizes)) addToLogger("Nyeremények resetelése sikertelen", _ERROR);
if (!$dbconnect->query($sqlTickets)) addToLogger("Szelvények resetelése sikertelen", _ERROR);
if (!$dbconnect->query($sqlEvents)) addToLogger("Események resetelése sikertelen", _ERROR);

addToLogger("Resetelés vége", _INFO);
writeLog();
$dbconnect->close();

?>
