/**
 * JQuery 显示更多插件 scrollView
 * 
 * @author Jerry
 * @version v1.0
 */
(function($){
	jQuery.extend({
		scrollView : function(options) {
			var _this = this;
			var defaluts = {
				'pageNo' : 1,
				'pageSize' : 10,
				'data' : null,
				'before' : null,
				'after' : null,
				'dataType' : "json",
				'url' : ""
			}
			var opts = $.extend(defaluts,options);
			$.ajax({
				url : opts.url,
				data : "pageNo="+opts.pageNo+"&pageSize="+opts.pageSize+"&"+opts.data,
				dataType : opts.dataType,
				beforeSend : opts.before,
				success: function (data) {
					opts.after.call(_this,data);
				}
			});
		}
	});
})(jQuery)