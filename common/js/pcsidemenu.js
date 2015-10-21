document.writeln(" <div class=\"sidebar\">");
document.writeln("        <!--快捷按钮组-->");
document.writeln("        <div class=\"sidebarshorcuts\">");
document.writeln("            <button class=\"btn btn-success\"><i class=\"fa fa-signal\"></i></button>");
document.writeln("            <button class=\"btn btn-info\"><i class=\"fa fa-pencil\"></i></button>");
document.writeln("            <button class=\"btn btn-warning\"><i class=\"fa fa-group\"></i></button>");
document.writeln("            <button class=\"btn btn-danger\"><i class=\"fa fa-cogs\"></i></button>");
document.writeln("        </div>");
document.writeln("        <!--列表导航-->");
document.writeln("        <ul class=\"navlist\" id=\"navlistId\">");
document.writeln("            <li><a href=\"../../adminindex/html/index.html\"><i class=\"fa fa-dashboard fatest\"></i>控制台</a></li>");
document.writeln("            <li>");
document.writeln("                <a href=\"../../pubTextPic/html/addarticle.html\"><i class=\"fa fa-desktop fatest\"></i>图文发布</a>");
document.writeln("            </li>");
document.writeln("            <li>");
document.writeln("                <a href=\"../../menu/html/menu.html\"><i class=\"fa fa-list fatest\"></i>菜单编辑</a>");
document.writeln("            </li>");
document.writeln("        </ul>");
document.writeln("    </div>");

$(function(){
	$.ajax({
        url         :   "../php/menu.php",
        datatype    :   "json",
        async       :   false,          //同步
        data        : {
            type    :"showmenu"
        },
        success:function(data) {  
            $.each(data,function(i,item){
                if(i == 0){
                    var str = '<li><a href="javascript:void(0); name='+item.id+'"><i class="fa fa-user fatest"></i>'+item.name+'<i class="fa fa-angle-down"></i></a>';
                }else if(i == 1){
                    var str = '<li><a href="javascript:void(0); name='+item.id+'"><i class="fa fa-life-ring fatest"></i>'+item.name+'<i class="fa fa-angle-down"></i></a>';
                }else if(i == 2){
                    var str = '<li><a href="javascript:void(0); name='+item.id+'"><i class="fa fa-graduation-cap fatest"></i>'+item.name+'<i class="fa fa-angle-down"></i></a>';
                }
                
                str += '<ul class="submenu">';
                $.each(item.sub,function(j,subitem){
                    str += '<li><a href="javascript:void(0)" name='+subitem.id+'>'+subitem.name+'</a></li>';
                });
                str += '</ul></li>';
                $("#navlistId").append(str);
            });       
        },
        error:function(e){  
            console.error("请求出错");
            console.error(e);
        } 
    });

    var str =  '<li><a href=""><i class="fa fa-cog fatest"></i>系统设置<i class="fa fa-angle-down"></i></a>';
    str += '<ul class="submenu"><li><a href="../../setting/html/editpwd.html">修改密码</a></li> <li><a href="javascript:void(0);">用户管理</a></li>';    
    str += '</ul></li>';
    $("#navlistId").append(str);

});