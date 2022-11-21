<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #Si l'utilisateur est connecté et que les variables POST existent
    if($_SESSION['email'] != '' AND !empty($_POST)) {
        #On initialise les variables
        $type = $_POST['type'];
        $id = $_POST['email'];

        #On récupère les informations du type
        $requete = "SELECT typeMemb FROM membre WHERE idMemb = '$id'";
        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
        while($row = $resultat->fetch()){
            #Si la certification est la même
            if($row['typeMemb'] != $type) {
                #On change le type de l'utilisateur
                $requete2 = "UPDATE membre SET typeMemb='$type' WHERE idMemb='$id'";
                $cnn->exec($requete2) or die(print_r($bdd->errorInfo()));
                header("Location: ../admin");
                $cnn = null;
            }
            else {
                echo "<script language='Javascript'>
                document.location.replace('../admin');
                </script>";
            }
        }
    }
    #On redirige l'utilisateur à l'accueil
    else {
        echo "<script language='Javascript'>
        document.location.replace('../');
        </script>";
    }
?>
