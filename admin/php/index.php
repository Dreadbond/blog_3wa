<?php
session_start() ;

// si session : layout et index. Sinon ne pas afficher la barre/footer
if (array_key_exists('connect', $_SESSION) && $_SESSION['connect']){
    $view = 'index' ;

    include '../tpl/layout.phtml' ;
}
else{
    include '../tpl/login.phtml' ;
}
?>