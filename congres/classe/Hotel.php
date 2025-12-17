<?php



    class Hotel{
        private ?int $id_hotel;

        private string $nom_hotel;

        private string $adresse_hotel;

        private float $prix;

        private float $prix_supplement_petit_dejeuner;

        private int $etoile;

        private int $chambre_disponible;


        // Constructeur

        public function __construct(?int $id, string $nom,string $adresse, float $prix, float $prix_supplement, int $etoile, int $chambre)
        {
            $this->id_hotel = $id;
            $this->nom_hotel = $nom;
            $this->adresse_hotel = $adresse;
            $this->prix = $prix;
            $this->prix_supplement_petit_dejeuner = $prix_supplement;
            $this->etoile = $etoile;
            $this->chambre_disponible = $chambre;
        }
        // Getters
        public function getIdHotel(): int
        {
            return $this->id_hotel;
        }

        public function getNomHotel(): string
        {
            return $this->nom_hotel;
        }

        public function getAdresse(): string
        {
            return $this->adresse_hotel;
        }

        public function getPrix(): float
        {
            return $this->prix;
        }

        public function getPrixSupplementPetitDejeuner(): float
        {
            return $this->prix_supplement_petit_dejeuner;
        }

        public function getEtoile(): int
        {
            return $this->etoile;
        }

        public function getChambreDisponible(): int
        {
            return $this->chambre_disponible;
        }

        // Setter
        public function setHotel(
            int $id,
            string $nom,
            string $adresse,
            float $prix,
            float $prix_supplement,
            int $etoile,
            int $chambre
        ): void {
            $this->id_hotel = $id;
            $this->nom_hotel = $nom;
            $this->adresse_hotel = $adresse;
            $this->prix = $prix;
            $this->prix_supplement_petit_dejeuner = $prix_supplement;
            $this->etoile = $etoile;
            $this->chambre_disponible = $chambre;
        }
                
    }
?>