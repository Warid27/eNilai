<?php
session_start();

$base_path = dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$base_path = '/' . trim($base_path, '/') . '/'; // Clean it
define('BASE_PATH', $base_path);

ob_start();

// Prevent redirect loop by checking the current page
$currentPage = $_GET['page'] ?? '';

if (!isset($_SESSION['user_id']) && $currentPage !== 'login') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu!';
    header('Location: ?page=login');
    exit;
}

require_once dirname(__FILE__) . '/../components/TableComponent.php';
$customHeaders = [
    'username' => 'Nama',
    'nis' => 'NIS',
    'subjects' => 'Mata Pelajaran',
    'value' => 'Nilai',
    'actions' => 'Aksi'
];
if ($_SESSION['id_role'] == 6) {
    $customHeaders = [
        'username' => 'Nama',
        'nis' => 'NIS',
        'subjects' => 'Mata Pelajaran',
        'value' => 'Nilai',
    ];
}

echo "ROLE  {$_SESSION['id_role']}";

$mapel = [
    "Matematika",
    "Bahasa Inggris",
    "Bahasa Indonesia"
];
if ($_SESSION['id_role'] == 2) {
    $mapel = [
        "Bahasa Indonesia"
    ];
}
if ($_SESSION['id_role'] == 3) {
    $mapel = [
        "Bahasa Inggris"
    ];
}
if ($_SESSION['id_role'] == 4) {
    $mapel = [
        "Matematika"
    ];
}

// Add actions column to each score record for non-role 6 users
if (isset($scores) && $currentPage == "nilai" && !isset($_GET['edit']) && $_SESSION['id_role'] != 6) {
    $filterScores = array_map(function ($score) {
        $score['actions'] = "<a class='btn-edit' href='?page=nilai&edit={$score['id']}'>Edit</a><a class='btn-delete' href='?page=nilai&delete={$score['id']}' onclick='return confirm(\"Hapus nilai ini?\")'>Hapus</a>";
        return $score;
    }, $scores);

    $filteredScores = array_map(function ($score) {
        unset($score['id']);
        unset($score['id_user']);
        return $score;
    }, $filterScores);
} else if (isset($scores) && $currentPage == "nilai" && !isset($_GET['edit'])) {
    $filteredScores = array_map(function ($score) {
        unset($score['id']);
        unset($score['id_user']);
        return $score;
    }, $scores ?? []);
}
?>

<?php if ($currentPage == 'nilai' && !isset($_GET['edit'])): ?>
    <h2>Manajemen Nilai</h2>
    <?php if ($_SESSION['id_role'] != 6): ?>
        <a class="btn-add success" style="margin-bottom: 1rem;" href="?page=nilai_create">Tambah Nilai</a>
    <?php endif; ?>
    <?php if (isset($filteredScores) && !empty($filteredScores)): ?>
        <?php renderTable($filteredScores, $customHeaders); ?>
    <?php endif; ?>
<?php endif; ?>

