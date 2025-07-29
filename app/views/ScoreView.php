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
if ($_SESSION['id_role'] == 6 && $currentPage !== 'dashboard') { 
    $_SESSION['error'] = 'Akses ditolak! Hanya guru yang dapat mengelola nilai.';
    header('Location: ?page=dashboard');
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

$mapel = [
    "Matematika",
    "Bahasa Inggris",
    "Bahasa Indonesia"
];

// Add actions column to each score record
if (isset($scores) && $currentPage == "nilai") {
    $filterScores = array_map(function ($score) {
        $score['actions'] = "<a class='btn-edit' href='?page=nilai&edit={$score['id']}'></a><a class='btn-delete' href='?page=nilai&delete={$score['id']}' onclick='return confirm(\"Hapus nilai ini?\")'></a>";
        return $score;
    }, $scores);

    $filteredScores = array_map(function ($score) {
        unset($score['id']);
        unset($score['id_user']);
        return $score;
    }, $filterScores);
}
?>

<?php if ($currentPage == 'nilai' && !isset($_GET['edit'])): ?>
    <h2>Manajemen Nilai</h2>
    <a class="btn-add success" style="margin-bottom: 1rem;" href="?page=nilai_create">Tambah Nilai</a>
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
            <?php foreach ($scoreUsers as $user): ?>
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
        <select name="subjects" id="subjectSelect" required>
            <option value="">-- Pilih Mata Pelajaran --</option>
            <?php foreach ($mapel as $subject): ?>
                <option value="<?php echo htmlspecialchars($subject); ?>" <?php echo (isset($edit['subjects']) && $edit['subjects'] == $subject) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($subject); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Nilai:</label>
        <input type="number" name="value" step="0.01" value="<?php echo htmlspecialchars($edit['value'] ?? '', ENT_QUOTES); ?>" required><br>

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
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>