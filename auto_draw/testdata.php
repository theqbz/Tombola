<?php
//Tesztadatok hozzáadása
$eventid=0;
$prizeid=0;

for ($i = 1; $i < 31; $i++)
{
    $title=$i.". tesztesemény";
    $startdate=date('Y-m-d H:i:s', "2021-11-20 20:00:00");
    $drawtime=date('Y-m-d H:i:s', '2021-11-20 21:00:00');
    $description='Ez a(z) '.$i.". esemeny részletes leírasa";
    $ispub=(int)'1';
    $autoticket=(int)'1';
    $location="Szombathely";
    $hash="EVENTHASH_".$i;

    $newevent="INSERT INTO events (title, start_time, draw_time, description, is_public, auto_ticket, location, hash) VALUES ('$title', '$startdate', '$drawtime', '$description', '$ispub', '$autoticket', '$location', '$hash')";
    if ($dbconnect->query($newevent)===true)
    {
        $eventid=$dbconnect->insert_id;
        addToLogger("A(z) ".$i.". esemény felvétele sikeres. ID=".$eventid, INFO);
    }
    else { addToLogger("Hiba az adatok hozzáadásában: ". $newevent . "\n" . $dbconnect->error, ERROR); }

    for ($j = 1; $j < 3; $j++)
    {
        $prizename=$i.". tesztnyeremény";
        $prizedescription="A(z) ".$i." nyeremény leírása";
        $event=$eventid;
        $prizevalue=$j*$i*100;

        $newprize="INSERT INTO prizes (prize_name, prize_description, event, prize_value) VALUES ('$prizename', '$prizedescription', '$event', '$prizevalue')";
        if ($dbconnect->query($newprize)===true)
        {
        	$prizeid=$dbconnect->insert_id;
            addToLogger("A(z) ".$j.". nyeremény felvétele sikeres. ID=".$prizeid, INFO);
        }
        else { addToLogger("Hiba az adatok hozzáadásában: ". $newevent . "\n" . $dbconnect->error, ERROR); }
    }
}




//$toadd="INSERT INTO ticketcolors (color_name) VALUES ('kek')";


?>