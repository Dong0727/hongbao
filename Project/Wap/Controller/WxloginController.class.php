<?php

 
namespace Wap\Controller;

class WxloginController extends \Think\Controller
{
	public function index()
	{
		$urls = I('urls');
		$sysconfig = M('sys_config')->find();
		$ubeiopenid = $this->geturl($urls, 3);
		if ($sysconfig['cbeicode'] == 1 && $ubeiopenid == '') {
			$url = 'http://' . $_SERVER['HTTP_HOST'] . U('Wap/Wxlogin/getbeiuopenid?urls=' . $urls);
			$url = urlencode($url);
			$wxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $sysconfig['cbeiappid'] . '&redirect_uri=' . $url . '&response_type=code&scope=snsapi_base#wechat_redirect';
			header('Location:' . $wxurl);
		} else {
			if ($sysconfig['cdenglucode'] == 1) {
				$url = 'http://' . $_SERVER['HTTP_HOST'] . U('Wap/Wxlogin/getuopenid?urls=' . $urls);
				$url = urlencode($url);
				$wxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $sysconfig['cwxappid'] . '&redirect_uri=' . $url . '&response_type=code&scope=snsapi_base#wechat_redirect';
			} else {
				$url = 'http://' . $_SERVER['HTTP_HOST'] . U('Wap/Wxlogin/getinfo?urls=' . $urls);
				$url = urlencode($url);
				$wxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $sysconfig['cwxappid'] . '&redirect_uri=' . $url . '&response_type=code&scope=snsapi_userinfo#wechat_redirect';
			}
			header('Location:' . $wxurl);
		}
	}
	public function getuopenid()
	{
		$wxcode = I('code');
		$urls = I('urls');
		$config = M('sys_config')->find();
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $config['cwxappid'] . '&secret=' . $config['cwxappsecret'] . '&code=' . $wxcode . '&grant_type=authorization_code';
		$info = json_decode(http_curl_get($url));
		$uopenid = $info->openid;
		if ($uopenid == '') {
			echo '公众号授权登录失败->错误码->' . $info->errcode . '，解决方法：重置Appsecret';
			die;
		}
		$ubeiopenid = $this->geturl($urls, 3);
		$utid = $this->geturl($urls, 2);
		$this->checkuser($uopenid, $ubeiopenid, $utid);
	}
	public function getinfo()
	{
		$wxcode = I('code');
		$urls = I('urls');
		$config = M('sys_config')->find();
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $config['cwxappid'] . '&secret=' . $config['cwxappsecret'] . '&code=' . $wxcode . '&grant_type=authorization_code';
		$info = json_decode(http_curl_get($url));
		$uopenid = $info->openid;
		$wxtoken = $info->access_token;
		if ($uopenid == '') {
			echo '公众号授权登录失败->错误码->' . $info->errcode . '，解决方法：重置Appsecret';
			die;
		}
		$url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $wxtoken . '&openid=' . $uopenid . '&lang=zh_CN';
		$userinfo = json_decode(http_curl_get($url));
		$ubeiopenid = $this->geturl($urls, 3);
		$utid = $this->geturl($urls, 2);
		$uickname = filter_Emoji($userinfo->nickname);
		$usex = intval($userinfo->sex);
		$uheadimgurl = $userinfo->headimgurl;
		$udizhi = $userinfo->province . $userinfo->city;
		$this->checkuser($uopenid, $ubeiopenid, $utid, $uickname, $usex, $uheadimgurl, $udizhi);
	}
	public function checkuser($uopenid = '', $ubeiopenid = '', $utid = 0, $uickname = '', $usex = 0, $uheadimgurl = '', $udizhi = '')
	{
		$user = M('user_list')->where('uopenid=\'' . $uopenid . '\'')->find();
		$ubeiser = M('user_list')->where('ubeiopenid=\'' . $ubeiopenid . '\' and ubeiopenid<>\'\'')->find();
		$config = M('sys_config')->find();
		if (!$user) {
			if ($ubeiser) {
				M('user_list')->save(array('id' => $ubeiser['id'], 'uopenid' => $uopenid));
				$userid = $ubeiser['id'];
			} else {
				$utuser = M('user_list')->where('id=' . $utid)->find();
				if (0 < $utuser['uvip']) {
					$data['utid'] = $utid;
				}
				if ($config['cdailicode'] == 2) {
					$yongjinset = M('yongjin_set')->order('ydengji asc')->find();
					$data['uvip'] = intval($yongjinset['ydengji']) == 0 ? 1 : intval($yongjinset['ydengji']);
				}
				$data['uopenid'] = $uopenid;
				$data['ubeiopenid'] = $ubeiopenid;
				$data['uickname'] = $uickname;
				$data['usex'] = $usex;
				$data['uheadimgurl'] = $uheadimgurl;
				$data['udizhi'] = $udizhi;
				$data['uregtime'] = time();
				$data['ulogintime'] = time();
				$userid = M('user_list')->add($data);
				unset($data);
				M('user_zhanghu')->add(array('userid' => $userid));
			}
		} else {
			if ($ubeiopenid != '' && $user['ubeiopenid'] != $ubeiopenid) {
				$data['ubeiopenid'] = $ubeiopenid;
			}
			if ($uickname != '') {
				$data['uickname'] = $uickname;
				$data['usex'] = $usex;
				$data['uheadimgurl'] = $uheadimgurl;
				$data['udizhi'] = $udizhi;
			}
			$data['ulogintime'] = time();
			$data['id'] = $userid = $user['id'];
			M('user_list')->save($data);
			unset($data);
		}
		session('geingxin', 1);
		session('userid', $userid);
		$this->redirect('Index/index');
	}
	public function getbeiuopenid()
	{
		$wxcode = I('code');
		$urls = I('urls');
		$sysconfig = M('sys_config')->find();
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $sysconfig['cbeiappid'] . '&secret=' . $sysconfig['cbeiappsecret'] . '&code=' . $wxcode . '&grant_type=authorization_code';
		$info = json_decode(http_curl_get($url));
		$ubeiopenid = $info->openid;
		if ($ubeiopenid == '') {
			echo '备份公众号授权登录失败->错误码->' . $info->errcode . '，解决方法：重置Appsecret';
			die;
		}
		$headurl = $sysconfig['cfaurl'] . U('Wap/Index/index?utid=' . $this->geturl($urls, 2) . '&ubeiopenid=' . $ubeiopenid);
		header('Location:' . $headurl);
	}
	public function geturl($urlsquery = '', $val = 1)
	{
		$urlarr = explode('|', $urlsquery);
		foreach ($urlarr as $v) {
			if (stripos($v, 'm-') !== false) {
				$m = substr($v, 2);
				continue;
			}
			if (stripos($v, 'c-') !== false) {
				$c = substr($v, 2);
				continue;
			}
			if (stripos($v, 'a-') !== false) {
				$a = substr($v, 2);
				continue;
			}
			if (stripos($v, 'utid-') !== false) {
				$utid = substr($v, 5);
				continue;
			}
			if (stripos($v, 'ubeiopenid-') !== false) {
				$ubeiopenid = substr($v, 11);
				continue;
			}
		}
		$urls = 'index.php?m=' . $m . '&c=' . $c . '&a=' . $a . '&utid=' . $utid;
		if ($val == 1) {
			return $urls;
		} else {
			if ($val == 2) {
				return intval($utid);
			} else {
				if ($val == 3) {
					return $ubeiopenid;
				}
			}
		}
	}
	public function mainfo()
	{
		$utid = I('utid', 0, 'intval');
		if ($utid == 0) {
			$utid = I('path.3', 0, 'intval');
		}
		$config = M('sys_config')->find();
		if ($config['cbeicode'] == 1) {
			$url = $config['cbeiurl'] . U('Wap/Index/index?utid=' . $utid);
		} else {
			$url = $config['cfaurl'] . U('Wap/Index/index?utid=' . $utid);
		}
		header('Location:' . $url);
	}
}