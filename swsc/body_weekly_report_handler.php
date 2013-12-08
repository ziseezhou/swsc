<?php
include_once('security.php');
include_once('fun.php');
PG_ASSERT(_local_file_load('common'));

$proName       = $_POST['id_proName'];
$proType       = $_POST['id_proType'];
$proStage      = $_POST['id_proStage']; 
$workAddress   = $_POST['id_workAddress'];
$workContent   = $_POST['id_workContent'];
$extraWorktime = $_POST['id_extraWorktime'];
$transDuration = $_POST['id_transDuration'];

$action    = $_GET['action'];

// Get data
if ($action == 'get_list') {
    $type = $_GET['type'];
    $time = $_GET['time'];
    $sql  = 'select * from weekly_report where ';
    
    // compose sql
    if ($time == 'thisWeek') {
        $sql .= ' YEARWEEK(date_format(report_date, \'%Y-%m-%d\')) = YEARWEEK(now())';
    } else if ($time == 'lastWeek') {
        $sql .= ' YEARWEEK(date_format(report_date, \'%Y-%m-%d\')) = YEARWEEK(now())-1';
    } else if (date_create_from_format('Y-m-d', $time)!= FALSE) {
        $sql .= ' YEARWEEK(date_format(report_date, \'%Y-%m-%d\')) = YEARWEEK('.$time.')';
    } else {
        _exit_json(array('ret'=>false, 'info'=>'Paramenter error: invalid weekly report type'));
    }

    if ($type == 'all') {
        ;
    } else if($type == 'personal') {
        $sql .= ' and _id_user='.$_SESSION['session_account_id'];
    } else {
        _exit_json(array('ret'=>false, 'info'=>'Paramenter error: invalid weekly report type'));
    }

    // debug
    //_exit_json(array('ret'=>false, 'info'=>$sql));

    $conn = conn();
    PG_ASSERT2($conn, 'db conn error!', true);

    // query
    $rs = @mysql_query($sql, $conn);
    if ($rs == TRUE) {
        $retArry = array();
        while ($row = mysql_fetch_array($rs, MYSQL_ASSOC)) {
            array_push($retArry, $row);
        }

        _exit_json(array('ret'=>'true', 'dataSet'=>$retArry));
    }

    // create json data
    //_exit_json(array('ret'=>false, 'info'=>'Query error, sql='+$sql));
    _exit_json(array('ret'=>false, 'info'=>'Query error, sql='.$sql));
}

// Add data
else if ($action == 'add') {
    $sql = 'insert into weekly_report ';
    $sql.= '(_id_user, pro_name, pro_type, pro_stage, work_address, work_content, extra_worktime, trans_duration) values (';
    $sql.= $_SESSION['session_account_id'].', ';
    $sql.= "'".$proName."', ";
    $sql.= "'".$proType."', ";
    $sql.= "'".$proStage."', ";
    $sql.= "'".$workAddress."', ";
    $sql.= "'".$workContent."', ";
    $sql.= $extraWorktime.', ';
    $sql.= $transDuration;
    $sql.= ')';

    //_exit_json(array('ret'=>false, 'info'=>$sql));
    //exit;
    
    $conn = conn();
    PG_ASSERT2($conn, 'db conn error!', true);
    
    $rs = @mysql_query($sql, $conn);
    if ($rs == TRUE) {
        _exit_json(array('ret'=>true));
    }

    _exit_json(array('ret'=>false));
}

// Edit data
else if ($action == 'edit') {
    ;
}

// Delete data
else if ($action == 'delete') {
    ;
}



?>