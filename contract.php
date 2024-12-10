<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['user_role'] == 'client') {
    $proposal_id = intval($_POST['proposal_id']);
    $terms = htmlspecialchars($_POST['terms']);
    $client_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO contracts (proposal_id, client_id, terms, status) VALUES (?, ?, ?, 'active')");
    $stmt->bind_param("iis", $proposal_id, $client_id, $terms);

    if ($stmt->execute()) {
        echo "Contract created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
