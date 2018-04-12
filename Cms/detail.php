<?php
include '../Init.php';
OpenDB();

session_start();
@$s_login=$_SESSION['login'];
if (!isset($s_login)||$s_login=="") {
  header('Location: index.php');
  die('Access Denied!');
}

@$p_action=trim($_POST['action']);
@$p_ID=trim($_POST['ID']);

if (isset($p_action)&&$p_action=="delete"&&
  isset($p_ID)&&$p_ID!="") {
  RunDB("delete from detail where ID='".mysql_real_escape_string($p_ID)."'");
  die('Refresh!');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>文章管理</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/flat-ui.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<style>
body {
  background: white;
}
#main {
  width: 90%;
  margin-top: 20px;
  margin-left: 20px;
}
table {
  font-size: 14px;
}
</style>

</head>

<body>
<div id="main">
  <button class="btn btn-success btn-large" type="button" name="add" onclick="add()">添加新文章</button>
  <div style="height:30px;"></div>
  <table class="table" style="table-layout:fixed">
    <thead>
      <tr>
        <th width="5%">排序</th>
        <th width="30%">标题</th>
        <th width="13%">分类</th>
        <th width="13%">作者</th>
        <th width="13%">创建时间</th>
        <th width="13%">最后更新时间</th>
        <th width="13%"></th>
      </tr>
    </thead>
    <tbody>
<?php
@$g_p=trim($_GET['p']);
if (!isset($g_p)||$g_p==""||!is_numeric($g_p)) {
  header('Location: detail.php?p=1');
  die('Redirect To First Page!');
}
$result=RunDB("select ID,ClassID,Author,CreateTime,UpdateTime,Title from detail order by CreateTime desc");
if ($g_p<=0||mysql_num_rows($result)<($g_p-1)*10) $g_p="1";
if (mysql_num_rows($result)>0) {
  $detail=array();
  while ($row=mysql_fetch_assoc($result)) {
    array_push($detail,$row);
  }
  if (mysql_num_rows($result)>=$g_p*10) {
    $lastNum=$g_p*10;
  }else{
    $lastNum=mysql_num_rows($result);
  }
  for ($i=($g_p-1)*10; $i<$lastNum; $i++) {
    echo '      <tr>'."\n";
    echo '        <td>'.(mysql_num_rows($result)-$i).'</td>'."\n";
    echo '        <td style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">'.$detail[$i]['Title'].'</td>'."\n";
    $resultClass=RunDB("select ID,Name from list where ID=".$detail[$i]['ClassID']);
    if (mysql_num_rows($resultClass)>0) {
      $rowClass=mysql_fetch_assoc($resultClass);
      $detail[$i]['ClassID']=$rowClass['Name'];
    }else{
      $detail[$i]['ClassID']="";
    }
    echo '        <td>'.$detail[$i]['ClassID'].'</td>'."\n";
    echo '        <td>'.$detail[$i]['Author'].'</td>'."\n";
    echo '        <td>'.format_date_time_utc($detail[$i]['CreateTime']).'</td>'."\n";
    echo '        <td>'.format_date_time_utc($detail[$i]['UpdateTime']).'</td>'."\n";
    echo '        <td>'."\n";
    echo '          <button class="btn btn-info btn-small" type="button" name="submit" onclick="modify('.$detail[$i]['ID'].')">修改</button>&nbsp;&nbsp;'."\n";
    echo '          <button class="btn btn-danger btn-small" type="button" name="delete" onclick="_delete($(this),'.$detail[$i]['ID'].')">删除</button>'."\n";
    echo '        </td>'."\n";
    echo '      </tr>'."\n";
  }
}
?>
    </tbody>
  </table>
  <div class="text-center">
    <div class="pagination">
      <ul>
<?php
if ($g_p!=1) echo '        <li class="previous"><a href="?p='.($g_p-1).'" class="fui-arrow-left"></a></li>'."\n";
$pbp=0;
for ($pa=($g_p-4);$pa<$g_p;$pa++){
  if ($pa<=0){
    $pbp++;
    continue;
  }
  echo '        <li><a href="?p='.$pa.'">'.$pa.'</a></li>'."\n";
}
echo '        <li class="active"><a href="javascript:void;">'.$g_p.'</a></li>'."\n";
for ($pb=($g_p+1);$pb<=($g_p+$pbp+5);$pb++){
  if ($pb>ceil(mysql_num_rows($result)/10)) break;
  echo '        <li><a href="?p='.$pb.'">'.$pb.'</a></li>'."\n";
}
if ($g_p!=ceil(mysql_num_rows($result)/10)) echo '        <li class="next"><a href="?p='.($g_p+1).'" class="fui-arrow-right"></a></li>'."\n";
?>
      </ul>
    </div>
  </div>
  <div style="height:20px;"></div>
</div>
</body>
<script type="text/javascript">
  function add(){
    document.location.href="detail_editor.php";
  }

  function modify(id){
    document.location.href="detail_editor.php?ID="+id;
  }

  function _delete(obj,id){
    if (!confirm('确实删除？')) return;
    obj.addClass("disabled");
    obj.html("删除中...");
    setTimeout(function(){
      //ajax异步提交
      $.ajax({            
          type:"POST",   //post提交方式默认是get
          url:"detail.php", 
          data:{action: "delete", ID: id},   //序列化               
          error:function(request) {      // 设置表单提交出错                 
              document.location.reload();
          },
          success:function(data) {
              document.location.reload();
          }
      });
    },500);
  }
</script>
</html>
<?php
CloseDB();
?>