<html class="ui-mobile">
<head>
    <base href="/microshop/">
    <title>点餐</title>
    <meta charset="utf-8">
    <meta name="viewport"   content="width=device-width, minimum-scale=1, maximum-scale=1,maximum-scale=1, user-scalable=no">
</head>
<body ryt12322="1">
<link rel="apple-touch-icon" href="pic_01.jpg">
<link rel="stylesheet" href="css/jquery.mobile-1.3.1.min.css">
<link href="css/mobiscroll.custom-2.5.0.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/common.css">
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery.mobile-1.3.1.min.js"></script>
<script src="js/mobiscroll.custom-2.5.0.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/DishesPool.js"></script>
<script type="text/javascript" src="js/jquery.scollView.js"></script>
<script type="text/javascript" src="js/mobilescroll.js"></script>
<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
    WeixinJSBridge.call('hideToolbar');
});
$(document).ready(function () {
    $('.mobilescroll').each(function () {
        this.touchScroll();
    });
    //透明层不显示
    //$('.dis_mask').hide();
});
var shopId = "0002";
var curDishesTypeId = "01";
var curLabelId = "0";
var nextPageNo = "";
var showVisible = "hide";//hide|show
//初始化一个DishesPool
var pool = (function (window) {
    var p = new DishesPool(new Array(), 0, 0);
    var pstr = 'null';
    if (pstr && pstr != 'null') {
        p.parseJson(pstr);
    }
    return p;
})(window);
function addDishes(dishesId, price, name, unitName) {
    $.ajax({
        url: "wechat/shopDishesStanard.do",
        type: "GET",
        data: "dishesId=" + dishesId + "&shopId=" + shopId,
        dataType: "json",
        success: function (msg) {
            alert(msg);
        }
    })

    var cur = $("#input_" + dishesId.trim()).val();
    if (!cur) {
        cur = 0;
    }
    var d = new Dishes(dishesId, "", price, 1, name, unitName);
    pool.plus(d);
    cur = parseInt(cur) + 1;
    if (cur > 0) {
        $("#del_" + dishesId.trim()).removeClass("self_menu_disdel");
        $("#del_" + dishesId.trim()).addClass("self_menu_del");

        $("#oper_tr_" + dishesId.trim()).show();
        $("#btn_tr_" + dishesId.trim()).hide();
    }
    $("#input_" + dishesId.trim()).val(cur);
    $("#totalNumber").text(pool.n);
    $("#totalMoney").text(pool.m);
}

function delDishes(dishesId, price, name, unitName) {
    var cur = $("#input_" + dishesId.trim()).val();
    if (!cur) {
        cur = 0;
    }
    if (cur != 0) {
        var d = new Dishes(dishesId, "", price, 1, name, unitName);
        pool.minus(d);
        cur = parseInt(cur) - 1;
        $("#input_" + dishesId.trim()).val(cur);
        if (cur <= 0) {
            $("#del_" + dishesId.trim()).removeClass("self_menu_del");
            $("#del_" + dishesId.trim()).addClass("self_menu_disdel");

            $("#oper_tr_" + dishesId.trim()).hide();
            $("#btn_tr_" + dishesId.trim()).show();
        }
    }
    $("#totalNumber").text(pool.n);
    $("#totalMoney").text(pool.m);
}


