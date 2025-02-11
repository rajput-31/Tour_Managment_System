<?php
session_start();
include 'db.php';

// Handle Reject Request
if (isset($_GET['reject_id']) && isset($_GET['reason'])) {
    $reject_id = intval($_GET['reject_id']); // Sanitize input
    $reason = $conn->real_escape_string($_GET['reason']); // Sanitize reason input

    $sql = "UPDATE expenses SET status = 'Rejected', admin_comment = '$reason' WHERE id = $reject_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to avoid multiple submissions
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Handle Approve Request
if (isset($_GET['approve_id'])) {
    $approve_id = intval($_GET['approve_id']); // Sanitize input

    // Update status to 'Approved' and set admin_comment to NULL
    $sql = "UPDATE expenses SET status = 'Approved', admin_comment = '---' WHERE id = $approve_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to avoid multiple submissions
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch all expenses with status and username (since username is in the expenses table)
$sql = "SELECT * FROM expenses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Expense Review</title>
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
        .btn-sm {
            padding: 6px 12px;
        }
        .action-btns a {
            margin: 0 5px;
        }
        h2 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #007bff;
        }
        .card-body {
            padding: 30px;
        }
        .navbar {
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
        }
        .navbar-nav .nav-link {
            color: white !important;
        }
        .navbar-toggler-icon {
            background-color: white !important;
        }
        .navbar .navbar-brand {
            font-weight: bold;
        }
    </style>
    <script>
        function validateForm() {
            let status = document.getElementById("status").value;
            let reasonField = document.getElementById("reason_field");
            if (status === "Rejected" && reasonField.value.trim() === "") {
                alert("Please provide a reason for rejection.");
                return false;
            }
            return true;
        }

        function confirmReject(expenseId) {
            var reason = prompt("Please provide a reason for rejecting this expense:");

            if (reason !== null && reason.trim() !== "") {
                window.location.href = "admin_confirmation.php?reject_id=" + expenseId + "&reason=" + encodeURIComponent(reason);
            } else if (reason === null) {
                return false;
            } else {
                alert("Please provide a valid reason for rejection.");
                return false;
            }
        }

        function approveExpense(expenseId) {
            window.location.href = "admin_confirmation.php?approve_id=" + expenseId;
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Logout</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 text-center text-primary">Admin Expense Review</h2>

        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Tour ID</th>
                            <th>Username</th> <!-- Displaying username -->
                            <th>Total Cost</th>
                            <th>Receipt</th>
                            <th>Status</th>
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
                                    <?php if (!empty($row['receipt_path'])) { ?>
                                        <a href="<?= htmlspecialchars($row['receipt_path']); ?>" target="_blank" class="btn btn-primary btn-sm">View Receipt</a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php
                                        if ($row['status'] == 'Pending') {
                                            echo '<span class="badge bg-warning">Pending</span>';
                                        } elseif ($row['status'] == 'Approved') {
                                            echo '<span class="badge bg-success">Approved</span>';
                                        } elseif ($row['status'] == 'Rejected') {
                                            echo '<span class="badge bg-danger">Rejected</span>';
                                        }
                                    ?>
                                </td>
                                <td>
    <!-- Always show Approve and Reject buttons -->
    <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="approveExpense(<?= $row['id']; ?>)">Approve</a>
    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="confirmReject(<?= $row['id']; ?>)">Reject</a>
</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div style="text-align: center; margin-top: 20px;">
    <button onclick="window.location.href='admin.php'" 
            style="padding: 10px 20px; background:linear-gradient(90deg, rgb(119, 210, 45), rgb(103, 219, 114)); color: white; border: none; border-radius: 5px; cursor: pointer;">
        Back to Dashboard
    </button>
</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>