<?php
  #On initialise les fichiers nécessaires
  include('../../config/isntlogin.php');
  include('../../config/database.php');
  include('../../config/badges.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forum</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel=icon type=image/png href="../../assets/images/favicon.png"/>
  </head>
  <header>
    <?php
        #On vérifie si il existe bien un identifiant et un mot de passe
        if(isset($_POST['email']) AND isset($_POST['psw'])){
          $requete = "SELECT count(idMemb) FROM membre WHERE idMemb = '{$_POST['email']}'";
          $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
          while($row = $resultat->fetch()){
              if($row['count(idMemb)'] == 0) {
                  $_SESSION['message'] = "Votre email ou mot de passe est incorrect";
                  header('Location: ../../error');
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
                  header('Location: ../../error');
              }
          }
      }

	    #Si l'utilisateur est connecté
        if($_SESSION['email'] != ''){
            $requete = "SELECT pseudoMemb, typeMemb FROM membre WHERE idMemb = '{$_SESSION['email']}'";
            $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
            while($row = $resultat->fetch()){
			          #On affiche la barre du haut du site selon son type
                if($row['typeMemb'] == '0'){
                    echo "<a href='../../'><img class='logo' src='../../assets/images/logo.png'></img></a> </a><p class='username admin'>{$row['pseudoMemb']}</p> <a href='../../script/logout.php' class='button cl2' style='float: right;'>Déconnexion <i class='fas fa-sign-out-alt'></i></a> <a href='../../account' class='button cl1' style='float: right;'>Mon compte <i class='fas fa-user-edit'></i></a> <a href='../../admin' class='button cl3' style='float: right;'>Administration <i class='fas fa-user-shield'></i></a>";
                }
                else if($row['typeMemb'] == '1' OR $row['typeMemb'] == '2'){
                    echo"<a href='../../'><img class='logo' src='../../assets/images/logo.png'></img></a> </a><a class='username user'>{$row['pseudoMemb']}</a> <a href='../../script/logout.php' class='button cl2' style='float: right;'>Déconnexion <i class='fas fa-sign-out-alt'></i></a> <a href='../../account' class='button cl1' style='float: right;'>Mon compte <i class='fas fa-user-edit'></i></a>";
                }
            }
        }
        else {
            echo"<a href='../../'><img class='logo' src='../../assets/images/logo.png'></img></a> </a> <a href='../../register' class='button cl1' style='float: right;'>S'inscrire <i class='fa fa-user-plus' aria-hidden='true'></i></a> <button class='button cl1' style='float: right;' onclick='openForm()'>Se connecter <i class='fa fa-user' aria-hidden='true'></i></button>";
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
    <div class='container'>
    <?php
      if(isset($_GET['rubrique'])) {
        $requete = "SELECT count(idRub) FROM rubrique WHERE idRub = '{$_GET['rubrique']}'";
        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
        while($row = $resultat->fetch()){
          #Si il n'y a pas d'article
          if($row['count(idRub)'] == 0) {
            echo "<center><p>La rubrique n'a pas été trouvée</p></center>";
          }
        }
        $requete = "SELECT nomRub FROM rubrique WHERE idRub = '{$_GET['rubrique']}'";
        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
        while($row = $resultat->fetch()){
          echo "<center><h2>{$row['nomRub']}</h2></center>";
        }
        $requete = "SELECT count(titreArt) FROM article WHERE idRub = '{$_GET['rubrique']}'";
        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
        while($row = $resultat->fetch()){
          #Si il n'y a pas d'article
          if($row['count(titreArt)'] == 0) {
            echo "<center><p>Il n'y a pas d'article à supprimer</p></center>";
          }
        }
        #On affiche les rubriques
        $requete1 = "SELECT idArt, titreArt FROM article WHERE idRub = '{$_GET['rubrique']}'";
        $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
        while($row1 = $resultat1->fetch()){
          $requete2 = "SELECT idMemb FROM article WHERE idArt = '{$row1['idArt']}'";
          $resultat2 = $cnn->query($requete2) or die(print_r($bdd->errorInfo()));
          while($row2 = $resultat2->fetch()){
            echo "<center><a href='../../script/delete_article?article={$row1['idArt']}&amp;rubrique={$_GET['rubrique']}' class='article cl1' style='text-decoration: none; color: white; font-size: 22px;'>Posté par ";
            $requete3 = "SELECT pseudoMemb, typeMemb, certifMemb FROM membre WHERE idMemb = '{$row2['idMemb']}'";
            $resultat3 = $cnn->query($requete3) or die(print_r($bdd->errorInfo()));
            while($row3 = $resultat3->fetch()){
              #On affiche le post selon le type du membre
              if($row3['typeMemb'] == 2) {
                if($row3['certifMemb'] == 1) {
                  echo "{$row3['pseudoMemb']} <i class='$certif' title='Utilisateur vérifié'></i>";
                }
                else {
                  echo "{$row3['pseudoMemb']}";
                }
              }
              else if($row3['typeMemb'] == 1) {
                if($row3['certifMemb'] == 1) {
                  echo "{$row3['pseudoMemb']} <i class='$certif' title='Utilisateur vérifié'></i> <i class='$mod' title='Modérateur'></i>";
                }
                else {
                  echo "{$row3['pseudoMemb']} <i class='$mod' title='Modérateur'></i>";
                }
              }
              else if($row3['typeMemb'] == 0) {
                if($row3['certifMemb'] == 1) {
                  echo "{$row3['pseudoMemb']} <i class='$certif' title='Utilisateur vérifié'></i> <i class='$admin' title='Administrateur'></i>";
                }
                else {
                  echo "{$row3['pseudoMemb']} <i class='$admin' title='Administrateur'></i>";
                }
              }
              echo "<br><br>{$row1['titreArt']}</a><br>";
            }
          }
        }
      }
      else {
        echo"<center><p>Il n'y a pas de rubrique définie</p></center>";
      }
	  echo"<center><a href='../?rubrique={$_GET['rubrique']}' class='button cl1'>Retour à la rubrique</a><br><br></div></center>";
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