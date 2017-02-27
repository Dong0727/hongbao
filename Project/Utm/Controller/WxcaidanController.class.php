<?php

 
namespace Utm\Controller;

class WxcaidanController extends CommonController
{
	public function edit()
	{
		$cid = I('cid', 0, 'intval');
		$nid = I('nid', 0, 'intval');
		$seldata = M('weixin_caidan')->where(' id = ' . $nid . ' ')->find();
		$optiondata = M('weixin_sendkey')->select();
		$this->assign('seldata', $seldata);
		$this->assign('optiondata', $optiondata);
		$this->assign('cid', $cid);
		$this->display();
	}
	public function show()
	{
		$whe['cid'] = I('cid', 0, 'intval');
		$whe['adminid'] = $_SESSION['adminid'];
		$seldata = M('weixin_caidan')->where($whe)->order('cnum desc,id desc')->select();
		$this->assign('seldata', $seldata);
		$this->assign('cid', $whe['cid']);
		$this->display();
	}
	public function save()
	{
		$data['cid'] = I('get.cid', 0, 'intval');
		$data['id'] = I('get.nid', 0, 'intval');
		$data['cname'] = I('cname');
		$data['cnum'] = I('cnum', 0, 'intval');
		$data['ctype'] = I('ctype', 0, 'intval');
		$data['curl'] = I('curl');
		$data['ckey'] = I('ckey');
		$data['adminid'] = $_SESSION['adminid'];
		if (0 < $data['id']) {
			M('weixin_caidan')->save($data);
		} else {
			M('weixin_caidan')->data($data)->add();
		}
		$this->success('添加成功', U('show?cid=' . $data['cid']), 1);
	}
	public function del()
	{
		$cid = I('cid', 0, 'intval');
		$nid = I('nid', 0, 'intval');
		$adminid = $_SESSION['adminid'];
		M('weixin_caidan')->where(' id = ' . $nid . ' and adminid=' . $adminid . ' ')->delete();
		if ($cid == 0) {
			M('weixin_caidan')->where(' cid = ' . $nid . ' and adminid=' . $adminid . ' ')->delete();
		}
		$this->success('删除成功', U('show?cid=' . $cid), 1);
	}
	public function create()
	{
		$adminid = $_SESSION['adminid'];
		$caidannum = M('weixin_caidan')->where('adminid=' . $adminid . ' and cid=0')->count();
		$caidanone = M('weixin_caidan')->where('adminid=' . $adminid . ' and cid=0')->order('cnum desc,id desc')->select();
		$caidanarr = array('button' => array());
		foreach ($caidanone as $v) {
			$caidantwo = M('weixin_caidan')->where('cid=' . $v['id'])->select();
			if (!$caidantwo) {
				if ($v['ctype'] == 0) {
					$arr = array('type' => 'view', 'name' => urlencode($v['cname']), 'url' => $v['curl']);
				}
				if ($v['ctype'] == 1) {
					$arr = array('type' => 'click', 'name' => urlencode($v['cname']), 'key' => urlencode($v['ckey']));
				}
				if ($v['ctype'] == 2) {
					$arr = array('type' => 'scancode_waitmsg', 'name' => urlencode($v['cname']), 'key' => 'rselfmenu_0_0', 'sub_button' => array());
				}
				if ($v['ctype'] == 3) {
					$arr = array('type' => 'pic_sysphoto', 'name' => urlencode($v['cname']), 'key' => 'rselfmenu_1_0', 'sub_button' => array());
				}
				array_push($caidanarr['button'], $arr);
			} else {
				$twoarr = array('name' => urlencode($v['cname']), 'sub_button' => array());
				foreach ($caidantwo as $vt) {
					if ($vt['ctype'] == 0) {
						$arr = array('type' => 'view', 'name' => urlencode($vt['cname']), 'url' => $vt['curl']);
					}
					if ($vt['ctype'] == 1) {
						$arr = array('type' => 'click', 'name' => urlencode($vt['cname']), 'key' => urlencode($vt['ckey']));
					}
					if ($vt['ctype'] == 2) {
						$arr = array('type' => 'scancode_waitmsg', 'name' => urlencode($vt['cname']), 'key' => 'rselfmenu_0_0', 'sub_button' => array());
					}
					if ($vt['ctype'] == 3) {
						$arr = array('type' => 'pic_sysphoto', 'name' => urlencode($vt['cname']), 'key' => 'rselfmenu_1_0', 'sub_button' => array());
					}
					array_push($twoarr['sub_button'], $arr);
				}
				array_push($caidanarr['button'], $twoarr);
			}
		}
		$data = urldecode(json_encode($caidanarr));
		$config = M('sys_config')->where('adminid=' . $adminid)->find();
		$wxtoken = new \Common\Tool\Wxtoken($config['cwxappid'], $config['cwxappsecret']);
		$access_token = $wxtoken->getAccessToken();
		$menuurl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token;
		$reString = http_curl_post($menuurl, $data);
		$str = json_decode($reString);
		echo $str->errmsg;
	}
}