<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['user_role'] == 'client') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $requirements = htmlspecialchars($_POST['requirements']);
    $budget = floatval($_POST['budget']);
    $client_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO jobs (client_id, title, description, requirements, budget) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssd", $client_id, $title, $description, $requirements, $budget);

    if ($stmt->execute()) {
        echo "Job posted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Post a Job</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>Requirements</label>
                <textarea name="requirements" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Budget</label>
                <input type="number" name="budget" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Post Job</button>
        </form>
    </div>
</body>

</html>