<?php

 
namespace Wap\Controller;

class CommonController extends \Think\Controller
{
	public function _initialize()
	{
		parse_str($_SERVER['QUERY_STRING'], $canshu);
		$urlstr[] = 'm-' . $canshu['m'];
		$urlstr[] = 'c-' . $canshu['c'];
		$urlstr[] = 'a-' . $canshu['a'];
		$urlstr[] = 'utid-' . $canshu['utid'];
		$urlstr[] = 'ubeiopenid-' . $canshu['ubeiopenid'];
		$urls = implode('|', $urlstr);
		$utid = $canshu['utid'];
		if (0 < $utid) {
			session('utid', intval($utid));
		}
		if (session('userid') == '') {
			if ($canshu['c'] == '' || $canshu['c'] == 'Index') {
				$this->redirect('Wxlogin/index?urls=' . $urls);
			} else {
				header('Location:http://www.qq.com');
				die;
			}
		} else {
			$userid = session('userid');
			$user = M('user_list')->where('id=' . $userid)->find();
			if (!$user) {
				$this->redirect('Wxlogin/index?urls=' . $urls);
			} else {
				if ($user['ustate'] == 2) {
					header('Location:http://www.qq.com');
					die;
				}
			}
		}
	}
}