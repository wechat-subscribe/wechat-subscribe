$(function(){
	var page = 1;

	function getPageContent( page ){
		$.ajax({
			url:"#",
			dataType:json,
			async:true,
			data:{
				'type':"",
				page:page
			},
			success:function(data){
				console.log(data);
				$(".loadpic").remove();	//去掉旋转图

				var str = '<li class="newsli clearfix"><a href="javascript:void(0);"><img class="leftimg" src="'+item.img+'" alt=" 公司图片" /><div class="video_flag"></div></a>';
				str += '<div class="newscontent"><div class="title"><a href="news.html?arctileId='+item.id+'">'+item.title+'</a></div>';
                str += '<div class="newscomment">'+item.comment+'</div></div></li>'; // <!-- (限制字数) -->      
                $("#newsulId").append(str);

                var lodstr = '<div class="loadbar clearfix">点击加载更多</div>';
    			$("body").append(lodstr);
			},
			error:function(e){
				console.error("请求出错");
			}
		});
	}
	
	 
	$("body").delegate(".loadbar","click",function(){
		page++;		//页码加1
		$(this).remove();
		var str = '<div class="loadpic"><img src="../img/load.gif" alt="" style="width: 30px;height: 30px;"></div>';
		$("body").append(str);
	});
});