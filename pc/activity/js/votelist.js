
var page = 1;   
menuId=10;

 /*
 *
 *
 *
 */
 data={'type':'list','page':1,'menuId':menuId};
var d=loadData(data);
pageWrite(d);


function loadData(data){ 
    var  re;
	$.ajax({
            url         :   "../php/votelist.php",
            datatype    :   "json",
            type      :   'GET',         //默认为GET方式
            async       :   false,          //同步
            data        :  data,
            success:function(data) { 
                console.log(data);	/////////////////////////////////////////////////////////////		
                re=JSON.parse(data);
				
             
                           
            },
            error:function(e){  
                console.log("请求出错");
            } 
        });
	return re;
}


function pageWrite(data){
	 var str="";
	 i=(page-1)*10+1;
	 $("#tbody").html(str);
	 $.each(data.list, function(idx, obj) { 
						href1="vote_option_list.html?voteId=";
						href2="../front/vote1.html?voteId=";
                        str = '<tr><td class="checkbox">'+ i++ +'</td>';
                        str += '<td><span  >'+obj.title+'</span></td>'; 
                       
                        str += '<td>'+obj.start+'</td>';
                        str += '<td>'+obj.end+'</td>';
						 str += '<td>'+runRadio(1)+'</td>';
                        str += '<td class="actionicon"><a  onClick="add_('+obj.id+')" name="'+obj.id+'"> <i title="编辑" class="fa fa-pencil blue editarticle"></i></a>';
						str += '<a href="'+href1+obj.id+'"   ><i title="管理下一级"  class="fa fa-list   "></i></a> ';
						str += '<a href="'+href2+obj.id+'"   ><i title="管理前台"  class="fa fa-paypal   "></i></a> '; 
						str += '<i class="fa fa-trash red remove"   title="删除"></i></td>';
                         
                        $("#tbody").append(str);
                    
	});
	// 设置页数
				allpage=data.PageNum;
                $("#allpage").html(data.PageNum);
                //page = data.PageNum;
				
}

 // 点击下一页操作
    $("#nextpage").bind("click",function(){ //alert("下一页"+page);
        if(!$(this).hasClass('disable')){
            page++;
            if(page <= allpage){
				
                data={'type':'list','page':page,'menuId':menuId};
				var d=loadData(data);
				pageWrite(d);
                
                //列表末尾页
                if(page == allpage){
                    $("#nextpage").addClass("disable");
                    $("#lastpage").addClass("disable");
                }
                //设置当前页码
                $("#pageconnum").val(page);
                //前一页可以使用
                if(page != 1){
                    $("#firstpage").removeClass("disable");
                    $("#prepage").removeClass("disable");
                }
            }
        }
        
    });
    // 点击上一页操作
    $("#prepage").bind("click",function(){
        if(!$(this).hasClass('disable')){
            page--;
            if(page >= 1){
				
                data={'type':'list','page':page,'menuId':menuId};
				var d=loadData(data);
				pageWrite(d);
                 
                //列表末尾页
                if(page == 1){
                    $("#firstpage").addClass("disable");
                    $("#prepage").addClass("disable");
                }
                //设置当前页码
                $("#pageconnum").val(page);
                //下一页可以使用
                 
                $("#lastpage").removeClass("disable");
                $("#nextpage").removeClass("disable");
                
            }
        } 
    });
    // 首页操作
    $("#firstpage").bind("click",function(){
        if(!$(this).hasClass('disable')){
            page=1;
            if(page >= 1){
				
                data={'type':'list','page':page,'menuId':menuId};
				var d=loadData(data);
				pageWrite(d);
               
                //列表末尾页
                if(page == 1){
                    $("#firstpage").addClass("disable");
                    $("#prepage").addClass("disable");
                }
                //设置当前页码
                $("#pageconnum").val(page);
                //下一页可以使用
                $("#lastpage").removeClass("disable");
                $("#nextpage").removeClass("disable");
            }
        } 
    });
    // 末尾页操作
    $("#lastpage").bind("click",function(){
        if(!$(this).hasClass('disable')){
            page=allpage;
            if(page <= allpage){
                
				data={'type':'list','page':page,'menuId':menuId};
				var d=loadData(data);
				pageWrite(d);
              
                //列表末尾页
                $("#lastpage").addClass("disable");
                $("#nextpage").addClass("disable");
                 
                //设置当前页码
                $("#pageconnum").val(page);
                //上一页可以使用
                $("#firstpage").removeClass("disable");
                $("#prepage").removeClass("disable");
            }
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
    
    //删除模态框
     $("body").delegate("#delid,tr td .fa-trash","click",function(){
        $(".hideContent,.comtent").css("display","block");
        //获取文章id
        var activityid = $(this).siblings().prop("name");
        var $tr_artilce = $(this).parent().parent();
        console.log(activityid);

		//发送第一次删除请求
		
		 data={'type':'update','projectId':activityid}; //alert(GetQueryString("projectId"));
		 da=loadData(data); 
		 $("#html").html(da.content);	
		 
		var str="";
		$("#html").find("img").each(function(){  str+=this.src+"|";   }) ; 
		//alert(str);
		
		
		
		
		
        //发送第二次删除请求
        $(".btnleft").click(function(){
            
            $(".hideContent,.comtent").css("display","none");alert(data);
			data={"type":"delete","id":id};
			var d=loadData(data);  
			 alert(data);
             return false;
        });
        //取消操作
        $(".btnright").click(function(){
            $(".hideContent,.comtent").css("display","none");
            return false;
        });
    });


function add_(id){
	//menuId=GetQueryString('menuId');
	
	//var url = window.document.location.href.toString();
    //var u = url.split("?") 
	if(id==undefined){
	location.href= "./addvote.html?menuId="+menuId;
		
	}else{
	location.href= "./addvote.html?menuId="+menuId+"&&projectId="+id;
	
	}
}

function runRadio(id){
				//data={'type':'list','page':page,'menuId':menuId};
				//var d=loadData(data);
				//pageWrite(d);
				return "button";
	
}
 //获取url信息
 function GetQueryString(name) { 
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
    var r = window.location.search.substr(1).match(reg); 
    if (r != null) return unescape(r[2]); return null; 
} 

 
