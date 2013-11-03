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
<link href='css/base.css' rel='stylesheet' type='text/css' />
<link href='css/btn.css' rel='stylesheet' type='text/css' />
<link href='css/tipsy.css' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery.tipsy.js"></script>
<script type="text/javascript" src="js/plbtn.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#w_add").plbtn({click:function(){alert('w_add');}});
    $("#w_this").plbtn({click:function(){alert('w_this');}});
    $("#w_last").plbtn({click:function(){alert('w_last');}});
    $("#w_query").plbtn({click:function(){alert('w_query');}});

    //$("#w_add").plbtn('addIcon', 'img/icon/w_add.png');
});
</script>
<style type="text/css">
</style>
</head>
<body>
    <div class="body_navi">&diams;&nbsp;<?=_('navi_weekly_report');?></div>
    <div class="body_toolbar">
        <div id="w_add" class="btn_base body_toolbar_item"><?=_('btn_w_add');?></div>
        <div id="w_this" class="btn_base body_toolbar_item"><?=_('btn_w_this');?></div>
        <div id="w_last" class="btn_base body_toolbar_item"><?=_('btn_w_last');?></div>
        <div id="w_query" class="btn_base body_toolbar_item"><?=_('btn_w_query');?></div>
        <div class="btn_base">
            <span style="display:block; float=left; width=16px; height=16px; background=red">icon</span>试试这个</div>
    </div>
</body>
</html>