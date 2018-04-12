<?php
include '../Init.php';
OpenDB();

session_start();
@$s_login=$_SESSION['login'];
if (!isset($s_login)||$s_login=="") {
  header('Location: index.php');
  die('Access Denied!');
}

@$p_File_ClassID=trim($_POST['File_ClassID']);
@$p_File_ID=trim($_POST['File_ID']);

if (isset($p_File_ClassID)&&$p_File_ClassID!=""&&
  isset($p_File_ID)&&$p_File_ID!=""&&
  isset($_FILES["File"])) {
  $result=RunDB("select * from img where ClassID='".mysql_real_escape_string($p_File_ClassID).
    "' and ID='".mysql_real_escape_string($p_File_ID)."'");
  if (mysql_num_rows($result)>0) {
    $row=mysql_fetch_assoc($result);
    if ($_FILES["File"]["type"] == "image/jpeg") {
      if ($_FILES["File"]["error"] <= 0) {
        move_uploaded_file($_FILES["File"]["tmp_name"],"..".$row['Url']);
      }
    }
  }
  header('Location: img.php');
  die('Refresh!');
}

@$p_ClassID=trim($_POST['ClassID']);
@$p_ID=trim($_POST['ID']);
@$p_Display=$_POST['Display'];
@$p_Url=$_POST['Url'];
@$p_Title=$_POST['Title'];
@$p_Link=$_POST['Link'];

