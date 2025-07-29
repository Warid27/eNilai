<?php
require_once dirname(__FILE__) . '/../config/Database.php';

class Gallery {
    private $db;

    public function __construct() {
        $db = new Database();
        $this->db = $db->getConnection();
    }

    public function getAllImages($id_user = null) {
        $query = "SELECT g.id, g.image, u.username 
                  FROM gallery g 
                  JOIN users u ON g.id_user = u.id";
        if ($id_user) {
            $query .= " WHERE g.id_user = :id_user";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id_user' => $id_user]);
        } else {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImageById($id) {
        $query = "SELECT * FROM gallery WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function uploadImage($id_user, $image_path) {
        $query = "INSERT INTO gallery (id_user, image) VALUES (:id_user, :image)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id_user' => $id_user,
            'image' => $image_path
        ]);
    }

    public function updateImage($id, $image_path) {
        $query = "UPDATE gallery SET image = :image WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'image' => $image_path,
            'id' => $id
        ]);
    }

    public function deleteImage($id) {
        $query = "DELETE FROM gallery WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}
?>