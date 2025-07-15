<?php
class Scores
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function createScore($subjects, $value, $id_user)
    {
        $stmt = $this->conn->prepare("INSERT INTO scores (subjects, value, id_user) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $subjects, $value, $id_user);
        return $stmt->execute();
    }

    public function updateScore($id, $username, $password, $nis = null, $id_role = 2)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE scores SET username = ?, password = ?, nis = ?, id_role = ? WHERE id = ?");
        $stmt->bind_param("sssii", $username, $hashed, $nis, $id_role, $id);
        return $stmt->execute();
    }

    public function deleteScore($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM scores WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getScore($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM scores WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function loginScore($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM scores WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllScores($params = null)
    {
        $query = "SELECT scores.id AS user_id, scores.username, scores.nis, scores.id_role, scores.subjects, scores.value FROM scores LEFT JOIN scores ON scores.id = scores.id_user WHERE id_role = 6";
        if ($params === 'all') $query = "SELECT * FROM scores";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
