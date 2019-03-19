<?php
/** Rapporte tous les champs d'une table
 * @param String le nom de la table
 */
function fetchAll($query){
    $result = [] ;
    $sqlQuery = '' ;
    
    switch($query){
        case 'articles':
            $sqlQuery = 
                'SELECT a_id as id, a_title as title, a_date_published as date, c.c_title as category, u.u_firstname as author, a_picture as picture
                FROM `b_article` a
                INNER JOIN b_category c ON a.a_category = c.c_id 
                INNER JOIN b_user u ON a_author = u.u_id';
        break ;
        
        case 'categories':
            $sqlQuery = 
                'SELECT c_id as id, c_title as title, c_parent as parent
                FROM `b_category`';
        break ;

        case 'users':
            $sqlQuery = '
                SELECT u_id as id, u_firstname as firstname, u_lastname as lastname, u_email as email, u_role as role, COUNT(a.a_author) as posts
                FROM b_user u
                LEFT JOIN b_article a ON a_author = u.u_id
                GROUP BY a.a_author, id';
        break ;
    }

    try{
        $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($sqlQuery);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_BOTH);
    }
    catch(PDOException $e){
        echo 'Une erreur s\'est produite : '.$e->getMessage();
    }

    return $result ;
}

/** Rapporte un résultat d'une table
 * @param String le nom de la table
 * @param String l'ID de la requête
 */
