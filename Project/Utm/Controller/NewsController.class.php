<?php

 
namespace Utm\Controller;

class NewsController extends CommonController
{
	public function newsedit()
	{
		$adminid = session('adminid');
		$ntype = I('ntype', 0, 'intval');
		$news = M('news')->where('ntype=' . $ntype)->find();
		$this->news = $news;
		$this->ntype = $ntype;
		$this->display();
	}
	public function newssave()
	{
		$adminid = session('adminid');
		$ntype = I('get.ntype', 0, 'intval');
		$data['ncontent'] = I('ncontent', '');
		$news = M('news')->where('ntype=' . $ntype)->find();
		if (!$news) {
			$data['ntype'] = $ntype;
			M('news')->add($data);
		} else {
			$data['id'] = $news['id'];
			M('news')->save($data);
		}
		$this->success('操作成功', U('newsedit?ntype=' . $ntype), 1);
	}
}