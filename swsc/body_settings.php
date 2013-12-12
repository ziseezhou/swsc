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

    $('#settings_btn_chglang').plbtn({click:function(){
        alert('change');
    }});
});
</script>
<style type="text/css">
body {
    margin: 0 auto;
    width: 100%;
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
    width:150px; float:left;
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
    width: 160px;
    height: 2.2em;
    padding: 0.3em 0 0.3em 0;
}
#lang #settings_btn_chglang {
    margin-left: 10px;
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
                <option value="en_rUS"><?=_('settings_title_lang_en_rUS');?></option>
                <option value="zh_rCN"><?=_('settings_title_lang_zh_rCN');?></option>
                </select>
            </div>
            <div id="settings_btn_chglang" class="btn_base"><?=_('settings_s_change');?></div>
        </div>
    </div>
    <div class="settings_item" id="pwd">
        <div class="title">
            <?=_("settings_title_pwd");?>
        </div>
        <div class="content">
        </div>
    </div>
</body>
</html>