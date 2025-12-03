<?php
function getBDconnexion() {
    // Configuration spécifique pour votre base Render PostgreSQL
    
    $host = 'dpg-d4o0ginpm1nc73fng7dg-a'; // Votre hostname Render
    $port = '5432'; // Port PostgreSQL standard
    $DBname = 'gestion_yyrb'; // Votre nom de base
    $user = 'aman'; // Votre username
    $password = 'y6D8Iou46JBEg30QqRfsALPVd6z8k8Lq'; // Votre mot de passe
    
    try {
        // Connexion PostgreSQL pour Render
        $dsn = "pgsql:host=$host;port=$port;dbname=$DBname";
        
        $conn = new PDO($dsn, $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        return $conn;
    } catch(PDOException $ex) {
        // Enregistrer l'erreur dans les logs
        error_log("Erreur de connexion à la base de données PostgreSQL : " . $ex->getMessage());
        
        // Message adapté selon l'environnement
        if (isset($_SERVER['RENDER'])) {
            // Sur Render, message générique
            die("Erreur de connexion à la base de données. Veuillez vérifier la configuration.");
        } else {
            // En local, message détaillé
            die("Erreur de connexion PostgreSQL : " . $ex->getMessage());
        }
    }
}
?>