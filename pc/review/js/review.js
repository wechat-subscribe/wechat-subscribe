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
    var projectName = 1;            //搜索类型
    var config = {
        articleid:"",	//文章ID
        tr_artilce:"",	//表格行父元素
        currentEle:""   //当前元素
    }

    var page = 1;           //页码标识符
    var allpage = 1;        //总页码标识符
    
    getPageContent(1,projectName);      //获取第一页投票工程的Id

    function getPageContent(page,projectId){
        $.ajax({
            url         :   "../php/activity_review.php",
            datatype    :   "json",
            type        :   'POST',         //默认为GET方式
            async       :   true,          //异步
            data        : {
                'type'      : "list",
                'id'  : projectId,
                'list_type':'manager_show',
                "page"      : page
            },
            success:function(data) {  
                // projectName = data.details_dataBase;   //往期回顾类型
                // data = JSON.parse(data);
                // 清空内容
                $("#tbody").html('');
                //添加列表内容
                $.each(data.list, function(idx, obj) {
                    if(obj.id != undefined){
                         var  str = '<tr><td class="checkbox"><input type="checkbox"/></td>';
                        str += '<td><a href="javascript:void(0);">'+obj.title+'</a></td>';
                        if(projectName == 1){
                            str += '<td>投票活动</td>';
                        }else if(projectName == 2){
                            str += '<td>图片互动</td>';
                        }
                        if(obj.review == 0){
                            str += '<td><span class="noopertion">未设置</span></td>';
                            str += '<td class="actionicon"><a href="javascript:void(0);" name="'+obj.id+'" class="plusAction"><i class="fa fa-plus-circle blue"></i></a></i></td>';
                        }else if(obj.review == 1){
                            str += '<td><span class="opertion">已设置</span></td>';
                            str += '<td class="actionicon"><a href="javascript:void(0);" name="'+obj.id+'" class="resetAction"><i class="fa fa-reply red"></i></a></td>';
                        }    
                        // str += '<td class="actionicon"><a href="addarticle.html?articleid='+obj.id+'" name="'+obj.id+'"> <i class="fa fa-plus-circle blue editarticle"></i></a> <i class="fa fa-reply red"></i></td>';
                        $("#tbody").append(str);
                    }
                });

                // // 设置页数
                allpage =  Math.ceil(data.num/10);
                $("#allpage").html(allpage);   
            },
            error:function(e){  
                console.log("请求出错");
            } 
        });
    }
    $("#searchproId").bind("click",function(){
        var projectId = $(".selecctopr").val();
        getPageContent(1,projectId);        //获取第一页数据
        page = 1;                           //重置page为1
        projectName = projectId;
    });
    

    // 点击下一页操作
    $("#nextpage").bind("click",function(){
        if(!$(this).hasClass('disable')){
            page++;
            if(page <= allpage){
                getPageContent(page,projectName);
                
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
                getPageContent(page,projectName);
                 
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
                getPageContent(page,projectName);
               
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
                getPageContent(page,projectName);
              
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
    
    //撤销往期回顾模态框
    $("body").delegate("#delid,tr td a.resetAction","click",function(){
        $(".hideContent,.comtent").css("display","block");
        
        config.articleid = $(this).attr("name");
        config.tr_artilce = $(this).parent().parent();
        config.currentEle = $(this);
    });
    //确定撤销操作
    $(".btnleft").click(function(){
        // 取消模态框
        $(".hideContent,.comtent").css("display","none");
        
        $.ajax({
            url         :   "../php/activity_review.php",
            datatype    :   "json",
            type        :   'POST',         //默认为GET方式
            async       :   true,          //异步
            data        : {
                'type'      : "list",
                'id'        : projectName,    //projectId == projectName
                'list_type' :'manager_delete',
                "projectid" : config.articleid,
                'review'    :1
            },
            success:function(data) {
                if(data.error == 1){
                    // config.tr_artilce.hide();
                    config.currentEle.removeClass("resetAction").addClass("plusAction");
                    config.currentEle.empty().append('<i class="fa fa-plus-circle blue"></i>');
                    config.currentEle.parent().prev().html('<span class="noopertion">未设置</span>');
                    // alert("撤销往期回顾成功");
                    // $(".tip").fadeToggle(3000);
                    tipTogle();
                    // setTimeout(tipTogle,1500);
                }else {
                    alert("撤销往期回顾失败");
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

    // 添加往期回顾操作
    $("body").delegate("tr td a.plusAction","click",function(){
        config.articleid = $(this).attr("name");
        config.tr_artilce = $(this).parent().parent();
        config.currentEle = $(this);

         $.ajax({
            url         :   "../php/activity_review.php",
            datatype    :   "json",
            type        :   'POST',         //默认为GET方式
            async       :   true,          //异步
            data        : {
                'type'      : "list",
                'id'        : projectName,    //projectId == projectName
                'list_type' :'manager_modify',
                "projectid" : config.articleid,
                'review'    :0
            },
            success:function(data) {
                if(data.error == 1){
                    config.currentEle.removeClass("plusAction").addClass("resetAction");
                    config.currentEle.empty().append('<i class="fa fa-reply red"></i>');
                    config.currentEle.parent().prev().html('<span class="opertion">已设置</span>');
                    // alert("添加往期回顾成功")
                    tipTogle();
                    // setTimeout(tipTogle,1000);
                }else {
                    alert("添加往期回顾失败");
                }
            },
            error:function(e){  
                console.log("请求出错");
                console.log(e);
            } 
        });
        return false;
    });
    // 设置弹出框
    function tipTogle(){
        $("body").append('<div class="popOperTip">操作成功</div>');
        $(".popOperTip").fadeToggle(1000);
        setTimeout('$(".popOperTip").fadeToggle(1000)',1500);

        // fadeToggle();
        // function fadeToggle(){
        //     if(!$(".popOperTip").is(":animated")){
        //          $(".popOperTip").stop(true).animate();
        //     } else{
        //          $(".popOperTip").fadeToggle(1000);
        //     }
        // }
        // setTimeout(fadeToggle,1500);
    }
});