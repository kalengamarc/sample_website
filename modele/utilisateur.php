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

    public function __construct($id, $nom, $prenom, $email, $mot_de_passe, $telephone, $role, $date_creation, $photo) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mot_de_passe = $mot_de_passe;
        $this->telephone = $telephone;
        $this->role = $role;
        $this->date_creation = $date_creation;
        $this->photo = $photo;
    }

    // --- GETTERS ---
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getMotDePasse() { return $this->mot_de_passe; }
    public function getRole() { return $this->role; }
    public function getTelephone() { return $this->telephone; }
    public function getDateCreation() { return $this->date_creation; }
    public function getPhoto() { return $this->photo; } // ✅ Getter photo

    // --- SETTERS ---
    public function setNom($nom) { $this->nom = $nom; }
    public function setPrenom($prenom) { $this->prenom = $prenom; }
    public function setEmail($email) { $this->email = $email; }
    public function setMotDePasse($mot_de_passe) { $this->mot_de_passe = $mot_de_passe; }
    public function setTelephone($telephone) { $this->telephone = $telephone; }
    public function setRole($role) { $this->role = $role; }
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
    public function setPhoto($photo) { $this->photo = $photo; } // ✅ Setter photo
}

class RequeteUtilisateur {
    private $crud;
    public function __construct() {
        $pdo = new Database();
        $this->crud = $pdo->getConnection();
    }

    // ✅ Ajouter utilisateur avec photo
    public function ajouterUtilisateur($utilisateur) {
        $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role, date_creation, photo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->crud->prepare($sql);
        $params = [
            $utilisateur->getNom(),
            $utilisateur->getPrenom(),
            $utilisateur->getEmail(),
            password_hash($utilisateur->getMotDePasse(), PASSWORD_BCRYPT),
            $utilisateur->getTelephone(),
            $utilisateur->getRole(),
            $utilisateur->getDateCreation(),
            $utilisateur->getPhoto()
        ];
        return $stmt->execute($params);
    }

    public function getUtilisateurByEmail($email) {
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Utilisateur($row['id_utilisateur'], $row['nom'], $row['prenom'], $row['email'], $row['mot_de_passe'], $row['telephone'], $row['role'], $row['date_creation'], $row['photo']);
        }
        return null;
    }

    public function getUtilisateurById($id) {
        $sql = "SELECT * FROM utilisateurs WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Utilisateur($row['id_utilisateur'], $row['nom'], $row['prenom'], $row['email'], $row['mot_de_passe'], $row['telephone'], $row['role'], $row['date_creation'], $row['photo']);
        }
        return null;
    }

    public function getAllUtilisateurs() {
        $sql = "SELECT * FROM utilisateurs";
        $stmt = $this->crud->query($sql);
        $utilisateurs = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $utilisateurs[] = new Utilisateur($row['id_utilisateur'], $row['nom'], $row['prenom'], $row['email'], $row['mot_de_passe'], $row['telephone'], $row['role'], $row['date_creation'], $row['photo']);
        }
        return $utilisateurs;
    }

    // ✅ Mise à jour avec photo
    public function mettreAJourUtilisateur($utilisateur) {
        $sql = "UPDATE utilisateurs 
                SET nom = ?, prenom = ?, email = ?, mot_de_passe = ?, telephone = ?, role = ?, photo = ?
                WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        $params = [
            $utilisateur->getNom(),
            $utilisateur->getPrenom(),
            $utilisateur->getEmail(),
            password_hash($utilisateur->getMotDePasse(), PASSWORD_BCRYPT),
            $utilisateur->getTelephone(),
            $utilisateur->getRole(),
            $utilisateur->getPhoto(),
            $utilisateur->getId()
        ];
        return $stmt->execute($params);
    }

    public function supprimerUtilisateur($id) {
        $sql = "DELETE FROM utilisateurs WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
