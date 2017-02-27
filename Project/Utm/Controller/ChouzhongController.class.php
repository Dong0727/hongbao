<?php

 
namespace Utm\Controller;

class ChouzhongController extends CommonController
{
	public function edit()
	{
		$adminid = session('adminid');
		$news = M('sys_config')->where('adminid=' . $adminid)->find();
		$this->news = $news;
		$this->display();
	}
	public function save()
	{
		$adminid = session('adminid');
		$data['cwxchoutxt'] = str_replace('，', ',', I('cwxchoutxt'));
		$sysconfig = M('sys_config')->where('adminid=' . $adminid)->find();
		if (!$sysconfig) {
			$data['adminid'] = $adminid;
			M('sys_config')->add($data);
		} else {
			$data['id'] = $sysconfig['id'];
			M('sys_config')->save($data);
		}
		$this->success('操作成功', U('edit'), 1);
	}
}