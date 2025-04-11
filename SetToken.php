<?php
session_start();

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input['token'])) {
    $_SESSION['jwt_token'] = $input['token'];
    $_SESSION['username'] = $input['username']; // Optional
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "No token provided"]);
}
?>