<?php
include '../Init.php';
OpenDB();

session_start();
@$s_login=$_SESSION['login'];
if (!isset($s_login)||$s_login=="") {
  header('Location: index.php');
  die('Access Denied!');
}

@$p_Title=trim($_POST['Title']);
@$p_Author=$_POST['Author'];
@$p_Keywords=$_POST['Keywords'];
@$p_Description=$_POST['Description'];
@$p_Footer=$_POST['Footer'];

if (isset($p_Title)&&$p_Title!="") {
  $result=RunDB("select * from site");
  if (mysql_num_rows($result)>0) {
    $row=mysql_fetch_assoc($result);
    RunDB("update site set Title='".mysql_real_escape_string($p_Title)."'".
      ",Author='".mysql_real_escape_string($p_Author)."'".
      ",Keywords='".mysql_real_escape_string($p_Keywords)."'".
      ",Description='".mysql_real_escape_string($p_Description)."'".
      ",Footer='".mysql_real_escape_string($p_Footer)."' where Title='".$row['Title']."'");
    die('Update Success!');
  }else{
    die('Site Not Ready!');
  }
}

$result=RunDB("select * from site");
if (mysql_num_rows($result)>0) {
  $row=mysql_fetch_assoc($result);
}else{
  die('Site Not Ready!');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>基本信息</title>
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
  <form>
    <div class="alert alert-success" id="msg-success" style="display:none;">保存成功</div>
    <div class="alert alert-danger" id="msg-error" style="display:none;"></div>
    <div class="form-group">
      <label class="control-label" for="Title">网站名称</label>
      <input type="text" class="form-control" name="Title" value="<?php echo htmlspecialchars($row['Title']); ?>">
    </div>
    <div class="form-group">
      <label class="control-label" for="Author">作者</label>
      <input type="text" class="form-control" name="Author" value="<?php echo htmlspecialchars($row['Author']); ?>">
    </div>
    <div class="form-group">
      <label class="control-label" for="Keywords">关键词</label>
      <input type="text" class="form-control" name="Keywords" value="<?php echo htmlspecialchars($row['Keywords']); ?>">
    </div>
    <div class="form-group">
      <label class="control-label" for="Description">网站简介</label>
      <input type="text" class="form-control" name="Description" value="<?php echo htmlspecialchars($row['Description']); ?>">
    </div>
    <div class="form-group">
      <label class="control-label" for="Footer">页脚代码</label>
      <textarea class="form-control" name="Footer" rows="6"><?php echo htmlspecialchars($row['Footer']); ?></textarea>
    </div>
    <div class="form-group text-center">
      <button class="btn btn-success btn-small" type="button" name="submit" onclick="save()">保存</button>&nbsp;&nbsp;&nbsp;&nbsp;
      <button class="btn btn-warning btn-small" type="reset">重置</button>
    </div>
  </form>
</div>
</body>
<script type="text/javascript">
  String.prototype.trim=function(){
    return this.replace(/(^\s*)|(\s*$)/g, "");
  }

  function save(){
    if (Timer_hideMsg) clearTimeout(Timer_hideMsg);
    hideMsg();
    var $Title=$("input[name='Title']");
    var $submit=$("button[name='submit']");
    $Title.val($Title.val().trim());
    if ($Title.val()=="") {
      $Title.parent().addClass("has-error");
    }else{
      $Title.parent().removeClass("has-error");
      $submit.addClass("disabled");
      $submit.html("保存中...");
      setTimeout(function(){
        //ajax异步提交
        $.ajax({            
            type:"POST",   //post提交方式默认是get
            url:"site.php", 
            data:$("form").serialize(),   //序列化               
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