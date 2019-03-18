<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$view = 'categoryDelete' ;
$category ;

if(array_key_exists('id', $_GET)){
    $category = fetch('category', $_GET['id']);
}
else $view = 'index' ;

include '../tpl/layout.phtml' ;

?>