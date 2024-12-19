<?php
class Favoris {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addFavori($utilisateur_id, $livre_id) {
        $stmt = $this->pdo->prepare("SELECT id FROM favoris WHERE utilisateur_id = ? AND livre_id = ?");
        $stmt->execute([$utilisateur_id, $livre_id]);
        if (!$stmt->fetch()) {
            $stmt = $this->pdo->prepare("INSERT INTO favoris (utilisateur_id, livre_id) VALUES (?, ?)");
            $stmt->execute([$utilisateur_id, $livre_id]);
        }
    }

    public function getFavorisByUser($utilisateur_id) {
        $stmt = $this->pdo->prepare("SELECT livres.titre, livres.auteur
                                     FROM favoris 
                                     JOIN livres ON favoris.livre_id = livres.id
                                     WHERE favoris.utilisateur_id = ?");
        $stmt->execute([$utilisateur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
