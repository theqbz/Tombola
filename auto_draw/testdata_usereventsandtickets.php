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

for ($i=1;$i<15;$i++)
{
    for($j=29;$j<32;$j++)
    {
        $newuserwvent="INSERT INTO userevents (user, event)
            VALUES ('$i', '$j')";
   
        if ($dbconnect->query($newuserwvent)===true)
        {
            $usereventid=$dbconnect->insert_id;
            addToLogger("A UserEvent felv�tele sikeres. ID=".$usereventid, INFO);
            
            $color="default";
            $value=$i+$j;
            
            $newticket="INSERT INTO tickets (userevents, color, value)
                VALUES ('$usereventid', '$color', '$value')";
            if ($dbconnect->query($newticket)===true)
            {
                $ticketid=$dbconnect->insert_id;
                addToLogger("A ticket l�trehoz�sa sikeres. ID=".$ticketid, INFO);
            }
            else { addToLogger("Hiba az adatok hozz�ad�s�ban: ". $newticket . "\n" . $dbconnect->error, ERROR); }
        }
        else { addToLogger("Hiba az adatok hozz�ad�s�ban: ". $newuserwvent . "\n" . $dbconnect->error, ERROR); }
    }
}

$dbconnect->close();
writeLog();




//$toadd="INSERT INTO ticketcolors (color_name) VALUES ('kek')";


?>
