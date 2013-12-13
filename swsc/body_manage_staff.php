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
<link href='css/floatBox.css' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery.tipsy.js"></script>
<script type="text/javascript" src="js/jquery.autosize.min.js"></script>
<script type="text/javascript" src="js/funs.js"></script>
<script type="text/javascript" src="js/plbtn.js"></script>
<script type="text/javascript">

function eventReceiver(e) {
    $(document).trigger(e.type, e);
}



$(document).ready(function(){
    // ===================================================================
    // Ajax Loading indicator
    
    $(document).ajaxStart(function(){
        $('#loading').show();
    }).ajaxStop(function(){
        $('#loading').hide();
    });

    // ===================================================================
    // Constants

    var listItemHeader = '\
        <tr>\
            <td width="64"><?=_("staff_new_username");?></td>\
            <td width="64"><?=_("staff_new_realname");?></td>\
            <td width="256"><?=_("s_email");?></td>\
            <td width="64"><?=_("staff_new_status");?></td>\
            <td width="64"><?=_("staff_new_level");?></td>\
            <td width="176" class="action"><?=_("table_header_actions");?></td>\
        </tr>';

    var listItemContainer = '\
        <tr>\
            <td width="64"></td>\
            <td width="64"></td>\
            <td width="256"></td>\
            <td width="64"></td>\
            <td width="64"></td>\
            <td width="176" class="action">\
                <div class="btn_item btn_delete"></div>\
                <div class="btn_item btn_edit"></div>\
                <div class="btn_item btn_keyreset"></div>\
            </td>\
        </tr>';

    // ===================================================================
    // Functions
    var showKeyResetRet =
    function(elem, pwd) {
        var floatId = 'float_id_reset_user_key_ret';
        var view = $('<div class="reset_key_ret"><?=_("staff_key_new");?>:<span class="pwd">'+pwd+'</span></div>');
        var dialog = $.f.floatDialogGet(floatId);
        var options ={borderSize: 2, gravity: 'w'};

        $.f.floatDialogInflate(dialog, view);
        $.f.floatDialogAssemble(elem, dialog, options);
    };

    var showKeyReset = 
    function(elem, value, action) {
        var floatId = 'float_id_reset_user_key';
        var view = $('<div></div>').html('\
                <div class="dilag_title"><span><?=_("staff_key_reset");?></span></div>\
                <div class="dilag_info">\
                  <span><?=_("staff_new_username");?>:'+value['account']+'</span><br/>\
                  <span><?=_("staff_new_realname");?>:'+value['real_name']+'</span>\
                </div>\
                <div class="dilag_buttons">\
                  <div id="reset_back_show" class="btn_base btn_normal btn_dialog_left" ><?=_("staff_key_reset_back_show");?></div>\
                  <div id="reset_back_email" class="btn_base btn_normal btn_dialog_right" ><?=_("staff_key_reset_back_email");?></div>\
                </div>');

        var dialog = $.f.floatDialogGet(floatId);
        $.f.floatDialogInflate(dialog, view);

        var btnShow = dialog.find('#reset_back_show');
        var btnEmail = dialog.find('#reset_back_email');

        btnShow
            .plbtn({})
            .plbtn('addIcon', 'img/icon/display.png')
            .click(function(e){
                action(value['_id'], 'show', elem);
                $(document).trigger('mouseup', e);
            });
        btnEmail
            .plbtn({})
            .plbtn('addIcon', 'img/icon/mail.png')
            .click(function(e){
                action(value['_id'], 'mail', elem);
                $(document).trigger('mouseup', e);
            });

        var options ={borderSize: 2, gravity: 'w'};
        $.f.floatDialogAssemble(elem, dialog, options);
    };

    var actionEdit = 
    function(value) {
        $('#staff_new_account input').val(value['account']);
        $('#staff_new_realname input').val(value['real_name']);
        $('#staff_new_email input').val(value['email']);
        $("#staff_new_enable input:radio").filter('[value='+value['enable']+']').prop('checked', true);
        $('#staff_new_level select').val(value['level']);
        $('#staff_new_btn_save').data('_id', value['_id']).data('account', value['account']);

        // show
        $('#staff_new_ret').css('display', 'none');
        $('#staff_list').css('display', 'none');
        $('#staff_new').css('display', 'block');
        $('#body_toolbar_list').css('display', 'block');
        $('#body_toolbar_add').css('display', 'none');
    };


    var actionSave =
    function() {
        $('#staff_new_ret').css('display', 'none');

        var _id = $('#staff_new_btn_save').data('_id');

        var account   = $.trim($('#staff_new_account input').val());
        var realName  = $.trim($('#staff_new_realname input').val());
        var email     = $.trim($('#staff_new_email input').val());
        var enable    = $("#staff_new_enable input:checked").val();
        var level     = $('#staff_new_level select').val();
        //var portrait  = $('#staff_new_portrait').children('input').val();

        if (account.length<=0 || realName.length<=0) {
            alert("<?=_('staff_new_username');?>"+', '+
                "<?=_('staff_new_realname');?><?=_('cannot_be_null');?>");
            return;
        }

        if (email.length<=0 || !$.f.validateEmail(email)) {
            alert("<?=_('s_email_err');?>");
            return;
        }

        var dataInput = {
            'account': account,
            'real_name': realName,
            'email': email,
            'enable': enable,
            'level': level
        };

        var isEdit = false;
        var url = '?c=body_manage_staff_handler&action=add';
        if (parseInt(_id)>0) {
            isEdit = true;
            url = '?c=body_manage_staff_handler&action=edit&_id='+_id;
        }

        $.post(url, dataInput,
        function(data){
            if (data.ret) {
                // succeed
                $('#staff_new_ret')
                    .text(account+", <?=_('staff_save_succeed');?>")
                    .css({'display':'block', 'color':'green'});

                // need clear form?
                if (!isEdit) removeEditForm();
                else {
                    // edit self date, update UI
                    window.parent.frames["banner"].document.location.reload(true);
                }
            } else {
                var infoTip = "<?=_('staff_save_failed');?>";
                if ('account_exist'==data.info) {
                    infoTip = "<?=_('staff_save_failed');?>"+','+"<?=_('staff_new_username')._('has_been_exists');?>"
                } else if (data.info != null){
                    infoTip = data.info;
                }

                $('#staff_new_ret')
                    .text(infoTip)
                    .css({'display':'block', 'color':'red'});
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    };

    var actionPreDelete =
    function(elem, _id) {
        $.f.showDeleteConfirm(elem, '<?=_("s_delete");?>', _id, actionDelete);
    };


    var actionDelete = 
    function(_id, elem) {
        //if (!window.confirm("<?=_('s_delete_confirm');?>")) {
        //    return;
        //}

        var url = '?c=body_manage_staff_handler&action=delete&_id='+_id;
        $.get(url, function(data){
            if (data.ret) {
                //loadUserList();
                var tr = $(elem).parent().parent();
                tr.remove();
            } else {
                alert('failed');
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    };


    var actionResetKey = 
    function(_id, type, elem) {
        var url = '?c=body_manage_staff_handler&action=reset_key&_id='+_id+'&type='+type;
        $.get(url, function(data){
            if (data.ret) {
                if (data.type == 'show') {
                    showKeyResetRet(elem, data.pwd);
                }
            } else {
                alert('failed');
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    };


    var loadUserList = 
    function() {
        $.get('?c=body_manage_staff_handler&action=get_list', function(data){
            if (data.ret) {
                var tBody = $('#staff_list table tbody');
                tBody.empty();
                tBody.append(listItemHeader);
                tBody.children('tr:first').css('font-weight', 'bold');
                tBody.children('tr:first').children('td:last').css('text-align', 'right');

                $.each(data.dataSet, function(index, value) {
                    tBody.append(listItemContainer);
                    var newLine = tBody.children('tr:last');
                    var newTdSet = newLine.children('td');
                    newTdSet.eq(0).html(value['account']);
                    newTdSet.eq(1).html(value['real_name']);
                    newTdSet.eq(2).html(value['email']);
                    newTdSet.eq(3).html(value['enable']=='0' ? "<?=_('staff_status_disabled');?>" : "<?=_('staff_status_enabled');?>");
                    newTdSet.eq(4).html(value['level']=='10'? "<?=_('staff_level_admin');?>" : "<?=_('staff_level_normal');?>");

                    if (value[3]=='0') {
                        newLine.css('background-color', '#EEE');
                    }else if (value[4]=='10') {
                        newLine.css('background-color', '#FFE6E6');
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
                        .click(function() {
                            actionEdit(value);
                        });
                    $("#staff_list table tr:last td .btn_delete")
                        .plbtn('addIcon', 'img/icon/delete_item.png')
                        .click(function() {
                            actionPreDelete(this, value['_id']);
                        });
                    $("#staff_list table tr:last td .btn_keyreset")
                        .plbtn('addIcon', 'img/icon/key_reset.png')
                        .click(function() {
                            showKeyReset(this, value, actionResetKey);
                        });
                        // .tipsy({delayIn:500, fallback:"<?=_('staff_key_reset');?>"})
                });
            } else {
                alert('failed');
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    };


    var removeEditForm =
    function() {
        $('#staff_new_account input').val('');
        $('#staff_new_realname input').val('');
        $('#staff_new_email input').val('');
        $("#staff_new_enable input:radio").filter('[value=1]').prop('checked', true);
        $('#staff_new_level select').val(1);
        $('#staff_new_btn_save').removeData();
        $('#staff_new_account span').text('');
    };

    var accountInputOnBlur =
    function(e) {
        var domSpan = $('#staff_new_account span');
        var accounVal = $.trim($('#staff_new_account input').val());

        if (accounVal.length <= 0 || 
            accounVal == $('#staff_new_btn_save').data('account')) {
            domSpan.text("");
            return;
        }

        var url = '?c=body_manage_staff_handler&action=check_account';

        $.post(url, {'account':accounVal},
        function(data){
            if (data.ret) {
                domSpan.css('color', 'red')
                .text("<?=_('staff_new_username')._('has_been_exists');?>");
            } else {
                domSpan.css('color', 'green')
                .text("<?=_('staff_new_username')._('available');?>");
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    };

    // ===================================================================
    // Main

    // Btn add
    $('#staff_btn_add').plbtn({click:function(){
        $('#staff_list').css('display', 'none');
        $('#staff_new').css('display', 'block');
        $('#body_toolbar_list').css('display', 'block');
        $('#body_toolbar_add').css('display', 'none');
        $('#staff_new_ret').css('display', 'none');

        removeEditForm();
    }}).plbtn('addIcon', 'img/icon/add.png');

    // Btn save
    $('#staff_new_btn_save').plbtn({click:function(){
        actionSave();
    }}).plbtn('addIcon', 'img/icon/save.png');

    // Btn list
    $('#staff_btn_list').plbtn({click:function(){
        $('#staff_list').css('display', 'block');
        $('#staff_new').css('display', 'none');
        $('#body_toolbar_list').css('display', 'none');
        $('#body_toolbar_add').css('display', 'block');
        loadUserList();
    }}).plbtn('addIcon', 'img/icon/back.png');

    // Account validation
    $('#staff_new_account input').blur(function(e){
        accountInputOnBlur(e);
    });


    // init
    loadUserList();
});
</script>
<style type="text/css">
#body_toolbar_list {
    display: none;
}
#staff_list table{
    border: 0;
    border-collapse:collapse;
}
#staff_list table td {
    border: 0;
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
#staff_new #staff_new_account input,
#staff_new #staff_new_realname input,
#staff_new #staff_new_email input{
    height: 2.2em;
    line-height: 2.2em;
    width: 160px;
    border:1px solid #CCC;
    border-radius: 1px;
    outline: none;
}
#staff_new input:focus{
    border:1px solid #333;
    outline: none;
    -webkit-box-shadow: none !important;
    -moz-box-shadow: none !important;
    box-shadow: none !important;
}
#staff_new .item_name {
    text-align: left;
    width: 90px;
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
.reset_key_ret {
    margin: 3px;
    padding: 6px 0 6px 0;
}
.reset_key_ret .pwd {
    margin-left: 2px;
    padding: 5px 8px 5px 8px;
    color: black;
    font-weight: bold;
    border: solid 1px #ADCD3C;
    background-color: #F2FDDB;
}
</style>
</head>
<body>
    <div class="body_navi">&bull;&nbsp;<?=_('navi_manage_staff');?><img id="loading" src="img/loading.gif" /></div>
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
                <tbody></tbody>
            </table>
        </div>

        <!-- Persenal weekly report: edit & add -->
        <div id="staff_new">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item_name"><?=_('staff_new_username');?>: </td>
                    <td id="staff_new_account"><input type="text" maxlength="20" /><span></span></td>
                </tr>
                <tr>
                    <td class="item_name"><?=_('staff_new_realname');?>: </td>
                    <td id="staff_new_realname"><input type="text" maxlength="20" /></td>
                </tr>
                <tr>
                    <td class="item_name"><?=_('s_email');?>: </td>
                    <td id="staff_new_email"><input type="text" maxlength="100" /></td>
                </tr>
                <tr>
                    <td class="item_name"><?=_('staff_new_status');?>: </td>
                    <td id="staff_new_enable">
                        <input type="radio" name="enable_status" value="0" />&nbsp;<?=_('staff_status_disabled');?>
                        <input type="radio" name="enable_status" value="1" checked />&nbsp;<?=_('staff_status_enabled');?><br>
                    </td>
                </tr>
                <tr>
                    <td class="item_name"><?=_('staff_new_level');?>: </td>
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