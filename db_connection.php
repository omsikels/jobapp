<?php
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // Configure these values according to your database setup
        $host = 'localhost';
        $dbname = 'jobapplication';
        $username = 'root';
        $password = '';
        
        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$dbname", 
                $username, 
                $password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    public function commit() {
        return $this->connection->commit();
    }
    
    public function rollback() {
        return $this->connection->rollBack();
    }
    
    public function insert($query, $params = []) {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $this->connection->lastInsertId();
    }
}
?>