$(function () {
    initSelectedDishes();
    /*var tbodyEls = $(".self_menuTab").find("tbody");
     if (tbodyEls && tbodyEls.length > 0) {
     for (var i=0,l=tbodyEls.length;i<l;i++) {
     var elId = $(tbodyEls[i]).attr("id");
     var ar = elId.split("_");
     alert(ar+"====")
     if (ar && ar.length >= 2) {
     initSelectedDishes(ar[1]);
     }
     }
     }*/
    if (showVisible == "show") {
        addmenu("JS_SelectShop");
    }
    $('#selDishesType').click(function () {
        var iconCurrs = $("#dishesType_JS_listData").find(".active_bg").find(".icon_curr");
        if (iconCurrs && iconCurrs.length != 0) {
            var iconCurr = iconCurrs[0];
            var dishesTypeId = $(iconCurr).parent().parent().attr("dishesTypeId");
            var dishesTypeName = $(iconCurr).parent().prev().text();
            if (dishesTypeName) {
                dishesTypeName = dishesTypeName.trim();
            }
            if (dishesTypeId && dishesTypeName) {
                $("#dishesType").attr("dishesTypeId", dishesTypeId);
                $("#dishesType").text(dishesTypeName);
            }

            var pageNo = 0;
            var pageSize = $("#loadMore").attr("pageSize");
            var nextPage = 'true';

            if (dishesTypeId != curDishesTypeId) {
                $(".self_menuTab").empty();
                curDishesTypeId = dishesTypeId;
                scroll(dishesTypeId, curLabelId, pageNo, pageSize, nextPage, shopId, function (n) {
                });
            }
        }
        $('.dis_mask').hide();
    });
    $("#selLabel").click(function () {
        var iconCurrs = $("#label_JS_listData").find(".active_bg").find(".icon_curr");
        if (iconCurrs && iconCurrs.length != 0) {
            var iconCurr = iconCurrs[0];
            var labelId = $(iconCurr).parent().parent().attr("labelId");
            var labelName = $(iconCurr).parent().prev().text();
            if (labelName) {
                labelName = labelName.trim();
            }
            if (labelId && labelName) {
                $("#label").attr("labeld", labelId);
                $("#label").text(labelName);
            }

            var pageNo = 0;
            var pageSize = $("#loadMore").attr("pageSize");
            var nextPage = 'true';

            if (labelId != curLabelId) {
                $(".self_menuTab").empty();
                curLabelId = labelId;
                scroll(curDishesTypeId, curLabelId, pageNo, pageSize, nextPage, shopId, function (n) {
                });
            }
        }
        $('.dis_mask').hide();
    })

    $("#selShop").click(function () {
        var iconCurrs = $("#label_JS_listShop").find(".active_bg").find(".icon_curr");
        if (iconCurrs && iconCurrs.length != 0) {
            var iconCurr = iconCurrs[0];
            var sId = $(iconCurr).parent().parent().attr("shopId");
            if (sId != shopId) {
                window.location.href = "selectedmenu.php";
            }
        }
    })

    $("#toSelfSelectMenu").click(function () {
        /*$("#shopId").val(shopId);
         $("#poolJson").val(pool.toJson());
         $("#postForm").attr("action","wechat/toSelfSelectMenu.do"); //设置访问路径
         $("#postForm").submit();*/
        var params = "mobilephone=&weChatId=o2bV2t71sI1w0cTQpKxjCPBvPYN0&userInfoId=&date=&shopId=" + shopId + "&poolJson=" + pool.toJson();
        window.location.href = "selectedmenu.php" ;
    })

    var nextPageNo = 0;

    $("#loadMore").click(function () {
        var dishesTypeId = $("#dishesType").attr("dishesTypeId");
        var labelId = $("#label").attr("labelId");
        var pageNo = $("#loadMore").attr("pageNo");
        var pageSize = $("#loadMore").attr("pageSize");
        var nextPage = $("#loadMore").attr("nextPage");

        if ((curDishesTypeId != dishesTypeId) || (curLabelId != labelId)) {
            curDishesTypeId = dishesTypeId;
            curLabelId = labelId;

            nextPageNo = 0;
            pageNo = 1;
        }
        //curDishesTypeId = dishesTypeId;
        //curLabelId = labelId;

        if ((nextPageNo + 1) == pageNo) {
            nextPageNo = (nextPageNo + 1);
            scroll(dishesTypeId, labelId, pageNo, pageSize, nextPage, shopId, function (n) {
                //nextPageNo = n;
            })
        }

        /*if (!pageNo) {
         pageNo = "1";
         }
         curDishesTypeId = dishesTypeId;
         curLabelId = labelId;
         if (!nextPageNo) {
         nextPageNo = parseInt(pageNo) + 1;
         //nextPageNo = (pageNo + 1);
         }
         if (nextPageNo && nextPageNo == (parseInt(pageNo)+1)) {
         nextPageNo = parseInt(pageNo) + 1;
         scroll(dishesTypeId,labelId,pageNo,pageSize,nextPage,shopId)
         }*/

    })

})

function redSelectShop() {
    window.location.href = "wechat/initAddSelfOrder.do?mobilephone=&weChatId=o2bV2t71sI1w0cTQpKxjCPBvPYN0&userInfoId=";
}

function redSelectDishes() {
    window.location.href = "selectedmenu.php";
}

/**
 * 跳转到菜品详情
 */
