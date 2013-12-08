<?php
include_once('security.php');
include_once('fun.php');

unset($_SESSION['session_account']);

_exit_json(array('ret'=>true));
?>