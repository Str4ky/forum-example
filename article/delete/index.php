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
        <br>
        <div class='container'>
        <center><h1>Suppression d'un message</h1></center><br>
            <?php
                #On vérifie si l'utilisateur est connecté
                if($_SESSION['email'] != ''){
                    if(isset($_GET['article']) AND isset($_GET['rubrique'])) {
                        $requete = "SELECT count(idArt) FROM article WHERE idArt = '{$_GET['article']}'";
                        $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
                        while($row = $resultat->fetch()){
                            #Si il n'y a pas d'article
                            if($row['count(idArt)'] == 0) {
                                echo "<center><p>Il n'y a pas d'article disponible</p><a href='../../' class='button cl1'>Retour à l'accueil</a><br><br></div></center>";
                            }
                            $requete1 = "SELECT count(contenuRep) FROM reponse WHERE idArt = '{$_GET['article']}'";
                            $resultat1 = $cnn->query($requete1) or die(print_r($bdd->errorInfo()));
                            while($row1 = $resultat1->fetch()){
                                #Si il n'y a pas de réponses
                                if($row1['count(contenuRep)'] == 0) {
                                    echo "<center><p>Il n'y a pas de réponses</p></center>";
                                }
                            }
                            $requete2 = "SELECT idRub FROM article WHERE idArt = '{$_GET['article']}'";
                            $resultat2 = $cnn->query($requete2) or die(print_r($bdd->errorInfo()));
                            while($row2 = $resultat2->fetch()){
                                #Si la rubrique associée à l'article n'est pas la bonne
                                if($row2['idRub'] != $_GET['rubrique']) {
                                    echo "<center><p>La rubrique n'est pas celle associé à l'article actuel</p>";
                                }
                                else {
	                                #On sélectionne les informations de l'articles
                                    $requete3 = "SELECT idRep, contenuRep, dateRep, idMemb FROM reponse WHERE idArt = '{$_GET['article']}'";
                                    $resultat3 = $cnn->query($requete3) or die(print_r($bdd->errorInfo()));
                                    while($row3 = $resultat3->fetch()){
                                        $requete4 = "SELECT pseudoMemb, typeMemb, certifMemb FROM membre WHERE idMemb = '{$row3['idMemb']}'";
                                            $resultat4 = $cnn->query($requete4) or die(print_r($bdd->errorInfo()));
                                            while($row4 = $resultat4->fetch()){
                                                $requete5 = "SELECT typeMemb FROM membre WHERE idMemb = '{$_SESSION['email']}'";
                                                $resultat5 = $cnn->query($requete4) or die(print_r($bdd->errorInfo()));
                                                while($row5 = $resultat5->fetch()){
								                    #On vérifie le type de membre pour en afficher ses options
                                                    if($row5['typeMemb'] == '0' OR $row5['typeMemb'] == '1'){
                                                        echo"<center><a href='../../script/delete_message.php?reponse={$row3['idRep']}&amp;article={$_GET['article']}&amp;rubrique={$_GET['rubrique']}' class='article cl1'>Posté par  ";
                                                        if($row4['typeMemb'] == 2) {
                                                            if($row4['certifMemb'] == 1) {
                                                                echo "{$row4['pseudoMemb']} <i class='$certif' title='Utilisateur vérifié'></i>";
                                                            }
                                                            else {
                                                                echo "{$row4['pseudoMemb']}";
                                                            }
                                                        }
                                                        else if($row4['typeMemb'] == 1) {
                                                            if($row4['certifMemb'] == 1) {
                                                                echo "{$row4['pseudoMemb']} <i class='$certif' title='Utilisateur vérifié'></i> <i class='$mod' title='Modérateur'></i>";
                                                            }
                                                            else {
                                                                echo "{$row4['pseudoMemb']} <i class='$mod' title='Modérateur'></i>";
                                                            }
                                                        }
                                                        else if($row4['typeMemb'] == 0) {
                                                            if($row4['certifMemb'] == 1) {
                                                                echo "{$row4['pseudoMemb']} <i class='$certif' title='Utilisateur vérifié'></i> <i class='$admin' title='Administrateur'></i>";
                                                            }
                                                            else {
                                                            echo "{$row4['pseudoMemb']} <i class='$admin' title='Administrateur'></i>";
                                                            }
                                                        } 
                                                    echo " le {$row3['dateRep']}<br><br>{$row3['contenuRep']}</a></center>";
                                                }
                                                if($row5['typeMemb'] == '2'){
                                                    echo"<center><h1>Vous n'êtes pas autorisé à supprimer un message</h1></center><br>";
                                                }
                                            }
                                        }
                                    }
                                    #On affiche un bouton de retour en arrière
                                    echo"<center><a href='../?article={$_GET['article']}&amp;rubrique={$_GET['rubrique']}' class='button cl1'>Retour</a><br><br></div></center>";
                                }
                            }
                        }
                    }
                    else {
                        #Si aucun article ou aucune rubrique n'a été définie
                        echo "<center><p>Il n'y a pas d'article ou de rubrique de définie</p><a href='../../' class='button cl1'>Retour à l'accueil</a><br><br></div></center>";
                    }
                }
                else{
					#On indique qu'il n'est pas connecté
                    echo"<center><h1>Vous n'êtes pas connecté</h1></center><br>";
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