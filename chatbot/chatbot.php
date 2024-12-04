<?php
header("Content-Type: application/json");

// Chemin vers le fichier CSV
$csvFile = "data.csv";
$data = [];

// Vérifier si le fichier CSV existe et est lisible
if (file_exists($csvFile) && is_readable($csvFile)) {
    // Charger les données du fichier CSV
    if (($handle = fopen($csvFile, "r")) !== false) {
        $headers = fgetcsv($handle); // Lire les en-têtes
        while (($row = fgetcsv($handle)) !== false) {
            $data[$row[0]] = $row[1]; // question => réponse
        }
        fclose($handle);
    }
} else {
    echo json_encode(["response" => "Erreur : Le fichier CSV n'est pas accessible."]);
    exit; // Arrêter l'exécution si le fichier n'est pas accessible
}

// Obtenir la question depuis l'URL
$userQuestion = isset($_GET['question']) ? trim($_GET['question']) : "";

// Rechercher une réponse
$response = isset($data[$userQuestion]) ? $data[$userQuestion] : "Désolé, je ne connais pas la réponse à cette question.";

// Journaliser la conversation
$log = date("Y-m-d H:i:s") . " - Question: " . $userQuestion . " - Réponse: " . $response . "\n";
file_put_contents("log.txt", $log, FILE_APPEND);

// Retourner la réponse au format JSON
echo json_encode(["response" => $response]);
?>