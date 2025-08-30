<?php
class Presence {
    private $id_presence;
    private $id_inscription;
    private $date_session;
    private $statut; // 'present' ou 'absent'

    // Constructeur
    public function __construct($id_inscription, $date_session, $statut = "absent") {
        $this->id_inscription = $id_inscription;
        $this->date_session = $date_session;
        $this->statut = $statut;
    }

    // Getters
    public function getIdPresence() { return $this->id_presence; }
    public function getIdInscription() { return $this->id_inscription; }
    public function getDateSession() { return $this->date_session; }
    public function getStatut() { return $this->statut; }

    // Setters
    public function setIdPresence($id) { $this->id_presence = $id; }
    public function setIdInscription($id) { $this->id_inscription = $id; }
    public function setDateSession($date) { $this->date_session = $date; }
    public function setStatut($statut) { $this->statut = $statut; }
}

include_once('base.php');

class CRUDPresence {
    private $connexion;

    public function __construct() {
        $db = new Database();
        $this->connexion = $db->getConnection();
    }

    // Ajouter une présence
    public function ajouterPresence(Presence $p) {
        $sql = "INSERT INTO presence (id_inscription, date_session, statut) VALUES (?, ?, ?)";
        $stmt = $this->connexion->prepare($sql);
        $ok = $stmt->execute([
            $p->getIdInscription(),
            $p->getDateSession(),
            $p->getStatut()
        ]);
        if($ok) {
            $p->setIdPresence($this->connexion->lastInsertId());
        }
        return $ok;
    }

    // Lire une présence par ID
    public function getPresenceById($id) {
        $sql = "SELECT * FROM presence WHERE id_presence = ?";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    // Lire toutes les présences
    public function getAllPresences() {
        $sql = "SELECT * FROM presence ORDER BY date_session DESC";
        $stmt = $this->connexion->query($sql);
        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->hydrate($row);
        }
        return $result;
    }

    // Lire les présences d'une inscription
    public function getPresencesByInscription($id_inscription) {
        $sql = "SELECT * FROM presence WHERE id_inscription = ? ORDER BY date_session DESC";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id_inscription]);
        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->hydrate($row);
        }
        return $result;
    }

    // Mettre à jour une présence
    public function updatePresence(Presence $p) {
        $sql = "UPDATE presence SET statut = ?, date_session = ? WHERE id_presence = ?";
        $stmt = $this->connexion->prepare($sql);
        return $stmt->execute([
            $p->getStatut(),
            $p->getDateSession(),
            $p->getIdPresence()
        ]);
    }

    // Supprimer une présence
    public function deletePresence($id) {
        $sql = "DELETE FROM presence WHERE id_presence = ?";
        $stmt = $this->connexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Hydrater un objet Presence à partir d'un tableau
    private function hydrate($data) {
        $p = new Presence($data['id_inscription'], $data['date_session'], $data['statut']);
        $p->setIdPresence($data['id_presence']);
        return $p;
    }
}
?>
