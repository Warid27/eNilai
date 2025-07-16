<?php
class Users
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function createUser($username, $password, $nis = null, $id_role = 2)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, nis, id_role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $hashed, $nis, $id_role);
        return $stmt->execute();
    }

    public function updateUser($id, $username, $password, $nis = null, $id_role = 2)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET username = ?, password = ?, nis = ?, id_role = ? WHERE id = ?");
        $stmt->bind_param("sssii", $username, $hashed, $nis, $id_role, $id);
        return $stmt->execute();
    }

    public function deleteUser($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getUser($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function loginUser($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllUsers($params = null)
    {
        $query = "SELECT users.id AS user_id, users.username, users.nis, users.id_role, scores.subjects, scores.value FROM users LEFT JOIN scores ON users.id = scores.id_user WHERE id_role = 6";
        if ($params === 'all') $query = "SELECT * FROM users";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
