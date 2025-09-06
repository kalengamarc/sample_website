<?php
class Partage {
    private $id_partage;
    private $id_utilisateur;
    private $id_formation;
    private $id_produit;
    private $plateforme;
    private $date_partage;
    private $ip_address;
    private $user_agent;

    // Plateformes valides
    const PLATEFORMES = ['facebook', 'twitter', 'linkedin', 'whatsapp', 'email', 'lien'];

    // Constructeur
    public function __construct($id_partage, $id_utilisateur, $id_formation, $id_produit, 
                               $plateforme, $date_partage = null, $ip_address = null, $user_agent = null) {
        $this->id_partage = $id_partage;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_formation = $id_formation;
        $this->id_produit = $id_produit;
        $this->setPlateforme($plateforme);
        $this->date_partage = $date_partage ?: date('Y-m-d H:i:s');
        $this->ip_address = $ip_address;
        $this->user_agent = $user_agent;

        // Validation
        if ($id_formation === null && $id_produit === null) {
            throw new InvalidArgumentException("Un partage doit être associé à une formation ou un produit");
        }
        if ($id_formation !== null && $id_produit !== null) {
            throw new InvalidArgumentException("Un partage ne peut pas être associé à une formation et un produit en même temps");
        }
    }

    // Getters
    public function getIdPartage() { return $this->id_partage; }
    public function getIdUtilisateur() { return $this->id_utilisateur; }
    public function getIdFormation() { return $this->id_formation; }
    public function getIdProduit() { return $this->id_produit; }
    public function getPlateforme() { return $this->plateforme; }
    public function getDatePartage() { return $this->date_partage; }
    public function getIpAddress() { return $this->ip_address; }
    public function getUserAgent() { return $this->user_agent; }

    // Setters
    public function setIdPartage($id) { $this->id_partage = $id; }
    public function setIdUtilisateur($id) { $this->id_utilisateur = $id; }
    public function setIdFormation($id) { 
        if ($this->id_produit !== null) {
            throw new InvalidArgumentException("Impossible de définir une formation lorsque produit est déjà défini");
        }
        $this->id_formation = $id;
    }
    public function setIdProduit($id) { 
        if ($this->id_formation !== null) {
            throw new InvalidArgumentException("Impossible de définir un produit lorsque formation est déjà définie");
        }
        $this->id_produit = $id;
    }
    public function setPlateforme($plateforme) { 
        if (!in_array($plateforme, self::PLATEFORMES)) {
            throw new InvalidArgumentException("Plateforme non valide: " . $plateforme);
        }
        $this->plateforme = $plateforme;
    }
    public function setDatePartage($date) { $this->date_partage = $date; }
    public function setIpAddress($ip) { $this->ip_address = $ip; }
    public function setUserAgent($user_agent) { $this->user_agent = $user_agent; }

    // Méthodes utilitaires
    public function isFormationPartage() {
        return $this->id_formation !== null;
    }

    public function isProduitPartage() {
        return $this->id_produit !== null;
    }

    public function getType() {
        if ($this->isFormationPartage()) return 'formation';
        if ($this->isProduitPartage()) return 'produit';
        return null;
    }

    public function getAssociatedId() {
        return $this->isFormationPartage() ? $this->id_formation : $this->id_produit;
    }

    public static function getPlateformesValides() {
        return self::PLATEFORMES;
    }

    public function isSocialMedia() {
        return in_array($this->plateforme, ['facebook', 'twitter', 'linkedin']);
    }

    public function isDirectShare() {
        return in_array($this->plateforme, ['whatsapp', 'email', 'lien']);
    }
}
?>
<?php
require_once 'base.php';

class PartageCRUD {
    private $pdo;

    public function __construct() {
        $db = new DataBase();
        $this->pdo = $db->getConnection();
    }

    // CREATE
    public function create(Partage $partage) {
        $sql = "INSERT INTO partages (id_utilisateur, id_formation, id_produit, plateforme, date_partage, ip_address, user_agent) 
                VALUES (:id_utilisateur, :id_formation, :id_produit, :plateforme, :date_partage, :ip_address, :user_agent)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_utilisateur' => $partage->getIdUtilisateur(),
            ':id_formation' => $partage->getIdFormation(),
            ':id_produit' => $partage->getIdProduit(),
            ':plateforme' => $partage->getPlateforme(),
            ':date_partage' => $partage->getDatePartage(),
            ':ip_address' => $partage->getIpAddress(),
            ':user_agent' => $partage->getUserAgent()
        ]);

        $partage->setIdPartage($this->pdo->lastInsertId());
        return $partage;
    }

    // READ
    public function getById($id) {
        $sql = "SELECT * FROM partages WHERE id_partage = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        
        return $this->mapToPartage($data);
    }

    public function getAll() {
        $sql = "SELECT * FROM partages ORDER BY date_partage DESC";
        $stmt = $this->pdo->query($sql);
        
        $partages = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $partages[] = $this->mapToPartage($data);
        }
        
        return $partages;
    }

    public function getByUtilisateur($id_utilisateur) {
        $sql = "SELECT * FROM partages WHERE id_utilisateur = :id_utilisateur ORDER BY date_partage DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        
        $partages = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $partages[] = $this->mapToPartage($data);
        }
        
        return $partages;
    }

    public function getByFormation($id_formation) {
        $sql = "SELECT * FROM partages WHERE id_formation = :id_formation ORDER BY date_partage DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_formation' => $id_formation]);
        
        $partages = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $partages[] = $this->mapToPartage($data);
        }
        
        return $partages;
    }

    public function getByProduit($id_produit) {
        $sql = "SELECT * FROM partages WHERE id_produit = :id_produit ORDER BY date_partage DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_produit' => $id_produit]);
        
        $partages = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $partages[] = $this->mapToPartage($data);
        }
        
        return $partages;
    }

    public function getByPlateforme($plateforme) {
        $sql = "SELECT * FROM partages WHERE plateforme = :plateforme ORDER BY date_partage DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':plateforme' => $plateforme]);
        
        $partages = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $partages[] = $this->mapToPartage($data);
        }
        
        return $partages;
    }

    public function getStatsByElement($id_element, $type) {
        if ($type === 'formation') {
            $sql = "SELECT plateforme, COUNT(*) as count FROM partages WHERE id_formation = :id_element GROUP BY plateforme";
        } else {
            $sql = "SELECT plateforme, COUNT(*) as count FROM partages WHERE id_produit = :id_element GROUP BY plateforme";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_element' => $id_element]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update(Partage $partage) {
        $sql = "UPDATE partages SET 
                id_utilisateur = :id_utilisateur,
                id_formation = :id_formation,
                id_produit = :id_produit,
                plateforme = :plateforme,
                date_partage = :date_partage,
                ip_address = :ip_address,
                user_agent = :user_agent
                WHERE id_partage = :id_partage";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_utilisateur' => $partage->getIdUtilisateur(),
            ':id_formation' => $partage->getIdFormation(),
            ':id_produit' => $partage->getIdProduit(),
            ':plateforme' => $partage->getPlateforme(),
            ':date_partage' => $partage->getDatePartage(),
            ':ip_address' => $partage->getIpAddress(),
            ':user_agent' => $partage->getUserAgent(),
            ':id_partage' => $partage->getIdPartage()
        ]);
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM partages WHERE id_partage = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // UTILS
    private function mapToPartage($data) {
        return new Partage(
            $data['id_partage'],
            $data['id_utilisateur'],
            $data['id_formation'],
            $data['id_produit'],
            $data['plateforme'],
            $data['date_partage'],
            $data['ip_address'],
            $data['user_agent']
        );
    }
}
?>