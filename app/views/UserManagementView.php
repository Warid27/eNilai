<?php
session_start();

$base_path = dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$base_path = '/' . trim($base_path, '/') . '/'; // Clean it
define('BASE_PATH', $base_path);


ob_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu!';
    header('Location: ?page=login');
    exit;
}
if ($_SESSION['id_role'] != 1) {
    $_SESSION['error'] = 'Akses ditolak! Hanya admin yang dapat mengelola pengguna.';
    header('Location: ?page=dashboard');
    exit;
}
require_once dirname(__FILE__) . '/../components/TableComponent.php';

$customHeaders = [
    'username' => 'Username',
    'nis' => 'NIS',
    'role' => 'Role',
    'actions' => 'Aksi'
];

if (isset($users)) {
    // Add actions and keep ID in data, but don't display it
    $filterUsers = array_map(function ($user) {
        $user['actions'] = "<a class='btn-edit' href='?page=user&edit={$user['id']}'></a><a class='btn-delete' href='?page=user&delete={$user['id']}' onclick='return confirm(\"Hapus pengguna ini?\")'></a>";
        return $user;
    }, $users);

    // Remove 'id' from each user array for display, but only in the output
    $filteredUsers = array_map(function ($user) {
        unset($user['id']); // Remove ID before displaying
        unset($user['id_role']); // Remove ID before displaying
        return $user;
    }, $filterUsers);
}
?>
<?php if (isset($_GET['page']) && $_GET['page'] == 'user' && !isset($_GET['edit'])) {
?>
    <h2>Manajemen Pengguna</h2>
    <a class="btn-add success" style="margin-bottom: 1rem;" href="?page=user_create">Tambah Pengguna</a>
    <?php if (isset($users) && isset($filteredUsers)) {
    ?>
        <?php renderTable($filteredUsers, $customHeaders); ?>
    <?php
    } ?>
<?php
} ?>
<?php if (isset($_GET['edit'])): ?>
    <h2 style="display: flex; flex-direction: row; align-items: center;"><a href="?page=user" class="btn-back" style="margin-right: 1rem;"></a>Edit Pengguna</h2>
    <form method="POST" action="?page=user&edit=<?php echo $user['id']; ?>" id="editUserForm">
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

        <label>Password (kosongkan jika tidak ingin mengubah):</label>
        <input type="text" name="password" value=""><br>

        <!-- NIS Field - Initially shown only if current role is 6 -->
        <?php if ($user['id_role'] == 6): ?>
            <div id="nisField">
                <label>NIS:</label>
                <input type="number" name="nis" value="<?php echo htmlspecialchars($user['nis'] ?? ''); ?>" required><br>
            </div>
        <?php else: ?>
            <div id="nisField" style="display:none;">
                <label>NIS:</label>
                <input type="number" name="nis" value="<?php echo htmlspecialchars($user['nis'] ?? ''); ?>" id="nisInput"><br>
            </div>
        <?php endif; ?>

        <label>Role:</label>
        <select name="id_role" id="roleSelect" required>
            <?php foreach ($roles as $role): ?>
                <option value="<?php echo $role['id']; ?>" <?php echo $user['id_role'] == $role['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($role['role']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Update Pengguna</button>
    </form>

    <!-- JavaScript to toggle NIS field based on role -->
    <script>
        document.getElementById('roleSelect').addEventListener('change', function() {
            const nisField = document.getElementById('nisField');
            const nisInput = document.getElementById('nisInput');

            if (this.value == '6') {
                nisField.style.display = 'block';
                nisInput.required = true;
            } else {
                nisField.style.display = 'none';
                nisInput.required = false;
            }
        });
    </script>
<?php elseif (isset($_GET['page']) && $_GET['page'] == 'user_create'): ?>
    <h2 style="display: flex; flex-direction: row; align-items: center;"><a href="?page=user" class="btn-back" style="margin-right: 1rem;"></a>Tambah Pengguna</h2>
    <form method="POST" action="?page=user_create" id="createUserForm">
        <label>Username:</label>
        <input type="text" name="username" required><br>

        <label>Password:</label>
        <input type="text" name="password" required><br>

        <!-- NIS Field (conditionally shown) -->
        <div id="nisField" style="display: none;">
            <label>NIS:</label>
            <input type="number" name="nis" id="nisInput"><br>
        </div>

        <label>Role:</label>
        <select name="id_role" id="roleSelect" required>
            <?php foreach ($roles as $role): ?>
                <option value="<?php echo $role['id']; ?>">
                    <?php echo htmlspecialchars($role['role']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Tambah Pengguna</button>
    </form>

    <!-- JavaScript to show/hide NIS based on selected role -->
    <script>
        document.getElementById('roleSelect').addEventListener('change', function() {
            const nisField = document.getElementById('nisField');
            const nisInput = document.getElementById('nisInput');

            if (this.value == '6') {
                nisField.style.display = 'block';
                nisInput.required = true;
            } else {
                nisField.style.display = 'none';
                nisInput.required = false;
            }
        });

        // Trigger on page load to check initial role selection (though default is none)
        // If you want to pre-check, you can set default role via PHP if needed
        document.getElementById('roleSelect').dispatchEvent(new Event('change'));
    </script>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>