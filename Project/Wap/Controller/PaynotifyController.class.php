<?php

 
namespace Wap\Controller;

class PaynotifyController extends \Think\Controller
{
	public function wxchongzhi()
	{
		$postStr = $GLOBALS['HTTP_RAW_POST_DATA'];
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		if ($postObj->result_code == 'SUCCESS' && $postObj->return_code == 'SUCCESS') {
			vendor('wxjiaoyi.JsApiPay');
			$sysconfig = M('sys_config')->find();
			$cwxappid = $sysconfig['cwxappid'];
			$cwxmchid = $sysconfig['cwxmchid'];
			$cwxappkey = $sysconfig['cwxappkey'];
			$cwxappsecret = $sysconfig['cwxappsecret'];
			if ($sysconfig['cbeipay'] == 2) {
				$cwxappid = $sysconfig['cbeiappid'];
				$cwxmchid = $sysconfig['cbeimchid'];
				$cwxappkey = $sysconfig['cbeiappkey'];
				$cwxappsecret = $sysconfig['cbeiappsecret'];
			}
			define('WXCERTPATH', substr(THINK_PATH, 0, -9));
			define('WXAPPID', $cwxappid);
			define('WXMCHID', $cwxmchid);
			define('WXKEY', $cwxappkey);
			define('WXAPPSECRET', $cwxappsecret);
			$input = new \WxPayOrderQuery();
			$input->SetTransaction_id($postObj->transaction_id);
			$result = \WxPayApi::orderQuery($input);
			if (array_key_exists('return_code', $result) && array_key_exists('result_code', $result) && $result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
				$danhao = $postObj->out_trade_no;
				$danrow = M('user_chongzhi')->where('ddanhao=\'' . $danhao . '\' and dcode=1')->find();
				if ($danrow) {
					$userid = $danrow['userid'];
					$djine = $danrow['djine'];
					$user = M('user_list')->where('id=' . $userid)->find();
					M()->execute('update __USER_ZHANGHU__ set uqianchong=uqianchong+' . $djine . ',uchongzong=uchongzong+' . $djine . ' where userid=' . $userid);
					M()->execute('update __USER_CHONGZHI__ set dcode=2,djisuan=2 where ddanhao=\'' . $danhao . '\'');
					echo 'success';
				}
			}
		}
	}
}