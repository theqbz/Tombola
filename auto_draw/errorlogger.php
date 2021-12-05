<?php
// Ticketto AutoDraw logger system

define("_OFF", 0);
define("_ERROR", 1);
define("_WIN", 2);
define("_INFO", 3);

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
    $separator = " -- ";
    $timestamp = date("Y-m-d H:i:s P", strtotime('+1 hour'));    // set time delay
    if ($type == 1) { $separator = " !- "; }
    if ($type == 2) { $separator = " *- "; }
    if ($mode == 1) { if ($type == 1) { $logtext.=$timestamp.$separator.$msg."\n"; } }
    if ($mode == 2) { if ($type != 3) { $logtext.=$timestamp.$separator.$msg."\n"; } }
    if ($mode == 3) { $logtext.=$timestamp.$separator.$msg."\n"; }
}

function writeLog()
{
    global $logtext;
    $success = file_put_contents('./tdrawlog.txt', $logtext, FILE_APPEND);
    echo($logtext);
}


?>
