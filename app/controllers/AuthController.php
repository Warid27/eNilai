<?php
require_once dirname(__FILE__) . '/../models/User.php';

class AuthController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validasi input
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Username dan password wajib diisi!';
                require_once dirname(__FILE__) . '/../views/LoginView.php';
                return;
            }

            // Ambil data user berdasarkan username (termasuk password hash)
            $user = $this->user->findByUsername($username); // Harus mengembalikan data user + password hash

            if ($user && password_verify($password, $user['password'])) {
                // Password cocok
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['id_role'] = $user['id_role'];
                $_SESSION['message'] = 'Login berhasil!';
                header('Location: ?page=dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Username atau password salah!';
            }
        }

        require_once dirname(__FILE__) . '/../views/LoginView.php';
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $_SESSION['message'] = 'Logout berhasil!';
        header('Location: ?page=login');
        exit;
    }
}
