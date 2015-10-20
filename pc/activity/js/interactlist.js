//图片交互脚本 
var num=0;//数据总数
var size=10;//请求数据的个数
var sum=0;
var data;
dataload();
//数据加载
function dataload(){
	
	  $("#load").show();
	  $.ajax({
            url         :   "../php/activity_interact.php?handle=interactlist",
            datatype    :   "json",
            type      :   'POST',        
            async       :   false,          //异步
            data        : {
				'handle'	: 'interactlist',
				'projectId'			:GetQueryString('type'),
                'num'      : num,
				"size"     :  size, 
            },
            success:function(tmp) { 
				tmp=JSON.parse(tmp);   
			
			   datashow(tmp);
               $("#load").hide();
				num=num+10;
            },
            error:function(e){  
                conlog("请求出错");
            } 
	});
}

function datashow(tmp){
	var str="";
	$.each(tmp , function(k,v){
	  if(v.id!=undefined){
		str+='<div class="item" data-id="'+v.id+'"><div class="pic"><a href="./interact.html?id='+v.id+'"> <img src="'+v.multimediaFile+' ">  </a></div>';
		str+='<div class="content">'+v.userId+"---"+v.date+' ';
		str+='<ul>  <li class="fa fa-trash red remove" onClick="_delete('+v.id+')"> </li>';
		str+='<li class="fa fa-pencil blue editarticle"> </li>';
		str+='</ul> </div>  </div>';
	  }
	}); 	
     $(".interactarea>.list").append(str);	
	 sum=tmp.sum;	 				
							
}
function _delete(id){
	 em = $(".item[data-id = "+id+"]"); 
	  
	  for(var i=0;i<11;i++){
				setTimeout((function(pos){
					return function(){ 
						animation(em,1-pos);
					}
				})(i/10),i*100);
				  
		}  	
	 $.ajax({
            url         :   "../php/activity_interact.php?handle=deleteinteract",
            datatype    :   "json",
            type      :   'POST',        
            async       :   false,          //异步
            data        : {
				'handle'	: 'deleteinteract',
				'id'			:id 
            },
            success:function(tmp) { 
				tmp=JSON.parse(tmp); 
					 
            },
            error:function(e){  
                conlog("请求出错");
            } 
	});
}
function animation(obj,i){  
        var left= 800-i*300 ;
		 
		obj.css('opacity',i);   
		obj.css('position',"fixed");
		obj.css('left',left+"px"); 
		obj.find(".content").css('display',"none");
		obj.find("img").css('width',"500px");
		obj.find("img").css('height',"400px");
		obj.css('z-index',"5");
}
 $(window).scroll(function () {
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if (scrollTop + windowHeight == scrollHeight) {
		 setTimeout("document.getElementById('my').src='include/common.php'; ",3000);//延时3秒 
			if(sum<num){ 
				conlog("加载完成共"+sum+"条数据");
			}
			else{
				conlog("已加载"+num+"条数据");
				dataload();
			}
		

        }
    });
	//获取url信息
 function GetQueryString(name) { 
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
    var r = window.location.search.substr(1).match(reg); 
    if (r != null) return unescape(r[2]); return null; 
} 
function conlog(str){
	alert(str);
}