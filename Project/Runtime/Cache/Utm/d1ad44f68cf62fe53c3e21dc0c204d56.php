<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎登录后台管理系统</title>
<link href="/Public/Admin/css/base.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/css/right.css" rel="stylesheet" type="text/css">
<link href="/Public/css/page.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/Public/js/jquery-2.1.1.min.js"></script>
<script>
$(document).ready(function(){
   $(function(){
      $('.rightinfo tbody tr:odd').css("backgroundColor","#f5f8fa");
   });
   $(".imgact1").click(function(){
	   var id=$(this).parent().find("input[name='id']").val();
	   var img=($(this).attr("src")=='/Public/Admin/image/no.gif') ? "/Public/Admin/image/yes.gif":"/Public/Admin/image/no.gif";
	   $(this).attr("src",img);
	   $.post("<?php echo U('ajaxuservip');?>",{id:id},function(data){});
   });
   $(".imgact2").click(function(){
	   var id=$(this).parent().find("input[name='id']").val();
	   var img=($(this).attr("src")=='/Public/Admin/image/no.gif') ? "/Public/Admin/image/yes.gif":"/Public/Admin/image/no.gif";
	   $(this).attr("src",img);
	   $.post("<?php echo U('ajaxuserstate');?>",{id:id},function(data){});
   });
   $("input[name='uvip']").change(function(){
	   var id=$(this).parent().find("input[name='id']").val();
	   var uvip = parseInt($(this).val());
	   if(isNaN(uvip)){return false;}
	   $.post("<?php echo U('ajaxvip');?>",{id:id,uvip:uvip},function(data){});
   });
});
</script>
</head>

<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="<?php echo U('Index/center');?>">首页</a></li>
    <li>用户</li>
  </ul>
</div>
<div class="rightinfo">
  <div class="tools"> 
    <ul class="toolbar">
      <li><a href="<?php echo U('index?ord=uzhengzong');?>"><span><img src="/Public/Admin/image/ico03.png" /></span>佣金排行</a></li> 
      <li><a href="<?php echo U('index?ord=uchongzong');?>"><span><img src="/Public/Admin/image/ico03.png" /></span>充值排行</a></li> 
      <li><a href="<?php echo U('index?ustate=2');?>"><span><img src="/Public/Admin/image/t08.png" /></span>黑名单</a></li> 
      <li style="background:#FFF; text-indent:1em; border:0">
      <form name="fsoso" method="post" action="">
      昵称、用户ID、Openid：<input type="text" name="sci" class="dfinput" value="<?php echo ($sci); ?>" style="width:200px" />
      <input name="submit" class="btn" value="查询" type="submit" >
      </form></li>
    </ul>
  </div>
  <table class="tablelist">
    <thead>
      <tr>
        <th>ID</th>
        <th>Openid</th>
        <th>昵称</th>
        <th>头像</th>
        <th>推广数</th>
        <th>推广额</th>
        <th>充值</th>
        <th>余额</th>
        <th>提现</th>
        <th>收红包</th>
        <th>总佣金</th>
        <th>已发佣金</th>
        <th>未发佣金</th>
        <th>发包</th>
        <?php if(($_SESSION['utype']) == "1"): ?><th>代理等级</th><?php endif; ?>
        <th>开启代理</th>
        <th>黑名单</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if(is_array($user)): foreach($user as $key=>$v): ?><tr height="45" align="center">
        <td><?php echo ($v['id']); ?></td>
        <td><?php echo ($v['uopenid']); ?><br>备：<?php echo ($v['ubeiopenid']); ?></td>
        <td><?php echo ($v['uickname']); ?></td>        
        <td><?php if(!empty($v[uheadimgurl])): ?><img src="<?php echo ($v['uheadimgurl']); ?>" height="54" width="54"/><?php endif; ?></td>
        <td><?php echo ($v['tuijiannum']); ?></td>
        <td><?php echo ($v['tuijianchong']); ?></td>
        <td><?php echo ($v['uchongzong']/100); ?></td>
        <td><?php echo ($v['uqianchong']/100); ?></td>
        <td><?php echo ($v['tixiane']/100); ?></td>
        <td><?php echo ($v['hb']/100); ?></td>
        <td><?php echo ($v['uzhengzong']/100); ?></td>
        <td><?php echo ($v['uqianfa']/100); ?></td>
        <td><?php echo ($v['uqianzheng']/100); ?></td>
        <td><a href="<?php echo U('fahb?userid='.$v[id]);?>"><?php echo ($v['fahbnum']); ?></a></td>
        <?php if(($_SESSION['utype']) == "1"): ?><td>
           <input type="hidden" name="id" value="<?php echo ($v[id]); ?>" />
           <input name="uvip" type="text" value="<?php echo ($v['uvip']); ?>" class="dfinput" style="width:30px; border:0;">
        </td><?php endif; ?>
        
        <td>
            <input type="hidden" name="id" value="<?php echo ($v[id]); ?>" />
            <?php if($v[uvip] > 0 ): ?><img src="/Public/Admin/image/yes.gif" style="cursor:pointer;vertical-align:middle" class="imgact1" />
            <?php else: ?>
            <img src="/Public/Admin/image/no.gif" style="cursor:pointer;vertical-align:middle" class="imgact1" /><?php endif; ?>
        </td>
        
        <td>
            <input type="hidden" name="id" value="<?php echo ($v[id]); ?>" />
            <?php if(($v[ustate]) == "2"): ?><img src="/Public/Admin/image/yes.gif" style="cursor:pointer;vertical-align:middle" class="imgact2" />
            <?php else: ?>
            <img src="/Public/Admin/image/no.gif" style="cursor:pointer;vertical-align:middle" class="imgact2" /><?php endif; ?>
        </td>
            
        <td>
          <img src="/Public/Admin/image/leftico03.png" width="14">
          <a href="<?php echo U('edit?id='.$v[id].'&nowpage='.$nowpage);?>">编辑</a>&nbsp;
        </td>
      </tr><?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
<div class="pages">
  <?php echo ($page); if(empty($user)): ?><font color='#ff0000'>暂无数据</font><?php endif; ?>
</div>
</body>
</html>