<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #Si l'utilisateur est connecté et que les variables POST existent
    if($_SESSION['email'] != '' AND !empty($_POST)) {
        #On initialise les variables en leurs retirant toute compréhension de code
        $name = htmlentities($_POST['nom']);
        $desc = htmlentities($_POST['description']);
        $id = $_POST['id'];

        #On créer la rubrique
        $requete = "INSERT INTO rubrique (nomRub, descRub, idCat)
        VALUES ('$name', '$desc', '$id')";
        $cnn->exec($requete) or die(print_r($bdd->errorInfo()));
        $cnn=null;  
    }
    #On redirige l'utilisateur à l'accueil
    header("Location: ../");
?>