
 var page = 1;   
  var ue = UE.getEditor('editor');	//实例化UE编辑器
 $(function(){
	 projectId=GetQueryString("projectId");
    if(projectId!=undefined){
		 $("input[name='projectId']").val(projectId); 
	}
   
	
    var menuId = GetQueryString("menuId") ;//alert(menuId);
	 if(projectId!=undefined){
		 data={'type':'update','projectId':projectId}; //alert(GetQueryString("projectId"));
		 da=loadData(data); 
		 
		 pageWrite(da);
	 }
    $("#submit").bind("click",function(){// alert($("input[name='projectId']").val());
        $("input[name='content']").val(UE.getEditor('editor').getPlainTxt());    
        //var content2 = UE.getEditor('editor').getAllHtml();
        if($("input[name='title']").val() == '' || $("input[name='content']").val() == ''){
        	alert("请检查数据不能为空");
        }else{ 
		if($("input[name='projectId']").val()){
		data={'type':'updateOK',
			'projectId':$("input[name='projectId']").val(),//projectId
			'title':$("input[name='title']").val(),
			'menuId':menuId,'content':ue.getContent(),
			 'start':$("input[name='start']").val() ,
			 'picture':$("input[name='picture']").val() ,
			 'end':$("input[name='end']").val() ,
			 'review':$("input[name='review']").val() ,
			 'valid':$("input[name='valid']").val() ,
			 'num':$("input[name='num']").val() 
			 };
		 d=loadData(data);//执行
		 if(d){
			 alert("更新成功");
		 }
		 else{
			 alert("更新失败");
		 }
		 	
		}else{
		data={'type':'add',
			'title':$("input[name='title']").val(),
			'menuId':menuId,'content':ue.getContent(),
			'num':$("input[name='num']").val() ,
			'start':$("input[name='start']").val() ,
			 'picture':$("input[name='picture']").val() ,
			 'end':$("input[name='end']").val() ,
			 'review':$("input[name='review']").val() ,
			 'valid':$("input[name='valid']").val() 
		};
		 d=loadData(data);//执行
		 if(d){
			 alert("添加成功");
		 }
		 else{
			 alert("添加失败");
		 }
		 	
		}
		
        }
        return false;
    });
    //缩略图片上传及时显示
    $("#thumbpic").change(function(){
        var data = new FormData();      //创建FormData对象
        //为FormData对象添加数据
        $.each($('#thumbpic')[0].files, function(i, file) {
            data.append('thumbpic', file);
        });
        $.ajax({
            url:'../php/activity_upload.php?subtype=thumb ',
            type:'POST',
            data:data,
            cache: false,
            contentType: false,      //不可缺
            processData: false,      //不可缺
            success:function(data){
            	console.log(data);
                if($("#uploadpic").children('img').length == 0) {
                    $("#uploadpic i").remove();             //清除背景图
					$("input[name='picture']").attr("value",data);
                    var $img = $(" <img src='"+data+"' id='imgurl' alt='' onclick=\"getElementById('thumbpic').click()\" />");
                   $("#thumbpic").before($img);
                } else {
                    $("#uploadpic img").remove();           //清除已存在图片
					$("input[name='picture']").attr("value",data);
                    var $img = $(" <img src='"+data+"' alt='' onclick=\"getElementById('thumbpic').click()\" />");
                   $("#thumbpic").before($img);
                }
            }
        });
    });
    
});
function loadData(data){ 
    var  re;
	$.ajax({
            url         :   "../php/votelist.php",
            datatype    :   "json",
            type      :   'GET',         //默认为GET方式
            async       :   false,          //同步
            data        :  data,
            success:function(data) { 
						
				console.log(data);			
                re=JSON.parse(data);
//				console.log(re);
                           
            },
            error:function(e){  
                console.log("请求出错");
            } 
        });
	return re;
}

 
function pageWrite(data){
	  //文章内容
		//$("input[name='id']").val(data.id);
		$("input[name='title']").val(data.title);
		$("input[name='num']").val(data.num);
		$("input[name='start']").val(data.start);
		$("input[name='end']").val(data.end);
		if(data.review){
			$("input[name='review']").attr("checked",true);
		}
		if(data.valid){
			$("input[name='valid']").attr("checked",true);
		}
		
		
		//$("select[name='type']").val(data.type);
		ue.addListener("ready", function () { 
			UE.getEditor('editor').execCommand('insertHtml', data.content);
		});
		//缩略图
		$("#uploadpic i").remove();                     //清除背景图
		$("input[name='picture']").attr("value",data.picture);
			var $img = $(" <img src='"+data.picture+"' id='imgurl' alt='' onclick=\"getElementById('thumbpic').click()\" />");
			$("#thumbpic").before($img);
				
}
 
 //获取url信息
 function GetQueryString(name) { 
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
    var r = window.location.search.substr(1).match(reg); 
    if (r != null) return unescape(r[2]); return null; 
} 

 

