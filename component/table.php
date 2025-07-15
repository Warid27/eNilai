<?php
function renderTable(array $data, array $customHeaders = [])
{
    if (empty($data)) {
        echo "<p>No data available</p>";
        return;
    }

    $headers = array_keys($data[0]);
?>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <?php foreach ($headers as $key) {
                ?>
                    <th><?= htmlspecialchars($customHeaders[$key] ?? ucfirst($key))  ?></th>
                <?php
                } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $i => $row) {  ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <?php foreach ($headers as $key) {
                    ?>
                        <td><?= !empty($row[$key]) ? htmlspecialchars($row[$key]) : "-" ?></td>
                    <?php
                    } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php
}
?>