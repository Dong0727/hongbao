<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎登录后台管理系统</title>
<link href="/Public/Admin/css/base.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/css/index.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/css/right.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="#">欢迎页</a></li>
  </ul>
</div>

<div class="mainindex">
  <div class="welinfo"><img src="/Public/Admin/image/sun.png" alt="天气" /><b><?php echo ($_SESSION['uname']); ?>，欢迎使用信息管理系统。</b>  </div>  
  <div class="welinfo">
    <span><img src="/Public/Admin/image/time.png" alt="时间" /></span>
    <i>您上次登录时间：<?php echo (date('Y-m-d H:i:s',$_SESSION['utime'])); ?></i>
    </div>
  <div class="xline"></div>
</div>
</body>
</html>