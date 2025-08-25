<?php
class Database {
    private static $host = "localhost";
    private static $dbname = "josnet";
    private static $username = "root";
    private static $password = "";
    public static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname,
                    self::$username,
                    self::$password
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$conn;
    }

    public static function Transactionbegin(){
        self::$conn->beginTransaction();
    }

    public static function isInTransanction(){
        return self::$conn->inTransaction();
    }

    public static function commit(){
        self::$conn->commit();
    }

    public static function rollBack(){
        return self::$conn->rollBack();
    }
    
}
?>