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

//prize jav�t�s

for ($i = 1; $i < 61; $i++)
{
    $prizename=$i.". tesztnyerem�ny";
    $prizedescription="A(z) ".$i.". nyerem�ny le�r�sa";

    $updateprize="UPDATE prizes SET prize_name='$prizename', prize_description='$prizedescription' WHERE id='$i'";

    if ($dbconnect->query($updateprize)===true) {
        //$prizeid=$dbconnect->insert_id;
        addToLogger("A(z) ".$i.". nyerem�ny friss�t�se sikeres.", INFO);
    }
    else { addToLogger("Hiba az adatok hozz�ad�s�ban: ". $updateprize . "\n" . $dbconnect->error, ERROR); }
}



writeLog();




//$toadd="INSERT INTO ticketcolors (color_name) VALUES ('kek')";


?>