<?php
include_once('security.php');
include_once('fun.php');
PG_ASSERT(_local_file_load('common'));

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>西南证券-北京投行二部</title>
<link href='css/base.css' rel='stylesheet' type='text/css' />
<link href='css/btn.css' rel='stylesheet' type='text/css' />
<link href='css/tipsy.css' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery.tipsy.js"></script>
<script type="text/javascript" src="js/plbtn.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#btn_exit").plbtn({cssNormal:'btn_toolbar_normal', cssHover:'btn_toolbar_hover', cssDisabled:'btn_toolbar_normal', cssChecked:'btn_toolbar_checked'});
    $("#btn_exit").plbtn('normal');
    $("#btn_exit").click(function(){
        $.get("?c=exit", function(data){
            if (data.ret) {
                self.parent.location.reload();
            } else {
                alert('<?=_("tip_error");?>');
            }
        }, "json");
    });

});
</script>
<style type="text/css">
body{
    background: #7F2711 url(img/head_1.jpg) no-repeat right top;
}
a:link, .clickable {
    color: white;
    text-decoration: none;
    cursor: pointer;
    cursor: hand; /* stupid IE */
}
a:active {
    color: white;
    text-decoration: underline;
}
a:visited {
    color: white;
    text-decoration: none;
}
a:hover {
    color: white;
    text-decoration: underline;
}
#banner {
    height: 90px;
}
#toolbar {
    display: block;
    padding: 0 5px 0 5px;
    color: white;
    height: 30px;
    font-size: 1.25em;
    font-weight: bold;
    background: transparent url(img/head_2.jpg) repeat-x;
}
#toolbar #left{
    display: block;
    float: left;
    line-height: 30px;
}
#toolbar #right{
    display: block;
    float: right;
    margin-right: 3px;
}

</style>
</head>
<body>
<div id="banner"><img src="img/logo.jpg" /></div>
<div id="toolbar">
    <div id="left"><?=_('tip_navi_hello');?>&nbsp;<? echo $_SESSION['real_name']; ?></div>
    <div id="right">
        <div id="btn_exit" class="btn_toolbar"><?=_('navi_exit');?></div>
    </div>
</div>
</body>
</html>
