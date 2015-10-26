var php_path = "./php/";
$(document).ready(function sss(){
	$.get(php_path+"picadmin.php",{act:"get"},
		function(data){
		   //alert(data);
		  $("#pics").empty().append(data);
		  $("#pics figure").bind('click',function(e){
		cur_content = $(this).parent();
		$(".row div").css("border-style","none");
		$(this).parent().css("border-style","solid");
    	$(this).parent().css("border-color","red");
	   });
	   delbind();
		}
	);
	var cur_content = null;

    /************************查看按时间限定记录 ****************************/
	$("#pics figure").bind('click',function(e){
		cur_content = $(this).parent();
		$(".row div").css("border-style","none");
		$(this).parent().css("border-style","solid");
    	$(this).parent().css("border-color","red");
	})
	
	
	$("#forward").bind('click',function(e){
		if(cur_content == null){
			
			alert("请先选择图片");
			return;
		}
		var cur_node = cur_content.parent(); //div  row
		var cur_li = cur_node.parent();//li
		var next_li = cur_li.prev();
		//alert(next_li);
		if(next_li.length == 0){
			//alert("zuiqianle ");
			next_li = $("#pics ul li").last();
			//return;
		}
		var next_node = next_li.html();
		cur_li.empty().append(next_node);
		next_li.empty().append(cur_node);
		//alert("forward");
		//alert(cur_li.html());
		//alert(next_li.html());
		$("#pics figure").bind('click',function(e){
		cur_content = $(this).parent();
		$(".row div").css("border-style","none");
		$(this).parent().css("border-style","solid");
    	$(this).parent().css("border-color","red");
	   });
	})
	
	$("#backward").bind('click',function(e){
		if(cur_content == null){
			
			alert("请先选择图片");
			return;
		}
		var cur_node = cur_content.parent(); //div  row
		var cur_li = cur_node.parent();//li
		var next_li = cur_li.next();
		//alert(next_li);
		if(next_li.length == 0){
			//alert("zuiqianle ");
			next_li = $("#pics ul li").first();
			//return;
		}
		var next_node = next_li.html();
		cur_li.empty().append(next_node);
		next_li.empty().append(cur_node);
		//alert("forward");
		//alert(cur_li.html());
		//alert(next_li.html());
		$("#pics figure").bind('click',function(e){
		cur_content = $(this).parent();
		$(".row div").css("border-style","none");
		$(this).parent().css("border-style","solid");
    	$(this).parent().css("border-color","red");
	   });
	})
	
	$("#upload").bind('click',function(e){
		$("#myModal").modal();
	})
	
	$("#save").bind('click',function(e){
		//alert("forward");
		var images = "";
		$("figure.post-image img").each(function(){
          //alert($(this).attr("src"));
		  var str = $(this).attr("src");
		  var temp = str.split("/");
		  if(temp)
		    str = temp[1];
		  images += str;
		  images += ":";
        });
		images = images.substr(0,images.length-1);
		
		$.get(php_path+"picadmin.php",{act:"save",images:images},
		function(data){
		   
		   if(data == 1){
			 
			  alert("设置保存成功");
			  
		   }else if(data == -1){
			  alert("删除操作失败，请联系技术支持");
		   }else if(data == -2){
			   alert("插入记录失败");
		   }else{
			   alert(data);
		   }
		}
		);
	})
	
	
	
	
	$("#fileupload").bind('click',function(e){
		//校验文件类型
		var filepath = $("input[name='file0']").val();
		if(filepath == ""){
			alert("请先选择图片");
			return false;
			
		}
        var extStart = filepath.lastIndexOf(".");
        var ext = filepath.substring(extStart, filepath.length).toUpperCase();
        if (ext != ".BMP" && ext != ".PNG" && ext != ".GIF" && ext != ".JPG" && ext != ".JPEG") {
            alert("图片限于bmp,png,gif,jpeg,jpg格式");
            return false;
        }
		$("#uploadstatus").html("正在上传......");
		$("#uploadform").submit();
	})
	
	
	$("#file0").change(function(){
	var objUrl = getObjectURL(this.files[0]) ;
	console.log("objUrl = "+objUrl) ;
	if (objUrl) {
		$("#img0").attr("src", objUrl) ;
	}
}) ;
//建立一個可存取到該file的url
function getObjectURL(file) {
	var url = null ; 
	if (window.createObjectURL!=undefined) { // basic
		url = window.createObjectURL(file) ;
	} else if (window.URL!=undefined) { // mozilla(firefox)
		url = window.URL.createObjectURL(file) ;
	} else if (window.webkitURL!=undefined) { // webkit or chrome
		url = window.webkitURL.createObjectURL(file) ;
	}
	return url ;
}

function  delbind(){
	$("header a").bind('click',function(e){
		 ret=confirm("确定删除此图片吗?");
		
         if(ret){
			 var imagefile = $(this).parent().next().find("img").attr("src");
		     var node_li = $(this).parent().parent().parent().parent();
		     node_li.remove(); 
			 
			 //alert(imagefile);
			 var temp = imagefile.split("/");
		     if(temp)
		       imagefile = temp[1];
		   
		    $.get(php_path+"picadmin.php",{act:"del",image:imagefile},
			function(data){
			   
			   if(data == 1){
				 
				  alert("文件删除成功");
				  
			   }else if(data == -1){
				  alert("删除数据库记录失败");
			   }else if(data == -2){
				   alert("文件删除失败");
			   }else if(data == -2){
				   alert("删除的文件不存在");
				   
			   }else{
				   alert(data);
			   }
			}
			);
		   
		   
		}
        
		
		
	})
	
	
}

});