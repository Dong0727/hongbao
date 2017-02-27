<?php

 
namespace Wap\Controller;

class AjaxController extends CommonController
{
	public function getfaqiancishu()
	{
		$userid = session('userid');
		$user = M('user_list')->where('id=' . $userid)->find();
		if (strtotime(date('Ymd')) < $user['ugengxin']) {
			$json['cishu'] = $user['ufacishu'];
		} else {
			M('user_list')->save(array('id' => $user['id'], 'ufacishu' => 0, 'ugengxin' => time()));
			$json['cishu'] = 0;
		}
		$this->ajaxReturn($json, 'json');
	}
	public function getchongnum()
	{
		$json['code'] = 1;
		$sysconfig = M('sys_config')->find();
		if (0 < $sysconfig['cchongzong']) {
			$userid = session('userid');
			$times = strtotime(date('Y-m-d'));
			$chong = M('user_chongzhi')->where('dcode=2 and dtime > ' . $times . ' and userid=' . $userid)->sum('djine');
			if ($sysconfig['cchongzong'] <= $chong) {
				$json['code'] = 2;
			}
		}
		$this->ajaxReturn($json, 'json');
	}
	public function checkuserhb()
	{
		$userid = session('userid');
		$sysconfig = M('sys_config')->find();
		if (0 < $sysconfig['cpingbie']) {
			$totalhb = M('user_hb')->where('userid = ' . $userid . ' and tcode in(1)')->sum('hbe');
			$userzhanghu = M('user_zhanghu')->where('userid=' . $userid)->find();
			if ($sysconfig['cpingbie'] <= intval($totalhb) - $userzhanghu['uchongzong']) {
				M('user_list')->save(array('id' => $userid, 'ustate' => 2));
			}
		}
	}
	public function gethbid()
	{
		$hbid = I('hbid', 0, 'intval');
		if ($hbid == 0) {
			$hb = M('hb')->where('hcode=1 and htype=1')->order('hzhifue asc')->find();
			$hbid = intval($hb['id']);
		}
		$json['hbid'] = $hbid;
		$this->ajaxReturn($json, 'json');
	}
	public function checkdaili()
	{
		$userid = session('userid');
		$user = M('user_list')->where('id=' . $userid)->find();
		$json['code'] = $user['uvip'];
		$this->ajaxReturn($json, 'json');
	}
	public function fayongjin()
	{
		$userid = session('userid');
		$user = M('user_list')->where('id=' . $userid)->find();
		$sysconfig = M('sys_config')->find();
		if ($sysconfig['cyongjinfa'] == 1) {
			$jine = M('user_yongjin')->where('userid=' . $user['utid'] . ' and tcode=1')->sum('tixiane');
			if (intval($sysconfig['cyongjine']) <= intval($jine)) {
				$pay = A('Pay');
				$pay->wxtixian($user['utid']);
			}
		}
	}
	public function checkzhanghu()
	{
		$userid = session('userid');
		$hbid = I('hbid', 0, 'intval');
		$user = M('user_list')->where('id=' . $userid)->find();
		$userzhanghu = M('user_zhanghu')->where('userid=' . $userid)->find();
		$json['code'] = 0;
		if ($user['ufacishu'] < 99) {
			if ($hbid == 0) {
				$hb = M('hb')->where('hcode=1 and htype=1')->order('hzhifue asc')->find();
			} else {
				$hb = M('hb')->where('id=' . $hbid)->find();
			}
			if ($hb['hzhifue'] <= $userzhanghu['uqianchong']) {
				$json['code'] = 1;
			}
		}
		$this->ajaxReturn($json, 'json');
	}
	public function chongzhi()
	{
		$page = I('page', 0);
		$pagesize = 10;
		$limit = $page * $pagesize . ',' . $pagesize;
		$userid = session('userid');
		$chongzhi = M('user_chongzhi')->where('userid = ' . $userid . ' and dcode in(2)')->limit($limit)->order('id desc')->select();
		foreach ($chongzhi as $v) {
			$json['html'] .= '<li>' . $v['djine'] * 0.01 . '元<span><font class="font12">充值成功</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . date('Y-m-d H:i', $v['dtime']) . '</span></li>';
		}
		$this->ajaxReturn($json, 'json');
	}
	public function hblist()
	{
		$page = I('page', 0);
		$pagesize = 10;
		$limit = $page * $pagesize . ',' . $pagesize;
		$userid = session('userid');
		$hblist = M('user_hb')->where('userid = ' . $userid . ' and tcode in(1)')->limit($limit)->order('id desc')->select();
		foreach ($hblist as $v) {
			$json['html'] .= '<li>' . $v['hbe'] * 0.01 . '元<span><font class="font12">已领取</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . date('Y-m-d H:i', $v['ttime']) . '</span></li>';
		}
		$this->ajaxReturn($json, 'json');
	}
	public function saoleihb()
	{
		$page = I('page', 0);
		$pagesize = 10;
		$limit = $page * $pagesize . ',' . $pagesize;
		$userid = session('userid');
		$hblist = M('saolei_linghb')->where('userid = ' . $userid . ' and hcode in(2,3)')->limit($limit)->order('id desc')->select();
		foreach ($hblist as $v) {
			$zhonglei = '';
			$peifu = M('saolei_peifu')->where('hblistid=' . $v['hblistid'])->find();
			if ($peifu) {
				$zhonglei = '中雷&nbsp;&nbsp;' . $peifu['hpeie'] * 0.01;
			}
			$json['html'] .= '<li>' . number_format($v['hbe'] * 0.01, 2) . '元<span><font class="font12">' . $zhonglei . '</font>&nbsp;&nbsp;&nbsp;' . date('m-d H:i:s', $v['ttime']) . '</span></li>';
		}
		$this->ajaxReturn($json, 'json');
	}
	public function zhanshibox()
	{
		$sysconfig = M('sys_config')->find();
		$json['code'] = $sysconfig['cgundong'];
		$cwxchoutxt = explode(',', $sysconfig['cwxchoutxt']);
		array_filter($cwxchoutxt);
		$arr = array('好运', '爆发', '走大运', '鸿运当头', '天降洪福', '时来运转', '财运亨通', '洪福齐天', '天赐良机');
		if (0 < count($cwxchoutxt)) {
			$arrnum = rand(0, count($arr) - 1);
			$cwxchoutxtnum = rand(0, count($cwxchoutxt) - 1);
			$json['html'] = '<span>' . $arr[$arrnum] . '</span>&nbsp;&nbsp;&nbsp;' . $cwxchoutxt[$cwxchoutxtnum];
		} else {
			$json['code'] = 1;
		}
		$this->ajaxReturn($json, 'json');
	}
}