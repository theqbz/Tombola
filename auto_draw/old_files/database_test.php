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

    $wasDraw = false;
    $wasError = false;

    echo ("Pontos idő: ".date('Y-m-d H:i:s', time())."<br>");

    srand();
    $sqlEvents = "SELECT `id`, `draw_time`
        FROM `events`
        WHERE `flag`<> 3
        AND `draw_time` < NOW();";
    $toDraw = $dbconnect->query($sqlEvents);

    if ($toDraw->num_rows > 0) {
        while ($row = $toDraw->fetch_assoc()) {
            $drawableEvents[]=$row;
        }

        /*
        echo ("drawableEvents: <pre>");
        var_dump($drawableEvents);
        echo ("</pre><br>");
         */

        foreach ($drawableEvents as $event) {

            /*
            echo("<br>Esemény: ");
            var_dump($event);
             */

            $eventId = intval($event["id"]);
            $sqlPrizes = "SELECT `id`, `event`
                FROM `prizes`
                WHERE `event` = '$eventId'
                AND `winner_ticket_id` IS NULL;";

            $eventsPrizes = $dbconnect->query($sqlPrizes);

            if ($eventsPrizes->num_rows > 0) {
                while ($row = $eventsPrizes->fetch_assoc()) {
                    $drawablePrizes[]=$row;
                }
                
                /*
                echo("<br>EventId: ");
                var_dump($eventId);
                echo ("<br>drawablePrizes: <pre>");
                var_dump($drawablePrizes);
                echo ("</pre><br>");
                 */

                foreach ($drawablePrizes as $prize) {

                    /*
                    echo("<br>    Nyeremény: ");
                    var_dump($prize);
                     */

                    $sqlTickets = "SELECT `tickets`.`id`, `userevents`.`user`
                        FROM `tickets`, `userevents`
                        WHERE `userevents`.`event` = '$eventId'
                        AND `tickets`.`userevents` = `userevents`.`id`
                        AND `tickets`.`won_prize_id` IS NULL;";

                    $eventsTickets = $dbconnect->query($sqlTickets);

                    if ($eventsTickets->num_rows > 0) {
                        while ($row = $eventsTickets->fetch_assoc()) {
                            $drawableTickets[]=$row;
                        }
                        $prizeId = intval($prize["id"]);


                        /*
                        echo("<br>PrizeID: ");
                        var_dump($prizeId);
                        echo ("<br>drawableTickets: <pre>");
                        var_dump($drawableTickets);
                        echo ("</pre><br>");
                         */

                        $toWin = rand(0, (count($drawableTickets)));
                        $winnerTicket = intval($drawableTickets[$toWin]["id"]);
                        $winnerUser = intval($drawableTickets[$toWin]["user"]);

                        $addPrizeToTicket = "UPDATE `tickets` SET `won_prize_id`='$prizeId' WHERE `id`='$winnerTicket'";
                        if ($dbconnect->query($addPrizeToTicket)===true) {
                            addToLogger("A(z) ".$winnerTicket." Id-jű tickethez a ".$prizeId." Id-jú nyeremény hozzáadva.", INFO); }
                        else { addToLogger("Hiba a szelvény frissítésekor: ".$addPrizeToTicket."\n".$dbconnect->error, ERROR); }

                        $addTicketToPrize = "UPDATE `prizes` SET `winner_ticket_id`='$winnerTicket' WHERE `id`='$prizeId'";
                        if ($dbconnect->query($addTicketToPrize)===true) {
                            addToLogger("A(z) ".$prizeId." Id-jű nyereményhez a ".$winnerTicket." Id-jú ticket hozzáadva.", INFO); }
                        else { addToLogger("Hiba a nyeremény frissítésekor: ".$addTicketToPrize."\n".$dbconnect->error, ERROR); }

                        $setEventDrawed = "UPDATE `events` SET `flag` = '3' WHERE `id`='$eventId';";
                        if ($dbconnect->query($setEventDrawed)===true) {
                            addToLogger("A(z) ".$eventId." Id-jú esemény átállítva SORSOLVA állapotra", INFO); }
                        else { addToLogger("Hiba az esemény frissítésekor: ".$addTicketToPrize."\n".$dbconnect->error, ERROR); }

                        addToLogger("Sorsolás: Esemény ID=".$eventId."; Nyeremény ID=".$prizeId."; Ticket ID=".$winnerTicket, INFO);

                        /*
                        echo ("<br>ToWin: ");
                        var_dump($toWin);
                        echo ("<br>winnerTicket ID: ");
                        var_dump($winnerTicket);
                        echo ("<br>winnerUser ID: ");
                        var_dump($winnerUser);
                         */
                    }
                    //echo("<br>UNSET: drawableTickets");
                    unset($drawableTickets);
                }
                echo("<br>Nincs több szabad nyeremény erre az eseményre");
            }
            //echo("<br>UNSET: drawablePrizes");
            unset($drawablePrizes);
        }
        echo("<br>Nincs több sorsolásra váró esemény<br><br>");
    }
    

    $dbconnect->close();

    addToLogger("Exit AutoDraw", INFO);
    writeLog();
    //echo($logtext);


    ?>
</body>
</html>
