/*
*vote_list
*投票列表
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
        //pageline script
        //设置背景颜色
        $("tbody tr").bind("mouseover",function(){
            $(this).addClass("hover");
        }).bind("mouseout",function(){
            $(this).removeClass("hover");
        });
        //设置选中状态
        $("tbody tr").bind("click",function(){
            $(this).siblings().removeClass("active").find("input").prop("checked", false);
            $(this).addClass("active").find("input").prop("checked", true);
        });
        //设置全选/不选状态
        $("#checkedall").click(function(){
            if(this.checked){
                $("tbody tr td").addClass("active");    //设置背景色
                $("tbody tr").addClass("active").find("input").prop("checked", true);
            }else{
                $("tbody tr").removeClass("active").find("input").prop("checked", false);
                $("tbody tr td").removeClass("active");
            }
        }); 
	
  
 
				
});//页面加载完毕执行的js结束
//全局变量
		url="../php/vote.php?handle=showProject";
		url2="../php/vote.php?handle=deleteProject";
		data="";
		tmp=0; 
		page=0;
		init();
//初始化操作
		function init(){
			 tmp=0;
			 if(getPar("page")<=0){
				 page=0;
			 }
			 $.post(url,
				{"page":page},
				function(d,s){ 
				d=JSON.parse(d);
				//alert(d);
				 pageWrite(d);
				 
				}
					
			 ); 
			
		}
        
//删除操作 
		function del(id){
			$(".hideContent,.comtent").css("display","block");
			tmp=id;
		}
		
//编辑页面
		function edit(id){
             window.location.href="./vote_project.html?id="+id; 
        }
//管理页面
		function edit1(id){
             window.location.href="./vote_option_list.html?voteId="+id; 
        }
//确认删除操作
		function truedel(){ 
			del_project(tmp);
			
		}
//取消操作
        function cancel(){
            $(".hideContent,.comtent").css("display","none");
			init();
            return false;
        }
//list写入页面
		function pageWrite(data){ 
			$("#tbody").html("");
			$.each(data,function(k,v){  
				var str=""; 
				str+='   <tr><td class="checkbox">'+(k+1)+'</td>';
				str+=' <td><a onClick="edit('+v.id+')" name="7">'+v.title+'</a></td>';
				str+='<td>'+v.date+'</td>';
				str+=' <td class="actionicon"><i onClick="edit('+v.id+')"  title ="编辑投票项" class="fa fa-pencil blue"></i><i onClick="edit1('+v.id+')" title="管理投票项" class="fa fa-list yellow"></i><i onClick="del('+v.id+')" title="删除投票项" class="fa fa-trash red"></i></td> </tr>';
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