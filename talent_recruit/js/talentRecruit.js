$(function(){
	// 请求人才招聘列表
    $.ajax({
        url         :   "../php/recruit_upload.php",
        datatype    :   "json",
        //type        :   'POST',         //默认为GET方式
        async       :   true,          //异步
        data        : {
            'type'      : "list",
            'page'		: 1
        },
        success:function(data) {  
            // 分页大小10条数据
            $.each(data.list,function(i,item){
          	  	 
        	var str = '<tr><td class="checkbox"><input type="checkbox"/></td>';
        		str += '<td><a href="" name="1">'+item.title+'</a></td>';
        		str += '<td>'+item.date+'</td>';
        		str += ' <td class="actionicon"><i class="fa fa-pencil blue"></i><i class="fa fa-trash red"></i></td></tr>';
        		$("#talenttbody").append(str);
            });
        },
        error:function(e){  
            console.log("请求出错");
        } 
    });
});