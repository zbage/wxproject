<html class="ui-mobile">
<head>
    <base href="/microshop/">
    <title>已选菜品</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,maximum-scale=1, user-scalable=no">
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

<script type="text/javascript">
    //初始化一个DishesPool
    var pool = (function (window) {
        var p = new DishesPool(new Array(), 0, 0);
        p.parseJson('{"a":[{"d":"10001  ","s":"","p":31,"no":1,"n":"至尊深井烧鹅","u":"份"}],"m":31,"n":1},' +
            '{"a":[{"d":"10001  ","s":"","p":31,"no":1,"n":"至尊深井烧鹅11","u":"份"}],"m":31,"n":1}');
        return p;
    })(window);
    function addDishes(dishesId, price, name, unitName) {
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
        $("#empty_btn").click(function () {
            $("#dishesTable").empty();
            $('.dis_mask').hide();
            pool.empty();
            $("#totalNumber").text(pool.totalNumber);
            $("#totalMoney").text(pool.totalMoney);
            $("#JS_empty").remove();
            var getTargetHeight = $(this).parent().parent().parent().height();
            $(this).parent().parent().parent().animate({'bottom': "-1" + getTargetHeight}, 400);
        });

        $("#sendAuthCodeCode").click(function () {
            var cls = $(this).attr("class");
            if (cls.indexOf("btn_validGray") > -1) {
                var phone = $("#phone").val();
                if (!phone) {
                    $("#phoneTip").text("请输入手机号").fadeIn(2000).fadeOut(3000);
                } else {
                    var v = /^((13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8})$/
                    if (v.test(phone)) {
                        $.ajax({
                            url: "wechat/sendAuthCodeCode.do",
                            method: "post",
                            data: "phone=" + phone,
                            success: function (msg) {
                                if (msg == 1) {
                                    $("#sendAuthCodeCode").text("已发送(120)");
                                    $("#sendAuthCodeCode").removeClass("btn_validGray").addClass("btn_disabled");
                                    SMSSended();
                                } else {
                                    $("#phoneTip").text("短信发送失败").fadeIn(2000).fadeOut(3000);
                                }
                            }
                        });
                    } else {
                        $("#phoneTip").text("手机号输入不合法").fadeIn(2000).fadeOut(3000);
                    }
                }
            }
        })

        $('#self_Save').click(function () {
            if (pool.isEmpty()) {
                $("#error").text("请选择菜品").fadeIn(100).fadeOut(3000);
                return false;
            }
            var mobilephone = $("#mobilephone").val();
            $(this).hide();
            if (mobilephone) {
                $("#poolJson").val(pool.toJson());
                doSave();
                //$("#saveOrderSelfMenuForm").submit();
            } else {
                $('#register').show();
            }
        });
    })
    function SMSSended() {
        var t = 120;
        var i = setInterval(function () {
            //var t = parseInt(jQuery("#sendAuthCodeCode").text()) || 120;
            if (t != 0) {
                jQuery("#sendAuthCodeCode").text("已发送(" + (--t) + ")");
            } else if (t == 0) {
                $("#sendAuthCodeCode").text("获取验证码").removeClass("btn_disabled").addClass("btn_validGray");
                clearInterval(i);
            }
        }, 1000);
    }
    function menuSuccess() {
        var phone = $("#phone").val();
        if (!phone) {
            $("#phoneTip").text("请输入手机号").fadeIn(2000).fadeOut(3000);
            return false;
        }
        var v = /^((13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8})$/
        if (!v.test(phone)) {
            $("#phoneTip").text("手机号输入不合法").fadeIn(2000).fadeOut(3000);
            return false;
        }
        var authCode = $("#authCode").val();
        if (!authCode) {
            $("#authCodeTip").text("请输入验证码").fadeIn(2000).fadeOut(3000);
            return false;
        }
        if (pool.isEmpty()) {
            $("#emptyTip").text("请选择菜品").fadeIn(2000).fadeOut(3000);
            return false;
        }

        $.ajax({
            url: "wechat/addUserInfo.do",
            data: "phone=" + phone + "&authCode=" + authCode + "&weChatId=" + $("#weChatId").val(),
            sync: false,
            dataType: "json",
            success: function (msg) {
                if (msg && msg.code == 1) {
                    $("#poolJson").val(pool.toJson());
                    $("#userInfoId").val(msg.userInfoId);
                    $("#mobilephone").val(phone);
                    doSave();
                    //$("#saveOrderSelfMenuForm").submit();
                } else {
                    $("#emptyTip").text(msg.tip).fadeIn(2000).fadeOut(3000);
                }
            }
        })

    }

    function doSave() {
        var _t = $("#_t").val();
        var mobilephone = $("#mobilephone").val();
        var weChatId = $("#weChatId").val();
        var userInfoId = $("#userInfoId").val();
        var date = $("#date").val();
        var shopId = $("#shopId").val();
        var poolJson = $("#poolJson").val();

        window.location.href = "wechat/saveOrderSelfMenu.do?_t=" + _t + "&mobilephone=" + mobilephone + "&weChatId=" + weChatId + "&userInfoId=" + userInfoId + "&date=" + date + "&shopId=" + shopId + "&poolJson=" + poolJson;
    }

    function redSelectShop() {
        window.location.href = "wechat/initAddSelfOrder.do?mobilephone=&weChatId=o2bV2t71sI1w0cTQpKxjCPBvPYN0&userInfoId=";
    }

    function redSelectDishes() {
        window.location.href = 'menu.php?mobilephone=&weChatId=o2bV2t71sI1w0cTQpKxjCPBvPYN0&userInfoId=&date=&shopId=0002&poolJson=' + pool.toString();
    }

    function redSelectedDishes() {
        window.location.href = 'wechat/toSelfSelectMenu.do?mobilephone=&weChatId=o2bV2t71sI1w0cTQpKxjCPBvPYN0&userInfoId=&date=&shopId=0002&poolJson={"a":[{"d":"10001  ","s":"","p":31,"no":1,"n":"至尊深井烧鹅","u":"份"}],"m":31,"n":1}';
    }

    function quxiaoshop() {
        $("#register").hide();
        $("#self_Save").show();
        $("#self_Save .btn_valid").show();
    }

