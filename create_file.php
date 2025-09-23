
<?php
// Formular anzeigen, wenn keine POST-Daten vorhanden sind (wg. nur Anzeige, wenn Formular nicht gerade verarbeitet wird + Schutz vor mehrfacher Verarbeitung)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<form action="create_file.php" method="POST">
            Dateiname: <input type="text" name="filename" />
            <br />
            Inhalt: <textarea name="content"></textarea>
            <br />
            <input type="submit" value="Datei erstellen" />
          </form>';
    exit;
}

try {
    // Eingaben auslesen und validieren
    $filename = basename(trim($_POST['filename']));
    $content = trim($_POST['content']);
    $forbidden_extensions = ['php', 'phtml', 'php3', 'php4', 'phps', 'asp', 'aspx', 'jsp', 'cgi', 'pl'];
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (in_array($extension, $forbidden_extensions)) {
        throw new Exception("Dateityp '$extension' ist nicht erlaubt.");
    }

    if (empty($filename)) {
        throw new Exception("Dateiname darf nicht leer sein.");
    }

    if (preg_match('/[^a-zA-Z0-9_\-\.]/', $filename)) {
        throw new Exception("Ungültige Zeichen im Dateinamen.");
    }

    // Datei schreiben
    $filepath = __DIR__ . "/files/" . $filename;

    // Ordner erstellen (nur wenn nicht vorhanden)
    if (!is_dir(__DIR__ . "/files")) {
        mkdir(__DIR__ . "/files", 0755, true);
    }

    file_put_contents($filepath, $content);

    echo "Datei erfolgreich erstellt.<br />";
    echo '<a href="show_file.php?filename=' . urlencode($filename) . '">Datei anzeigen</a>';

} catch (Exception $e) {
    echo "Fehler: " . htmlspecialchars($e->getMessage());
}
?>


<!-- kleines HTML- und JS-Snippet für Live-Vorschau -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Datei erstellen</title>
    <link rel="stylesheet" href="style.css">
    <script>
    function updatePreview(text) {
        document.getElementById('preview').textContent = text;
    }
    </script>
</head>
<body>
    <h1>Datei erstellen</h1>
    <?php if ($message): ?>
        <p><b><?= htmlspecialchars($message) ?></b></p>
    <?php endif; ?>

    <form method="POST">
        Dateiname: <input type="text" name="filename" value="<?= htmlspecialchars($filename) ?>" />
        <br />
        Inhalt: <textarea name="content" oninput="updatePreview(this.value)"><?= htmlspecialchars($content) ?></textarea>
        <br />
        <input type="submit" value="Datei erstellen" />
    </form>

    <h3>Vorschau:</h3>
    <pre id="preview" style="border:1px solid #ffffff; padding:1rem;"></pre>
</body>
</html>


<!-- Anmerkungen
- basename() verhindert Directory Traversal (wenn ein Programm Dateipfade aus Benutzereingaben übernimmt, z.B. via Erweiterung statt view?file=?  über view?file= ../../etc/passwd).
- preg_match() filtert unerwünschte Zeichen im Dateinamen.
- htmlspecialchars() schützt vor XSS (Cross Site Scripting = Einschleusen schädlicher JS-Codes, z.B. stehlen von Login-Cookies für Kontoübernahme) beim Anzeigen von Inhalten.
- Dateien werden im Unterordner /files gespeichert
-->
