# Fiche Technique - Gestion Congrès

> **Date** : 17/12/2025  
> **Version** : 1.0  
> **Projet** : Application de gestion d'hôtels et de congressistes

---

## 1. Présentation du Projet
L'application **Gestion Congrès** est une solution web permettant de gérer l'hébergement des participants à un congrès. Elle offre des fonctionnalités pour l'administration des hôtels (création, modification, suppression) et l'affectation des chambres aux congressistes, en respectant des règles de gestion strictes (disponibilité, facturation).

## 2. Stack Technique
*   **Langage Backend** : PHP 8.x
*   **Base de Données** : MySQL
*   **Architecture** : MVC (Modèle-Vue-Contrôleur) "maison"
*   **Interface Serveur** : Apache (via WAMP/XAMPP)
*   **Frontend** : HTML5, CSS3, JavaScript (Vanilla)
*   **Accès Données** : PDO avec requêtes préparées

## 3. Architecture Logicielle

### Structure des Répertoires
Le projet suit une structure MVC claire :
*   `classe/` : Classes objets (DTO), ex: `Hotel.php`.
*   `config/` : Configuration de la base de données (`Database.php`).
*   `controller/` : Logique de traitement (`HotelController`, `AuthController`, `mainController`).
*   `modele/` : Accès aux données (DAO), ex: `md.hotel.php`.
*   `vue/` : Vues HTML (Templates et pages).
*   `index.php` : Point d'entrée unique (Front Controller).

### Système de Routage
Le fichier `index.php` délègue la requête au `mainController.php`, qui agit comme un routeur ("Dispatcher"). Il sélectionne le contrôleur spécifique (`HotelController`, `AuthController`, `HomeController`) en fonction du paramètre GET `action`.

**Exemples de routes :**
*   `Index.php?action=hotels` : Liste des hôtels -> `HotelController`.
*   `Index.php?action=login` : Connexion -> `AuthController`.

## 4. Modèle de Données (Base de Données)

L'application repose sur une base MySQL relationnelle nommée `congres`.

### Tables Principales
1.  **`hotel`**
    *   `id_hotel` (PK): Identifiant unique.
    *   `nom_hotel`, `adresse_hotel`: Informations générales.
    *   `prix`, `prix_supplement_petit_dejeuner`: Tarification.
    *   `etoile`: Catégorie (Stars).
    *   `chambre_disponible`: Stock de chambres.

2.  **`congressiste`**
    *   `id_congressiste` (PK): Identifiant unique.
    *   `nom`, `prenom`, `email`: Identité.
    *   `password`: Hashé pour l'authentification.
    *   `id_hotel` (FK): Clé étrangère vers `hotel` (peut être NULL).
    *   `acompte`: Booléen indiquant si l'acompte est versé.

3.  **`facture`** *( Table de contrainte )*
    *   Utilisée pour bloquer la ré-attribution si une facture est émise pour un congressiste.

### Règles d'Intégrité
*   Relation `1,1` entre Congressiste et Hôtel (un seul hôtel par congressiste).
*   La suppression d'un hôtel entraîne la désattribution automatique des congressistes associés (mise à NULL de `id_hotel`).

## 5. Fonctionnalités Clés

### Gestion Hôtelière (CRUD)
*   **Lister** : Visualisation des hôtels avec filtres sur le nombre d'étoiles et les chambres disponibles.
*   **Ajouter** : Formulaire de création d'hôtel.
*   **Modifier** : Édition des informations.
*   **Supprimer** : Suppression sécurisée avec gestion des dépendances (désattribution).

### Gestion des Attributions
*   **Attribuer** : Affecte un congressiste à un hôtel.
    *   *Contrainte* : Vérifie la disponibilité (`chambre_disponible > 0`).
    *   *Action* : Décrémente le stock de chambres.
*   **Désattribuer** : Libère la chambre.
    *   *Action* : Incrémente le stock de chambres.
    *   *Contrainte* : Impossible si une facture existe.

### Sécurité & Fiabilité
*   **Transactions SQL** : Utilisées pour garantir la cohérence des données lors des attributions/désattributions (ex: `beginTransaction`, `commit`, `rollBack`).
*   **Injections SQL** : Protection via l'utilisation systématique de `bindValue` dans PDO.
*   **Authentification** : Hashage des mots de passe.

## 6. Installation

1.  Cloner le projet dans le dossier web (`www` ou `htdocs`).
2.  Importer le script SQL de la base de données.
3.  Configurer l'accès BDD dans `config/Database.php` :
    ```php
    private $host = "localhost";
    private $database_name = "congres";
    private $username = "root";
    private $password = "";
    ```
4.  Accéder via `http://localhost/congres/`.

---

