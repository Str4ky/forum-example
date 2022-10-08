<?php
    #On se connecte à la base de données
    $host = "localhost";
    $bdd = "forum";
    $user = "root";
    $passwd = "";

    #On essaie de se connecter
    try{
        $cnn = new PDO("mysql:host=$host;dbname=$bdd;charset=utf8",$user,$passwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));  
    }
    #Si ça marche pas on affiche une erreur
    catch(PDOException $e){
        echo "Erreur : ".$e->getMessage();
    }
?>