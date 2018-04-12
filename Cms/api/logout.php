<?php
session_start();
unset($_SESSION['login']);
echo "Logout Success!";
?>