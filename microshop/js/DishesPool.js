/**
 * 选择菜品总数据
 * 
 * @param dishesArr -- 菜品对象数组
 * @param totalMoney -- 总金额
 * @param totalNumber -- 总数量
 * @return
 */
function DishesPool(dishesArr,totalMoney,totalNumber) {
	this.a = dishesArr || [];
	this.m = totalMoney || 0
	this.n = totalNumber || 0;
}
//重写原型对象，增加方法
DishesPool.prototype = {
	constructor : DishesPool,
	toJson : function() {
		return '{"a":['+ this.a +'],"m":'+this.m+',"n":'+this.n + '}';
	},
	get : function(dishesId) {
		var dishes = null;
		for (var i=0,l=this.a.length;i<l;i++) {
			dishes = this.a[i];
			if (dishes.d == dishesId) {
				break;
			}
		}
		return dishes;
	},
	plus : function(dishes) {
		if (this.a.length > 0) {
			var inArr = null;
			for (var i=0,l=this.a.length;i<l;i++) {
				if (this.a[i].equals(dishes)) {
					inArr = this.a[i];
					break;
				}
			}
			if (inArr) {
				inArr.plus(dishes.no);
			} else {
				this.a.push(dishes);
			}
		} else {
			this.a.push(dishes);
		}
		//将总数量加一
		this.n += dishes.no;
		this.m += (dishes.p * dishes.no);
	},
	minus : function(dishes) {
		var inArr = null;
		var inArrIndex = -1;
		for (var i=0,l=this.a.length;i<l;i++) {
			if (this.a[i].equals(dishes)) {
				inArr = this.a[i];
				inArrIndex = i;
				break;
			}
		}
		if (inArr) {
			inArr.no -= dishes.no;
			this.n -= dishes.no;
			this.m -= (dishes.p * dishes.no);
			if (inArr.no <= 0) {
				this.a.splice(inArrIndex,1);
			}
		}
	},
	toString : function() {
		return this.toJson();	
	},
	parseJson : function(jsonStr) { //将传入的json字符串对象解析成DishesPool对象
		if (jsonStr) {
			try {
				var json = (new Function("return " + jsonStr))();
				for (var i=0,l=json.a.length;i<l;i++) {
					this.a.push(new Dishes(json.a[i].d,json.a[i].s,json.a[i].p,json.a[i].no,json.a[i].n,json.a[i].u));
				}
				this.m = json.m;
				this.n = json.n;
			} catch(e){
			}
		}
	},
	empty : function() {
		this.a.splice(0,this.a.length);
		this.m = 0;
		this.n = 0;
	},
	isEmpty : function (){
		return (!this.a || this.a.length == 0); 
	}
}
/**
 * 选择菜品
 * 
 * @param dishesId 菜品ID，对应后台数据库
 * @param standardId 规格ID，对应后台数据库
 * @param price 单价
 * @param number 数量
 * @param name 名称
 * @param image 图片
 * @return
 */
function Dishes(dishesId,standardId,price,number,name,unitName) {
	this.d = dishesId;
	this.s = standardId;
	this.p = price;
	this.no = number;
	this.n = name;
	this.u = unitName;
}
//重写原型对象，增加方法
Dishes.prototype = {
	constructor : Dishes, 
	toJson : function(){
		return '{"d":"'+ this.d +'","s":"'+ this.s +'","p":'+
		this.p +',"no":'+ this.no +',"n":"'+ this.n +'","u":"'+ this.u +'"}';
	},
	plus : function(number) {
		this.no += number;
	},
	minus : function(number) {
		this.no -= number;
	},
	equals : function(other) {
		//菜品ID 和 规格ID相同的时候才是同一个菜品
		if (this.d == other.d && this.s == other.s) { 
			return true;
		}
		return false;
	},
	toString : function(){
		return this.toJson();
	}
}

/**
* String format
* 
* @return {String}
*/
String.prototype.format = function () {
	var s = this, i = arguments.length;
	while (i--) {
		s = s.replace(new RegExp('\\{' + i + '\\}', 'gm'), arguments[i]);
	}
	return s;
};

//动态加载

