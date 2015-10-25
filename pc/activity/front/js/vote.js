 var user='123456';
voteId=getPar('voteId');
datatmp={'type':"getFather","projectId":voteId};
var father=loadData(datatmp);
if(father.title==undefined){
	alert("非法操作");
	location.href="./votelist.html";
}
 else{
			$(".headpic>img").attr("src",father.picture);
			$(".votetitle>h4").html(father.title);
			$(".content>p").html(father.content); 
 }
 
 data={'type':'list','page':1,'voteId':voteId};
 var d=loadData(data); 
 pageWrite(d);
 
 
 function pageWrite(d){
 
 $.each(d.list,function(k,v){
		str=""; 
		str+=' <div class="votepeo">';
		str+='<img src="'+v.picture+'" width="50px" >';
		str+='<div class="votepeotitle upspace"><a href="">'+v.title+'</a></div>';
		str+='<div class="voteaction upspace">';
		str+='<a onClick="vote('+v.id+')"><i class="fa fa-thumbs-o-up"></i>投票</a>';
		str+='<p  >'+sumVoteItem(v.id)+'票</p>';
		str+='</div>';
		str+='</div>';
		$("#list").append(str);
		
	});
 }
 
function loadData(data){ 
    var  re;
	$.ajax({
            url         :   "../php/vote_option_list.php",
            datatype    :   "json",
            type      :   'GET',         //默认为GET方式
            async       :   false,          //同步
            data        :  data,
            success:function(data) { 
                console.log(data);	/////////////////////////////////////////////////////////////		
                re=JSON.parse(data);
				
             
                           
            },
            error:function(e){  
                console.log("请求出错");
            } 
        });
	return re;
}
function loadInteractData(data){ 
    var  re;
	$.ajax({
            url         :   "../php/vote_interact.php",
            datatype    :   "json",
            type      :   'GET',         //默认为GET方式
            async       :   false,          //同步
            data        :  data,
            success:function(data) { 
                console.log(data);	/////////////////////////////////////////////////////////////		
                re=JSON.parse(data);
				
             
                           
            },
            error:function(e){  
                console.log("请求出错");
            } 
        });
	return re;
} 

function sumVoteItem(id){
	    data={'voteSum':'voteSum','id':id};
		return loadInteractData(data); 
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
function vote(id){
	data={"user":user,"id":id,"voteId":voteId}
	d=loadInteractData(data);
	 if(d){
		 alert("成功");
		 location.replace(true);
	 }
	 else{
		 alert("请不要刷票");
	 }
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
  