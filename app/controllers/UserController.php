<?php
require_once dirname(__FILE__) . '/../models/User.php';
require_once dirname(__FILE__) . '/../models/Role.php';

class UserController
{
    private $user;
    private $role;

    public function __construct()
    {
        $this->user = new User();
        $this->role = new Role();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu!';
            header('Location: ?page=login');
            exit;
        }
        if ($_SESSION['id_role'] != 1) {
            $_SESSION['error'] = 'Akses ditolak! Hanya admin yang dapat mengelola pengguna.';
            header('Location: ?page=login');
            exit;
        }
        $users = $this->user->getAll();
        $roles = $this->role->getAllRoles();
        require_once dirname(__FILE__) . '/../views/UserManagementView.php';
    }

    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu!';
            header('Location: ?page=login');
            exit;
        }
        if ($_SESSION['id_role'] != 1) {
            $_SESSION['error'] = 'Akses ditolak! Hanya admin yang dapat mengelola pengguna.';
            header('Location: ?page=login');
            exit;
        }

        $roles = $this->role->getAllRoles();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $nis = trim($_POST['nis'] ?? '') ?: null; // null if empty
            $id_role = (int)($_POST['id_role'] ?? 2);

            // Validasi input wajib
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Username dan password wajib diisi!';
                header('Location: ?page=user&act=add');
                exit;
            }

            // Validasi khusus untuk role 6: nis wajib diisi
            if ($id_role == 6 && (is_null($nis) || $nis === '')) {
                $_SESSION['error'] = 'NIS wajib diisi untuk role yang dipilih!';
                header('Location: ?page=user&act=add');
                exit;
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Simpan ke database
            if ($this->user->create($username, $hashedPassword, $nis, $id_role)) {
                $_SESSION['message'] = 'Pengguna berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan pengguna!';
            }

            header('Location: ?page=user');
            exit;
        }

        require_once dirname(__FILE__) . '/../views/UserManagementView.php';
    }

    public function edit($id)
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu!';
            header('Location: ?page=login');
            exit;
        }
        if ($_SESSION['id_role'] != 1) {
            $_SESSION['error'] = 'Akses ditolak! Hanya admin yang dapat mengelola pengguna.';
            header('Location: ?page=login');
            exit;
        }

        $user = $this->user->getById($id);
        $roles = $this->role->getAllRoles();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $nis = trim($_POST['nis'] ?? '');
            $id_role = (int)($_POST['id_role'] ?? 2);

            // Validasi input wajib
            if (empty($username)) {
                $_SESSION['error'] = 'Username wajib diisi!';
                header('Location: ?page=user&act=edit&id=' . $id);
                exit;
            }

            // Handle nis based on role
            if ($id_role == 6) {
                // For role 6, nis must be a valid integer
                if (empty($nis) || !is_numeric($nis) || (int)$nis <= 0) {
                    $_SESSION['error'] = 'NIS wajib diisi dengan angka yang valid untuk role ini!';
                    header('Location: ?page=user&act=edit&id=' . $id);
                    exit;
                }
                $nis = (int)$nis; // Cast to integer
            } else {
                // For other roles, nis is optional; set to NULL if empty
                $nis = empty($nis) ? null : (int)$nis;
            }

            // Hash password if provided
            $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

            // Update database
            if ($this->user->update($id, $username, $hashedPassword, $nis, $id_role)) {
                $_SESSION['message'] = 'Pengguna berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui pengguna!';
            }
            header('Location: ?page=user');
            exit;
        }

        require_once dirname(__FILE__) . '/../views/UserManagementView.php';
    }
    public function delete($id)
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu!';
            header('Location: ?page=login');
            exit;
        }
        if ($_SESSION['id_role'] != 1) {
            $_SESSION['error'] = 'Akses ditolak! Hanya admin yang dapat mengelola pengguna.';
            header('Location: ?page=login');
            exit;
        }
        if ($this->user->delete($id)) {
            $_SESSION['message'] = 'Pengguna berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus pengguna!';
        }
        header('Location: ?page=user');
        exit;
    }
}
