<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #Si l'utilisateur est connecté et que les variables POST existent
    if($_SESSION['email'] != '' AND !empty($_POST)) {
        #On initialise les variables en leurs retirant toute compréhension de code
        $id = $_SESSION['email'];
        $pseudo = htmlentities($_POST['nom']);
        $password = password_hash($_POST['psw'], PASSWORD_DEFAULT);
        $mail = htmlentities($_POST['email']);

        #On modifie les informations de l'utilisateur
        $requete = "UPDATE membre SET pseudoMemb='$pseudo', mdpMemb='$password', idMemb='$mail' WHERE idMemb = '$id'";
        $cnn->exec($requete) or die(print_r($cnn->errorInfo()));
        $cnn = null;

        #On redirige l'utilisateur vers la page de son compte
        header("Location: ../account");
    }
    else {
        #On redirige l'utilisateur à l'accueil
        header("Location: ../");
    }
?>