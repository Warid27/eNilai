<?php
function renderTable(array $data, array $customHeaders = [])
{
    if (empty($data)) {
        echo "<p>Tidak ada data tersedia.</p>";
        return;
    }

    // Determine all possible columns
    $staticHeaders = [];
    $jsonKeys = [];

    foreach ($data as $row) {
        $rowHeaders = array_keys($row);
        foreach ($rowHeaders as $key) {
            if ($key === 'scores' && !empty($row[$key]) && is_string($row[$key])) {
                $decoded = json_decode($row[$key], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    foreach ($decoded as $subject => $value) {
                        if (!in_array($subject, $jsonKeys)) {
                            $jsonKeys[] = $subject;
                        }
                    }
                }
            } elseif (!in_array($key, ['scores', 'actions'])) {
                if (!in_array($key, $staticHeaders)) {
                    $staticHeaders[] = $key;
                }
            }
        }
    }

    // Reorder subjects as requested: Matematika → Bahasa Inggris → Bahasa Indonesia
    $preferredOrder = ["Matematika", "Bahasa Inggris", "Bahasa Indonesia"];
    usort($jsonKeys, function ($a, $b) use ($preferredOrder) {
        $posA = array_search($a, $preferredOrder);
        $posB = array_search($b, $preferredOrder);
        if ($posA === false) return 1;
        if ($posB === false) return -1;
        return $posA <=> $posB;
    });

    // Final header order: static + subject columns
    $finalHeaders = array_merge($staticHeaders, $jsonKeys);

    // ✅ Only add 'actions' to headers if it's defined in $customHeaders
    $showActions = isset($customHeaders['actions']);
    if ($showActions) {
        $finalHeaders[] = 'actions';
    }
?>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="text-align: center;">No</th>
                <?php foreach ($finalHeaders as $key): ?>
                    <th style="text-align: center;"><?php
                        echo htmlspecialchars($customHeaders[$key] ?? ucfirst($key));
                        ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $i => $row): ?>
                <tr>
                    <td style="text-align: center;"><?php echo $i + 1; ?></td>
                    <?php foreach ($finalHeaders as $key): ?>
                        <td style="text-align: center;">
                            <?php
                            if ($key === 'actions' && $showActions && !empty($row[$key])) {
                                echo $row[$key]; // Allow HTML links
                            } elseif (in_array($key, $jsonKeys)) {
                                // Extract score from JSON field
                                $scoreValue = '-';
                                if (!empty($row['scores'])) {
                                    $decoded = json_decode($row['scores'], true);
                                    if (json_last_error() === JSON_ERROR_NONE && isset($decoded[$key])) {
                                        $scoreValue = htmlspecialchars($decoded[$key]);
                                    }
                                }
                                echo $scoreValue;
                            } else {
                                // Regular field
                                echo !empty($row[$key]) ? htmlspecialchars($row[$key]) : '-';
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php
}
?>