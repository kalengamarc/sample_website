<?php
include_once('base.php');
class Utilisateur{
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $mot_de_passe;
    private $telephone;
    private $role;
    private $date_creation;

    public function __construct($id, $nom, $prenom, $email, $mot_de_passe, $telephone, $role, $date_creation) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mot_de_passe = $mot_de_passe;
        $this->telephone = $telephone;
        $this->role = $role;
        $this->date_creation = $date_creation;
    }
    public function getId() {
        return $this->id;
    }
    public function getNom() {
        return $this->nom;
    }
    public function getPrenom() {
        return $this->prenom;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getMotDePasse() {
        return $this->mot_de_passe;
    }
    public function getRole() {
        return $this->role;
    }
    public function getTelephone() {
        return $this->telephone;
    }
    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }
    public function getDateCreation() {
        return $this->date_creation;
    }
    public function setNom($nom) {
        $this->nom = $nom;
    }
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setMotDePasse($mot_de_passe) {
        $this->mot_de_passe = $mot_de_passe;
    }
    public function setRole($role) {
        $this->role = $role;
    }
    public function setDateCreation($date_creation) {
        $this->date_creation = $date_creation;
    }

}

class RequeteUtilisateur{

    private $crud;
    public function __construct() {
        $pdo = new Database();
        $this->crud = $pdo->getConnection();
    }
public function ajouterUtilisateur($utilisateur) {
    $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->crud->prepare($sql);
    $params = [
        $utilisateur->getNom(),
        $utilisateur->getPrenom(),
        $utilisateur->getEmail(),
        password_hash($utilisateur->getMotDePasse(), PASSWORD_BCRYPT),
        $utilisateur->getTelephone(),
        $utilisateur->getRole(),
        $utilisateur->getDateCreation()
    ];
    return $stmt->execute($params);
}

    public function getUtilisateurByEmail($email) {
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Utilisateur($row['id_utilisateur'], $row['nom'], $row['prenom'], $row['email'], $row['mot_de_passe'], $row['telephone'], $row['role'], $row['date_creation']);
        }
        return null;
    }
    public function getUtilisateurById($id) {
        $sql = "SELECT * FROM utilisateurs WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Utilisateur($row['id_utilisateur'], $row['nom'], $row['prenom'], $row['email'], $row['mot_de_passe'], $row['telephone'], $row['role'], $row['date_creation']);
        }
        return null;
    }
    public function getAllUtilisateurs() {
        $sql = "SELECT * FROM utilisateurs";
        $stmt = $this->crud->query($sql);
        $utilisateurs = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $utilisateurs[] = new Utilisateur($row['id_utilisateur'], $row['nom'], $row['prenom'], $row['email'], $row['mot_de_passe'], $row['telephone'], $row['role'], $row['date_creation']);
        }
        return $utilisateurs;
    }
    public function mettreAJourUtilisateur($utilisateur) {
        $sql = "UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, mot_de_passe = ?, telephone = ?, role = ? WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        $params = [$utilisateur->getNom(), $utilisateur->getPrenom(), $utilisateur->getEmail(), password_hash($utilisateur->getMotDePasse(), PASSWORD_BCRYPT), $utilisateur->getTelephone(), $utilisateur->getRole(), $utilisateur->getId()];
        return $stmt->execute($params);
    }
    public function supprimerUtilisateur($id) {
        $sql = "DELETE FROM utilisateurs WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        return $stmt->execute([$id]);
    }
    public function verifierConnexion($email, $mot_de_passe) {
        $utilisateur = $this->getUtilisateurByEmail($email);
        if ($utilisateur && password_verify($mot_de_passe, $utilisateur->getMotDePasse())) {
            return $utilisateur;
        }
        return null;
    }
    public function rechercherUtilisateurs($mot_cle) {
        $sql = "SELECT * FROM utilisateurs WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ?";
        $param = "%" . $mot_cle . "%";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$param, $param, $param]);
        $utilisateurs = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $utilisateurs[] = new Utilisateur($row['id_utilisateur'], $row['nom'], $row['prenom'], $row['email'], $row['mot_de_passe'], $row['telephone'], $row['role'], $row['date_creation']);
        }
        return $utilisateurs;
    }
    public function changerMotDePasse($id, $nouveau_mot_de_passe) {
        $sql = "UPDATE utilisateurs SET mot_de_passe = ? WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        $params = [password_hash($nouveau_mot_de_passe, PASSWORD_BCRYPT), $id];
        return $stmt->execute($params);
    }
    public function definirRole($id, $role) {
        $sql = "UPDATE utilisateurs SET role = ? WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        $params = [$role, $id];
        return $this->crud->execute($params);
    }
    public function getUtilisateursParRole($role) {
        $sql = "SELECT * FROM utilisateurs WHERE role = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$role]);
        $utilisateurs = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $utilisateurs[] = new Utilisateur($row['id_utilisateur'], $row['nom'], $row['prenom'], $row['email'], $row['mot_de_passe'], $role['telephone'], $row['role'], $row['date_creation']);
        }
        return $utilisateurs;
    }
}


?>