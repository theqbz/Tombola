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

for ($i=1;$i<15;$i++)
{
    for($j=29;$j<32;$j++)
    {
        $newuserwvent="INSERT INTO userevents (user, event)
            VALUES ('$i', '$j')";
   
        if ($dbconnect->query($newuserwvent)===true)
        {
            $usereventid=$dbconnect->insert_id;
            addToLogger("A UserEvent felvétele sikeres. ID=".$usereventid, INFO);
            
            $color="default";
            $value=$i+$j;
            
            $newticket="INSERT INTO tickets (userevents, color, value)
                VALUES ('$usereventid', '$color', '$value')";
            if ($dbconnect->query($newticket)===true)
            {
                $ticketid=$dbconnect->insert_id;
                addToLogger("A ticket létrehozása sikeres. ID=".$ticketid, INFO);
            }
            else { addToLogger("Hiba az adatok hozzáadásában: ". $newticket . "\n" . $dbconnect->error, ERROR); }
        }
        else { addToLogger("Hiba az adatok hozzáadásában: ". $newuserwvent . "\n" . $dbconnect->error, ERROR); }
    }
}

$dbconnect->close();
writeLog();




//$toadd="INSERT INTO ticketcolors (color_name) VALUES ('kek')";


?>
