<?php
include_once('security.php');
include_once('fun.php');
PG_ASSERT(_local_file_load('common'));

$proName = $_POST['id_proName'];
$proType = $_POST['id_proType'];
$proStage = $_POST['id_proStage']; 
$workAddress = $_POST['id_workAddress'];
$workContent = $_POST['id_workContent'];
$extraWorktime = $_POST['id_extraWorktime'];
$transDuration = $_POST['id_transDuration'];

$action    = $_GET['action'];

if (strlen($account) > 0) {
    $sql = "select * from user where account='$account'";
    $sql = "insert into weekly_report ";
    $sql.= "(_id_user, pro_name, pro_type, pro_stage, work_address, work_content, extra_worktime, trans_duration) values (";
    $sql.= $_SESSION['account_id'].", ";
    $sql.= "'".$proName."', ";
    $sql.= "'".$proType."', ";
    $sql.= "'".$proStage."', ";
    $sql.= "'".$workAddress."', ";
    $sql.= "'".$workContent."', ";
    $sql.= $extraWorktime.", ";
    $sql.= $transDuration;
    $sql.= ")";

    //_exit_json(array('ret'=>false, 'info'=>$sql));
    //exit;
    
    $conn = conn();
    PG_ASSERT2($conn, "db conn error!", true);
    
    $rs = @mysql_query($sql, $conn);
    if ($rs == TRUE) {
        _exit_json(array('ret'=>true));
    }

    _exit_json(array('ret'=>false));
}


?>