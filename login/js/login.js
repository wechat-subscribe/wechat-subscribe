/**
 * Created by sun on 2015/9/29.
 */
$(document).ready(function(){
    //随机背景图片
    var random_bg=Math.floor(Math.random()*5+1);
    var bg='url(../img/loginbg_'+random_bg+'.jpg)';
    $("body").css("background-image",bg);

    //移除验证码
    $("#hide").click(function(){
        $(".code").fadeOut("slow");
        return false;
    });
    //显示验证码
    $("#captcha").focus(function(){
        $(".code").fadeIn("fast");
        return false;
    });

    var loginUrl = "";

    $("#user_name,#password,#captcha").focus(function(){
        $(".usernameinfo,.pwdinfo,.modiinfo,.modifierror,.pwderror").hide(200);
    });

    $(".submit").bind("click",function(){
        var username = $("#user_name").val();
        var password = $("#password").val();
        var modifi = $("#captcha").val();
        
        console.log(username);
        console.log(password);

        if(username == ''){
            $(".usernameinfo").show(200);
            return ;
        }else if(password == ''){
            $(".pwdinfo").show(200);
            return ;
        }else if(modifi == ''){
            $(".modiinfo").show(200);
            return ;
        }

        $.ajax({
            url         :   "../php/login.php",
            datatype    :   "json",
            // async       :   true,           //异步
            data        : {
                'number'    : username,
                'pwd'       : password
            }, success:function(data) {
                console.log(data);
                if(data.error == 1){
                    $.session.set('username', data.adminName);
                    //$.session.clear();
                    //$.session.get('username');
                    //$.session.remove('username');
                    console.log("登陆成功");

                    //window.location.href="./index.html";

                }else if(data.error == 0){
                    $(".pwderror").show(200);
                }
            }, error:function(e){
                console.log("请求出错");
            }
        });
    });

});