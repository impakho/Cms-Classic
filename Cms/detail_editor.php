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
@$p_Title=trim($_POST['Title']);
@$p_Class=$_POST['Class'];
@$p_Author=$_POST['Author'];
@$p_Source=$_POST['Source'];
@$p_Content=htmlspecialchars_decode($_POST['Content']);

if (isset($p_ID)&&$p_ID!=""&&
  isset($p_Title)&&$p_Title!="") {
  $result=RunDB("select ID from detail where ID='".mysql_real_escape_string($p_ID)."'");
  if (mysql_num_rows($result)>0) {
    RunDB("update detail set Title='".mysql_real_escape_string($p_Title)."'".
      ",ClassID='".mysql_real_escape_string($p_Class)."'".
      ",Author='".mysql_real_escape_string($p_Author)."'".
      ",Source='".mysql_real_escape_string($p_Source)."'".
      ",UpdateTime='".strtotime("now")."'".
      ",Content='".$p_Content."' where ID='".mysql_real_escape_string($p_ID)."'");
    die($p_ID);
  }else{
    RunDB("insert into detail (Title,ClassID,Author,Source,Count,CreateTime,UpdateTime,Content) values (".
      "'".mysql_real_escape_string($p_Title)."'".
      ",'".mysql_real_escape_string($p_Class)."'".
      ",'".mysql_real_escape_string($p_Author)."'".
      ",'".mysql_real_escape_string($p_Source)."'".
      ",0".
      ",'".strtotime("now")."'".
      ",'".strtotime("now")."'".
      ",'".$p_Content."')");
    $result=RunDB("select ID from detail order by ID desc limit 1");
    $row=mysql_fetch_assoc($result);
    die($row['ID']);
  }
}

@$g_ID=trim($_GET['ID']);
$Type="添加新文章";
$Title="";
$Class="";
$Author="";
$Source="";
$Content="";

if (isset($g_ID)&&$g_ID!="") {
  $result=RunDB("select * from detail where ID='".mysql_real_escape_string($g_ID)."'");
  if (mysql_num_rows($result)>0){
    $row=mysql_fetch_assoc($result);
    $Type="修改文章";
    $Title=$row['Title'];
    $Class=$row['ClassID'];
    $Author=$row['Author'];
    $Source=$row['Source'];
    $Content=$row['Content'];
  }
}else{
  $g_ID="0";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>文章管理</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="editor/themes/default/default.css" />
<link rel="stylesheet" href="editor/plugins/code/prettify.css" />
<script charset="utf-8" src="editor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="editor/lang/zh-CN.js"></script>
<script charset="utf-8" src="editor/plugins/code/prettify.js"></script>
<style>
body {
  background: white;
}
#main {
  width: 60%;
  margin-top: 20px;
  margin-left: 20px;
}
.form-control {
  width: 50%;
}
</style>

</head>

<body>
<div id="main">
  <div id="nav">
    <button class="btn btn-primary btn-small" type="button" onclick="history.go(-1)">返回</button>
    <h3><?php echo htmlspecialchars($Type); ?></h3>
  </div>
  <div style="height:20px;"></div>
  <form>
    <div class="alert alert-success" id="msg-success" style="display:none;">保存成功</div>
    <div class="alert alert-danger" id="msg-error" style="display:none;"></div>
    <div class="form-group">
      <label class="control-label" for="Title">标题</label>
      <input type="text" class="form-control" name="Title" value="<?php echo htmlspecialchars($Title); ?>">
    </div>
    <div class="form-group">
      <label class="control-label" for="Class">分类</label>
      <select class="form-control" name="Class">
<?php
$result=RunDB("select ID,Name from list order by OrderID");
$isSelect=0;
while ($row=mysql_fetch_assoc($result)){
  if ($row['ID']==$Class) {
    $isSelect=1;
    echo '        <option value="'.$row['ID'].'" selected="selected">'.htmlspecialchars($row['Name']).'</option>'."\n";
  }else{
    echo '        <option value="'.$row['ID'].'">'.htmlspecialchars($row['Name']).'</option>'."\n";
  }
}
if ($isSelect==0) echo '        <option value="'.$Class.'" selected="selected">-------------------- 未分类 --------------------</option>'."\n";
?>
      </select>
    </div>
    <div class="form-group">
      <label class="control-label" for="Author">作者</label>
      <input type="text" class="form-control" name="Author" value="<?php echo htmlspecialchars($Author); ?>">
    </div>
    <div class="form-group">
      <label class="control-label" for="Source">来源</label>
      <input type="text" class="form-control" name="Source" value="<?php echo htmlspecialchars($Source); ?>">
    </div>
    <div class="form-group">
      <label class="control-label">内容</label>
      <textarea id="Content" style="width:1000px;height:300px;"><?php echo $Content; ?></textarea>
    </div>
    <div style="height:10px;"></div>
    <div class="form-group text-center">
      <input type="hidden" name="ID" value="<?php echo htmlspecialchars($g_ID); ?>">
      <button class="btn btn-success btn-small" type="button" name="submit" onclick="save()">保存</button>&nbsp;&nbsp;&nbsp;&nbsp;
      <button class="btn btn-warning btn-small" type="reset" onclick="if (confirm('确实重置？')) $('#Content').html(oldContent);">重置</button>
    </div>
    <div style="height:20px;"></div>
  </form>
</div>
</body>
<script type="text/javascript">
  var oldContent=$("#Content").html();

  KindEditor.ready(function(K) {
    window.editor = K.create('textarea[id="Content"]', {
      resizeType : 1,
      allowPreviewEmoticons : false,
      allowImageUpload : false,
      items : [
        'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
        'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
        'insertunorderedlist', '|', 'emoticons', 'image', 'link']
    });
    prettyPrint();
  });

  String.prototype.trim=function(){
    return this.replace(/(^\s*)|(\s*$)/g, "");
  }

  function save(){
    if (Timer_hideMsg) clearTimeout(Timer_hideMsg);
    hideMsg();
    var $ID=$("input[name='ID']");
    var $Title=$("input[name='Title']");
    var $Class=$("select[name='Class']");
    var $Author=$("input[name='Author']");
    var $Source=$("input[name='Source']");
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
            url:"detail_editor.php", 
            data:{ ID: $ID.val(), Title: $Title.val(), Class: $Class.val(), 
              Author: $Author.val(), Source: $Source.val(), Content: window.editor.html() },
            error:function(request) {      // 设置表单提交出错                 
                $submit.removeClass("disabled");
                $submit.html("保存");
                $("#msg-error").html(request);
                $("#msg-error").show();
            },
            success:function(data) {
                $("input[name='ID']").val(data);
                $submit.removeClass("disabled");
                $submit.html("保存");
                $("#msg-success").show();
                Timer_hideMsg=setTimeout("hideMsg()",2000);
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