<?php
require 'config.php';
$_SESSION = [];
session_unset();
session_destroy();
unset($_COOKIE['user']);
setcookie('user', '', time() - 3600, '/');
?>