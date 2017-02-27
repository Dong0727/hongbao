<?php

 
namespace Utm\Controller;

class WxhuifuController extends CommonController
{
	public function keylist()
	{
		$whe['stype'] = I('get.stype', 0, 'intval');
		$whe['adminid'] = session('adminid');
		if (I('sname') != '') {
			$whe['sname'] = array('like', '%' . I('sname') . '%');
		}
		$count = M('weixin_sendkey')->where($whe)->count();
		$page = new \Think\Page($count, 12);
		$limit = $page->firstRow . ',' . $page->listRows;
		$seldata = M('weixin_sendkey')->where($whe)->limit($limit)->select();
		$this->page = $page->show();
		$this->nowpage = I('p');
		$this->stype = $whe['stype'];
		$this->seldata = $seldata;
		$this->display();
	}
	public function keydel()
	{
		$nowpage = I('nowpage', 1, 'intval');
		$stype = I('stype', 0, 'intval');
		$nid = I('nid', 0, 'intval');
		$adminid = $_SESSION['adminid'];
		M('weixin_sendkey')->where(' id = ' . $nid . ' and adminid=' . $adminid . ' ')->delete();
		$this->success('操作成功', U('Wxhuifu/keylist?stype=' . $stype . '&p=' . $nowpage), 1);
	}
	public function keyadd()
	{
		$nid = I('nid', 0, 'intval');
		$stype = I('stype', 0, 'intval');
		$seldata = M('weixin_sendkey')->where('id=' . $nid)->find();
		$this->seldata = $seldata;
		$this->stype = $stype;
		$this->display();
	}
	public function keysave()
	{
		$data['sname'] = I('sname');
		$data['kcode'] = I('kcode');
		$data['stype'] = I('get.stype', 0, 'intval');
		$data['id'] = I('get.nid', 0, 'intval');
		if (0 < $data['id']) {
			M('weixin_sendkey')->save($data);
		} else {
			$data['adminid'] = $_SESSION['adminid'];
			$data['stime'] = time();
			M('weixin_sendkey')->data($data)->add();
		}
		$this->success('操作成功', U('Wxhuifu/keylist?stype=' . $data['stype']), 1);
	}
	public function keyconlist()
	{
		$kid = I('kid', 0, 'intval');
		$stype = I('stype', 0, 'intval');
		$itemdata = M('weixin_sendkey')->where(' id = ' . $kid . ' ')->find();
		$seldata = M('weixin_sendcon')->where(' kid = ' . $kid . ' ')->select();
		$this->seldata = $seldata;
		$this->itemdata = $itemdata;
		$this->stype = $stype;
		$this->display();
	}
	public function keyconadd()
	{
		$kid = I('kid', 0, 'intval');
		$stype = I('stype', 0, 'intval');
		$id = I('id', 0, 'intval');
		$itemdata = M('weixin_sendkey')->where(' id = ' . $kid . ' ')->find();
		$seldata = M('weixin_sendcon')->where('id=' . $id)->find();
		$this->stype = $stype;
		$this->seldata = $seldata;
		$this->itemdata = $itemdata;
		$this->display();
	}
	public function keyconsave()
	{
		$stype = I('get.stype', 0, 'intval');
		$data['kid'] = I('get.kid', 0, 'intval');
		$data['snum'] = I('snum', 1, 'intval');
		$data['stime'] = I('stime', 0, 'intval');
		$data['spic'] = I('spic');
		$data['sname'] = I('sname');
		$data['surl'] = I('surl');
		$data['sdec'] = I('sdec');
		$data['id'] = I('get.id', 0, 'intval');
		$filepath = substr(date('Ymd', $data['stime']), 0, 4) . '/' . substr(date('Ymd', $data['stime']), 4, 2) . '/' . substr(date('Ymd', $data['stime']), 6, 2) . '/';
		if (!empty($_FILES['file']['name'])) {
			$upload = new \Think\Upload();
			$upload->savePath = $filepath;
			$upload->subName = '';
			$upload->exts = array('jpg', 'gif', 'png', 'jpeg');
			$info = $upload->upload();
			if (!$info) {
				$this->error($upload->getError());
			} else {
				@unlink('./Uploads/' . $filepath . $data['spic']);
				$data['spic'] = $info['file']['savename'];
			}
		}
		if (0 < $data['id']) {
			M('weixin_sendcon')->save($data);
		} else {
			M('weixin_sendcon')->data($data)->add();
		}
		$this->success('操作成功', U('Wxhuifu/keyconlist?stype=' . $data['stype'] . '&kid=' . $data['kid']), 1);
	}
	public function keycondel()
	{
		$stype = I('stype', 0, 'intval');
		$id = I('id', 0, 'intval');
		$data = M('weixin_sendcon')->where(' id=' . $id . ' ')->find();
		$filepath = './Uploads/' . substr(date('Ymd', $data['stime']), 0, 4) . '/' . substr(date('Ymd', $data['stime']), 4, 2) . '/' . substr(date('Ymd', $data['stime']), 6, 2) . '/';
		@unlink($filepath . $data['spic']);
		M('weixin_sendcon')->where('id=' . $id)->delete();
		$this->success('操作成功', U('Wxhuifu/keyconlist?stype=' . $stype . '&kid=' . $data['kid']), 1);
	}
}