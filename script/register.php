<?php
    #On initialise les fichiers nécessaires
    include('../config/isntlogin.php');
    include('../config/database.php');

    #Si les variables POST existent
    if(!empty($_POST)) {
	    #On vérifie si un compte extsie déjà
        if(isset($_POST['email']) AND isset($_POST['password'])){  

            #On initialise les variables en leurs retirant toute compréhension de code
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $pseudo = htmlentities($_POST['nom']);
            $email = htmlentities($_POST['email']);

            #On vérifie si le compte est déjà créé
            $requete = "select idMemb, pseudoMemb from membre";
            $resultat = $cnn->query($requete) or die(print_r($bdd->errorInfo()));
            while($row = $resultat->fetch()){
                if($row['idMemb'] == $email OR $row['pseudoMemb'] == $pseudo){
                    $_SESSION['message'] = "L'email ou le pseudo est déjà utilisé";
                    header("Location: ../error");
                    return;
                }
            }        
	    #Sinon on créer un compte				
            $requete = "INSERT INTO membre (idMemb, pseudoMemb, dateIns, mdpMemb, typeMemb, certifMemb)
            VALUES ('$email', '$pseudo', CURDATE(), '$password', 2, 0)";
            $cnn->exec($requete) or die(print_r($bdd->errorInfo()));
            $cnn=null; 
        }
    }
    #On connecte l'utilisateur automatiquement
    $_SESSION['email'] = $email;
    #On rerigige l'utilisateur à l'accueil
    header("Location: ../");         
?>
