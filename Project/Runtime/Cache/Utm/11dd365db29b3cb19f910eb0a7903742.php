<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style type="text/css">
* { font-size: 9pt; border: 0; margin: 0; padding: 0; }
body { font-family: '微软雅黑'; }
a { text-decoration: none; color: #000; }
h2 { font-weight: normal; }
.error { width: 80%; margin: 0 auto; margin-top: 75px; padding-top: 65px; text-align: center; }
.error h2 { font-size: 22px; }
.error p { line-height: 35px; color: #717678; }
</style>
</head>
<div class="error">
  <p><img src='/Public/Admin/image/success.png' width='60'></p>
  <h2><?php echo($message);?></h2>
  <p>页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></p>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	  var time = --wait.innerHTML;
	  if(time <= 0) {
		    location.href = href;
		    clearInterval(interval);
	  };
}, 1000);
})();
</script>
</body></html>