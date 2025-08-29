<?php
session_start();

$response = [
    'loggedIn' => false,
    'userName' => null
];

if (isset($_SESSION['username'])) {
    $response['loggedIn'] = true;
    $response['userName'] = $_SESSION['username'];
}

echo json_encode($response);
?>
