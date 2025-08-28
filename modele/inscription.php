<?php
include_once("base.php");

class Inscription {
    private $id_inscription;
    private $id_utilisateur;
    private $id_formation;
    private $date_inscription;
    private $statut;

    public function __construct($id_inscription, $id_utilisateur, $id_formation, $date_inscription, $statut) {
        $this->id_inscription = $id_inscription;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_formation = $id_formation;
        $this->date_inscription = $date_inscription;
        $this->statut = $statut;
    }

    // --- GETTERS ---
    public function getIdInscription() { return $this->id_inscription; }
    public function getIdUtilisateur() { return $this->id_utilisateur; }
    public function getIdFormation() { return $this->id_formation; }
    public function getDateInscription() { return $this->date_inscription; }
    public function getStatut() { return $this->statut; }

    // --- SETTERS ---
    public function setIdUtilisateur($id_utilisateur) { $this->id_utilisateur = $id_utilisateur; }
    public function setIdFormation($id_formation) { $this->id_formation = $id_formation; }
    public function setDateInscription($date_inscription) { $this->date_inscription = $date_inscription; }
    public function setStatut($statut) { $this->statut = $statut; }
}


include_once('base.php');
include_once('Inscription.php'); // ajustez le chemin si besoin

class RequeteInscription {
    private $crud;
    private const $TABLE = 'inscriptions'; // changez en 'inscription' si votre table est au singulier
    private const $STATUTS = ['inscrit', 'en cours', 'terminé', 'annulé'];

    public function __construct() {
        $pdo = new Database();
        $this->crud = $pdo->getConnection();
    }

    /* -------------------- CREATE -------------------- */
    public function ajouterInscription(Inscription $i) {
        $this->assertStatutValide($i->getStatut());

        $sql = "INSERT INTO ".self::TABLE." (id_utilisateur, id_formation, statut, date_inscription)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->crud->prepare($sql);
        $date = $i->getDateInscription() ?? date('Y-m-d H:i:s');
        $ok = $stmt->execute([
            $i->getIdUtilisateur(),
            $i->getIdFormation(),
            $i->getStatut(),
            $date
        ]);

        if ($ok) {
            return (int)$this->crud->lastInsertId();
        }
        return false;
    }

    /* -------------------- READ -------------------- */
    public function getInscriptionById(int $id): ?Inscription {
        $sql = "SELECT * FROM ".self::TABLE." WHERE id_inscription = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function getAllInscriptions(): array {
        $sql = "SELECT * FROM ".self::TABLE." ORDER BY date_inscription DESC";
        $stmt = $this->crud->query($sql);
        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $this->hydrate($row);
        }
        return $items;
    }

    public function getInscriptionsByUtilisateur(?int $id_utilisateur): array {
        $sql = "SELECT * FROM ".self::TABLE." WHERE id_utilisateur ".($id_utilisateur === null ? "IS NULL" : "= ?");
        $stmt = $this->crud->prepare($sql);
        $stmt->execute($id_utilisateur === null ? [] : [$id_utilisateur]);
        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $this->hydrate($row);
        }
        return $items;
    }

    public function getInscriptionsByFormation(?int $id_formation): array {
        $sql = "SELECT * FROM ".self::TABLE." WHERE id_formation ".($id_formation === null ? "IS NULL" : "= ?");
        $stmt = $this->crud->prepare($sql);
        $stmt->execute($id_formation === null ? [] : [$id_formation]);
        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $this->hydrate($row);
        }
        return $items;
    }

    /* -------------------- UPDATE -------------------- */
    public function mettreAJourInscription(Inscription $i): bool {
        if (!$i->getIdInscription()) return false;
        $this->assertStatutValide($i->getStatut());

        $sql = "UPDATE ".self::TABLE."
                SET id_utilisateur = ?, id_formation = ?, statut = ?
                WHERE id_inscription = ?";
        $stmt = $this->crud->prepare($sql);
        return $stmt->execute([
            $i->getIdUtilisateur(),
            $i->getIdFormation(),
            $i->getStatut(),
            $i->getIdInscription()
        ]);
    }

    public function changerStatut(int $id_inscription, string $statut): bool {
        $this->assertStatutValide($statut);
        $sql = "UPDATE ".self::TABLE." SET statut = ? WHERE id_inscription = ?";
        $stmt = $this->crud->prepare($sql);
        return $stmt->execute([$statut, $id_inscription]);
    }

    /* -------------------- DELETE -------------------- */
    public function supprimerInscription(int $id): bool {
        $sql = "DELETE FROM ".self::TABLE." WHERE id_inscription = ?";
        $stmt = $this->crud->prepare($sql);
        return $stmt->execute([$id]);
    }

    /* -------------------- UTILITAIRES -------------------- */
    public function existeDeja(?int $id_utilisateur, ?int $id_formation): bool {
        $conds = [];
        $params = [];
        if ($id_utilisateur === null) { $conds[] = "id_utilisateur IS NULL"; }
        else { $conds[] = "id_utilisateur = ?"; $params[] = $id_utilisateur; }

        if ($id_formation === null) { $conds[] = "id_formation IS NULL"; }
        else { $conds[] = "id_formation = ?"; $params[] = $id_formation; }

        $sql = "SELECT 1 FROM ".self::TABLE." WHERE ".implode(" AND ", $conds)." LIMIT 1";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetchColumn();
    }

    public function compterParFormation(int $id_formation): int {
        $sql = "SELECT COUNT(*) FROM ".self::TABLE." WHERE id_formation = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id_formation]);
        return (int)$stmt->fetchColumn();
    }

    public function statsParStatut(): array {
        $sql = "SELECT statut, COUNT(*) AS total FROM ".self::TABLE." GROUP BY statut";
        $stmt = $this->crud->query($sql);
        $out = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $out[$row['statut']] = (int)$row['total'];
        }
        return $out;
    }

    public function getDernieresInscriptions(int $limit = 10): array {
        $limit = max(1, (int)$limit);
        $sql = "SELECT * FROM ".self::TABLE." ORDER BY date_inscription DESC LIMIT {$limit}";
        $stmt = $this->crud->query($sql);
        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $this->hydrate($row);
        }
        return $items;
    }

    /* -------------------- PRIVÉ -------------------- */
    private function hydrate(array $row): Inscription {
        return new Inscription(
            $row['id_inscription'] ?? null,
            $row['id_utilisateur'] ?? null,
            $row['id_formation'] ?? null,
            $row['statut'] ?? 'inscrit',
            $row['date_inscription'] ?? null
        );
    }

    private function assertStatutValide(string $statut): void {
        if (!in_array($statut, self::STATUTS, true)) {
            throw new InvalidArgumentException(
                "Statut invalide: {$statut}. Autorisés: ".implode(', ', self::STATUTS)
            );
        }
    }
}

?>