/*

{
    "data": [
        {
            "name": "蜜汁南瓜",
            "id": "10003  ",
            "number": "10003  ",
            "flag": "1",
            "seq": 1,
            "shopId": "1",
            "price": 12,
            "saleState": 1,
            "spell": "MZNG",
            "dishesTypeId": "01",
            "unitId": "10 ",
            "createTime": 1378204033000,
            "unitName": "份",
            "dishesTypeName": "冷菜",
            "thumbnailUrl": "images/pic_01.jpg",
            "highDefUrl": "images/pic_01.jpg",
            "behavior": "1",
            "describe": "1",
            "isImport": 1,
            "isDel": 0,
            "shopSaleState": 1,
            "sdId": "3",
            "shopPrice": 11
        }
    ],
    "currentPageNo": 2,
    "pageCount": 2,
    "subTotal": null,
    "grandTotal": null,
    "pageSize": 2,
    "totalRecords": 4,
    "startPageIndex": 1,
    "endPageIndex": 2,
    "prePage": true,
    "nextPage": false,
    "totalPage": 2,
    "grandTotal2": null
}
<tbody id="dishes_${d.id }">
	<tr>
    	<td rowspan="2" width="50%" valign="middle"><a onclick="selfMenuDetail('${d.id }')" href="#"><img src="${d.highDefUrl }" /></a></td>
    	<td width="50%" valign="top">${d.name }<p class="font90 gray9">${d.price }元/${d.unitName }</p></td>
  	</tr>
  	<tr style="display: none;" id="oper_tr_${fn:trim(d.id) }">
        <td class="notop">
        	<span class="left25">
        		<a href="#" class="self_menu_add" id="add_${fn:trim(d.id) }" onClick="addDishes('${d.id }',${d.price },'${d.name }','${d.highDefUrl }','${d.unitName }')">添加菜品</a>
        	</span>
        	<span class="left30">
        		<input type="text" value="0" name="dishesNum" class="self_menu_addinput" id="input_${fn:trim(d.id) }" />
        	</span>
        	<span class="left35">
        		<a href="#" class="self_menu_disdel" id="del_${fn:trim(d.id) }" onClick="delDishes('${d.id }',${d.price },'${d.name }','${d.highDefUrl }','${d.unitName }')">删除菜品</a>
        	</span>
        </td>
  	</tr>
    <tr id="btn_tr_${fn:trim(d.id) }">
    	 <td class="notop">
    	 	<a href="#" class="btn_valid p510 font90" style="width:35%;" id="btn_${fn:trim(d.id) }" onClick="addDishes('${d.id }',${d.price },'${d.name }','${d.highDefUrl }','${d.unitName }')">来一份</a>
    	 </td>
  	</tr>        		
</tbody>
*/



/**
 * 初始化已选的菜品
 */
/*function initSelectedDishesAjax() {
	var dishesId = "10001  ";
	var dishes = pool.get(dishesId);
	if (dishes && dishes.number && dishes.number > 0) {
		$("#input_"+dishesId.trim()).val(dishes.number);
		$("#del_"+dishesId.trim()).removeClass("self_menu_disdel").addClass("self_menu_del")
	}
}*/

/**
 * 初始化已选的菜品
 */
function initSelectedDishes() {
	/*alert(dishesId+"===")

	var dishes = pool.get(dishesId);
	alert(dishes + "===" +pool)
	if (dishes && dishes.number && dishes.number > 0) {
		$("#input_"+dishesId.trim()).val(dishes.number);
		$("#del_"+dishesId.trim()).removeClass("self_menu_disdel").addClass("self_menu_del")
	}*/
	for (var i=0,l=pool.a.length;i<l;i++) {
		dishes = pool.a[i];
		if (dishes && dishes.no && dishes.no > 0) {
			$("#input_"+dishes.d.trim()).val(dishes.no);
			$("#del_"+dishes.d.trim()).removeClass("self_menu_disdel").addClass("self_menu_del");
			
			$("#oper_tr_"+dishes.d.trim()).show();
			$("#btn_tr_"+dishes.d.trim()).hide();
		}
	}
	$("#totalNumber").text(pool.n);
	$("#totalMoney").text(pool.m);
}

/**
 * 加载滚动
 * 
 * @param dishesTypeId -- 类型ID
 * @param labelId -- 标签ID
 * @param pageNo -- 当前页
 * @param pageSize -- 每页显示大小
 * @param nextPage --  是否下一页
 * @return
 */
