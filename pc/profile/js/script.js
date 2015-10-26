var php_path = "./php/";
var timerFID;
$(document).ready(function sss(){
	
	$.get(php_path+"picadmin.php",{act:"getpics"},
		function(data){
		 // alert(data);
		  $("#focusBar ul.mypng").empty().append(data);
		  $("#focusBar li").css("width",$(window).width());
			$("#focusBar").css("width",$(window).width());
			//alert("hi");
			//alert($(window).height()*0.8);
			$("#focusBar").css("height",$(window).height()*0.8);
			$("#focusBar li").css("height",$(window).height()*0.8);
			$("#focusIndex1").show();
			
			
			$("#focusIndex1 .focus").css({"left":"0","margin-left":"0"});
			
			$("a.arrL").mouseover(function(){stopFocusAm();}).mouseout(function(){starFocustAm();});
			$("a.arrR").mouseover(function(){stopFocusAm();}).mouseout(function(){starFocustAm();});
			$("#focusBar li").mouseover(function(){stopFocusAm();}).mouseout(function(){starFocustAm();});
			timerFID = setInterval("timer_tickF()",3000);

		 
		}
	);
	
	
});    

	
/*------focus-------*/
$("#focusBar").hover(
	function () {
		$("#focusBar .arrL").stop(false,true);
		$("#focusBar .arrR").stop(false,true);
		$("#focusBar .arrL").animate({ left: 0}, { duration: 500 });
		$("#focusBar .arrR").animate({ right: 0}, { duration: 500 });
	}, function () {
		$("#focusBar .arrL").stop(false,true);
		$("#focusBar .arrR").stop(false,true);
		$("#focusBar .arrL").animate({ left: -52}, { duration: 500 });
		$("#focusBar .arrR").animate({ right: -52}, { duration: 500 });
	}
);



function nextPage() {
	changeFocus(true);
}
function prePage() {
	//alert("pre");
	changeFocus(false);
}

var currentFocusI=1;
var changeingFocus = false;
function changeFocus(dir) {
	//alert("timer");
	if($("#focusBar li").length <= 1) return;
	if(changeingFocus) return;
	changeingFocus = true;
	
	$("#focusIndex"+nextI).stop(false,true);
	$("#focusIndex"+nextI+" .focus").stop(false,true);
	
	
	var nextI = dir?currentFocusI+1:currentFocusI-1;
	nextI = nextI>$("#focusBar li").length?1:(nextI<1?$("#focusBar li").length:nextI);
	//var focusWidth = $(window).width()>1000?1000:$(window).width();
	$("#focusIndex"+currentFocusI).css("width",$(window).width());
	$("#focusIndex"+nextI).css("width",$(window).width());
	if(dir) {
		$("#focusIndex"+nextI).css("left",$(window).width());
		//$("#focusIndex"+nextI+" .focus").css("left",$(window).width()/2);
		$("#focusIndex"+nextI+" .focus").css({"left":$(window).width(),"margin-left":"0"});
		
		$("#focusIndex"+currentFocusI).show();
		$("#focusIndex"+nextI).show();
		
		
		$("#focusIndex"+currentFocusI+" .focus").animate({left: -($(window).width()/2+1000)},800,'easeInExpo',function(){
				$("#focusIndex"+nextI+" .focus").animate({left: 0},1000,'easeInOutCirc');
				
				
				$("#focusIndex"+currentFocusI).animate({left: -$(window).width()},1000,'easeOutExpo');
				$("#focusIndex"+nextI).animate({left: 0},1000,'easeOutExpo',function(){
						$("#focusIndex"+currentFocusI).hide();
						currentFocusI = nextI;
						changeingFocus = false;
				});
		});
	} else {
		$("#focusIndex"+nextI).css("left",-$(window).width());
		$("#focusIndex"+nextI+" .focus").css({"left":-($(window).width()+1000),"margin-left":"0"});
		
		$("#focusIndex"+currentFocusI).show();
		$("#focusIndex"+nextI).show();
		
		
		$("#focusIndex"+currentFocusI+" .focus").animate({left: $(window).width()},500,'easeInExpo',function(){
				$("#focusIndex"+nextI+" .focus").animate({left: 0},1200,'easeInOutCirc');
				
				
				$("#focusIndex"+currentFocusI).animate({left: $(window).width()},1000,'easeOutExpo');
				$("#focusIndex"+nextI).animate({left: 0},1000,'easeOutExpo',function(){
						$("#focusIndex"+currentFocusI).hide();
						currentFocusI = nextI;
						changeingFocus = false;
				});
		});
	}
}
function starFocustAm(){
	timerFID = setInterval("timer_tickF()",3000);
}
function stopFocusAm(){
	clearInterval(timerFID);
}
function timer_tickF() {
	changeFocus(true);
}
