<?php
//Tesztadatok hozzáadása
require_once 'errorlogger.php';
require_once 'db_auth_data.php';
$eventid=0;
$prizeid=0;

$dbconnect=new mysqli($tserver,$tdbuser,$tdbpassword,$tdbname);
if ($dbconnect->connect_error) {
    addToLogger("Nem sikerült csatlakozni az adatbázishoz:".$dbconnect->connect_error, ERROR);
    writeLog();
    exit();
}
addToLogger("Adatbázis: sikeres csatlakozás.", INFO);

//prize javítás

for ($i = 1; $i < 61; $i++)
{
    $prizename=$i.". tesztnyeremény";
    $prizedescription="A(z) ".$i." nyeremény leírása";
    $updateprize='UPDATE prizes SET prize_name='$='$prizename'
    , prize_description




writeLog();




//$toadd="INSERT INTO ticketcolors (color_name) VALUES ('kek')";


?>
