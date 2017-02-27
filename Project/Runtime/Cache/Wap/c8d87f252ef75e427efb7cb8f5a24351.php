<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>喜迎新年 精彩无限</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
<script src="/Public/js/jquery-2.1.1.min.js"></script>
<link href="/Public/css/base.css" rel="stylesheet" type="text/css">
<link href="/Public/Weixin/css/index.css?v=<?php echo rand(0,99);?>" rel="stylesheet" type="text/css">
<link href="/Public/Weixin/css/head.css?v=<?php echo rand(0,99);?>" rel="stylesheet" type="text/css">
<style></style>
<script>
$(document).ready(function () { 
   $('.pay2 .ptxt6').click(function(){
	   location.href='<?php echo U("Index/hb?hbid=".$hbid);?>';
   });
   $('.pay2 .ptxt5').click(function(){
	   $.post("<?php echo U('Ajax/getfaqiancishu');?>",{},function(res){
		   if(res.cishu < 99){
			    callpay();
		   } else {
			    $('.msgbox .txt').text('今天超过上限，请明天再来！');
		        $('.msgbox').show();
		   }
	   });
   });
});

function jsApiCall() {
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo ($jsApiParameters); ?>,
			function(res){
				if(res.err_msg == "get_brand_wcpay_request:ok"){
					//支付成功
					$("input[name='msgbox']").val(1);
			        $('.msgbox .txt').text('支付成功！');
		            $('.msgbox').show();
				}
			}
		);
}

function callpay()	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    } else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		} else {
		    jsApiCall();
		}
}
</script>
</head>
<body>
<section class="container">
  <div class="container-hd">
    <figrue class="figure"><img src="/Public/Weixin/img/banner-index.jpg" alt="" /></figrue>
    <div class="container-hd_text"> <i class="i i-text i-text_left"></i> <i class="i i-text i-text_right"></i> </div>
    <i class="i-lantern i-lantern_left"></i> <i class="i-lantern i-lantern_right"></i>
    <div class="fireworks"> <i class="i-firework"></i> <i class="i-firework"></i> </div>
    <i class="i i-character bounceIn floatUpDown"></i> </div>
</section>

<div id="scroll_div" class="scroll_div">
    <div id="scroll_begin">
        <b><?php echo ($sysconfig[ctongzhi]); ?></b>
    </div>
    <div id="scroll_end"></div>
</div>
<div class="pay2">
  <p class="ptxt1">抢红包步骤</p>
  <p class="ptxt2">支付<?php echo ($hb[hzhifue]/100); ?>元必得一个红包</p>
  <p class="ptxt3">一、微信支付</p>
  <p class="ptxt4">￥<font class="price"><?php echo ($hb[hzhifue]/100); ?></font></p>
  <p class="ptxt5">立即支付</p>
  <p class="ptxt3">二、支付完成后，记得去拆红包哦！</p>
  <p class="ptxt6">现在去抢红包</p>
