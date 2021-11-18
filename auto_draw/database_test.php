<!DOCTYPE html>
<html lang="HU-hu">
<head>
	<meta charset="utf-8" />
	<title>Ticketto DB test</title>
</head>
<body>

    <?php
require_once 'db_auth_data.php';

$dbconnect=new mysqli($tserver,$tdbuser,$tdbpassword,$tdbname);

if ($dbconnect->connect_error) { die("Nem siker�lt a csatlakoz�s: ".$dbconnect->connect_error); }
echo "Sikeres csatlakozás!<br>";

$title="Ennek sikerülnie kellene elvileg";
$startdate=date('Y-m-d H:m:s');
$drawtime=date('Y-m-d H:m:s');
$description="Az első tesztesemeny reszletes leírasa";
$ispub=(int)'0';
$autoticket=(int)'0';
$location="Szombathely";
$hash="EVENTHASH_1";

//$toadd="INSERT INTO ticketcolors (color_name) VALUES ('kek')";
$sql="INSERT INTO events (title, start_time, draw_time, description, is_public, auto_ticket, location, hash) VALUES ('$title', '$startdate', '$drawtime', '$description', '$ispub', '$autoticket', '$location', '$hash')";

if ($dbconnect->query($sql)===true) { echo "Új rekord felvétele sikeres"; }
else { echo "Hiba az adatok hozzáadásában: ". $sql . "<br>" . $dbconnect->error; }



$dbconnect->close();

    ?>
</body>
</html>
