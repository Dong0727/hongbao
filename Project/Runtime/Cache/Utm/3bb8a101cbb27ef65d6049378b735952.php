<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎登录后台管理系统</title>
<link href="/Public/Admin/css/base.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/css/left.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="/Public/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript">
$(function(){	
	//导航切换
	$(".menuson li").click(function(){
		$(".menuson li.active").removeClass("active")
		$(this).addClass("active");
	});	
	$('.title').click(function(){
		var $ul = $(this).next('ul');
		$('dd').find('ul').slideUp();
		if($ul.is(':visible')){
			$(this).next('ul').slideUp();
		} else {
			$(this).next('ul').slideDown();
		}
	});
})
</script>
</head>
<body style="background:#f0f9fd;">
<div class="lefttop"><span></span>功能列表</div>
<dl class="leftmenu">
  <dd>
    <div class="title"><span><img src="/Public/Admin/image/leftico01.png" /></span>基本管理</div>
    <ul class="menuson">
    
    <?php if(($_SESSION['utype']) == "1"): ?><li><cite></cite><a href="<?php echo U('Shuju/index');?>"  target="rightFrame">数据统计</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Hb/show');?>"  target="rightFrame">红包设置</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Zhuanpan/add');?>"  target="rightFrame">转盘设置</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Guaguale/add');?>"  target="rightFrame">刮 刮 乐</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Saolei/show');?>"  target="rightFrame">扫&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;雷</a><i></i></li><?php endif; ?>
      
      <li><cite></cite><a href="<?php echo U('User/index');?>"  target="rightFrame">用户管理</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('User/hb');?>"  target="rightFrame">红包记录</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('User/tixian');?>"  target="rightFrame">提现记录</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('User/chongzhi');?>"  target="rightFrame">充值记录</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('User/yongjin');?>"  target="rightFrame">佣金查询</a><i></i></li>
    <?php if(($_SESSION['utype']) == "1"): ?><li><cite></cite><a href="<?php echo U('User/yongjinjiesuan');?>"  target="rightFrame">佣金结算</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Index/clearall');?>"  target="rightFrame">清空数据</a><i></i></li><?php endif; ?>
      <!--<li><cite></cite><a href="<?php echo U('Duizhang/index');?>"  target="rightFrame">对账系统</a><i></i></li>-->
    </ul>
  </dd>
  
<?php if(($_SESSION['utype']) == "1"): ?><dd>
    <div class="title"><span><img src="/Public/Admin/image/leftico01.png" /></span>基本配置</div>
    <ul class="menuson">
      <li><cite></cite><a href="<?php echo U('sysma/index');?>"  target="rightFrame">二维码设置</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('News/newsedit?ntype=1');?>"  target="rightFrame">客服设置</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('News/newsedit?ntype=2');?>"  target="rightFrame">佣金说明</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Chouzhong/edit');?>"  target="rightFrame">中奖信息</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Yongjin/index');?>"  target="rightFrame">代理等级</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Yongjin/fenxiao');?>"  target="rightFrame">三级佣金</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Sysconfig/index');?>" target="rightFrame">系统配置</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Sysuser/userlist');?>" target="rightFrame">系统用户</a><i></i></li>
    </ul>
  </dd>
  
  <dd>
    <div class="title"><span><img src="/Public/Admin/image/leftico01.png" /></span>微信设置</div>
    <ul class="menuson">
      <li><cite></cite><a href="<?php echo U('Index/jiekou');?>"  target="rightFrame">微信接口</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Wxhuifu/keyleixing');?>" target="rightFrame">微信回复</a><i></i></li>
      <li><cite></cite><a href="<?php echo U('Wxcaidan/show');?>" target="rightFrame">微信菜单</a><i></i></li>
    </ul>
  </dd><?php endif; ?>
  
</dl>
</body>
</html>