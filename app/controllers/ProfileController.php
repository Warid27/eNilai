<?php
require_once dirname(__FILE__) . '/../models/User.php';

class ProfileController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu!';
            header('Location: ?page=login');
            exit;
        }

        // Get current user data
        $userData = $this->user->getById($_SESSION['user_id']);
        if (!$userData) {
            $_SESSION['error'] = 'Data pengguna tidak ditemukan!';
            header('Location: ?page=dashboard');
            exit;
        }

        // Define base path for assets
        $base_path = defined('BASE_PATH') ? BASE_PATH : dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
        $base_path = '/' . trim($base_path, '/') . '/';
        if (!defined('BASE_PATH')) {
            define('BASE_PATH', $base_path);
        }

        require_once dirname(__FILE__) . '/../views/ProfileView.php';
    }

    public function updateProfile()
    {
        $username = trim($_POST['username'] ?? '');
        $nis = trim($_POST['nis'] ?? '');

        // Validation
        if (empty($username)) {
            $_SESSION['error'] = 'Username tidak boleh kosong!';
            return;
        }

        // Check if username is already taken by another user
        $existingUser = $this->user->getByUsername($username);
        if ($existingUser && $existingUser['id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Username sudah digunakan oleh pengguna lain!';
            return;
        }

        // Update profile
        if ($this->user->updateProfile($_SESSION['user_id'], $username, $nis)) {
            $_SESSION['username'] = $username; // Update session
            $_SESSION['message'] = 'Profile berhasil diperbarui!';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui profile!';
        }
    }

    public function changePassword()
    {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = 'Semua field password harus diisi!';
            return;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'Password baru dan konfirmasi password tidak cocok!';
            return;
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = 'Password baru minimal 6 karakter!';
            return;
        }

        // Get current user data
        $userData = $this->user->getById($_SESSION['user_id']);
        if (!$userData) {
            $_SESSION['error'] = 'Data pengguna tidak ditemukan!';
            return;
        }

        // Verify current password
        if (!password_verify($currentPassword, $userData['password'])) {
            $_SESSION['error'] = 'Password saat ini tidak benar!';
            return;
        }

        // Update password
        if ($this->user->updatePassword($_SESSION['user_id'], $newPassword)) {
            $_SESSION['message'] = 'Password berhasil diubah!';
        } else {
            $_SESSION['error'] = 'Gagal mengubah password!';
        }
    }
}
