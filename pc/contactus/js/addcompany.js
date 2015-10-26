$(function(){
	var subcompanyid = GetQueryString("subcompanyid");

	if(subcompanyid != null){
		$.ajax({
			url:"../php/subcompany.php",
			dataType:"json",
			data:{
				type:"detail",
				id:subcompanyid
			},
			success:function(data){
				// console.log(data);
				$("#subcompany").val(data[0].companyName);
				$("#address").val(data[0].address);
				$("#contact").val(data[0].phone);
				$("#email").val(data[0].email);
			},
			error:function(e){
				console.error(e);
			}
		}) 
	}else{
		 
	}
	$("#submit").bind("click",function(){
		var subcompany = $("#subcompany").val();
		var address = $("#address").val();
		var phone = $("#contact").val();
		var email = $("#email").val();
		
		if(subcompany != '' && address != '' && phone != '' && email != ''){
			if(subcompanyid == null){		//添加操作
				$.ajax({
					url:"../php/subcompany.php",
					dataType:"json",
					data:{
						type:"add",
						companyName:subcompany,
						address:address,
						phone:phone,
						email:email,
						coordinate:'89.5341,87.145646'
					},
					success:function(data){
						if(data === 1){
							tipTogle("添加成功");
							// $("#subcompany").val("");
							// $("#address").val("");
							// $("#contact").val("");
							// $("#email").val("");
						}else{
							alert("添加失败");
						}
					},
					error:function(e){
						console.error(e);
					}
				});
			}else{		//修改更新操作
				$.ajax({
					url:"../php/subcompany.php",
					dataType:"json",
					data:{
						type:"update",
						id:subcompanyid,
						companyName:subcompany,
						address:address,
						phone:phone,
						email:email,
						coordinate:'89.5341,87.145646'
					},
					success:function(data){
						// console.log(data);
						if(data === 1){
							tipTogle("修改成功");
							// $("#subcompany").val("");
							// $("#address").val("");
							// $("#contact").val("");
							// $("#email").val("");
						}else{
							alert("修改失败");
						}
					},
					error:function(e){
						console.error(e);
					}
				});
			}
			
		}else {
			alert("请输入完整信息");
		}
	});

	// 设置弹出框
    function tipTogle(str){
        $("body").append('<div class="popOperTip">'+str+'</div>');
        $(".popOperTip").fadeToggle(1000);
        setTimeout('$(".popOperTip").fadeToggle(1000)',1500);
    }
});