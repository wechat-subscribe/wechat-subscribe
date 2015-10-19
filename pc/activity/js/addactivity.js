 
//获取url信息
 function GetQueryString(name) { 
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
    var r = window.location.search.substr(1).match(reg); 
    if (r != null) return unescape(r[2]); return null; 
} 
 //获取活动类型
var type; 
$.ajaxSetup({ 
    async : false 
});  
$.post(
	"../php/activity.php?handle=activitycate",
			{
                'handle'      : "activitycate",  
            },
			function(d,s){
				type=JSON.parse(d);//alert(type);
				var str="";
				$.each(type,function(k,v){
					str+=" <option value="+v.id+">"+v.name+"</option> "; 
				});
				$("select[name='type']").html(str);
			}
	);
	
$(function(){

    var ue = UE.getEditor('editor');	//实例化UE编辑器
    $("input[name='voteId']").val(GetQueryString("voteId"));//设置voteid
    var activityid = GetQueryString("activityid") ;//alert(activityid);
    if(activityid != null){
           
        // 请求文章内容
        $.ajax({
            url         :   "../php/activity.php?handle=activitylist",
            datatype    :   "json",
            type      :   'POST',         //默认为GET方式
            async       :   false,          //异步
            data        : {
                'handle'      : "activitylist",
				"id"           :  activityid, 
            },
            success:function(data) { // alert(data);
                data = JSON.parse(data);
                //文章内容
                $("input[name='id']").val(data.id);
                $("input[name='title']").val(data.title);
				$("select[name='type']").val(data.type);
                ue.addListener("ready", function () {
                    
                    UE.getEditor('editor').execCommand('insertHtml', data.content);
                });
                //缩略图
                $("#uploadpic i").remove();                     //清除背景图
				$("input[name='picture']").attr("value",data.picture);
                    var $img = $(" <img src='"+data.picture+"' id='imgurl' alt='' onclick=\"getElementById('thumbpic').click()\" />");
                    $("#thumbpic").before($img);
                
                
            },
            error:function(e){  
                console.log("请求出错");
            } 
        });
      

    }else{
        $(".nocomment").css("display","block");
    }

    //切换tab
    $("a[data-toggle=tab]").bind("click",function(){
        $(this).parent().siblings().find("a").removeClass("active");
        $(this).addClass("active");
        if($(this).attr("href") == "article"){
            $(".comment").hide();
            $(".article").show();
        }else{
            $(".article").hide();
            $(".comment").show();
        }
        return false;
    });


    $("#submit").bind("click",function(){ 
        $("input[name='content']").val(UE.getEditor('editor').getPlainTxt());    
        //var content2 = UE.getEditor('editor').getAllHtml();
        if($("input[name='title']").val() == '' || $("input[name='content']").val() == ''){
        	alert("请检查数据不能为空");
        }else{
		
                $.post("../php/activity_upload.php?handle=newProject",
					 $("form").serialize(),
					 function(data) {  
                        console.log(data);
                        if(data == 1){
                            alert("更新成功");
                        }else if(data == 2){
                            alert("请检查数据不能为空");
                        }else if(data == 0){
                            alert("更新失败");
                        }
                       
                    }
				); 
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