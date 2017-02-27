<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset='utf-8'>
<title>欢迎登录后台管理系统</title>
<link href="/Public/Admin/css/base.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/css/top.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="/Public/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	//顶部导航切换
	$(".nav li a").click(function(){		
      $(".nav li a.selected").removeClass("selected")
		  $(this).addClass("selected");
	});
});
</script>
</head>
<body style="background:url(/Public/Admin/image/topbg.gif) repeat-x;">
<div class="topleft"><img src="/Public/Admin/image/logo.png" title="系统首页" /></div>
<ul class="nav">

  <?php if(($_SESSION['utype']) == "1"): ?><li><a href="<?php echo U('Index/center');?>" target="rightFrame" class="selected"><img src="/Public/Admin/image/icon08.png" />
    <h2>系统首页</h2>
    </a></li>
    <li><a href="<?php echo U('Hb/show');?>"  target="rightFrame"><img src="/Public/Admin/image/icon13.png" />
    <h2>红包设置</h2>
    </a></li>
    <li><a href="<?php echo U('Zhuanpan/add');?>"  target="rightFrame"><img src="/Public/Admin/image/icon12.png" />
    <h2>转盘设置</h2>
    </a></li>
    <li><a href="<?php echo U('Yongjin/index');?>"  target="rightFrame"><img src="/Public/Admin/image/icon07.png" />
    <h2>代理等级</h2>
    </a></li>
    <li><a href="<?php echo U('Sysconfig/index');?>"  target="rightFrame"><img src="/Public/Admin/image/icon06.png" />
    <h2>系统设置</h2>
    </a></li>
    <li><a href="<?php echo U('Sysuser/passedit');?>"  target="rightFrame"><img src="/Public/Admin/image/icon11.png"/>
    <h2>账户设置</h2>
    </a></li>
  <li><a href="<?php echo U('Sys/logs');?>" target="rightFrame"><img src="/Public/Admin/image/icon01.png" />
    <h2>系统日志</h2>
    </a></li>
  <li><a href="<?php echo U('Index/cleardata');?>"  target="rightFrame"><img src="/Public/Admin/image/icon03.png" title="模块设计" />
    <h2>清空缓存</h2>
    </a></li><?php endif; ?>
    
</ul>
<div class="topright">
  <ul>
    <li><a href="<?php echo U('Login/logout');?>" target="_parent">退出</a></li>
  </ul>
  <div class="user"> 
    <span><?php echo (session('uname')); ?>&nbsp;</span>    
</div>
</div>
</body>
</html>