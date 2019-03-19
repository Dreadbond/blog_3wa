<?php

// remove all session variables
$_SESSION['connect'] = false ;
$_SESSION['userId'] = NULL ;
$_SESSION['user'] = NULL ;
$_SESSION['flashbag'] = NULL ;

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

header('location:index.php');
?>