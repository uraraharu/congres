<?php


require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../modele/md.congressiste.php';

$action = $_GET['action'] ?? 'login';
$pdo = (new Database())->getConnexion();
$mCong = new ModeleCongressiste($pdo);

switch ($action) {

    case 'login':
        require_once __DIR__ . '/../vue/layout/header.php';
        require __DIR__ . '/../vue/auth/login.php';
        break;

    case 'dologin':
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            header('Location: ?action=login&status=login_error');
            exit;
        }

        $user = $mCong->findByEmailAndPassword($email, $password);
        if ($user) {
            $_SESSION['congressiste'] = $user; // id_congressiste, nom, prenom, email, id_hotel
            header('Location: ?action=hotels&status=login_ok');
        } else {
            header('Location: ?action=login&status=login_error');
        }
        exit;

    case 'logout':
        unset($_SESSION['congressiste']);
        header('Location: ?action=accueil&status=logout_ok');
        exit;

    default:
        header('Location: ?action=login');
        exit;
}
?>