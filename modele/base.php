<?php
class Database {
    private static string $host = "localhost";
    private static string $dbname = "josnet";
    private static string $username = "root";
    private static string $password = "";
    private static ?PDO $conn = null;

    // Connexion unique (Singleton)
    public static function getConnection(): PDO {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8",
                    self::$username,
                    self::$password
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("❌ Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return self::$conn;
    }

    // Démarrer une transaction
    public static function beginTransaction(): void {
        $db = self::getConnection();
        if (!$db->inTransaction()) {
            $db->beginTransaction();
        }
    }

    // Vérifier si on est en transaction
    public static function isInTransaction(): bool {
        $db = self::getConnection();
        return $db->inTransaction();
    }

    // Valider une transaction
    public static function commit(): void {
        $db = self::getConnection();
        if ($db->inTransaction()) {
            $db->commit();
        }
    }

    // Annuler une transaction
    public static function rollBack(): void {
        $db = self::getConnection();
        if ($db->inTransaction()) {
            $db->rollBack();
        }
    }

    // Fermer la connexion
    public static function close(): void {
        self::$conn = null;
    }
}
?>
