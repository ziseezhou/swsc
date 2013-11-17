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
    var onClickSave = function(_id) {
        var tr = $(this).parent().parent();

        var proName = tr.children('.id_proName').children('textarea').val();
        var proType = tr.children('.id_proType').children('textarea').val();
        var proStage = tr.children('.id_proStage').children('textarea').val();
        var workAddress = tr.children('.id_workAddress').children('textarea').val();
        var workContent = tr.children('.id_workContent').children('textarea').val();
        var extraWorktime = tr.children('.id_extraWorktime').children('input').val();
        var transDuration = tr.children('.id_transDuration').children('input').val();

        //alert(proName+proType+proStage+workAddress+workConten+extraWorktime+transDuration);
        var dataInput = {
            'id_proName': proName,
            'id_proType': proType,
            'id_proStage': proStage,
            'id_workAddress': workAddress,
            'id_workContent': workContent,
            'id_extraWorktime': extraWorktime,
            'id_transDuration': transDuration
        };

        var url = '?c=handler_weekly_report&action=add';
        if (_id.length > 0) {
            url = '?c=handler_weekly_report&action=edit&_id='+_id;
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
    };

    var onClickDelete = function() {};
    var onClickEdit = function() {};

    var setupInputLine = function (sSelector) {
        $(sSelector+' td').each(function(){
            var childInput = $(this).children('input');
            var childTextArea = $(this).children('textarea');
            if (childInput.length || childTextArea.length) {
                $(this).click(function(){
                    if (childInput.length) {
                        childInput.focus();
                    } else {
                        childTextArea.focus();
                    }
                });
            }

            if (childTextArea.length) {
                childTextArea.css('width', $(this).css('width'));

                $(this).bind("contextmenu",function(e){
                    if (e.target.type=='textarea') {
                        return true;
                    } else {
                        childTextArea.focus();
                        return false;
                    }
                });
            }
        });

        $(sSelector+' textarea').autosize();
        $(sSelector+' .w_save').plbtn({
            cssNormal:'btn_item_normal', 
            cssHover:'btn_item_hover', 
            cssDisabled:'btn_item_normal', 
            cssChecked:'btn_item_normal'});
        $(sSelector+' .w_save').plbtn('addIcon', 'img/icon/save.png');
        $(sSelector+' .w_save').click(onClickSave);

        // custom contextmenu
    };

    var ele_newLine = '<tr class="elem_input">\
        <td width="64" class="id_proName"><textarea rows="1"></textarea></td>\
        <td width="64" class="id_proType"><textarea rows="1"></textarea></td>\
        <td width="64" class="id_proStage"><textarea rows="1"></textarea></td>\
        <td width="64" class="id_workAddress"><textarea rows="1"></textarea></td>\
        <td width="240" class="id_workContent"><textarea rows="1"></textarea></td>\
        <td width="64" class="id_extraWorktime"><input type="text" name="a" value="0" maxlength="3" /></td>\
        <td width="64" class="id_transDuration"><input type="text" name="a" value="0" maxlength="3" /></td>\
        <td class="item_actions"><div class="btn_item w_save"></div></td>\
      </tr>';

    $('#w_add').plbtn({click:function(){
        $('#report_review').css('display', 'none');
        $('#report_new').css('display', 'block');
    }});
    $('#w_this').plbtn({click:function(){
        $('#report_new').css('display', 'none');
        $('#report_review').css('display', 'block');
    }});
    $('#w_last').plbtn({click:function(){alert('w_last');}});
    $('#w_query').plbtn({click:function(){alert('w_query');}});

    $('#w_add').plbtn('addIcon', 'img/icon/add.png');
    $('#w_query').tipsy({delayIn:500, fallback:"<?=_('btn_w_date_eg');?>"});

    $('#w_this').click();

    $('#w_add_newline').plbtn({click:function(){
        $('#report_new table').append(ele_newLine);
        var firstLineTag = '#report_new table tr:nth-child(3) td:first';
        var rowspan = $(firstLineTag).attr('rowspan');
        $(firstLineTag).attr('rowspan', (parseInt(rowspan)+1));

        setupInputLine('#report_new table tr:last');
    }});
    

    $('#w_add_save_all').plbtn({click:function(){alert('w_add_save_all');}});
    $('#w_add_newline').plbtn('addIcon', 'img/icon/add_item.png');
    $('#w_add_save_all').plbtn('addIcon', 'img/icon/save.png');


    $('textarea').autosize();

    $(".btn_item").each(function(){
        $(this).plbtn({cssNormal:'btn_item_normal', cssHover:'btn_item_hover', cssDisabled:'btn_item_normal', cssChecked:'btn_item_normal'});
    });
    $(".w_edit").each(function(){
        $(this).plbtn('addIcon', 'img/icon/edit_item.png');
    });
    $(".w_delete").each(function(){
        $(this).plbtn('addIcon', 'img/icon/delete_item.png');
    });


    

    // after loading exist items;
    // add a new line;
    $('#w_add_newline').click();

});
</script>
<style type="text/css">
.formContainer {
    float: left;
}
#inputDate {
    padding: 0 2px;
    height: 22px;
    width: 75px;
}
#w_query {
    margin-left: -3px;
}

