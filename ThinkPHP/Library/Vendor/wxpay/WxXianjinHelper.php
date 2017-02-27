<?php
/**
* 微信现金
*
*/
include_once("CommonUtil.php");
include_once("SDKRuntimeException.class.php");
include_once("MD5SignUtil.php");
class WxHongBaoHelper
{
	var $parameters; //cft 参数
	function __construct()
	{
		
	}
	function setParameter($parameter, $parameterValue) {
		$this->parameters[CommonUtil::trimString($parameter)] = CommonUtil::trimString($parameterValue);
	}
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	protected function create_noncestr( $length = 30 ) {  
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
		$str ="";  
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
			//$str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
		}  
		return $str;  
	}
	function check_sign_parameters(){
		/*
		if($this->parameters["nonce_str"] == null || 
			$this->parameters["mch_billno"] == null || 
			$this->parameters["mch_id"] == null || 
			$this->parameters["wxappid"] == null || 
			$this->parameters["send_name"] == null ||
			$this->parameters["re_openid"] == null || 
			$this->parameters["total_amount"] == null || 
			$this->parameters["total_num"] == null || 
			$this->parameters["wishing"] == null || 
			$this->parameters["client_ip"] == null || 
			$this->parameters["act_name"] == null || 
			$this->parameters["remark"] == null 
		){
			$commonUtil = new CommonUtil();
			$content=$commonUtil->arrayToXml($this->parameters);
			$toppath="sign.txt";
			$Ts=fopen($toppath,"a+");
			fputs($Ts,$content."\r\n");
			fclose($Ts);
			return false;
		}
		*/
		return true;
	}
	protected function get_sign(){
		try {
			if (null == PARTNERKEY || "" == PARTNERKEY ) {
				throw new SDKRuntimeException("");
			}
			if($this->check_sign_parameters() == false) { 
			   throw new SDKRuntimeException("");
		    }
			$commonUtil = new CommonUtil();
			ksort($this->parameters);
			$unSignParaString = $commonUtil->formatQueryParaMap($this->parameters, false);

			$md5SignUtil = new MD5SignUtil();
			return $md5SignUtil->sign($unSignParaString,$commonUtil->trimString(PARTNERKEY));
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
	}
	
	function create_hongbao_xml($retcode = 0, $reterrmsg = "ok"){
		try {
		    //var_dump($this->parameters);
		    $this->setParameter('sign', $this->get_sign());
		    //var_dump($this->parameters);
		    $commonUtil = new CommonUtil();
		    return  $commonUtil->arrayToXml($this->parameters);
		   
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}		
	}
	
	function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
	{
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		//这里设置代理，如果有的话
		//curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
		//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		
		//以下两种方式需选择一种
		//第一种方法，cert 与 key 分别属于两个.pem文件
		curl_setopt($ch,CURLOPT_SSLCERT,CERTPATH.'Uploads/wxcacert/apiclient_cert.pem');
 		curl_setopt($ch,CURLOPT_SSLKEY,CERTPATH.'Uploads/wxcacert/apiclient_key.pem');
 		curl_setopt($ch,CURLOPT_CAINFO,CERTPATH.'Uploads/wxcacert/rootca.pem');
		
		//第二种方式，两个文件合成一个.pem文件
		//curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
	 
		if( count($aHeader) >= 1 ){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
		}
	 
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		$data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		}
		else { 
			$error = curl_errno($ch);
			echo "errorCode:$error\n"; 
			curl_close($ch);
			return false;
		}
	}
   function Getip(){
      if($_SERVER['HTTP_CLIENT_IP']){
         $onlineip=$_SERVER['HTTP_CLIENT_IP'];//HTTP_CLIENT_IP 客户端，及浏览器所在的电脑，的ip地址
      } elseif ($_SERVER['HTTP_X_FORWARDED_FOR']){
         $onlineip=$_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
         $onlineip=$_SERVER['REMOTE_ADDR'];
      }
	  if(strlen($onlineip) > 15){
		  $onlineip=$_SERVER['REMOTE_ADDR'];
	  }
	  return $onlineip;
   }
   function curl_get_ssl($url){
	  $file_content="";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); 
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $file_content = curl_exec($ch); 
      curl_close($ch);
  	 return $file_content;
   }
}
?>