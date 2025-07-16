<?php 

include resolvePath("@/component/table.php");
require_once resolvePath("@/lib/api.php");

// Fetch users
try {
    $users = api("/users?params=all");
    if (!empty($users)) {
        $allowedKeys = ['username', 'nis', 'subjects', 'value'];
        $customHeaders = ['username' => 'Nama', 'nis' => 'NIS', 'subjects' => 'Mapel', 'value' => 'Nilai'];
        $filteredUsers = array_map(function ($item) use ($allowedKeys) {
            return array_filter($item, function ($key) use ($allowedKeys) {
                return in_array($key, $allowedKeys);
            }, ARRAY_FILTER_USE_KEY);
        }, $users);
?>
        <h2>Nilai Siswa</h2>
<?php
        renderTable($filteredUsers, $customHeaders);
    }
} catch (Exception $e) {
    $users = [];
    $error = "Error: " . $e->getMessage();
}
?>