<?php if (isset($_GET['edit'])): ?>
    <h2 style="display: flex; flex-direction: row; align-items: center;">
        <a href="?page=nilai" class="btn-back" style="margin-right: 1rem;"></a>Edit Nilai
    </h2>
    <form method="POST" action="?page=nilai&edit=<?php echo $edit['id']; ?>" id="editScoreForm">
        <label>Pengguna:</label>
        <select name="id_user" id="userSelect" required>
            <option value="">-- Pilih Pengguna --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['id']; ?>" <?php echo $edit['id_user'] == $user['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($user['username']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <!-- NIS Field - Initially shown only if user role is 6 -->
        <?php if (isset($edit['nis'])): ?>
            <div id="nisField">
                <label>NIS:</label>
                <input type="number" name="nis" id="nisInput" value="<?php echo htmlspecialchars($edit['nis'] ?? ''); ?>" readonly><br>
            </div>
        <?php else: ?>
            <div id="nisField" style="display:none;">
                <label>NIS:</label>
                <input type="number" name="nis" id="nisInput" value="<?php echo htmlspecialchars($edit['nis'] ?? ''); ?>" readonly><br>
            </div>
        <?php endif; ?>

        <label>Mata Pelajaran:</label>
        <?php if (count($mapel) === 1): ?>
            <input type="text" name="subjects" id="subjectSelect" value="<?php echo htmlspecialchars($mapel[0]); ?>" readonly><br>
        <?php else: ?>
            <select name="subjects" id="subjectSelect" required>
                <option value="">-- Pilih Mata Pelajaran --</option>
                <?php foreach ($mapel as $subject): ?>
                    <option value="<?php echo htmlspecialchars($subject); ?>" <?php echo (isset($edit['subjects']) && $edit['subjects'] == $subject) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($subject); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
        <?php endif; ?>

        <label>Nilai:</label>
        <input type="number" name="value" step="1" min="0" max="100" value="<?php echo htmlspecialchars($edit['value'] ?? '', ENT_QUOTES); ?>" required><br>

        <button type="submit">Update Nilai</button>
    </form>

    <!-- JavaScript to toggle NIS field based on user selection -->
    <script>
        document.getElementById('userSelect').addEventListener('change', function() {
            const nisField = document.getElementById('nisField');
            const nisInput = document.getElementById('nisInput');
            const selectedUserId = this.value;

            // Fetch user role via AJAX or use preloaded data
            // For simplicity, assuming $scoreUsers contains role info
            const userData = <?php echo json_encode($scoreUsers); ?>;
            const selectedUser = userData.find(user => user.id == selectedUserId);

            if (selectedUser && selectedUser.id_role == '6') {
                nisField.style.display = 'block';
                nisInput.value = selectedUser.nis || '';
            } else {
                nisField.style.display = 'none';
                nisInput.value = '';
            }
        });

        // Trigger on page load to set initial state
        document.getElementById('userSelect').dispatchEvent(new Event('change'));
    </script>
