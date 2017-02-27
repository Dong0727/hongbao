<?php

 
namespace Utm\Controller;

class SysController extends CommonController
{
	public function logs()
	{
		$logs = M('sys_log')->order('id desc')->limit(20)->select();
		$this->logs = $logs;
		$this->display();
	}
}