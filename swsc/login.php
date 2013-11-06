<?php 
include_once('security.php');
include_once('fun.php');
PG_ASSERT(_local_file_load('common'));

$account    = $_POST['a'];
$pwd        = $_POST['p'];

$account    = db_str_filter($account);
$pwd        = db_str_filter($pwd);

if (strlen($account) > 0) {
    $sql = "select * from user where account='$account'";
    
    $conn = conn();
    PG_ASSERT2($conn, "db conn error!", true);
    
    $rs = @mysql_query($sql, $conn);
    PG_ASSERT2($rs, "db query error!", true);

    if (mysql_num_rows($rs)>0) {
        $row = mysql_fetch_array($rs);
        $sqlPwd = $row['pwd'];
        
        if ($sqlPwd == $pwd) {
            $_SESSION['account'] = $account;
            $_SESSION['real_name'] = $row['real_name'];
            _exit_json(array('ret'=>true, 'account'=>$account));
        }
    }

    _exit_json(array('ret'=>false));
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>西南证券-北京投行二部OA系统</title>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">                                         
$(document).ready(function(){
    $("#loginForm").submit(function(event){
        event.preventDefault();

        var form    = $(this);
        var account = form.find('input[name="a"]').val();
        var pwd     = form.find('input[name="p"]').val();
        var url     = form.attr('action');
        var infobox = $('#info');

        if (account.length==0 || pwd.length==0) {
            //alert("<?=_('login_input_null');?>");
            infobox.html("<?=_('login_input_null');?>");
            infobox.show();
            return;
        }

        $.post(url, {a:account, p:pwd},
        function(data){
            if (data.ret) {
                // sign in successfully
                // cookie the username
                location.reload(true);
            } else {
                infobox.html("<?=_('login_input_error');?>");
                infobox.show();
            }
        }, "json");
    });

    // cookie out username if exist
    $("#a input").focus();
});
</script>
<style type="text/css">
* {
    padding:0;
    margin:0;
}
html {
    overflow-y:scroll;
    font-size: 63%;
}
body {
    margin:0;
    padding:45px 0 0 0;
    font-family: arial, sans-serif;
    font-size: 1.2em;
    background: #8E1D23 url(img/login_left_top.jpg) no-repeat left top;
}
ol,ul {
    list-style:none;
}
li{
    float:left;
    clear:both;
    display:block;
    height: 40px;
}
form {
    position: absolute;
    top: 50%;
    left:50%;
    height: 136px;
    margin-top: -60px;
    width: 160px;
    margin-left: -80px;
}
#container {
    position: absolute;
    bottom: 0;
    left:0;
    width: 316px;
    height: 325px;
    background: transparent url(img/login_left_bottom.jpg) no-repeat left bottom;
}
#loginBox {
    position: absolute;
    top: 50%;
    left:50%;
    height: 360px;
    margin-top: -180px;
    width: 560px;
    margin-left: -280px;
}
#a input, #p input {
    padding: 3px;
    height: 22px;
    width: 155px;
    line-height: 22px;
    font-size: 12px;
    vertical-align:middle;
    background-color: white;
    border: solid 1px #000;
}
#s input {
    height:30px;
    width: 74px;
    border: none;
    color: #8E1D23;
    font-size: 14sp;
    font-weight: bold;
    background: transparent url(img/login_btn.jpg) no-repeat;
}
#info {
    display:none;
    padding: 3px;
    width: 155px;
    text-align: left;
    color: red;
    background-color:#FFFFDD;
    border: solid 1px #E3E197;
}
#title {
    font-size: 14px;
    font-weight:bold;
    text-align: center;
    height: 118px; 
    line-height: 35px; 
    background-image: url('img/login_logo.jpg');
    background-repeat: no-repeat;
    background-color: transparent;
    background-position: center top;
}
input[type=text], input[type=password] {
    transition: all 0.20s ease-in-out;
    -webkit-transition: all 0.20s ease-in-out;
    -moz-transition: all 0.20s ease-in-out;
    border:1px solid #C3D9FF;
    border-radius: 3px;
    outline: none;
}
input[type=text]:focus,input[type=password]:focus,textarea:focus{
    box-shadow:0 0 5px rgba(103,166,217,1);
    -moz-box-shadow:0 0 5px rgba(103,166,217,1);
    -webkit-box-shadow:0 0 5px rgba(103,166,217,1);
    -o-box-shadow:0 0 5px rgba(103,166,217,1);
}
</style>
</head>
<body>
<div id="container"></div>
<div id="loginBox">
<div id="title">&nbsp;</div>
<form action="./" id="loginForm">
<ul>
<li id="a"><input type="text" name="a" maxlength="30" placeholder="<?=_('login_name');?>" /></li>
<li id="p"><input type="password" name="p"  maxlength="30" placeholder="<?=_('login_pwd');?>" /></li>
<li id="s"><input type="submit" value="<?=_('login');?>" /></li>
<li id="info"></li>
</ul>
</form>
</div>
</body>
</html>