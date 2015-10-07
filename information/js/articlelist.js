/**
 * Created by sun on 2015/9/29.
 */
$(function(){
    var page = 1;
    $.ajax({
        url         :   "../php/information_show.php",
        datatype    :   "json",
        //type      :   'POST',         //默认为GET方式
        async       :   false,          //同步
        data        : {
            'type'      : "list",
            'moduleId'  : 1,
            "page"      : page
        },
        success:function(data) {  
            console.log(data);
           
            $.each(JSON.parse(data), function(idx, obj) {
            
                var  str = '<tr><td class="checkbox"><input type="checkbox"/></td>';
                str += '<td><a href="addarticle.html?articleid='+obj.id+'">'+obj.title+'</a></td>';
                str += '<td>'+obj.date+'</td>';
                str += '<td class="actionicon"><a href="addarticle.html?articleid='+obj.id+'"> <i class="fa fa-pencil blue editarticle"></i></a> <a href="addarticle.html?articleid='+obj.id+'"><i class="fa fa-trash red remove"></i></a></td>';
                console.log(obj.title);
                $("#tbody").append(str);
            });
                       
        },
        error:function(e){  
            console.log("请求出错");
        } 
    });

    // $("i.remove").bind("click",function(){
        
       
    // });


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
    $("#delid,tr td .fa-trash").click(function(){
        $(".hideContent,.comtent").css("display","block");
        //获取文章id
//            var test = $(this).parents().siblings().children("a").attr("name");
//            console.log(test);
    });

    //删除操作
    $(".btnleft").click(function(){
        $(".hideContent,.comtent").css("display","block");
        return false;
    });
    //取消操作
    $(".btnright").click(function(){
        $(".hideContent,.comtent").css("display","none");
        return false;
    });

});