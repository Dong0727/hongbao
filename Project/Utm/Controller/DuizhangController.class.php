<?php

 
namespace Utm\Controller;

class DuizhangController extends CommonController
{
	public function index()
	{
		$count = M('user_duizhang')->count();
		$page = my_page($count, 7);
		$limit = $page->firstRow . ',' . $page->listRows;
		$duizhang = M('user_duizhang')->limit($limit)->select();
		$this->page = $page->show();
		$this->duizhang = $duizhang;
		$this->display();
	}
	public function jisuanzhangdan()
	{
		$zhangdan = M('user_wxexcel')->group('uopenid')->field('count(id) wxnum,sum(wxjine) wxnum,uopenid')->select();
		foreach ($zhangdan as $v) {
			$user = M('user_list')->where('uopenid=\'' . $v['uopenid'] . '\'')->find();
			$userid = intval($user['id']);
			$fazong = M('user_hb')->where('tcode=1 and userid=' . $userid)->sum('hbe');
			$dailifa = M('user_yongjin')->where('tcode in(2) and userid=' . $userid)->sum('tixiane');
			$tixian = M('user_tixian')->where('userid=' . $userid)->sum('tixiane');
			$saoleie = M('saolei_linghb')->where('hcode=2 and userid = ' . $userid)->sum('hbe');
			$paynum = $fazong + $dailifa + $tixian + $saoleie;
			$userduizhang = M('user_duizhang')->where('userid=' . $userid)->find();
			if (!$userduizhang) {
				M('user_duizhang')->add(array('userid' => $userid, 'paynum' => $paynum, 'wxnum' => $v['wxnum']));
			} else {
				M('user_duizhang')->save(array('id' => $userduizhang[id], 'paynum' => $paynum, 'wxnum' => $v['wxnum']));
			}
		}
		$this->success('成功', U('index'), 1);
	}
	public function saveexcel()
	{
		if (!empty($_FILES['filecsv']['name'])) {
			$upload = new \Think\Upload();
			$upload->savePath = 'excel/';
			$upload->subName = '';
			$upload->exts = array('xls');
			$upload->saveName = 'duizhang';
			$upload->replace = true;
			if (!$upload->upload()) {
				$this->error($upload->getError());
			} else {
				Vendor('PhpExcel.PHPExcel');
				$file_name = './Uploads/excel/duizhang.xls';
				$objReader = \PHPExcel_IOFactory::createReader('Excel5');
				$objReader->setReadDataOnly(true);
				$objPHPExcel = $objReader->load($file_name, $encode = 'utf-8');
				$sheet = $objPHPExcel->getSheet(0);
				$highestRow = $sheet->getHighestRow();
				for ($i = 2; $i <= $highestRow; $i++) {
					$data['uopenid'] = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getValue();
					$data['wxdanhao'] = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getValue();
					$wxjine = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getValue();
					$wxjine = str_replace('\'', '', $wxjine);
					$data['wxjine'] = $wxjine * 100;
					M('user_wxexcel')->add($data);
					unset($data);
				}
			}
		}
		$this->success('成功', U('index'), 1);
	}
}