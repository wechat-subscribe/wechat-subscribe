<?php 
	
	/** 
	 * 使用代理方式
	 * 封装微信操作接口函数
	 */
 
 	/**
 	* 全局变量
 	*/
	define("TOKEN", "testtoken"); 
	define('APP_ID', 'wxd7f18d2e562e7fa6'); 
	define('APP_SECRET', '0091a3a8185f5d3ae9e7fcf5d4507239'); 
	
	/**
	* 路由机制
	*/
	$wechatObj = new wechatCallbackApi(APP_ID, APP_SECRET); 
	$methodName  = $_GET['type'];
	
	if( in_array($methodName , get_class_methods('wechatCallbackApi')) ) {			//判断类里面是否存在该方法
		$wechatObj->$methodName();
	}else {
		return phpinfo();
	}

	/*if($methodName == "getAccess_token") {
		$wechatObj->getAccess_token(); 
	}else if($methodName == "creatMenu"){
		$wechatObj->creatMenu(); 
	}else {
		echo phpinfo();
	}*/



class wechatCallbackApi 
{ 	
	private $fromUsername; 
    private $toUsername; 
    private $times; 
    private $keyword; 
    private $app_id; 
    private $app_secret; 

	public function __construct($appid,$appsecret) { 
        $this->app_id = $appid; 
        $this->app_secret = $appsecret; 
    } 

    /** 
     * 获取access_token 
     */ 
    public function getAccess_token() { 
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->app_id."&secret=".$this->app_secret; 
		$data = json_decode(file_get_contents($url),true); 
        if($data['access_token']){ 
            return $data['access_token']; 
        }else{ 
            return "获取access_token错误"; 
        }
		// $result = file_get_contents($url); 
		// header('Content-type:text/json');
		// return $result;
    } 

	
	/**
	 * 自定义菜单创建
	 */
	public function creatMenu() {
		 
		$access_token = $this->getAccess_token();
		$url 	=  "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
		
	    $curl = curl_init();  
	    curl_setopt($curl, CURLOPT_URL, $url); 				//设置url
	    //curl_setopt($curl, CURLOPT_HEADER, 1);			//设置头文件的信息作为数据流输出
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);		//设置获取的信息以文件流的形式返回，而不是直接输出
	    curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, FALSE);	//不需要验证ssl
		curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, FALSE);	
	    curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, 5);
	    curl_setopt($curl, CURLOPT_POST, 1);				//设置post方式提交
	   
	    $arr = array(  
            'button' =>array( 
                array( 
                    'name' =>	urlencode("生活查询"), 
                    'type' => 	'click',  
			        'key'  =>  	'V1001_TODAY_MUSIC' 
                ), 
                array( 
                    'name'=>urlencode("轻松娱乐"), 
                    'sub_button'=>array( 
                        array( 
			               'name' => urlencode("搜索"),
			               'type' => "view",
			               'url'  => "http://www.soso.com/"
                        ), 
                        array( 
                            'name' => urlencode("幸运大转盘"), 
                            'type' => "view",
			                'url'  => "http://www.baidu.com/"
                        ) 
                    ) 
                ), 
                array( 
                    'name'=>urlencode("我的信息"), 
                    'sub_button'=>array( 
                        array( 
                            'name' => urlencode("关于我"), 
                            'type' => 'click', 
                            'key'  => 'VCX_ABOUTME' 
                        ), 
                        array( 
                            'name' => urlencode("联系我们"),
                            'type' => "view",
                            'url'  => "http://www.leida.club/testwechatown/nav.html"
                        ) 
                    ) 
                ) 
            ) 
        ); 
        $json_data = urldecode(json_encode($arr)); 

	    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
	    $result 	= curl_exec($curl);
		curl_close($curl);
		
		header('Content-type:text/json');
		echo $result;
	}

	/**
	 * 自定义菜单查询(GET请求)
	 */
	public function getMenu() {

		$access_token = $this->getAccess_token();
		$url 	=  "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$access_token;
		$data = file_get_contents($url);

		header('Content-type:text/json');
		echo $data;
	}

	/**
	 * 自定义菜单删除
	 */
	public function delMenu() {
		// https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=ACCESS_TOKEN

		$access_token = $this->getAccess_token();
		$url 	=  "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$access_token;
		$data = file_get_contents($url);

		header('Content-type:text/json');
		echo $data;
	}



	/**
	 * 获取微信服务器IP(GET请求)
	 */
 	public function getWechatServerIp() {
	 	
		$access_token = $this->getAccess_token();
		$url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$access_token;
		$data = file_get_contents($url);

		header('Content-type:text/json');
		echo $data;
		
		// $data = json_decode(file_get_contents($url),true); 
		// if($data['ip_list']){ 
		// 	header('Content-type:text/json');
		// 	echo $data['ip_list'][0];
		// }
	}
}
	/**
	* 获取access_token（GET方法示例）
	*/
	/*public function getAccess_token() {
		global $GRANT_TYPE;
		global $APPID;
		global $APPSECRET;
		global $ACCESS_TOKEN;

		$grant_type	=  	isset($_GET['grant_type']) 	? $_GET['grant_type'] 	: $GRANT_TYPE;
		$appid     	=  	isset($_GET['appid'])		? $_GET['appid'] 		: $APPID;
		$secret		=  	isset($_GET['secret']) 		? $_GET['secret'] 		: $APPSECRET;
 
		$getAccessTokenUrl  = "https://api.weixin.qq.com/cgi-bin/token?grant_type=".$grant_type."&appid=".$appid."&secret=".$secret;			 

		$curl = curl_init();
		curl_setopt ($curl, CURLOPT_URL, $getAccessTokenUrl);
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, FALSE);			//不需要验证ssl
		curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, FALSE);	
		curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, 5);
		$http_code 		= curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		$result 		= curl_exec($curl);
		curl_close($curl);
		$arr 			= json_decode($result,true);				//将第二个参数为true时将转化为数组  
		$ACCESS_TOKEN 	= $arr['access_token'];
		header('Content-type:text/json');
		echo $result;
	}*/

?>