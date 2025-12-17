<?php
    class ModeleCongressiste{

        private PDO $pdo;
        public function __construct($db){
            $this->pdo = $db;
        }



        
        public function getCongressiste(): array{
            $sql = "SELECT id_congressiste, nom, prenom FROM congressiste";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $congressistes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $congressistes;
        }


        
        public function getNbSouhaite($id){
            $sql = "SELECT nb_etoile_souhaite FROM congressiste WHERE id_congressiste = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultat['nb_etoile_souhaite'];
        }

        public function findByEmailAndPassword(string $email, string $password): ?array {
            // On récupère le congressiste par email
            $sql = "SELECT id_congressiste, nom, prenom, email, password, id_hotel
                    FROM congressiste
                    WHERE email = :email
                    LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Supprime le hash du tableau avant de le renvoyer
                unset($user['password']);
                return $user;
            }

            return null;
        }


        //TODO : Verifier si deja attribué
        public function setHotelCongressiste($idCongressiste,$idHotel): bool{

             // Refuser si une facture existe déjà
            $stmt = $this->pdo->prepare("SELECT 1 FROM facture WHERE id_congressiste = :id LIMIT 1");
            $stmt->bindValue(':id', $idCongressiste, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn()) {
                return false; // facture déjà attribuée
            }
                    // 🔒 Vérifier si un hôtel est déjà attribué à ce congressiste
            $stmt = $this->pdo->prepare("
                SELECT id_hotel 
                FROM congressiste 
                WHERE id_congressiste = :id
                LIMIT 1
            ");
            $stmt->bindValue(':id', $idCongressiste, PDO::PARAM_INT);
            $stmt->execute();
            $currentHotel = $stmt->fetchColumn();
            if (!empty($currentHotel)) {
                return false; // ❌ un hôtel est déjà attribué
            }

            // Récupérer le prix du supplément de l'hôtel
            $stmt = $this->pdo->prepare("SELECT prix_supplement_petit_dejeuner FROM hotel WHERE id_hotel = :id_hotel");
            $stmt->bindValue(':id_hotel', $idHotel, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row === false) {
                return false; // hôtel introuvable
            }

            $prixSupplement = (float)$row['prix_supplement_petit_dejeuner'];
            $flagSupplement = $prixSupplement > 0 ? 1 : 0; // booleen TINYINT(1) dans congressiste

            // Attribuer l'hôtel + flag petit-déj
            $stmt = $this->pdo->prepare("
                UPDATE congressiste
                SET id_hotel = :id_hotel,
                    supplement_petit_dejeuner = :supplement
                WHERE id_congressiste = :id_congressiste
            ");
            $stmt->bindValue(':id_hotel', $idHotel, PDO::PARAM_INT);
            $stmt->bindValue(':supplement', $flagSupplement, PDO::PARAM_INT);
            $stmt->bindValue(':id_congressiste', $idCongressiste, PDO::PARAM_INT);

            return $stmt->execute(); // ✅ une seule exécution, retourne bool
        }
        public function getAllCongressiste(): array
        {
            $sql = "SELECT id_congressiste, nom, prenom, id_hotel
                    FROM congressiste
                    ORDER BY nom, prenom";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>