function scroll(dishesTypeId,labelId,pageNo,pageSize,nextPage,shopId, callback) {
	/*var dishesTypeId = $("#dishesType").attr("dishesTypeId");
	var labelId = $("#label").attr("labelId");
	var pageNo = $("#loadMore").attr("pageNo");
	var pageSize = $("#loadMore").attr("pageSize");
	var nextPage = $("#loadMore").attr("nextPage");*/
	if (nextPage == 'true') {
		if (dishesTypeId && labelId) {
			$.scrollView({
				url : 'wechat/ajaxLoadDishes.do',
				pageNo : (parseInt(pageNo) + 1),
				pageSize : pageSize,
				data : "dishesTypeId="+dishesTypeId+"&labelId="+labelId+"&shopId="+shopId,
				before : function () {
							
				},					
				after : function(data) { //处理请求结果
					if (data) {
						if ((data.nextPage+'') == 'false') {
							$("#loadMore").hide();
						} else {
							$("#loadMore").show();
						}
						$("#loadMore").attr("nextPage",data.nextPage);
						$("#loadMore").attr("pageNo",data.currentPageNo);
						//callback(data.currentPageNo);
						//nextPageNo = (data.currentPageNo + 1);
						if (!data.pageCount || data.pageCount == 0) {
							$("#loadMore").attr("pageSize",5);
						} else {
							$("#loadMore").attr("pageSize",data.pageCount);
						}
						
						var arr = [];
						if (data.data) {
							$(data.data).each(function() {
								
								if (!this.thumbnailUrl || this.thumbnailUrl == 'null') {
									this.thumbnailUrl = 'images/noimg.jpg'
								}
								if (!this.highDefUrl || this.highDefUrl == 'null') {
									this.highDefUrl = 'images/noimg.jpg'
								}
								
								arr.push('<tbody id="dishes_{0}">'.format(this.id));
								arr.push('<tr>');
								arr.push('<td width="50%" valign="middle" rowspan="2">');
								arr.push('<a href="#" onclick="selfMenuDetail(\'{0}\')" class="ui-link"><img src="{1}"></a>'.format(this.id,this.thumbnailUrl));
								arr.push('</td>');
								arr.push('<td width="50%" valign="top" colspan="3">{0}<p class="font90 gray9">{1}元/{2}</p>	</td>'.format(this.name,this.price,this.unitName));	
								arr.push('</tr>');		
							    
								arr.push('<tr style="display: none;" id="oper_tr_{0}">'.format($.trim(this.id)));
								arr.push('<td class="notop">');
								arr.push('<span class="left25"><a onclick="addDishes(\'{0}\',{1},\'{2}\',\'{3}\')" id="add_{4}" class="self_menu_add ui-link" href="#">添加菜品</a></span>'.format(this.id,this.price,this.name,this.unitName,$.trim(this.id)));
								arr.push('<span class="left30">');
								arr.push('<input type="text" readonly="readonly" id="input_{0}" class="self_menu_addinput ui-input-text ui-body-c" name="" value="0">'.format($.trim(this.id)));
								arr.push('</span>');
								arr.push('<span class="left35"><a onclick="delDishes(\'{0}\',{1},\'{2}\',\'{3}\')" id="del_{4}" class="self_menu_disdel ui-link" href="#">删除菜品</a></span>'.format(this.id,this.price,this.name,this.unitName,$.trim(this.id)));
							   	arr.push('</td>');
							   	arr.push('</tr>');
							   	
							   	arr.push('<tr id="btn_tr_{0}">'.format($.trim(this.id)));
							   	arr.push('<td colspan="3" class="notop">');
							   	arr.push('<a href="#" class="btn_valid p510 font90" onclick="addDishes(\'{0}\',{1},\'{2}\',\'{3}\')" style="width:35%;" id="btn_{4}">来一份</a>'.format(this.id,this.price,this.name,this.unitName,$.trim(this.id)));
							   	arr.push('</td>');
							   	arr.push('</tr>');
							   	
							   	arr.push('</tbody>');      		
							})
						}
						$(".self_menuTab").append(arr.join(''));
						
						$(".listTab,.listTab2,.listTabpadd7,.self_menuTab").find('td:eq(0)').css('border-top-left-radius','8px');
						$(".listTab,.listTab2,.listTabpadd7,.self_menuTab").find('tr:eq(0)').find('td:last').css('border-top-right-radius','8px')
					}
					initSelectedDishes();
					//initSelectedDishesAjax();
				}				
			});
		}
	}
}