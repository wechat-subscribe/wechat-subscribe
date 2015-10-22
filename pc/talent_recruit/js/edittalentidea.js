/**
 * Created by sun on 2015/9/29.
 */
// 编辑人才理念

//获取url信息
 function GetQueryString(name) { 
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
    var r = window.location.search.substr(1).match(reg); 
    if (r != null) return unescape(r[2]); return null; 
} 
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
	var infoId = '';		//用于测试的Id

    var ue = UE.getEditor('editor');	//实例化UE编辑器
    // 请求人才理念内容
    $.ajax({
        url         :   "../php/talent_philosophy.php?type=show",
        datatype    :   "json",
        type      	:   'POST',         //默认为GET方式
        async       :   true,          //异步
       
        success:function(data) {  
            data = JSON.parse(data);
            if(data.empty != true){
                data = data.show;
            	$("#title").val(data[0].title);  //标题
	            ue.addListener("ready", function () {		//内容
	                UE.getEditor('editor').execCommand('insertHtml', data[0].content);
	            });  
	            
	            //栏目图
	            $("#uploadpic i").remove();                     //清除背景图
	            var $img = $(" <img src='"+data[0].picture+"' id='imgurl' alt='' onclick=\"getElementById('thumbpic').click()\" />");
	            $("#uploadpic input").before($img);

	            infoId = data[0].id;
            }
            
        },
        error:function(e){  
            console.log("请求出错");
        }
    }); 	
 	


    $("#submit").bind("click",function(){
        

        var title 		= $("#title").val();  //标题
        //var content 	= UE.getEditor('editor').getPlainTxt();    
        //var content = UE.getEditor('editor').getAllHtml();
        var content = ue.getContent();      //带格式的文章内容
        //console.log(title);
        //console.log(content);
        if(title == '' || content == ''){
        	alert("请检查数据不能为空");
        }else{
            console.log(infoId);

            $.ajax({
                url         :   "../php/talent_philosophy.php?type=updateInfoOK",
                datatype    :   "json", 
                type        :   'POST',         
                async       :   true,          //异步
                data        : {
                	"infoId" 		: infoId,
                    "title"         : title,           
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
            url:'../php/talent_philosophy.php?type=updateInfoOK&subtype=picture',
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