<?php
require_once dirname(__FILE__) . '/../config/Database.php';

class User
{
    private $db;

    public function __construct()
    {
        $db = new Database();
        $this->db = $db->getConnection();
    }

    public function login($username, $password)
    {
        $query = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['username' => $username, 'password' => $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $query = "SELECT u.id, u.username, u.id_role, u.nis, r.role FROM users u JOIN roles r ON u.id_role = r.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsersWithoutScores()
    {
        $query = "SELECT u.id, u.username, u.nis, u.id_role, r.role FROM users u JOIN roles r ON u.id_role = r.id LEFT JOIN scores s ON u.id = s.id_user WHERE u.id_role = 6 AND s.id_user IS NULL;";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($username, $password, $nis, $id_role)
    {
        $query = "INSERT INTO users (username, password, nis, id_role) VALUES (:username, :password, :nis, :id_role)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'username' => $username,
            'password' => $password,
            'nis' => $nis,
            'id_role' => $id_role
        ]);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUsername($username)
    {
        $query = "SELECT id, username, password, id_role FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $username, $password, $nis, $id_role)
    {
        // Start building the query with fields that are always updated
        $query = "UPDATE users SET username = :username, nis = :nis, id_role = :id_role";

        // Add password only if it's provided
        if ($password !== null) {
            $query .= ", password = :password";
        }

        $query .= " WHERE id = :id";

        $stmt = $this->db->prepare($query);

        // Build the parameters array
        $params = [
            'username' => $username,
            'nis' => $nis,
            'id_role' => $id_role,
            'id' => $id
        ];

        // Only bind password if it's not null
        if ($password !== null) {
            $params['password'] = $password;
        }

        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get user by username for profile controller
     * @param string $username
     * @return array|false
     */
    public function getByUsername($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update user profile (username and nis only)
     * @param int $id
     * @param string $username
     * @param string $nis
     * @return bool
     */
    public function updateProfile($id, $username, $nis)
    {
        $query = "UPDATE users SET username = :username, nis = :nis WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'username' => $username,
            'nis' => $nis,
            'id' => $id
        ]);
    }

    /**
     * Update user password
     * @param int $id
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'password' => $hashedPassword,
            'id' => $id
        ]);
    }
}
