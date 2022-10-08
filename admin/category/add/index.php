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
    <link rel="stylesheet" href="https://rawcdn.githack.com/hung1001/font-awesome-pro-v6/44659d9/css/all.min.css">
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
    <center><h1>Création d'une nouvelle catégorie</h1></center><br>
    <?php
		  #Si l'utilisateur est connecté
      if($_SESSION['email'] != ''){
        $requete = "SELECT typeMemb FROM membre WHERE idMemb = '{$_SESSION['email']}'";
        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
        while($row = $resultat->fetch()){
				  #Si l'utilisateur est administrateur
          if($row['typeMemb'] == '0'){
            #On affiche le formulaire de création de catégorie
            echo "<div class='text'>
            <center><form method='post' action='../../../script/create_category.php'>
            <label for='nom'>Icône de la catégorie</label><br><br>
            <select name='icon' id='icon' onchange='change_icon(this);'>
            <option value=''>Aucune</option>
            <option value='fa-solid fa-book'>Livre</option>
            <option value='fa-solid fa-book-copy'>Double livre</option>
            <option value='fa-solid fa-book-open-cover'>Livre ouvert</option>
            <option value='fa-solid fa-pen'>Crayon</option>
            <option value='fa-solid fa-file-pen'>Crayon et feuille</option>
            <option value='fa-solid fa-newspaper'>Journal</option>
            <option value='fa-solid fa-scroll'>Parchemin</option>
            <option value='fa-solid fa-comment'>Bulle</option>
            <option value='fa-solid fa-comments'>Double bulle</option>
            <option value='fa-solid fa-computer'>Ordinateur fixe</option>
            <option value='fa-solid fa-laptop'>Ordinateur portable</option>
            <option value='fa-solid fa-headphones'>Casque</option>
            <option value='fa-solid fa-microphone'>Microphone</option>
            <option value='fa-solid fa-camera'>Appareil photo</option>
            <option value='fa-solid fa-video'>Caméra</option>
            <option value='fa-solid fa-compact-disc'>CD</option>
            <option value='fa-solid fa-record-vinyl'>Vinyl</option>
            <option value='fa-solid fa-file'>Fichier</option>
            <option value='fa-solid fa-folder'>Dossier</option>
            <option value='fa-solid fa-image'>Image</option>
            <option value='fa-solid fa-film'>Pellicule</option>
            <option value='fa-solid fa-at'>Arobase</option>
            <option value='fa-solid fa-paperclip'>Trombone</option>
            <option value='fa-solid fa-gamepad-modern'>Manette</option>
            <option value='fa-solid fa-joystick'>Joystick</option>
            <option value='fa-solid fa-phone'>Téléphone</option>
            <option value='fa-solid fa-mobile-screen'>Smartphone</option>
            <option value='fa-solid fa-earth-americas'>Terre</option>  
            <option value='fa-solid fa-house'>Maison</option>
            <option value='fa-solid fa-car'>Voiture</option>
            <option value='fa-solid fa-sailboat'>Bateau</option>
            <option value='fa-solid fa-anchor'>Enclume</option>
            <option value='fa-solid fa-paper-plane'>Avion en papier</option>
            <option value='fa-solid fa-fish'>Poisson</option>
            <option value='fa-solid fa-burger'>Hamburger</option>
            <option value='fa-solid fa-apple-whole'>Pomme</option>
            <option value='fa-solid fa-glass-half'>Verre</option>
            <option value='fa-solid fa-mug-hot'>Tasse</option>
            <option value='fa-solid fa-star'>Étoile</option>
            <option value='fa-solid fa-sun'>Soleil</option>
            <option value='fa-solid fa-moon'>Lune</option>
            <option value='fa-solid fa-cloud'>Nuage</option>
            <option value='fa-solid fa-heart'>Coeur</option>
            <option value='fa-solid fa-square'>Carré</option>
            <option value='fa-solid fa-diamond'>Losange</option>
            <option value='fa-solid fa-rectangle-wide'>Rectangle</option>
            <option value='fa-solid fa-triangle'>Triangle</option>
            <option value='fa-solid fa-circle'>Cercle</option>
            <option value='fa-solid fa-hexagon'>Hexagone</option>
            <option value='fa-solid fa-octagon'>Octogone</option>
            <option value='fa-solid fa-spade'>Piques</option>
            <option value='fa-solid fa-club'>Trèfles</option>
            <option value='fa-solid fa-rhombus'>Carreaux</option>
            <option value='fa-solid fa-shapes'>Formes</option>
            <option value='fa-solid fa-plus'>Plus</option>
            <option value='fa-solid fa-minus'>Moins</option>
            <option value='fa-solid fa-xmark'>Croix</option>
            <option value='fa-solid fa-slash-forward'>Slash</option>
            <option value='fa-solid fa-hashtag'>Hashtag</option>
            <option value='fa-solid fa-list-ol'>Liste</option>
            <option value='fa-solid fa-lock'>Cadenas fermé</option>
            <option value='fa-solid fa-lock-open'>Cadenas ouvert</option>
            <option value='fa-solid fa-tag'>Tag</option>
            <option value='fa-solid fa-bell'>Cloche</option>
            <option value='fa-solid fa-crown'>Couronne</option>
            <option value='fa-solid fa-swords'>Épées</option>
            <option value='fa-solid fa-bullseye-arrow'>Cible</option>
            <option value='fa-solid fa-music-note'>Croche</option>
            <option value='fa-solid fa-music'>Double croche</option>
            <option value='fa-solid fa-user'>Utilisateur</option>
            <option value='fa-solid fa-user-group'>Double utilisateur</option>
            <option value='fa-solid fa-users'>Triple utilisateur</option>
            <option value='fa-solid fa-user-robot'>Robot utilisateur</option>
            <option value='fa-solid fa-robot'>Visage d'un robot</option>
            <option value='fa-solid fa-skull'>Tête de mort</option>
            <option value='fa-solid fa-hand'>Main</option>
            <option value='fa-solid fa-hands'>Applaudir</option>
            <option value='fa-solid fa-handshake-simple'>Poignée de main</option>
            </select><i name='fa' id='fa' class='' style='font-size: 46px; padding-left: 32px; position: relative; top: 10px;'></i><br><br>
            <label for='nom'>Nom de la catégorie</label><br><br>
            <input type='text' name='nom' id='nom' placeholder='Entrez le nom de la catégorie' required/><br>
            <input type='submit' value='Créer une nouvelle catégorie' class='button cl3'/>
            </form></center>
            </div>";
          }
          #Si l'utilisateur n'a pas les permissions
          else {
            echo"<center><p>Vous n'avez pas les permissions pour créer une nouvelle catégorie</p></center>";
          }
        }
      }
      #Si l'utilisateur n'est pas connecté
      else {
        echo"<center><p>Vous n'êtes pas connecté</p></center>";
      }
      echo "<center><a href='../../../' class='button cl1'>Retour à l'accueil</a></center><br></div>";
    ?>
  </body>
</html>

<!--  Le script de récupération des icônes, du bouton de retour en haut de page et de connexion -->
<script>
    function change_icon() {
    value = document.getElementById('icon');
    icon = value.options[value.selectedIndex].value;
    document.getElementById('fa').className = icon;
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