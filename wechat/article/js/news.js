$(function(){

	// 显示评论
	$("#contentid").click(function(){
        $(".hideContent,.comtent").css("display","block");
    });
    //提交评论
    $(".btnleft").click(function(){
        $(".hideContent,.comtent").css("display","none");
        return false;
    });
    //取消评论
    $(".btnright").click(function(){
        $(".hideContent,.comtent").css("display","none");
        return false;
    });
});