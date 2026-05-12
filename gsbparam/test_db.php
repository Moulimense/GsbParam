<?php
$pdo = new PDO('mysql:host=localhost:3307;dbname=gsb_paramv2', 'util_gsb', 'MdpGrvSecur123!');
$stmt = $pdo->query('SHOW TABLES');
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
print_r($tables);
foreach ($tables as $table) {
    echo "\nTABLE: $table\n";
    $stmt2 = $pdo->query("DESCRIBE $table");
    print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
}
?>
