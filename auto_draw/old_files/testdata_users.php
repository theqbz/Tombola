<?php
//Tesztadatok hozz�ad�sa
require_once 'errorlogger.php';
require_once 'db_auth_data.php';
$eventid=0;
$prizeid=0;

$dbconnect=new mysqli($tserver,$tdbuser,$tdbpassword,$tdbname);
if ($dbconnect->connect_error) {
    addToLogger("Nem siker�lt csatlakozni az adatb�zishoz:".$dbconnect->connect_error, ERROR);
    writeLog();
    exit();
}
addToLogger("Adatb�zis: sikeres csatlakoz�s.", INFO);

for ($i = 1; $i < 15; $i++)
{
    $name=$i.". felhasznalo";
    $email='borsodi.zoltan@gmail.com';

    $newuser="INSERT INTO users (first_name, email, password) VALUES ('$name', '$email', 'jelszo')";
    if ($dbconnect->query($newuser)===true)
    {
        $userid=$dbconnect->insert_id;
        addToLogger("A(z) ".$i.". user felv�tele sikeres. ID=".$userid, INFO);
    }
    else { addToLogger("Hiba az adatok hozz�ad�s�ban: ". $newuser . "\n" . $dbconnect->error, ERROR); }
}

writeLog();




//$toadd="INSERT INTO ticketcolors (color_name) VALUES ('kek')";


?>
