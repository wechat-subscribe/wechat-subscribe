$(function(){   
    var config ={
        event:'',       //判断弹出框事件类型（添加，重命名，删除，发布文章）
        error:'',       //用于判断添加是否可以添加
        isFir:'',       //用于判断是否是一级菜单
        parent:'',       //用于动态添加节点的父节点
        curelemId:'',      //当前点击元素
        firtype:1      //添加一级菜单的类型 1:一级目录；2:一级菜单默认为目录
    };

    // 初始化微信左侧菜单
    $.ajax({
        url         :   "../php/menu.php",
        datatype    :   "json",
        async       :   true,          //异步
        data        : {
            type    :"showmenu"
        },
        success:function(data) {  
            // data = JSON.parse(data);
             console.log(data);
            $.each(data,function(i,item){
                // console.log(item);
                var str = '<dl class="menuListsub"><dt class="menuListsublight" name="'+item.id+'">';
                str += '<a href="javascript:void(0);" class=""><strong>'+item.name+'</strong></a>';
                str += '<span class="menu_opr"><a href="javascript:void(0);" id="" class="addMenu sec" name="'+item.id+'"><i class="fa fa-plus" name="'+item.id+'"></i></a><a href="javascript:void(0);" id="orderBt" class=" " name="排序" style="display:none"><i class="fa fa-sort"></i></a></span></dt>';
                $.each(item.sub,function(j,subitem){
                    // console.log(subitem);
                    str += '<dd name="'+subitem.id+'" id="subMenu_menu_1_1"><i class="icon_dot">●</i>';
                    str += '<a href="javascript:void(0);" class=""><strong>'+subitem.name+'</strong></a>';
                    str += '<span class="menu_opr"><a href="javascript:void(0);" id="orderBt" class=" " name="排序" style="display:none"><i class="fa fa-sort"></i></a></span></dd>';
                });
                str += '</dl>';
                $("#menuListId").append(str);
            });       
        },
        error:function(e){  
            console.error("请求出错");
        } 
    });


    //添加菜单弹出框
    $("body").delegate(".addMenu","click",function(){
        config.event = "addMenu";               //弹出框事件类型
        if($(this).hasClass("fir")){
            config.parent = $("#menuListId");   //一级菜单的父节点
            config.isFir = true;                //一级菜单      
            $("#dialog_head").html("添加一级菜单");
            // 遍历已经存在的一级菜单
            var num = $("body .menuList .menuListsub").size();
            if(num>=3){
                config.error = true;
                var str = '<span><i class="fa fa-info-circle"></i></span><div class="delcon"><h4>添加一级菜单出错</h4><p>当前已存在3个一级菜单</p></div>';
                $(".contextareawrap.add").html(str);
            }else{
                config.error = false;
                var str = '<p class="frm_label"><i class="fa fa-info-circle"></i>还能添加<strong>'+ (3-num) +'</strong>个一级菜单，请输入名称（4个汉字或8个字母以内）</p>';
                str +=  '<input type="text" id="addmenuInputId"  class="frm_input" name="popInput" value=""><div><p class="frm_label"><i class="fa fa-info-circle"></i>选择菜单类型</p>';
                str += '<form class="contextareawrapradio" >一级目录：<input type="radio" checked="checked" name="contextareawrapradio" value="null" />&nbsp;&nbsp;功能菜单：<input type="radio" name="contextareawrapradio" value="menu" /></form></div>';
                str += '<div class="select_addmenu_div" style="display: none;"><select class="select_addmenu_sel" id="select_addmenu_selIdpar"><option value ="0">请选择栏目类型</option><option value ="articlelist">文章列表</option><option value ="activity">活动</option></select>';
                str += '<select class="select_addmenu_sel" id="select_addmenu_selIdchil"><option value ="0">请选择栏目类型</option></select></div>';
            
                $(".contextareawrap.add").html(str);
            }
        }else if($(this).hasClass("sec")){
            config.parent = $(this).parent().parent().parent();    //二级菜单的父节点
            config.isFir = false;                                  //二级菜单
            config.curelemId = $(this).attr("name");               //二级菜单ID 

            $("#dialog_head").html("添加二级菜单");
            var num = $(this).parent().parent().parent().find("dd").size();
            if(num>=5){
                config.error = true;
                var str = '<span><i class="fa fa-info-circle"></i></span><div class="delcon"><h4>添加二级菜单出错</h4><p>当前已存在5个二级菜单</p></div>';
                $(".contextareawrap.add").html(str);
            }else{
                config.error = false;
                var str = '<p class="frm_label"><i class="fa fa-info-circle"></i>还能添加<strong>'+(5-num)+'</strong>个二级菜单，请输入名称（4个汉字或8个字母以内）</p><input type="text" id="addmenuInputId"  class="frm_input" name="popInput" value=""><p class="frm_label"><i class="fa fa-info-circle"></i>选择菜单类型</p><div class="select_addmenu_div"><select class="select_addmenu_sel" id="select_addmenu_selIdpar"><option value ="0">请选择栏目类型</option><option value ="articlelist">文章列表</option><option value ="activity">活动</option></select><select class="select_addmenu_sel" id="select_addmenu_selIdchil"><option value ="0">请选择栏目类型</option></select></div>';

                $(".contextareawrap.add").html(str);
            }
        }else if($(this).hasClass("contain")){
        	// alert(config.parent.attr("name"));
        	config.curelemId = config.parent.attr("name");          //二级菜单ID 	
            config.parent = config.parent.parent();                 //二级菜单的父节点
            config.isFir = false;                                   //二级菜单
            

            $("#dialog_head").html("添加二级菜单");
            var num = config.parent.find("dd").size();
            if(num>=5){
                config.error = true;
                var str = '<span><i class="fa fa-info-circle"></i></span><div class="delcon"><h4>添加二级菜单出错</h4><p>当前已存在5个二级菜单</p></div>';
                $(".contextareawrap.add").html(str);
            }else{
                config.error = false;
                var str = '<p class="frm_label"><i class="fa fa-info-circle"></i>还能添加<strong>'+(5-num)+'</strong>个二级菜单，请输入名称（4个汉字或8个字母以内）</p><input type="text" id="addmenuInputId"  class="frm_input" name="popInput" value=""><p class="frm_label"><i class="fa fa-info-circle"></i>选择菜单类型</p><div class="select_addmenu_div"><select class="select_addmenu_sel" id="select_addmenu_selIdpar"><option value ="0">请选择栏目类型</option><option value ="articlelist">文章列表</option><option value ="activity">活动</option></select><select class="select_addmenu_sel" id="select_addmenu_selIdchil"><option value ="0">请选择栏目类型</option></select></div>';
                $(".contextareawrap.add").html(str);
            }
        }

        $(".hideContent,.comtent").css("display","block");
        $(".add").removeClass("disable");
     
        return false; 
    });

     // 重命名菜单
    $(".editmenubtn").on('click',function(){
        config.event = "editMenuName";                              //弹出框事件类型(重命名)
        if(config.parent){
        	$("#dialog_head").html("重命名菜单");       
            var menuname = config.parent.find("a strong").text();  //获取已经存在的菜单名称

            var str = '<p class="frm_label"><i class="fa fa-info-circle"></i>不多于4个汉字或8个字母以内</p><input type="text" class="frm_input" name="popInput" value="'+menuname+'" id="editNameInput">';
            $(".contextareawrap.edit ").html(str);

        
            $(".hideContent,.comtent").css("display","block");
            $(".edit").removeClass("disable");
        }else{
            alert("请选择菜单");
        }
       
    });
    //删除菜单
    $(".delmenubtn").on('click',function(){
        config.event = "delMenu";                              //弹出框事件类型(删除)
       
        if(config.parent){                                      //判断是否存在父元素
            $("#dialog_head").html("删除菜单");
            if(config.parent.prop("tagName") == "DT"){
                var str = '<span><i class="fa fa-info-circle"></i></span><div class="delcon"><h4>删除确认</h4><p>删除后该菜单和二级菜单将被删除，同时消息将不会被保存</p></div>';
                $(".contextareawrap.del ").html(str);
            }else{
                var str = '<span><i class="fa fa-info-circle"></i></span><div class="delcon"><h4>删除确认</h4><p>删除后该菜单下设置的消息将不会被保存</p></div>';
                $(".contextareawrap.del ").html(str);
            }

            $(".hideContent,.comtent").css("display","block");
            $(".del").removeClass("disable");
        }else{
            alert("请选择菜单");
        }
    });
    // 确定弹出框
    $(".btnleft").on('click',function(){
        $(".hideContent,.comtent").css("display","none");
        $(".add,.del,.edit").addClass("disable");
        if( config.event === "addMenu"){                    //添加
            var text = $("#addmenuInputId").val();          //菜单名称

            if(config.error === false){
                if(config.isFir === true){
                    if(config.firtype === 1){					//空目录
                        var menuFirstType = '';
                        var menusecondType = '';
                    }else if(config.firtype ===2){				//菜单项
                        var menuFirstType = $("#select_addmenu_selIdpar").val();
                        var menusecondType = $("#select_addmenu_selIdchil").val();
                    }

                    var firstMenuType = config.firtype === 1? "nullMenu": "funcMenu";
                    config.curelemId = config.curelemId === ''?0:config.curelemId;
                    
                    $.ajax({
                        url:"../php/menu.php",
                        datatype:"json",
                        async:false,
                        data:{
                            type:"addmenu",
                            firstMenuType:firstMenuType, 
                            menuName: text,
                            parentId:config.curelemId,
                            menuFirstType:menuFirstType,
                            menusecondType:menusecondType 
                        },
                        success:function(data){
                            // console.log(data);
                            if(data.error === 1){
								var str = '<dl class="menuListsub"> <dt class="menuListsublight" name="'+data.id+'"><a href="" class=" "><strong>'+text+'</strong></a><span class="menu_opr"><a href="javascript:void(0);" id="" class="addMenu sec" name="添加"><i class="fa fa-plus"></i></a><a href="javascript:void(0);" id="orderBt" class=" " name="排序" style="display:none"><i class="fa fa-sort"></i></a></span></dt></dl>';
                    			config.parent.append(str);  
                    			alert("添加成功");
                            }else{
                            	alert("添加失败");
                            }
                        },
                        error:function(e){
                            console.error("请求出错");
                        }
                    });
                     
                }else{
                    // 二级菜单
                    var menuFirstType = $("#select_addmenu_selIdpar").val();
                    var menusecondType = $("#select_addmenu_selIdchil").val();
                    
                    console.log(text);
                    console.log(config.curelemId);
                    console.log(menuFirstType);
                    console.log(menusecondType);
                    $.ajax({
                        url         :   "../php/menu.php",
                        datatype:"json",
                        async:false,
                        data        : {
                            type    :"addmenu",
                            menuName: text,
                            parentId:config.curelemId,
                            menuFirstType:menuFirstType,
                            menusecondType:menusecondType
                        },success:function(data) {  
                             
                            if(data.error == 1){
                                var str = '<dd class="" id="subMenu_menu_1_1"><i class="icon_dot">●</i><a href="javascript:void(0);" class=""><strong>'+text+'</strong></a><span class="menu_opr"><a href="javascript:void(0);" id="orderBt" class=" " name="排序" style="display:none"><i class="fa fa-sort"></i></a></span></dd>';
                                config.parent.append(str);
                                alert("添加二级菜单成功");
                            }else{
                                alert("添加二级菜单失败");
                            }
                        },error:function(e){  
                            console.log("请求出错");
                        } 
                    });
                }
            }
        }else if(config.event === "editMenuName"){      //重命名
            var menuNewName = $("#editNameInput").val();

            var menuId = config.parent.attr("name");				//菜单ID
            var menuName = config.parent.find("strong").text();		//菜单名称
             
            $.ajax({
                url :   "../php/menu.php",
                dataType: "json",	
                async: false,	 
                data        : {
                    type    :"updatemenu",
                    menuId: menuId,
                    menuNewName:menuNewName
                },success:function(data) { 
                	console.log(data);
                	if(data === 1){
                	 	config.parent.find("a strong").text(menuNewName);
                	 	alert("更新成功");
                	}else if(data === 0){
                		alert("更新失败")
                	}
                	 
                },error:function(e){  
                    console.log(e);
                    console.log("请求出错");
                } 
            });
        }else if(config.event === "delMenu"){           //删除
        	
        	var menuId = config.parent.attr("name");				//菜单ID
            var menuName = config.parent.find("strong").text();		//菜单名称
           
            $.ajax({
                url         :   "../php/menu.php",
                dataType: "json",
                async: false,	
                data        : {
                    type    :"deletemenu",
                    menuId: menuId,
                    menuName:menuName
                },success:function(data) { 
                	console.log(data);
                	if(data.error === "删除成功"){
                		if(config.parent.prop("tagName") == "DT"){  //删除一级菜单
                			config.parent.parent().remove();	
                		}else{										//删除二级菜单
                			config.parent.remove();
                		}
                		// 重置内容
                		//更换右侧内容
			            $(".innermaincon_wrapinfo").removeClass("disable");
			            $(".innermaincon_wrap.textbtn,.innermaincon_wrap.editbtn,.innermaincon_wrap.delbtn,.innermaincon_wrap.addbtn").addClass("disable");

			            config.parent = '';//重置父元素
                	}
                	alert(data.error);
                },error:function(e){  
                    console.log(e);
                    console.log("请求出错");
                } 
            });
            
        }
        return false;
    });
    //弹出框取消
    $(".closealert").on('click',function(){
        $(".hideContent,.comtent").css("display","none");
        $(".add,.del,.edit").addClass("disable");
        return false;
    }); 

    // 增加菜单时点击切换文章类型
    $("body").delegate("#select_addmenu_selIdpar","change",function(){
        console.log($(this).find("option:selected").text());
        console.log($(this).val());  
        if($(this).val() == 'articlelist'){                         //文章列表
            var str = '<option value ="0">请选择栏目类型</option><option value ="picture">图文列表</option><option value ="video">视频列表</option><option value ="voice">音频列表</option>';
        }else if($(this).val() == 'activity'){                   //活动选项
            var str = '<option value ="0">请选择栏目类型</option><option value ="interact">图文互动</option><option value ="vote">投票</option><option value ="review">往期回顾</option>';
        }else if($(this).val() == 0){
             var str = '<option value ="0">请选择栏目类型</option>';   //两者没有选择
        }
        $("#select_addmenu_selIdchil").html(str);
    });
    // 增加一级菜单时处理菜单类型（空目录，菜单类型） 
    $("body").delegate(".contextareawrapradio","change",function(){
        var str = $(".contextareawrapradio input[type='radio']:checked").val();
        if(str === "menu"){
            config.firtype = 2;                     //菜单
            $(".select_addmenu_div").show();
        }else if(str === "null"){
            config.firtype = 1;                     //目录
            $(".select_addmenu_div").hide();
        }
    });

    // 点击具体菜单
    $("body").delegate("dd,dt","click",function(){
        // 移除并添加颜色选项
        $(this).siblings().removeClass("selected");
        $(this).parent().siblings().find("dd,dt").removeClass("selected");
        $(this).addClass("selected");

        config.parent =  $(this);               //点击具体菜单父元素
        
        if($( this ).prop("tagName") == "DT"){
            //更改右侧栏目名称
            var text = $(this).find("strong").html();
            $("#righttitle h4").html("一级菜单："+text);

            //更换右侧内容
            $(".innermaincon_wrapinfo").addClass("disable");
            $(".innermaincon_wrap.textbtn").addClass("disable");
            $(".innermaincon_wrap.editbtn,.innermaincon_wrap.delbtn,.innermaincon_wrap.addbtn").removeClass("disable");

        }else{
            var text = $(this).find("strong").html();
            $("#righttitle h4").html("二级菜单："+text);
            //更换右侧内容
            $(".innermaincon_wrapinfo").addClass("disable");
             $(".innermaincon_wrap.addbtn").addClass("disable");
            $(".innermaincon_wrap.textbtn,.innermaincon_wrap.editbtn,.innermaincon_wrap.delbtn").removeClass("disable");
        }




       // 显示图标内容暂时不能使用
       // $("a.btninfo").hover(function(){
       //      var str = ' <div style="position: absolute;height: 20px;line-height: 20px;width: 40px; top: -25px; background: #888;color: #FFFFFF; text-align: center;"><div style="position: absolute;bottom:-5px;left:5px; width: 0;height: 0;border-left: 5px solid transparent;border-right: 5px solid transparent;border-top: 6px solid #888;"></div>添加</div>';
       //      $(this).append(str);
       // },function(){
       //      //alert("可以使用");
       // });
     });
});