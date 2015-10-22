/**
 * Created by sun on 2015/9/29.
 */
// 模块拖拽  
$(function(){  
	var _move=false;//移动标记  
	var _x,_y;//鼠标离控件左上角的相对位置
	
    $(".conheader").click(function(){  
        	//alert("click");//点击（松开后触发）  
        }).mousedown(function(e){  
        _move=true;  
        _x=e.pageX-parseInt($(".comtent").css("left"));  
        _y=e.pageY-parseInt($(".comtent").css("top"));  
        $(".comtent").fadeTo(20, 0.7);//点击后开始拖动并透明显示  
        return false;
    });  
    $(".hideContent").mousemove(function(e){  
        if(_move){  
            var x=e.pageX-_x;//移动时根据鼠标位置计算控件左上角的绝对位置  
            var y=e.pageY-_y;  
            $(".comtent").css({top:y,left:x});//控件新位置  
        }  
    }).mouseup(function(){  
		_move=false;  
		$(".comtent").fadeTo("fast", 1);//松开鼠标后停止移动并恢复成不透明 
	});  
});  
$(function(){
	var moduleId = GetQueryString("moduleId");
	var menuName = escape(GetQueryString("menuName"));
	var submenuName = escape(GetQueryString("submenuName"));
	$("#jumpAddUrl").attr("href","addarticle.html?moduleId="+moduleId+"&menuName="+menuName+"&submenuName="+submenuName)

    var config = {
        articleid:"",	//文章ID
        tr_artilce:""	//表格行父元素
    }
    var page = 1;           //页码标识符
    var allpage = 1;        //总页码标识符
    
    getPageContent(1);      //获取第一页数据

    function getPageContent(page){
        $.ajax({
            url         :   "../php/information_show.php",
            datatype    :   "json",
            //type      :   'POST',         //默认为GET方式
            async       :   true,          //同步
            data        : {
                'type'      : "list",
                'moduleId'  : moduleId,
                "page"      : page
            },
            success:function(data) {  
                data = JSON.parse(data);
                // console.log(data);
                $("#tbody").html('');
                //添加列表内容
                $.each(data, function(idx, obj) {
                    if(obj.id != undefined){
                         var  str = '<tr><td class="checkbox"><input type="checkbox"/></td>';
                        // str += '<td><a href="addarticle.html?articleid='+obj.id+'">'+obj.title+'</a></td>';
                        str += '<td><a href="addarticle.html?articleid='+obj.id+'&menuName='+menuName+'&submenuName='+submenuName+'" >'+obj.title+'</a></td>';
                        str += '<td>'+obj.date+'</td>';
                        str += '<td class="actionicon"><a href="addarticle.html?articleid='+obj.id+'" name="'+obj.id+'"> <i class="fa fa-pencil blue editarticle"></i></a> <i class="fa fa-trash red remove"></i></td>';
                         
                        $("#tbody").append(str);
                    }
                   
                });
                // 设置页数
                $("#allpage").html(data.PageNum);
                allpage = data.PageNum;
                           
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
        
        config.articleid = $(this).siblings().prop("name");
        config.tr_artilce = $(this).parent().parent();
    });

    //删除操作
    $(".btnleft").click(function(){
        // 取消模态框
        $(".hideContent,.comtent").css("display","none");

        $.ajax({
            url         :   "../php/information_show.php",
            datatype    :   "json",
            //type      :   'POST',         //默认为GET方式
            async       :   true,          //异步
            data        : {
                'type'      : "deleteInfo",
                "infoId"      : config.articleid
            },
            success:function(data) {    
                data = JSON.parse(data);
                console.log();
                if(data == 1){
                    //将页面里文章删除
                    config.tr_artilce.hide();
                    tipTogle();
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

    // 设置弹出框
    function tipTogle(){
        $("body").append('<div class="popOperTip">操作成功</div>');
        $(".popOperTip").fadeToggle(1000);
        setTimeout('$(".popOperTip").fadeToggle(1000)',1500);
    }

});