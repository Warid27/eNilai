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
        if ($_SESSION['id_role'] == 6) {
            $_SESSION['error'] = 'Akses ditolak! Hanya admin yang dapat mengelola nilai.';
            header('Location: ?page=login');
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
        if ($_SESSION['id_role'] == 6) {
            $_SESSION['error'] = 'Akses ditolak! Hanya admin yang dapat mengelola nilai.';
            header('Location: ?page=login');
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

            // Update scores
            $defaultScores = [
                'Matematika' => 0,
                'Bahasa Indonesia' => 0,
                'Bahasa Inggris' => 0
            ];
            $scores = array_merge($defaultScores, $scores);
            $scores[$subject] = $value;

            if ($this->score->update($id, $scores)) {
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
        if ($_SESSION['id_role'] == 6) {
            $_SESSION['error'] = 'Akses ditolak! Hanya admin yang dapat mengelola nilai.';
            header('Location: ?page=login');
            exit;
        }

        if ($this->score->delete($id)) {
            $_SESSION['message'] = 'Nilai berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus nilai!';
        }
        header('Location: ?page=nilai');
        exit;
    }
}
