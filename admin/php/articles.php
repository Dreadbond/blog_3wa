<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$view = 'articles' ;
$articles = fetchAll('articles');

include '../tpl/layout.phtml' ;

$_SESSION['flashbag'] = NULL ;
?>