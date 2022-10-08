<?php
    #On initialise la session
    session_start();

    #Si l'utilisateur n'est pas conecté
    if (!isset($_SESSION['email'])) {
        #On initialise une variable de session
        $_SESSION['email'] = '';
    }
?>