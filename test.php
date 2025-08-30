<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Simuler une base de données
$users = [
    ["id" => 1, "name" => "Alice"],
    ["id" => 2, "name" => "Bob"]
];

$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$segments = explode('/', trim($path, '/'));

// Route : /users
if ($segments[0] === 'users') {
    if ($method === 'GET') {
        echo json_encode($users);
    } elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['name'])) {
            $newUser = ["id" => count($users) + 1, "name" => $input['name']];
            $users[] = $newUser;
            echo json_encode($newUser);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Le champ 'name' est requis"]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Méthode non autorisée"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Endpoint non trouvé"]);
}
?>