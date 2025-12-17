<?php

function controleurPrincipal(string $action): string {
    switch ($action) {
        case 'accueil':
            return 'HomeController.php';

        case 'hotels':          // liste des hotels
        case 'creerhotel':      // POST création d'hotel
        case 'formhotel':       // afficher le formulaire
        case 'deletehotel':     // supprimer un hotel
        case 'formupdate':      // afficher le formulaire d’édition
        case 'updatehotel':     // POST de mise à jour d'hotel
        case 'attribuerHotel':
        case 'choisirCongressiste':
        case 'unassignHotel':
            return 'HotelController.php';
        
        case 'login':              // form de connexion
        case 'dologin':            // POST de connexion
        case 'logout':             // déconnexion
            return 'AuthController.php';
        
        default:
            return 'HomeController.php';
    }
}