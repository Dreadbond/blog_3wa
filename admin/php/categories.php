<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$view = 'categories' ;
$headers = ['Titre', 'Parent'];
$categories = [];
$categories = fetchAll('categories');

include '../tpl/layout.phtml' ;

$_SESSION['flashbag'] = NULL ;
?>