if (isset($p_ClassID)&&$p_ClassID!=""&&
  isset($p_ID)&&$p_ID!="") {
  $result=RunDB("select * from img where ClassID='".mysql_real_escape_string($p_ClassID).
    "' and ID='".mysql_real_escape_string($p_ID)."'");
  if (mysql_num_rows($result)>0) {
    if ($p_Display=="on") {
      $p_Display="1";
    }else{
      $p_Display="0";
    }
    if (isset($p_Title)) {
      RunDB("update img set Display=".$p_Display.
      	",Url='".mysql_real_escape_string($p_Url)."'".
        ",Title='".mysql_real_escape_string($p_Title)."'".
        ",Link='".mysql_real_escape_string($p_Link)."' where ClassID='".mysql_real_escape_string($p_ClassID).
        "' and ID='".mysql_real_escape_string($p_ID)."'");
    }else{
      RunDB("update img set Display=".$p_Display.
      	",Url='".mysql_real_escape_string($p_Url)."'".
        ",Link='".mysql_real_escape_string($p_Link)."' where ClassID='".mysql_real_escape_string($p_ClassID).
        "' and ID='".mysql_real_escape_string($p_ID)."'");
    }
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
<title>滚动图片</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<style>
body {
  background: white;
}
#main {
  width: 80%;
  margin-top: 20px;
  margin-left: 20px;
}
img {
  height: 30px;
}
</style>

</head>

<body>
<div id="main">
  <div class="alert alert-success" id="msg1-success" style="display:none;">保存成功</div>
  <div class="alert alert-danger" id="msg1-error" style="display:none;"></div>
  <table class="table">
    <caption>Banner图片</caption>
    <thead>
      <tr>
        <th>排序</th>
        <th>是否显示</th>
        <th>图片预览</th>
        <th>图片地址</th>
        <th>超链接</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
<?php
$result=RunDB("select * from img where ClassID=1 order by ID");
if (mysql_num_rows($result)>0) {
  while($img1=mysql_fetch_assoc($result)) {
    echo '      <tr id="img'.$img1['ClassID'].'-'.$img1['ID'].'">'."\n";
    echo '        <td>'.$img1['ID'].'</td>'."\n";
    if ($img1['Display']=="1") {
      echo '        <td><input type="checkbox" name="Display" checked="checked"></td>'."\n";
    }else{
      echo '        <td><input type="checkbox" name="Display"></td>'."\n";
    }
    echo '        <td><a href="'.htmlspecialchars($img1['Url']).'?t='.rand(10000000,99999999).'" target="mainFrame-滚动图片"><img src="'.htmlspecialchars($img1['Url']).'?t='.rand(10000000,99999999).'" title="点击预览图片"></a></td>'."\n";
    echo '        <td><input type="text" class="form-control" name="Url" value="'.htmlspecialchars($img1['Url']).'"></td>'."\n";
    echo '        <td><input type="text" class="form-control" name="Link" value="'.htmlspecialchars($img1['Link']).'"></td>'."\n";
    echo '        <td>'."\n";
    echo '          <input type="hidden" name="ClassID" value="'.$img1['ClassID'].'">'."\n";
    echo '          <input type="hidden" name="ID" value="'.$img1['ID'].'">'."\n";
    echo '          <button class="btn btn-success btn-small" type="button" name="submit" onclick="save('.$img1['ClassID'].',\'img'.$img1['ClassID'].'-'.$img1['ID'].'\')">保存</button>&nbsp;&nbsp;'."\n";
    echo '          <form name="fileForm" enctype="multipart/form-data" method="POST" action="img.php" style="display:none;">'."\n";
    echo '            <input type="hidden" name="File_ClassID" value="'.$img1['ClassID'].'">'."\n";
    echo '            <input type="hidden" name="File_ID" value="'.$img1['ID'].'">'."\n";
    echo '            <input type="file" name="File" accept="image/jpeg" style="display:none;">'."\n";
    echo '          </form>'."\n";
    echo '          <!--<button class="btn btn-info btn-small" type="button" name="upload" onclick="upload('.$img1['ClassID'].',\'img'.$img1['ClassID'].'-'.$img1['ID'].'\')">上传</button>-->'."\n";
    echo '        </td>'."\n";
    echo '      </tr>'."\n";
  }
}else{
  die('Img1 Not Ready!');
}
?>
    </tbody>
  </table>
  <div class="alert alert-success" id="msg2-success" style="display:none;">保存成功</div>
  <div class="alert alert-danger" id="msg2-error" style="display:none;"></div>
  <table class="table">
    <caption>翻页图片</caption>
    <thead>
      <tr>
        <th>排序</th>
        <th>是否显示</th>
        <th>图片预览</th>
        <th>图片地址</th>
        <th>标题</th>
        <th>超链接</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
<?php
$result=RunDB("select * from img where ClassID=2 order by ID");
if (mysql_num_rows($result)>0) {
  while($img2=mysql_fetch_assoc($result)) {
    echo '      <tr id="img'.$img2['ClassID'].'-'.$img2['ID'].'">'."\n";
    echo '        <td>'.$img2['ID'].'</td>'."\n";
    if ($img2['Display']=="1") {
      echo '        <td><input type="checkbox" name="Display" checked="checked"></td>'."\n";
    }else{
      echo '        <td><input type="checkbox" name="Display"></td>'."\n";
    }
    echo '        <td><a href="'.htmlspecialchars($img2['Url']).'?t='.rand(10000000,99999999).'" target="mainFrame-滚动图片"><img src="'.htmlspecialchars($img2['Url']).'?t='.rand(10000000,99999999).'" title="点击预览图片"></a></td>'."\n";
    echo '        <td><input type="text" class="form-control" name="Url" value="'.htmlspecialchars($img2['Url']).'"></td>'."\n";
    echo '        <td><input type="text" class="form-control" name="Title" value="'.htmlspecialchars($img2['Title']).'"></td>'."\n";
    echo '        <td><input type="text" class="form-control" name="Link" value="'.htmlspecialchars($img2['Link']).'"></td>'."\n";
    echo '        <td>'."\n";
    echo '          <input type="hidden" name="ClassID" value="'.$img2['ClassID'].'">'."\n";
    echo '          <input type="hidden" name="ID" value="'.$img2['ID'].'">'."\n";
    echo '          <button class="btn btn-success btn-small" type="button" name="submit" onclick="save('.$img2['ClassID'].',\'img'.$img2['ClassID'].'-'.$img2['ID'].'\')">保存</button>&nbsp;&nbsp;'."\n";
    echo '          <form name="fileForm" enctype="multipart/form-data" method="POST" action="img.php" style="display:none;">'."\n";
    echo '            <input type="hidden" name="File_ClassID" value="'.$img2['ClassID'].'">'."\n";
    echo '            <input type="hidden" name="File_ID" value="'.$img2['ID'].'">'."\n";
    echo '            <input type="file" name="File" accept="image/jpeg" style="display:none;">'."\n";
    echo '          </form>'."\n";
    echo '          <!--<button class="btn btn-info btn-small" type="button" name="upload" onclick="upload('.$img2['ClassID'].',\'img'.$img2['ClassID'].'-'.$img2['ID'].'\')">上传</button>-->'."\n";
    echo '        </td>'."\n";
    echo '      </tr>'."\n";
  }
}else{
  die('Img2 Not Ready!');
}
?>
    </tbody>
  </table>
  <div class="alert alert-success" id="msg3-success" style="display:none;">保存成功</div>
  <div class="alert alert-danger" id="msg3-error" style="display:none;"></div>
  <table class="table">
    <caption>轮播图片</caption>
    <thead>
      <tr>
        <th>排序</th>
        <th>是否显示</th>
        <th>图片预览</th>
        <th>图片地址</th>
        <th>超链接</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
<?php
$result=RunDB("select * from img where ClassID=3 order by ID");
if (mysql_num_rows($result)>0) {
  while($img3=mysql_fetch_assoc($result)) {
    echo '      <tr id="img'.$img3['ClassID'].'-'.$img3['ID'].'">'."\n";
    echo '        <td>'.$img3['ID'].'</td>'."\n";
    if ($img3['Display']=="1") {
      echo '        <td><input type="checkbox" name="Display" checked="checked"></td>'."\n";
    }else{
      echo '        <td><input type="checkbox" name="Display"></td>'."\n";
    }
    echo '        <td><a href="'.htmlspecialchars($img3['Url']).'?t='.rand(10000000,99999999).'" target="mainFrame-滚动图片"><img src="'.htmlspecialchars($img3['Url']).'?t='.rand(10000000,99999999).'" title="点击预览图片"></a></td>'."\n";
    echo '        <td><input type="text" class="form-control" name="Url" value="'.htmlspecialchars($img3['Url']).'"></td>'."\n";
    echo '        <td><input type="text" class="form-control" name="Link" value="'.htmlspecialchars($img3['Link']).'"></td>'."\n";
    echo '        <td>'."\n";
    echo '          <input type="hidden" name="ClassID" value="'.$img3['ClassID'].'">'."\n";
    echo '          <input type="hidden" name="ID" value="'.$img3['ID'].'">'."\n";
    echo '          <button class="btn btn-success btn-small" type="button" name="submit" onclick="save('.$img3['ClassID'].',\'img'.$img3['ClassID'].'-'.$img3['ID'].'\')">保存</button>&nbsp;&nbsp;'."\n";
    echo '          <form name="fileForm" enctype="multipart/form-data" method="POST" action="img.php" style="display:none;">'."\n";
    echo '            <input type="hidden" name="File_ClassID" value="'.$img3['ClassID'].'">'."\n";
    echo '            <input type="hidden" name="File_ID" value="'.$img3['ID'].'">'."\n";
    echo '            <input type="file" name="File" accept="image/jpeg" style="display:none;">'."\n";
    echo '          </form>'."\n";
    echo '          <!--<button class="btn btn-info btn-small" type="button" name="upload" onclick="upload('.$img3['ClassID'].',\'img'.$img3['ClassID'].'-'.$img3['ID'].'\')">上传</button>-->'."\n";
    echo '        </td>'."\n";
    echo '      </tr>'."\n";
  }
}else{
  die('Img3 Not Ready!');
}
?>
    </tbody>
  </table>
</div>
</body>
<script type="text/javascript">
  String.prototype.trim=function(){
    return this.replace(/(^\s*)|(\s*$)/g, "");
  }

  function save(classid,trid){
    if (Timer_hideMsg) clearTimeout(Timer_hideMsg);
    hideMsg();
    var $submit=$("#"+trid+" button[name='submit']");
    $submit.addClass("disabled");
    $submit.html("保存中...");
    setTimeout(function(){
      //ajax异步提交
      $.ajax({            
          type:"POST",   //post提交方式默认是get
          url:"img.php", 
          data:$("#"+trid+" input").serialize(),   //序列化               
          error:function(request) {      // 设置表单提交出错                 
              $submit.removeClass("disabled");
              $submit.html("保存");
              $("#msg"+classid+"-error").html(request);
              $("#msg"+classid+"-error").show();
          },
          success:function(data) {
              if (data=="Update Success!") {
                $submit.removeClass("disabled");
                $submit.html("保存");
                $("#msg"+classid+"-success").show();
                Timer_hideMsg=setTimeout("hideMsg()",2000);
              }else{
                $submit.removeClass("disabled");
                $submit.html("保存");
                $("#msg"+classid+"-error").html('未知错误：'+data);
                $("#msg"+classid+"-error").show();
              }
          }
      });
    },500);
  }

  var Timer_hideMsg;

  function hideMsg(){
    $("#msg1-success").hide();
    $("#msg1-error").hide();
    $("#msg2-success").hide();
    $("#msg2-error").hide();
    $("#msg3-success").hide();
    $("#msg3-error").hide();
  }

  var uploadClassId="";
  var uploadTrId="";

  function upload(classid,trid){
    uploadClassId=classid;
    uploadTrId=trid;
    $("#"+trid+" input[name='File']").click();
  }

  $("input[name='File']").change(function(e) {
    if ($(this).val()!=""){
      var fileExt=$(this).val().substr($(this).val().lastIndexOf(".")).toLowerCase();
      if (fileExt!=".jpg") {
        alert("请上传后缀名为.jpg的图片");
        $(this).val('');
        return false;
      }
      var $fileForm=$("#"+uploadTrId+" form[name='fileForm']");
      var $upload=$("#"+uploadTrId+" button[name='upload']");
      $upload.addClass("disabled");
      $upload.html("上传中...");
      $fileForm.submit();
    }
  });
</script>
</html>
<?php
CloseDB();
?>