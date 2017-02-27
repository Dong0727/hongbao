<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎登录后台管理系统</title>
<link href="/Public/Admin/css/base.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/css/right.css" rel="stylesheet" type="text/css">
<link href="/Public/css/page.css" rel="stylesheet" type="text/css">
<script src="/Public/js/jquery-2.1.1.min.js"></script>
<script>
$(document).ready(function(){
   $(function(){
      $('.rightinfo tbody tr:odd').css("backgroundColor","#f5f8fa");
   });
   $(".imgact1").click(function(){
	   var id=$(this).parent().find("input[name='id']").val();
	   var img=($(this).attr("src")=='/Public/Admin/image/no.gif') ? "/Public/Admin/image/yes.gif":"/Public/Admin/image/no.gif";
	   $(this).attr("src",img);
	   $.post("<?php echo U('ajaxhbcode');?>",{id:id},function(data){});
   });
});
function del(){ if(confirm("确定要删除吗？")) {   return true;  }  else  {  return false;  } }
</script>
</head>

<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="<?php echo U('Index/center');?>">首页</a></li>
    <li><a href="<?php echo U('show');?>">管理红包</a></li>
  </ul>
</div>
<div class="rightinfo">
  <div class="tools"> 
    <ul class="toolbar">
      <li><a href="<?php echo U('add');?>"><span><img src="/Public/Admin/image/t01.png" /></span>添加</a></li> 
    </ul>
  </div>
    <table class="tablelist">
    <thead>
      <tr>
        <th>名字</th>
        <th>个数</th>
        <th>最小金额</th>
        <th>最大金额</th>
        <th>显示</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      
      <?php if(is_array($seldata)): foreach($seldata as $key=>$v): ?><tr height="45" align="center">
        <td><font color="#FF0000"><?php echo ($v[hzhifue]/100); ?></font>元竞猜红包</td>
        <td><?php echo ($v[hgeshu]); ?>个</td>
        <td><?php echo ($v['hminmoney']/100); ?></td>        
        <td><?php echo ($v['hmaxmoney']/100); ?></td>
        <td>
            <input type="hidden" name="id" value="<?php echo ($v[id]); ?>" />
            <?php if(($v[hcode]) == "1"): ?><img src="/Public/Admin/image/yes.gif" style="cursor:pointer;vertical-align:middle" class="imgact1" />
            <?php else: ?>
            <img src="/Public/Admin/image/no.gif" style="cursor:pointer;vertical-align:middle" class="imgact1" /><?php endif; ?></td>
        <td>
          <img src="/Public/Admin/image/leftico03.png" width="14">
          <a href="<?php echo U('add?id='.$v['id']);?>">编辑</a>&nbsp;
          <img src="/Public/Admin/image/t03.png" width="14">
          <a onClick="return del()" href="<?php echo U('del?id='.$v['id']);?>" class="tablelink">删除</a>
        </td>
      </tr><?php endforeach; endif; ?>
      
    </tbody>
  </table>
</div>
<div class="pages">
   <?php echo ($page); if(empty($seldata)): ?><font color='#ff0000'>暂无数据</font><?php endif; ?>
</div>
</body>
</html>