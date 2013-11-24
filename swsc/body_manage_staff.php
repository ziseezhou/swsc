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
<script type="text/javascript" src="js/jquery.autosize.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#staff_add').plbtn({click:function(){
        $('#staff_list').css('display', 'none');
        $('#staff_new').css('display', 'block');
        $('.body_toolbar').css('display', 'none');
    }});
    $('#staff_add').plbtn('addIcon', 'img/icon/add.png');

    $('#staff_new_btn_save').plbtn({click:function(){
        alert('save');
    }});
    $('#staff_new_btn_save').plbtn('addIcon', 'img/icon/save.png');

    $('#staff_new_btn_cancel').plbtn({click:function(){
        $('#staff_list').css('display', 'block');
        $('#staff_new').css('display', 'none');
        $('.body_toolbar').css('display', 'block');
    }});
    $('#staff_new_btn_cancel').plbtn('addIcon', 'img/icon/back.png');
});
</script>
<style type="text/css">
#staff_new {
    display: none;
}
#staff_new table {
    border: 0;
    border-bottom: solid 1px #CCC;
    border-collapse:collapse;
}
#staff_new table td{
    border:0;
}
#staff_new .item_name {
    text-align: right;
}
#staff_new_toolbar {
    margin-top: 8px;
}
</style>
</head>
<body>
    <div class="body_navi">&bull;&nbsp;<?=_('navi_manage_staff');?></div>
    <div class="body_toolbar">
        <div id="staff_add" class="btn_base body_toolbar_item"><?=_('s_add');?></div>
    </div>

    <div class="workspace">

        <!-- Department weekly report list -->
        <div id="staff_list">
            list
        </div>

        <!-- Persenal weekly report: edit & add -->
        <div id="staff_new">
            <table border="0" cellpadding="0" cellspacing="0">
                <col width="64" />
                <col width="240" />
                <tr>
                    <td width=64 class="item_name"><?=_('staff_new_username');?>: </td>
                    <td></td>
                </tr>
                <tr>
                    <td width=64 class="item_name"><?=_('staff_new_realname');?>: </td>
                    <td></td>
                </tr>
                <tr>
                    <td width=64 class="item_name"><?=_('staff_new_status');?>: </td>
                    <td></td>
                </tr>
                <tr>
                    <td width=64 class="item_name"><?=_('staff_new_level');?>: </td>
                    <td></td>
                </tr>
                <tr>
                    <td width=64 class="item_name"><?=_('staff_new_portrait');?>: </td>
                    <td></td>
                </tr>
            </table>
            <div id="staff_new_toolbar">
            <div id="staff_new_btn_save" class="btn_base body_toolbar_item"><?=_('s_save');?></div>
            <div id="staff_new_btn_cancel" class="btn_base body_toolbar_item"><?=_('s_cancel');?></div>
            </div>
        </div>
    </div>
</body>
</html>