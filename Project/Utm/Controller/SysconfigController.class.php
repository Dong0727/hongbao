<?php

 
namespace Utm\Controller;

class SysconfigController extends CommonController
{
	public function index()
	{
		$adminid = session('adminid');
		$sysconfig = M('sys_config')->where('adminid=' . $adminid)->find();
		$this->sysconfig = $sysconfig;
		$this->display();
		R('Index/getbanquan');
	}
	public function save()
	{
		$adminid = session('adminid');
		$data['cduanlianjie'] = I('cduanlianjie', 2);
		$data['ctongzhi'] = I('ctongzhi');
		$data['ckouliang'] = I('ckouliang', 0);
		$data['cwxappid'] = I('cwxappid', '', 'trim');
		$data['cwxappsecret'] = I('cwxappsecret', '', 'trim');
		$data['cwxmchid'] = I('cwxmchid', '', 'trim');
		$data['cwxappkey'] = I('cwxappkey', '', 'trim');
		$data['cdenglucode'] = I('cdenglucode', 1);
		$data['cyongjinfa'] = I('cyongjinfa', 2);
		$data['cdailicode'] = I('cdailicode', 1);
		$data['cgundong'] = I('cgundong', 1);
		$data['cmaurl'] = I('cmaurl', '', 'trim');
		$data['cfaurl'] = I('cfaurl', '', 'trim');
		$data['cyongjine'] = I('cyongjine', 0) * 100;
		$data['cchongzong'] = I('cchongzong') * 100;
		$data['cpingbie'] = I('cpingbie', 0) * 100;
		$data['cbeicode'] = I('cbeicode', 2, 'intval');
		$data['cbeiurl'] = I('cbeiurl', '', 'trim');
		$data['cbeiappid'] = I('cbeiappid', '', 'trim');
		$data['cbeiappsecret'] = I('cbeiappsecret', '', 'trim');
		$data['cbeipay'] = I('cbeipay', 1, 'intval');
		$data['cbeimchid'] = I('cbeimchid', '', 'trim');
		$data['cbeiappkey'] = I('cbeiappkey', '', 'trim');
		$data['adminid'] = $adminid;
		$sysconfig = M('sys_config')->where('adminid=' . $adminid)->find();
		if (!$sysconfig) {
			M('sys_config')->data($data)->add();
		} else {
			$data['id'] = $sysconfig['id'];
			M('sys_config')->save($data);
		}
		if (!empty($_FILES['file']['name'][0])) {
			$upload = new \Think\Upload();
			$upload->savePath = 'wxcacert/';
			$upload->subName = '';
			$upload->exts = array('pem');
			$upload->saveName = '';
			$upload->replace = true;
			if (!$upload->upload()) {
				$this->error($upload->getError());
			}
		}
		$this->success('成功', U('index'), 1);
	}
}