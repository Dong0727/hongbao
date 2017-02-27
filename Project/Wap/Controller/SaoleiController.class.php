<?php

 
namespace Wap\Controller;

class SaoleiController extends CommonController
{
	public function index()
	{
		$hbnum = M('saolei_hbset')->count();
		if (1 < $hbnum) {
			$hb = M('saolei_hbset')->order('hpaixu desc')->select();
		} else {
			$hb = M('saolei_hbset')->find();
			$this->redirect('hb?hbid=' . $hb['id']);
			die;
		}
		$this->hb = $hb;
		$this->display();
	}
	public function hb()
	{
		$userid = session('userid');
		$hbid = I('hbid', 0, 'intval');
		$hbset = M('saolei_hbset')->where('id=' . $hbid)->find();
		$fahb = M()->table('__SAOLEI_USERFALIST__ a')->join('__SAOLEI_USERFA__ b on a.faid=b.id')->where('b.hbid=' . $hbid . ' and a.hcode=1')->field('a.id')->order('a.id asc')->limit(20)->select();
		$this->hbset = $hbset;
		$this->fahb = $fahb;
		$this->display();
	}
	public function ajaxfahb()
	{
		$hbid = I('hbid', 0, 'intval');
		$hweishu = I('hweishu', 0, 'intval');
		$userid = session('userid');
		$hbset = M('saolei_hbset')->where('id=' . $hbid)->find();
		if (!$hbset) {
			die;
		}
		$json['code'] = $yuecode = $this->checkzhanghu($hbset['hbzhifu']);
		if ($yuecode == 2) {
			$data = array('userid' => $userid, 'hbid' => $hbid, 'hzhifue' => $hbset['hbzhifu'], 'hgeshu' => $hbset['hgeshu'], 'hweishu' => $hweishu, 'htime' => time());
			$faid = M('saolei_userfa')->add($data);
			unset($data);
			$weikongzhi = $hbset['hgeshu'] * 10;
			$zongqian = $hbset['hbzhifu'];
			$faqian = $zongqian - $hbset['hgeshu'] * 100 - $weikongzhi;
			for ($i = 1; $i <= $hbset['hgeshu']; $i++) {
				$hbarr[] = rand(0, $faqian);
			}
			$hbarrnum = array_sum($hbarr);
			foreach ($hbarr as $k => $v) {
				$hbarr[$k] = (int) ($v / $hbarrnum * $faqian) + 100;
			}
			$hbarr[0] = $hbarr[0] + $zongqian - array_sum($hbarr) - $weikongzhi;
			if ($hbset['hweiduo'] != 10) {
				$geshu = 0;
				$arr = array();
				foreach ($hbarr as $k => $v) {
					$wei = substr($v, -1);
					if ($wei == $hbset['hweiduo']) {
						$geshu++;
					} else {
						$arr[] = $k;
					}
				}
				for ($j = 0; $j < 3 - $geshu; $j++) {
					$wei = substr($hbarr[$arr[$j]], -1);
					$cha = $wei - $hbset['hweiduo'];
					if (0 <= $cha) {
						$zhi = $hbarr[$arr[$j]] - $cha;
						if (100 <= $zhi) {
							$hbarr[$arr[$j]] = $zhi;
							$weikongzhi = $weikongzhi + $cha;
						} else {
							$hbarr[$arr[$j]] = $hbarr[$arr[$j]] + 10 - $cha;
							$weikongzhi = $weikongzhi + $cha - 10;
						}
					} else {
						$hbarr[$arr[$j]] = $hbarr[$arr[$j]] + abs($cha);
						$weikongzhi = $weikongzhi - abs($cha);
					}
				}
			}
			if ($hbset['hweishao'] != 10) {
				$geshu = 0;
				$arr = array();
				foreach ($hbarr as $k => $v) {
					$wei = substr($v, -1);
					if ($wei == $hbset['hweishao']) {
						$geshu++;
						$arr[] = $k;
					}
				}
				foreach ($arr as $v) {
					$hbarr[$v] = $hbarr[$v] + 1;
					$weikongzhi = $weikongzhi - 1;
				}
			}
			$hbarr[0] = $hbarr[0] + $weikongzhi;
			foreach ($hbarr as $v) {
				$data = array('faid' => $faid, 'hmoney' => $v, 'hweishu' => substr($v, -1));
				$hblistid = M('saolei_userfalist')->add($data);
				$hbjine = $v;
			}
			M()->execute('update __USER_ZHANGHU__ set uqianchong=uqianchong-' . $hbset['hbzhifu'] . ' where userid=' . $userid);
			M()->execute('update __SAOLEI_USERFALIST__ set hcode=2 where id=' . $hblistid);
			M('saolei_linghb')->add(array('userid' => -1, 'hcode' => 2, 'hblistid' => $hblistid, 'hbe' => $hbjine, 'ttime' => time()));
			$user = M('user_list')->where('id=' . $userid)->find();
			if (0 < $user['utid']) {
				$yongjinma = rand(10000, 99999);
				session('yongjinma', $yongjinma);
				$pay = A('Pay');
				$pay->yongjin($user['utid'], $hbjine, $yongjinma, 1);
			}
		}
		$this->ajaxReturn($json, 'json');
	}
	public function checkzhanghu($jine = 0)
	{
		$userid = session('userid');
		$zhanghu = M('user_zhanghu')->where('userid=' . $userid)->find();
		if ($jine <= $zhanghu['uqianchong']) {
			return 2;
		} else {
			return 1;
		}
	}
	public function ajaxhbcode()
	{
		$hbid = I('hbid', 0, 'intval');
		if ($hbid == 0) {
			$hb = M('hb')->order('hzhifue asc')->find();
			$hbid = intval($hb['id']);
		}
		$json['hbid'] = $hbid;
		$this->ajaxReturn($json, 'json');
	}
	public function ajaxchai()
	{
		$hbid = I('hbid', 0, 'intval');
		$xuanze = I('xuanze', 1, 'intval');
		$userid = session('userid');
		$user = M('user_list')->where('id=' . $userid)->find();
		$userzhanghu = M('user_zhanghu')->where('userid=' . $userid)->find();
		$fahb = M('saolei_userfa')->where('hbid=' . $hbid)->find();
		if (!$fahb) {
			die;
		}
		$hb = M()->table('__SAOLEI_USERFALIST__ a')->join('__SAOLEI_USERFA__ b on a.faid=b.id')->where('b.hbid=' . $hbid . ' and a.hcode=1')->field('a.id,a.hmoney,a.hweishu faweishu,b.hzhifue,b.hweishu,b.userid')->order('a.id asc')->find();
		if (!$hb) {
			$json['code'] = 1;
		} else {
			if ($userzhanghu['uqianchong'] < $hb['hzhifue'] || $userzhanghu['uqianchong'] <= 0) {
				$json['code'] = 2;
			} else {
				if ($xuanze == 1) {
					M()->execute('update __USER_ZHANGHU__ set uqianchong=uqianchong+' . $hb['hmoney'] . ' where userid=' . $userid);
					M()->execute('update __SAOLEI_USERFALIST__ set hcode=2 where id=' . $hb['id']);
					M('saolei_linghb')->add(array('userid' => $userid, 'hblistid' => $hb['id'], 'hbe' => $hb['hmoney'], 'hcode' => 3, 'ttime' => time()));
				} else {
					$lingid = M('saolei_linghb')->add(array('userid' => $userid, 'hblistid' => $hb['id'], 'hbe' => $hb['hmoney'], 'hcode' => 1, 'ttime' => time()));
					$pay = A('Pay');
					$pay->saolei($userid, $lingid);
				}
				if ($hb['faweishu'] == $hb['hweishu']) {
					M()->execute('update __USER_ZHANGHU__ set uqianchong=uqianchong-' . $hb['hzhifue'] . ' where userid=' . $userid);
					M()->execute('update __USER_ZHANGHU__ set uqianchong=uqianchong+' . $hb['hzhifue'] . ' where userid=' . $hb['userid']);
					M('saolei_peifu')->add(array('userid' => $userid, 'fauserid' => $hb['userid'], 'hblistid' => $hb['id'], 'hpeie' => $hb['hzhifue'], 'ttime' => time()));
					$json['weihao'] = $hb['faweishu'];
				}
				$json['code'] = 3;
				$json['hbe'] = number_format($hb['hmoney'] * 0.01, 2);
				$json['txt'] = '恭喜您抽中红包<font color="#FF0000">' . $json['hbe'] . '</font>元';
				if (0 <= $json['weihao'] && $json['weihao'] != '') {
					$json['txt'] .= '，踩中尾号' . $json['weihao'] . '，支付玩家' . $hb['hzhifue'] * 0.01 . '元';
				}
				$json['hbetxt'] = $json['hbe'] . '<br><font style="font-size:12px;">预测：' . $hb['hweishu'] . '</font>';
			}
		}
		$this->ajaxReturn($json, 'json');
	}
	public function fahb()
	{
		$userid = session('userid');
		$fahb = M('saolei_userfa')->where('userid=' . $userid)->select();
		foreach ($fahb as $k => $v) {
			$row = M('saolei_userfalist')->where('faid=' . $v['id'])->find();
			if (!$row) {
				M('saolei_userfa')->where('id=' . $v['id'])->delete();
			}
		}
		$this->display();
	}
	public function ajaxfahblist()
	{
		$page = I('page', 0);
		$pagesize = 4;
		$limit = $page * $pagesize . ',' . $pagesize;
		$userid = session('userid');
		$fahb = M('saolei_userfa')->where('userid = ' . $userid)->limit($limit)->order('id desc')->select();
		foreach ($fahb as $v) {
			$html = '';
			$yichainum = M('saolei_userfalist')->where('faid=' . $v['id'] . ' and hcode=2')->count();
			$weichainum = M('saolei_userfalist')->where('faid=' . $v['id'] . ' and hcode=1')->count();
			$hblist = M('saolei_userfalist')->where('faid=' . $v['id'])->select();
			$html = '<li><p>红包</p><p>状态</p><p>获得</p></li>';
			foreach ($hblist as $val) {
				$txt = '';
				if ($val['hcode'] == 2) {
					$userlinghb = M('saolei_linghb')->where('hblistid=' . $val['id'] . ' and hcode in(2,3)')->find();
					if ($userlinghb) {
						if ($userlinghb['userid'] == -1) {
							$txt = 'Boss';
						} else {
							if ($val['hweishu'] == $v['hweishu']) {
								$txt = $v['hzhifue'] * 0.01;
							}
						}
					}
				}
				$html .= "<li>\r\n                     <p>" . number_format($val['hmoney'] * 0.01, 2) . "</p>\r\n                     <p>" . ($val['hcode'] == 1 ? '<font color=#FF0000>未拆</font>' : '已拆') . "</p>\r\n                     <p>" . $txt . "</p>\r\n                     </li>";
			}
			$json['html'] .= "<div class=\"items\">\r\n                              <p class=\"title\"><strong>红包：</strong><font class=\"qian\">" . $v['hzhifue'] * 0.01 . '元</font>&nbsp;&nbsp;&nbsp;个数：' . $v['hgeshu'] . '&nbsp;&nbsp;&nbsp;尾数：' . $v['hweishu'] . "</p>\r\n                              <p>已拆：" . $yichainum . "个<span class=\"xiang\">详情</span></p>\r\n                              <p>未拆：" . $weichainum . "个</p>\r\n                              <p>时间：" . date('m-d H:s', $v['htime']) . "</p>\r\n                              <ul>" . $html . '</ul></div>';
		}
		$this->ajaxReturn($json, 'json');
	}
}