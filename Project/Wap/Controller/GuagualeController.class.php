<?php

 
namespace Wap\Controller;

class GuagualeController extends CommonController
{
	public function index()
	{
		$hb = M('hb')->where('htype=3')->find();
		$this->hbid = $hb['id'];
		$this->hb = $hb;
		$this->display();
	}
	public function ajaxgua()
	{
		if (IS_AJAX) {
			$userid = session('userid');
			$user = M('user_list')->where('id=' . $userid)->find();
			$userzhanghu = M('user_zhanghu')->where('userid=' . $userid)->find();
			$hb = M('hb')->where('htype=3')->find();
			if (!$hb) {
				die;
			}
			$hbid = intval($hb['id']);
			if (intval($userzhanghu['uqianchong']) < intval($hb['hzhifue']) || intval($userzhanghu['uqianchong']) <= 0) {
				$json['code'] = 1;
			} else {
				$userhb = M('user_hb')->where('userid=' . $userid)->order('id desc')->find();
				if (15 < time() - intval($userhb['ttime'])) {
					$daichaihb = M('user_hb')->where('hbid=' . $hbid . ' and userid=' . $userid . ' and tcode=2')->find();
					if (!$daichaihb) {
						$chounum = M('user_hb')->where('hbid=' . $hbid . ' and userid=' . $userid . ' and tcode=1')->count();
						$chounum = $chounum + 1;
						$hbgailv = M('hb_gailv')->where('hbid=' . $hbid . ' and hcishu=' . $chounum . ' and hgailv>0')->select();
						if (!$hbgailv) {
							$hbgailv = M('hb_gailv')->where('hbid=' . $hbid . ' and hgailv>0')->select();
						}
						foreach ($hbgailv as $k => $v) {
							$gailvarr[$k] = $v['hgailv'];
						}
						$gailvk = get_arr_rand($gailvarr);
						$hbe = rand($hbgailv[$gailvk]['hmin'], $hbgailv[$gailvk]['hmax']);
						$data = array('userid' => $userid, 'hbid' => $hbid, 'hbe' => $hbe, 'ttime' => time(), 'tcode' => 2);
						$userhbid = M('user_hb')->add($data);
						session('zhuanhbid', $userhbid);
						$json['hbe'] = $hbe * 0.01;
					} else {
						session('zhuanhbid', $daichaihb['id']);
						$json['hbe'] = $daichaihb['hbe'] * 0.01;
					}
					$json['code'] = 2;
				} else {
					$json['code'] = 5;
				}
			}
			$this->ajaxReturn($json, 'json');
		}
	}
	public function ajaxchai()
	{
		$userid = session('userid');
		$userhbid = intval(session('zhuanhbid'));
		session('zhuanhbid', null);
		$userhb = M('user_hb')->where('userid=' . $userid . ' and tcode=2 and id=' . $userhbid)->find();
		if ($userhb) {
			$pay = A('Pay');
			$json['code'] = $pay->wxtixian($userid, 1, $userhbid);
			$json['hbe'] = $userhb['hbe'] * 0.01;
		}
		$this->ajaxReturn($json, 'json');
	}
}