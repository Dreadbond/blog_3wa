<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$view = 'userDelete' ;
$user ;

if(array_key_exists('id', $_GET)){
    $user = fetch('user', $_GET['id']);
}
else $view = 'index' ;

include '../tpl/layout.phtml' ;

?>