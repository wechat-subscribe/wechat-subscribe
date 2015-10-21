//获取url信息
 function GetQueryString(name) { 
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
    var r = window.location.search.substr(1).match(reg); 
    if (r != null) return unescape(r[2]); return null; 
} 


$(function(){
	var articleid = GetQueryString("articleid") ;
    if(articleid != null){
    	$.ajax({
            url         :   "../php/recruit_upload.php",
            datatype    :   "json",
            type        :   'POST',         //默认为GET方式
            async       :   false,          //异步
            data        : {
                'type'      : "details",
                'id'      : articleid
            },
            success:function(data) {
            	console.log(data);
                data = data.details;
                $("#title").val(data.positionName);		//职位名称
				$("#address").val(data.address);		//工作地址
				$("#talentnum").val(data.num);		//招聘数量
				$("#sel").val(data.sexRequire);		//性别 
				$("#salary").val(data.salary);			//工作薪资
				//var other  = "";							//岗位职责
				//var content = "";					//任职资格
				
				$.each(data.other,function(i,item){	//岗位职责
					if(i==0){
						$(".jobinfoinput").val(item);
					}else{
						var $jobinfo = $('<div class="conlabel"><span class="leftspan"></span><input style="margin-left: 23px;" type="text" class="jobinfoinput" value="'+item+'" name=""></div>');
       					$(".jobyq").before($jobinfo);
					}
				});

				$.each(data.content,function(i,item){	//任职资格
					if(i==0){
						$(".jobyqinput").val(item);
					}else{
						var $jobinfo = $('<div class="conlabel"><span class="leftspan"></span><input style="margin-left: 23px;" type="text" class="jobyqinput" value="'+item+'" name=""></div>');
       					$(".contact").before($jobinfo);
					}
				});
            },
            error:function(e){  
                console.log("请求出错");
            } 
        });
    }


	//动态添加岗位职责
	$(".addjobinfoinput").on('click',function(){
        var $jobinfo = $('<div class="conlabel"><span class="leftspan"></span><input style="margin-left: 23px;" type="text" class="jobinfoinput" value="" name=""></div>');
        $(".jobyq").before($jobinfo);
    });
	// 动态添加任职资格
    $(".addjobyqinput").on('click',function(){
        var $jobyq = $('<div class="conlabel"><span class="leftspan"></span><input style="margin-left: 23px;" type="text" class="jobyqinput" value="" name=""></div>');
        $(".contact").before($jobyq);
    });

	$("#addTalentsubmit").on('click',function(){

		var positionName = $("#title").val();		//职位名称
		var address = $("#address").val();			//工作地址
		var num = $("#talentnum").val();			//招聘数量
		var sexRequire = $("#sel").val();			//性别 
		var salary = $("#salary").val();			//工作薪资
		var other  = "";							//岗位职责
		var content = "";							//任职资格
		$(".jobinfoinput").each(function(i,item){ 
			if(i == 0){
				other += $(this).val();
			}else{
				other += '|'+$(this).val();
			}
			
		});

		$(".jobyqinput").each(function(i,item){
			if(i == 0){
				content += $(this).val();
			}else{
				content += '|'+$(this).val();
			}
		});
		
		if(articleid != null){					// 更新操作
			//提交招聘信息
	        $.ajax({
	            url         :   "../php/recruit_upload.php",
	            datatype    :   "json",
	            type      :   'POST',         //默认为GET方式
	            async       :   false,        //同步
	            data        : {
	                'type'      : "modify",
	                'id'		: articleid,
	                'positionName':positionName,
	                'address'	:address,
	                'num'	:num,
	                'other'	:other,
	        		'sexRequire' :sexRequire,
	                'salary':salary,
	                'content':content
	            },
	            success:function(data) {  
	                 if(data.error == 1){
	                 	alert("修改成功");
	                 }else{
	                 	alert("修改失败");
	                 }
	                 
	            },
	            error:function(e){  
	                console.log("请求出错");
	            } 
	        });
		}else{									//添加操作
			//提交招聘信息
	        $.ajax({
	            url         :   "../php/recruit_upload.php",
	            datatype    :   "json",
	            type      :   'POST',         //默认为GET方式
	            async       :   false,        //同步
	            data        : {
	                'type'      : "add",
	                'positionName':positionName,
	                'address'	:address,
	                'num'	:num,
	                'other'	:other,
	        		'sexRequire' :sexRequire,
	                'salary':salary,
	                'content':content
	            },
	            success:function(data) {  
	                 if(data == 1){
	                 	alert("添加成功");
	                 }else{
	                 	alert("添加失败");
	                 }
	                 
	            },
	            error:function(e){  
	                console.log("请求出错");
	            } 
	        });
		}
		

		//判断职位，地址，人数，时间 是否为空

		
		return false;	//阻止事件冒泡
	});

});