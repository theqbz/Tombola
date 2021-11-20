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

    /* TICKETTO AUTODRAW
     * folyamat
     *     |SORSOLÁS|
     * 1 - Eventek tábla lekérdezése: sorsolás időpontja aktuális-e
     * 2 - Az aktuális sorsolási időponttal rendelkező eventek kigyűjtése tömbbe
     * 3 - Az aktuális sorsolási időponttal rendelkező eventekre szóló tickettek kigyűjtése egy tömbbe
     * 4 - Egyesével iterálni a tömböt és vizsgálni, hogy a hozzá tartozó nyeremények elkeltek-e
     * 4 - Szabad nyeremény esetén a ticket tömbből random választani nyertes szelvényt
     * 5 - A nyeremény táblába felvenni a nyertes szelvény ID-t
     * 6 - A ticket táblába felvenni a nyeremény ID-t
     * 7 - Email a nyertes szelvény tulajdonosának: |Nyertesek értesítése|
     * 8 - Ha van még nyeretlen szelvény, akkor folytatás a 4-es ponttal
     */


    require_once('testdata.php');

    $dbconnect->close();

    addToLogger("Exit AutoDraw", INFO);
    writeLog();
    echo($logtext);





    ?>
</body>
</html>
