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
        var ele_newLine = '<tr class="elem_input">\
            <td width="64"><textarea rows="1"></textarea></td>\
            <td width="64"><textarea rows="1"></textarea></td>\
            <td width="64"><textarea rows="1"></textarea></td>\
            <td width="64"><textarea rows="1"></textarea></td>\
            <td width="240"><textarea rows="1"></textarea></td>\
            <td width="64"><input type="text" name="a" maxlength="3" /></td>\
            <td width="64"><input type="text" name="a" maxlength="3" /></td>\
            <td></td>\
          </tr>';

        $('#report_new table').append(ele_newLine);
        var firstLineTag = '#report_new table tr:nth-child(3) td';
        var rowspan = $(firstLineTag).attr('rowspan');
        $(firstLineTag).attr('rowspan', (parseInt(rowspan)+1));
    }});

    $('#w_add_save_all').plbtn({click:function(){alert('w_add_save_all');}});
    $('#w_add_newline').plbtn('addIcon', 'img/icon/add.png');
    $('#w_add_save_all').plbtn('addIcon', 'img/icon/save.png');


    $('textarea').autosize();

    $('.elem_input td').each(function(){
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
            /*
            $(this).mousedown(function(e) {
                //e.stopPropagation(); // avoid triggering its parent
                //alert(e.target.type);
                //return false;
                e.stopPropagation(); // avoid triggering its parent
                e.preventDefault();
                return false;
            });
            
            $(this).mouseup(function(e) {
                e.stopPropagation(); // avoid triggering its parent
                e.preventDefault();
                return false;
                //alert(e.target.type);
                //return false;
            });
            //($this).mousedown(function(e) {
            //    //childTextArea.trigger('mouseup');
            //    alert(e.currentTarget+', '+event.relatedTarget);
            //});*/
            
            $(this).bind("contextmenu",function(e){
                if (e.target.type=='textarea') {
                    return true;
                } else {
                    childTextArea.focus();
                    //childTextArea.trigger('mousedown', {type:'mousedown', button:2});
                    //childTextArea.trigger('mouseup', {type:'mouseup', button:2});
                    //childTextArea.trigger('contextmenu', e);
                    //childTextArea.trigger({
                    //    type: 'mousedown',
                    //    which: 3
                    //});
                    //e.target.type=='textarea'
                    //childTextArea.trigger('contextmenu');
                    return false;
                }
            });
        }
    });


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
    vertical-align: top;
}
.elem_input textarea {
    resize: none;
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
            <td rowspan="2">李煜</td>
            <td width="64">博彦科技</td>
            <td width="64">非公开</td>
            <td width="64">反馈答复</td>
            <td width="64">非现场</td>
            <td width="240">针对问题进行进一步讨论</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td width="64">诚益通</td>
            <td width="64">IPO</td>
            <td width="64">补充中期材料</td>
            <td width="64">非现场</td>
            <td width="240">补充反馈恢复中7月回款情况</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td>顾形宇</td>
            <td width="64">高鸿股份</td>
            <td width="64">并购</td>
            <td width="64">尽职调查</td>
            <td width="64">高阳捷迅现场</td>
            <td width="240">现场尽职调查</td>
            <td width="64">　</td>
            <td width="64">　</td>
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
          <tr>
            <td rowspan="2">成永攀</td>
            <td width="64">德基机械</td>
            <td width="64">IPO</td>
            <td width="64">补充中报</td>
            <td width="64">北京</td>
            <td width="240">访谈客户、补充招股书</td>
            <td width="64">6</td>
            <td width="64">5</td>
          </tr>
          <tr>
            <td width="64">赛轮股份</td>
            <td width="64">非公开</td>
            <td width="64">上报材料</td>
            <td width="64">北京</td>
            <td width="240">答复反馈意见、与预审员沟通</td>
            <td width="64">　</td>
            <td width="64">5</td>
          </tr>
          <tr>
            <td>陈嘉楠</td>
            <td width="64">高鸿股份</td>
            <td width="64">并购</td>
            <td width="64">尽职调查</td>
            <td width="64">北京</td>
            <td width="240">写交易报告书</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
        </table>
        </div>
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
            <td rowspan="3"><? echo $_SESSION['real_name']; ?></td>
            <td width="64">同方股份</td>
            <td width="64">资产重组</td>
            <td width="64">封卷</td>
            <td width="64">北京</td>
            <td width="240">制作同方要求的申报材料</td>
            <td width="64">0</td>
            <td width="64">0</td>
            <td></td>
          </tr>
          <tr>
            <td width="64">德基机械</td>
            <td width="64">IPO</td>
            <td width="64">审核反馈</td>
            <td width="64">浙江宁波、辽宁阜新</td>
            <td width="240">走访德基机械客户</td>
            <td width="64">0</td>
            <td width="64">10</td>
            <td></td>
          </tr>

          <tr class="elem_input">
            <td width="64"><textarea rows="1"></textarea></td>
            <td width="64"><textarea rows="1"></textarea></td>
            <td width="64"><textarea rows="1"></textarea></td>
            <td width="64"><textarea rows="1"></textarea></td>
            <td width="240"><textarea rows="1"></textarea></td>
            <td width="64"><input type="text" name="a" maxlength="3" /></td>
            <td width="64"><input type="text" name="a" maxlength="3" /></td>
            <td></td>
          </tr>
          <!--
          <tr>
            <td width="64">德基机械</td>
            <td width="64">IPO</td>
            <td width="64">审核反馈</td>
            <td width="64">浙江宁波、辽宁阜新</td>
            <td width="240">走访德基机械客户</td>
            <td width="64">0小时</td>
            <td width="64">10小时</td>
          </tr>
          -->
        </table>
        <div id="report_new_toolbar">
            <div id="w_add_newline" class="btn_base body_toolbar_item"><?=_('btn_w_add_newline');?></div>
            <div id="w_add_save_all" class="btn_base body_toolbar_item"><?=_('btn_w_add_save_all');?></div>
        </div>
        </div>
    </div>
</body>
</html>