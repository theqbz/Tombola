<?php
// Ticketto AutoDraw logger system

define("_OFF", 0);
define("_ERROR", 1);
define("_INFO", 2);

$logtext = "\n";
$mode = 2;

function setLoggerMode($type)
{
    global $mode;
    $mode = $type;
}

function addToLogger($msg, $type)
{
    global $logtext, $mode;
    $separator = " - ";
    if ($type == 1) { $separator = " -! "; }
    if ($mode == 1) { if ($type == 1) { $logtext.=date('Y.m.d H:i:s P').$separator.$msg."\n"; } }
    if ($mode == 2) { $logtext.=date('Y.m.d H:i:s P').$separator.$msg."\n"; }
}

function writeLog()
{
    global $logtext;
    $success = file_put_contents('./tdrawlog.txt', $logtext, FILE_APPEND);
    echo($logtext);
}


?>
