<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$title ;
$action ;
$categories = fetchAll('categories') ;
$users = fetchAll('users');

// On initialise un article vide
$keys = ['id', 'title', 'category', 'picture', 'content', 'author'];
$article = array_fill_keys($keys,'');

if(isset($_GET['id'])){
    $article = fetch('article', $_GET['id']);
    
    $title = 'Edition d\'article : '.$article['title'] ;
    $action = 'Editer';   
}
else{
    $title = 'Nouvel article' ;
    $article['id'] = NULL ;
    $action = 'Ajouter';   
}

$view = 'articleForm' ;
include '../tpl/layout.phtml' ;

?>