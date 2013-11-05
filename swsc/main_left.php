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
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
function bodyTo(id) {
    window.parent.frames["f3"].document.location='?c='+id;
}
$(document).ready(function(){
    $('#menu li a').each(function(){
        $(this).click(function(){
            bodyTo($(this).attr('id'));
        });
    });

});
</script>
<style type="text/css">
body{
    background-color:#FFF;
}

a:link, .clickable {
    color: black;
    text-decoration: none;
    cursor: pointer;
    cursor: hand; /* stupid IE */
}
a:active {
    color: black;
    text-decoration: underline;
}
a:visited {
    color: black;
    text-decoration: none;
}
a:hover {
    color: black;
    text-decoration: underline;
}

#tip {
    width: 100%;
}
#menu li {
    display: block;
    float: left;
    clear: both;
    margin:5px 0 0 5px;
    padding-left: 10px;
    background: transparent url(img/navi_item.gif) no-repeat left top;
    list-style-type: none;
    
}
#menu li span {
    display: block;
    width: 5px;
    float: left;
}
#menu li a {
    cursor: pointer;
    cursor: hand; /* stupid IE */
}

</style>
</head>
<body>
<div id="tip" class="body_navi"><?=_('tip_navi_work');?></div>
<div id="menu">
    <li>&nbsp;&nbsp;<a id="body_weekly_report" target="f3"><?=_('navi_weekly_report');?></a></li>
    <li>&nbsp;&nbsp;<a id="body_finance_report" target="f3"><?=_('navi_finance_report');?></a></li>
</div>
</body>
</html>
