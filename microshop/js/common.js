// JavaScript Document
$(document).ready(function(){
	
	//table 第一行td的圆角显示
	$(".listTab,.listTab2,.listTabpadd7,.self_menuTab").find('td:eq(0)').css('border-top-left-radius','8px');
	$(".listTab,.listTab2,.listTabpadd7,.self_menuTab").find('tr:eq(0)').find('td:last').css('border-top-right-radius','8px')
	
	
	//首页上拉效果
	$('#JS_listNav tr').each(function(){
		$(this).click(function(){
			$('.dis_mask').show();	
			$.each($('.btn_finish'),function(i,elem){
				var _bottom = $(elem).parent().css('bottom').replace('px','')*1;
				if(_bottom == 0){
					$(elem).trigger('click');
					return false;
				}
			});
			 var getId = $(this).attr('id');
			$("#dialog_"+getId).animate({'bottom':'0'},400);
		});
		
	});	
	
	//自助点菜: 热菜、全部上拉效果
	$('#JS_menu p').bind('click',function(){
		$('.dis_mask').show();
		$.each($('.btn_finish'),function(i,elem){
				var _bottom = $(elem).parent().css('bottom').replace('px','')*1;
				if(_bottom == 0){
					$(elem).trigger('click');
					return false;
				}
			});
         var getId = $(this).attr("id");
			$("#dialog_"+getId).animate({'bottom':'0'},400);
		});
		
	$(".btn_finish").click(function(){
			var getTargetHeight = $(this).parent().height();
			$(this).parent().animate({'bottom':"-1"+getTargetHeight},400);	
	})

	//清空 弹出是否确认清空数据
		$('#JS_empty').click(function(){	
		    $('.dis_mask').show();
			 var getId = $(this).attr('id');
			$("#dialog_"+getId).animate({'bottom':'0'},400);	
		});
		
	$("#JS_yes,#JS_no").click(function(){
		    $('.dis_mask').hide();
			var getTargetHeight = $(this).parent().parent().parent().height();
			$(this).parent().parent().parent().animate({'bottom':"-1"+getTargetHeight},400);	
	});

    //隐藏遮盖层
	$('.btn_finish').click(function(){$('.dis_mask').hide();});
	$('.dis_mask').click(function(){
		$('.dis_mask').hide();
		$(".btn_finish").trigger("click");
		$("#JS_yes,#JS_no").trigger("click");
	});

	//上拉效果内的单选效果
	$(".backColor tr.active_bg").click(function(){
		$(this).siblings('.active_bg').find('td:eq(0)').css({'color':'#333','font-weight':'normal'});
		$(this).siblings('.active_bg').find('td:eq(1)').html('');
		$(this).find('td:eq(0)').css({'color':'#385488','font-weight':'bold'});
		$(this).find('td:eq(1)').html('<span class="icon_curr"></span>');
	})
	
	//信息，点击后加粗去掉
	$(".message_list li").click(function(){
		$(this).addClass('nobold');	
	})
	 
	  //菜单成功里面的菜单明细 点击显示
    $('.dis_menu_title').click(function(){
		$(this).hide();
		$('.dis_menu').show();
    });
	 
	
	 
});

//自助点菜: 加菜（加号）上拉效果
function addmenu(JS_addMenu){	   
	$('.dis_mask').show();
	$("#dialog_"+JS_addMenu).animate({'bottom':'0'},400)
};	



//---------------------------------------------------  
//日期格式化  
//格式 YYYY/yyyy/YY/yy 表示年份  
//MM/M 月份  
//W/w 星期  
//dd/DD/d/D 日期  
//hh/HH/h/H 时间  
//mm/m 分钟  
//ss/SS/s/S 秒  
//---------------------------------------------------  
Date.prototype.format = function(formatStr) {   
 var str = formatStr;   
 var Week = ['日','一','二','三','四','五','六'];  

 str=str.replace(/yyyy|YYYY/,this.getFullYear());   
 str=str.replace(/yy|YY/,(this.getYear() % 100)>9?(this.getYear() % 100).toString():'0' + (this.getYear() % 100));   

 str=str.replace(/MM/,(this.getMonth()+1)>9?(this.getMonth()+1).toString():'0' + (this.getMonth()+1));   
 str=str.replace(/M/g,(this.getMonth()+1));   

 str=str.replace(/w|W/g,Week[this.getDay()]);   

 str=str.replace(/dd|DD/,this.getDate()>9?this.getDate().toString():'0' + this.getDate());   
 str=str.replace(/d|D/g,this.getDate());   

 str=str.replace(/hh|HH/,this.getHours()>9?this.getHours().toString():'0' + this.getHours());   
 str=str.replace(/h|H/g,this.getHours());   
 str=str.replace(/mm/,this.getMinutes()>9?this.getMinutes().toString():'0' + this.getMinutes());   
 str=str.replace(/m/g,this.getMinutes());   

 str=str.replace(/ss|SS/,this.getSeconds()>9?this.getSeconds().toString():'0' + this.getSeconds());   
 str=str.replace(/s|S/g,this.getSeconds());   

 return str;   
}  