$(function(){
	var validflage = false;
	$("#oldpasswd").bind("blur",function(){
		//if(validflage === false){		//每次都需要验证
			var oldpasswd = $(this).val();
			$.ajax({
				url:"../php/password_modify.php",
				dataType:"json",
				type: 'POST',
				data:{
					past_password:oldpasswd,
					pastpwdType:"pastpwdType"
				},
				success:function(data){
					console.log(data);
					if(data != 1){	//验证失败
						validflage = false;
						$("#oldpasswddiv").addClass("error").removeClass("success");
						$(".fa-remove").show();
						$(".fa-check").hide();

						//原密码错误不允许修改
						$("#newpasswd,#newpasswd2").attr("disabled",true);

					}else{			//验证成功
						validflage = true;
						$("#oldpasswddiv").removeClass("error").addClass("success");
						$(".fa-remove").hide();
						$(".fa-check").show();

						//原密码正确允许修改
						$("#newpasswd,#newpasswd2").attr("disabled",false);
					}
				},
				error:function(e){
					console.log(e);
				}
			});
		//}
	});

	// $("#oldpasswd").bind("clange",function(){
	// 	alert(1);
	// });

	$("#submit").bind("click",function(){
		if( validflage === true){		//验证成功

			var newpasswd = $("#newpasswd").val();
			var newpasswd2 = $("#newpasswd2").val();
			if(newpasswd === newpasswd2){
				$.ajax({
					url:"../php/password_modify.php",
					dataType:"json",
					type: 'POST',
					data:{
						now_password1:newpasswd,
						now_password2:newpasswd2,
						pastpwdType:"newpwdType"
					},
					success:function(data){
						console.log(data);
						if(data === 1){
							tipTogle()
						}else{
							alert("密码修改失败");
						}
					},
					error:function(e){
						console.log(e);
					}
				});
			}else{
				alert("两次密码不一致");
			}
			
		}else{
			alert("请输入原密码");
		}
	});

	 // 设置弹出框
    function tipTogle(){
        $("body").append('<div class="popOperTip">密码修改成功</div>');
        $(".popOperTip").fadeToggle(1000);
        setTimeout('$(".popOperTip").fadeToggle(1000)',1500);
    }
});