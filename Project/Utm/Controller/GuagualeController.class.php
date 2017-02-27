<?php

 
namespace Utm\Controller;

class GuagualeController extends CommonController
{
	public function add()
	{
		$seldata = M('hb')->where('htype=3')->find();
		$id = intval($seldata['id']);
		$hbgailv = M('hb_gailv')->where('hbid=' . $id)->select();
		$this->seldata = $seldata;
		$this->hbgailv = $hbgailv;
		$this->display();
	}
	public function save()
	{
		$data['hzhifue'] = I('hzhifue', 0) * 100;
		$data['htime'] = I('htime', 0, 'intval');
		$id = I('get.id', 0, 'intval');
		if (0 < $id) {
			$data['id'] = $id;
			M('hb')->save($data);
		} else {
			$data['htype'] = 3;
			$id = M('hb')->add($data);
		}
		$hmin = I('hmin');
		$hmax = I('hmax');
		$hgailv = I('hgailv');
		$hcishu = I('hcishu');
		$hbgailv = M('hb_gailv')->where('hbid=' . $id)->select();
		$gailvnum = count($hmin) <= count($hbgailv) ? count($hbgailv) : count($hmin);
		for ($i = 0; $i < $gailvnum; $i++) {
			$data = array('hbid' => $id, 'hmin' => intval($hmin[$i] * 100), 'hmax' => intval($hmax[$i] * 100), 'hgailv' => intval($hgailv[$i]), 'hcishu' => intval($hcishu[$i]));
			if (count($hmin) <= count($hbgailv)) {
				if ($i <= count($hmin) - 1) {
					$data['id'] = $hbgailv[$i]['id'];
					M('hb_gailv')->save($data);
				} else {
					$gailvid = $hbgailv[$i]['id'];
					M('hb_gailv')->where('id=' . $gailvid)->delete();
				}
			} else {
				if ($i <= count($hbgailv) - 1) {
					$data['id'] = $hbgailv[$i]['id'];
					M('hb_gailv')->save($data);
				} else {
					M('hb_gailv')->add($data);
				}
			}
			unset($data);
		}
		$this->success('操作成功', U('add'), 1);
	}
}