</script>


<div id="wrapper" class="ui-mobile-viewport ui-overlay-c">
    <div data-role="page" id="self_selectmenu" data-url="self_selectmenu" tabindex="0"
         class="ui-page ui-body-c ui-page-footer-fixed ui-page-active" style="padding-bottom: 61px; min-height: 167px;">
        <div id="register">
            <p class="wrap"></p>

            <div class="register">
                <form action="#" method="post" id="saveOrderSelfMenuForm">
                    <input type="hidden" name="_t" id="_t" value="ab2bbde552464585bdaf85029fb99bc4">
                    <input type="hidden" name="mobilephone" id="mobilephone" value="">
                    <input type="hidden" name="weChatId" id="weChatId" value="o2bV2t71sI1w0cTQpKxjCPBvPYN0">
                    <input type="hidden" name="userInfoId" id="userInfoId" value="">
                    <input type="hidden" name="date" id="date" value="">
                    <input type="hidden" name="shopId" id="shopId" value="0002">
                    <input type="hidden" name="poolJson" id="poolJson" value="o2bV2t71sI1w0cTQpKxjCPBvPYN0">

                    <p>输入手机号完成预定</p>

                    <p class="mt5 gray9 font80">到店就餐时请向服务员提供【手机号码】确认即可！</p>

                    <div class="mt5">
                        <table cellpadding="0" cellspacing="0" border="0" class="listnopadd" width="100%"
                               bgcolor="#FFF">
                            <tbody>
                            <tr>
                                <td style="border-bottom:1px solid #bababa;">
                                    <div class="ui-input-text ui-shadow-inset ui-corner-all ui-btn-shadow"><input
                                            type="text" id="phone" name="phone" placeholder="手机号"
                                            class="inputNone ui-input-text ui-body-c"
                                            style="padding:11px 10px; width:100%; font-size:100%;"></div>
                                    <span id="phoneTip" class="red90" style="display: none;">输入手机号</span></td>
                            </tr>
                            <tr>
                                <td><p class="relative">

                                    <div class="ui-input-text ui-shadow-inset ui-corner-all ui-btn-shadow"><input
                                            type="text" id="authCode" name="authCode" placeholder="短信验证码"
                                            class="inputNone ui-input-text ui-body-c"
                                            style="padding:11px 10px; width:60%; font-size:100%;"></div>
                                    <a href="#" id="sendAuthCodeCode" class="btn_validGray min80 ui-link">获取验证码</a></p>
                                    <span id="authCodeTip" class="red90" style="display: none;">输入验证码</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <span id="emptyTip" class="red90" style="display: none;">请选择菜品</span>

                    <p class="mt10" onclick="menuSuccess()" id="regfinish" style="float:left; width:40%;"><a href="#"
                                                                                                             id="menuSuccess"
                                                                                                             class="btn_valid ui-link">完成</a>
                    </p>

                    <p class="mt10" style="float:right; width:40%;"><a id="regfinish" onclick="quxiaoshop()"
                                                                       class="btn_validGray ui-link">取消</a></p>
                    <br class="cb">
                </form>
            </div>
        </div>
        <div class="topMenu"><a href="#" onclick="redSelectDishes()" class="ui-link">选择菜品</a>已选菜品</div>
        <div id="content" data-role="content" class="ui-content" role="main">
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="listTabpadd7" width="100%" bgcolor="#FFF"
                       id="dishesTable">

                    <tbody>
                    <tr>
                        <td width="50%" style="border-top-left-radius: 8px;">至尊深井烧鹅<p class="mt5 gray6 font90">
                                31.0/份</p></td>
                        <td width="40%" style="border-top-right-radius: 8px;">
                            <p id="oper_tr_10001">
	                     <span class="left25">
	                     	<a href="#" class="self_menu_add ui-link" id="add_10001"
                               onclick="addDishes('10001  ',31.0,'至尊深井烧鹅','份')">添加菜品</a>
	                     </span>
	                     <span class="left30">
	                     	<div class="ui-input-text ui-shadow-inset ui-corner-all ui-btn-shadow"><input type="text"
                                                                                                          readonly=""
                                                                                                          value="至尊深井烧鹅"
                                                                                                          name="dishesNum"
                                                                                                          class="self_menu_addinput ui-input-text ui-body-c"
                                                                                                          id="input_10001">
                            </div>
	                     </span>
	                     <span class="left35">
	                     	<a href="#" class="self_menu_del ui-link" id="del_10001"
                               onclick="delDishes('10001  ',31.0,'至尊深井烧鹅','份')">删除菜品</a>
	                     </span>
                            </p>
                            <p id="btn_tr_10001" style="display: none;">
                                <a href="#" class="btn_valid p510 font90 ui-link" style="width:35%; min-width:45px;"
                                   id="btn_10001" onclick="addDishes('10001  ',31.0,'至尊深井烧鹅','份')">来一份</a>
                            </p>
                        </td>

                    </tr>
                    </tbody>


                </table>
            </div>

            <p class="mt20"><a href="javascript:void(0)" id="JS_empty" class="btn_validRed ui-link">清空菜单</a></p>

            <p class="mt20 red90" id="error" style="display: none;"></p>
        </div>
        <!-- /content -->
        <div class="dis_mask"></div>
        <div id="dialog_JS_empty">
            <p class="dialog_title">您确定清空菜单？</p>

            <div class="listData_Box">
                <p class="mt10"><a href="#" class="btn_validRed ui-link" id="empty_btn">清空菜单</a></p>

                <p class="mt10"><a style="cursor: pointer;" class="btn_validGray ui-link" id="JS_no">取消</a></p>
            </div>
        </div>
        <!--/dialog_JS_empty-->
        <div data-role="footer" data-position="fixed" id="footer" class="ui-footer ui-bar-a ui-footer-fixed slideup"
             role="contentinfo">
            <div class="p15 font90"><p class="mt5">总计：<span id="totalMoney">31</span>元，共计<span id="totalNumber">1</span>份
                </p>

                <p id="self_Save"><a href="#" class="btn_valid btn_finish ui-link">保存</a></p></div>
        </div>
        <!-- /footer -->
    </div>
    <!-- /page -->
    <div class="ui-loader ui-corner-all ui-body-a ui-loader-default"><span class="ui-icon ui-icon-loading"></span>

        <h1>loading</h1></div>
