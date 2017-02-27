<?php

 
namespace Wap\Controller;

class PayController extends \Think\Controller
{
	public function wxtixian($userid = 0, $utype = 2, $userhbid = 0)
	{
		$jine = 0;
		if ($utype == 1) {
			$userzhanghu = M('user_zhanghu')->where('userid=' . $userid)->find();
			$userhb = M('user_hb')->where('userid=' . $userid . ' and tcode=2 and id=' . $userhbid)->find();
			$jine = intval($userhb['hbe']);
			$hbid = intval($userhb['hbid']);
			$hb = M('hb')->where('id=' . $hbid)->find();
			if (intval($userzhanghu['uqianchong']) < intval($hb['hzhifue']) || intval($userzhanghu['uqianchong']) <= 0) {
				die;
			}
			$hzhifue = intval($hb['hzhifue']);
		}
		if ($utype == 2) {
			$jine = M('user_yongjin')->where('userid=' . $userid . ' and tcode=1')->sum('tixiane');
		}
		$user = M('user_list')->where('id=' . $userid)->find();
		if (100 <= intval($jine) && $user['ufacishu'] <= 99) {
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
			$wxHongBaoHelper->setParameter('amount', $jine);
			$wxHongBaoHelper->setParameter('re_user_name', '提现');
			$wxHongBaoHelper->setParameter('desc', '零钱入账');
			$wxHongBaoHelper->setParameter('spbill_create_ip', $wxHongBaoHelper->Getip());
			$postXml = $wxHongBaoHelper->create_hongbao_xml();
			$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
			$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
			$responseObj = simplexml_load_string($responseXml);
			if ($responseObj->result_code == 'SUCCESS' && $responseObj->return_code == 'SUCCESS') {
				if ($utype == 1) {
					M()->execute('update __USER_ZHANGHU__ set uqianchong=uqianchong-' . $hzhifue . ' where userid=' . $userid);
					M()->execute('update __USER_HB__ set tcode=1 where id=' . $userhbid);
					$yongjinma = rand(10000, 99999);
					session('yongjinma', $yongjinma);
					$this->yongjin($user['utid'], $hzhifue, $yongjinma);
				}
				if ($utype == 2) {
					M()->execute('update __USER_YONGJIN__ set tcode=2 where userid=' . $userid . ' and tcode=1');
					M()->execute('update __USER_ZHANGHU__ set uqianzheng=uqianzheng-' . $jine . ',uqianfa=uqianfa+' . $jine . ' where userid=' . $userid);
				}
				if (strtotime(date('Ymd')) < $user['ugengxin']) {
					M()->execute('update __USER_LIST__ set ufacishu=ufacishu+1 where id=' . $user['id']);
				} else {
					M('user_list')->save(array('id' => $user['id'], 'ufacishu' => 1, 'ugengxin' => time()));
				}
				return 2;
			} else {
				M('sys_log')->add(array('lbiaoshi' => '微信企业付款', 'lcon' => $postXml . $responseXml, 'ltime' => time()));
				return 3;
			}
		} else {
			return 4;
		}
	}
	public function yongjin($utid = 0, $jine = 0, $yongjinma = 0, $ytype = 2)
	{
		if ($yongjinma == session('yongjinma')) {
			session('yanzhengmasanji', null);
			$utuser = M('user_list')->where('id=' . $utid)->find();
			$fenxiao = M('fenxiao')->find();
			if ($fenxiao && $ytype == 2 && $utuser && 0 < $utid) {
				if (0 < $fenxiao['fjine1']) {
					M('user_yongjin')->add(array('userid' => $utid, 'tixiane' => $fenxiao['fjine1'], 'tjisuan' => 2, 'ttime' => time()));
					M()->execute('update __USER_ZHANGHU__ set uqianzheng=uqianzheng+' . $fenxiao['fjine1'] . ",\r\n\t\t\t\t\t   uzhengzong=uzhengzong+" . $fenxiao['fjine1'] . ' where userid=' . $utid);
				}
				$utid2 = intval($utuser['utid']);
				$user2 = M('user_list')->where('id=' . $utid2)->find();
				if ($user2 && 0 < $utid2) {
					if (0 < $fenxiao['fjine2']) {
						M('user_yongjin')->add(array('userid' => $utid2, 'tixiane' => $fenxiao['fjine2'], 'tjisuan' => 2, 'ttime' => time()));
						M()->execute('update __USER_ZHANGHU__ set uqianzheng=uqianzheng+' . $fenxiao['fjine2'] . ",\r\n\t\t\t\t\t\t  uzhengzong=uzhengzong+" . $fenxiao['fjine2'] . ' where userid=' . $utid2);
					}
					$utid3 = intval($user2['utid']);
					$user3 = M('user_list')->where('id=' . $utid3)->find();
					if ($user3 && 0 < $utid3) {
						if (0 < $fenxiao['fjine3']) {
							M('user_yongjin')->add(array('userid' => $utid3, 'tixiane' => $fenxiao['fjine3'], 'tjisuan' => 2, 'ttime' => time()));
							M()->execute('update __USER_ZHANGHU__ set uqianzheng=uqianzheng+' . $fenxiao['fjine3'] . ",\r\n\t\t\t\t\t\t\t   uzhengzong=uzhengzong+" . $fenxiao['fjine3'] . ' where userid=' . $utid3);
						}
					}
				}
			}
			if (0 < $utid && $utuser) {
				$sysconfig = M('sys_config')->find();
				if (0 < $sysconfig['ckouliang']) {
					$tixiannum = M('user_yongjin')->where('userid=' . $utid)->count();
					if (10 - $sysconfig['ckouliang'] <= $tixiannum % 10) {
						$data['tcode'] = $tcode = 4;
					}
				}
				$yongjindengji = intval($utuser['uvip']);
				$yongjinset = M('yongjin_set')->where('ydengji=' . $yongjindengji)->find();
				if ($yongjinset) {
					$tixiane = $jine * 0.01 * intval($yongjinset['ybaifenbi']);
					$data['userid'] = $utid;
					$data['uchong'] = $jine;
					$data['tixiane'] = $tixiane;
					$data['tjisuan'] = 2;
					$data['ttime'] = time();
					M('user_yongjin')->add($data);
					unset($data);
					if (intval($tcode) != 4) {
						M()->execute('update __USER_ZHANGHU__ set uqianzheng=uqianzheng+' . $tixiane . ",\r\n\t\t\t\t\t uzhengzong=uzhengzong+" . $tixiane . ' where userid=' . $utid);
					}
				}
				$yongjinset = M('yongjin_set')->where('ydengji=(' . $yongjindengji . '+1)')->find();
				if ($yongjinset) {
					$tixiane = M('user_yongjin')->where('userid=' . $utid . ' and tcode in(1,2)')->sum('uchong');
					if ($yongjinset['yjine'] <= $tixiane) {
						M()->execute('update __USER_LIST__ set uvip=uvip+1 where id=' . $utid);
					}
				}
			}
		}
	}
	public function saolei($userid = 0, $lingid = 0)
	{
		$user = M()->table('__USER_LIST__ a')->join('__USER_ZHANGHU__ b on a.id=b.userid')->where('a.id=' . $userid)->field('a.uopenid,b.uqianchong')->find();
		$linghb = M()->table('__SAOLEI_LINGHB__ a')->join('__SAOLEI_USERFALIST__ b on a.hblistid=b.id')->join('__SAOLEI_USERFA__ c on b.faid=c.id')->where('a.id=' . $lingid . ' and a.hcode=1')->field('a.hbe,a.userid,c.hzhifue,a.hblistid')->find();
		if (!$linghb) {
			die;
		}
		if ($userid != $linghb['userid']) {
			die;
		}
		if ($user['uqianchong'] < $linghb['hzhifue'] || $user['uqianchong'] == 0) {
			die;
		}
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
		$wxHongBaoHelper->setParameter('amount', $linghb['hbe']);
		$wxHongBaoHelper->setParameter('re_user_name', '提现');
		$wxHongBaoHelper->setParameter('desc', '零钱入账');
		$wxHongBaoHelper->setParameter('spbill_create_ip', $wxHongBaoHelper->Getip());
		$postXml = $wxHongBaoHelper->create_hongbao_xml();
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
		$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
		$responseObj = simplexml_load_string($responseXml);
		if ($responseObj->result_code == 'SUCCESS' && $responseObj->return_code == 'SUCCESS') {
			M()->execute('update __SAOLEI_LINGHB__ set hcode=2 where id=' . $lingid);
		} else {
			M()->execute('update __USER_ZHANGHU__ set uqianchong=uqianchong+' . $linghb['hbe'] . ' where userid=' . $userid);
			M()->execute('update __SAOLEI_LINGHB__ set hcode=3 where id=' . $lingid);
			M('sys_log')->add(array('lbiaoshi' => '微信企业付款', 'lcon' => $postXml . $responseXml, 'ltime' => time()));
		}
		M()->execute('update __SAOLEI_USERFALIST__ set hcode=2 where id=' . $linghb['hblistid']);
	}
}