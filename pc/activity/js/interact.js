var n = 1,
	w,x,l, lw;

var cont_width = 298,
	cont_height = 212,
	speed = 'slow';
		
/* Zoom Effect */		
var zoom_width = 350,
    zoom_height = 250;
	
$(document).ready(function(e) {
		lw = $('.slider ul li').outerWidth() + 60;
		$('.slider ul li:first-child').addClass('show');
		sw = $(window).outerWidth();
		x = (sw - lw)/2 - (zoom_width - cont_width) - 10;
		l = $('.slider ul li').length;
		w = l * lw + (zoom_width - cont_width);
		console.log(l)
	$('.slider ul').attr('style','left:'+x+'px');
	$('.slider ul').width(w);
});


$('.next').bind('click', function(el,ev) {
		anNext($(this),ev)
})
	
function anNext(obj,ev) {
	if(n==l || n > 1) {
		obj.unbind(ev);
		$('.slider ul').animate({ "left": "+="+lw+"" }, speed, function(){
			obj.click( function() {anNext(obj)})
		});
		n = n - 1;
		show(n)
	} 
}

$('.prev').bind('click', function(el,ev) {
		anim($(this),ev)
})
//µã»÷Í¼Æ¬µ¯³ö´óÍ¼
$('.slider').find('img').on('click',function(){ 
	$('.hideContent').show();
	
	
	em=$('.imgINhidecontent').find('img');
	em.attr("src",this.src);	
	 for(var i=0;i<11;i++){
				setTimeout((function(pos){
					return function(){ 
						animation(em,pos);
					}
				})(i/10),i*100);
				  
		}  	
   $('.imgINhidecontent').show();
});	
//µã»÷Ïû³ýmodel±³¾°
$('.hideContent').on('click',function(){
	$('.hideContent').hide();
	$('.imgINhidecontent').hide();
});	
function animation(obj,i){  
        var left= i*400 ;
		 
		obj.css('opacity',i);   
		obj.css('position',"fixed");
		obj.css('left',left+"px"); 
		obj.find(".content").css('display',"none");
		obj.find("img").css('width',"500px");
		obj.find("img").css('height',"400px");
		obj.css('z-index',"5");
}
function anim(obj,ev) {
	if(n == 1 || n < l) {
		obj.unbind(ev);
		$('.slider ul').animate({ "left": "-="+lw+"" }, speed, function(){
			obj.click( function() { anim(obj)})
		});
		n = n + 1;
		show(n);
	}
}


function show(n) {
	$('.slider ul li.show img').animate({width: cont_width, height: cont_height},speed)
	$('.slider ul li:nth-child('+n+') img').animate({width: zoom_width, height: zoom_height,},speed)
	
	$('.slider ul li.show').animate({marginTop: "0"})
	$('.slider ul li:nth-child('+n+')').animate({marginTop: "-20px"})
	
	$('.slider ul li.show').removeClass('show');
	$('.slider ul li:nth-child('+n+')').addClass('show');
}