function selfMenuDetail(_dishesId) {
    //alert(_dishesId);
    window.location.href = "selectedmenu.php";
}

</script>


<div id="wrapper" class="ui-mobile-viewport ui-overlay-c">
<div data-role="page" id="self_menu" data-url="self_menu" tabindex="0"
     class="ui-page ui-body-c ui-page-footer-fixed ui-page-active" style="padding-bottom: 61px; min-height: 167px;">
<div class="topMenu">选择菜品<p class="btn_validGray" onclick="addmenu('JS_SelectShop')">珠江路店</p></div>
<div id="content" data-role="content" class="ui-content" role="main">
    <div class="relative" id="JS_menu">
        <p class="btn_validGray relaLeft" id="JS_HotDishes">

            <a id="dishesType" href="javascript:void(0);" dishestypeid="01" class="ui-link">冷菜</a><span
                class="icon_arrowD"></span>


        </p>

        <p class="btn_validGray relaRight" id="JS_All"><a id="label" href="javascript:void(0);" labelid="0"
                                                          class="ui-link">全部</a><span class="icon_arrowD"></span></p>
    </div>
    <div class="mt20">
        <table cellpadding="0" cellspacing="0" border="0" class="self_menuTab" width="100%" bgcolor="#FFF">

            <tbody id="dishes_10001  ">
            <tr>
                <td rowspan="2" width="50%" valign="middle" style="border-top-left-radius: 8px;">
                    <a onclick="selfMenuDetail('10001  ')" href="#" class="ui-link">


                        <img src="image/1.jpg">


                    </a></td>
                <td width="50%" valign="top" style="border-top-right-radius: 8px;">至尊深井烧鹅<p class="font90 gray9">
                        31.0元/份</p></td>
            </tr>
            <tr style="display: none;" id="oper_tr_10001">
                <td class="notop">
                        	<span class="left25">
                        		<a href="#" class="self_menu_add ui-link" id="add_10001"
                                   onclick="addDishes('10001  ',31.0,'至尊深井烧鹅','份')">添加菜品</a>
                        	</span>
                        	<span class="left30">
                        		<div class="ui-input-text ui-shadow-inset ui-corner-all ui-btn-shadow"><input
                                        type="text" readonly="" value="0" name="dishesNum"
                                        class="self_menu_addinput ui-input-text ui-body-c" id="input_10001"></div>
                        	</span>
                        	<span class="left35">
                        		<a href="#" class="self_menu_disdel ui-link" id="del_10001"
                                   onclick="delDishes('10001  ',31.0,'至尊深井烧鹅','份')">删除菜品</a>
                        	</span>
                </td>
            </tr>
            <tr id="btn_tr_10001">
                <td class="notop">
                    <a href="#" class="btn_valid p510 font90 ui-link" style="width:35%;" id="btn_10001"
                       onclick="addDishes('10001  ',31.0,'至尊深井烧鹅','份')">来一份</a>
                </td>
            </tr>
            </tbody>

            <tbody id="dishes_10002  ">
            <tr>
                <td rowspan="2" width="50%" valign="middle">
                    <a onclick="selfMenuDetail('10002  ')" href="#" class="ui-link">


                        <img src="image/2.jpg">


                    </a></td>
                <td width="50%" valign="top">潮式生卤虾<p class="font90 gray9">38.0元/份</p></td>
            </tr>
            <tr style="display: none;" id="oper_tr_10002">
                <td class="notop">
                        	<span class="left25">
                        		<a href="#" class="self_menu_add ui-link" id="add_10002"
                                   onclick="addDishes('10002  ',38.0,'潮式生卤虾','份')">添加菜品</a>
                        	</span>
                        	<span class="left30">
                        		<div class="ui-input-text ui-shadow-inset ui-corner-all ui-btn-shadow"><input
                                        type="text" readonly="" value="0" name="dishesNum"
                                        class="self_menu_addinput ui-input-text ui-body-c" id="input_10002"></div>
                        	</span>
                        	<span class="left35">
                        		<a href="#" class="self_menu_disdel ui-link" id="del_10002"
                                   onclick="delDishes('10002  ',38.0,'潮式生卤虾','份')">删除菜品</a>
                        	</span>
                </td>
            </tr>
            <tr id="btn_tr_10002">
                <td class="notop">
                    <a href="#" class="btn_valid p510 font90 ui-link" style="width:35%;" id="btn_10002"
                       onclick="addDishes('10002  ',38.0,'潮式生卤虾','份')">来一份</a>
                </td>
            </tr>
            </tbody>

            <tbody id="dishes_10003  ">
            <tr>
                <td rowspan="2" width="50%" valign="middle">
                    <a onclick="selfMenuDetail('10003  ')" href="#" class="ui-link">


                        <img src="image/3.jpg">


                    </a></td>
                <td width="50%" valign="top">卤干丝拌海肠<p class="font90 gray9">28.0元/份</p></td>
            </tr>
            <tr style="display: none;" id="oper_tr_10003">
                <td class="notop">
                        	<span class="left25">
                        		<a href="#" class="self_menu_add ui-link" id="add_10003"
                                   onclick="addDishes('10003  ',28.0,'卤干丝拌海肠','份')">添加菜品</a>
                        	</span>
                        	<span class="left30">
                        		<div class="ui-input-text ui-shadow-inset ui-corner-all ui-btn-shadow"><input
                                        type="text" readonly="" value="0" name="dishesNum"
                                        class="self_menu_addinput ui-input-text ui-body-c" id="input_10003"></div>
                        	</span>
                        	<span class="left35">
                        		<a href="#" class="self_menu_disdel ui-link" id="del_10003"
                                   onclick="delDishes('10003  ',28.0,'卤干丝拌海肠','份')">删除菜品</a>
                        	</span>
                </td>
            </tr>
            <tr id="btn_tr_10003">
                <td class="notop">
                    <a href="#" class="btn_valid p510 font90 ui-link" style="width:35%;" id="btn_10003"
                       onclick="addDishes('10003  ',28.0,'卤干丝拌海肠','份')">来一份</a>
                </td>
            </tr>
            </tbody>

            <tbody id="dishes_10004  ">
            <tr>
                <td rowspan="2" width="50%" valign="middle">
                    <a onclick="selfMenuDetail('10004  ')" href="#" class="ui-link">


                        <img src="image/4.jpg">


                    </a></td>
                <td width="50%" valign="top">精卤牛展<p class="font90 gray9">32.0元/份</p></td>
            </tr>
            <tr style="display: none;" id="oper_tr_10004">
                <td class="notop">
                        	<span class="left25">
                        		<a href="#" class="self_menu_add ui-link" id="add_10004"
                                   onclick="addDishes('10004  ',32.0,'精卤牛展','份')">添加菜品</a>
                        	</span>
                        	<span class="left30">
                        		<div class="ui-input-text ui-shadow-inset ui-corner-all ui-btn-shadow"><input
                                        type="text" readonly="" value="0" name="dishesNum"
                                        class="self_menu_addinput ui-input-text ui-body-c" id="input_10004"></div>
                        	</span>
                        	<span class="left35">
                        		<a href="#" class="self_menu_disdel ui-link" id="del_10004"
                                   onclick="delDishes('10004  ',32.0,'精卤牛展','份')">删除菜品</a>
                        	</span>
                </td>
            </tr>
            <tr id="btn_tr_10004">
                <td class="notop">
                    <a href="#" class="btn_valid p510 font90 ui-link" style="width:35%;" id="btn_10004"
                       onclick="addDishes('10004  ',32.0,'精卤牛展','份')">来一份</a>
                </td>
            </tr>
            </tbody>

            <tbody id="dishes_10005  ">
            <tr>
                <td rowspan="2" width="50%" valign="middle">
                    <a onclick="selfMenuDetail('10005  ')" href="#" class="ui-link">


                        <img src="image/5.jpg">


                    </a></td>
                <td width="50%" valign="top">卤皮虾<p class="font90 gray9">26.0元/份</p></td>
            </tr>
            <tr style="display: none;" id="oper_tr_10005">
                <td class="notop">
                        	<span class="left25">
                        		<a href="#" class="self_menu_add ui-link" id="add_10005"
                                   onclick="addDishes('10005  ',26.0,'卤皮虾','份')">添加菜品</a>
                        	</span>
                        	<span class="left30">
                        		<div class="ui-input-text ui-shadow-inset ui-corner-all ui-btn-shadow"><input
                                        type="text" readonly="" value="0" name="dishesNum"
                                        class="self_menu_addinput ui-input-text ui-body-c" id="input_10005"></div>
                        	</span>
                        	<span class="left35">
                        		<a href="#" class="self_menu_disdel ui-link" id="del_10005"
                                   onclick="delDishes('10005  ',26.0,'卤皮虾','份')">删除菜品</a>
                        	</span>
                </td>
            </tr>
            <tr id="btn_tr_10005">
                <td class="notop">
                    <a href="#" class="btn_valid p510 font90 ui-link" style="width:35%;" id="btn_10005"
                       onclick="addDishes('10005  ',26.0,'卤皮虾','份')">来一份</a>
                </td>
            </tr>
            </tbody>

        </table>
    </div>

    <p class="mt20" id="loadMore" pageno="1" pagesize="5" nextpage="true"><a class="btn_validGray ui-link">加载更多...</a>
    </p>


