<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'freelancer') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['job_id'])) {
    die("Job ID is required.");
}

$job_id = intval($_GET['job_id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proposal_details = htmlspecialchars($_POST['proposal_details']);
    $proposed_rate = floatval($_POST['proposed_rate']);
    $freelancer_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO proposals (job_id, freelancer_id, proposal_details, proposed_rate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisd", $job_id, $freelancer_id, $proposal_details, $proposed_rate);

    if ($stmt->execute()) {
        echo "Proposal submitted successfully! <a href='dashboard.php'>Back to Dashboard</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Submit Proposal</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Submit Proposal for Job ID: <?php echo $job_id; ?></h2>
        <form method="POST">
            <div class="mb-3">
                <label>Proposal Details</label>
                <textarea name="proposal_details" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>Proposed Rate</label>
                <input type="number" name="proposed_rate" class="form-control" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Proposal</button>
        </form>
    </div>
</body>

</html>