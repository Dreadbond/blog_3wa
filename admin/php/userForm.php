<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$title ;
$action ;
$users = fetchAll('users');

// On initialise un user vide
$keys = ['id', 'firstname', 'lastname', 'email', 'role', 'valide'];
$user = array_fill_keys($keys,'');

if(isset($_GET['id'])){
    $user = fetch('user', $_GET['id']);

    $title = 'Edition d\'utilisateur : '.$user['firstname'] ;
    $action = 'Editer';   
}
else{
    $title = 'Nouvel user' ;
    $user['id'] = NULL ;
    $action = 'Ajouter';   
}

$view = 'userForm' ;
include '../tpl/layout.phtml' ;

?>