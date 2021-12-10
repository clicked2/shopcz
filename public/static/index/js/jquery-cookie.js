;(function($, window){
	$.extend({
		addCookie: function(key, value, day, path, domein){
			// 1.处理默认保存的路径
			var index = window.location.pathname.lastIndexOf("/");
			var currentPath = window.location.pathname.slice(0, index);
			path = path || currentPath;
			//2.处理默认保存的domein
			domein = domein || document.domein;
			//3.处理默认的过期时间
			if(!day){
				document.cookie = key+"="+value+";path="+path+";domein="+domein+";";
			}else{
				var date = new Date();
				date.setDate(date.getDate()+day);
				document.cookie = key+"="+value+";expires="+date.toGMTString()+";path="+path+";domein="+domein+";";
			}
		},
		getCookie: function(key){
			var arr = document.cookie.split(';');
			for(var i=0 ; i<arr.length ; i++){
				var temp = arr[i].split('=');
				if(temp[0].trim() === key){
					return temp[1];
					}
				}
			},
		delCookie: function(key){
			addCookie(key, getCookie(key), -1);
		}
	});
})($, window);