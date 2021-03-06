/**
 * Created by sun on 2015/9/29.
 */

 
    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    //var ue = UE.getEditor('editor');


    /*function isFocus(e){
        alert(UE.getEditor('editor').isFocus());
        UE.dom.domUtils.preventDefault(e)
    }
    function setblur(e){
        UE.getEditor('editor').blur();
        UE.dom.domUtils.preventDefault(e)
    }
    function insertHtml() {
        var value = prompt('插入html代码', '');
        UE.getEditor('editor').execCommand('insertHtml', value)
    }
    function createEditor() {
        enableBtn();
        UE.getEditor('editor');
    }
    function getAllHtml() {
        alert(UE.getEditor('editor').getAllHtml())
    }
    function getContent() {
        var arr = [];
        arr.push("使用editor.getContent()方法可以获得编辑器的内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getContent());
        alert(arr.join("\n"));
    }
    function getPlainTxt() {
        var arr = [];
        arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getPlainTxt());
        alert(arr.join('\n'))
    }
    function setContent(isAppendTo) {
        //var arr = [];
        //arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
        UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
        //alert(arr.join("\n"));
    }
    function setDisabled() {
        UE.getEditor('editor').setDisabled('fullscreen');
        disableBtn("enable");
    }

    function setEnabled() {
        UE.getEditor('editor').setEnabled();
        enableBtn();
    }

    function getText() {
        //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
        var range = UE.getEditor('editor').selection.getRange();
        range.select();
        var txt = UE.getEditor('editor').selection.getText();
        alert(txt)
    }

    function getContentTxt() {
        var arr = [];
        arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
        arr.push("编辑器的纯文本内容为：");
        arr.push(UE.getEditor('editor').getContentTxt());
        alert(arr.join("\n"));
    }
    function hasContent() {
        var arr = [];
        arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
        arr.push("判断结果为：");
        arr.push(UE.getEditor('editor').hasContents());
        alert(arr.join("\n"));
    }
    function setFocus() {
        UE.getEditor('editor').focus();
    }
    function deleteEditor() {
        disableBtn();
        UE.getEditor('editor').destroy();
    }
    function disableBtn(str) {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            if (btn.id == str) {
                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
            } else {
                btn.setAttribute("disabled", "true");
            }
        }
    }
    function enableBtn() {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
        }
    }

    function getLocalData () {
        alert(UE.getEditor('editor').execCommand( "getlocaldata" ));
    }

    function clearLocalData () {
        UE.getEditor('editor').execCommand( "clearlocaldata" );
        alert("已清空草稿箱")
    }
    */

