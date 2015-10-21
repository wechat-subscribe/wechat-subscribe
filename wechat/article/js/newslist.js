$(function(){
	// $.ajax({
	// 	url:"../test.php",
	// 	async:true,
	// 	data:{
	// 		'type':"update",
	// 		'infoid':1
	// 	},
	// 	success:function(data){
	// 		console.log(data);
	// 	},
	// 	error:function(e){
	// 		console.error("请求出错");
	// 	}
	// });
	$(".loadbar ").bind("click",function(){
		
	});
	$("body").delegate(".loadbar","click",function(){
		$(this).remove();
		var str = '<div class="loadpic"><img src="../img/load.gif" alt="" style="width: 30px;height: 30px;"></div>';
		$("body").append(str);
	});
});