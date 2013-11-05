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

    $("#w_add").plbtn('addIcon', 'img/icon/add.png');
    $("#w_query").tipsy({delayIn:500, fallback:"<?=_('btn_w_date_eg');?>"});
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

input[type=text], input[type=password] {
    transition: all 0.20s ease-in-out;
    -webkit-transition: all 0.20s ease-in-out;
    -moz-transition: all 0.20s ease-in-out;
    border:1px solid #C3D9FF;
    border-radius: 3px;
    outline: none;
}
input[type=text]:focus,input[type=password]:focus,textarea:focus{
    border:1px solid black;
    box-shadow:0 0 5px rgba(103,166,217,1);
    -moz-box-shadow:0 0 5px rgba(103,166,217,1);
    -webkit-box-shadow:0 0 5px rgba(103,166,217,1);
    -o-box-shadow:0 0 5px rgba(103,166,217,1);
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
        <table border="0" cellpadding="1" cellspacing="0">
          <col width="65" />
          <col width="64" span="4" />
          <col width="241" />
          <col width="64" span="2" />
          <tr>
            <td colspan="5" width="321">时间：2013年8月12日-2013年8月16日</td>
            <td width="241"></td>
            <td width="64"></td>
            <td width="64"></td>
          </tr>
          <tr>
            <td>　</td>
            <td width="64">项目名称</td>
            <td width="64">项目类型</td>
            <td width="64">项目阶段</td>
            <td width="64">工作地点</td>
            <td width="241">工作内容</td>
            <td width="64">加班时长</td>
            <td width="64">行程时长</td>
          </tr>
          <tr>
            <td>苏磊</td>
            <td width="64">赛轮股份</td>
            <td width="64">非公开</td>
            <td width="64">答复反馈意见</td>
            <td width="64">青岛</td>
            <td width="241">补充半年报及答复反馈意见</td>
            <td width="64">0</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td>宋元</td>
            <td width="64">德基机械</td>
            <td width="64">IPO</td>
            <td width="64">补半年报</td>
            <td width="64">北京</td>
            <td width="241">更新财务数据</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td>牛志鹏</td>
            <td width="64">天和众邦</td>
            <td width="64">IPO</td>
            <td width="64">保荐阶段</td>
            <td width="64">北京</td>
            <td width="241">更新半年报及反馈意见</td>
            <td width="64">--</td>
            <td width="64">--</td>
          </tr>
          <tr>
            <td>李建功</td>
            <td width="64">高鸿股份</td>
            <td width="64">资产重组</td>
            <td width="64">尽职调查</td>
            <td width="64">北京</td>
            <td width="241">尽职调查</td>
            <td width="64">无</td>
            <td width="64">无</td>
          </tr>
          <tr>
            <td>王宜生</td>
            <td width="64">长征电气非公开项目</td>
            <td width="64">非公开</td>
            <td width="64">辅导&amp;尽职调查</td>
            <td width="64">北京</td>
            <td width="241">长征电气过往三年及一期关联交易情况核查、上次募投项目、本次募投项目内容及重大合同核对</td>
            <td width="64">0小时</td>
            <td width="64">0小时</td>
          </tr>
          <tr>
            <td rowspan="2">侯泱</td>
            <td width="64">同方股份</td>
            <td width="64">资产重组</td>
            <td width="64">封卷</td>
            <td width="64">北京</td>
            <td width="241">制作同方要求的申报材料</td>
            <td width="64">0小时</td>
            <td width="64">0小时</td>
          </tr>
          <tr>
            <td width="64">德基机械</td>
            <td width="64">IPO</td>
            <td width="64">审核反馈</td>
            <td width="64">浙江宁波、辽宁阜新</td>
            <td width="241">走访德基机械客户</td>
            <td width="64">0小时</td>
            <td width="64">10小时</td>
          </tr>
          <tr>
            <td rowspan="2">李煜</td>
            <td width="64">博彦科技</td>
            <td width="64">非公开</td>
            <td width="64">反馈答复</td>
            <td width="64">非现场</td>
            <td width="241">针对问题进行进一步讨论</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td width="64">诚益通</td>
            <td width="64">IPO</td>
            <td width="64">补充中期材料</td>
            <td width="64">非现场</td>
            <td width="241">补充反馈恢复中7月回款情况</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td>顾形宇</td>
            <td width="64">高鸿股份</td>
            <td width="64">并购</td>
            <td width="64">尽职调查</td>
            <td width="64">高阳捷迅现场</td>
            <td width="241">现场尽职调查</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td rowspan="4">陈明星</td>
            <td width="64">山东美多</td>
            <td width="64">IPO</td>
            <td width="64">辅导</td>
            <td width="64">北京</td>
            <td width="241">撰写业务与技术章节，跟踪蓝天环科模拟合并问题</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td width="64">绿翔糖业</td>
            <td width="64">IPO</td>
            <td width="64">改制</td>
            <td width="64">北京</td>
            <td width="241">召开中介会，准备股改相关工作，督促企业尽快股改</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td width="64">长征电气</td>
            <td width="64">非公开</td>
            <td width="64">尽调</td>
            <td width="64">北京</td>
            <td width="241">尽职调查，撰写尽调报告</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td width="64">同方股份</td>
            <td width="64">重组</td>
            <td width="64">发行</td>
            <td width="64">北京</td>
            <td width="241">准备发行阶段相关文件</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
          <tr>
            <td rowspan="2">成永攀</td>
            <td width="64">德基机械</td>
            <td width="64">IPO</td>
            <td width="64">补充中报</td>
            <td width="64">北京</td>
            <td width="241">访谈客户、补充招股书</td>
            <td width="64">6</td>
            <td width="64">5</td>
          </tr>
          <tr>
            <td width="64">赛轮股份</td>
            <td width="64">非公开</td>
            <td width="64">上报材料</td>
            <td width="64">北京</td>
            <td width="241">答复反馈意见、与预审员沟通</td>
            <td width="64">　</td>
            <td width="64">5</td>
          </tr>
          <tr>
            <td>陈嘉楠</td>
            <td width="64">高鸿股份</td>
            <td width="64">并购</td>
            <td width="64">尽职调查</td>
            <td width="64">北京</td>
            <td width="241">写交易报告书</td>
            <td width="64">　</td>
            <td width="64">　</td>
          </tr>
        </table>
    </div>
</body>
</html>