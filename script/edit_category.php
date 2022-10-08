<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #Si l'utilisateur est connecté et que les variables POST existent
    if($_SESSION['email'] != '' AND !empty($_POST)) {
        #On initialise les variables en leurs retirant toute compréhension de code
        $id = $_POST['category'];
        $icon = htmlentities($_POST['icon']);
        $name = htmlentities($_POST['nom']);

        #On modifie la catégorie
        $requete = "UPDATE categorie SET nomCat='$name', iconCat='$icon' WHERE idCat = '$id'";
        $cnn->exec($requete) or die(print_r($cnn->errorInfo()));
        $cnn = null;
    }
    #On redirige l'utilisateur à l'accueil
    header("Location: ../");
?>