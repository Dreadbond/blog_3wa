<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$error = [] ;

if(array_key_exists('firstname', $_POST)){
    $oldUser = fetch('user', $_POST['id']);
    $user = $_POST ;
    
    /************************** Prénom/pseudo *************************************/
    if(!isset($user['firstname'])) $error = 'Vous devez au moins saisir un pseudo' ;
    
    /************************** Nom *************************************/
    
    /************************ email    *************************************/
    if(!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) array_push($error, 'Email incorrect') ;

    /************************ password    *************************************/
    if($oldUser['password'] != hash('md5' , $user['password'])) array_push($error, 'Mot de passe erroné') ;
    
    /************************ valide    *************************************/
    $user['valide']= 1;

    /************************ role *************************************/
    if ($user['role'] != 'ADMIN' && $user['role'] != 'AUTHOR') array_push($error, 'Role incorrect') ;

    // Si id est renseigné : c'est un ancien user : on update
    if(!sizeof($error) && strlen($user['id']) ){
        updateDB('user', $user);
    }
    else if(!sizeof($error) && !strlen($user['id'])){
        addDB('user', $user);
    }
}
else if (array_key_exists('delete', $_POST)){
    deleteDB('user', $_POST['delete']);
}

$_SESSION['flashbag'] = $error ;

header('Location: users.php');
?>