</div>
<!-- /wrapper -->


</linkrel="apple-touch-icon">
<style id="forn6u" class="nm9v8f forn6u">
    .krluef {
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

    .y1yjczn {
        height: 52px;
        border-bottom: solid 1px #e4e4e4;
        padding: 20px;
        font-size: 20px;
        font-weight: 400;
        position: relative;
    }

    .y1yjczn img {
        float: left;
        margin-right: 15px;
    }

    .xqwzyx {
        height: 227px;
        font-size: 14px;
        padding: 20px;
    }

    .xqwzyx .h18hq5y {
        float: left;
        margin-right: 20px;
        margin-top: -6px;
    }

    .xqwzyx .cm56q0 {
        margin-top: 10px;
        margin-bottom: 25px;
    }

    .xqwzyx span {
        color: red;
    }

    .l8njrq {
        background-color: #e9e9e9;
        height: 20px;
        padding: 20px;
        border-bottom-right-radius: 2px;
        border-bottom-left-radius: 2px;
    }

    .l8njrq label {
        background: url(chrome-extension://keigpnkjljkelclbjbekcfnaomfodamj/assets/images/qrcode/check.png) no-repeat 0 1px;
    }

    .l8njrq label.selected {
        background: url(chrome-extension://keigpnkjljkelclbjbekcfnaomfodamj/assets/images/qrcode/checked.png) no-repeat 0 0;
    }

    .l8njrq label input {
        opacity: 0;
        margin-right: 10px;
    }

    .y1yjczn .x3q4ox {
        position: absolute;
        right: 10px;
        top: 5px;
        font-size: 24px;
        text-decoration: none;
        color: #b3b3b3;
    }

    .y1yjczn a {
        color: #377bee;
    }
</style>
<style type="text/css" id="xd8hji" class="nm9v8f">
    .pu7vnv {
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

    .pu7vnv .gtqta7 {
    }

    .pu7vnv .r9yaqc {
        background: #fff;
        position: absolute;
        height: 8px;
        width: 8px;
        margin-left: -3px;
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
        left: 20%;
    }

    .pu7vnv.x21ylup .r9yaqc {
        box-shadow: -1px -1px 2px rgba(0, 0, 0, 0.4);
        top: -5px;
    }

    .pu7vnv.w1bvmxi .r9yaqc {
        bottom: -5px;
        box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
    }

    .pu7vnv.n1alyiq .r9yaqc {
        left: 50%;
    }

    .x3q4ox {
        font-style: normal;
        font-size: 23px;
        position: relative;
        top: 3px;
    }

    .l1bqesc {
        width: 170px;
        padding: 20px;
        font-size: 14px;
    }

    .l1bqesc .g1vz4zk {
        text-decoration: none;
        font-size: 14px;
        color: #999;
        float: right;
        position: relative;
        top: 5px;
    }

    .gtqta7 {
        overflow: hidden;
    }

    .g1vz4zk:hover {
        color: #1e90ff;
    }

    .xrbbex {
        width: 120px;
        height: 152px;
        padding: 20px;
    }

    .xrbbex .edypwa {
        margin-bottom: 10px;
    }

    .xrbbex .gtqta7 span {
        color: red;
    }
</style>

<div class="pu7vnv x21ylup l1bqesc">
    <div class="r9yaqc"></div>
    <div class="gtqta7">
        同步商品到手机一淘收藏夹， 手机购买更优惠！
        <a class="g1vz4zk" href="#">我知道了<i class="x3q4ox">×</i></a>
    </div>
</div>

<div class="pu7vnv w1bvmxi n1alyiq xrbbex">
    <div class="r9yaqc"></div>
    <div class="gtqta7">
        <div class="edypwa"></div>
        <div class="dn1ain">手机购买省<span>13</span>元</div>
    </div>
</div>


<div class="krluef">
    <div class="y1yjczn"><img src="chrome-extension://keigpnkjljkelclbjbekcfnaomfodamj/assets/images/qrcode/icon.png">已成功收藏到<a
            href="http://ruyi.taobao.com/my/want">一淘收藏夹</a><br>从手机一淘收藏夹购买更优惠<a class="x3q4ox" href="#">×</a></div>
    <div class="xqwzyx">
        <img class="h18hq5y" src="chrome-extension://keigpnkjljkelclbjbekcfnaomfodamj/assets/images/qrcode/msg.png">
        扫码安装一淘客户端
        <div class="cm56q0"><img
                src="chrome-extension://keigpnkjljkelclbjbekcfnaomfodamj/assets/images/qrcode/etaoqr.png" width="116"
                height="116"></div>
        安卓手机扫码初装，<br> 登录后送<span>50</span>个集分宝
    </div>
    <div class="l8njrq"><label><input id="no-tips" type="checkbox">不再提示</label></div>
</div>


</body>
</html>