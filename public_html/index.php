<!DOCTYPE html>
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- <link rel="stylesheet" href='//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css'/> -->
    <link rel="stylesheet" href='css/bootstrap.css'/>
<!-- <script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script> -->
    <script type="text/javascript" src='js/jquery.js'></script>
    <!-- <script type="text/javascript" src=''></script> -->
    <script type="text/javascript" src='js/bootstrap.js'></script>
<!-- <script type="text/javascript" src='https://code.jquery.com/ui/1.11.4/jquery-ui.min.js'></script>-->
    <script type="text/javascript" src='js/jqueryui.js'></script>
    <!-- <link rel="stylesheet" href='//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js'/> -->
    <link rel="stylesheet" href='css/jqueryui.css'/>
    <!-- <script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.js'></script> -->
    <script type="text/javascript" src='js/jquery.validate.js'></script>
</head>
<?php
require 'config.php';

session_start();
$start = microtime(true);

if(DEBUG == 'ON') { echo "<pre>"; }
else { error_reporting(0); }
!isset($_GET['app'])?header('Location: ?app='.DEFAULTAPP):null;
$function = explode("/", $_GET['app']);
foreach($function as &$word) { $word = preg_replace('/[^A-z]/', '', $word); }

$app = $function[0]. 'Controller';
try { require LOCATION . 'private/controller/' . $app . '.php'; }
catch (Exception $e) { exit; }

$controller = new $app();
if(count($function) == 1) {
    $controller->home();
} else {
    $controller->$function[1](isset($function[2])?$function[2]:null);
}

$stop = microtime(true);
if(DEBUG == 'ON') {
    echo "<p>" . ($stop - $start) . " microseconds</p>";
    echo "<p>" . memory_get_usage(true) . " bytes</p>";
    echo "</pre>";
}

exit();
?>