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
    var listItemHeader = '\
        <tr>\
            <td width="128"><?=_("staff_new_username");?></td>\
            <td width="128"><?=_("staff_new_realname");?></td>\
            <td width="128"><?=_("staff_new_status");?></td>\
            <td width="128"><?=_("staff_new_level");?></td>\
            <td width="176" class="action"><?=_("table_header_actions");?></td>\
        </tr>'
    var listItemContainer = '\
        <tr>\
            <td width="128"></td>\
            <td width="128"></td>\
            <td width="128"></td>\
            <td width="128"></td>\
            <td width="176" class="action">\
                <div class="btn_item btn_delete"></div>\
                <div class="btn_item btn_edit"></div>\
                <div class="btn_item btn_keyreset"></div>\
            </td>\
        </tr>';
    var actionEdit = function(_id) {alert(_id);};
    var actionDelete = function(_id) {
        if (!window.confirm("<?=_('S_delete_confirm');?>")) {
            return;
        }

        var url = '?c=body_manage_staff_handler&action=delete&_id='+_id;
        $.get(url, function(data){
            if (data.ret) {
                loadUserList();
            } else {
                alert('failed');
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    };
    var actionResetKey = function(_id) {alert(_id);};

    var loadUserList = function() {
        $.get('?c=body_manage_staff_handler&action=getlist', function(data){
            if (data.ret) {
                $('#staff_list table').empty();
                $('#staff_list table').append(listItemHeader);
                $('#staff_list table tr:first').css('font-weight', 'bold');
                $('#staff_list table tr:first td:last').css('text-align', 'right');
                $.each(data.dataSet, function(index, value) {
                    $('#staff_list table').append(listItemContainer);
                    var _id = value[4];
                    var newLine = $('#staff_list table tr:last td');
                    newLine.eq(0).html(value[0]);
                    newLine.eq(1).html(value[1]);
                    newLine.eq(2).html(value[2]=='0' ? "<?=_('staff_status_disabled');?>" : "<?=_('staff_status_enabled');?>");
                    newLine.eq(3).html(value[3]=='10'? "<?=_('staff_level_admin');?>" : "<?=_('staff_level_normal');?>");

                    if (value[3]=='10') {
                        $('#staff_list table tr:last').css('background-color', '#FFE6E6');
                    }

                    $("#staff_list table tr:last td .btn_item")
                    .css('float', 'right')
                    .plbtn({
                        cssNormal:'btn_item_normal', 
                        cssHover:'btn_item_hover', 
                        cssDisabled:'btn_item_normal', 
                        cssChecked:'btn_item_normal'});
                    $("#staff_list table tr:last td .btn_edit")
                        .plbtn('addIcon', 'img/icon/edit_item.png')
                        .click(function() {actionEdit(_id);});
                    $("#staff_list table tr:last td .btn_delete")
                        .plbtn('addIcon', 'img/icon/delete_item.png')
                        .click(function() {actionDelete(_id);});
                    $("#staff_list table tr:last td .btn_keyreset")
                        .plbtn('addIcon', 'img/icon/key_reset.png')
                        .tipsy({delayIn:500, fallback:"<?=_('staff_key_reset');?>"})
                        .click(function() {actionResetKey(_id);});
                });
            } else {
                alert('failed');
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    };

    $('#staff_btn_add').plbtn({click:function(){
        $('#staff_list').css('display', 'none');
        $('#staff_new').css('display', 'block');
        $('#body_toolbar_list').css('display', 'block');
        $('#body_toolbar_add').css('display', 'none');
    }});
    $('#staff_btn_add').plbtn('addIcon', 'img/icon/add.png');

    $('#staff_new_btn_save').plbtn({click:function(){
        var _id = $('#staff_new table').data('account_id');

        var account   = $('#staff_new_account').children('input').val();
        var realName = $('#staff_new_realname').children('input').val();
        var enable    = $('#staff_new_enable').children('input').val();
        var level     = $('#staff_new_level').children('select').val();
        //var portrait  = $('#staff_new_portrait').children('input').val();

        if (account.length<=0 || realName.length<=0) {
            alert("<?=_('staff_new_username');?>"+', '+
                "<?=_('staff_new_realname');?><?=_('cannot_be_null');?>");
            return;
        }

        var dataInput = {
            'account': account,
            'real_name': realName,
            'enable': enable,
            'level': level
        };

        var url = '?c=body_manage_staff_handler&action=add';
        if (parseInt(_id)>0) {
            url = '?c=body_manage_staff_handler&action=edit&_id='+_id;
        }

        $.post(url, dataInput,
        function(data){
            if (data.ret) {
                // succeed
                $('#staff_new_ret')
                    .text(account+' '+"<?=_('staff_ret_succeed');?>")
                    .css({'display':'block', 'color':'green'});

                // 
                //accountObj.val('');
                //realNameObj.val('');
                //enableObj.get(1).checked = true;
                //levelObj.val(1);
            } else {
                if ('account_exist'==data.info) {
                    $('#staff_new_ret')
                    .text(account+' '+<?=_('staff_ret_failed');?>+' '+"<?=_('staff_new_username')._('has_been_exists');?>")
                    .css({'display':'block', 'color':'red'});
                } else {
                    $('#staff_new_ret')
                    .text(account+' '+"<?=_('staff_ret_failed');?>")
                    .css({'display':'block', 'color':'red'});
                }
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    }});
    $('#staff_new_btn_save').plbtn('addIcon', 'img/icon/save.png');

    $('#staff_btn_list').plbtn({click:function(){
        $('#staff_list').css('display', 'block');
        $('#staff_new').css('display', 'none');
        $('#body_toolbar_list').css('display', 'none');
        $('#body_toolbar_add').css('display', 'block');
        loadUserList();
    }});
    $('#staff_btn_list').plbtn('addIcon', 'img/icon/back.png');

    $('#staff_new_account input').blur(function(e){
        if ($(this).val().length <= 0) {
            $(this).parent().children('span').text("");
            return;
        }

        var url = '?c=body_manage_staff_handler&action=check_account';
        var account = $(this).val();

        $.post(url, {'account':account},
        function(data){
            if (data.ret) {
                $('#staff_new_account').children('span')
                .css('color', 'red')
                .text("<?=_('staff_new_username')._('has_been_exists');?>");
            } else {
                $('#staff_new_account').children('span')
                .css('color', 'green')
                .text("<?=_('staff_new_username')._('available');?>");
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    });


    // init
    loadUserList();
});
</script>
<style type="text/css">
#body_toolbar_list {
    display: none;
}
#staff_list table, td {
    border: 0;
}
#staff_list table tr{
    border-bottom: solid 1px #AAA;
}
#staff_new {
    display: none;
}
#staff_new .tableHeader{
    font-weight: bold;
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
#staff_new_ret {
    margin-top: 5px;
    display:none;
    padding: 3px;
    text-align: left;
    color: red;
    background-color:#FFFFDD;
    border: solid 1px #E3E197;
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
        <div id="body_toolbar_list">
            <div id="staff_btn_list" class="btn_base body_toolbar_item"><?=_('s_return_to_list');?></div>
        </div>
        <div id="body_toolbar_add">
            <div id="staff_btn_add" class="btn_base body_toolbar_item"><?=_('s_add');?></div>
        </div>
    </div>

    <div class="workspace">

        <!-- Department weekly report list -->
        <div id="staff_list">
            <table border="0" cellpadding="0" cellspacing="0">
            </table>
        </div>

        <!-- Persenal weekly report: edit & add -->
        <div id="staff_new">
            <table border="0" cellpadding="0" cellspacing="0">
                <col width="64" />
                <col width="624" />
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
                        <input type="radio" name="enable_status" value="0">&nbsp;<?=_('staff_status_disabled');?>
                        <input type="radio" name="enable_status" value="1" checked>&nbsp;<?=_('staff_status_enabled');?><br>
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
            <div id="staff_new_ret"></div>
            <div id="staff_new_toolbar">
            <div id="staff_new_btn_save" class="btn_base body_toolbar_item"><?=_('s_save');?></div>
            </div>
        </div>
    </div>
</body>
</html>