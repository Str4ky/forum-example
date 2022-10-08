<?php
  #On initialise les fichiers nécessaires
  include('../config/isntlogin.php');
  include('../config/database.php');

  #On vérifie si le membre est connecté
  if($_SESSION['email'] != '' AND !empty($_GET)){
    $requete1 = "select typeMemb from membre where idMemb = '{$_SESSION['email']}'";
    $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
    while($row1 = $resultat1->fetch()){
			#On vérifie les permissions de l'utilisateur pour supprimer un message
      if($row1['typeMemb'] == '0' OR $row1['typeMemb'] == '1'){
        $requete = "DELETE FROM reponse WHERE idRep='{$_GET['reponse']}'";
        $cnn->exec($requete) or die(print_r($bdd->errorInfo()));
        #On redirige l'utilisateur à la page de l'article
        header("Location: ../article/delete?article={$_GET['article']}&rubrique={$_GET['rubrique']}");
      }
    }
    $cnn=null;  
  }
  else{
		#On redirige l'utilisateur à l'accueil
    header("Location: ../");
  }
?>