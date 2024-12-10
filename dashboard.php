<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard</title>
</head>

<body>
    <div class="container mt-5">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <p>Your Role: <strong><?php echo ucfirst($user_role); ?></strong></p>

        <?php if ($user_role == 'client'): ?>
            <!-- Client Dashboard -->
            <h2>Your Job Postings</h2>
            <?php
            $stmt = $conn->prepare("SELECT * FROM jobs WHERE client_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Budget</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo ucfirst($row['status']); ?></td>
                                <td>$<?php echo number_format($row['budget'], 2); ?></td>
                                <td>
                                    <a href="view_proposals.php?job_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">View Proposals</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No job postings found. <a href="job_posting.php" class="btn btn-primary">Post a Job</a></p>
            <?php endif;
            $stmt->close();
            ?>
        <?php elseif ($user_role == 'freelancer'): ?>
            <!-- Freelancer Dashboard -->
            <h2>Available Jobs</h2>
            <?php
            $stmt = $conn->prepare("SELECT * FROM jobs WHERE status = 'open'");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Budget</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>$<?php echo number_format($row['budget'], 2); ?></td>
                                <td>
                                    <a href="submit_proposal.php?job_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Submit Proposal</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No jobs available at the moment.</p>
            <?php endif;
            $stmt->close();
            ?>
        <?php endif; ?>
    </div>
</body>

</html>