</div>
<!-- /content -->
<div class="dis_mask" style="display: none;"></div>
<div id="dialog_JS_HotDishes">
    <p class="dialog_title">选择菜品类型</p>

    <p class="btn_valid btn_finish" id="selDishesType">完成</p>

    <div class="listData_Box">
        <div class="mobilescroll" style="max-height:200px; overflow:hidden;">
            <table cellpadding="0" cellspacing="0" border="0" class="listTab2 JS_listData backColor" width="100%"
                   bgcolor="#FFF" id="dishesType_JS_listData">

                <tbody>
                <tr class="active_bg" dishestypeid="01">
                    <td width="80%" style="font-weight: bold; color: rgb(56, 84, 136); border-top-left-radius: 8px;">
                        冷菜
                    </td>
                    <td width="20%" align="right" style="border-top-right-radius: 8px;"><span class="icon_curr"></span>
                    </td>
                </tr>

                <tr class="active_bg" dishestypeid="02">
                    <td width="80%">热菜</td>
                    <td width="20%" align="right"></td>
                </tr>

                <tr class="active_bg" dishestypeid="03">
                    <td width="80%">海鲜</td>
                    <td width="20%" align="right"></td>
                </tr>

                <tr class="active_bg" dishestypeid="04">
                    <td width="80%">面点</td>
                    <td width="20%" align="right"></td>
                </tr>

                <tr class="active_bg" dishestypeid="05">
                    <td width="80%">茶饮</td>
                    <td width="20%" align="right"></td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/dialog_JS_HotDishes-->
