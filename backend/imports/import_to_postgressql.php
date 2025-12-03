<?php
// Script d'importation simplifié
// Placez ce fichier dans votre projet et votre export SQL dans le même dossier

// Chemin vers votre fichier de connexion
require_once 'connectBD.php';

function importDatabase($sqlFile) {
    $conn = db_start();
    
    // Vérifier si le fichier existe
    if (!file_exists($sqlFile)) {
        die("❌ Fichier $sqlFile non trouvé");
    }
    
    echo "<h2>Importation en cours...</h2>";
    
    // Lire le fichier SQL
    $sql = file_get_contents($sqlFile);
    
    // Conversions MySQL → PostgreSQL basiques
    $sql = str_replace('`', '"', $sql);
    $sql = str_replace('AUTO_INCREMENT', 'SERIAL', $sql);
    $sql = str_replace('ENGINE=InnoDB', '', $sql);
    $sql = str_replace('int(11)', 'INTEGER', $sql);
    $sql = str_replace('datetime', 'TIMESTAMP', $sql);
    
    // Séparer les requêtes
    $queries = explode(';', $sql);
    
    $success = 0;
    $errors = 0;
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query) && strlen($query) > 10) {
            try {
                $conn->exec($query);
                $success++;
                echo "✓ OK<br>";
            } catch (PDOException $e) {
                $errors++;
                echo "✗ Erreur: " . $e->getMessage() . "<br>";
            }
        }
    }
    
    echo "<h3>Résultat :</h3>";
    echo "<p>✅ $success requêtes réussies</p>";
    echo "<p>❌ $errors erreurs</p>";
}

// Interface web
?>
<!DOCTYPE html>
<html>
<head>
    <title>Importation BD</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .warning { background: #fff3cd; padding: 15px; margin: 15px 0; }
        .danger { background: #f8d7da; padding: 15px; margin: 15px 0; }
    </style>
</head>
<body>
    <h1>Importation vers PostgreSQL Render</h1>
    
    <div class="warning">
        ⚠️ Ce script va écraser les données existantes
    </div>
    
    <div class="danger">
        🔴 SUPPRIMEZ CE FICHIER APRÈS IMPORTATION
    </div>
    
    <?php
    // Lister les fichiers SQL
    $sqlFiles = glob('*.sql');
    if (empty($sqlFiles)) {
        echo "<p>Placez votre fichier .sql dans le même dossier que ce script</p>";
    } else {
        echo "<h3>Fichiers SQL disponibles :</h3>";
        foreach ($sqlFiles as $file) {
            echo "<p>" . basename($file) . "</p>";
        }
    }
    ?>
    
    <form method="post">
        <p>
            <label>Nom du fichier SQL :</label><br>
            <input type="text" name="sqlfile" value="<?php echo !empty($sqlFiles) ? basename($sqlFiles[0]) : 'export.sql'; ?>" style="width: 300px;">
        </p>
        
        <p>
            <input type="checkbox" name="confirm" required>
            <label>Je confirme l'importation</label>
        </p>
        
        <button type="submit" name="action" value="import">Importer</button>
        <button type="submit" name="action" value="test">Tester la connexion</button>
    </form>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['action'] === 'test') {
            echo "<h3>Test de connexion :</h3>";
            try {
                $conn = db_start();
                $stmt = $conn->query("SELECT version() as version");
                $result = $stmt->fetch();
                echo "✅ Connecté à PostgreSQL : " . $result['version'];
            } catch (Exception $e) {
                echo "❌ Erreur : " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'import' && isset($_POST['confirm'])) {
            $sqlFile = $_POST['sqlfile'];
            importDatabase($sqlFile);
        }
    }
    ?>
</body>
</html>