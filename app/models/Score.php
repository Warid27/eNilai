<?php
require_once dirname(__FILE__) . '/../config/Database.php';

class Score
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Get all scores with user info
     * Each row contains: id, id_user, username, nis, scores (as JSON)
     */
    public function getAll()
    {
        $query = "SELECT s.id, s.id_user, s.scores, u.username, u.nis 
                  FROM scores s 
                  JOIN users u ON s.id_user = u.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new score record
     * @param int $id_user
     * @param array $scores Associative array: ['Matematika' => 85, 'Bahasa Indonesia' => 76, ...]
     * @return bool
     */
    public function create($id_user, $scores)
    {
        $scoresJson = json_encode($scores);
        if ($scoresJson === false) {
            return false; // Invalid JSON
        }

        $query = "INSERT INTO scores (id_user, scores) VALUES (:id_user, :scores)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id_user' => $id_user,
            'scores' => $scoresJson
        ]);
    }

    /**
     * Find score record by ID
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        $query = "SELECT * FROM scores WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update scores for a specific record
     * @param int $id Record ID
     * @param array $scores Associative array of subject => score
     * @return bool
     */
    public function update($id, $scores)
    {
        $scoresJson = json_encode($scores);
        if ($scoresJson === false) {
            return false;
        }

        $query = "UPDATE scores SET scores = :scores WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'scores' => $scoresJson,
            'id' => $id
        ]);
    }

    /**
     * Delete a score record by ID
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $query = "DELETE FROM scores WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Optional: Get scores by user ID (useful for profile/report)
     * @param int $id_user
     * @return array|false
     */
    public function getByUserId($id_user)
    {
        $query = "SELECT * FROM scores WHERE id_user = :id_user LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id_user' => $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
