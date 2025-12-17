<?php
    class ModeleHotel{

        private PDO $pdo;
        public function __construct($db){
            $this->pdo = $db;
        }

            
        public function createHotel(Hotel $hotel){
            $sql = "INSERT INTO hotel (nom_hotel, adresse_hotel, prix, prix_supplement_petit_dejeuner, etoile, chambre_disponible) VALUES (:nom, :adresse, :prix, :prix_supplement_petit_dejeuner, :etoile, :chambre_disponible)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nom',$hotel->getNomHotel(),PDO::PARAM_STR);
            $stmt->bindValue(':adresse', $hotel->getAdresse(), PDO::PARAM_STR);
            $stmt->bindValue(':prix', $hotel->getPrix());
            $stmt->bindValue(':prix_supplement_petit_dejeuner', $hotel->getPrixSupplementPetitDejeuner());
            $stmt->bindValue(':etoile', $hotel->getEtoile(), PDO::PARAM_INT);
            $stmt->bindValue(':chambre_disponible', $hotel->getChambreDisponible(), PDO::PARAM_INT);
            return $stmt->execute();
            /*
            $status = $stmt->execute();
            if ($status) {
                $hotel->setIdHotel((int)$this->pdo->lastInsertId());
            }
            return $status;
            */
        }
    
            
            public function deleteHotel(int $id): bool
            {
                try {
                // Démarrer une transaction
                $this->pdo->beginTransaction();

                // Désattribuer les congressistes liés à cet hôtel
                $stmt = $this->pdo->prepare("
                    UPDATE congressiste
                    SET id_hotel = NULL,
                        supplement_petit_dejeuner = 0
                    WHERE id_hotel = :id_hotel
                ");
                $stmt->bindValue(':id_hotel', $id, PDO::PARAM_INT);
                $stmt->execute();

                // (Optionnel : tu peux récupérer combien ont été désattribués)
                // $nbDesattribues = $stmt->rowCount();

                // Supprimer l’hôtel
                $stmt = $this->pdo->prepare("DELETE FROM hotel WHERE id_hotel = :id");
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $okDelete = $stmt->execute();

                // Vérifier suppression réussie
                if (!$okDelete || $stmt->rowCount() !== 1) {
                    $this->pdo->rollBack();
                    return false;
                }

                // Valider les changements
                $this->pdo->commit();
                return true;

                } catch (Throwable $e) {
                    // En cas d'erreur → annule tout
                    if ($this->pdo->inTransaction()) {
                        $this->pdo->rollBack();
                    }
                    return false;
                }
            }
            

            public function updateHotel(Hotel $hotel): bool
            {
                $sql = "UPDATE hotel 
                        SET nom_hotel = :nom,
                            adresse_hotel = :adresse,
                            prix = :prix,
                            prix_supplement_petit_dejeuner = :prix_supplement_petit_dejeuner,
                            etoile = :etoile,
                            chambre_disponible = :chambre_disponible
                        WHERE id_hotel = :id";

                $stmt = $this->pdo->prepare($sql);

                $stmt->bindValue(':id', $hotel->getIdHotel(), PDO::PARAM_INT);
                $stmt->bindValue(':nom', $hotel->getNomHotel(), PDO::PARAM_STR);
                $stmt->bindValue(':adresse', $hotel->getAdresse(), PDO::PARAM_STR);
                $stmt->bindValue(':prix', $hotel->getPrix());
                $stmt->bindValue(':prix_supplement_petit_dejeuner', $hotel->getPrixSupplementPetitDejeuner());
                $stmt->bindValue(':etoile', $hotel->getEtoile(), PDO::PARAM_INT);
                $stmt->bindValue(':chambre_disponible', $hotel->getChambreDisponible(), PDO::PARAM_INT);

                return $stmt->execute();
            }

            public function findAll(?int $etoile = null, ?int $chambres = null)
            {
                $sql = "SELECT id_hotel, nom_hotel, adresse_hotel, prix, prix_supplement_petit_dejeuner, etoile, chambre_disponible
                FROM hotel WHERE 1=1"; //ne retire 1=1 ça casse tt
                $params = [];

                if ($etoile !== null) {
                    $sql .= " AND etoile >= :etoile";
                    $params[':etoile'] = $etoile;
                }

                if ($chambres !== null) {
                    $sql .= " AND chambre_disponible >= :chambres";
                    $params[':chambres'] = $chambres;
                }

                $sql .= " ORDER BY etoile DESC, chambre_disponible DESC, id_hotel ASC";

                $stmt = $this->pdo->prepare($sql);
                foreach ($params as $k => $v) {
                    $stmt->bindValue($k, $v, PDO::PARAM_INT);
                }
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            public function findById(int $id): ?array {
            $stmt = $this->pdo->prepare(
                "SELECT id_hotel, nom_hotel, adresse_hotel, prix,
                        prix_supplement_petit_dejeuner, etoile, chambre_disponible
                FROM hotel
                WHERE id_hotel = :id"
            );
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
            return $hotel !== false ? $hotel : null;
        }
        public function findByEtoile(int $etoile): array {
            $sql = "SELECT id_hotel, nom_hotel, adresse_hotel, prix,
                        prix_supplement_petit_dejeuner, etoile, chambre_disponible
                    FROM hotel
                    WHERE etoile = :etoile
                    ORDER BY prix ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':etoile', $etoile, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // TODO : FILTRE PAR ETOILE
    }




?>