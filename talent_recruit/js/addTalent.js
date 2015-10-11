$(function(){
	//alert('1');
	$("#addTalentsubmit").bind('click',function(){
		var tal_title = $("#title").val();
		var tal_address = $("#address").val();
		var tal_deparment = $("#deparment").val();
		var tal_pay = $("#pay").val();
		
		console.log(tal_title);
		console.log(tal_address);
		console.log(tal_deparment);
		console.log(tal_pay);
		return false;	//阻止事件冒泡
	});

});