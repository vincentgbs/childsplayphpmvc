<!DOCTYPE html>
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <link rel="stylesheet" href='css/base.css'/>
    <script type="text/javascript" src='js/base.js'></script>
</head>
<?php
include_once 'config.php';

session_start();
$start = microtime(true);

if(DEBUG == 'ON') { echo "<pre>"; }
else { error_reporting(0); }
!isset($_GET['app'])?header('Location: ?app=Welcome'):null;
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