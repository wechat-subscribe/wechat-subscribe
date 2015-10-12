

var video, $output;
var scale = 0.25; 
video = $("#videoShow").get(0);
 
function imgUrl(){
	var canvas = document.createElement("canvas");
	canvas.width = video.videoWidth * scale;
	canvas.height = video.videoHeight * scale;
	canvas.getContext('2d')
	.drawImage(video, 0, 0, canvas.width, canvas.height);
	return canvas.toDataURL();
}

$("#capture").click(function(){
	//console.log(imgUrl());
 
		$("#uploadpic img").remove();           //清除已存在图片
		$("input[name='thumb']").attr("value",imgUrl());
		var $img = $(" <img src='"+imgUrl()+"' id='imgurl' alt=''   />");
		$("#uploadpic").append($img);
	 
});

$("input[name='video']").on("change",function(){ 
	  var data = new FormData();      //创建FormData对象
        //为FormData对象添加数据
        $.each(this.files, function(i, file) {
            data.append('upload_file', file);
        });  
	 $.ajax({
            url:'../php/information_upload.php?subtype=upvideo',
            type:'POST',
            data:data,
            cache: false,
            contentType: false,      //不可缺
            processData: false,      //不可缺
            success:function(data){
            	console.log(data);
				if(data){
					$("#videoShow").append("<source src="+data+"></source>");
				}
                
            }
        });
 
}); 
 
//获取url信息
 function GetQueryString(name) { 
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
    var r = window.location.search.substr(1).match(reg); 
    if (r != null) return unescape(r[2]); return null; 
}  

$(function(){

    var ue = UE.getEditor('editor');	//实例化UE编辑器
    
    var articleid = GetQueryString("articleid") ;
    if(articleid != null){

        // 请求文章内容
        $.ajax({
            url         :   "../php/information_show.php",
            datatype    :   "json",
            //type      :   'POST',         //默认为GET方式
            async       :   false,          //异步
            data        : {
                'type'      : "updateInfo",
                'infoId'    : articleid,
            },
            success:function(data) {  
                data = JSON.parse(data);
                //文章内容
                $("#title").val(data.title);
                ue.addListener("ready", function () {
                    //ue.setContent(data.content);
                    //UE.getEditor('editor').execCommand('insertHtml', data.content);
                    UE.getEditor('editor').execCommand('insertHtml', data.content);
                });
                //缩略图
                $("#uploadpic i").remove();                     //清除背景图
                    var $img = $(" <img src='"+data.thumb+"' id='imgurl' alt='' onclick=\"getElementById('thumbpic').click()\" />");
                    $("#uploadpic input").before($img);
                
                if(data.is_leaveword){
                      $("#leaveword").attr("checked","checked");
                }
                if(data.is_zan){
                    $("#zan").attr("checked","checked");
                }   
            },
            error:function(e){  
                console.log("请求出错");
            } 
        });
        
        // 请求评论内容
        $.ajax({
            url         :   "../php/information_show.php",
            datatype    :   "json",
            //type      :   'POST',         //默认为GET方式
            async       :   false,          //异步
            data        : {
                'type'      : "details",
                'infoId'    : articleid,
                'page'      :1
            },
            success:function(data) {  
                data = JSON.parse(data);
                console.log(data);
                if(data.num_leaveword == 0){
                    $(".nocomment").css("display","block");
                }else{
                
                      $.each(data.leaveword,function(i,item){
                            var str = '<tr><td class="checkbox"><input type="checkbox"/></td>';
                            str += '<td><a href="">'+item.content+'</a></td>';
                            str += ' <td>'+item.date+'</td>';
                            str += '<td class="actionicon"> <i class="fa fa-trash red"></i></td></tr>';

                            $("#commenttbody").append(str);
                      });
                    $(".pageinput").val("1");
                   
                    $(".tablediv").show();

                }
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
        //console.log(getAllHtml());

        var title 		= $("#title").val();  //标题
		var thumb       = $("input[name='thumb']").val(); 
        var leaveword 	= ($("#leaveword").attr("checked")=="checked")?1:0;
        var zan 		= ($("#zan").attr("checked")=="checked")?1:0;
        var content 	= UE.getEditor('editor').getPlainTxt();    
        //var content2 = UE.getEditor('editor').getAllHtml();
        if(title == '' || content == ''){
        	alert("请检查数据不能为空");
        }else{
            if(articleid != null){
                $.ajax({
                    url         :   "../php/information_show.php?type=updateInfoOK",
                    datatype    :   "json", 
                    async       :   true,          //异步
                    data        : {
                        'infoId'        : articleid,
                        "title"         : title,
                        "is_leaveword"  : leaveword,
                        "is_zan"        : zan,
                        "thumb"        : thumb,
                        "content"       : content
                    },
                    success:function(data) {  
                        console.log(data);
                        if(data == 1){
                            alert("更新成功");
                        }else if(data == 2){
                            alert("请检查数据不能为空");
                        }else if(data == 0){
                            alert("更新失败");
                        }
                       
                    },
                    error:function(e){  
                        console.log("请求出错");
                    }
                });
            }else{
                $.ajax({
                    url         :   "../php/information_upload.php",
                    datatype    :   "json", 
                    type        :   'POST',         
                    async       :   true,          //异步
                    data        : {
                        "moduleId"      : 2,
                        "title"         : title,
                        "leaveword"  : leaveword,
                        "zan"        : zan,
						"thumb"        : thumb,
                        "content"       : content
                    },
                    success:function(data) {  
                        console.log(data);
                        if(data == 1){
                            alert("添加成功");
                        }else if(data == 2){
                            alert("请检查数据不能为空");
                        }else if(data == 0){
                            alert("添加失败");
                        }
                       
                    },
                    error:function(e){  
                        console.log("请求出错");
                    }
                });
            }
        	
        }
        return false;
    });

    //缩略图片上传及时显示
    $("#thumbpic").change(function(){
        var data = new FormData();      //创建FormData对象
        //为FormData对象添加数据
        $.each($('#thumbpic')[0].files, function(i, file) {
            data.append('upload_file', file);
        });
        $.ajax({
            url:'../php/information_show.php?type=updateInfoOK&subtype=thumb',
            type:'POST',
            data:data,
            cache: false,
            contentType: false,      //不可缺
            processData: false,      //不可缺
            success:function(data){
            	console.log(data);
                if($("#uploadpic").children('img').length == 0) {
                    $("#uploadpic i").remove();             //清除背景图
                    var $img = $(" <img src='"+data+"' id='imgurl' alt='' onclick=\"getElementById('thumbpic').click()\" />");
                    $("#uploadpic input").before($img);
                } else {
                    $("#uploadpic img").remove();           //清除已存在图片
                    var $img = $(" <img src='"+data+"' alt='' onclick=\"getElementById('thumbpic').click()\" />");
                    $("#uploadpic input").before($img);
                }
            }
        });
    });
    
});

 