<?php

 
namespace Utm\Controller;

class YongjinController extends CommonController
{
	public function index()
	{
		$count = M('yongjin_set')->count();
		$page = my_page($count, 10);
		$limit = $page->firstRow . ',' . $page->listRows;
		$seldata = M('yongjin_set')->limit($limit)->select();
		foreach ($seldata as $key => $val) {
		}
		$this->page = $page->show();
		$this->nowpage = I('p');
		$this->seldata = $seldata;
		$this->display();
	}
	public function add()
	{
		$id = I('id', 0, 'intval');
		$seldata = M('yongjin_set')->where('id=' . $id)->find();
		$this->seldata = $seldata;
		$this->display();
	}
	public function del()
	{
		$id = I('id', 0, 'intval');
		M('yongjin_set')->where('id=' . $id)->delete();
		$this->success('操作成功', U('index'), 1);
	}
	public function save()
	{
		$data['ydengji'] = I('ydengji', 0);
		$data['ybaifenbi'] = I('ybaifenbi', 0, 'intval');
		$data['yjine'] = I('yjine', 0) * 100;
		$id = I('get.id', 0, 'intval');
		if (0 < $id) {
			$data['id'] = $id;
			M('yongjin_set')->save($data);
		} else {
			$id = M('yongjin_set')->add($data);
		}
		$this->success('操作成功', U('index'), 1);
	}
	public function fenxiao()
	{
		$seldata = M('fenxiao')->find();
		$this->seldata = $seldata;
		$this->display();
	}
	public function fenxiaosave()
	{
		$data['fjine1'] = I('fjine1', 0, 'intval');
		$data['fjine2'] = I('fjine2', 0, 'intval');
		$data['fjine3'] = I('fjine3', 0, 'intval');
		$id = I('get.id', 0, 'intval');
		if (0 < $id) {
			$data['id'] = $id;
			M('fenxiao')->save($data);
		} else {
			$id = M('fenxiao')->add($data);
		}
		$this->success('操作成功', U('fenxiao'), 1);
	}
}