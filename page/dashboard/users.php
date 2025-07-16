<?php
require_once dirname(__FILE__) . "/../../lib/api.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $data = [
      'username' => $_POST['username'] ?? '',
      'password' => $_POST['password'] ?? '',
      'nis' => $_POST['nis'] ?? null,
      'id_role' => $_POST['id_role'] ?? '6' // Default to string '6' to match form expectations
    ];

    // Validate required fields
    if (empty($data['username']) || empty($data['password'])) {
      throw new Exception('Username and password are required');
    }

    // Call API
    $response = api('/users', 'POST', $data);

    // Check response (assuming API returns JSON)
    $responseData = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE || !isset($responseData['status'])) {
      throw new Exception('Invalid API response');
    }

    // Redirect on success
    header('Location: ?page=login&message=' . urlencode('User created'));
    exit;
  } catch (Exception $e) {
    // Redirect with error
    header('Location: ?page=login&message=' . urlencode('Error: ' . $e->getMessage()));
    exit;
  }
}
?>

<?php
$roles = [
  1 => 'Admin',
  2 => 'Guru Bahasa Indonesia',
  3 => 'Guru Bahasa Inggris',
  4 => 'Guru Matematika',
  5 => 'Wali kelas',
  6 => 'Siswa'
];
?>

<form method="POST" action="">
  <label>Username:</label>
  <input type="text" name="username" required><br>

  <label>Password:</label>
  <input type="password" name="password" required><br>

  <label>NIS (optional):</label>
  <input type="text" name="nis"><br>

  <label>Role:</label>
  <select name="id_role" required>
    <?php foreach ($roles as $key => $value): ?>
      <option value="<?= $key ?>"><?= htmlspecialchars($value) ?></option>
    <?php endforeach; ?>
  </select><br>


  <button type="submit">Tambah Pengguna</button>
</form>