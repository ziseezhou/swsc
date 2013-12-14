<?php 

include_once('config.php');

// check requst is or not from mobile
function is_mobile(){
    $regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
    $regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
    $regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
    $regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
    $regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
    $regex_match.=")/i";

    return isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
}

// push 404
function push_404() {
    header('HTTP/1.1 404 Not Found');
    header("status: 404 Not Found"); 
}

// filter sql injection char
function str_remove_sql_injection($s)
{
    //$s=str_replace("\\", '&#92;', $s);
    $s = ereg_replace("'", "&#39", $s);
    $s = ereg_replace('"', "&quot;", $s);

    return $s;
}

function show_error_page($err) {
    $_SESSION['session_error'] = $err;
    include('error.php');
    exit;
}


function _local_file_load($localFileName){
    global $_PG_LOCAL;

    if (!isset($_PG_LOCAL) || !is_array($_PG_LOCAL)) {
        $_PG_LOCAL = array();
    }

    // fetch local flag
    $local = $_SESSION['session_local']; // example: zh_rCN
    if ( strlen($local)<=0) { // actually, need more check

        // again check from cookie;
        // {{

        // }}

        $local = "zh_rCN"; // default Chinese
        $_SESSION['session_local'] = $local;
    } 

    $filePath = "./local/".$local."/".$localFileName.'.txt'; // example: /local/zh_rCN/common.txt

    // according to the local session, load the matching local string file.
    $fArray = @file($filePath);
    if ($fArray==FALSE) {
        return array(-1, "Failed to open file:".$filePath);
    }

    foreach ($fArray as $lineStr) {
        $lineStr = trim($lineStr);
        if (strlen($lineStr) == 0) continue;

        $isComment = strpos($lineStr, "//");
        if (false === $isComment || 0 != $isComment) {

            $keyValue = explode("|", $lineStr, 2);

            //$_PG_LOCAL[$keyValue[0]] = $keyValue[1];
            $_PG_LOCAL[$keyValue[0]] = preg_replace("/\s/","",$keyValue[1]);
        } //else { echo $lineStr;} // debug code
    }

}

function PG_ASSERT($ret, $show_err_page=false) {
    global $_PG_LOCAL;
    global $_PG_DEBUG;

    if (isset($_PG_DEBUG) && $_PG_DEBUG && is_array($ret) && $ret[0]<0) {
        echo "Assert: ".$ret[0].", ".$ret[1];
        if ($show_err_page) {
            show_error_page($ret);
        }
    }
}

function PG_ASSERT2($ret, $tip, $show_err_page=false) {
    if (flase == $ret)
    PG_ASSERT(array(-1, $tip), $show_err_page);
}

function _($key) {
    global $_PG_LOCAL;

    if (!isset($_PG_LOCAL) || 
        !is_array($_PG_LOCAL) ||
        !array_key_exists($key, $_PG_LOCAL)) {
        return $key;
    }

    return $_PG_LOCAL[$key];
}

function _exit_json($ret) {
    if (!is_array($ret)) {
        PG_ASSERT(array(-1, "_exit_json need a array parameter"), true);
        exit;
    }

    echo json_encode($ret);

    exit;
}

function conn() {
    global $_DB_HOST, $_DB_NAME, $_DB_ACCOUNT, $_DB_PWD;

    $conn = mysql_connect($_DB_HOST, $_DB_ACCOUNT, $_DB_PWD);
    if (false == $conn) {
        return $conn;
    }
    
    if (!mysql_select_db($_DB_NAME, $conn)) {
        mysql_close($conn);
        $conn = false;
        return $conn;
    }
    
    return $conn;
}

function db_str_filter($s) {
    // mysql_real_escape_string();
    //$s=str_replace("\\", '&#92;', $s);
    $s = ereg_replace("'", "&#39", $s);
    $s = ereg_replace('"', "&quot;", $s);

    return $s;
}

function rand_password_old($length = 8){
    $randpwd = '';
    for ($i = 0; $i < $length; $i++) {
        $randpwd .= chr(mt_rand(33, 126));
    }

    return $randpwd;
}

function rand_password($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';

    $randpwd = '';
    for ( $i = 0; $i < $length; $i++ ) {
        // $randpwd .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        $randpwd .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }

    return $randpwd;
}

function ZLOG($TAG, $str) {
    $log = $TAG.':'.$str;

    $sql = 'insert into log (user_id, user_account, user_name, log) values';
    $sql.= '("'.$_SESSION['session_account_id'].'", "'.$_SESSION['session_account'].'","'.$_SESSION['session_real_name'].'", "'.$log.'")';

    $conn = conn();
    mysql_query($sql, $conn);
}

function ZLOG_INSERT() {
    $TAG  = 'NULL';
    $str  = 'INSERT:';
    $args = func_get_args(); 
    foreach ( $args as $key => $value ) {
        if ($key == 0) $TAG = $value;
        else {
            $str .= $value.',';
        }
    }

    ZLOG($TAG, $str);
}

function ZLOG_UPDATE() {
    $TAG  = 'NULL';
    $str  = 'UPDATE:';
    $args = func_get_args(); 
    foreach ( $args as $key => $value ) {
        if ($key == 0) $TAG = $value;
        else {
            $str .= $value.',';
        }
    }

    ZLOG($TAG, $str);
}

function ZLOG_DEL() {
    $TAG  = 'NULL';
    $str  = 'DEL:';
    $args = func_get_args(); 
    foreach ( $args as $key => $value ) {
        if ($key == 0) $TAG = $value;
        else {
            $str .= $value.',';
        }
    }

    ZLOG($TAG, $str);
}

?>