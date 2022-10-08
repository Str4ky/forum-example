<?php
  #On initialise les fichiers nécessaires
  include('../config/isntlogin.php');
  include('../config/database.php');
	
  #On vérifie si le membre est connecté et que les variables GET existent
  if($_SESSION['email'] != '' AND !empty($_GET)){
    #On initialise la variable de session
    $id = $_GET['article'];

		#On récupère les informations de l'article et on supprime tout
    $requete1 = "SELECT typeMemb FROM membre WHERE idMemb = '{$_SESSION['email']}'";
    $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
    while($row1 = $resultat1->fetch()){
      if($row1['typeMemb'] == '0' OR $row1['typeMemb'] == '1'){
        $requete = "DELETE FROM reponse WHERE idArt = '$id'";
        $cnn->exec($requete);
        $requete1 = "DELETE FROM article WHERE idArt = '$id'";
        $cnn->exec($requete1) or die(print_r($bdd->errorInfo()));
        header("Location: ../rubrique/delete?rubrique=$id");
      }
    }
    $cnn=null;  
  }
  else{
		#On redirige l'utilisateur à l'accueil
    header("Location: ../");
  }
?>