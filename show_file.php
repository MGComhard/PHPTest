
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Datei anzeigen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
try {
    if (!isset($_GET['filename'])) {
        throw new Exception("Kein Dateiname angegeben.");
    }

    $filename = basename(trim($_GET['filename']));

    if (preg_match('/[^a-zA-Z0-9_\-\.]/', $filename)) {
        throw new Exception("Ungültiger Dateiname.");
    }

    $filepath = __DIR__ . "/files/" . $filename;

    if (!file_exists($filepath)) {
        throw new Exception("Datei nicht gefunden.");
    }

    $content = file_get_contents($filepath);
    echo "<h3>Inhalt von {$filename}:</h3>";
    echo "<pre>" . htmlspecialchars($content) . "</pre>";
    echo "<p><a href='index.php'>⬅️ Zurück zur Übersicht</a></p>";

} catch (Exception $e) {
    echo "<p>Fehler: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
</body>
</html>