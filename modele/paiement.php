<?php
class Paiement {
    private $id_paiement;
    private $id_utilisateur;
    private $type;
    private $id_reference;
    private $montant;
    private $mode;
    private $statut;
    private $date_paiement;

    // Constructeur
    public function __construct($id_utilisateur, $type, $id_reference, $montant, $mode, $statut="en attente", $date_paiement=null) {
        $this->id_utilisateur = $id_utilisateur;
        $this->type = $type;
        $this->id_reference = $id_reference;
        $this->montant = $montant;
        $this->mode = $mode;
        $this->statut = $statut;
        $this->date_paiement = $date_paiement;
    }

    // Getters et setters
    public function getIdPaiement() { return $this->id_paiement; }
    public function getIdUtilisateur() { return $this->id_utilisateur; }
    public function getType() { return $this->type; }
    public function getIdReference() { return $this->id_reference; }
    public function getMontant() { return $this->montant; }
    public function getMode() { return $this->mode; }
    public function getStatut() { return $this->statut; }
    public function getDatePaiement() { return $this->date_paiement; }

    public function setIdPaiement($id) { $this->id_paiement = $id; }
    public function setIdUtilisateur($id) { $this->id_utilisateur = $id; }
    public function setType($t) { $this->type = $t; }
    public function setIdReference($id) { $this->id_reference = $id; }
    public function setMontant($m) { $this->montant = $m; }
    public function setMode($m) { $this->mode = $m; }
    public function setStatut($s) { $this->statut = $s; }
    public function setDatePaiement($d) { $this->date_paiement = $d; }
}

include_once('base.php');

class CRUDPaiement {
    private $connexion;

    public function __construct() {
        $db = new Database();
        $this->connexion = $db->getConnection();
    }

    // Créer un paiement
    public function createPaiement(Paiement $paiement) {
        $sql = "INSERT INTO paiement (id_utilisateur, type, id_reference, montant, mode, statut) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connexion->prepare($sql);
        return $stmt->execute([
            $paiement->getIdUtilisateur(),
            $paiement->getType(),
            $paiement->getIdReference(),
            $paiement->getMontant(),
            $paiement->getMode(),
            $paiement->getStatut()
        ]);
    }

    // Lire un paiement par ID
    public function getPaiementById($id) {
        $sql = "SELECT * FROM paiement WHERE id_paiement = ?";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->hydrate($data) : null;
    }

    // Récupérer tous les paiements
    public function getAllPaiements() {
        $sql = "SELECT * FROM paiement ORDER BY date_paiement DESC";
        $stmt = $this->connexion->query($sql);
        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->hydrate($row);
        }
        return $result;
    }

    // Mettre à jour un paiement (ex: statut)
    public function updatePaiement(Paiement $paiement) {
        $sql = "UPDATE paiement SET statut = ?, mode = ?, montant = ? WHERE id_paiement = ?";
        $stmt = $this->connexion->prepare($sql);
        return $stmt->execute([
            $paiement->getStatut(),
            $paiement->getMode(),
            $paiement->getMontant(),
            $paiement->getIdPaiement()
        ]);
    }

    // Supprimer un paiement
    public function deletePaiement($id) {
        $sql = "DELETE FROM paiement WHERE id_paiement = ?";
        $stmt = $this->connexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Hydrater un objet Paiement à partir d'un tableau
    private function hydrate($data) {
        $paiement = new Paiement(
            $data['id_utilisateur'],
            $data['type'],
            $data['id_reference'],
            $data['montant'],
            $data['mode'],
            $data['statut'],
            $data['date_paiement']
        );
        $paiement->setIdPaiement($data['id_paiement']);
        return $paiement;
    }
}
?>

