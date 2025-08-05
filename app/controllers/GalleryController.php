<?php
require_once dirname(__FILE__) . '/../models/Gallery.php';

class GalleryController {
    private $gallery;

    public function __construct() {
        $this->gallery = new Gallery();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu!';
            header('Location: ?page=login');
            exit;
        }
        $images = $this->gallery->getAllImages();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = $_POST['id_user'] ?? $_SESSION['user_id'];
            $id = $_POST['id'] ?? null;
            $upload_dir = dirname(__FILE__) . '/../../public/assets/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            if (!is_writable($upload_dir)) {
                $_SESSION['error'] = 'Folder uploads tidak memiliki izin tulis!';
                header('Location: ?page=gallery');
                exit;
            }
            $file = $_FILES['image'];
            $allowed_types = ['image/jpeg', 'image/png'];
            if (!in_array($file['type'], $allowed_types)) {
                $_SESSION['error'] = 'Hanya file JPEG atau PNG yang diizinkan!';
                header('Location: ?page=gallery');
                exit;
            }
            $filename = time() . '_' . basename($file['name']);
            $target = $upload_dir . $filename;
            $image_path = "public/assets/uploads/" . $filename;
            if (move_uploaded_file($file['tmp_name'], $target)) {
                if ($id) {
                    $old_image = $this->gallery->getImageById($id);
                    if ($old_image && file_exists(dirname(__FILE__) . '/../../' . $old_image['image'])) {
                        unlink(dirname(__FILE__) . '/../../' . $old_image['image']);
                    }
                    if ($this->gallery->updateImage($id, $image_path)) {
                        $_SESSION['message'] = 'Gambar berhasil diperbarui!';
                    } else {
                        $_SESSION['error'] = 'Gagal memperbarui gambar!';
                    }
                } else {
                    if ($this->gallery->uploadImage($id_user, $image_path)) {
                        $_SESSION['message'] = 'Gambar berhasil diunggah!';
                    } else {
                        $_SESSION['error'] = 'Gagal mengunggah gambar!';
                    }
                }
            } else {
                $_SESSION['error'] = 'Gagal mengunggah file!';
            }
            header('Location: ?page=gallery');
            exit;
        }
        if (isset($_GET['delete'])) {
            $id = $_GET['delete'];
            $image = $this->gallery->getImageById($id);
            if ($image && file_exists(dirname(__FILE__) . '/../../' . $image['image'])) {
                unlink(dirname(__FILE__) . '/../../' . $image['image']);
            }
            if ($this->gallery->deleteImage($id)) {
                $_SESSION['message'] = 'Gambar berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Gagal menghapus gambar!';
            }
            header('Location: ?page=gallery');
            exit;
        }
        $edit = null;
        if (isset($_GET['edit'])) {
            $edit = $this->gallery->getImageById($_GET['edit']);
            if (!$edit) {
                $_SESSION['error'] = 'Gambar tidak ditemukan!';
                header('Location: ?page=gallery');
                exit;
            }
        }
        require_once dirname(__FILE__) . '/../views/GalleryView.php';
    }
}