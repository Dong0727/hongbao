<?php

 
namespace Utm\Controller;

class SaoleiController extends CommonController
{
	public function show()
	{
		$count = M('saolei_hbset')->count();
		$page = my_page($count, 10);
		$limit = $page->firstRow . ',' . $page->listRows;
		$seldata = M('saolei_hbset')->order('hpaixu desc')->limit($limit)->select();
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
		$seldata = M('saolei_hbset')->where('id=' . $id)->find();
		$this->seldata = $seldata;
		$this->display();
	}
	public function del()
	{
		$id = I('id', 0, 'intval');
		M('saolei_hbset')->where('id=' . $id)->delete();
		$this->success('操作成功', U('show'), 1);
	}
	public function save()
	{
		$data['hbzhifu'] = I('hbzhifu', 0) * 100;
		$data['hgeshu'] = I('hgeshu', 0);
		$data['hpaixu'] = I('hpaixu', 0);
		$data['hweiduo'] = I('hweiduo') == '' ? 10 : I('hweiduo', 0, 'intval');
		$data['hweishao'] = I('hweishao') == '' ? 10 : I('hweishao', 0, 'intval');
		$id = I('get.id', 0, 'intval');
		if (0 < $id) {
			$data['id'] = $id;
			M('saolei_hbset')->save($data);
		} else {
			$id = M('saolei_hbset')->add($data);
		}
		$this->success('操作成功', U('show'), 1);
	}
}