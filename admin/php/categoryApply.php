<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$error = [] ;

if(array_key_exists('title', $_POST)){
    $category = $_POST ;
    
    /************************** Titre *************************************/
    if (!strlen($category['title'])) array_push($error, 'Vous devez rentrer un titre.') ;
    
    /************************** Parent *************************************/
    //  Si le parent est renseigné mais n'est pas un numérique
    if (!ctype_digit($category['parent'])) {
        array_push($error, 'Parent category is not numeric.') ;
        $category['parent'] = NULL ;
    }

    // Si id est renseigné : c'est un ancien category : on update
    if (!sizeof($error)){
        // Si id est renseigné : c'est un ancien article : on update
        if($category['id'] != NULL){
            $isUpdated = updateDB('category', $category);
            if ($isUpdated) array_push($error, $isUpdated) ;
        }
        else{
            $category = addDB('category', $category);
            if ($isAdded) array_push($isAdded, $isUpdated) ;
        }
    }
}
else if (array_key_exists('delete', $_POST)){
    deleteDB('category', $_POST['delete']);
}
else array_push($error, 'Y\' a une erreur ...') ;

$_SESSION['flashbag'] = $error ;

header('Location: categories.php');
?>