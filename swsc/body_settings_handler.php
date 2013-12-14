<?php
include_once('security.php');
include_once('fun.php');

$LOG_TAG = 'STAFF';
$action  = $_GET['action'];

if ($action == 'lang') {
    $val = $_GET['val'];
    $settings = $_SESSION['session_settings'];

    $settingsArray = @json_decode($settings, true);
    if ($settingsArray != null) {
        $settingsArray['local'] = $val;
    } else {
        $settingsArray = array();
        $settingsArray['local'] = $val;
    }
    $settings = json_encode($settingsArray);

    $conn = conn();
    PG_ASSERT2($conn, 'db conn error!', true);

    $sql = "update user set settings='$settings' where _id=".$_SESSION['session_account_id'];
    //_exit_json(array('ret'=>false, 'info'=>$sql));
    $rs = @mysql_query($sql, $conn);
    if ($rs == TRUE) {
        $_SESSION['session_local'] = $val;
        _exit_json(array('ret'=>'true'));
    }

    _exit_json(array('ret'=>false));
}

_exit_json(array('ret'=>false, 'info'=>'action='.$action));
?>