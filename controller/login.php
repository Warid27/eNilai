<?php
include dirname(__FILE__) . "/../service/users.php";

$userService = new Users($conn);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $userService->loginUser($username);
    if ($user) {
        if ($user && password_verify($password, $user['password'])) {
            if ($user['nis'] === null) {
                header("Location:?page=dashboard&message=" . urlencode("Login berhasil"));
            } else {
                header("Location:?page=login&message=" . urlencode("Siswa tidak bisa login"));
            }
        } else {
            header("Location:?page=login&message=" . urlencode("Login gagal"));
        }
    } else {
        header("Location:?page=login&message=" . urlencode("User tidak ditemukan"));
    }
}
