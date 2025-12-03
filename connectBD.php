<?php
function getBDconnexion() {
    // Détection automatique de l'environnement
    $isRender = isset($_SERVER['RENDER']) || getenv('RENDER');
    
    if ($isRender) {
        // CONFIGURATION RENDER (PostgreSQL)
        $host = 'dpg-d4o0ginpm1nc73fng7dg-a';
        $port = '5432';
        $DBname = 'gestion_yyrb';
        $user = 'aman';
        $password = 'y6D8Iou46JBEg30QqRfsALPVd6z8k8Lq';
        $dsn = "pgsql:host=$host;port=$port;dbname=$DBname";
    } else {
        // CONFIGURATION LOCALE (MySQL)
        $host = 'localhost';
        $DBname = 'gestion_stock';
        $user = 'root';
        $password = '';
        $dsn = "mysql:host=$host;dbname=$DBname;charset=utf8";
    }
    
    try {
        $conn = new PDO($dsn, $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        // Pour MySQL local uniquement
        if (!$isRender) {
            $conn->exec("SET time_zone = '+00:00'");
        }
        
        return $conn;
    } catch(PDOException $ex) {
        // Enregistrement dans les logs
        error_log("Erreur connexion DB: " . $ex->getMessage());
        
        // Message utilisateur adapté
        if ($isRender) {
            die("Erreur de connexion à la base de données sur Render. Contactez l'administrateur.");
        } else {
            die("Erreur de connexion locale : " . $ex->getMessage());
        }
    }
}

// Alias pour compatibilité avec votre code existant
function db_connect() {
    return getBDconnexion();
}

function db_start() {
    // Cette fonction semble être appelée dans votre index.php
    $conn = getBDconnexion();
    return $conn;
}
?>