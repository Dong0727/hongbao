<?php

 
namespace Wap\Controller;

class ChongzhiController extends CommonController
{
	public function chong()
	{
		$ctype = I('ctype', 1, 'intval');
		$hbid = I('hbid', 0, 'intval');
		$djine = I('djine', 0, 'intval') * 100;
		$userid = session('userid');
		$user = M('user_list')->where('id=' . $userid)->find();
		if ($ctype == 1 || $ctype == 2 || $ctype == 4) {
			$hb = M('hb')->where('id=' . $hbid)->find();
			$djine = intval($hb['hzhifue']);
		}
		$data['ddanhao'] = $danhao = date('YmdHis') . rand(100, 999);
		$data['userid'] = $userid;
		$data['djine'] = $djine;
		$data['dtime'] = time();
		M('user_chongzhi')->add($data);
		unset($data);
		$sysconfig = M('sys_config')->find();
		$cwxappid = $sysconfig['cwxappid'];
		$cwxmchid = $sysconfig['cwxmchid'];
		$cwxappkey = $sysconfig['cwxappkey'];
		$cwxappsecret = $sysconfig['cwxappsecret'];
		$uopenid = $user['uopenid'];
		if ($sysconfig['cbeipay'] == 2) {
			$cwxappid = $sysconfig['cbeiappid'];
			$cwxmchid = $sysconfig['cbeimchid'];
			$cwxappkey = $sysconfig['cbeiappkey'];
			$cwxappsecret = $sysconfig['cbeiappsecret'];
			$uopenid = $user['ubeiopenid'];
		}
		define('WXAPPID', $cwxappid);
		define('WXMCHID', $cwxmchid);
		define('WXKEY', $cwxappkey);
		define('WXAPPSECRET', $cwxappsecret);
		vendor('wxjiaoyi.JsApiPay');
		$input = new \WxPayUnifiedOrder();
		$input->SetBody('充值');
		$input->SetOut_trade_no($danhao);
		$input->SetTotal_fee($djine);
		$input->SetNotify_url('http://' . $_SERVER['HTTP_HOST'] . __ROOT__ . '/index.php/Wap/Paynotify/wxchongzhi.html');
		$input->SetTrade_type('JSAPI');
		$input->SetLimit_pay('no_credit');
		$input->SetOpenid($uopenid);
		$order = \WxPayApi::unifiedOrder($input);
		if ($order[return_code] == 'FAIL') {
			M('sys_log')->add(array('lbiaoshi' => '微信支付', 'lcon' => var_export($order, true), 'ltime' => time()));
		}
		$jsapipay = new \JsApiPay();
		$jsApiParameters = $jsapipay->GetJsApiParameters($order);
		$this->hb = $hb;
		$this->hbid = $hb['id'];
		$this->sysconfig = $sysconfig;
		$this->jsApiParameters = $jsApiParameters;
		if ($ctype == 1) {
			$this->display('Index:pay');
		}
		if ($ctype == 2) {
			$this->display('Zhuanpan:pay');
		}
		if ($ctype == 3) {
			$this->display('Ucenter:chongzhi');
		}
		if ($ctype == 4) {
			$this->display('Guaguale:pay');
		}
	}
}