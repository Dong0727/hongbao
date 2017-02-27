<?php

 
namespace Wap\Controller;

class UcenterController extends CommonController
{
	public function index()
	{
		$userid = session('userid');
		$userzhanghu = M('user_zhanghu')->where('userid=' . $userid)->find();
		$jiazunum = M('user_list')->where('utid=' . $userid)->count();
		$this->jiazunum = $jiazunum;
		$this->userzhanghu = $userzhanghu;
		$this->display();
	}
}