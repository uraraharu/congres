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


        public function setHotelCongressiste($idCongressiste,$idHotel): bool{

          try {
                $this->pdo->beginTransaction();

                // Vérifier facture existante
                $stmt = $this->pdo->prepare("SELECT 1 FROM facture WHERE id_congressiste = :id LIMIT 1");
                $stmt->bindValue(':id', $idCongressiste, PDO::PARAM_INT);
                $stmt->execute();
                if ($stmt->fetchColumn()) {
                    $this->pdo->rollBack(); // annule la requete
                    return false;
                }

                // Vérifier si déjà attribué
                $stmt = $this->pdo->prepare("SELECT id_hotel FROM congressiste WHERE id_congressiste = :id FOR UPDATE");
                $stmt->bindValue(':id', $idCongressiste, PDO::PARAM_INT);
                $stmt->execute();
                $currentHotel = $stmt->fetchColumn();
                if (!empty($currentHotel)) {
                    $this->pdo->rollBack(); // annule la requete
                    return false;
                }

                // Verrouiller hôtel + récupérer infos
                $stmt = $this->pdo->prepare("
                    SELECT prix_supplement_petit_dejeuner, chambre_disponible
                    FROM hotel
                    WHERE id_hotel = :id_hotel
                    FOR UPDATE
                ");
                $stmt->bindValue(':id_hotel', $idHotel, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$row) {
                    $this->pdo->rollBack();
                    return false;
                }

                // Vérifier disponibilité
                if ((int)$row['chambre_disponible'] <= 0) {
                    $this->pdo->rollBack();
                    return false;
                }

                $prixSupplement = (float)$row['prix_supplement_petit_dejeuner'];
                $flagSupplement = $prixSupplement > 0 ? 1 : 0;

                // Assigner le congressiste
                $stmt = $this->pdo->prepare("
                    UPDATE congressiste
                    SET id_hotel = :id_hotel,
                        supplement_petit_dejeuner = :supplement
                    WHERE id_congressiste = :id_congressiste
                ");
                $stmt->bindValue(':id_hotel', $idHotel, PDO::PARAM_INT);
                $stmt->bindValue(':supplement', $flagSupplement, PDO::PARAM_INT);
                $stmt->bindValue(':id_congressiste', $idCongressiste, PDO::PARAM_INT);
                $ok1 = $stmt->execute();

                // Décrémenter les chambres
                $stmt = $this->pdo->prepare("
                    UPDATE hotel
                    SET chambre_disponible = chambre_disponible - 1
                    WHERE id_hotel = :id_hotel AND chambre_disponible > 0
                ");
                $stmt->bindValue(':id_hotel', $idHotel, PDO::PARAM_INT);
                $ok2 = $stmt->execute();

                if (!$ok1 || $stmt->rowCount() !== 1) { //rowCount Retourne le nombre de lignes affectées par la dernière requête
                    $this->pdo->rollBack();
                    return false;
                }

                $this->pdo->commit(); // executer les requetes
                return true;

            } catch (Throwable $e) {
                if ($this->pdo->inTransaction()) $this->pdo->rollBack();
                return false;
            }
        }
        public function unsetHotelCongressiste(int $idCongressiste): bool
        {
            try {
                $this->pdo->beginTransaction();

                // Lire ancien hôtel
                $stmt = $this->pdo->prepare("
                    SELECT id_hotel
                    FROM congressiste
                    WHERE id_congressiste = :id
                    FOR UPDATE
                ");
                $stmt->bindValue(':id', $idCongressiste, PDO::PARAM_INT);
                $stmt->execute();
                $oldHotel = $stmt->fetchColumn();

                if (empty($oldHotel)) {
                    $this->pdo->commit();
                    return true;
                }

                // Supprimer lien hôtel
                $stmt = $this->pdo->prepare("
                    UPDATE congressiste
                    SET id_hotel = NULL,
                        supplement_petit_dejeuner = 0
                    WHERE id_congressiste = :id
                ");
                $stmt->bindValue(':id', $idCongressiste, PDO::PARAM_INT);
                $ok1 = $stmt->execute();

                // Réincrémenter les chambres
                $stmt = $this->pdo->prepare("
                    UPDATE hotel
                    SET chambre_disponible = chambre_disponible + 1
                    WHERE id_hotel = :id_hotel
                ");
                $stmt->bindValue(':id_hotel', (int)$oldHotel, PDO::PARAM_INT);
                $ok2 = $stmt->execute();

                if (!$ok1 || !$ok2) {
                    $this->pdo->rollBack();
                    return false;
                }

                $this->pdo->commit();
                return true;

            } catch (Throwable $e) {
                if ($this->pdo->inTransaction()) $this->pdo->rollBack();
                return false;
            }
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
        public function getAssignedCongressistes(): array
        {
            $sql = "SELECT c.id_congressiste, c.nom, c.prenom, c.id_hotel, h.nom_hotel
                    FROM congressiste c
                    INNER JOIN hotel h ON h.id_hotel = c.id_hotel
                    ORDER BY c.nom, c.prenom";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    }
?>