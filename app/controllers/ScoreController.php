<?php
require_once dirname(__FILE__) . '/../models/Score.php';
require_once dirname(__FILE__) . '/../models/User.php';

class ScoreController
{
    private $user;
    private $score;

    public function __construct()
    {
        $this->user = new User();
        $this->score = new Score();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu!';
            header('Location: ?page=login');
            exit;
        }

        $scores = $this->score->getAll();
        $scoreUsers = $this->user->getUsersWithoutScores();
        require_once dirname(__FILE__) . '/../views/ScoreView.php';
    }

    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu!';
            header('Location: ?page=login');
            exit;
        }
        
        // Enhanced role-based permission check
        $userRole = $_SESSION['id_role'];
        if ($userRole == 6) { // Siswa cannot manage scores
            $_SESSION['error'] = 'Akses ditolak! Siswa tidak dapat mengelola nilai.';
            header('Location: ?page=login');
            exit;
        }
        if ($userRole == 5) { // Walikelas can only view, not create/edit
            $_SESSION['error'] = 'Akses ditolak! Walikelas hanya dapat melihat nilai, tidak dapat mengedit.';
            header('Location: ?page=nilai');
            exit;
        }

        $scoreUsers = $this->user->getUsersWithoutScores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = (int)($_POST['id_user'] ?? $_SESSION['user_id']);
            $subject = trim($_POST['subjects'] ?? '');
            $value = $_POST['value'] ?? null;

            // Validate required fields
            if (empty($subject) || $value === null) {
                $_SESSION['error'] = 'Subject dan nilai harus diisi!';
                header('Location: ?page=nilai&act=add');
                exit;
            }

            // Normalize value to number
            $value = floatval($value);

            // Define allowed subjects
            $allowedSubjects = ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris'];
            if (!in_array($subject, $allowedSubjects)) {
                $_SESSION['error'] = 'Mata pelajaran tidak valid!';
                header('Location: ?page=nilai&act=add');
                exit;
            }
            
            // Check if guru can edit this specific subject
            if (!$this->canEditSubject($userRole, $subject)) {
                $_SESSION['error'] = 'Akses ditolak! Anda hanya dapat mengedit mata pelajaran sesuai dengan role Anda.';
                header('Location: ?page=nilai&act=add');
                exit;
            }

            // Create new score entry
            $defaultScores = [
                'Matematika' => 0,
                'Bahasa Indonesia' => 0,
                'Bahasa Inggris' => 0
            ];
            $defaultScores[$subject] = $value;

            if ($this->score->create($id_user, $defaultScores)) {
                $_SESSION['message'] = "Nilai untuk <strong>$subject</strong> berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = 'Gagal menambahkan nilai!';
            }

            header('Location: ?page=nilai');
            exit;
        }

        require_once dirname(__FILE__) . '/../views/ScoreView.php';
    }

    public function edit($id)
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu!';
            header('Location: ?page=login');
            exit;
        }
        
        // Enhanced role-based permission check
        $userRole = $_SESSION['id_role'];
        if ($userRole == 6) { // Siswa cannot manage scores
            $_SESSION['error'] = 'Akses ditolak! Siswa tidak dapat mengelola nilai.';
            header('Location: ?page=login');
            exit;
        }
        if ($userRole == 5) { // Walikelas can only view, not edit
            $_SESSION['error'] = 'Akses ditolak! Walikelas hanya dapat melihat nilai, tidak dapat mengedit.';
            header('Location: ?page=nilai');
            exit;
        }

        $score = $this->score->getById($id);
        $edit = $this->score->getById($id);
        $users = $this->user->getAll();

        if (!$score) {
            $_SESSION['error'] = 'Data nilai tidak ditemukan!';
            header('Location: ?page=nilai');
            exit;
        }

        // Decode scores for editing
        $scores = json_decode($score['scores'], true) ?: [];
        $possibleSubjects = ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris'];
        $score['subjects'] = 'Matematika';
        $score['value'] = 0;
        foreach ($possibleSubjects as $subj) {
            if (isset($scores[$subj]) && $scores[$subj] != 0) {
                $score['subjects'] = $subj;
                $score['value'] = $scores[$subj];
                break;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = (int)($_POST['id_user'] ?? $_SESSION['user_id']);
            $subject = trim($_POST['subjects'] ?? '');
            $value = $_POST['value'] ?? null;

            // Validate required fields
            if (empty($subject) || $value === null) {
                $_SESSION['error'] = 'Subject dan nilai harus diisi!';
                header('Location: ?page=nilai&act=edit&id=' . $id);
                exit;
            }

            // Normalize value to number
            $value = floatval($value);

            // Validate subject
            $allowedSubjects = ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris'];
            if (!in_array($subject, $allowedSubjects)) {
                $_SESSION['error'] = 'Mata pelajaran tidak valid!';
                header('Location: ?page=nilai&act=edit&id=' . $id);
                exit;
            }
            
            // Check if guru can edit this specific subject
            if (!$this->canEditSubject($userRole, $subject)) {
                $_SESSION['error'] = 'Akses ditolak! Anda hanya dapat mengedit mata pelajaran sesuai dengan role Anda.';
                header('Location: ?page=nilai&act=edit&id=' . $id);
                exit;
            }

            // Update only the specific subject score, preserving others
            if ($this->score->updateSubjectScore($id, $subject, $value)) {
                $_SESSION['message'] = "Nilai untuk <strong>$subject</strong> berhasil diperbarui!";
            } else {
                $_SESSION['error'] = 'Gagal memperbarui nilai!';
            }

            header('Location: ?page=nilai');
            exit;
        }

        require_once dirname(__FILE__) . '/../views/ScoreView.php';
    }

    public function delete($id)
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu!';
            header('Location: ?page=login');
            exit;
        }
        
        // Enhanced role-based permission check
        $userRole = $_SESSION['id_role'];
        if ($userRole == 6) { // Siswa cannot manage scores
            $_SESSION['error'] = 'Akses ditolak! Siswa tidak dapat mengelola nilai.';
            header('Location: ?page=login');
            exit;
        }
        if ($userRole == 5) { // Walikelas can only view, not delete
            $_SESSION['error'] = 'Akses ditolak! Walikelas hanya dapat melihat nilai, tidak dapat menghapus.';
            header('Location: ?page=nilai');
            exit;
        }

        // Handle POST request for delete action
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $deleteType = $_POST['delete_type'] ?? '';
            $subject = $_POST['subject'] ?? '';

            $success = false;
            $message = '';

            // Role-based delete logic
            if ($userRole <= 1) { // Superadmin (0) and Admin (1)
                if ($deleteType === 'all') {
                    $success = $this->score->nullifyAllScores($id);
                    $message = $success ? 'Semua nilai berhasil dihapus!' : 'Gagal menghapus semua nilai!';
                } elseif ($deleteType === 'subject' && !empty($subject)) {
                    $success = $this->score->nullifySubjectScore($id, $subject);
                    $message = $success ? "Nilai $subject berhasil dihapus!" : "Gagal menghapus nilai $subject!";
                } else {
                    $_SESSION['error'] = 'Pilihan hapus tidak valid!';
                    header('Location: ?page=nilai');
                    exit;
                }
            } elseif ($userRole >= 2 && $userRole <= 4) { // Gurus
                // Gurus can only delete their specific subject
                $allowedSubject = $this->getSubjectByRole($userRole);
                if ($subject === $allowedSubject) {
                    $success = $this->score->nullifySubjectScore($id, $subject);
                    $message = $success ? "Nilai $subject berhasil dihapus!" : "Gagal menghapus nilai $subject!";
                } else {
                    $_SESSION['error'] = 'Akses ditolak! Anda hanya dapat menghapus nilai mata pelajaran sesuai role Anda.';
                    header('Location: ?page=nilai');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Akses ditolak! Anda tidak memiliki izin untuk menghapus nilai.';
                header('Location: ?page=nilai');
                exit;
            }

            if ($success) {
                $_SESSION['message'] = $message;
            } else {
                $_SESSION['error'] = $message;
            }
            header('Location: ?page=nilai');
            exit;
        }

        // If GET request, show delete confirmation form
        $score = $this->score->getById($id);
        if (!$score) {
            $_SESSION['error'] = 'Data nilai tidak ditemukan!';
            header('Location: ?page=nilai');
            exit;
        }

        require_once dirname(__FILE__) . '/../views/ScoreView.php';
    }
    
    /**
     * Check if user role can edit specific subject
     * @param int $userRole
     * @param string $subject
     * @return bool
     */
    private function canEditSubject($userRole, $subject)
    {
        // Superadmin (0) and Admin (1) can edit all subjects
        if ($userRole <= 1) {
            return true;
        }
        
        // Subject-specific guru permissions
        switch ($userRole) {
            case 2: // Guru Bahasa Indonesia
                return $subject === 'Bahasa Indonesia';
            case 3: // Guru Bahasa Inggris
                return $subject === 'Bahasa Inggris';
            case 4: // Guru Matematika
                return $subject === 'Matematika';
            case 5: // Walikelas - can only view, not edit
                return false;
            case 6: // Siswa - cannot edit
                return false;
            default:
                return false;
        }
    }

    /**
     * Get subject name by user role
     * @param int $userRole
     * @return string|null
     */
    private function getSubjectByRole($userRole)
    {
        switch ($userRole) {
            case 2: // Guru Bahasa Indonesia
                return 'Bahasa Indonesia';
            case 3: // Guru Bahasa Inggris
                return 'Bahasa Inggris';
            case 4: // Guru Matematika
                return 'Matematika';
            default:
                return null;
        }
    }
}
