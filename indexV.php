<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $destination = $_POST['destination'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $days = $_POST['days'];
    $description = $_POST['description'];

    $sql = "INSERT INTO tour (username, destination, start_date, end_date, days, description) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $username, $destination, $start_date, $end_date, $days, $description);

    if ($stmt->execute()) {
        echo "<script>alert('tour request submitted successfully!'); window.location.href = 'statusV.php';</script>";
    } else {
        echo "<script>alert('Error submitting tour request!');</script>";
    }

    // if ($stmt->execute()) {
    //     header("Location: admin.php");
    //     exit();
    // } else {
    //     echo "Error: " . $stmt->error;
    // }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Submission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      

        body {
            background-color: #f8f9fa;
            /* background-image: url('https://www.shutterstock.com/image-photo/lush-rice-paddy-field-neat-260nw-2499404003.jpg'); */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
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
                    <!-- <li class="nav-item">
                        <a class="nav-link active" href="logout.php">Logout</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h3>Create Tour Request</h3>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Username:</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username"  class="form-control" required >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Destination:</label>
                        <input type="text" id="destination" name="destination" placeholder="Enter destination"  class="form-control" required >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date:</label>
                        <input type="date" id="start_date" name="start_date"  class="form-control" required >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Number of Days:</label>
                        <input type="number" id="days" name="days" class="form-control" required >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description:</label>
                        <input type="text" id="description" name="description" placeholder="Enter your description" rows="4" class="form-control" required >
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="create_tour" class="btn btn-success">Create Request</button>
                        <button type="button" class="btn btn-primary" onclick="window.location.href='employee.php';">Back</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>