<?php
include '../Init.php';
OpenDB();

session_start();
@$s_login=$_SESSION['login'];
if (!isset($s_login)||$s_login=="") {
  header('Location: index.php');
  die('Access Denied!');
}

@$p_ID=trim($_POST['ID']);
@$p_Type=$_POST['Type'];

if (isset($p_ID)&&$p_ID!="") {
  $result=RunDB("select ID from list where ID='".mysql_real_escape_string($p_ID)."'");
  if (mysql_num_rows($result)>0) {
    if ($p_Type=="on") {
      $p_Type="1";
    }else{
      $p_Type="0";
    }
    RunDB("update list set Type=".$p_Type." where ID='".mysql_real_escape_string($p_ID)."'");
    die('Update Success!');
  }else{
    die('Invalid Argument!');
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>分类转文章</title>
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
        <th width="10%">排序</th>
        <th width="20%">是否转为文章</th>
        <th width="40%">名称</th>
        <th width="30%"></th>
      </tr>
    </thead>
    <tbody>
<?php
$result=RunDB("select ID,Name,Type from list order by OrderID");
if (mysql_num_rows($result)>0) {
  $i=0;
  while($list=mysql_fetch_assoc($result)) {
    $i++;
    echo '      <tr id="list-'.$list['ID'].'">'."\n";
    echo '        <td>'.$i.'</td>'."\n";
    if ($list['Type']=="0") {
      echo '        <td><input type="checkbox" name="Type"></td>'."\n";
    }else{
      echo '        <td><input type="checkbox" name="Type" checked="checked"></td>'."\n";
    }
    echo '        <td>'.$list['Name'].'</td>'."\n";
    echo '        <td>'."\n";
    echo '          <input type="hidden" name="ID" value="'.$list['ID'].'">'."\n";
    echo '          <button class="btn btn-success btn-small" type="button" name="submit" onclick="save(\'list-'.$list['ID'].'\')">保存</button>&nbsp;&nbsp;'."\n";
    echo '          <button class="btn btn-primary btn-small" type="button" name="modify" onclick="document.location.href=\'list_detail_editor.php?ID='.$list['ID'].'\'">修改文章</button>'."\n";
    echo '        </td>'."\n";
    echo '      </tr>'."\n";
  }
}
?>
    </tbody>
  </table>
  <div style="height:20px;">&nbsp;</div>
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
          url:"list_detail.php", 
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