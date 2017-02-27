<?php

 
namespace Wap\Controller;

class TixianController extends CommonController
{
	public function wxtixian()
	{
		$userid = session('userid');
		$userzhanghu = M('user_zhanghu')->where('userid=' . $userid)->find();
		$tixiane = intval($userzhanghu['uqianchong']);
		if (100 <= intval($tixiane)) {
			$user = M('user_list')->where('id=' . $userid)->find();
			$sysconfig = M('sys_config')->find();
			define('CERTPATH', substr(THINK_PATH, 0, -9));
			define('PARTNERKEY', $sysconfig['cwxappkey']);
			vendor('wxpay.WxXianjinHelper');
			$commonUtil = new \CommonUtil();
			$wxHongBaoHelper = new \WxHongBaoHelper();
			$wxHongBaoHelper->setParameter('nonce_str', $commonUtil->create_noncestr());
			$wxHongBaoHelper->setParameter('partner_trade_no', date('YmdHis') . rand(100, 999));
			$wxHongBaoHelper->setParameter('mchid', $sysconfig['cwxmchid']);
			$wxHongBaoHelper->setParameter('mch_appid', $sysconfig['cwxappid']);
			$wxHongBaoHelper->setParameter('openid', $user['uopenid']);
			$wxHongBaoHelper->setParameter('check_name', 'NO_CHECK');
			$wxHongBaoHelper->setParameter('amount', $tixiane);
			$wxHongBaoHelper->setParameter('re_user_name', '提现');
			$wxHongBaoHelper->setParameter('desc', '零钱入账');
			$wxHongBaoHelper->setParameter('spbill_create_ip', $wxHongBaoHelper->Getip());
			$postXml = $wxHongBaoHelper->create_hongbao_xml();
			$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
			$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
			$responseObj = simplexml_load_string($responseXml);
			if ($responseObj->result_code == 'SUCCESS' && $responseObj->return_code == 'SUCCESS') {
				M()->execute('update __USER_ZHANGHU__ set uqianchong=uqianchong-' . $tixiane . ' where userid=' . $userid);
				M('user_tixian')->add(array('userid' => $userid, 'tixiane' => $tixiane, 'ttime' => time()));
				$json['code'] = 1;
			} else {
				M('sys_log')->add(array('lbiaoshi' => '用户提现', 'lcon' => $postXml . $responseXml, 'ltime' => time()));
				$json['code'] = 2;
			}
		} else {
			$json['code'] = 3;
		}
		$this->ajaxReturn($json, 'json');
	}
}