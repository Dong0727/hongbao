<?php

 
function get_rand_txt($length)
{
	$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$result = '';
	$l = strlen($str);
	for ($i = 0; $i < $length; $i++) {
		$num = rand(0, $l - 1);
		$result .= $str[$num];
	}
	return $result;
}
function filter_Emoji($str)
{
	$str = preg_replace_callback('/./u', function (array $match) {
		return 4 <= strlen($match[0]) ? '' : $match[0];
	}, $str);
	return $str;
}
function get_arr_rand($proArr)
{
	$result = '';
	$proSum = array_sum($proArr);
	foreach ($proArr as $key => $proCur) {
		$randNum = mt_rand(1, $proSum);
		if ($randNum <= $proCur) {
			$result = $key;
			break;
		} else {
			$proSum -= $proCur;
		}
	}
	unset($proArr);
	return $result;
}
function http_curl_get($url, $type = 1)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_TIMEOUT, 5000);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($curl, CURLOPT_URL, $url);
	if ($type == 1) {
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	}
	$res = curl_exec($curl);
	if ($res) {
		curl_close($curl);
		return $res;
	} else {
		$error = curl_errno($curl);
		curl_close($curl);
		return $error;
	}
}
function http_curl_post($url, $data = NULL)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	if (!empty($data)) {
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}
function create_log($content)
{
	$toppath = 'log.txt';
	$Ts = fopen($toppath, 'a+');
	fputs($Ts, $content . "\r\n");
	fclose($Ts);
}
function create_zip($path, $zipname)
{
	$zip = new ZipArchive();
	if ($zip->open($path . $zipname . '.zip', ZipArchive::OVERWRITE) === true) {
		if (!is_dir($path)) {
			return NULL;
		}
		$handle = opendir($path);
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				$path2 = $path . '/' . $file;
				if (is_dir($path2)) {
					create_zip($path2, $zip);
				} else {
					$zip->addFile($path2);
				}
			}
		}
		@closedir($path);
	}
	$zip->close();
}
function create_file($filename, $filecontent)
{
	$local_file = fopen($filename, 'w');
	if (false !== $local_file) {
		if (false !== fwrite($local_file, $filecontent)) {
			fclose($local_file);
			return true;
		}
	}
	return false;
}
function sp_show_msg($msg, $class = '')
{
	echo '<script type="text/javascript">showmsg("' . $msg . '", "' . $class . '")</script>';
	flush();
	ob_flush();
}
function sp_dir_create($path, $mode = 511)
{
	if (is_dir($path)) {
		return true;
	}
	$ftp_enable = 0;
	$path = sp_dir_path($path);
	$temp = explode('/', $path);
	$cur_dir = '';
	$max = count($temp) - 1;
	for ($i = 0; $i < $max; $i++) {
		$cur_dir .= $temp[$i] . '/';
		if (@is_dir($cur_dir)) {
			continue;
		}
		@mkdir($cur_dir, 511, true);
		@chmod($cur_dir, 511);
	}
	return is_dir($path);
}
function dir_del($dir)
{
	if (!is_dir($dir)) {
		return NULL;
	}
	$handle = opendir($dir);
	while (($file = readdir($handle)) !== false) {
		if ($file != '.' && $file != '..') {
			$fullpath = $dir . '/' . $file;
			if (!is_dir($fullpath)) {
				@unlink($fullpath);
			} else {
				dir_del($fullpath);
			}
		}
	}
	closedir($handle);
	if (rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}
function sp_dir_path($path)
{
	$path = str_replace('\\', '/', $path);
	if (substr($path, -1) != '/') {
		$path = $path . '/';
	}
	return $path;
}
function check_is_weixin()
{
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		return true;
	}
	return false;
}
function my_page($count, $pagesize = 10)
{
	$p = new \Think\Page($count, $pagesize);
	$p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录</li>');
	$p->setConfig('prev', '上一页');
	$p->setConfig('next', '下一页');
	$p->setConfig('last', '末页');
	$p->setConfig('first', '首页');
	$p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
	$p->lastSuffix = false;
	return $p;
}
function soft_chks($txt)
{

}
function soft_str_create($i = 0)
{

}