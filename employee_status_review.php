<?php
session_start();
include 'db.php';

// Fetch all reviewed expenses (Rejected, Approved, and Pending) including username from the expenses table
$sql = "SELECT * FROM expenses WHERE status IN ('Rejected', 'Approved', 'PENDING')";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Status Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .table-dark th {
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
            color: #fff;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #007bff;
        }

        .navbar {
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
        }

        .navbar-nav .nav-link {
            color: white !important;
        }

        .back-button {
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            position: absolute;
            right: 20px;
            bottom: 20px;
        }

        .back-button:hover {
            opacity: 0.9;
        }

        .button-container {
            position: relative;
            height: 60px;
        }

        .no-records {
            text-align: center;
            font-size: 1.2rem;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Employee Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- <li class="nav-item">
                        <a class="nav-link active" href="logout.php">Logout</a>
                    </li> -->
                    <li class="nav-item">
                    <a class="nav-link active" href="employee_status_review.php">Expenses Status</a>
                </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Employee Status Review</h2>
        <div class="card shadow-lg">
            <div class="card-body">
                <?php if ($result->num_rows > 0) { ?>
                    <table class="table table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Tour ID</th>
                                <th>Username</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th>Admin Comment</th>
                                <th>Receipt</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['tour_id']); ?></td>
                                    <td><?= htmlspecialchars($row['username']); ?></td> <!-- Display username -->
                                    <td>₹<?= number_format($row['total_cost'], 2); ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'Approved') { ?>
                                            <span class="badge bg-success"><?= htmlspecialchars($row['status']); ?></span>
                                        <?php } elseif ($row['status'] == 'Rejected') { ?>
                                            <span class="badge bg-danger"><?= htmlspecialchars($row['status']); ?></span>
                                        <?php } else { ?>
                                            <span class="badge bg-warning"><?= htmlspecialchars($row['status']); ?></span>
                                        <?php } ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['admin_comment']); ?></td>
                                    <td>
                                        <?php if (!empty($row['receipt_path'])) { ?>
                                            <a href="<?= htmlspecialchars($row['receipt_path']); ?>" target="_blank" class="btn btn-primary btn-sm">View Receipt</a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="employee_form_edit.php?expense_id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="no-records">
                        No records found for expenses that are approved or rejected.
                    </div>
                <?php } ?>
                <!-- Back Button -->
                <div class="button-container">
                    <button class="back-button" onclick="location.href='expenses.php'">Back</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>