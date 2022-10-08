<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #On vérifie si l'utilisateur est connecté
    if($_SESSION['email'] != '' AND !empty($_GET)){
        #On initialise la variable de session
        $id = $_GET['rubrique'];

		#On prend toutes les valeurs de la rubrique (son id, les articles et réponses associé) puis on supprime le tout
        $requete = "select typeMemb from membre where idMemb = '{$_SESSION['email']}'";
        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
        while($row = $resultat->fetch()){
            if($row['typeMemb'] == '0' OR $row['typeMemb'] == '1'){
                $requete1 = "DELETE FROM rubrique WHERE idRub='$id'";
                $cnn->exec($requete1) or die(print_r($bdd->errorInfo()));
                $requete2 = "select idArt from article where idRub = $id";
                $resultat2 = $cnn->query($requete2) or die(print_r($bdd->errorInfo()));
                while($row2 = $resultat2->fetch()){
				    $requete3 = "DELETE FROM reponse WHERE idArt = {$row2['idArt']}";
                    $cnn->exec($requete3);
                }
                $requete4 = "DELETE FROM article WHERE idRub='$id'";
                $cnn->exec($requete4);
			}
            #On redirige l'utilisateur vers la page de suppression de rubrique
			header("Location: ../admin/rubrique/delete");
        }
        $cnn=null;  
    }
    else{
        #On redirige l'utilisateur à l'accueil
        header("Location: ../");
    }
?>