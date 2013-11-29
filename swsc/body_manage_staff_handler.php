<?php
include_once('security.php');
include_once('fun.php');
PG_ASSERT(_local_file_load('common'));

$action    = $_GET['action'];

if ($action == 'add') {
    $account   = $_POST['account'];
    $real_name = $_POST['real_name'];
    $enable    = $_POST['enable']; 
    $level     = $_POST['level'];
    $portrait  = $_POST['portrait'];

    $pwd = rand_password(6);
    $pwd_md5 = md5($pwd);
    $create_time = date('Y-m-d H:i:s');

    $sql  = 'insert into user ';
    $sql .= '(account, pwd, real_name, enable, level, create_time, portrait) values (';
    $sql .= "'".$account.', ';
    $sql .= "'".$pwd_md5."', ";
    $sql .= "'".$real_name."', ";
    $sql .= "'".$enable."', ";
    $sql .= "'".$level."', ";
    $sql .= "'".$create_time."', ";
    $sql .= "'".$portrait."', ";
    $sql .= $extraWorktime.', ';
    $sql .= $transDuration;
    $sql .= ')';

    _exit_json(array('ret'=>false, 'info'=>$sql, 'pwd'=>$pwd));
    exit;
    
    $conn = conn();
    PG_ASSERT2($conn, 'db conn error!', true);
    
    $rs = @mysql_query($sql, $conn);
    if ($rs == TRUE) {
        _exit_json(array('ret'=>true));
    }

    _exit_json(array('ret'=>false));
}


?>