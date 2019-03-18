<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$title ;
$action ;
$categories = fetchAll('categories') ;

// On initialise une categorie vide
$keys = ['id', 'title', 'parent'];
$category = array_fill_keys($keys,'');

if(isset($_GET['id'])){
    $category = fetch('category', $_GET['id']);

    $title = 'Edition de catégorie : '.$category['title'] ;
    $action = 'Editer';
}
else{
    $title = 'Nouvelle catégorie' ;
    $category['id'] = NULL ;
    $action = 'Ajouter';   
}

$view = 'categoryForm' ;
include '../tpl/layout.phtml' ;

?>