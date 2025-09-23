
<?php
try {
    if (!isset($_GET['filename'])) throw new Exception("Kein Dateiname angegeben.");
    $filename = basename($_GET['filename']);
    $filepath = __DIR__ . "/files/" . $filename;

    if (!file_exists($filepath)) throw new Exception("Datei nicht gefunden.");
    unlink($filepath);
    header("Location: index.php");
    exit;
} catch (Exception $e) {
    echo "Fehler: " . htmlspecialchars($e->getMessage());
}
?>