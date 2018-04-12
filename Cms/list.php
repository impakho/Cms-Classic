<?php
include '../Init.php';

session_start();
@$s_login=$_SESSION['login'];
if (!isset($s_login)||$s_login=="") {
  header('Location: index.php');
  die('Access Denied!');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>分类设置</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="js/themes/default/easyui-new.css" />
<link rel="stylesheet" type="text/css" href="js/themes/icon.css" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="js/jquery.etree.js"></script>
<script type="text/javascript" src="js/jquery.etree.lang.js"></script>
<style>
body {
  background: white;
}
#main {
  width: 60%;
  margin-top: 20px;
  margin-left: 20px;
}
</style>
</head>
<body>
<div id="main">
  <div style="margin-bottom:10px">
    <a href="javascript:void(0)" onclick="$('#list').etree('create')">新建</a>
    <a href="javascript:void(0)" onclick="$('#list').etree('edit')">修改</a>
    <a href="javascript:void(0)" onclick="$('#list').etree('destroy')">删除</a>
    <a href="javascript:void(0)" onclick="$('#list').find('.tree-node-selected').removeClass('tree-node-selected')">取消选择</a>
  </div>
  <ul id="list"></ul>
  <div id="menu" class="easyui-menu" style="width:100px;">
    <div onclick="$('#list').etree('create')" data-options="iconCls:'icon-add'">新建</div>
    <div onclick="$('#list').etree('edit')" data-options="iconCls:'icon-edit'">修改</div>
    <div onclick="$('#list').etree('destroy')" data-options="iconCls:'icon-remove'">删除</div>
  </div>
</div>
<script type="text/javascript">
$(function(){
  $('#list').etree({
    url: 'list_data.php',
    createUrl: 'list_data.php?action=create',
    updateUrl: 'list_data.php?action=update',
    destroyUrl: 'list_data.php?action=delete',
    dndUrl: 'list_data.php?action=move',
    onContextMenu: function(e,node){
      e.preventDefault();
      $(this).tree('select',node.target);
      $('#menu').menu('show',{
        left: e.pageX,
        top: e.pageY
      });
    }
  });
});
</script>
</body>
</html>