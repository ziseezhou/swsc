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
<link href="css/glDatePicker.default.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery.tipsy.js"></script>
<script type="text/javascript" src="js/jquery.autosize.min.js"></script>
<script type="text/javascript" src="js/jquery.glDatePicker.min.new.js"></script>
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

    var T_ALL = 'all';
    var T_PER = 'personal';
    var TIME_THIS_WEEK = 'thisWeek';
    var TIME_LAST_WEEK = 'lastWeek';

    var ele_AllListLine = '<tr>\
            <td width="64"></td>\
            <td width="64"></td>\
            <td width="64"></td>\
            <td width="64"></td>\
            <td width="240"></td>\
            <td width="64"></td>\
            <td width="64"></td>\
          </tr>';

    var ele_newLine = '<tr class="elem_input">\
        <td width="64" class="id_proName"><textarea rows="1"></textarea></td>\
        <td width="64" class="id_proType"><textarea rows="1"></textarea></td>\
        <td width="64" class="id_proStage"><textarea rows="1"></textarea></td>\
        <td width="64" class="id_workAddress"><textarea rows="1"></textarea></td>\
        <td width="240" class="id_workContent"><textarea rows="1"></textarea></td>\
        <td width="64" class="id_extraWorktime"><input type="text" value="0" maxlength="3" /></td>\
        <td width="64" class="id_transDuration"><input type="text" value="0" maxlength="3" /></td>\
        <td class="item_actions"><div class="btn_item w_save"></div></td>\
      </tr>';


    // ===================================================================
    // Functions

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

        var url = '?c=body_weekly_report_handler&action=add';
        if (typeof _id!='undefined' && _id.length > 0) {
            url = '?c=body_weekly_report_handler&action=edit&_id='+_id;
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

    var emptyTableList = function(type) {
        var tableId = (type==T_ALL) ? '#report_list table tbody' : '#report_new table tbody';
        $(tableId).children('tr').each(function(){
            if (!$(this).hasClass('tableHeader') && !$(this).hasClass('tableDate')){
                $(this).remove();
            }
        });
    };

    var loadingList = function(type, time) {
        if (typeof time == 'undefined') {
            time = TIME_THIS_WEEK;
        }

        $.get('?c=body_weekly_report_handler&action=get_list&type='+type+'&time='+time, function(data){
            if (data.ret) {
                emptyTableList(type);
                assembleList(type, data.dataSet);
            }
            else {
                alert('failed');
            }
        }, "json")
        .fail(function(){
            alert('failed');
        });
    };

    var assembleList = function(type, data) {
        var tableDivId = 'report_list';
        var lastLineId = '#report_list table tbody tr:last';
        var newViewStr = ele_AllListLine;

        if (type == T_PER) {
            tableDivId = 'report_new';
            lastLineId = '#report_new table tbody tr:last';
            newViewStr = ele_newLine;
        } 

        {
            //var lastLineId = '#report_list table tbody tr:last';
            var latestNameLine = $(lastLineId);
            var indexOfLastNameLine = 1;
            var indexOfTdSet = 0;

            $.each(data, function(index, value) {
                var i = latestNameLine.data('_id_user');
                if (latestNameLine.data('_id_user') != value['_id_user']) {
                    var newLine = $(newViewStr).prepend('<td rowspan="1" style="color:black;">'+value['real_name']+'</td>')
                    newLine.insertAfter(lastLineId);
                    latestNameLine = $(lastLineId);
                    latestNameLine.data('_id_user', value['_id_user']);
                    indexOfTdSet = 1;
                    indexOfLastNameLine = index + 2;
                } else {
                    var tdRowSpan = document
                        .getElementById(tableDivId)
                        .getElementsByTagName('table')[0]
                        .getElementsByTagName('tr')[indexOfLastNameLine]
                        .getElementsByTagName('td')[0];
                    rowspan = tdRowSpan.rowSpan;
                    tdRowSpan.rowSpan = rowspan+1;
                    indexOfTdSet = 0;

                    $(newViewStr).insertAfter(lastLineId);
                }

                // set datas
                var newTdSet = $(lastLineId).children('td');

                newTdSet.eq(indexOfTdSet).html(value['pro_name']);
                newTdSet.eq(indexOfTdSet+1).html(value['pro_type']);
                newTdSet.eq(indexOfTdSet+2).html(value['pro_stage']);
                newTdSet.eq(indexOfTdSet+3).html(value['work_address']);
                newTdSet.eq(indexOfTdSet+4).html(value['work_content']);
                newTdSet.eq(indexOfTdSet+5).html(value['extra_worktime']);
                newTdSet.eq(indexOfTdSet+6).html(value['trans_duration']);

                if (type == T_PER) {
                    //setupInputLine();
                } 
            });
            
            if (type == T_PER) {
                $('#w_add_newline').click();
            } 
        }
    };

    
    // ===================================================================
    // Main

    // Btn list
    $('#w_list').plbtn({click:function(){
        $('#report_list').css('display', 'block');
        $('#report_new').css('display', 'none');
        $('#body_toolbar_list').css('display', 'block');
        $('#body_toolbar_add').css('display', 'none');
        $(window).resize(); // resize glDatePicker

        // loading list
        loadingList(T_ALL);
    }}).plbtn('addIcon', 'img/icon/list.png');

    // Btn this week
    $('#w_this').plbtn({click:function(){
        loadingList(T_ALL, TIME_THIS_WEEK);
    }});

    // Btn last week 
    $('#w_last').plbtn({click:function(){
        loadingList(T_ALL, TIME_LAST_WEEK);
    }});


    

    // Btn add
    $('#w_add').plbtn({click:function(){
        $('#report_list').css('display', 'none');
        $('#report_new').css('display', 'block');
        $('#body_toolbar_list').css('display', 'none');
        $('#body_toolbar_add').css('display', 'block');
        $(window).resize(); // resize glDatePicker

        // loading personal list
        loadingList(T_PER);

        
    }}).plbtn('addIcon', 'img/icon/add.png');

    
    // Btn personal, Add new line
    $('#w_add_newline').plbtn({click:function(){
        var lastLineId = '#report_new table tr:last';
        var rowspan = $('#report_new table tr:nth-child(3) td:first').attr('rowspan');

        try {
            var tdRowSpan = document
                .getElementById('report_new')
                .getElementsByTagName('table')[0]
                .getElementsByTagName('tr')[2]
                .getElementsByTagName('td')[0];
            rowspan = tdRowSpan.rowSpan;
            tdRowSpan.rowSpan = rowspan+1;
        }catch(e){}

        if (typeof rowspan == 'undefined') {
            var firstInputLine = $(ele_newLine).prepend('<td rowspan="1"><?=$_SESSION["session_real_name"];?></td>');
            firstInputLine.insertAfter(lastLineId);
        } else {
            $(ele_newLine).insertAfter(lastLineId);
        }

        setupInputLine(lastLineId);
    }}).plbtn('addIcon', 'img/icon/add_item.png');
    
    // Btn personal, Save all lines
    $('#w_add_save_all').plbtn({click:function(){
        alert('w_add_save_all');
    }}).plbtn('addIcon', 'img/icon/save.png');


    // Loading .... {{{
    //$('textarea').autosize();

    $(".btn_item").each(function(){
        $(this).plbtn({cssNormal:'btn_item_normal', cssHover:'btn_item_hover', cssDisabled:'btn_item_normal', cssChecked:'btn_item_normal'});
    });
    $(".w_edit").each(function(){
        $(this).plbtn('addIcon', 'img/icon/edit_item.png');
    });
    $(".w_delete").each(function(){
        $(this).plbtn('addIcon', 'img/icon/delete_item.png');
    });

    // }}}



    // Workaround for IE6 date picker
    var dataPickerBorderSize = 1;
    if ($.f.isIE6()) {
        dataPickerBorderSize = 0;
    }

    // Init datapicker 1
    var datePicker = $('#w_query').glDatePicker({
        width:330, 
        height:280,
        borderSize: dataPickerBorderSize,
        dowNames: <?=_('s_dow_names');?>,
        monthNames: <?=_('s_month_names');?>,
        onClick: function(el, cell, date, data) {
            var time = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
            loadingList(T_ALL, time);
        }
    });

    // Btn query from date
    $('#w_query').plbtn({click:function(){
        datePicker.show();
    }}).plbtn('addIcon', 'img/icon/calendar.png');

    
    // Init datapicker 2
    var datePicker2 = $('#report_new_date_btn').glDatePicker({
        width:330, 
        height:280,
        borderSize: dataPickerBorderSize,
        dowNames: <?=_('s_dow_names');?>,
        monthNames: <?=_('s_month_names');?>,
        onClick: function(el, cell, date, data) {
            var time = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
            loadingList(T_PER, time);
        }
    });
    
    // Btn personal, query from date
    $('#report_new_date_btn').plbtn({click:function(){
        datePicker2.show();
    }}).plbtn('addIcon', 'img/icon/calendar.png');


    // Init
    // after loading exist items;
    // add a new line;
    // 
    $('#w_list').click();

});
</script>
<style type="text/css">
#report_list, #report_new {
    display: none;
}
.tableHeader {
    font-weight: bold;
    border-bottom: solid 2px black;
}
.tableDate {
    font-weight: bold;
    border-top: solid 2px black;
    border-bottom: solid 2px black;
    height: 2.5em;
}
.tableDate div {
    float:left;
}
.tableDate #report_new_date {
    line-height: 2.2em;
}
.tableDate #report_new_date_btn {
    margin-left: 5px;
}
#report_new input, textarea {
    width: 100%;
    font-size: 1.2em;
    color: #0000CD;
    border: 0;
    outline: none;
}
#report_new input[type=text]:focus, textarea:focus {
    border: 0;
    outline: none;
    box-shadow:0 0 0 0;
    -moz-box-shadow:0 0 0 0;
    -webkit-box-shadow:0 0 0 0;
    -o-box-shadow:0 0 0 0;
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
#workspace table {
    border: solid 1px black;
    border-collapse:collapse;
}
#workspace table td{
    border: solid 1px black;
}
</style>
</head>
<body>
    <div class="body_navi">&bull;&nbsp;<?=_('navi_weekly_report');?><img id="loading" src="img/loading.gif" /></div>
    <div class="body_toolbar">
        <div id="body_toolbar_list">
        <div id="w_this" class="btn_base body_toolbar_item"><?=_('btn_w_this');?></div>
        <div id="w_last" class="btn_base body_toolbar_item"><?=_('btn_w_last');?></div>
        <div id="w_query" class="btn_base body_toolbar_item" ><?=_('btn_w_date');?></div>
        <div class="toolbar_divider"></div>
        <div id="w_add" class="btn_base body_toolbar_item"><?=_('btn_w_add');?></div>
        </div>
        <div id="body_toolbar_add">
            <div id="w_list" class="btn_base body_toolbar_item"><?=_('btn_w_list');?></div>
        </div>
    </div>

    <div class="workspace">

        <!-- Department weekly report list -->
        <div id="report_list">
        <table border="0" cellpadding="1" cellspacing="0">
          <col width="64" />
          <col width="64" span="4" />
          <col width="240" />
          <col width="64" span="2" />
          <tbody>
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
          </tbody>
        </table>
        </div>

        <!-- Persenal weekly report: edit & add -->
        <div id="report_new">
        <table border="0" cellpadding="1" cellspacing="0">
          <col width="64" />
          <col width="64" span="4" />
          <col width="240" />
          <col width="64" span="3" />
          <tbody>
          <tr class="tableDate">
            <td colspan="9" width="752">
                <div id="report_new_date">时间：2013年8月12日-2013年8月16日</div>
                <div id="report_new_date_btn" class="btn_base body_toolbar_item"><?=_('btn_w_date_else');?></div>
            </td>
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
          <tbody>
        </table>
        <div id="report_new_toolbar">
            <div id="w_add_newline" class="btn_base body_toolbar_item"><?=_('btn_w_add_newline');?></div>
            <!--
            <div id="w_add_save_all" class="btn_base body_toolbar_item"><?=_('btn_w_add_save_all');?></div>
            -->
        </div>
        </div>
    </div>
</body>
</html>