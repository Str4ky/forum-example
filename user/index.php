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
            if(isset($_GET['user'])) {
                $requete = "SELECT count(pseudoMemb) FROM membre WHERE pseudoMemb = '{$_GET['user']}'";
                $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
                while($row = $resultat->fetch()){
                    if($row['count(pseudoMemb)'] != 1) {
                        echo "<div class='container'><center><p>L'utilisateur spécifié est introuvable</p><a class='button cl1' href='../'>Retourner à l'accueil</a></center><br></div>";
                    }
                        else {
                            #Si l'utilisateur est spécifier
                            if($_GET['user'] != '') {
				                #On prend les infos du membre pour ensuite les afficher
                                $requete1 = "SELECT * FROM membre WHERE pseudoMemb = '{$_GET['user']}'";
                                $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
                                while($row1 = $resultat1->fetch()){
					                #On affiche les informations de l'utilisateur
                                    echo "<center><div class='container'><center><h2>Informations de l'utilisateur</h2></center>";
                                    if($row1['typeMemb'] == 2) {
                                        if($row1['certifMemb'] == 1) {
                                            echo "<i class='fa-solid fa-tag'></i> Pseudo : {$row1['pseudoMemb']} <i class='$certif' title='Utilisateur vérifié'></i>";
                                        }
                                        else {
                                            echo "<i class='fa-solid fa-tag'></i> Pseudo : {$row1['pseudoMemb']}";
                                        }
                                            echo "<br><br><i class='fa-solid fa-user'></i> Type de membre : Utilisateur";
                                    }
                                    else if($row1['typeMemb'] == 1) {
                                        if($row1['certifMemb'] == 1) {
                                            echo "<i class='fa-solid fa-tag'></i> Pseudo : {$row1['pseudoMemb']} <i class='$certif' title='Utilisateur vérifié'></i> <i class='$mod' title='Modérateur'></i>";
                                        }
                                        else {
                                            echo "<i class='fa-solid fa-tag'></i> Pseudo : {$row1['pseudoMemb']} <i class='$mod' title='Modérateur'></i>";
                                        }
                                        echo "<br><br><i class='fa-solid fa-user'></i> Type de membre : Modérateur";
                                    }
                                    else if($row1['typeMemb'] == 0) {
                                        if($row1['certifMemb'] == 1) {
                                            echo "<i class='fa-solid fa-tag'></i> Pseudo : {$row1['pseudoMemb']} <i class='$certif' title='Utilisateur vérifié'></i> <i class='$admin' title='Administrateur'></i>";
                                        }
                                        else {
                                            echo "<i class='fa-solid fa-tag'></i> Pseudo : {$row1['pseudoMemb']} <i class='$admin' title='Administrateur'></i>";
                                        }
                                        echo "<br><br><i class='fa-solid fa-user'></i> Type de membre : Administrateur";
                                    }
                                    echo "<br><br><i class='fa-solid fa-clock'></i> Date d'inscription : {$row1['dateIns']}<br><br></div></center><br><div class='container'><center><h2>Activités sur le forum</h2>";
                                    #On récupère les informations d'activités de l'utilisateur et on les affiches
                                    $requete2= "SELECT count(titreArt) FROM article WHERE idMemb = '{$row1['idMemb']}'";
                                    $resultat2 = $cnn->query($requete2) or die(print_r($bdd->errorInfo()));
                                    while($row2 = $resultat2->fetch()){
                                        echo "<i class='fas fa-envelope'></i> Nombre d'articles postés : {$row2['count(titreArt)']}";
                                    }
                                    $requete3 = "SELECT count(contenuRep) FROM reponse WHERE idMemb = '{$row1['idMemb']}'";
                                    $resultat3 = $cnn->query($requete3) or die(print_r($bdd->errorInfo()));
                                    while($row3 = $resultat3->fetch()){
                                        echo "<br><br><i class='fas fa-align-left'></i> Nombre de réponses postées : {$row3['count(contenuRep)']}<br><br></div></center>";
                                    }
                                }
                            }
                        }
                    }
                }
                else {
                    #Si l'utilisateur n'a pas été spécifié
                    echo "<div class='container'><center><p>Vous n'avez pas spécifié d'utilisateur</p><a class='button cl1' href='../'>Retourner à l'accueil</a></center><br></div>";
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