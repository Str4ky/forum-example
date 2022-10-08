<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #Si l'utilisateur est connecté et que les variables POST & GET existent
    if($_SESSION['email'] != '' AND !empty($_POST) AND !empty($_GET)) {
        #On initialise les variables en leurs retirant toute compréhension de code
        $message = htmlentities($_POST['message']);

        #On envoie une réponse dans l'article
        $requete = "INSERT INTO reponse (contenuRep, dateRep, idArt, idMemb)
        VALUES ('$message', CURDATE(), '{$_GET["article"]}', '{$_SESSION["email"]}')";
        $cnn->exec($requete) or die(print_r($bdd->errorInfo()));
        header("Location: ../article?article={$_GET['article']}&rubrique={$_GET['rubrique']}");
        $cnn=null;
    }
    else {
        header("Location: ../");
    }
?>