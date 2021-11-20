<?php
// Ticketto AutoDraw logger system

define("ERROR",1);
define("INFO",2);

$logtext="\n";
$separator=" - ";

function addToLogger($msg, $type)
{
    global $logtext, $separator;
    if ($type==1) { $separator=" -! "; }
    else if ($type==2) { $separator=" - "; }
    $logtext.=date('Y.m.d H:i:s P').$separator.$msg."\n";
}

function writeLog()
{
    global $logtext;
    $success=file_put_contents('./tdrawlog.txt',$logtext,FILE_APPEND);
    echo($logtext);
}


?>