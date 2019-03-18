<?php
include 'utils/config.php';
include 'utils/utils.php';
checkConnection();

$error = [] ;

if(array_key_exists('title', $_POST)){
    $article = $_POST ;
    $oldArticle = fetch('article', $article['id']);
    
    /************************** Titre *************************************/
    
    /************************** Date *************************************/
    $date = $article['date'];
    $time = $article['time'];
    $article['datetime'] = new DateTime($date.' '.$time);
    
    /************************ User/author *************************************/
    // Si l'utilisateur n'est pas sélectionné, on met l'utilisateur actuel. TODO : revoir ça quand il y aura des comptes
    if (strlen($article['author'] == 0)) $article['author'] = '1';
    
    /*************************** Image *************************************/
    // Si la checkbox s'est affichée et est cochée : on supprime
    if (array_key_exists('delete_picture', $article) && $article['delete_picture']){
        // On ne récupère volontairement pas l'erreur, car dans le cas d'une image avec lien brisé, l'update dans la BDD ne se fait pas.
        unlink(UPLOADS_DIR.'articles/'.$oldArticle['picture']);

        $article['picture'] = NULL ;
    }
    // s'une nouvelle photo a été uploadée, mais que la checkbox n'est pas cochée
    else if ($_FILES["picture"]["error"] == UPLOAD_ERR_OK) {
        // s'il y a une ancienne photo : la supprimer
        if ($oldArticle['picture']){
        // On ne récupère volontairement pas l'erreur, car dans le cas d'une image avec lien brisé, l'update dans la BDD ne se fait pas.
            unlink(UPLOADS_DIR.'articles/'.$oldArticle['picture']);
        }

        $tmp_name = $_FILES["picture"]["tmp_name"];
        // basename() peut empêcher les attaques de système de fichiers;
        // la validation/assainissement supplémentaire du nom de fichier peut être approprié
        $pictureArticle = uniqid().'-'.basename($_FILES["picture"]["name"]);
        $article['picture'] = $pictureArticle ;
        // https://stackoverflow.com/questions/20165461/getting-error-with-move-uploaded-file-http-wrapper-does-not-support-writeable-co/23425010
        move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'].'/php/blog/img/uploads/articles/'.$pictureArticle);
    }
    // Si ni supprimé, ni changé, on laisse l'image d'origine
    else {
        $article['picture'] = $oldArticle['picture'] ;
    }
        
    // Si tout est bien formaté, pas d'erreur : on applique
    if (!sizeof($error)){
        // Si id est renseigné : c'est un ancien article : on update
        if($article['id'] != NULL){
            $isUpdated = updateDB('article', $article);
            if ($isUpdated) array_push($error, $isUpdated) ;
        }
        else{
            $isAdded = addDB('article', $article);
            if ($isAdded) array_push($isAdded, $isUpdated) ;
        }
    }
}
else if (array_key_exists('delete', $_POST)){
    deleteDB('article', $_POST['delete']);
}

$_SESSION['flashbag'] = $error ;

header('Location: articles.php')
?>