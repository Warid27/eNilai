eNilai — Website Input Nilai Siswa
eNilai adalah aplikasi berbasis web sederhana untuk mengelola input nilai siswa dan galeri gambar, dibuat sebagai tugas sekolah menggunakan PHP Native tanpa framework. Aplikasi ini mengimplementasikan pola MVC (Model-View-Controller) untuk struktur kode yang terorganisir dan mudah dikembangkan.
✨ Fitur Utama

✅ Login SistemOtentikasi pengguna berdasarkan username dan password dengan manajemen sesi.

📊 DashboardMenampilkan ringkasan data dan navigasi cepat ke fitur utama (khusus admin).

🧑‍💼 Level/Role Pengguna  

Admin (id_role: 1): Kelola semua data pengguna, nilai, dan galeri.
Guru (id_role: 2-5): Input dan edit nilai siswa berdasarkan mata pelajaran.
Siswa (id_role: 6): Hanya bisa melihat data nilai dan galeri (terbatas).


✏️ CRUD Nilai Siswa  

Create: Tambah nilai baru untuk siswa.
Read: Lihat daftar nilai siswa dalam tabel.
Update: Edit nilai yang sudah ada.
Delete: Hapus nilai.


🖼️ CRUD Galeri Gambar  

Create: Upload gambar terkait pengguna (misalnya, foto profil atau dokumen).
Read: Tampilkan galeri gambar secara visual.
Update: Ganti gambar yang sudah ada.
Delete: Hapus gambar dari galeri dan server.


🔔 Notifikasi  

Menggunakan SweetAlert2 untuk menampilkan pesan sukses atau gagal secara interaktif.


🔒 Keamanan  

Autentikasi sesi untuk halaman terproteksi seperti dashboard.
Penggunaan prepared statements untuk mencegah SQL injection.
Validasi tipe dan ukuran file untuk upload gambar.
File konfigurasi dan utilitas debugging berada di luar folder publik.



🛠️ Teknologi

Backend: PHP Native (MVC)
Database: MySQL (phpMyAdmin)
Frontend: HTML, CSS, JavaScript (SweetAlert2 via CDN)
API Client: cURL untuk komunikasi antarcontroller
Server: Apache (XAMPP direkomendasikan untuk pengembangan lokal)

🚀 Cara Menjalankan

Clone atau Download RepositoryClone repository ini atau download sebagai ZIP, lalu ekstrak ke folder htdocs (jika menggunakan XAMPP):
git clone <url-repository>


Siapkan Database  

Buat database bernama enilai di phpMyAdmin.
Impor skema database berikut (simpan sebagai database.sql):CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nis INT,
    id_role INT NOT NULL,
    status TINYINT DEFAULT 0,
    FOREIGN KEY (id_role) REFERENCES roles(id)
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role INT NOT NULL
);

CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    subjects VARCHAR(255),
    value INT,
    FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255),
    id_user INT,
    FOREIGN KEY (id_user) REFERENCES users(id)
);

INSERT INTO roles (id, role) VALUES
    (1, 0),
    (2, 1),
    (3, 2),
    (4, 3),
    (5, 4),
    (6, 5);


Jalankan impor:mysql -u root -p enilai < database.sql




Konfigurasi Database  

Buka app/config/AppConfig.php dan sesuaikan kredensial database:$DB_HOST = 'localhost';
$DB_USERNAME = 'root';
$DB_PASSWORD = '';
$DB_NAME = 'enilai';




Konfigurasi Upload Gambar  

Buat folder public/assets/uploads/ untuk menyimpan gambar yang diunggah.
Pastikan folder ini memiliki izin tulis (chmod 755 atau 777 pada server lokal):chmod -R 755 public/assets/uploads/




Jalankan Server  

Jika menggunakan XAMPP, pastikan Apache dan MySQL berjalan, lalu akses http://localhost/eNilai-main/public/.
Alternatif, jalankan server PHP bawaan:php -S localhost:8000 -t public/


Akses di browser: http://localhost:8000.


Uji Aplikasi  

Tambahkan data uji ke tabel users:INSERT INTO users (username, password, nis, id_role) VALUES
('admin', '$2y$10$...', 12345, 1); -- Ganti password dengan hash dari password_hash('admin123', PASSWORD_BCRYPT)


