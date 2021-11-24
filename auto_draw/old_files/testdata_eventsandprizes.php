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

for ($i = 1; $i < 31; $i++)
{
    $title=$i.". tesztesem�ny";
    $startdate=date('Y-m-d H:i:s');
    $drawtime=date('Y-m-d H:i:s');
    $description='Ez a(z) '.$i.". esemeny r�szletes le�rasa";
    $ispub=(int)'1';
    $autoticket=(int)'1';
    $location="Szombathely";
    $hash="EVENTHASH_".$i;

    $newevent="INSERT INTO events (title, start_time, draw_time, description, is_public, auto_ticket, location, hash) VALUES ('$title', '$startdate', '$drawtime', '$description', '$ispub', '$autoticket', '$location', '$hash')";
    if ($dbconnect->query($newevent)===true)
    {
        $eventid=$dbconnect->insert_id;
        addToLogger("A(z) ".$i.". esem�ny felv�tele sikeres. ID=".$eventid, INFO);
    }
    else { addToLogger("Hiba az adatok hozz�ad�s�ban: ". $newevent . "\n" . $dbconnect->error, ERROR); }

    for ($j = 1; $j < 3; $j++)
    {
        $prizename=$i.". tesztnyerem�ny";
        $prizedescription="A(z) ".$i." nyerem�ny le�r�sa";
        $event=$eventid;
        $prizevalue=$j*$i*100;

        $newprize="INSERT INTO prizes (prize_name, prize_description, event, prize_value) VALUES ('$prizename', '$prizedescription', '$event', '$prizevalue')";
        if ($dbconnect->query($newprize)===true)
        {
        	$prizeid=$dbconnect->insert_id;
            addToLogger("A(z) ".$j.". nyerem�ny felv�tele sikeres. ID=".$prizeid, INFO);
        }
        else { addToLogger("Hiba az adatok hozz�ad�s�ban: ". $newevent . "\n" . $dbconnect->error, ERROR); }
    }
}
writeLog();




//$toadd="INSERT INTO ticketcolors (color_name) VALUES ('kek')";


?>