<div id="dialog_JS_All">
    <p class="dialog_title">选择分类</p>

    <p class="btn_valid btn_finish" id="selLabel">完成</p>

    <div class="listData_Box">
        <div class="mobilescroll" style="max-height:200px; overflow:hidden;">
            <table cellpadding="0" cellspacing="0" border="0" class="listTab2 JS_listData backColor" width="100%"
                   bgcolor="#FFF" id="label_JS_listData">

                <tbody>
                <tr class="active_bg" labelid="0">
                    <td width="80%" style="font-weight: bold; color: rgb(56, 84, 136); border-top-left-radius: 8px;">
                        全部
                    </td>
                    <td width="20%" align="right" style="border-top-right-radius: 8px;"><span class="icon_curr"></span>
                    </td>
                </tr>

                <tr class="active_bg" labelid="3">
                    <td width="80%">人气菜</td>
                    <td width="20%" align="right"></td>
                </tr>

                <tr class="active_bg" labelid="1">
                    <td width="80%">特价菜</td>
                    <td width="20%" align="right"></td>
                </tr>

                <tr class="active_bg" labelid="2">
                    <td width="80%">推荐菜</td>
                    <td width="20%" align="right"></td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/dialog_JS_All-->
<div id="dialog_JS_addMenu">
    <p class="dialog_title">选择菜品类型</p>

    <p class="btn_valid btn_finish">完成</p>

    <div class="listData_Box">
        <div class="mobilescroll" style="max-height:200px; overflow:hidden;">
            <table cellpadding="0" cellspacing="0" border="0" class="listTab2 JS_listData backColor" width="100%"
                   bgcolor="#FFF">
                <tbody>
                <tr class="active_bg">
                    <td width="80%" style="border-top-left-radius: 8px;">大份</td>
                    <td width="20%" align="right" style="border-top-right-radius: 8px;"></td>
                </tr>
                <tr class="active_bg">
                    <td>中份</td>
                    <td align="right"></td>
                </tr>
                <tr class="active_bg">
                    <td>小份</td>
                    <td align="right"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/dialog_JS_addMenu-->