$(function(){

    var ue = UE.getEditor('editor');	//实例化UE编辑器
    
    var articleid = GetQueryString("articleid") ;
    if(articleid != null){

        // 请求文章内容
        $.ajax({
            url         :   "../php/information_show.php",
            datatype    :   "json",
            //type      :   'POST',         //默认为GET方式
            async       :   false,          //异步
            data        : {
                'type'      : "updateInfo",
                'infoId'    : articleid,
            },
            success:function(data) {  
                data = JSON.parse(data);
                //文章内容
                $("#title").val(data.title);
                ue.addListener("ready", function () {
                    //ue.setContent(data.content);
                    //UE.getEditor('editor').execCommand('insertHtml', data.content);
                    UE.getEditor('editor').execCommand('insertHtml', data.content);
                });
                //缩略图
                $("#uploadpic i").remove();                     //清除背景图
                    var $img = $(" <img src='"+data.thumb+"' id='imgurl' alt='' onclick=\"getElementById('thumbpic').click()\" />");
                    $("#uploadpic input").before($img);
                
                if(data.is_leaveword){
                      $("#leaveword").attr("checked","checked");
                }
                if(data.is_zan){
                    $("#zan").attr("checked","checked");
                }
            },
            error:function(e){  
                console.log("请求出错");
            } 
        });
        
        // 请求评论内容
        $.ajax({
            url         :   "../php/information_show.php",
            datatype    :   "json",
            //type      :   'POST',         //默认为GET方式
            async       :   false,          //异步
            data        : {
                'type'      : "details",
                'infoId'    : articleid,
                'page'      :1
            },
            success:function(data) {  
                data = JSON.parse(data);
                console.log(data);
                if(data.num_leaveword == 0||data.num_leaveword == undefined){        //不存在评论 
                    $(".nocomment").css("display","block");

                }else{
                
                      $.each(data.leaveword,function(i,item){
                            var str = '<tr><td class="checkbox"><input type="checkbox"/></td>';
                            str += '<td><a href="">'+item.content+'</a></td>';
                            str += ' <td>'+item.date+'</td>';
                            str += '<td class="actionicon"> <i class="fa fa-trash red"></i></td></tr>';

                            $("#commenttbody").append(str);
                      });
                    $(".pageinput").val("1");
                   
                    $(".tablediv").show();

                }
            },
            error:function(e){  
                console.log("请求出错");
            } 
        });

    }else{
        $(".nocomment").css("display","block");
    }

    //切换tab
    $("a[data-toggle=tab]").bind("click",function(){
        $(this).parent().siblings().find("a").removeClass("active");
        $(this).addClass("active");
        if($(this).attr("href") == "article"){
            $(".comment").hide();
            $(".article").show();
        }else{
            $(".article").hide();
            $(".comment").show();
        }
        return false;
    });


    $("#submit").bind("click",function(){
        //console.log(getAllHtml());

        var title 		= $("#title").val();  //标题
        var leaveword 	= ($("#leaveword").attr("checked")=="checked")?1:0;
        var zan 		= ($("#zan").attr("checked")=="checked")?1:0;
        //var content 	= UE.getEditor('editor').getPlainTxt();  
        //var content2 = UE.getEditor('editor').getAllHtml();
        var content = ue.getContent();      //带格式的文章内容
        if(title == '' || content == ''){
        	alert("请检查数据不能为空");
        }else{
            if(articleid != null){
                $.ajax({
                    url         :   "../php/information_show.php?type=updateInfoOK",
                    datatype    :   "json", 
                    async       :   true,          //异步
                    data        : {
                        'infoId'        : articleid,
                        "title"         : title,
                        "is_leaveword"  : leaveword,
                        "is_zan"        : zan,
                        "content"       : content
                    },
                    success:function(data) {  
                        console.log(data);
                        if(data == 1){
                            alert("更新成功");
                        }else if(data == 2){
                            alert("请检查数据不能为空");
                        }else if(data == 0){
                            alert("更新失败");
                        }
                       
                    },
                    error:function(e){  
                        console.log("请求出错");
                    }
                });
            }else{
                var moduleId = GetQueryString("moduleId") ;
                
                $.ajax({
                    url         :   "../php/information_upload.php",
                    datatype    :   "json", 
                    type        :   'POST',         
                    async       :   true,          //异步
                    data        : {
                        "moduleId"      : moduleId,
                        "title"         : title,
                        "leaveword"  : leaveword,
                        "zan"        : zan,
                        "content"       : content
                    },
                    success:function(data) {  
                        console.log(data);
                        if(data == 1){
                            alert("添加成功");
                        }else if(data == 2){
                            alert("请检查数据不能为空");
                        }else if(data == 0){
                            alert("添加失败");
                        }
                       
                    },
                    error:function(e){  
                        console.log("请求出错");
                    }
                });
            }
        	
        }
        return false;
    });

    //缩略图片上传及时显示
    $("#thumbpic").change(function(){
        var data = new FormData();      //创建FormData对象
        //为FormData对象添加数据
        $.each($('#thumbpic')[0].files, function(i, file) {
            data.append('upload_file', file);
        });
        $.ajax({
            url:'../php/information_show.php?type=updateInfoOK&subtype=thumb',
            type:'POST',
            data:data,
            cache: false,
            contentType: false,      //不可缺
            processData: false,      //不可缺
            success:function(data){
            	console.log(data);
                if($("#uploadpic").children('img').length == 0) {
                    $("#uploadpic i").remove();             //清除背景图
                    var $img = $(" <img src='"+data+"' id='imgurl' alt='' onclick=\"getElementById('thumbpic').click()\" />");
                    $("#uploadpic input").before($img);
                } else {
                    $("#uploadpic img").remove();           //清除已存在图片
                    var $img = $(" <img src='"+data+"' alt='' onclick=\"getElementById('thumbpic').click()\" />");
                    $("#uploadpic input").before($img);
                }
            }
        });
    });
    
});