<?php
  #On initialise les fichiers nécessaires
  include('../../../config/isntlogin.php');
  include('../../../config/database.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forum</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="https://rawcdn.githack.com/hung1001/font-awesome-pro-v6/44659d9/css/all.css">
    <link rel=icon type=image/png href="../../../assets/images/favicon.png"/>
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
                  header('Location: ../../../error');
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
                  header('Location: ../../../error');
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
              echo "<a href='../../../'><img class='logo' src='../../../assets/images/logo.png'></img></a> </a><p class='username admin'>{$row['pseudoMemb']}</p> <a href='../../../script/logout.php' class='button cl2' style='float: right;'>Déconnexion <i class='fas fa-sign-out-alt'></i></a> <a href='../../../account' class='button cl1' style='float: right;'>Mon compte <i class='fas fa-user-edit'></i></a> <a href='../../' class='button cl3' style='float: right;'>Administration <i class='fas fa-user-shield'></i></a>";
            }
            else if($row['typeMemb'] == '1' OR $row['typeMemb'] == '2'){
              echo"<a href='../../../'><img class='logo' src='../../../assets/images/logo.png'></img></a> </a><a class='username user'>{$row['pseudoMemb']}</a> <a href='../../../script/logout.php' class='button cl2' style='float: right;'>Déconnexion <i class='fas fa-sign-out-alt'></i></a> <a href='../../../account' class='button cl1' style='float: right;'>Mon compte <i class='fas fa-user-edit'></i></a>";
            }
          }
        }
        else {
          echo"<a href='../../../'><img class='logo' src='../../../assets/images/logo.png'></img></a> </a> <a href='../../../register' class='button cl1' style='float: right;'>S'inscrire <i class='fa fa-user-plus' aria-hidden='true'></i></a> <button class='button cl1' style='float: right;' onclick='openForm()'>Se connecter <i class='fa fa-user' aria-hidden='true'></i></button>";
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
    <br>
    <div class="container">
    <center><h1>Modification d'une rubrique</h1></center><br>
    <?php
		  #On vérifie si l'utilisateur est connecté
      if($_SESSION['email'] != ''){
        $requete = "SELECT typeMemb FROM membre WHERE idMemb = '{$_SESSION['email']}'";
        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
        while($row = $resultat->fetch()){
				  #On vérifie si l'utilisateur est administrateur ou non
          if($row['typeMemb'] == '0'){
            $requete1 = "SELECT count(nomCat) FROM categorie";
            $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
            while($row1 = $resultat1->fetch()){
              if($row1['count(nomCat)'] != 0) {
                $requete2 = "SELECT count(nomRub) FROM rubrique";
                $resultat2 = $cnn->query($requete2) or die(print_r($bdd->errorInfo()));
                while($row2 = $resultat2->fetch()){
                  if($row2['count(nomRub)'] != 0) {
                    echo "<div class='text'>
                    <center><form method='post' action='../../../script/edit_rubrique.php'>
                    <label for='nom'>Catégorie</label><br><br>
                    <select name='category' id='category'>";
                    $requete3 = "SELECT idCat, nomCat FROM categorie";
                    $resultat3 = $cnn->query($requete3) or die(print_r($bdd->errorInfo()));
                    while($row3 = $resultat3->fetch()){
                      echo "<option value='".$row3['idCat']."'>".$row3['nomCat']."</option>";
                    }
                    echo "
                    </select><br><br>
                    <label for='nom'>Rubrique</label><br><br>
                    <select name='rubrique' id='rubrique' onchange='change_name(this);'>";
                    $requete4 = "SELECT idRub, nomRub, descRub FROM rubrique";
                    $resultat4 = $cnn->query($requete4) or die(print_r($bdd->errorInfo()));
                    while($row4 = $resultat4->fetch()){
                      echo "<option value='".$row4['idRub']."'>".$row4['nomRub']."</option>";
                    }
                    echo "
                    </select><br><br>
                    <label for='nom'>Nom de la rubrique</label><br><br>
                    <input type='text' name='nom' id='nom' placeholder='Entrez le nom de la rubrique' required/><br><br>
                    <label for='nom'>Description de la rubrique</label><br><br>
                    <input type='text' name='desc' id='desc' placeholder='Entrez la description de la rubrique' required/><br>
                    <input type='submit' value='Modifier la rubrique' class='button cl3'/>
                    </form></center>
                    </div>";
                  }
                  #Si il n'y a pas de rubrique
                  else {
                  echo"<center><p>Il n'y a pas de rubrique à modifier</p></center>";
                  }
                }
              }
              #Si il n'y a pas de catégorie
              else {
              echo"<center><p>Il n'y a pas de catégorie</p></center>";
              }
            }
          }
          else {
            #Si l'utilisateur n'a pas les permissions
            echo"<center><p>Vous n'avez pas les permissions pour créer une nouvelle catégorie</p></center>";
          }
        }
      }
      else {
        #Si l'utilisateur n'est pas connecté
        echo"<center><p>Vous n'êtes pas connecté</p></center>";
      }
      echo "<center><a href='../../../' class='button cl1'>Retour à l'accueil</a></center><br></div>";
    ?>
  </body>
</html>

<!--  Le script de récupération du nom de la rubrique, du bouton de retour en haut de page et de connexion -->
<script>
  function change_name() {
    option = document.getElementById('rubrique');
    name = option.options[option.selectedIndex].text;
    document.getElementById('nom').value = name;
  }

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