</div>
<script>
$(document).ready(function () { 
   var zhanshiboxsetInterval;
   $(function(){	
      /*   
	   if( String(location).indexOf("Wap&c=Index&a=index") == -1 && String(location).indexOf("Wap&c=Index&a=hb") == -1 ){
		    $.post("<?php echo U('Ajax/checkzhanghu');?>",{},function(res){
				if(res.code==1){
					 alert('请先拆红包');
					 $.post("<?php echo U('Ajax/gethbid');?>",{hbid:<?php echo (intval($hb[id])); ?>},function(res){
					     location.href='<?php echo U("hb?hbid=");?>'+res.hbid;
					 },'json');
				}
			},'json');
	   }
	   */
	   $.post("<?php echo U('Ajax/getfaqiancishu');?>",{},function(res){});
	   $.post('<?php echo U("Ajax/fayongjin");?>',{},function(){	});
	   $.post('<?php echo U("Ajax/checkuserhb");?>',{},function(){	});
	   zhanshiboxsetInterval = setInterval(zhanshibox, 10000);
	   zhanshibox();
   });
   $('#qianghongbao').click(function(){
	   $.post("<?php echo U('Ajax/gethbid');?>",{hbid:<?php echo (intval($hbid)); ?>},function(res){
			location.href='<?php echo U("Index/hb?hbid=");?>'+res.hbid;
	   },'json');
   });
   $('#checkdaili').click(function(){
	   $.post("<?php echo U('Ajax/checkdaili');?>",{},function(res){
		   if(res.code == 0){
			   location.href='<?php echo U("Index/kefu");?>';
		   } else {
			   location.href='<?php echo U("Index/daili");?>';
		   }
	   },'json');
   });
   $('.msgbox .but').click(function(){
	    var msgbox = $("input[name='msgbox']").val();
		if(msgbox == 1){
            $.post("<?php echo U('Ajax/checkzhanghu');?>",{hbid:<?php echo (intval($hbid)); ?>},function(res){
				if(res.code==1){
					location.href = "<?php echo U('Index/hb?hbid='.$hbid);?>";
				} else {
				    location.href = "<?php echo U('Chongzhi/chong?ctype=1&hbid='.$hbid);?>";
				}
			},'json');
			return false;
		}
		if(msgbox == 2){
            $.post("<?php echo U('Ajax/checkzhanghu');?>",{hbid:<?php echo (intval($hbid)); ?>},function(res){
				if(res.code==1){
					location.href = "<?php echo U('Zhuanpan/index');?>";
				} else {
				    location.href = "<?php echo U('Chongzhi/chong?ctype=2&hbid='.$hbid);?>";
				}
			},'json');
			return false;
		}
		if(msgbox == 3){
            $.post("<?php echo U('Ajax/checkzhanghu');?>",{hbid:<?php echo (intval($hbid)); ?>},function(res){
				if(res.code==1){
					location.href = "<?php echo U('Guaguale/index');?>";
				} else {
				    location.href = "<?php echo U('Chongzhi/chong?ctype=4&hbid='.$hbid);?>";
				}
			},'json');
			return false;
		}
		if(msgbox == 4){
			location.href = "<?php echo U('Ucenter/index');?>";	
			return false;
		}
		$('.msgbox').hide();
   });   
});
function zhanshibox(){
	$.post("<?php echo U('Ajax/zhanshibox');?>",{},function(res){	 
	    $('.zhanshibox').show(500);
	    if(res.code == 1){
		    $('.zhanshibox').hide();
			clearInterval(zhanshiboxsetInterval);
		}   
		$('.zhanshibox p').html(res.html);
		$('.zhanshibox p').fadeIn(500);
	},'json');
	setTimeout(function(){$('.zhanshibox p').fadeOut(500);}, 8000);
}
</script>
<style>
.zhanshibox { position: fixed; top: 0; left: 0; right: 0; height: 24px; background-color: rgba(0,0,0,0.3); text-align: center; font-size: 12px; line-height: 24px; color: #FFF; display: none; z-index: 9; }
.zhanshibox p { display: none; }
.zhanshibox span { color: #3F0; border: #3F0 1px solid; padding-left: 2px; padding-right: 2px; border-radius: 2px; }
</style>
<div class="msgbox">
  <input type="hidden" value="0" name="msgbox">
  <!--
      0无跳转 
      1拆红包 
      2转盘 
      3刮刮卡
      4个人中心
  -->
  <div class="box">
    <p class="title">提示</p>
    <p class="txt">恭喜您获得0元红包</p>
    <p class="but">确定</p>
  </div>
</div>
<div class="zhanshibox">
  <p></p>
</div>
<div id="dengdai">
    <div class="loading">
           <span></span>
           <span></span>
           <span></span>
           <span></span>
           <span></span>
    </div>
</div>
<div style="height:60px;"></div>
<div class="footer">
  <ul>
    <li style="float:left;width: 33.3%;text-align: center;"><a href="<?php echo U('Index/index?randres='.rand(100,999));?>">抢红包</a></li>
    <li style="display:none;"><a href="<?php echo U('Zhuanpan/index?randres='.rand(100,999));?>">转盘</a></li>
    <li style="display:none;"><a href="<?php echo U('Guaguale/index?randres='.rand(100,999));?>">刮刮</a></li>
    <!--<li><a href="<?php echo U('Index/yongjin');?>">佣金</a></li>-->
    <li style="display:none;"><a href="<?php echo U('Saolei/index?randres='.rand(100,999));?>">扫雷</a></li>
    <li style="float:left;width: 33.3%;text-align: center;" id="checkdaili">代理</li>
    <li style="float:left;width: 33.3%;text-align: center;"><a href="<?php echo U('Ucenter/index?randres='.rand(100,999));?>">我</a></li>
  </ul>
</div>

<script>
function ScrollImgLeft() {
    var speed = 20
    var scroll_begin = document.getElementById("scroll_begin");
    var scroll_end = document.getElementById("scroll_end");
    var scroll_div = document.getElementById("scroll_div");
    scroll_end.innerHTML = scroll_begin.innerHTML

    function Marquee() {
        if(scroll_end.offsetWidth - scroll_div.scrollLeft <= 0)
            scroll_div.scrollLeft -= scroll_begin.offsetWidth
        else
            scroll_div.scrollLeft++
    }
    var MyMar = setInterval(Marquee, speed)
    scroll_div.onmouseover = function() {
        clearInterval(MyMar)
    }
    scroll_div.onmouseout = function() {
        MyMar = setInterval(Marquee, speed)
    }
}
ScrollImgLeft();
</script>
</body>
</html>