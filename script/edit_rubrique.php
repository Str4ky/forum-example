<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #Si l'utilisateur est connecté et que les variables POST existent
    if($_SESSION['email'] != '' AND !empty($_POST)) {
        #On initialise les variables en leurs retirant toute compréhension de code
        $id = $_POST['category'];
        $id2 = $_POST['rubrique'];
        $name = htmlentities($_POST['nom']);
        $desc = htmlentities($_POST['desc']);
        
        #On modifie la rubrique
        $requete = "UPDATE rubrique SET idCat='$id', nomRub='$name', descRub='$desc' WHERE idRub = '$id2'";
        $cnn->exec($requete) or die(print_r($cnn->errorInfo()));
        $cnn=null;
    }
    #On redirige l'utilisateur à l'accueil
    header("Location: ../");
?>