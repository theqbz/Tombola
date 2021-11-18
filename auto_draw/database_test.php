<!DOCTYPE html>
<html lang="HU-hu">
<head>
	<meta charset="utf-8" />
	<title>Ticketto DB test</title>
</head>
<body>
<?php

    require_once 'errorlogger.php';
    require_once 'db_auth_data.php';

    addToLogger("Start Ticketto AutoDraw", INFO);

    $dbconnect=new mysqli($tserver,$tdbuser,$tdbpassword,$tdbname);
    if ($dbconnect->connect_error) {
        addToLogger("Nem sikerült csatlakozni az adatbázishoz:".$dbconnect->connect_error, ERROR);
        writeLog();
        exit();
    }
    addToLogger("Adatbázis: sikeres csatlakozás.", INFO);

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

    if ($dbconnect->query($sql)===true) { addToLogger("Új rekord felvétele sikeres.", INFO); }
    else { addToLogger("Hiba az adatok hozzáadásában: ". $sql . "\n" . $dbconnect->error, ERROR); }

    $dbconnect->close();

    addToLogger("Exit AutoDraw", INFO);
    writeLog();
    echo($logtext);

?>
</body>
</html>
