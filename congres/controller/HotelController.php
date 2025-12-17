<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classe/Hotel.php';
require_once __DIR__ . '/../modele/md.hotel.php';
require_once __DIR__ . '/../modele/md.congressiste.php';

// Router secondaire pour les actions Hôtel
$action = $_GET['action'] ?? 'hotels';

$database = new Database();
$pdo = $database->getConnexion();
$model = new ModeleHotel($pdo);
$mCong = new ModeleCongressiste($pdo);

switch ($action) {

    // GET /?action=hotels
    case 'hotels':
        // Récupérer les filtres (null si absent, false si invalide)
        $etoile   = filter_input(INPUT_GET, 'etoile', FILTER_VALIDATE_INT);
        $chambres = filter_input(INPUT_GET, 'chambres', FILTER_VALIDATE_INT);

        // Normaliser : si invalide => null (pour ne pas filtrer)
        if ($etoile === false)   { $etoile = null; }
        if ($chambres === false) { $chambres = null; }

        // passer les filtres au modèle
        $hotels = $model->findAll($etoile, $chambres);
        require_once __DIR__ . '/../vue/layout/header.php';
        require __DIR__ . '/../vue/hotel/list.php';

        break;

    // GET /?action=formhotel
    case 'formhotel':
        require_once __DIR__ . '/../vue/layout/header.php';
        require __DIR__ . '/../vue/hotel/form_create.php';

        break;
    case 'formupdate':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) { 
            header('Location: ?action=hotels&status=error'); 
            exit; 
        }

        $hotel = $model->findById((int)$id);
        if (!$hotel) { 
            header('Location: ?action=hotels&status=notfound'); 
            exit; 
        }

        require_once __DIR__ . '/../vue/layout/header.php';
        require __DIR__ . '/../vue/hotel/form_update.php';
    break;

    
    case 'creerhotel':
        
        //$id          = filter_input(INPUT_POST, 'id_hotel', FILTER_VALIDATE_INT);
        $nom         = filter_input(INPUT_POST, 'nom_hotel', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $adresse     = filter_input(INPUT_POST, 'adresse_hotel', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prix        = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
        $prixSupp    = filter_input(INPUT_POST, 'prix_supplement_petit_dejeuner', FILTER_VALIDATE_FLOAT);
        $etoile      = filter_input(INPUT_POST, 'etoile', FILTER_VALIDATE_INT);
        $chambreDisp = filter_input(INPUT_POST, 'chambre_disponible', FILTER_VALIDATE_INT);

        // Validation
        /*
        $erreurs = [];
        foreach ([
            'nom' => $nom, 'adresse' => $adresse, 'prix' => $prix,
            'prix_supplement' => $prixSupp, 'etoile' => $etoile, 'chambre_disponible' => $chambreDisp
        ] as $k => $v) {
            if ($v === false || $v === null || $v === '') $erreurs[] = "Champ invalide: $k";
        }
        */

        if (!empty($erreurs)) {
            // Affichage des erreurs
            $flash_error = implode('<br>', array_map('htmlspecialchars', $erreurs));
            require_once __DIR__ . '/../vue/layout/header.php';
            require __DIR__ . '/../vue/hotel/form_create.php';
            exit;
        }
        $id = null;

        
        $hotel = new Hotel(
            (int)$id,
            (string)$nom,
            (string)$adresse,
            (float)$prix,
            (float)$prixSupp,
            (int)$etoile,
            (int)$chambreDisp
        );

        $ok = $model->createHotel($hotel);

        
        header('Location: ?action=hotels' . ($ok ? '&status=created' : '&status=error'));
        exit;

    case 'updatehotel':

        $id          = filter_input(INPUT_POST, 'id_hotel', FILTER_VALIDATE_INT);
        $nom         = filter_input(INPUT_POST, 'nom_hotel', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $adresse     = filter_input(INPUT_POST, 'adresse_hotel', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prix        = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
        $prixSupp    = filter_input(INPUT_POST, 'prix_supplement_petit_dejeuner', FILTER_VALIDATE_FLOAT);
        $etoile      = filter_input(INPUT_POST, 'etoile', FILTER_VALIDATE_INT);
        $chambreDisp = filter_input(INPUT_POST, 'chambre_disponible', FILTER_VALIDATE_INT);
        // Validation
        $erreurs = [];
        foreach ([
            'id' => $id, 'nom' => $nom, 'adresse' => $adresse, 'prix' => $prix,
            'prix_supplement' => $prixSupp, 'etoile' => $etoile, 'chambre_disponible' => $chambreDisp
        ] as $k => $v) {
            if ($v === false || $v === null || $v === '') $erreurs[] = "Champ invalide: $k";
        }
        if (!empty($erreurs)) {
            $flash_error = implode('<br>', array_map('htmlspecialchars', $erreurs));
            
            $hotel = [
            'id_hotel' => (int)$id,
            'nom_hotel' => (string)($nom ?? ''),
            'adresse_hotel' => (string)($adresse ?? ''),
            'prix' => (float)($prix ?? 0),
            'prix_supplement_petit_dejeuner' => (float)($prixSupp ?? 0),
            'etoile' => (int)($etoile ?? 0),
            'chambre_disponible' => (int)($chambreDisp ?? 0),
            ];
            require_once __DIR__ . '/../vue/layout/header.php';
            require __DIR__ . '/../vue/hotel/form_update.php';

            exit;
        }

        $hotel = new Hotel(
            (int)$id, 
            (string)$nom,
            (string)$adresse,
            (float)$prix,
            (float)$prixSupp,
            (int)$etoile,
            (int)$chambreDisp
        );
        $ok = $model->updateHotel($hotel);

        header('Location: ?action=hotels&status=' . ($ok ? 'updated' : 'error'));
        exit;
        
    case 'deletehotel':
        $id = filter_input(INPUT_POST, 'id_hotel', FILTER_VALIDATE_INT);
        if ($id === false || $id === null) {
            header('Location: ?action=hotels&status=error');
            exit;
        }
        $ok = $model->deleteHotel((int)$id);
        header('Location: ?action=hotels&status=' . ($ok ? 'deleted' : 'error'));
        exit;
    case 'choisirCongressiste': // GET
        $idHotel = filter_input(INPUT_GET, 'id_hotel', FILTER_VALIDATE_INT);
        if (!$idHotel) {
            header('Location: ?action=hotels&status=assign_error');
            exit;
        }
        $congressistes           = $mCong->getAllCongressiste();      // tous
        $assignedCongressistes   = $mCong->getAssignedCongressistes(); // déjà attribués
        $status = $_GET['status'] ?? null;
        require_once __DIR__ . '/../vue/layout/header.php';
        require __DIR__ . '/../vue/congressiste_list.php';
    exit;

    case 'attribuerHotel': // POST
            $idHotel = filter_input(INPUT_POST, 'id_hotel', FILTER_VALIDATE_INT);
        $idCong  = filter_input(INPUT_POST, 'id_congressiste', FILTER_VALIDATE_INT);

        if (!$idHotel || !$idCong) {
            header('Location: ?action=hotels&status=assign_error');
            exit;
        }

        $ok = $mCong->setHotelCongressiste($idCong, $idHotel);

        if ($ok) {
            header('Location: ?action=hotels&status=assigned');
        } else {
            // ➜ revenir sur l'écran de choix avec un message d'erreur demandé
            header('Location: ?action=choisirCongressiste&id_hotel='.$idHotel.'&status=assign_already');
        }
    exit;
    case 'unassignHotel': // POST
        $idCong  = filter_input(INPUT_POST, 'id_congressiste', FILTER_VALIDATE_INT);
        $idHotel = filter_input(INPUT_POST, 'id_hotel', FILTER_VALIDATE_INT); // pour revenir sur la même page
        if (!$idCong) {
            header('Location: ?action=hotels&status=error');
            exit;
        }
        $ok = $mCong->unsetHotelCongressiste($idCong);
        if ($ok) {
            header('Location: ?action=choisirCongressiste&id_hotel='.((int)$idHotel).'&status=unassigned_ok');
        } else {
            header('Location: ?action=choisirCongressiste&id_hotel='.((int)$idHotel).'&status=unassigned_err');
        }
    exit;

    default:
        header('Location: ?action=hotels');
        exit;
}