function fetch($query, $id){
    $result = [] ;
    $sqlQuery = '' ;
    
    switch($query){
        case 'article':
            $sqlQuery = '
                SELECT 
                    a_id as id, 
                    a_title as title, 
                    a_date_published as date, 
                    c.c_id as category, 
                    u.u_id as author_id,
                    a_content as content,
                    a_picture as picture
                FROM `b_article` a
                INNER JOIN b_category c ON a.a_category = c.c_id 
                INNER JOIN b_user u ON a_author = u.u_id
                WHERE a_id = :id';
        break ;

        case 'category':
            $sqlQuery = 
                'SELECT c_id as id, c_title as title, c_parent as parent
                FROM `b_category`
                WHERE c_id = :id
                ';
        break ;
        
        case 'user':
            $sqlQuery = '
                SELECT u_id as id, u_firstname as firstname, u_lastname as lastname, u_password as password, u_email as email, u_role as role, COUNT(a.a_author) as posts
                FROM b_user u
                LEFT JOIN b_article a ON a_author = u.u_id
                WHERE u_id = :id
                GROUP BY a.a_author, id';
        break ;
        // case 'user':
        //     $sqlQuery = 
        //         'SELECT u_id as id, u_firstname as firstname, u_lastname as lastname, u_password as password, u_email as email, u_role as role
        //         FROM b_user u
        //         WHERE u_id = :id
        //         ';
        // break ;
    }

    try{
        $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($sqlQuery);
        $sth->bindValue('id',$id,PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e){
        echo 'Une erreur s\'est produite : '.$e->getMessage();
    }

    return $result['0'] ;
}

/** Insère des trucs dans une table
 * @param String nom de la table
 * @param Array tableau associatif des données correspondantes
 */
function addDB($table, $data){
    // todo : faire fonction qui retourne rien si pas d'ID
    try{
        $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        switch($table){
            case 'article':
                $sqlQuery = "INSERT INTO `b_article` (`a_title`, `a_date_published`, `a_content`, `a_category`, `a_author`) VALUES (:title, :date, :content, :category, :author);";
                $sth = $dbh->prepare($sqlQuery);
                $sth->bindValue('title',$data['title'],PDO::PARAM_STR);
                $sth->bindValue('date',$data['datetime']->format('Y-m-d H:i:s'));
                $sth->bindValue('content',$data['content'],PDO::PARAM_STR);
                $sth->bindValue('category',$data['category'],PDO::PARAM_INT);
                $sth->bindValue('author',$data['author'],PDO::PARAM_INT);
            break ;
            
            case 'category':
                    $sqlQuery = 
                    "INSERT INTO `b_category` (`c_id`, `c_title`, `c_parent`) 
                    VALUES (:id, :title, :parent);";
                    $sth = $dbh->prepare($sqlQuery);
                    $sth->bindValue('id',$data['id'],PDO::PARAM_INT);
                    $sth->bindValue('title',$data['title'],PDO::PARAM_STR);
                    $sth->bindValue('parent',$data['parent'],PDO::PARAM_INT);
            break ;
            
            case 'user':
                $sqlQuery = "
                    INSERT INTO `b_user` (`u_firstname`, `u_lastname`, `u_email`, `u_password`, `u_valide`, `u_role`) 
                    VALUES (:firstname, :lastname, :email, :password, :valide, :role)
                ";
                $sth = $dbh->prepare($sqlQuery);
                $sth->bindValue('firstname',$data['firstname'],PDO::PARAM_STR);
                $sth->bindValue('lastname',$data['lastname'],PDO::PARAM_STR);
                $sth->bindValue('email',$data['email'],PDO::PARAM_STR);
                $sth->bindValue('password',$data['password'],PDO::PARAM_STR);
                $sth->bindValue('valide',$data['valide'],PDO::PARAM_INT);
                $sth->bindValue('role',$data['role'],PDO::PARAM_STR);
            break ;
            
        }
        $sth->execute();
    }
    catch(PDOException $e){
        return 'Une erreur s\'est produite : '.$e->getMessage();
    }
}

/** Edite une entrée d'une table
 * @param String le nom de la table
 * @param String l'ID de la requête
 */
function updateDB($table, $data){
    try{
        $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        switch($table){
            case 'article':
                $sqlQuery = 
                "UPDATE `b_article` 
                SET `a_author` = :author, 
                    `a_category` = :category, 
                    `a_content` = :content, 
                    `a_title` = :title,
                    `a_picture`  = :picture
                WHERE `b_article`.`a_id` = :id";
                $sth = $dbh->prepare($sqlQuery);
                $sth->bindValue('author',$data['author'],PDO::PARAM_INT);
                $sth->bindValue('category',$data['category'],PDO::PARAM_INT);
                $sth->bindValue('content',$data['content'],PDO::PARAM_STR);
                $sth->bindValue('id',$data['id'],PDO::PARAM_INT);
                // $sth->bindValue('datetime',$data['datetime']->format('Y-m-d H:i:s'));
                $sth->bindValue('title',$data['title'],PDO::PARAM_STR);
                $sth->bindValue('picture',$data['picture'],PDO::PARAM_STR);
            break ;
                
            case 'category':
                $sqlQuery = 
                "UPDATE `b_category` 
                SET `c_id` = :id,
                    `c_title` = :title, 
                    `c_parent` = :parent
                WHERe `b_category`.`c_id` = :id";
                $sth = $dbh->prepare($sqlQuery);
                $sth->bindValue('id',$data['id'],PDO::PARAM_INT);
                $sth->bindValue('title',$data['title'],PDO::PARAM_STR);
                $sth->bindValue('parent',$data['parent'],PDO::PARAM_INT);
            break ;
                
            case 'user':
                $sqlQuery = 
                "UPDATE `b_user`
                SET `u_firstname` = :firstname, 
                    `u_lastname` = :lastname, 
                    `u_email` = :email, 
                    `u_valide`  = :valide,
                    `u_role`  = :role
                WHERE `u_id` = :id";
                
                $sth = $dbh->prepare($sqlQuery);
                $sth->bindValue('firstname',$data['firstname'],PDO::PARAM_STR);
                $sth->bindValue('lastname',$data['lastname'],PDO::PARAM_STR);
                $sth->bindValue('email',$data['email'],PDO::PARAM_STR);
                // $sth->bindValue('password',$data['password'],PDO::PARAM_STR);
                $sth->bindValue('valide',$data['valide'],PDO::PARAM_INT);
                $sth->bindValue('role',$data['role'],PDO::PARAM_STR);
                $sth->bindValue('id',$data['id'],PDO::PARAM_STR);
            break ;
                
        }
        $sth->execute();
    }
    catch(PDOException $e){
        return 'Erreur dans l\'écriture de la dB : '.$e->getMessage() ;
    }
}

/** Supprime une entrée d'une table
 * @param String le nom de la table
 * @param String l'ID de la requête
 */
function deleteDB($table, $id){
    $sqlQuery;
    try{
        $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        switch($table){
            case 'article':
                $sqlQuery = "DELETE FROM `b_article` WHERE `a_id` = :id";
                $article = fetch('article', $id);
                unlink(UPLOADS_DIR.'articles/'.$article['picture']);
            break ;
            
            case 'category':
                $sqlQuery = "DELETE FROM `b_category` WHERE `c_id` = :id";
            break ;
            
            case 'user':
                $sqlQuery = "DELETE FROM `b_user` WHERE `u_id` = :id";
            break ;
        }
        $sth = $dbh->prepare($sqlQuery);
        $sth->bindValue('id',$id,PDO::PARAM_INT);
        $sth->execute();
    }
    catch(PDOException $e){
        echo 'Une erreur s\'est produite : '.$e->getMessage();
    }
}

/** Retourne vrai si un utilisateur 
 * @param [] tableau username et password hashé
 */
function checkUser($email, $password){
    try{
        $dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sqlQuery = 
            'SELECT u_id as id
            FROM b_user
            WHERE u_email = :email
            AND u_password = :password';
            
        $sth = $dbh->prepare($sqlQuery);
        $sth->bindValue('email', $email,PDO::PARAM_STR);
        $sth->bindValue('password', $password,PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        if (sizeof($result[0])) $result = $result[0]['id'] ;
        else $result = false ;
    }
    catch(PDOException $e){
        echo 'Une erreur s\'est produite : '.$e->getMessage();
        $result = false ;
    }
    return $result ;
}

/** Vérifie si l'user est connecté. Redirige vers index le cas échéant
 * 
 */
function checkConnection(){
    session_start() ;

    if (!array_key_exists('connect', $_SESSION) || !$_SESSION['connect']){
        header('location:index.php'); 
    }
}

?>