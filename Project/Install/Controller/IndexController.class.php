<?php

 
namespace Install\Controller;

class IndexController extends \Think\Controller
{
	public function _initialize()
	{
		if (file_exists('./Data/install.lock')) {
			redirect(__ROOT__ . '/');
		}
	}
	public function step1()
	{
		$data = array();
		$data['phpversion'] = @phpversion();
		$data['os'] = PHP_OS;
		if (class_exists('pdo')) {
			$data['pdo'] = '<i class="fa-rig">已开启</i>';
		} else {
			$data['pdo'] = '<i class="fa-err">未开启</i>';
		}
		if (extension_loaded('pdo_mysql')) {
			$data['pdo_mysql'] = '<i class="fa-rig">已开启</i>';
		} else {
			$data['pdo_mysql'] = '<i class="fa-err">未开启</i>';
		}
		if (ini_get('file_uploads')) {
			$data['upload_size'] = '<i class="fa-rig">' . ini_get('upload_max_filesize') . '</i>';
		} else {
			$data['upload_size'] = '<i class="fa-err">禁止上传</i> ';
		}
		if (function_exists('session_start')) {
			$data['session'] = '<i class="fa-rig">支持</i> ';
		} else {
			$data['session'] = '<i class="fa-err">不支持</i> ';
		}
		$folders = array('', 'Data', 'Uploads', APP_PATH . 'Common/Conf', APP_PATH . 'Runtime');
		$new_folders = array();
		foreach ($folders as $dir) {
			$Testdir = './' . $dir;
			sp_dir_create($Testdir);
			if (sp_testwrite($Testdir)) {
				$new_folders[$dir]['w'] = true;
			} else {
				$new_folders[$dir]['w'] = false;
			}
			if (is_readable($Testdir)) {
				$new_folders[$dir]['r'] = true;
			} else {
				$new_folders[$dir]['r'] = false;
			}
		}
		$data['folders'] = $new_folders;
		$this->assign($data);
		$this->display();
	}
	public function step3()
	{
		if (IS_POST) {
			$dbconfig['DB_TYPE'] = 'mysql';
			$dbconfig['DB_HOST'] = I('post.dbhost');
			$dbconfig['DB_USER'] = I('post.dbuser');
			$dbconfig['DB_PWD'] = I('post.dbpw');
			$dbconfig['DB_PORT'] = I('post.dbport');
			$db = \Think\Db::getInstance($dbconfig);
			$dbname = strtolower(I('post.dbname'));
			$sql = 'CREATE DATABASE IF NOT EXISTS `' . $dbname . '` DEFAULT CHARACTER SET utf8';
			$db->execute($sql) || $this->error($db->getError());
			$this->display();
			$dbconfig['DB_NAME'] = $dbname;
			$dbconfig['DB_PREFIX'] = trim(I('post.dbprefix'));
			$db = \Think\Db::getInstance($dbconfig);
			$table_prefix = trim(I('post.dbprefix'));
			sp_execute_sql($db, $table_prefix);
			sp_create_admin_account($db, $table_prefix);
			sp_create_config($dbconfig);
			session('_install_step', 3);
			sleep(1);
			$this->redirect('step4');
		} else {
			die;
		}
	}
	public function step4()
	{
		if (session('_install_step') == 3) {
			@touch('./Data/install.lock');
			$this->display();
		} else {
			$this->error('非法安装！');
		}
	}
}