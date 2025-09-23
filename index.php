
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dateiübersicht</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
$dir = __DIR__ . '/files';
$files = is_dir($dir) ? scandir($dir) : [];

echo "<h1>Dateiübersicht</h1>";
echo "<ul>";
foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    echo "<li>
            <a href='show_file.php?filename=" . urlencode($file) . "'>$file</a>
            | <a href='delete_file.php?filename=" . urlencode($file) . "' onclick='return confirm(\"Wirklich löschen?\")'>🗑️ Löschen</a>
          </li>";
}
echo "</ul>";
echo "<a href='create_file.php'>➕ Neue Datei erstellen</a>";
?>
</body>
</html>