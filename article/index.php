<?php
  #On initialise les fichiers nécessaires
  include('../config/isntlogin.php');
  include('../config/database.php');
  include('../config/badges.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forum</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
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
            echo "<a href='../'><img class='logo' src='../assets/images/logo.png'></img></a> </a><p class='username admin'>{$row['pseudoMemb']}</p> <a href='../script/logout.php' class='button cl2' style='float: right;'>Déconnexion <i class='fas fa-sign-out-alt'></i></a> <a href='../account' class='button cl1' style='float: right;'>Mon compte <i class='fas fa-user-edit'></i></a> <a href='../admin' class='button cl3' style='float: right;'>Administration <i class='fas fa-user-shield'></i></a>";
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
      <?php
      echo "<form action='./?article={$_GET['article']}&amp;rubrique={$_GET['rubrique']}' class='form-container' method='POST'>";
      ?>
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
      if(isset($_GET['article']) AND isset($_GET['rubrique'])) {
        $requete = "SELECT count(idArt) FROM article WHERE idArt = '{$_GET['article']}'";
        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
        while($row = $resultat->fetch()){
          #Si il n'y a pas d'article
          if($row['count(idArt)'] == 0) {
            echo "<center><div class='container'><p>L'article n'a pas été trouvé</p><a href='../rubrique?rubrique={$_GET['rubrique']}' class='button cl1'>Retour</a><br><br></div></center>";
          }
          $requete1 = "SELECT idRub FROM article WHERE idArt = '{$_GET['article']}'";
          $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
          while($row1 = $resultat1->fetch()){
            #Si la rubrique associée à l'article n'est pas la bonne
            if($row1['idRub'] != $_GET['rubrique']) {
              echo "<center><div class='container'><p>La rubrique n'est pas celle associé à l'article actuel</p>";
            }
            else {
              $requete2 = "SELECT count(idRub) FROM article WHERE idRub = '{$_GET['rubrique']}'";
              $resultat2 = $cnn->query($requete2) or die(print_r($bdd->errorInfo()));
              while($row2 = $resultat2->fetch()){
                #Si il n'y a pas de rubrique
                if($row2['count(idRub)'] == 0) {
                  echo "<center><div class='container'><p>Il n'y a pas de rubrique disponible</p>";
                }
              }
              #On récupère les informations de l'article ainsi que ses réponses et on les affiche
              $requete3 = "SELECT idArt, titreArt, contenuArt, dateArt, idMemb FROM article WHERE idArt = '{$_GET['article']}'";
              $resultat3 = $cnn->query($requete3) or die(print_r($bdd->errorInfo()));
              while($row3 = $resultat3->fetch()){
                echo "<center><div class='container'><h2>{$row3['titreArt']}</h2>";
                $requete4 = "SELECT pseudoMemb, typeMemb, certifMemb FROM membre WHERE idMemb = '{$row3['idMemb']}'";
                $resultat4 = $cnn->query($requete4) or die(print_r($bdd->errorInfo()));
                while($row4 = $resultat4->fetch()){
                  #On affiche le post selon le type du membre
                  echo"<center><p class='article'>Posté par ";
                  if($row4['typeMemb'] == 2) {
                    if($row4['certifMemb'] == 1) {
                      echo "<a href='../user?user={$row4['pseudoMemb']}' style='color: white;'>{$row4['pseudoMemb']}</a> <i class='$certif' title='Utilisateur vérifié'></i>";
                    }
                    else {
                      echo "<a href='../user?user={$row4['pseudoMemb']}' style='color: white;'>{$row4['pseudoMemb']}</a>";
                    }
                  }
                  else if($row4['typeMemb'] == 1) {
                    if($row4['certifMemb'] == 1) {
                      echo "<a href='../user?user={$row4['pseudoMemb']}' style='color: white;'>{$row4['pseudoMemb']}</a> <i class='$certif' title='Utilisateur vérifié'></i> <i class='$mod' title='Modérateur'></i>";
                    }
                    else {
                      echo "<a href='../user?user={$row4['pseudoMemb']}' style='color: white;'>{$row4['pseudoMemb']}</a> <i class='$mod' title='Modérateur'></i>";
                    }
                  }
                  else if($row4['typeMemb'] == 0) {
                    if($row4['certifMemb'] == 1) {
                      echo "<a href='../user?user={$row4['pseudoMemb']}' style='color: white;'>{$row4['pseudoMemb']}</a> <i class='$certif' title='Utilisateur vérifié'></i> <i class='$admin' title='Administrateur'></i>";
                    }
                    else {
                      echo "<a href='../user?user={$row4['pseudoMemb']}' style='color: white;'>{$row4['pseudoMemb']}</a> <i class='$admin' title='Administrateur'></i>";
                    }
                  }
                  echo " le {$row3['dateArt']}<br><br>{$row3['contenuArt']}</p></center><br>";
                  $requete5 = "SELECT contenuRep, dateRep, idMemb FROM reponse WHERE idArt = '{$_GET['article']}'";
                  $resultat5 = $cnn->query($requete5) or die(print_r($bdd->errorInfo()));
                  while($row5 = $resultat5->fetch()){
                    $requete6 = "SELECT pseudoMemb, idMemb, typeMemb, certifMemb FROM membre WHERE idMemb = '{$row5['idMemb']}'";
                    $resultat6 = $cnn->query($requete6) or die(print_r($bdd->errorInfo()));
                    while($row6 = $resultat6->fetch()){
                      #On affiche la réponse selon le type du membre
                      echo"<center><p class='article'>Posté par ";
                      if($row6['typeMemb'] == 2) {
                        if($row6['certifMemb'] == 1) {
                          echo "<a href='../user?user={$row6['pseudoMemb']}' style='color: white;'>{$row6['pseudoMemb']}</a> <i class='$certif' title='Utilisateur vérifié'></i>";
                        }
                        else {
                          echo "<a href='../user?user={$row6['pseudoMemb']}' style='color: white;'>{$row6['pseudoMemb']}</a>";
                        }
                      }
                      else if($row6['typeMemb'] == 1) {
                        if($row6['certifMemb'] == 1) {
                          echo "<a href='../user?user={$row6['pseudoMemb']}' style='color: white;'>{$row6['pseudoMemb']}</a> <i class='$certif' title='Utilisateur vérifié'></i> <i class='$mod' title='Modérateur'></i>";
                        }
                        else {
                          echo "<a href='../user?user={$row6['pseudoMemb']}' style='color: white;'>{$row6['pseudoMemb']}</a> <i class='$mod' title='Modérateur'></i>";
                        }
                      }
                      else if($row6['typeMemb'] == 0) {
                        if($row6['certifMemb'] == 1) {
                          echo "<a href='../user?user={$row6['pseudoMemb']}' style='color: white;'>{$row6['pseudoMemb']}</a> <i class='$certif' title='Utilisateur vérifié'></i> <i class='$admin' title='Administrateur'></i>";
                        }
                        else {
                          echo "<a href='../user?user={$row6['pseudoMemb']}' style='color: white;'>{$row6['pseudoMemb']}</a> <i class='$admin' title='Administrateur'></i>";
                        }
                      }
                      echo " le {$row5['dateRep']}<br><br>{$row5['contenuRep']}</p></center><br><p class='post'></p>";
                    }
                  }
                }
              }
              #On vérifie si le membre est connecté
              if($_SESSION['email'] != ''){
				        #Si oui on affiche la possibilité d'envoyer un message
                echo "<div class='text'>
                <center><form method='post' action='../script/send_message.php?article={$_GET['article']}&amp;rubrique={$_GET['rubrique']}'>
                <input type='text' name='message' id='message' placeholder='Entrez votre message' required/>
                <input type='submit' value='Envoyer' class='button cl1' style='margin-left: 12px; id='message'/>
                </form>";
              }
              else {
                echo "<a href='../register' style='color: white; text-decoration: none;'>Envie de poster une réponse ?</a>";
              }
              $requete7 = "SELECT typeMemb FROM membre WHERE idMemb = '{$_SESSION['email']}'";
              $resultat7 = $cnn->query($requete7) or die(print_r($bdd->errorInfo()));
              while($row7 = $resultat7->fetch()){
				        #On vérifie le type de membre pour en afficher des options de modération/d'administration
                if($row7['typeMemb'] == '0' OR $row7['typeMemb'] == '1'){
                  $requete8 = "SELECT count(contenuRep) FROM reponse WHERE idArt = '{$_GET['article']}'";
                  $resultat8 = $cnn->query($requete8) or die(print_r($bdd->errorInfo()));
                  while($row8 = $resultat8->fetch()){
                    if($row8['count(contenuRep)'] != 0) {
                      echo"<a href='delete?article={$_GET['article']}&amp;rubrique={$_GET['rubrique']}' class='button cl2' style='text-decoration: none; text-align: center;'>Supprimer un message <i class='fas fa-trash'></i></a></center></div>";
                    }
                  }
                }
              }
            }
            #On affiche un bouton de retour en arrière
            echo"<center><a href='../rubrique?rubrique={$_GET['rubrique']}' class='button cl1'>Retour</a></center><br></div>";
          }
        }
      }
      else {
        #Si aucun article ou aucune rubrique n'a été définie
        echo "<center><div class='container'><p>Il n'y a pas d'article ou de rubrique de définie</p><a href='../' class='button cl1'>Retour à l'accueil</a><br><br></div></center>";
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