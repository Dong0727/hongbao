<?php

 
namespace Utm\Controller;

class SysmaController extends CommonController
{
	public function index()
	{
		$mset = M('sys_maset')->find();
		$this->mset = $mset;
		$this->display();
	}
	public function masave()
	{
		$data['mleft'] = I('mleft', 159, 'intval');
		$data['mtop'] = I('mtop', 232, 'intval');
		$data['msize'] = I('msize', 5, 'intval');
		$data['midcolor'] = I('midcolor', '#FFFFFF', 'trim');
		$id = I('get.id', 0, 'intval');
		if (0 < $id) {
			$data['id'] = $id;
			M('sys_maset')->save($data);
		} else {
			$id = M('sys_maset')->add($data);
		}
		$this->success('操作成功', U('index'), 1);
	}
}