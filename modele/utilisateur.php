<?php
include_once('base.php');

class Utilisateur {
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $mot_de_passe;
    private $telephone;
    private $role;
    private $date_creation;
    private $photo; // ✅ Ajout de l'attribut photo
    private $description; // ✅ Ajout de l'attribut description
    private $specialite; // ✅ Ajout de l'attribut specialite
    private $id_formation;

    public function __construct(
        $id,
        $nom,
        $prenom,
        $email,
        $mot_de_passe,
        $telephone,
        $role,
        $description,
        $date_creation,
        $photo,
        $specialite = '',
        $id_formation = null)
        {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mot_de_passe = $mot_de_passe;
        $this->telephone = $telephone;
        $this->role = $role;
        $this->description = $description;
        $this->specialite = $specialite;
        $this->date_creation = $date_creation;
        $this->photo = $photo;
        $this->id_formation = $id_formation;
    }

    // --- GETTERS ---
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getMotDePasse() { return $this->mot_de_passe; }
    public function getRole() { return $this->role; }
    public function getDescription() { return $this->description; }
    public function getSpecialite() { return $this->specialite; } // ✅ Getter specialite
    public function getTelephone() { return $this->telephone; }
    public function getDateCreation() { return $this->date_creation; }
    public function getPhoto() { return $this->photo; } // ✅ Getter photo
    public function getIdFormation() { return $this->id_formation; } // ✅ Getter photo
    public function getBio() { return $this->description; } // ✅ Alias pour description

    // --- SETTERS ---
    public function setNom($nom) { $this->nom = $nom; }
    public function setPrenom($prenom) { $this->prenom = $prenom; }
    public function setEmail($email) { $this->email = $email; }
    public function setMotDePasse($mot_de_passe) { $this->mot_de_passe = $mot_de_passe; }
    public function setTelephone($telephone) { $this->telephone = $telephone; }
    public function setRole($role) { $this->role = $role; }
    public function setDescription($description) { $this->description = $description; }
    public function setSpecialite($specialite) { $this->specialite = $specialite; } // ✅ Setter specialite
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
    public function setPhoto($photo) { $this->photo = $photo; } // ✅ Setter photo
    public function setIdFormation($id_formation) { $this->id_formation = $id_formation; } // ✅ Setter photo
    public function setBio($bio) { $this->description = $bio; } // ✅ Alias pour description
}


class RequeteUtilisateur {
    private $crud;

    public function __construct() {
        $pdo = new Database();
        $this->crud = $pdo->getConnection();
    }

    // ✅ Ajouter utilisateur avec photo
    public function ajouterUtilisateur($utilisateur) {
        try {
            $pdo = new Database();
            $crud = $pdo->getConnection();
            
            // Debug: Log the SQL and parameters
            $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role, description, date_creation, photo, specialite, id_formation) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Handle null values properly
            $idFormation = $utilisateur->getIdFormation();
            if ($idFormation === '' || $idFormation === '2') {
                $idFormation = null; // Set to NULL for database
            }
            
            $params = [
                $utilisateur->getNom(),
                $utilisateur->getPrenom(),
                $utilisateur->getEmail(),
                password_hash($utilisateur->getMotDePasse(), PASSWORD_BCRYPT),
                $utilisateur->getTelephone(),
                $utilisateur->getRole(),
                $utilisateur->getDescription(),
                $utilisateur->getDateCreation(),
                $utilisateur->getPhoto() ?: '', // Convert NULL to empty string
                $utilisateur->getSpecialite(),
                $idFormation
            ];
            
            error_log("DEBUG SQL: " . $sql);
            error_log("DEBUG Params: " . print_r($params, true));
            
            $stmt = $crud->prepare($sql);
            $result = $stmt->execute($params);
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("DEBUG SQL Error: " . print_r($errorInfo, true));
                error_log("DEBUG Last Insert ID attempt: " . $crud->lastInsertId());
            } else {
                error_log("DEBUG SQL Success - Last Insert ID: " . $crud->lastInsertId());
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("DEBUG Exception in ajouterUtilisateur: " . $e->getMessage());
            return false;
        }
    }

    public static function getUtilisateurByEmail($email) {
        $pdo = new Database();
        $crud = $pdo->getConnection();
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $crud->prepare($sql);
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Utilisateur($row['id_utilisateur'], $row['nom'], $row['prenom'], $row['email'], $row['mot_de_passe'], $row['telephone'], $row['role'], $row['description'], $row['date_creation'], $row['photo'], $row['specialite'], $row['id_formation']);
        }
        return null;
    }
    public function getUtilisateursByRole(string $role): array {
        $pdo = new Database();
        $crud = $pdo->getConnection();
    $sql = "SELECT * FROM utilisateurs WHERE role = ?";
    $stmt = $this->crud->prepare($sql);
    $stmt->execute([$role]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $utilisateurs = [];
    foreach ($rows as $row) {
        $utilisateurs[] = new Utilisateur(
            $row['id_utilisateur'],
            $row['nom'],
            $row['prenom'],
            $row['email'],
            $row['mot_de_passe'],
            $row['telephone'],
            $row['role'],
            $row['description'],
            $row['date_creation'],
            $row['photo'],
            $row['specialite'],
            $row['id_formation']
        );
    }
    return $utilisateurs;
}
    // ✅ Récupérer utilisateur par ID avec photo

    public function getUtilisateurById($id) {
        $sql = "SELECT * FROM utilisateurs WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Utilisateur($row['id_utilisateur'], $row['nom'], $row['prenom'], $row['email'], $row['mot_de_passe'], $row['telephone'], $row['role'], $row['description'], $row['date_creation'], $row['photo'], $row['specialite'], $row['id_formation']);
        }
        return null;
    }

    public function getAllUtilisateurs() {
        $sql = "SELECT * FROM utilisateurs";
        $stmt = $this->crud->query($sql);
        $utilisateurs = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $utilisateurs[] = new Utilisateur($row['id_utilisateur'], $row['nom'], $row['prenom'], $row['email'], $row['mot_de_passe'], $row['telephone'], $row['role'], $row['description'], $row['date_creation'], $row['photo'], $row['specialite'], $row['id_formation']);
        }
        return $utilisateurs;
    }

    // ✅ Mise à jour avec photo, bio, specialite et id_formation
    public function mettreAJourUtilisateur($utilisateur) {
        $sql = "UPDATE utilisateurs 
                SET nom = ?, prenom = ?, email = ?, telephone = ?, role = ?, photo = ?, description = ?, specialite = ?, id_formation = ?
                WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        $params = [
            $utilisateur->getNom(),
            $utilisateur->getPrenom(),
            $utilisateur->getEmail(),
            $utilisateur->getTelephone(),
            $utilisateur->getRole(),
            $utilisateur->getPhoto(),
            $utilisateur->getDescription(),
            $utilisateur->getSpecialite(),
            $utilisateur->getIdFormation(),
            $utilisateur->getId()
        ];
        return $stmt->execute($params);
    }

    public function supprimerUtilisateur($id) {
        $sql = "DELETE FROM utilisateurs WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function authentifier($email, $mot_de_passe) {
        $utilisateur = $this->getUtilisateurByEmail($email);
        if ($utilisateur && password_verify($mot_de_passe, $utilisateur->getMotDePasse())) {
            return $utilisateur;
        }
        return null;
    }
}
?>