<!--<div id="dialog_JS_SelectShop" style="bottom: 0px;">-->
<!--    <p class="dialog_title">选择门店</p>-->
<!---->
<!--    <p class="btn_valid btn_finish" id="selShop">完成</p>-->
<!---->
<!--    <div class="listData_Box">-->
<!--        <div class="mobilescroll" style="max-height:200px; overflow:hidden;">-->
<!--            <table cellpadding="0" cellspacing="0" border="0" class="listTab2 JS_listData backColor" width="100%"-->
<!--                   bgcolor="#FFF" id="label_JS_listShop">-->
<!---->
<!--                <tbody>-->
<!--                <tr class="active_bg" shopid="0002">-->
<!--                    <td width="80%" style="font-weight: bold; color: rgb(56, 84, 136); border-top-left-radius: 8px;">-->
<!--                        珠江路店-->
<!--                    </td>-->
<!--                    <td width="20%" align="right" style="border-top-right-radius: 8px;"><span class="icon_curr"></span>-->
<!--                    </td>-->
<!--                </tr>-->
<!---->
<!--                <tr class="active_bg" shopid="0003">-->
<!--                    <td width="80%">中华门店</td>-->
<!--                    <td width="20%" align="right"></td>-->
<!--                </tr>-->
<!---->
<!--                <tr class="active_bg" shopid="0004">-->
<!--                    <td width="80%">新街口店</td>-->
<!--                    <td width="20%" align="right"></td>-->
<!--                </tr>-->
<!---->
<!--                <tr class="active_bg" shopid="0005">-->
<!--                    <td width="80%">江宁店</td>-->
<!--                    <td width="20%" align="right"></td>-->
<!--                </tr>-->
<!---->
<!--                </tbody>-->
<!--            </table>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--/dialog_JS_SelectShop-->
<div data-role="footer" data-position="fixed" id="footer" class="ui-footer ui-bar-a ui-footer-fixed slideup"
     role="contentinfo">
    <div class="p15 font90">
        <p class="mt5">总计：<span id="totalMoney">0</span>元，共计<span id="totalNumber">0</span>份</p>
        <a id="toSelfSelectMenu" class="btn_valid btn_finish ui-link">已选菜品</a>
    </div>
</div>
<!-- /footer -->
</div>
<!-- /page -->
<div class="ui-loader ui-corner-all ui-body-a ui-loader-default"><span class="ui-icon ui-icon-loading"></span>

    <h1>loading</h1></div>
</div>
<!-- /wrapper -->

<!-- 提交表单 -->
<div style="display: none;">
    <form action="" method="post" id="postForm">
        <!-- 用户基本信息的情况 -->
        <input type="hidden" id="mobilephone" name="mobilephone" value="">
        <input type="hidden" id="weChatId" name="weChatId" value="o2bV2t71sI1w0cTQpKxjCPBvPYN0">
        <input type="hidden" id="userInfoId" name="userInfoId" value="">

        <!-- 不可变参数，传过来了就不会变了 -->
        <input type="hidden" id="date" name="date" value="">

        <!-- 可变参数 -->
        <input type="hidden" id="shopId" name="shopId" value="">
        <input type="hidden" id="poolJson" name="poolJson" value="">
    </form>
</div>


