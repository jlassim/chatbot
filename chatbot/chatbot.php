<?php
header("Content-Type: application/json");

// Chemin vers le fichier CSV
$csvFile = "data.csv";
$data = [];

// Charger les données du fichier CSV
if (($handle = fopen($csvFile, "r")) !== false) {
    $headers = fgetcsv($handle); // Lire les en-têtes
    while (($row = fgetcsv($handle)) !== false) {
        $data[$row[0]] = $row[1]; // question => réponse
    }
    fclose($handle);
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