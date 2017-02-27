<?php

 
function sp_testwrite($d)
{
	$tfile = '_test.txt';
	$fp = @fopen($d . '/' . $tfile, 'w');
	if (!$fp) {
		return false;
	}
	fclose($fp);
	$rs = @unlink($d . '/' . $tfile);
	if ($rs) {
		return true;
	}
	return false;
}
function sp_execute_sql($db, $tablepre)
{
	$file = get_sql_file('./Data/');
	$sqlcon = file_get_contents('./Data/' . $file);
	$sql = str_replace("\r", "\n", $sqlcon);
	$sql = explode(";\n", $sql);
	preg_match('/CREATE TABLE IF NOT EXISTS `([^ ]*)`/', $sqlcon, $matches);
	if (empty($matches)) {
		preg_match('/CREATE TABLE `([^ ]*)`/', $sqlcon, $matches);
	}
	$pre_arr = explode('_', $matches[1]);
	$default_tablepre = $pre_arr[0] . '_';
	$sql = str_replace(' `' . $default_tablepre, ' `' . $tablepre, $sql);
	sp_show_msg('开始安装数据库...');
	foreach ($sql as $item) {
		$item = trim($item);
		if (empty($item)) {
			continue;
		}
		preg_match('/CREATE TABLE IF NOT EXISTS `([^ ]*)`/', $item, $matches);
		if (empty($matches)) {
			preg_match('/CREATE TABLE `([^ ]*)`/', $item, $matches);
		}
		if ($matches) {
			$table_name = $matches[1];
			$msg = '创建数据表' . $table_name;
			if (false !== $db->execute($item)) {
				sp_show_msg($msg . ' 完成');
			} else {
				sp_show_msg($msg . ' 失败！', 'error');
			}
		} else {
			$db->execute($item);
		}
	}
}
function sp_create_admin_account($db, $table_prefix)
{
	$uweb = $_SERVER['HTTP_HOST'];
	$uname = I('post.manager');
	$upass = I('post.manager_pwd') . $uweb;
	$upass = md5(md5($upass));
	$utime = time();
	$sql = 'INSERT INTO `' . $table_prefix . 'sys_user`(uname,upass,utime) VALUES (\'' . $uname . '\', \'' . $upass . '\',\'' . $utime . '\');';
	$db->execute($sql);
	sp_show_msg('管理员账号创建成功!');
}
function sp_create_config($config)
{
	if (is_array($config)) {
		$conf = (include APP_PATH . 'Common/Conf/config.php');
		foreach ($config as $key => $value) {
			$conf[$key] = $value;
		}
		$conf = '<?php return ' . var_export($conf, true) . ';';
		if (file_put_contents(APP_PATH . 'Common/Conf/config.php', $conf)) {
			sp_show_msg('配置文件写入成功');
		} else {
			sp_show_msg('配置文件写入失败！', 'error');
		}
		return '';
	}
}
function get_sql_file($path)
{
	if (!is_dir($path)) {
		return NULL;
	}
	$handle = opendir($path);
	$files = '';
	while (false !== ($file = readdir($handle))) {
		if ($file != '.' && $file != '..') {
			$path2 = $path . '/' . $file;
			if (!is_dir($path2)) {
				if (preg_match('/\\.(sql)$/i', $file)) {
					$files = $file;
					break;
				}
			}
		}
	}
	closedir($handle);
	return $files;
}