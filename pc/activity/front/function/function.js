
 

/**
* 微信操作接口封装
* 使用代理
*/

// 全局变量
var ACCESS_TOKEN    = "";				//获取access_token
var EXPIRES_IN		= "";				//获取access_token的时间
var AGENTURL		= "function/function.php";	//代理URL

/** 获取access_token
* access_token的有效期目前为2个小时,需定时刷新
* 重复获取将导致上次获取的access_token失效
*/
function getAccess_token() {
	$.ajax({
		url			: 	AGENTURL,
		datatype	:  	"json",
		async		: 	false,
		type:           "GET",
		data: {
			type            :   "getAccess_token"
		},
		success:function(data) {   
			console.log(data);
			//console.log("获取access_token == " + ACCESS_TOKEN+"expires_in == "+EXPIRES_IN);
			/*if(data.access_token) {
				ACCESS_TOKEN = data.access_token;
				EXPIRES_IN   = data.expires_in;
				console.log("获取access_token == " + ACCESS_TOKEN+"expires_in == "+EXPIRES_IN);
				return ACCESS_TOKEN;
			}else if(data.errcode) {
				console.log(data.errcode+" "+data.errmsg);
			}*/
			console.log("获取access_token成功");
		},
		error:function(e){   		
			console.log("获取access_token出错");
		}
	});
}


/**
* 自定义菜单创建
*/
function creatMenu() {
	
	$.ajax({
		url			: 	AGENTURL,
		datatype	:  	"json",
		async		: 	false,
		type:           "GET",
		data: {
			type            :   "creatMenu"
		},
		success:function(data) {   
			console.log("调用creatMenu函数正常");
		},
		error:function(e){   		
			console.log("调用creatMenu函数失败");
		}
	});
}


/**
* 自定义菜单查询
*/
function getMenu() {
	
	$.ajax({
		url			: 	AGENTURL,
		datatype	:  	"json",
		async		: 	false,
		type:           "GET",
		data: {
			type            :   "getMenu"
		},
		success:function(data) {   
			console.log("调用getMenu函数正常");
		},
		error:function(e){   		     
			console.log("调用getMenu函数失败");
		}
	});
}

/**
* 自定义菜单删除
*/
function delMenu()　{
	
	$.ajax({
		url			: 	AGENTURL,
		datatype	:  	"json",
		async		: 	false,
		type:           "GET",
		data: {
			type            :   "delMenu"
		},
		success:function(data) {   
			console.log("调用delMenu函数正常");
		},
		error:function(e){   		
			console.log("调用delMenu函数失败");
		}
	});
}

/**
* 获取微信服务器IP
*/
function getWechatServerIp() {
	
	$.ajax({
		url			: 	AGENTURL,
		datatype	:  	"json",
		async		: 	false,
		type:           "GET",
		data: {
			type            :   "getWechatServerIp"
		},
		success:function(data) {   
			console.log("调用getWechatServerIp函数正常");
		},
		error:function(e){   		
			console.log("调用getWechatServerIp函数失败");
		}
	});
}
