<?php
include '../../Init.php';

session_start();
@$s_login=$_SESSION['login'];
if (!isset($s_login)||$s_login=="") {
  die('Access Denied!');
}

@$p_password=trim($_POST['password']);
if (!isset($p_password)||$p_password=="") {
  die('Invalid Argument!');
}

OpenDB();
$result=RunDB("select * from site");
if (mysql_num_rows($result)>0) {
  $row=mysql_fetch_assoc($result);
  RunDB("update site set Password='".md5($row['Salt'].$p_password)."' where Title='".$row['Title']."'");
  echo "ChangePwd Success!";
}else{
  die('Site Not Ready!');
}
CloseDB();
?>