.body_toolbar input[type=text], input[type=password] {
    transition: all 0.20s ease-in-out;
    -webkit-transition: all 0.20s ease-in-out;
    -moz-transition: all 0.20s ease-in-out;
    border:1px solid #C3D9FF;
    border-radius: 3px;
    outline: none;
}
.body_toolbar input[type=text]:focus,input[type=password]:focus{
    border:1px solid black;
    box-shadow:0 0 5px rgba(103,166,217,1);
    -moz-box-shadow:0 0 5px rgba(103,166,217,1);
    -webkit-box-shadow:0 0 5px rgba(103,166,217,1);
    -o-box-shadow:0 0 5px rgba(103,166,217,1);
}
#report_review, #report_new {
    display: none;
}
.tableHeader, .tableDate {
    font-weight: bold;
    border: solid 2px black;
}
#report_new input, textarea {
    width: 100%;
    font-size: 1.2em;
    color: #0000CD;
    border: 0;
}
#report_new input[type=text]:focus, textarea:focus {
    border: 0;
    outline: none;
}
#report_new input {
    height: 1.2em;
}
#report_new_toolbar {
    margin-top: 15px;
}
.elem_input {
    color: #0000CD;
}
.elem_input td {
    padding-top: 4px;
    vertical-align: top;
}
.elem_input textarea {
    resize: none;
    height: 1.2em;
}
.elem_input td .item_actions {
    padding: 0;
}
</style>
</head>
<body>
    <div class="body_navi">&bull;&nbsp;<?=_('navi_weekly_report');?></div>
    <div class="body_toolbar">
        <div id="w_this" class="btn_base body_toolbar_item"><?=_('btn_w_this');?></div>
        <div id="w_last" class="btn_base body_toolbar_item"><?=_('btn_w_last');?></div>
        <div class="formContainer">
            <form id="target" action="destination.html">
                <input type="text" id="inputDate" value="" maxlength="8" placeholder="20131001" />
            </form>
        </div>
        <div id="w_query" class="btn_base body_toolbar_item" ><?=_('btn_w_query');?></div>
        <div class="toolbar_divider"></div>
        <div id="w_add" class="btn_base body_toolbar_item"><?=_('btn_w_add');?></div>
    </div>

    <div class="workspace">

        <!-- Department weekly report list -->
        <div id="report_review">
        <table border="0" cellpadding="1" cellspacing="0">
          <col width="64" />
          <col width="64" span="4" />
          <col width="240" />
          <col width="64" span="2" />
          <tr class="tableDate">
            <td colspan="8" width="688">时间：2013年8月12日-2013年8月16日</td>
          </tr>
          <tr class="tableHeader">
            <td><?=_('table_header_Name');?></td>
            <td width="64"><?=_('table_header_proName');?></td>
            <td width="64"><?=_('table_header_proType');?></td>
            <td width="64"><?=_('table_header_proStage');?></td>
            <td width="64"><?=_('table_header_workAddress');?></td>
            <td width="240"><?=_('table_header_workContent');?></td>
            <td width="64"><?=_('table_header_extraWorktime');?></td>
            <td width="64"><?=_('table_header_transDuration');?></td>
          </tr>
          <tr>
            <td>苏磊</td>
            <td width="64">赛轮股份</td>
            <td width="64">非公开</td>
            <td width="64">答复反馈意见</td>
            <td width="64">青岛</td>
            <td width="240">补充半年报及答复反馈意见</td>
            <td width="64">0</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td>宋元</td>
            <td width="64">德基机械</td>
            <td width="64">IPO</td>
            <td width="64">补半年报</td>
            <td width="64">北京</td>
            <td width="240">更新财务数据</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td>牛志鹏</td>
            <td width="64">天和众邦</td>
            <td width="64">IPO</td>
            <td width="64">保荐阶段</td>
            <td width="64">北京</td>
            <td width="240">更新半年报及反馈意见</td>
            <td width="64">--</td>
            <td width="64">--</td>
          </tr>
          <tr>
            <td>李建功</td>
            <td width="64">高鸿股份</td>
            <td width="64">资产重组</td>
            <td width="64">尽职调查</td>
            <td width="64">北京</td>
            <td width="240">尽职调查</td>
            <td width="64">无</td>
            <td width="64">无</td>
          </tr>
          <tr>
            <td>王宜生</td>
            <td width="64">长征电气非公开项目</td>
            <td width="64">非公开</td>
            <td width="64">辅导&amp;尽职调查</td>
            <td width="64">北京</td>
            <td width="240">长征电气过往三年及一期关联交易情况核查、上次募投项目、本次募投项目内容及重大合同核对</td>
            <td width="64">0小时</td>
            <td width="64">0小时</td>
          </tr>
          <tr>
            <td rowspan="2">侯泱</td>
            <td width="64">同方股份</td>
            <td width="64">资产重组</td>
            <td width="64">封卷</td>
            <td width="64">北京</td>
            <td width="240">制作同方要求的申报材料</td>
            <td width="64">0小时</td>
            <td width="64">0小时</td>
          </tr>
          <tr>
            <td width="64">德基机械</td>
            <td width="64">IPO</td>
            <td width="64">审核反馈</td>
            <td width="64">浙江宁波、辽宁阜新</td>
            <td width="240">走访德基机械客户</td>
            <td width="64">0小时</td>
            <td width="64">10小时</td>
          </tr>
          <tr>
            <td rowspan="4">陈明星</td>
            <td width="64">山东美多</td>
            <td width="64">IPO</td>
            <td width="64">辅导</td>
            <td width="64">北京</td>
            <td width="240">撰写业务与技术章节，跟踪蓝天环科模拟合并问题</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td width="64">绿翔糖业</td>
            <td width="64">IPO</td>
            <td width="64">改制</td>
            <td width="64">北京</td>
            <td width="240">召开中介会，准备股改相关工作，督促企业尽快股改</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td width="64">长征电气</td>
            <td width="64">非公开</td>
            <td width="64">尽调</td>
            <td width="64">北京</td>
            <td width="240">尽职调查，撰写尽调报告</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td width="64">同方股份</td>
            <td width="64">重组</td>
            <td width="64">发行</td>
            <td width="64">北京</td>
            <td width="240">准备发行阶段相关文件</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
        </table>
        </div>

        <!-- Persenal weekly report: edit & add -->
        <div id="report_new">
        <table border="0" cellpadding="1" cellspacing="0">
          <col width="64" />
          <col width="64" span="4" />
          <col width="240" />
          <col width="64" span="3" />
          <tr class="tableDate">
            <td colspan="9" width="752">时间：2013年8月12日-2013年8月16日</td>
          </tr>
          <tr class="tableHeader">
            <td><?=_('table_header_Name');?></td>
            <td width="64"><?=_('table_header_proName');?></td>
            <td width="64"><?=_('table_header_proType');?></td>
            <td width="64"><?=_('table_header_proStage');?></td>
            <td width="64"><?=_('table_header_workAddress');?></td>
            <td width="240"><?=_('table_header_workContent');?></td>
            <td width="64"><?=_('table_header_extraWorktime');?></td>
            <td width="64"><?=_('table_header_transDuration');?></td>
            <td width="64"><?=_('table_header_actions');?></td>
          </tr>
          <tr>
            <td rowspan="2"><? echo $_SESSION['real_name']; ?></td>
            <td width="64">同方股份</td>
            <td width="64">资产重组</td>
            <td width="64">封卷</td>
            <td width="64">北京</td>
            <td width="240">制作同方要求的申报材料</td>
            <td width="64">0</td>
            <td width="64">0</td>
            <td class="item_actions">
                <div class="btn_item w_edit"></div>
                <div class="btn_item w_delete"></div>
            </td>
          </tr>
          <tr>
            <td width="64">德基机械</td>
            <td width="64">IPO</td>
            <td width="64">审核反馈</td>
            <td width="64">浙江宁波、辽宁阜新</td>
            <td width="240">走访德基机械客户</td>
            <td width="64">0</td>
            <td width="64">10</td>
            <td class="item_actions">
                <div class="btn_item w_edit"></div>
                <div class="btn_item w_delete"></div>
            </td>
          </tr>
        </table>
        <div id="report_new_toolbar">
            <div id="w_add_newline" class="btn_base body_toolbar_item"><?=_('btn_w_add_newline');?></div>
            <div id="w_add_save_all" class="btn_base body_toolbar_item"><?=_('btn_w_add_save_all');?></div>
        </div>
        </div>
    </div>
</body>
</html>