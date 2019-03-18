<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$view = 'articleDelete' ;
$article ;

if(array_key_exists('id', $_GET)){
    $article = fetch('article', $_GET['id']);
}
else $view = 'index' ;

include '../tpl/layout.phtml' ;

?>