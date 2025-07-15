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

<form action="?page=login&action=users" method="POST">
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