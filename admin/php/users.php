<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$view = 'users' ;
$users = [];
$users = fetchAll('users');

include '../tpl/layout.phtml' ;

$_SESSION['flashbag'] = NULL ;
?>