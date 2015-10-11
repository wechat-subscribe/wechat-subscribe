$(function(){

    var config = {
        basePath   : '/wechat-subscribe/pc/'
    };


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
    //左侧导航下拉框选项
    $(".navlist li").bind("click",function() {
        var $submenu = $(this).children(".submenu");
        if ($submenu.length != 0) {
            if ($submenu.is(":visible")) {
                $submenu.slideUp();
            } else {
                $submenu.slideDown();
            }
            return false;           //  阻止事件冒泡和事件默认属性
        }
        else {
//                $(this).siblings().removeClass("active");
//                $(this).siblings().children().children().removeClass("active");
//                $(this).parent().parent().siblings().removeClass("active");
//                $(this).parent().parent().siblings().children().children().removeClass("active");
//                $(this).addClass("active");
            event.stopPropagation();    //  阻止事件冒泡
        }
    });

})
