<?php
class Livre {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM livres");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function add($titre, $auteur, $utilisateur_id) {
        $stmt = $this->pdo->prepare("INSERT INTO livres (titre, auteur, utilisateur_id) VALUES (?, ?, ?)");
        $stmt->execute([$titre, $auteur, $utilisateur_id]);
    }
}
