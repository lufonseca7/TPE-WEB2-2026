<?php
require_once './config.php';
class userModel{
    private $db;

    function __construct() {
        $this->db = new PDO('mysql:host='.MYSQL_HOST.';port=3307;dbname='.MYSQL_DB.';charset=utf8', MYSQL_USER, MYSQL_PASS);
        $this->_deploy();
    }

    private function _deploy() {
        $query = $this->db->prepare(
            'CREATE TABLE IF NOT EXISTS usuario (
                id int(11) NOT NULL AUTO_INCREMENT,
                user varchar(50) NOT NULL,
                password char(60) NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci'
        );
        $query->execute();

        $query = $this->db->prepare('SELECT COUNT(*) FROM usuario WHERE user = ?');
        $query->execute(['webadmin']);

        if ($query->fetchColumn() == 0) {
            $password = password_hash('admin', PASSWORD_BCRYPT);
            $query = $this->db->prepare('INSERT INTO usuario(user, password) VALUES (?, ?)');
            $query->execute(['webadmin', $password]);
        }
    }

    public function getByUser($user) {
        $query = $this->db->prepare('SELECT * FROM usuario WHERE user = ?');
        $query->execute([$user]);

        return $query->fetch(PDO::FETCH_OBJ);
    }
}