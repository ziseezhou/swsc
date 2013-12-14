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
<script type="text/javascript" src="js/plbtn.js"></script>
<script type="text/javascript" src="js/funs.js"></script>
<script type="text/javascript" src="js/md5.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    // ===================================================================
    // Ajax Loading indicator
    
    $(document).ajaxStart(function(){
        $('#loading').show();
    }).ajaxStop(function(){
        $('#loading').hide();
    });


    // ===================================================================
    // Main

    $('#settings_btn_lang_chg').plbtn({click:function(){
        var url = '?c=body_settings_handler&action=lang&val='+$('#lang select').val();
        $.get(url, function(data){
            if (data.ret) {
                if (typeof window.parent != "undefined") {
                    top.frames.location.reload(true);
                } else {
                    document.location.reload(true);
                }
            } else {
                alert('failed');
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    }});

    $('#settings_btn_pwd_submit').plbtn({click:function(){
        
        var oldPwd = $.trim($('#pwd_old input').val());
        var newPwd = $.trim($('#pwd_new input').val());
        var newPwdAgain = $.trim($('#pwd_new_again input').val());

        var itemNull = false;
        $('#pwd input').each(function(){
            if ($.trim($(this).val()).length <= 0) {
                $.f.showToastInfo($(this).parent(), '<?=_("cannot_be_null");?>');
                itemNull = true;
            }
        });

        if (itemNull) {
            return;
        }

        if (newPwd != newPwdAgain) {
            $.f.showToastInfo($('#pwd_new_again'), '<?=_("settings_title_pwd_new_again_error");?>');
            return;
        }

        var url = '?c=body_manage_staff_handler&action=chg_key';

        var oldPwd_md5 = md5(oldPwd);
        var newPwd_md5 = md5(newPwd);

        $.post(url, {'oldPwd':oldPwd_md5, 'newPwd':newPwd_md5},
        function(data){
            if (data.ret) {
                // reset input
                $('#pwd_old input').val('');
                $('#pwd_new input').val('');
                $('#pwd_new_again input').val('');

                $.f.showToastInfo('#settings_btn_pwd_submit', '<?=_("settings_title_pwd_succeed");?>');
            } else {
                if (data.info == 'current_key_err') {
                    $.f.showToastInfo($('#pwd_old'), '<?=_("s_pwd_err");?>');
                } else {
                    alert('failed');
                }
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    }});
});
</script>
<style type="text/css">
body {
    margin: 0 auto;
}
input {
    height: 2.2em;
    line-height: 2.2em;
    width: 180px;
    border:1px solid #CCC;
    border-radius: 1px;
    outline: none;
}
input[type=text]:focus, input[type=password]:focus{
    border:1px solid #333;
    outline: none;
    -webkit-box-shadow: none !important;
    -moz-box-shadow: none !important;
    box-shadow: none !important;
}
.btn_base {
    font-size: 1.2em;
    line-height: 1.2em;
    min-width: 50px;
    width: auto !important;
    width: 50px;
    text-align: center;
}
.settings_item {
    margin: 0 auto;
    overflow: auto;
    padding: 10px 5px 10px 5px;
    background-color: #EFEFEF;
    border-top: solid 1px white;
    border-bottom: solid 1px #CCC;
    _display:inline-block;
}
.title {
    float:left;
    width:150px;
    font-weight: bold;
}
.content {
    margin-left:160px; 
}
.content div {
    float: left;
}
#lang {
    margin-top: 5px;
}
#lang select{
    width: 200px;
    height: 2.2em;
    line-height: 2.2em;
    font-size: 1.1em !important;
    font-size: 1.6em;
    padding: 0.3em 0 0.3em 0;
}
#lang #settings_btn_lang_chg {
    margin-left: 10px;
}
#pwd table {
    border: 0;
    border-collapse:collapse;
}
#pwd table td{
    border:0;
    height: 30px;
}
#pwd .item_name {
    text-align: left;
    width: 150px;
    height: 2.6em;
}
</style>
</head>
<body>
    <div class="body_navi">&bull;&nbsp;<?=_('s_settings');?><img id="loading" src="img/loading.gif" /></div>
    <div class="settings_item" id="lang">
        <div class="title">
            <?=_("settings_title_lang");?>
        </div>
        <div class="content">
            <div>
                <select>
                <option value="en_rUS" <? if ($_SESSION['session_local']=='en_rUS') echo 'selected="selected"'; ?>><?=_('settings_title_lang_en_rUS');?></option>
                <option value="zh_rCN" <? if ($_SESSION['session_local']=='zh_rCN') echo 'selected="selected"'; ?>><?=_('settings_title_lang_zh_rCN');?></option>
                </select>
            </div>
            <div id="settings_btn_lang_chg" class="btn_base"><?=_('settings_s_change');?></div>
        </div>
    </div>
    <div class="settings_item" id="pwd">
        <div class="title">
            <?=_("settings_title_pwd");?>
        </div>
        <div class="content">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item_name"><?=_('settings_title_pwd_old');?>: </td>
                    <td id="pwd_old"><input type="password" maxlength="20" /></td>
                </tr>
                <tr>
                    <td class="item_name"><?=_('settings_title_pwd_new');?>: </td>
                    <td id="pwd_new"><input type="password" maxlength="20" /></td>
                </tr>
                <tr>
                    <td class="item_name"><?=_('settings_title_pwd_new_again');?>: </td>
                    <td id="pwd_new_again"><input type="password" maxlength="20" /></td>
                </tr>
            </table>
            <div id="settings_btn_pwd_submit" class="btn_base"><?=_('settings_s_submit');?></div>
        </div>
    </div>
</body>
</html>