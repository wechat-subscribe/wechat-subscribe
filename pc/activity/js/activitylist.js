 var page = 1;           //页码标识符
 var allpage = 1;        //总页码标识符
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
				
			}
	);
	
$(function(){
	
    
    getPageContent(1);      //获取第一页数据

    function getPageContent(page){
        $.ajax({
            url         :   "../php/activity.php?handle=activitylist",
            datatype    :   "json",
            type      :   'POST',         //默认为GET方式
            async       :   false,          //同步
            data        : {
                'handle'      : "activitylist", 
                "page"      : page
            },
            success:function(data) {  
                data = JSON.parse(data);
                // console.log(data);
                $("#tbody").html('');
                //添加列表内容
				i=(page-1)*10+1;
                $.each(data, function(idx, obj) {
                    if(obj.id != undefined){
                         var  str = '<tr><td class="checkbox">'+ i++ +'</td>';
                        str += '<td><span onClick="typeToUrl('+obj.type+','+obj.id+')">'+obj.title+'</span></td>';
                        str += '<td> '+typeToString(obj.type)+'</td>';
                        str += '<td>'+obj.date+'</td>';
                        str += '<td class="actionicon"><a href="addactivity.html?activityid='+obj.id+'" name="'+obj.id+'"> <i title="编辑" class="fa fa-pencil blue editarticle"></i></a>';
						str += '<i onClick="typeToUrl('+obj.type+','+obj.id+')" class="fa fa-list yellow " title="管理下一级"></i> ';
						str += '<i onClick="typeToFront('+obj.type+','+obj.id+')" class="fa fa-paypal yellow " title="查看"></i> ';
						str += '<i class="fa fa-trash red remove" title="删除"></i></td>';
                         
                        $("#tbody").append(str);
                    }
                   
                });
                // 设置页数
				allpage=data.sum;
                $("#allpage").html(data.sum);
                page = data.page;
                           
            },
            error:function(e){  
                console.log("请求出错");
            } 
        });
    }
    

    // 点击下一页操作
    $("#nextpage").bind("click",function(){ 
        if(!$(this).hasClass('disable')){
            page++;
            if(page <= allpage){
                getPageContent(page);
                
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
                getPageContent(page);
                 
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
                getPageContent(page);
               
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
                getPageContent(page);
              
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

        //删除操作
        $(".btnleft").click(function(){
            // 取消模态框
            $(".hideContent,.comtent").css("display","none");

            $.ajax({
                url         :   "../php/activity.php?handle=deleteactivity",
                datatype    :   "json",
                type      :   'POST',         //默认为GET方式
                async       :   false,          //同步
                data        : {
                    'handle'      : "deleteactivity",
                    "id"      : activityid
                },
                success:function(data) {    
                    data = JSON.parse(data);
                    if(data == 1){
                        //将页面里文章删除
                        $tr_artilce.hide();
                        alert("删除成功");
                    }else {
                        alert("删除失败");
                    }
                },
                error:function(e){  
                    console.log("请求出错");
                } 
            });
            return false;
        });
        //取消操作
        $(".btnright").click(function(){
            $(".hideContent,.comtent").css("display","none");
            return false;
        });
    });


});

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

function typeToString(id){
	 var restring;
	 $.each(type,function(k,v){
		 //alert(id+"__"+v.id+"__"+v.name);
		 if(id==v.id){
			 restring = v.name;
		 }
	 }) ;
	 return restring;
}
function typeToUrl(t,id){
	 var restring;
	 $.each(type,function(k,v){
		 //alert(id+"__"+v.id+"__"+v.name);
		 if(t==v.id){
			 restring = v.url+id;
		 }
	 }) ;
	 location.href=restring;
	 // window.open(restring); 
} 

function typeToFront(t,id){
	 var restring;
	 $.each(type,function(k,v){
		 //alert(id+"__"+v.id+"__"+v.name);
		 if(t==v.id){
			 restring = v.fronturl+id;
		 }
	 }) ;
	 window.open(restring); 
	 //location.href=restring;
} 