<?php
  #On initialise les fichiers nécessaires
  include('config/isntlogin.php');
  include('config/database.php');
?>
<!DOCTYPE html>
<html>
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Forum</title>
      <link rel="stylesheet" href="assets/css/style.css">
      <link rel="stylesheet" href="https://rawcdn.githack.com/hung1001/font-awesome-pro-v6/44659d9/css/all.min.css">
      <link rel=icon type=image/png href="assets/images/favicon.png"/>
  </head>

  <?php
    #On vérifie si il existe bien un identifiant et un mot de passe
    if(isset($_POST['email']) AND isset($_POST['psw'])){
      $requete = "SELECT count(idMemb) FROM membre WHERE idMemb = '{$_POST['email']}'";
      $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
      while($row = $resultat->fetch()){
        if($row['count(idMemb)'] == 0) {
          $_SESSION['message'] = "Votre email ou mot de passe est incorrect";
          header('Location: error');
        }
      }
      $requete1 = "SELECT idMemb, mdpMemb FROM membre WHERE idMemb = '{$_POST['email']}'";
        $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
          while($row1 = $resultat1->fetch()){
            #On vérifie si le mot de passe, ou on lui a retiré toute compréhension de code est égal à celui de la base de données
            if (password_verify(htmlentities($_POST['psw']), $row1['mdpMemb'])) {
              $_SESSION['email'] = $_POST['email'];
            }
            else {
			        #La c'est non donc on redirige l'utilisateur sur une page d'erreur
              $_SESSION['message'] = "Votre email ou mot de passe est incorrect";
              header('Location: error');
            }
          }
      }    
  ?>

  <header>
    <?php
	    #Si quelqu'un est connecté
        if($_SESSION['email'] != ''){
          $requete = "SELECT pseudoMemb, typeMemb FROM membre WHERE idMemb = '{$_SESSION['email']}'";
          $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
          while($row = $resultat->fetch()){
					  #On affiche la barre du haut du site selon son type
            if($row['typeMemb'] == '0'){
              echo "<a href='./'><img class='logo' src='assets/images/logo.png'></img></a> </a><p class='username admin'>{$row['pseudoMemb']}</p> <a href='script/logout.php' class='button cl2' style='float: right;'>Déconnexion <i class='fas fa-sign-out-alt'></i></a> <a href='account' class='button cl1' style='float: right;'>Mon compte <i class='fas fa-user-edit'></i></a> <a href='admin' class='button cl3' style='float: right;'>Administration <i class='fas fa-user-shield'></i></a>";
            }
            else if($row['typeMemb'] == '1' OR $row['typeMemb'] == '2'){
              echo"<a href='./'><img class='logo' src='assets/images/logo.png'></img></a> </a><a class='username user'>{$row['pseudoMemb']}</a> <a href='script/logout.php' class='button cl2' style='float: right;'>Déconnexion <i class='fas fa-sign-out-alt'></i></a> <a href='account' class='button cl1' style='float: right;'>Mon compte <i class='fas fa-user-edit'></i></a>";
            }
          }
        }
        else {
          echo"<a href='./'><img class='logo' src='assets/images/logo.png'></img></a> </a> <a href='register' class='button cl1' style='float: right;'>S'inscrire <i class='fa fa-user-plus' aria-hidden='true' style='float: right;'></i></a> <button class='button cl1' style='float: right;' onclick='openForm()'>Se connecter <i class='fa fa-user' aria-hidden='true'></i></button>";
        }
    ?>
  </header>
  <br><br>
  <body>
	  <!--  La pop up de connexion -->
    <div class="form-popup" id="myForm">
      <form action="./" class="form-container" method="POST">
        <a class="btn_cancel" onclick="closeForm()"><i class="fas fa-times"></i></a>
          <h2>Se connecter</h2>

          <label for="email"><b>Adresse email</b></label>
          <input type="email" placeholder="Entrer une adresse email" name="email" id="email" required>

          <label for="psw"><b>Mot de passe</b></label>
          <input type="password" placeholder="Entrer un mot de passe" name="psw" id="psw" required>

          <button type="submit" class="btn">Se connecter</button>
      </form>
    </div>
    <!--  Le bouton de retour en haut de page -->
    <button onclick="topFunction()" id="myBtn" title="Retourner en haut de page"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
    
    <?php
      #Si le membre est connecté
      if($_SESSION['email'] != ''){
        $requete = "SELECT typeMemb FROM membre WHERE idMemb = '{$_SESSION['email']}'";
        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
        while($row = $resultat->fetch()){
          #On affiche le bouton de création de catégories seulement si le membre est admin
          if($row['typeMemb'] == '0'){
            echo "<center><a href='admin/category/add' class='buttonBig cl3' style='text-decoration: none; text-align: center;'>Créer une nouvelle catégorie <i class='fa fa-plus' aria-hidden='true'></i></a>";
            $requete1 = "SELECT count(nomCat) FROM categorie";
            $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
            while($row1 = $resultat1->fetch()){
              #On affiche le bouton de modification et de suppression de rubriques seulement si au moins une catégorie est créée
              if($row1['count(nomCat)'] != 0) {
                echo "<a href='admin/category/edit' class='buttonBig cl3' style='text-decoration: none; text-align: center;'>Modifier une catégorie <i class='fa fa-pen' aria-hidden='true'></i></a><a href='admin/category/delete' class='buttonBig cl2' style='text-decoration: none; text-align: center;'>Supprimer une catégorie <i class='fas fa-trash'></i></a>";
              }
            }
            echo "</center><br>";
          }
        }
      }

      #On affiche les catégories et leurs rubriques
      $requete = "SELECT iconCat, nomCat, idCat FROM categorie";
      $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
      while($row = $resultat->fetch()){
        if($row['iconCat'] == "") {
          echo"<center><div class='category'>{$row['nomCat']}</div></center><br>";
        }
        else {
          echo"<center><div class='category'><i class='{$row['iconCat']}'></i> {$row['nomCat']}</div></center><br>";
        } 
        $requete1 = "SELECT nomRub, idRub, descRub FROM rubrique WHERE idCat={$row['idCat']}";
        $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
        while($row1 = $resultat1->fetch()){
          echo"<center><u><a href='rubrique?rubrique={$row1['idRub']}' style='text-decoration: none; color: white;'><p class='rubrique cl1'>{$row1['nomRub']}</u><br><br>{$row1['descRub']}</p></a></center><br>";
        }
      }
  
      #Si le membre est connecté
      if($_SESSION['email'] != ''){
        $requete = "SELECT typeMemb FROM membre WHERE idMemb = '{$_SESSION['email']}'";
        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
        while($row = $resultat->fetch()){
          $requete1 = "SELECT count(nomCat) FROM categorie";
          $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
          while($row1 = $resultat1->fetch()){
            #On affiche le bouton de création de rubriques seulement si le membre est admin et qu'au moins une catégorie est créée
            if($row['typeMemb'] == '0' AND $row1['count(nomCat)'] != 0){
              echo "<center><a href='admin/rubrique/add' class='buttonBig cl3' style='text-decoration: none; text-align: center;'>Créer une nouvelle rubrique <i class='fa fa-plus' aria-hidden='true'></i></a>";
              $requete2 = "SELECT count(nomRub) FROM rubrique";
              $resultat2 = $cnn->query($requete2) or die(print_r($bdd->errorInfo()));
              while($row2 = $resultat2->fetch()){
                #On affiche le bouton de modification et de suppression de rubriques seulement si au moins une rubrique est créée
                if($row2['count(nomRub)'] != 0) {
                  echo "<a href='admin/rubrique/edit' class='buttonBig cl3' style='text-decoration: none; text-align: center;'>Modifier une rubrique <i class='fa fa-pen' aria-hidden='true'></i></a><a href='admin/rubrique/delete' class='buttonBig cl2' style='text-decoration: none; text-align: center;'>Supprimer une rubrique <i class='fas fa-trash'></i></a>";
                }
              }
              echo "</center><br>";
            }
          }
        }
      }

	    #On affiche les statistiques du forum
      $requete = "SELECT count(nomCat) FROM categorie";
      $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
      while($row = $resultat->fetch()){
        $requete1 = "SELECT count(nomRub) FROM rubrique";
        $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
        while($row1 = $resultat1->fetch()){
			    $requete2 = "SELECT count(titreArt) FROM article";
          $resultat2 = $cnn->query($requete2) or die(print_r($bdd->errorInfo()));
          while($row2 = $resultat2->fetch()){
			      $requete3 = "SELECT count(contenuRep) FROM reponse";
            $resultat3 = $cnn->query($requete3) or die(print_r($bdd->errorInfo()));
            while($row3 = $resultat3->fetch()){
              echo"<center><div class='category'><i class='fas fa-chart-bar'></i> Statistiques du forum</div><div class='rubrique cl1' style='font-size: 24px; pointer-events: none;'><i class='fas fa-folder-open'></i> Nombre de catégories : {$row['count(nomCat)']}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='fas fa-comment-alt'></i> Nombre de rubriques : {$row1['count(nomRub)']}<br><br><i class='fas fa-envelope'></i> Nombre d'articles : {$row2['count(titreArt)']}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='fas fa-align-left'></i> Nombre de réponses : {$row3['count(contenuRep)']}</div></center><br>";
            }
          }
		    }
	    }
    ?>
  </body>
</html>

<!--  Le script du bouton de retour en haut de page et de connexion -->
<script>
    var mybutton = document.getElementById("myBtn");
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            mybutton.style.display = "block";
        }
        else {
        mybutton.style.display = "none";
        }
    }

    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

    function openForm() {
        document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
        document.getElementById("myForm").style.display = "none";
    }
</script>