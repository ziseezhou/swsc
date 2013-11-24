<?php
include_once('security.php');
include_once('fun.php');
include_once('config.php');
PG_ASSERT(_local_file_load('common'));

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>西南证券-北京投行二部OA系统</title>
</head>
<frameset frameborder=0 border=0 rows="120,*">
<frame scrolling=no name=banner noresize="true" marginwidth=0 marginheight=0 src="?c=main_top">
<frameset name=mainframe id=mainframe frameborder=0 border=0 cols="180,14,*">
    <frame scrolling=no name=menu noresize="true" marginwidth=0 marginheight=0 src="?c=main_left">
    <frame scrolling=no noresize="true" name=toogle marginwidth=0 marginheight=0 src="main_divider.html">
    <frame marginwidth=0 marginheight=0 name="f3" src="?c=main_body">
    <noframes>
    <body>Your browser does not handle frames!</body>
    </noframes>
</frameset>
</frameset>
</html>