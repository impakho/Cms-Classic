<?php
include '../../Init.php';

@$p_password=trim($_POST['password']);
@$p_vcode=strtolower(trim($_POST['vcode']));
if (!isset($p_password)||$p_password==""||
  !isset($p_vcode)||$p_vcode=="") {
  die('Invalid Argument!');
}

session_start();
@$s_vcode=$_SESSION['vcode'];
if (!isset($s_vcode)||$s_vcode==""||$s_vcode!=$p_vcode) {
  die('VCode Error!');
}

OpenDB();
$result=RunDB("select * from site");
if (mysql_num_rows($result)>0) {
  $row=mysql_fetch_assoc($result);
  if (strtolower(md5($row['Salt'].$p_password))==strtolower($row['Password'])) {
    $_SESSION['login']="1";
    echo "Login Success!";
  }else{
    echo "Password Error!";
  }
}else{
  die('Site Not Ready!');
}
CloseDB();
?>