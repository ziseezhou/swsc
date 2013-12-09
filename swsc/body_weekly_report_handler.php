<?php
include_once('security.php');
include_once('fun.php');
PG_ASSERT(_local_file_load('common'));



$action    = $_GET['action'];

// Get data
if ($action == 'get_list') {
    $proName       = $_POST['id_proName'];
    $proType       = $_POST['id_proType'];
    $proStage      = $_POST['id_proStage']; 
    $workAddress   = $_POST['id_workAddress'];
    $workContent   = $_POST['id_workContent'];
    $extraWorktime = $_POST['id_extraWorktime'];
    $transDuration = $_POST['id_transDuration'];

    $type = $_GET['type'];
    $time = $_GET['time'];
    $sql  = 'select * from weekly_report where ';
    
    // compose sql
    if ($time == 'thisWeek') {
        $sql .= ' YEARWEEK(date_format(report_date, \'%Y-%m-%d\')) = YEARWEEK(now())';
    } else if ($time == 'lastWeek') {
        $sql .= ' YEARWEEK(date_format(report_date, \'%Y-%m-%d\')) = YEARWEEK(now())-1';
    } else if (date_create_from_format('Y-m-d', $time) != FALSE) {
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

    $sql .= ' order by _id_user';

    // debug
    //_exit_json(array('ret'=>false, 'info'=>$sql));
    // sleep(3); // debug for loading indicator

    $conn = conn();
    PG_ASSERT2($conn, 'db conn error!', true);

    // query
    $rs = @mysql_query($sql, $conn);
    if ($rs == TRUE) {
        $retArry = array();
        $cacheOnce = false;
        $cacheNames = array();
        while ($row = mysql_fetch_array($rs, MYSQL_ASSOC)) {
            if (!$cacheOnce) {
                $sqlCache = 'select _id, real_name from user';
                $rsCache  = @mysql_query($sqlCache, $conn);
                while ($rowCache = mysql_fetch_array($rsCache, MYSQL_ASSOC)) {
                    $cacheNames[$rowCache['_id']] = $rowCache['real_name'];
                }

                $cacheOnce = true;
                //_exit_json(array('ret'=>'false', 'dataSet'=>$cacheNames));
            }

            $row['real_name'] = $cacheNames[$row['_id_user']];
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
    $proName       = $_POST['id_proName'];
    $proType       = $_POST['id_proType'];
    $proStage      = $_POST['id_proStage']; 
    $workAddress   = $_POST['id_workAddress'];
    $workContent   = $_POST['id_workContent'];
    $extraWorktime = $_POST['id_extraWorktime'];
    $transDuration = $_POST['id_transDuration'];

    $time = $_GET['time'];
    $create_time = date('Y-m-d H:i:s');

    //if (date_create_from_format('Y-m-d', $time) == FALSE) {
    if (date('Y-m-d', strtotime($time)) != $time) {
        $time = $create_time;
    }

    $sql = 'insert into weekly_report (_id_user, pro_name, pro_type, pro_stage, work_address, work_content, extra_worktime, trans_duration, report_date, report_create_date, report_update_date) values ';
    $sql.= "(".$_SESSION['session_account_id'].", '$proName', '$proType', '$proStage', '$workAddress', '$workContent', $extraWorktime, $transDuration, '$time', '$create_time', '$create_time')";

    //_exit_json(array('ret'=>false, 'info'=>$sql));
    
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