<?php
session_start();

$racine = __DIR__;
require_once "$racine/controller/mainController.php";

// Détermine l'action demandée
$action = $_GET['action'] ?? 'accueil';

// Résout le fichier contrôleur à inclure
$fichierControleur = controleurPrincipal($action);
require_once "$racine/controller/$fichierControleur";