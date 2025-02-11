<?php
include 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid request.'); window.location.href = 'statusV.php';</script>";
    exit();
}

$tour_id = (int)$_GET['id']; // Get the tour ID from the URL
$sql = "SELECT * FROM tour WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tour_id);
$stmt->execute();
$result = $stmt->get_result();
$tour = $result->fetch_assoc();

if (!$tour) {
    echo "<script>alert('Tour request not found.'); window.location.href = 'statusV.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $destination = $_POST['destination'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $days = $_POST['days'];
    $description = $_POST['description'];

    $update_sql = "UPDATE tour SET username=?, destination=?, start_date=?, end_date=?, days=?, description=? WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssi", $username, $destination, $start_date, $end_date, $days, $description, $tour_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Tour request updated successfully!'); window.location.href = 'statusV.php';</script>";
    } else {
        echo "<script>alert('Error updating tour request!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Tour Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.9);
        }
        .card-header {
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }
        .navbar {
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
        }
        .navbar-nav .nav-link {
            color: white !important;
        }
        .form-label {
            font-weight: 600;
        }
        .btn {
            font-size: 1.1rem;
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
            border: none;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .card-body {
            padding: 2rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Employee Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="statusV.php">Tour Status</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h3>Update Tour Request</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username:</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($tour['username']) ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Destination:</label>
                    <input type="text" name="destination" value="<?= htmlspecialchars($tour['destination']) ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Start Date:</label>
                    <input type="date" name="start_date" value="<?= $tour['start_date'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">End Date:</label>
                    <input type="date" name="end_date" value="<?= $tour['end_date'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Number of Days:</label>
                    <input type="number" name="days" value="<?= $tour['days'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description:</label>
                    <input type="text" name="description" value="<?= htmlspecialchars($tour['description']) ?>" class="form-control" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Update Request</button>
                    <button type="button" class="btn btn-primary" onclick="window.location.href='statusV.php';">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