Buka halaman:
http://localhost:8000?page=home (beranda)
http://localhost:8000?page=login (login)
http://localhost:8000?page=nilai (CRUD nilai dengan notifikasi SweetAlert2)
http://localhost:8000?page=dashboard (kelola pengguna, khusus admin)
http://localhost:8000?page=gallery (CRUD galeri dengan notifikasi SweetAlert2)





📁 Struktur Folder
eNilai-main/
├── app/
│   ├── config/
│   │   ├── AppConfig.php        # Konfigurasi aplikasi
│   │   └── Database.php         # Koneksi database
│   ├── controllers/
│   │   ├── AuthController.php   # Autentikasi (login, logout)
│   │   ├── UserController.php   # Pengelolaan pengguna
│   │   ├── ScoreController.php  # Pengelolaan nilai
│   │   └── GalleryController.php # Pengelolaan galeri
│   ├── models/
│   │   ├── User.php             # Model untuk tabel users
│   │   ├── Role.php             # Model untuk tabel roles
│   │   ├── Score.php            # Model untuk tabel scores
│   │   └── Gallery.php          # Model untuk tabel gallery
│   ├── views/
│   │   ├── HomeView.php         # Halaman beranda
│   │   ├── LoginView.php        # Halaman login
│   │   ├── ScoreView.php        # Halaman nilai
│   │   ├── DashboardView.php    # Halaman dashboard
│   │   ├── UserManagementView.php # Manajemen pengguna
│   │   ├── GalleryView.php      # Halaman galeri
│   │   └── NotFoundView.php     # Halaman 404
│   ├── components/
│   │   ├── NavbarComponent.php   # Navigasi atas
│   │   ├── SidebarComponent.php  # Navigasi samping
│   │   ├── TableComponent.php    # Komponen tabel
│   ├── core/
│   │   └── APIClient.php        # Klien API untuk komunikasi antarcontroller
│   └── utils/
│       └── DebugInfo.php        # Utilitas debugging (jangan akses di publik)
├── public/
│   ├── index.php                # Titik masuk aplikasi
│   ├── .htaccess                # Pengaturan server
│   └── assets/
│       ├── css/
│       │   └── style.css        # Styling aplikasi
│       ├── js/                  # JavaScript (opsional)
│       ├── img/
│       │   └── enilai.png       # Gambar logo
│       ├── icon/
│       │   └── enilai.ico       # Ikon aplikasi
│       └── uploads/             # Folder untuk gambar galeri
├── tests/
│   ├── DeleteTest.php           # Tes API DELETE
│   ├── GetTest.php              # Tes API GET
│   ├── PostTest.php             # Tes API POST
│   ├── PutTest.php              # Tes API PUT
├── vendor/                      # Dependensi Composer (opsional)
└── README.md                    # Dokumentasi proyek

⚠️ Catatan Penting

Keamanan:  

Jangan letakkan file DebugInfo.php di folder public/ karena berisi informasi sensitif (phpinfo()). Akses hanya melalui terminal lokal:php app/utils/DebugInfo.php


Pastikan folder public/assets/uploads/ memiliki izin tulis.
Gunakan password_hash() untuk menyimpan password di tabel users.


Notifikasi:  

Aplikasi menggunakan SweetAlert2 via CDN (memerlukan koneksi internet).
Jika tidak ada koneksi, notifikasi akan default ke alert bawaan browser.


Debugging:  

Jika terjadi error, periksa log PHP atau aktifkan mode debug di php.ini (display_errors = On untuk pengembangan lokal).
Periksa path file di require_once dan URL API di APIClient.php.


Dependensi:  

Pastikan ekstensi PHP seperti mysqli, curl, dan fileinfo (untuk upload gambar) diaktifkan di php.ini.
SweetAlert2 dimuat dari CDN: https://cdn.jsdelivr.net/npm/sweetalert2@11.



📝 Kontribusi
Jika ingin menambahkan fitur atau memperbaiki bug:

Fork repository ini.
Buat branch baru (git checkout -b fitur-baru).
Commit perubahan (git commit -m "Menambahkan fitur X").
Push ke branch (git push origin fitur-baru).
Buat Pull Request.

📧 Kontak
Untuk pertanyaan atau laporan bug, hubungi [nama kamu atau email tim].