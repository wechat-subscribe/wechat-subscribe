// 跳转函数
function jump(destion) {
    window.location.href=destion;
}
$(function(){
	var page = 1;
	// 动态设置弹出框的位置
	var height = $(window).height();//浏览器当前窗口可视区域宽度 
    var width =  $(window).width(); //浏览器当前窗口可视区域宽度 
    $(".comtent").css({
      "left":(width/2-240/2),
      "top":(height/2-140/2)
    });
	
	//getPageContent(1);      //获取第一页数据
    function getPageContent(page){
        $.ajax({
            url         :   "#",
            //type      :   'POST',         //默认为GET方式
            async       :   true,          //同步
            data        : {
                'type'      : "nav",
                'moduleId'  : 1,
                "page"      : page
            },
            success:function(data) {  
                data = JSON.parse(data);
               	//清空数据 
                $("#navcontainerUlId").html('');
                //添加列表内容
                $.each(data, function(idx, obj) {
                    if(obj.id != undefined && obj.id != null){
                         var  str = '<li class="clearfix"><div class="company"><a href="des.html">';
                        str += ' <h3>山东青岛分公司</h3></a>';
                        str += '<p><i class="fa fa-phone"></i>联系电话：010-61136676  400-690-6676</p></div>';
                        str += ' <div class="gobtn"><button  class="btngo" href="des.html" onclick=javascrtpt:jump("des.html")>我要去</button></div></li>';
                         
                        $("#navcontainerUlId").append(str);
                    }
                });
                // 设置页数
                $("#allpage").html(data.PageNum);
                allpage = data.PageNum;        
            },error:function(e){  
                console.log("请求出错");
            } 
        });
    }

	//搜索模态框
    $(".inputclass").click(function(){
        $(".hideContent,.comtent").css("display","block");
        $(".contextarea input").focus();
    });
    //搜索操作
    $(".btnleft").click(function(){
        $(".hideContent,.comtent").css("display","block");
        return false;
    });
    //取消操作
    $(".btnright").bind("click",function(){
        $(".hideContent,.comtent").css("display","none");
        return false;
    });
});