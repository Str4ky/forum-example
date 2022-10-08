<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #Si l'utilisateur est connecté et que les variables POST existent
    if($_SESSION['email'] != '' AND !empty($_POST)) {
        #On initialise les variables
        $type = $_POST['type'];
        $id = $_POST['email'];

        #On change le type de l'utilisateur
        $requete = "UPDATE membre SET typeMemb='$type' WHERE idMemb='$id'";
        $cnn->exec($requete) or die(print_r($bdd->errorInfo()));
        header("Location: ../admin");
        $cnn = null;
    }
    #On redirige l'utilisateur à l'accueil
    else {
        echo "<script language='Javascript'>
        document.location.replace('../');
        </script>";
    }
?>
