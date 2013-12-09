<?php
include_once('security.php');
include_once('fun.php');
PG_ASSERT(_local_file_load('common'));

$action    = $_GET['action'];

// functions
//----------------------------------
function is_account_exist($a) {
    $conn = conn();
    PG_ASSERT2($conn, 'db conn error!', true);

    $sql = 'select count(*) as total from user where account="'.$a.'"';

    $rs = @mysql_query($sql, $conn);
    if ($rs == TRUE) {
        $rsArray = mysql_fetch_array($rs);
        if ($rsArray['total'] > 0) {
            return true;
        }
    }

    return false;
}
//----------------------------------
if ($action == 'get_list') {
    $conn = conn();
    PG_ASSERT2($conn, 'db conn error!', true);

    $sql = 'select account,real_name,email,enable,level,_id from user';
    $rs = @mysql_query($sql, $conn);
    if ($rs == TRUE) {
        $retArry = array();
        while ($row = mysql_fetch_array($rs, MYSQL_ASSOC)) {
            array_push($retArry, $row);
        }

        _exit_json(array('ret'=>'true', 'dataSet'=>$retArry));
    }

    _exit_json(array('ret'=>false));
}

else if ($action == 'add') {
    $account   = $_POST['account'];
    $real_name = $_POST['real_name'];
    $email     = $_POST['email'];
    $enable    = $_POST['enable']; 
    $level     = $_POST['level'];
    $portrait  = $_POST['portrait'];

    // check the account is or not exists
    if (is_account_exist($account)) {
        _exit_json(array('ret'=>false, 'info'=>'account_exist'));
    }

    $pwd = rand_password(6);
    $pwd_md5 = md5($pwd);
    $create_time = date('Y-m-d H:i:s');

    $sql  = 'insert into user (account, pwd, real_name, email, enable, level, create_time, portrait) values ';
    $sql .= "('$account', '$pwd_md5', '$real_name', '$email', '$enable', '$level', '$create_time', '$portrait')";

    //_exit_json(array('ret'=>false, 'info'=>$sql, 'pwd'=>$pwd));
    
    $conn = conn();
    PG_ASSERT2($conn, 'db conn error!', true);
    
    $rs = @mysql_query($sql, $conn);
    if ($rs == TRUE) {
        _exit_json(array('ret'=>true));
    }

    _exit_json(array('ret'=>false));
}

else if ($action == 'check_account') {
    $account   = $_POST['account'];

    if (is_account_exist($account)) {
        _exit_json(array('ret'=>true));
    }

    _exit_json(array('ret'=>false));
}

else if ($action == 'delete') {
    $sql = 'delete from user where _id='.$_GET['_id'];

    $conn = conn();
    PG_ASSERT2($conn, 'db conn error!', true);
    
    $rs = @mysql_query($sql, $conn);
    if ($rs == TRUE) {
        _exit_json(array('ret'=>true));
    }

    _exit_json(array('ret'=>false));
}

else if ($action == 'edit') {
    $_id = $_GET['_id'];

    $account   = $_POST['account'];
    $real_name = $_POST['real_name'];
    $email     = $_POST['email'];
    $enable    = $_POST['enable']; 
    $level     = $_POST['level'];
    $portrait  = $_POST['portrait'];

    // check account firstly
    $sql = "select account from user where _id=$_id";
    $conn = conn();
    PG_ASSERT2($conn, 'db conn error!', true);
    
    //_exit_json(array('ret'=>false, 'info'=>$sql));
    $rs = @mysql_query($sql, $conn);
    if($row = mysql_fetch_array($rs, MYSQL_NUM)) {
        if ($row[0] != $account && is_account_exist($account)) {
            _exit_json(array('ret'=>false, 'info'=>'account_exist'));
        }
    } else {
        _exit_json(array('ret'=>false, 'info'=>"fetch account error, _id=$_id"));
    }

    // update data
    // (real_name, email, enable, level, portrait);
    $sql  = ' update user set ';
    $sql .= " account = '$account',";
    $sql .= " real_name = '$real_name',";
    $sql .= " email = '$email',";
    $sql .= " enable = '$enable',";
    $sql .= " level = '$level',";
    $sql .= " portrait = '$portrait'";
    $sql .= " where _id=$_id";

    //_exit_json(array('ret'=>false, 'info'=>$sql));

    $rs = @mysql_query($sql, $conn);
    if ($rs) {
        if ($_SESSION['session_account_id'] == $_id) {
            // update the seesion values
            $_SESSION['session_account']    = $account;
            $_SESSION['session_real_name']  = $real_name;
        }

        _exit_json(array('ret'=>true));
    }

    _exit_json(array('ret'=>false));
}

else if ($action == 'reset_key') {
    $_id = $_GET['_id'];
    $type = $_GET['type'];

    $pwd = rand_password(6);
    $pwd_md5 = md5($pwd);

    $sql  = ' update user set ';
    $sql .= " pwd = '$pwd_md5'";
    $sql .= " where _id=$_id";

    $conn = conn();
    PG_ASSERT2($conn, 'db conn error!', true);
    
    $rs = @mysql_query($sql, $conn);
    if ($rs == TRUE) {
        if ($type == 'show') {
            _exit_json(array('ret'=>true, 'type'=>$type, 'pwd'=>$pwd));
        } else {
            // email the new pwd
            //_exit_json(array('ret'=>true, 'type'=>$type));
            _exit_json(array('ret'=>true, 'type'=>$type, 'pwd'=>$pwd));
        }
    }

    _exit_json(array('ret'=>false));
}

 _exit_json(array('ret'=>false, 'info'=>'action='.$action));
?>