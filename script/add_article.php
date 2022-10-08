<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #Si l'utilisateur est connecté et que les variables POST existent
    if($_SESSION['email'] != '' AND !empty($_POST)) {
        #On initialise les variables en leurs retirant toute compréhension de code
        $nom = htmlentities($_POST['nom']);
        $contenu = htmlentities($_POST['contenu']);

        #On créer l'article
        $requete = "INSERT INTO article (titreArt, contenuArt, idRub, dateArt, idMemb)
        VALUES ('$nom', '$contenu', '{$_GET["rubrique"]}', CURDATE(), '{$_SESSION["email"]}')";
        $cnn->exec($requete) or die(print_r($bdd->errorInfo()));
	    #On ramène l'utilisateur à la page précédente
        header("Location: ../rubrique?rubrique={$_GET['rubrique']}");
        $cnn=null;
    }
    #On ramène l'utilisateur à l'accueil
    else {
        echo "<script language='Javascript'>
        document.location.replace('../');
        </script>";
    }
?>