<?php
session_start();
require 'ValidateCode.class.php';
$_vc = new ValidateCode();
$_vc->doimg();
$_SESSION['vcode'] = strtolower($_vc->getCode());
?>