</link rel="apple-touch-icon">
<style id="x1mx5ds" class="o1zalz0 x1mx5ds">
    .y1rhona {
        width: 420px;
        height: 420px;
        border-radius: 2px;
        box-shadow: 0 0 7px rgba(0, 0, 0, 0.4);
        background: #fff;
        position: fixed;
        left: 50%;
        top: 50%;
        margin-left: -210px;
        margin-top: -210px;
        z-index: 2147483646;
        display: none;
        font: 14px/1.5 arial, \5b8b\4f53, sans-serif;
    }

    .pmms6c {
        height: 52px;
        border-bottom: solid 1px #e4e4e4;
        padding: 20px;
        font-size: 20px;
        font-weight: 400;
        position: relative;
    }

    .pmms6c img {
        float: left;
        margin-right: 15px;
    }

    .k8q37b {
        height: 227px;
        font-size: 14px;
        padding: 20px;
    }

    .k8q37b .c1s3all {
        float: left;
        margin-right: 20px;
        margin-top: -6px;
    }

    .k8q37b .vnfudq {
        margin-top: 10px;
        margin-bottom: 25px;
    }

    .k8q37b span {
        color: red;
    }

    .b5m68k {
        background-color: #e9e9e9;
        height: 20px;
        padding: 20px;
        border-bottom-right-radius: 2px;
        border-bottom-left-radius: 2px;
    }

    .b5m68k label {
        background: url(chrome-extension://keigpnkjljkelclbjbekcfnaomfodamj/assets/images/qrcode/check.png) no-repeat 0 1px;
    }

    .b5m68k label.selected {
        background: url(chrome-extension://keigpnkjljkelclbjbekcfnaomfodamj/assets/images/qrcode/checked.png) no-repeat 0 0;
    }

    .b5m68k label input {
        opacity: 0;
        margin-right: 10px;
    }

    .pmms6c .a1alnh0 {
        position: absolute;
        right: 10px;
        top: 5px;
        font-size: 24px;
        text-decoration: none;
        color: #b3b3b3;
    }

    .pmms6c a {
        color: #377bee;
    }
</style>
<style type="text/css" id="kg11if" class="o1zalz0">
    .g1silm4 {
        border-radius: 2px;
        box-shadow: 0 0 7px rgba(0, 0, 0, 0.4);
        position: absolute;
        top: 376px;
        left: 404px;
        margin-bottom: 100px;
        background: #fff;
        z-index: 2147483646;
        display: none;
        font: 14px/1.5 arial, \5b8b\4f53, sans-serif;
    }

    .g1silm4 .x3a8lx {
    }

    .g1silm4 .d14v9i {
        background: #fff;
        position: absolute;
        height: 8px;
        width: 8px;
        margin-left: -3px;
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
        left: 20%;
    }

    .g1silm4.p1fh3ub .d14v9i {
        box-shadow: -1px -1px 2px rgba(0, 0, 0, 0.4);
        top: -5px;
    }

    .g1silm4.a11ak2h .d14v9i {
        bottom: -5px;
        box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
    }

    .g1silm4.upwf0r .d14v9i {
        left: 50%;
    }

    .a1alnh0 {
        font-style: normal;
        font-size: 23px;
        position: relative;
        top: 3px;
    }

    .s159i4r {
        width: 170px;
        padding: 20px;
        font-size: 14px;
    }

    .s159i4r .wym94d {
        text-decoration: none;
        font-size: 14px;
        color: #999;
        float: right;
        position: relative;
        top: 5px;
    }

    .x3a8lx {
        overflow: hidden;
    }

    .wym94d:hover {
        color: #1e90ff;
    }

    .i1ykljy {
        width: 120px;
        height: 152px;
        padding: 20px;
    }

    .i1ykljy .vg1zd7 {
        margin-bottom: 10px;
    }

    .i1ykljy .x3a8lx span {
        color: red;
    }
</style>

<div class="g1silm4 p1fh3ub s159i4r">
    <div class="d14v9i"></div>
    <div class="x3a8lx">
        同步商品到手机一淘收藏夹， 手机购买更优惠！
        <a class="wym94d" href="#">我知道了<i class="a1alnh0">×</i></a>
    </div>
</div>

<div class="g1silm4 a11ak2h upwf0r i1ykljy">
    <div class="d14v9i"></div>
    <div class="x3a8lx">
        <div class="vg1zd7"></div>
        <div class="mfyw2a">手机购买省<span>13</span>元</div>
    </div>
</div>


<div class="y1rhona">
    <div class="pmms6c"><img src="chrome-extension://keigpnkjljkelclbjbekcfnaomfodamj/assets/images/qrcode/icon.png">已成功收藏到<a
            href="http://ruyi.taobao.com/my/want">一淘收藏夹</a><br>从手机一淘收藏夹购买更优惠<a class="a1alnh0" href="#">×</a></div>
    <div class="k8q37b">
        <img class="c1s3all" src="chrome-extension://keigpnkjljkelclbjbekcfnaomfodamj/assets/images/qrcode/msg.png">
        扫码安装一淘客户端
        <div class="vnfudq"><img
                src="chrome-extension://keigpnkjljkelclbjbekcfnaomfodamj/assets/images/qrcode/etaoqr.png" width="116"
                height="116"></div>
        安卓手机扫码初装，<br> 登录后送<span>50</span>个集分宝
    </div>
    <div class="b5m68k"><label><input id="no-tips" type="checkbox">不再提示</label></div>
</div>


</body>
</html>