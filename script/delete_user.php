<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #On initialise une variable POST
    $id = $_POST['email'];
    #On vérifie si l'email défini est la même que celle de l'utilisateur
    if(isset($id)){
		#On supprime le membre et ses données
        $requete = "DELETE FROM membre WHERE idMemb = '$id'";
        $cnn->exec($requete) or die(print_r($bdd->errorInfo()));
        $requete1 = "SELECT count(contenuRep) FROM reponse WHERE idMemb = '$id'";
        $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
        while($row1 = $resultat1->fetch()){
            if($row1['count(contenuRep)'] != 0) {
                $requete2 = "DELETE FROM reponse WHERE idMemb = '$id'";
                $cnn->exec($requete2) or die(print_r($bdd->errorInfo()));
            }
            $requete3 = "SELECT count(titreArt) FROM article WHERE idMemb = '$id'";
            $resultat3 = $cnn->query($requete3) or die(print_r($bdd->errorInfo()));
            while($row3 = $resultat3->fetch()){
                if($row3['count(titreArt)'] != 0) {
                    $requete4 = "SELECT idArt FROM article WHERE idMemb = '$id'";
                    $resultat4 = $cnn->query($requete4) or die(print_r($bdd->errorInfo()));
                    while($row4 = $resultat4->fetch()){
                        $requete5 = "SELECT count(contenuRep) FROM reponse WHERE idArt = '{$row4['idArt']}'";
                        $resultat5 = $cnn->query($requete5) or die(print_r($bdd->errorInfo()));
                        while($row5 = $resultat5->fetch()){
                            if($row5['count(contenuRep)'] != 0) {
                                $requete5 = "DELETE FROM reponse WHERE idArt = '{$row4['idArt']}'";
                                $cnn->exec($requete5) or die(print_r($bdd->errorInfo()));
                            }
                        }
                    }
                    $requete6 = "DELETE FROM article WHERE idMemb = '$id'";
                    $cnn->exec($requete6) or die(print_r($bdd->errorInfo()));
                }
            }
        }
    }
    $cnn = null;
    #On redirige l'utilisateur à l'accueil
    header("Location: ../");
?>