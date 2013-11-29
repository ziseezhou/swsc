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
        var _id = $('#staff_new table').data('account_id');

        var account   = $('#staff_new_account').children('input').val();
        var real_name = $('#staff_new_realname').children('input').val();
        var enable    = $('#staff_new_enable').children('input').val();
        var level     = $('#staff_new_level').children('select').val();
        //var portrait  = $('#staff_new_portrait').children('input').val();

        if (account.length<=0 || real_name.length<=0) {
            alert("<?=_('staff_new_username');?>"+', '+
                "<?=_('staff_new_realname');?><?=_('cannot_be_null');?>");
            return;
        }

        var dataInput = {
            'account': account,
            'real_name': real_name,
            'enable': enable,
            'level': level
        };

        var url = '?c=body_manage_staff_handler&action_handler=add';
        if (parseInt(_id)>0) {
            url = '?c=body_manage_staff_handler&action_handler=edit&_id='+_id;
        }

        $.post(url, dataInput,
        function(data){
            if (data.ret) {
                // succeed
                // show the new item 
            } else {
                //infobox.html("<?=_('login_input_error');?>");
                //infobox.show();
                // show error;
                alert(data.info);
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    }});
    $('#staff_new_btn_save').plbtn('addIcon', 'img/icon/save.png');

    $('#staff_new_btn_cancel').plbtn({click:function(){
        $('#staff_list').css('display', 'block');
        $('#staff_new').css('display', 'none');
        $('.body_toolbar').css('display', 'block');
    }});
    $('#staff_new_btn_cancel').plbtn('addIcon', 'img/icon/back.png');

    $('#staff_new_account input').blur(function(e){
        if ($(this).val().length > 0) {
            $(this).parent().children('span')
            .css('color', 'red')
            .text("<?=_('staff_new_username')._('has_been_exists');?>");
        }
    });
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
    padding-left: 10px;
    border:0;
    height: 2.5em;
}
#staff_new table td span{
    margin-left: 5px;
    display:inline;
}
#staff_new input[type=text], select{
    height: 1.5em;
    line-height: 1.5em;
    width: 160px;
    border: solid 1px #CCC;
    outline: none;
}
#staff_new .item_name {
    text-align: right;
    background-color: #EFEFEF;
}
#staff_new_toolbar {
    margin-top: 8px;
}
.float_right {
    float: right;
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
                <col width="360" />
                <tr>
                    <td width=64 class="item_name"><?=_('staff_new_username');?>: </td>
                    <td id="staff_new_account"><input type="text" maxlength="20" /><span></span></td>
                </tr>
                <tr>
                    <td width=64 class="item_name"><?=_('staff_new_realname');?>: </td>
                    <td id="staff_new_realname"><input type="text" maxlength="20" /></td>
                </tr>
                <tr>
                    <td width=64 class="item_name"><?=_('staff_new_status');?>: </td>
                    <td id="staff_new_enable">
                        <input type="radio" name="enable_status" value="0" checked>&nbsp;<?=_('staff_status_disabled');?>
                        <input type="radio" name="enable_status" value="1">&nbsp;<?=_('staff_status_enabled');?><br>
                    </td>
                </tr>
                <tr>
                    <td width=64 class="item_name"><?=_('staff_new_level');?>: </td>
                    <td id="staff_new_level">
                        <select>
                        <option value="1"><?=_('staff_level_normal');?></option>
                        <option value="10"><?=_('staff_level_admin');?></option>
                        </select> 
                    </td>
                </tr>
                <!--
                <tr>
                    <td width=64 class="item_name"><?=_('staff_new_portrait');?>: </td>
                    <td id="staff_new_portrait"><input type="file" name="portrait" /> </td>
                </tr>-->
            </table>
            <div id="staff_new_toolbar">
            <div id="staff_new_btn_save" class="btn_base body_toolbar_item"><?=_('s_save');?></div>
            <div id="staff_new_btn_cancel" class="btn_base body_toolbar_item float_right"><?=_('s_cancel');?></div>
            </div>
        </div>
    </div>
</body>
</html>