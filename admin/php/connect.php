<?php
include 'utils/config.php';
include 'utils/utils.php';

session_start() ;

if(array_key_exists('email', $_POST)){
    $user = $_POST ;
    $user['password'] = hash('md5' , $user['password']) ;
    
    if (checkUser($user['email'], $user['password'])){
        $fetchedUser = fetch('user', checkUser($user['email'], $user['password']) );

        $_SESSION['connect'] = true ;
        $_SESSION['userId'] = $fetchedUser['id'] ;
        $_SESSION['user'] = $fetchedUser ;
        $_SESSION['flashbag'] = NULL ;
        
    }
    else{
        $_SESSION['flashbag'] = 'Identifiants erronés.' ;
    }
    header('location:index.php') ;
}
?>