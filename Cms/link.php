<?php
include '../Init.php';

session_start();
@$s_login=$_SESSION['login'];
if (!isset($s_login)||$s_login=="") {
  header('Location: index.php');
  die('Access Denied!');
}

@$p_action=trim($_POST['action']);
@$p_ID=trim($_POST['ID']);
@$p_Title=$_POST['Title'];
@$p_Url=$_POST['Url'];

OpenDB();
if (isset($p_action)&&$p_action!="") {
  switch ($p_action) {
    case 'save':
      $result=RunDB("select * from link where ID='".mysql_real_escape_string($p_ID)."'");
      if (mysql_num_rows($result)>0) {
        RunDB("update link set Title='".mysql_real_escape_string($p_Title)."'".
          ",Url='".mysql_real_escape_string($p_Url)."' where ID='".mysql_real_escape_string($p_ID)."'");
        die('Update Success!');
      }else{
        die('Invalid Argument!');
      }
      break;
    
    case 'delete':
      $result=RunDB("select * from link where ID='".mysql_real_escape_string($p_ID)."'");
      if (mysql_num_rows($result)>0) {
        RunDB("delete from link where ID='".mysql_real_escape_string($p_ID)."'");
        die('Refresh!');
      }else{
        die('Invalid Argument!');
      }
      break;
    
    case 'add':
      RunDB("insert into link (Title,Url) values ('".mysql_real_escape_string($p_Title)."'".
        ",'".mysql_real_escape_string($p_Url)."')");
      die('Refresh!');
      break;
    
    default:
      break;
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>友情链接</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
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
  <div class="alert alert-success" id="msg-success" style="display:none;"></div>
  <div class="alert alert-danger" id="msg-error" style="display:none;"></div>
  <table class="table">
    <thead>
      <tr>
        <th>排序</th>
        <th>名称</th>
        <th>超链接</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
<?php
$result=RunDB("select * from link order by ID");
if (mysql_num_rows($result)>0) {
  $i=0;
  while($link=mysql_fetch_assoc($result)) {
    $i++;
    echo '      <tr id="link-'.$link['ID'].'">'."\n";
    echo '        <td>'.$i.'</td>'."\n";
    echo '        <td><input type="text" class="form-control" name="Title" value="'.htmlspecialchars($link['Title']).'"></td>'."\n";
    echo '        <td><input type="text" class="form-control" name="Url" value="'.htmlspecialchars($link['Url']).'"></td>'."\n";
    echo '        <td>'."\n";
    echo '          <input type="hidden" name="action" value="save">'."\n";
    echo '          <input type="hidden" name="ID" value="'.$link['ID'].'">'."\n";
    echo '          <button class="btn btn-success btn-small" type="button" name="submit" onclick="save(\'link-'.$link['ID'].'\')">保存</button>&nbsp;&nbsp;'."\n";
    echo '          <button class="btn btn-danger btn-small" type="button" name="delete" onclick="_delete('.$link['ID'].',\'link-'.$link['ID'].'\')">删除</button>'."\n";
    echo '        </td>'."\n";
    echo '      </tr>'."\n";
  }
}
?>
    </tbody>
  </table>
  <div style="height:50px;">&nbsp;</div>
  <form>
    <table class="table" style="width:70%;">
      <h4>添加友情链接</h4>
      <thead>
        <tr>
          <th>名称</th>
          <th>超链接</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text" class="form-control" name="Title"></td>
          <td><input type="text" class="form-control" name="Url"></td>
          <td>
            <input type="hidden" name="action" value="add">
            <button class="btn btn-primary btn-small" type="button" name="submit" onclick="add()">添加</button>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
</body>
<script type="text/javascript">
  String.prototype.trim=function(){
    return this.replace(/(^\s*)|(\s*$)/g, "");
  }

  function save(trid){
    if (Timer_hideMsg) clearTimeout(Timer_hideMsg);
    hideMsg();
    var $submit=$("#"+trid+" button[name='submit']");
    $submit.addClass("disabled");
    $submit.html("保存中...");
    setTimeout(function(){
      //ajax异步提交
      $.ajax({            
          type:"POST",   //post提交方式默认是get
          url:"link.php", 
          data:$("#"+trid+" input").serialize(),   //序列化               
          error:function(request) {      // 设置表单提交出错                 
              $submit.removeClass("disabled");
              $submit.html("保存");
              $("#msg-error").html(request);
              $("#msg-error").show();
          },
          success:function(data) {
              if (data=="Update Success!") {
                $submit.removeClass("disabled");
                $submit.html("保存");
                $("#msg-success").html('保存成功');
                $("#msg-success").show();
                Timer_hideMsg=setTimeout("hideMsg()",2000);
              }else{
                $submit.removeClass("disabled");
                $submit.html("保存");
                $("#msg-error").html('未知错误：'+data);
                $("#msg-error").show();
              }
          }
      });
    },500);
  }

  function _delete(id,trid){
    if (!confirm('确实删除？')) return;
    if (Timer_hideMsg) clearTimeout(Timer_hideMsg);
    hideMsg();
    var $deleteBtn=$("#"+trid+" button[name='delete']");
    $deleteBtn.addClass("disabled");
    $deleteBtn.html("删除中...");
    setTimeout(function(){
      //ajax异步提交
      $.ajax({            
          type:"POST",   //post提交方式默认是get
          url:"link.php", 
          data:{action: "delete", ID: id},   //序列化               
          error:function(request) {      // 设置表单提交出错                 
              $deleteBtn.removeClass("disabled");
              $deleteBtn.html("删除");
              $("#msg-error").html(request);
              $("#msg-error").show();
          },
          success:function(data) {
              if (data=="Refresh!") {
                document.location.reload();
              }else{
                $deleteBtn.removeClass("disabled");
                $deleteBtn.html("删除");
                $("#msg-error").html('未知错误：'+data);
                $("#msg-error").show();
              }
          }
      });
    },500);
  }

  function add(){
    if (Timer_hideMsg) clearTimeout(Timer_hideMsg);
    hideMsg();
    var $submit=$("form button[name='submit']");
    $submit.addClass("disabled");
    $submit.html("添加中...");
    setTimeout(function(){
      //ajax异步提交
      $.ajax({            
          type:"POST",   //post提交方式默认是get
          url:"link.php", 
          data:$("form").serialize(),   //序列化               
          error:function(request) {      // 设置表单提交出错                 
              $submit.removeClass("disabled");
              $submit.html("添加");
              $("#msg-error").html(request);
              $("#msg-error").show();
          },
          success:function(data) {
              if (data=="Refresh!") {
                document.location.reload();
              }else{
                $submit.removeClass("disabled");
                $submit.html("添加");
                $("#msg-error").html('未知错误：'+data);
                $("#msg-error").show();
              }
          }
      });
    },500);
  }

  var Timer_hideMsg;

  function hideMsg(){
    $("#msg-success").hide();
    $("#msg-error").hide();
  }
</script>
</html>
<?php
CloseDB();
?>