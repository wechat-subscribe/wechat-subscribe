/*
*vote_project
*投票项列表
*writer ly
*/  
  $(function(){
        //用户设置选项
        $(".useraction").bind("click",function(){
            var $menu = $(this).parent().children(".usermenu");
            var $upmenu = $(this).children("i");
            if($menu.is(":visible")){
                $menu.slideUp();
                $upmenu.removeClass("upmenu");
            }else {
                $menu.slideDown();
                $upmenu.addClass("upmenu");
            }
        });
        //下拉框选项
        $(".navlist li").bind("click",function(){
            var $submenu = $(this).children(".submenu");
            if($submenu.length != 0){
                if($submenu.is(":visible")){
                    $submenu.slideUp();
                    return false;
                }else{
                    $submenu.slideDown();
                    return false;
                }
            }else {
                //这里设置的不对需要修改
                $(this).siblings().removeClass("active");
                $(this).parent().parent().siblings().removeClass("active");
                $(this).parent().parent().siblings().children().removeClass("active");
                $(this).addClass("active");
                //event.stopPropagation();    //  阻止事件冒泡
                return false;
            }
        }); 
				
});//页面加载完毕执行的js结束

//实例化ue编辑器
var content_e = UE.getEditor('content_e',
		  {
			autoHeightEnabled:true,
			initialFrameWidth:700, //初始化宽度
			initialFrameHeight:420, //初始化高度
		  });
		  
  //实例化编辑器
						  var o_ueditorupload = UE.getEditor('j_ueditorupload',
						  {
							autoHeightEnabled:false
						  });
						  o_ueditorupload.ready(function ()
						  {
							 
							o_ueditorupload.hide();//隐藏编辑器
							 
							//监听图片上传
							o_ueditorupload.addListener('beforeInsertImage', function (t,arg)
							{
								  //alert('这是图片地址：'+arg[0].src);
								  $("input[name='picture']").val(arg[0].src);
								   $("#img").attr("src", arg[0].src);
							});
						 
						  });
						   
						  //弹出图片上传的对话框
						  function upImage()
						  {
							var myImage = o_ueditorupload.getDialog("insertimage");
							myImage.open();
						  }
						 
//全局变量
		url="../php/vote.php?handle=showProject";
		url2="../php/vote.php?handle=deleteProject";
		url3="../php/vote.php?handle=newProject";
		data="";
		tmp=0; 
		page=0;
		if(getPar("id")!="false"){
			$("form").append("<input type=\"hidden\" name=\"id\" value=\""+getPar("id")+"\">");
		}
		 load();
		function load(){
			$.post(url,
			{"id":getPar("id")},
			function(d,s){ 
					d=JSON.parse(d);
					$("input[name='title']").val(d.title);
					$("input[name='picture']").val(d.picture);
					 $("#img").attr("src", d.picture);
					//$("textarea[name='content']").val(d.content); 
					content_e.addListener("ready", function () { 
						this.setContent(d.content,false); 
					});
					 
			});
		}
		function submit(){ 
			//alert($("form").serialize());
			$.post(url3,
			$("form").serialize(),
			function(d,s){ //alert(d)
					if(d){
						conlog("提交成功");
						history.go(-1);
						}
			});
		}
		function cancel_(){
			location.reload(true);
		} 
				
//删除操作 
		function del(id){
			$(".hideContent,.comtent").css("display","block");
			tmp=id;
		}
		
//编辑页面
		function edit(){
             window.location.href=""; 
        }
//确认删除操作
		function truedel(){ 
			del_project(tmp);
			init();
		}
//取消操作
        function cancel(){
            $(".hideContent,.comtent").css("display","none");
			init();
            return false;
        }
//list写入页面
		function pageWrite(data){//alert(data); 
			$("#tbody").html("");
			$.each(data,function(k,v){  
				var str=""; 
				str+='   <tr><td class="checkbox">'+(k+1)+'</td>';
				str+=' <td><a href="" name="7">'+v.title+'</a></td>';
				str+='<td>'+v.date+'</td>';
				str+=' <td class="actionicon"><i onClick="edit('+v.Id+')" class="fa fa-pencil blue"></i><i onClick="del('+v.Id+')" class="fa fa-trash red"></i></td> </tr>';
				$("#tbody").append(str);          
			});     
			 
		}
//ajax数据传输
 
//删除
function del_project(id){
	$.get(
	url2+"&&id="+id,
	function(d,s){
		if(s){
			alert("删除成功 ");
			location.reload(true);
		}
		else{
			console.log("删除失败");
		}
	}
	);
}
//获取get参数
function getPar(par){
    //获取当前URL
    var local_url = document.location.href; 
    //获取要取得的get参数位置
    var get = local_url.indexOf(par +"=");
    if(get == -1){
        return false;   
    }   
    //截取字符串
    var get_par = local_url.slice(par.length + get + 1);    
    //判断截取后的字符串是否还有其他get参数
    var nextPar = get_par.indexOf("&");
    if(nextPar != -1){
        get_par = get_par.slice(0, nextPar);
    }
    return get_par;
}
 
//调试日志
		function conlog(str)
		{
			alert(str);
		}