<?php
require_once __DIR__ . '/../config/database.php';
$db = getDBConnection();
if ($db) {
    $cols = $db->query("DESCRIBE adventure_progress")->fetchAll();
    echo "Columns in adventure_progress:\n";
    foreach ($cols as $c) {
        echo " - " . $c['Field'] . " (" . $c['Type'] . ")\n";
    }
}
