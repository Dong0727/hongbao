<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset='utf-8'>
<title>欢迎登录后台管理系统</title>
<link href="/Public/Admin/css/base.css?v=<?php echo rand(0,100);?>" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/css/login.css?v=<?php echo rand(0,100);?>" rel="stylesheet" type="text/css" />
<style type="text/css">
body, td, th { font-family: "微软雅黑"; }
</style>
<script type="text/javascript" src="/Public/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="/Public/Admin/js/login.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
   	   $(function(){   	   	
   	   	   $('.loginbox').css({'position':'absolute','left':($(window).width()-692)/2}); 
   	   });
   	   $(window).resize(function(){  
           $('.loginbox').css({'position':'absolute','left':($(window).width()-692)/2});
        });   	   
   	    $(".loginbtn").click(function(){
            var uname=$("input[name='uname']").val();    
            var upass=$("input[name='upass']").val(); 
			var uma = $("input[name='uma']").val();     
            if(uname == '' || upass =='' || uma == ''){
                alert("填写错误");           
                return false;      
            }
            $.post('<?php echo U("ajaxlogin");?>',{uname:uname,upass:upass,uma:uma},function(data){
                if(data.status==1) {              
                    location.href='<?php echo U("Index/index");?>';
                    return false;           
                }
                if(data.status==2){
                    alert("错误次数太多，稍后再试");
                    return false;
                }
                if(data.status==3){
                    alert("登录失败");
                    return false;
                }
                if(data.status==4){
                    alert("验证码错误");
					$('#yzm').attr('src',"<?php echo U('ma?res='.rand(1,100));?>");
                    return false;
                }
            },'json'); 
        });
   });
</script>
</head>

<body style="background-color:#1c77ac; background-image:url(/Public/Admin/image/light.png); background-repeat:no-repeat; background-position:center top; overflow:hidden;">
<div id="mainBody">
  <div id="cloud1" class="cloud"></div>
  <div id="cloud2" class="cloud"></div>
</div>
<div class="logintop"> <span>欢迎登录后台管理界面平台</span> </div>
<div class="loginbody"> <span class="systemlogo" style="display:block"></span>
  <div class="loginbox">
    <ul>
      <li>
        <input name="uname" type="text" class="loginuser" placeholder='用户名'/>
      </li>
      <li>
        <input name="upass" type="password" class="loginpwd" placeholder='密码'/>
      </li>
      <li>
        <input name="uma" type="text"  placeholder='验证码' style="width: 70px; height: 32px; line-height: 32px; border-top: solid 1px #a7b5bc; border-left: solid 1px #a7b5bc; border-right: solid 1px #ced9df; border-bottom: solid 1px #ced9df; background: url(/Public/Admin/image/inputbg.gif) repeat-x; text-indent: 10px; border-radius:3px; "/>&nbsp;&nbsp;<img src="<?php echo U('ma');?>" height="30" style=" vertical-align:middle;" id="yzm" onClick="this.src='<?php echo U("ma?res=".rand(1,100));?>'">
      </li>
      <li>
        <input name="button" type="button" class="loginbtn" value="登录"/>
      </li>
    </ul>
  </div>
</div>
<div class="loginbm"></div>
</body>
</html>