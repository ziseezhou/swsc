<?php
if (!isset($watchdog)) {
    header('HTTP/1.1 404 Not Found');
    header("status: 404 Not Found");
    include('404.php');
    exit;
}

if (!isset($_SESSION['session_account']))
{
    include('login.php');
    exit;
}
?>