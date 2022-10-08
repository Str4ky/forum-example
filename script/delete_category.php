<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #On vérifie si l'utilisateur est connecté
    if($_SESSION['email'] != '' AND !empty($_GET)){
        #On initialise la variable de session
        $id = $_GET['category'];
        
		#On prend toutes les valeurs de la catégorie (son id, les articles et réponses associé) puis on supprime le tout
        $requete = "SELECT typeMemb FROM membre WHERE idMemb = '{$_SESSION['email']}'";
        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
        while($row = $resultat->fetch()){
            if($row['typeMemb'] == '0' OR $row['typeMemb'] == '1'){
                $requete1 = "DELETE FROM categorie WHERE idCat = $id";
                $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
                $requete2 = "SELECT idRub FROM rubrique WHERE idCat=$id";
                $resultat2 = $cnn->query($requete2) or die(print_r($bdd->errorInfo()));
                while($row2 = $resultat2->fetch()){
                    $requete3 = "DELETE FROM rubrique WHERE idRub = {$row2['idRub']}";
                    $cnn->exec($requete3) or die(print_r($bdd->errorInfo()));
                    $requete4 = "SELECT idArt FROM article WHERE idRub = {$row2['idRub']}";
                    $resultat4 = $cnn->query($requete4) or die(print_r($bdd->errorInfo()));
                        while($row4 = $resultat4->fetch()){
				            $requete5 = "DELETE FROM reponse WHERE idArt = {$row4['idArt']}";
                            $cnn->exec($requete5);
                        }
                    $requete6 = "DELETE FROM article WHERE idRub = {$row2['idRub']}";
                    $cnn->exec($requete6);
				}
                #On redirige l'utilisateur à la page de suppression de catégorie
			    header("Location: ../admin/category/delete");
            }
        $cnn=null; 
        }
    }
    #On redirige l'utilisateur à l'accueil
    else{
        header("Location: ../");
    }
?>