<?php elseif ($currentPage == 'nilai_create'): ?>
    <h2 style="display: flex; flex-direction: row; align-items: center;">
        <a href="?page=nilai" class="btn-back" style="margin-right: 1rem;"></a>Tambah Nilai
    </h2>
    <form method="POST" action="?page=nilai_create" id="createScoreForm">
        <label>Pengguna:</label>
        <select name="id_user" id="userSelect" required>
            <option value="">-- Pilih Pengguna --</option>
            <?php foreach ($scoreUsers as $user): ?>
                <option value="<?php echo $user['id']; ?>">
                    <?php echo htmlspecialchars($user['username']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <!-- NIS Field (conditionally shown) -->
        <div id="nisField" style="display: none;">
            <label>NIS:</label>
            <input type="number" name="nis" id="nisInput" readonly><br>
        </div>

        <label>Mata Pelajaran:</label>
        <select name="subjects" id="subjectSelect" required>
            <option value="">-- Pilih Mata Pelajaran --</option>
            <?php foreach ($mapel as $subject): ?>
                <option value="<?php echo htmlspecialchars($subject); ?>">
                    <?php echo htmlspecialchars($subject); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Nilai:</label>
        <input type="number" name="value" step="0.01" required><br>

        <button type="submit">Tambah Nilai</button>
    </form>

    <!-- JavaScript to show/hide NIS based on selected user -->
    <script>
        document.getElementById('userSelect').addEventListener('change', function() {
            const nisField = document.getElementById('nisField');
            const nisInput = document.getElementById('nisInput');
            const selectedUserId = this.value;

            // Fetch user role via AJAX or use preloaded data
            const userData = <?php echo json_encode($scoreUsers); ?>;
            const selectedUser = userData.find(user => user.id == selectedUserId);

            if (selectedUser && selectedUser.id_role == '6') {
                nisField.style.display = 'block';
                nisInput.value = selectedUser.nis || '';
            } else {
                nisField.style.display = 'none';
                nisInput.value = '';
            }
        });

        // Trigger on page load to check initial selection
        document.getElementById('userSelect').dispatchEvent(new Event('change'));
    </script>
<?php elseif (isset($_GET['delete'])): ?>
    <?php
    // Get score data for delete confirmation
    $deleteId = (int)$_GET['delete'];
    $userRole = $_SESSION['id_role'];
    ?>
    <div class="page-header">
        <div class="page-title">
            <h1>
                <a href="?page=nilai" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <i class="fas fa-trash"></i> Hapus Nilai
            </h1>
            <p>Pilih jenis penghapusan nilai</p>
        </div>
    </div>

    <div class="form-container">
        <div class="form-card">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Peringatan:</strong> Tindakan ini akan menghapus (mengosongkan) nilai yang dipilih dan tidak dapat dibatalkan.
            </div>

            <form method="POST" action="?page=nilai&delete=<?= $deleteId ?>" id="deleteScoreForm">
                <?php if ($userRole <= 1): // Superadmin and Admin ?>
                    <div class="form-group">
                        <label>
                            <i class="fas fa-list"></i> Pilihan Penghapusan
                        </label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="delete_type" value="all" required>
                                <span class="radio-custom"></span>
                                <div class="radio-content">
                                    <strong>Hapus Semua Nilai</strong>
                                    <small>Menghapus nilai untuk semua mata pelajaran</small>
                                </div>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="delete_type" value="subject" required>
                                <span class="radio-custom"></span>
                                <div class="radio-content">
                                    <strong>Hapus Nilai Mata Pelajaran Tertentu</strong>
                                    <small>Menghapus nilai untuk mata pelajaran yang dipilih saja</small>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="subjectGroup" style="display: none;">
                        <label for="subject">
                            <i class="fas fa-book"></i> Pilih Mata Pelajaran
                        </label>
                        <select name="subject" id="subject" class="form-control">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            <option value="Matematika">Matematika</option>
                            <option value="Bahasa Indonesia">Bahasa Indonesia</option>
                            <option value="Bahasa Inggris">Bahasa Inggris</option>
                        </select>
                    </div>
                <?php elseif ($userRole >= 2 && $userRole <= 4): // Gurus ?>
                    <?php
                    $allowedSubjects = [
                        2 => 'Bahasa Indonesia',
                        3 => 'Bahasa Inggris', 
                        4 => 'Matematika'
                    ];
                    $guruSubject = $allowedSubjects[$userRole];
                    ?>
                    <input type="hidden" name="delete_type" value="subject">
                    <input type="hidden" name="subject" value="<?= $guruSubject ?>">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Anda akan menghapus nilai untuk mata pelajaran <strong><?= $guruSubject ?></strong> sesuai dengan role Anda.
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <a href="?page=nilai" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Show/hide subject selection based on delete type
        document.querySelectorAll('input[name="delete_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const subjectGroup = document.getElementById('subjectGroup');
                const subjectSelect = document.getElementById('subject');
                
                if (this.value === 'subject') {
                    subjectGroup.style.display = 'block';
                    subjectSelect.required = true;
                } else {
                    subjectGroup.style.display = 'none';
                    subjectSelect.required = false;
                    subjectSelect.value = '';
                }
            });
        });

        // Form validation
        document.getElementById('deleteScoreForm').addEventListener('submit', function(e) {
            const deleteType = document.querySelector('input[name="delete_type"]:checked');
            if (!deleteType) {
                e.preventDefault();
                alert('Silakan pilih jenis penghapusan!');
                return;
            }
            
            if (deleteType.value === 'subject') {
                const subject = document.getElementById('subject');
                if (subject && !subject.value) {
                    e.preventDefault();
                    alert('Silakan pilih mata pelajaran!');
                    return;
                }
            }
            
            // Final confirmation
            const confirmMessage = deleteType.value === 'all' 
                ? 'Apakah Anda yakin ingin menghapus SEMUA nilai?' 
                : `Apakah Anda yakin ingin menghapus nilai ${subject ? subject.value : ''}?`;
                
            if (!confirm(confirmMessage)) {
                e.preventDefault();
            }
        });
    </script>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>