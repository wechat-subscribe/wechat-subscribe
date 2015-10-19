/*
*vote front
*writer  ly
*/

//加载一个工程
url="../php/activity.php?handle=activitylist"
url2="../php/vote.php?handle=showItem"
url3="../php/vote_interact.php"
voteId=getPar("voteId");
data="";
data2="";
$.post(url,{"id":voteId},function(d,s){ 
	d=JSON.parse(d);
	$(".headpic>img").attr("src",d.picture);
	$(".votetitle>h4").html(d.title);
	$(".content>p").html(d.content);
	data=d;
});
$.post(url2,{"voteId":voteId},function(d,s){ 
d=JSON.parse(d);
	$.each(d,function(k,v){
		str=""; 
		str+=' <div class="votepeo">';
		str+='<img src="'+v.picture+'" width="50px" >';
		str+='<div class="votepeotitle upspace"><a href="">'+v.name+'</a></div>';
		str+='<div class="voteaction upspace">';
		str+='<a onClick="vote(111111111,'+v.id+')"><i class="fa fa-thumbs-o-up"></i>投票</a>';
		str+='<p id="vote_'+v.id+'">0票</p>';
		str+='</div>';
		str+='</div>';
		$("#list").append(str);
		voteSum(v.id);
	});
	data2=d;
});


function voteSum(id){ 
	 
	$.post(url3,{"voteSum":1,"id":id},function(d,s){
		if(d){
			d=JSON.parse(d);
			//conlog(d);
			 $("#vote_"+id+"").html(d+"票")
		}
		else{
			//conlog(d);
			 
		}
		
	});
	 
}
//投票简介
 $(".content>p").css("display","none");//先隐藏简介
$(".up_down").on("click",up_down);
 function up_down(){
	 if($(".content>p").css("display")=="none"){
		 $(".up_down").html("<i class=\"fa test fa-angle-double-up \"></i>");
		 $(".content>p").css("display","block");
	 }
	 else{
		 $(".up_down").html("<i class=\"fa test fa-angle-double-down \"></i>");
		 $(".content>p").css("display","none");
	 }
	
	
}
//投票
function vote(user,id){
	$.post(url3,{"user":user,"id":id,"voteId":getPar("voteId")},function(d,s){
		d=JSON.parse(d);
		if(d){
			alert("投票成功！");
		}
		else{
			alert("您已投票请不要重复投票！");
		}
	});
}
//获取get参数
function getPar(par){
    //获取当前URL
    var local_url = document.location.href; 
    //获取要取得的get参数位置
    var get = local_url.indexOf(par +"=");
    if(get == -1){
        return false;   
    }   
    //截取字符串
    var get_par = local_url.slice(par.length + get + 1);    
    //判断截取后的字符串是否还有其他get参数
    var nextPar = get_par.indexOf("&");
    if(nextPar != -1){
        get_par = get_par.slice(0, nextPar);
    }
    return get_par;
}
 
//调试日志
		function conlog(str)
		{
			alert(str);
		}