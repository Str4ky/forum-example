<?php
  #On initialise les fichiers nécessaires
  include('../config/isntlogin.php');
  include('../config/database.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forum</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="https://rawcdn.githack.com/hung1001/font-awesome-pro-v6/44659d9/css/all.min.css">
        <link rel=icon type=image/png href="../assets/images/favicon.png"/>
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
                  header('Location: ../error');
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
                  header('Location: ../error');
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
            echo "<a href='../'><img class='logo' src='../assets/images/logo.png'></img></a> </a><p class='username admin'>{$row['pseudoMemb']}</p> <a href='../script/logout.php' class='button cl2' style='float: right;'>Déconnexion <i class='fas fa-sign-out-alt'></i></a> <a href='../account' class='button cl1' style='float: right;'>Mon compte <i class='fas fa-user-edit'></i></a> <a href='./' class='button cl3' style='float: right;'>Administration <i class='fas fa-user-shield'></i></a>";
          }
          else if($row['typeMemb'] == '1' OR $row['typeMemb'] == '2'){
            echo"<a href='../'><img class='logo' src='../assets/images/logo.png'></img></a> </a><a class='username user'>{$row['pseudoMemb']}</a> <a href='../script/logout.php' class='button cl2' style='float: right;'>Déconnexion <i class='fas fa-sign-out-alt'></i></a> <a href='../account' class='button cl1' style='float: right;'>Mon compte <i class='fas fa-user-edit'></i></a>";
          }
        }
      }
      else {
        echo"<a href='../'><img class='logo' src='../assets/images/logo.png'></img></a> </a> <a href='../register' class='button cl1' style='float: right;'>S'inscrire <i class='fa fa-user-plus' aria-hidden='true'></i></a> <button class='button cl1' style='float: right;' onclick='openForm()'>Se connecter <i class='fa fa-user' aria-hidden='true'></i></button>";
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
    <center><div class="container">
    <h2>Administration</h2>
    <br>
      <?php
        #Si le membre est connecté
        if($_SESSION['email'] != ''){
          $requete= "SELECT count(pseudoMemb) FROM membre WHERE idMemb <> '{$_SESSION['email']}'";
          $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
          while($row = $resultat->fetch()){
            if($row['count(pseudoMemb)'] == 0) {
              echo "<p>Il n'y a pas d'utilisateur à administrer</p><a class='button cl1' href='../'>Retourner à l'accueil</a><br><br></div></center>";
            }
            else {
	      #On récupère les informations du membre
              $requete1 = "SELECT typeMemb FROM membre WHERE idMemb = '{$_SESSION['email']}'";
              $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
              while($row1 = $resultat1->fetch()){
		#Si l'utilisateur est administrateur
                if($row1['typeMemb'] == '0'){
                  echo "
                  <p>Modifier les permissions d'un utilisateur :</p><br>
                  <form action='../script/change_type.php' method='POST'>
                  <select name='email' id='email'>";
                  $requete2 = "SELECT pseudoMemb, idMemb FROM membre WHERE idMemb <> '{$_SESSION['email']}'";
                  $resultat2 = $cnn->query($requete2) or die(print_r($bdd->errorInfo()));
                  while($row2 = $resultat2->fetch()){
                    echo "<option value='{$row2['idMemb']}'>{$row2['pseudoMemb']}</option>";
                  }
                  echo "</select>
                  <select name='type' id='type'>
                  <option value='2'>Utilisateur</option>
                  <option value='1'>Modérateur</option>
                  <option value='0'>Administrateur</option>
                  </select><br>
                  <button class='button cl3' type='submit'>Modifier les permissions</button></form><br><br>
                  <p>Modifier la certification d'un utilisateur :</p><br>
                  <form action='../script/change_certification.php' method='POST'>
                  <select name='email' id='email'>";
                  $requete3 = "SELECT pseudoMemb, idMemb FROM membre WHERE idMemb <> '{$_SESSION['email']}'";
                  $resultat3 = $cnn->query($requete3) or die(print_r($bdd->errorInfo()));
                  while($row3 = $resultat3->fetch()){
                    echo "<option value='{$row3['idMemb']}'>{$row3['pseudoMemb']}</option>";
                  }
                  echo "</select>
                  <select name='certif' id='certif'>
                  <option value='1'>Certifier</option>
                  <option value='0'>Ne pas certifier</option>
                  </select><br>
                  <button class='button cl3' type='submit'>Modifier la certification</button></form><br><br>
                  <p>Supprimer le compte d'un utilisateur :</p><br>
                  <form action='../script/delete_user.php' method='POST'>
                  <select name='email' id='email'>";
                  $requete4 = "SELECT pseudoMemb, idMemb FROM membre WHERE idMemb <> '{$_SESSION['email']}'";
                  $resultat4 = $cnn->query($requete4) or die(print_r($bdd->errorInfo()));
                  while($row4 = $resultat4->fetch()){
                    echo "<option value='{$row4['idMemb']}'>{$row4['pseudoMemb']}</option>";
                  }
                  echo "</select><br>
                  <button class='button cl2' type='submit'>Supprimer le compte</button></form><br></div></div>";
                  echo "<br></div></center>";
                }
                #Si l'utilisateur n'est pas administrateur
                else if($row1['typeMemb'] == '1' OR $row1['typeMemb'] == '2'){   
                  echo "<div class='container'><center>Vous n'avez pas accès aux outils d'administration</center><center><a class='button cl1' href='../'>Retourner à l'accueil</a></center></div>";
                }
              }             
            }
          }
        }
        #Si l'utilisateur n'est pas connecté
        else {
          echo"<div class='container'><center>Vous n'êtes pas connecté</center><br><center><a class='button cl1' href='../'>Retourner à l'accueil</a></center></div>";
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
