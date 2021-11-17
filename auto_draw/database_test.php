<!DOCTYPE html>
<html lang="HU-hu">
<head>
	<meta charset="utf-8" />
	<title>Ticketto DB test</title>
</head>
<body>

    <?php
$tserver="localhost";
$tdbname="ticketto";
$tdbuser="tdbtester";
$tdbpassword="WRb4dKaeuSQKm7Q";

$dbconnect=new mysqli($tserver,$tdbuser,$tdbpassword,$tdbname);

if ($dbconnect->connect_error) { die("Nem siker�lt a csatlakoz�s: ".$dbconnect->connect_error); }
echo "Sikeres csatlakoz�s!";

$title="Elso teszt esemeny";
$startdate=date("Y.m.d");
$drawtime=date("Y.m.d.H.i.s");
$descrption="Az elso tesztesemeny reszletes leirasa";
$ispub=true;
$autoticket=false;
$location="Szombathely";
$hash="EVENTHASH_1";

$toadd="INSERT INTO events (title, start_time, draw_time, description, is_public, auto_ticket, location, hash) VALUES ($title, $startdate, $drawdate, $description, $ispub, $autoticket, $location, $hash)";
//$add="INSERT INTO Prizes (prize_name, prize_description, event, prize_value, winner_ticket_id) VALUES ('els� teszt nyerem�ny', 'Az els� tesztnyerem�ny r�szletes le�r�sa', )"

if ($dbconnect->query($toadd)===true) { echo "�j rekord felv�tele sikeres"; }
else { echo "Hiba az adatok hozz�ad�s�ban: ". $toadd . "<br>" . $dbconnect->error; }



$dbconnect->close();

    ?>
</body>
</html>