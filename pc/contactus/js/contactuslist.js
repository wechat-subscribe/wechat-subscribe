;$(function(){
	var moduleId = GetQueryString("moduleId");
	var menuName = escape(GetQueryString("menuName"));
	var submenuName = escape(GetQueryString("submenuName"));
	$("#jumpAddUrl").attr("href","addcompany.html?moduleId="+moduleId+"&menuName="+menuName+"&submenuName="+submenuName);

    var config = {
        articleid:"",	//文章ID
        tr_artilce:""	//表格行父元素
    };
    var page = 1;           //页码标识符
    var allpage = 1;        //总页码标识符
    
    getPageContent(1);      //获取第一页数据

    function getPageContent(page){
        $.ajax({
            url         :   "../php/subcompany.php",
            datatype    :   "json",
            //type      :   'POST',         //默认为GET方式
            async       :   true,          //同步
            data        : {
                'type'      : "checkPage",
                // 'moduleId'  : moduleId,
                "page"      : page
            },
            success:function(data) { 
                // console.log(data);
                if(data.error != 0) {         //数据不为空
                    $("#tbody").html('');
                    // 添加列表内容
                    $.each(data.check, function(idx, obj) {
                        if(obj.id != undefined){
                            var  str = '<tr><td class="checkbox"><input type="checkbox"/></td>';
                            str += '<td><a href="addcompany.html?subcompanyid='+obj.id+'&menuName='+menuName+'&submenuName='+submenuName+'" >'+obj.companyName+'</a></td>';
                            str += '<td>'+obj.address+'</td>';
                            str += '<td>'+obj.phone+'</td>';
                            str += '<td class="actionicon"><a href="addcompany.html?subcompanyid='+obj.id+'&menuName='+menuName+'&submenuName='+submenuName+'" > <i class="fa fa-pencil blue editarticle"></i></a> <i class="fa fa-trash red remove"></i></td>';
                             
                            $("#tbody").append(str);
                        }
                    });
                }else{
                    alert("数据为空");
                }
               
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
        // alert(1);
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
            url         :   "../php/subcompany.php",
            datatype    :   "json",
            //type      :   'POST',         //默认为GET方式
            async       :   true,          //异步
            data        : {
                'type'      : "del",
                "id"      : config.articleid
            },
            success:function(data) {    
                // data = JSON.parse(data);
                // console.log();
                if(data == 1){
                    //将页面元素删除
                    config.tr_artilce.hide();
                    tipTogle("删除成功");
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
    function tipTogle(str){
        str = str != undefined?str:"操作成功";
        $("body").append('<div class="popOperTip">'+str+'</div>');
        $(".popOperTip").fadeToggle(1000);
        setTimeout('$(".popOperTip").fadeToggle(1000)',1500);
    }

});