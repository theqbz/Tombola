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

for ($i = 1; $i < 15; $i++)
{
    $name=$i.". felhasznalo";
    $email='borsodi.zoltan@gmail.com';

    $newuser="INSERT INTO users (first_name, email, password) VALUES ('$name', '$email', 'jelszo')";
    if ($dbconnect->query($newuser)===true)
    {
        $userid=$dbconnect->insert_id;
        addToLogger("A(z) ".$i.". user felvétele sikeres. ID=".$userid, INFO);
    }
    else { addToLogger("Hiba az adatok hozzáadásában: ". $newuser . "\n" . $dbconnect->error, ERROR); }
}

writeLog();




//$toadd="INSERT INTO ticketcolors (color_name) VALUES ('kek')";


?>
