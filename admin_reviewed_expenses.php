<?php
session_start();
include 'db.php';

// Fetch all reviewed expenses (Approved or Rejected)
$sql = "SELECT * FROM expenses WHERE status IN ('Approved', 'Rejected')";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reviewed Expenses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
        /* background-image: url('https://www.shutterstock.com/image-photo/lush-rice-paddy-field-neat-260nw-2499404003.jpg'); */
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

    /* Apply the linear gradient to navbar */
    .navbar {
        background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
    }

    .navbar-nav .nav-link {
        color: white !important;
    }

    .navbar-toggler-icon {
        background-color: white !important;
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                   
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_reviewed_expenses.php">Logout</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Reviewed Expenses</h2>

        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-hover text-center">
                    <thead class="table-dark" >
                        <tr>
                            <th>Tour ID</th>
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
                                <td>₹<?= number_format($row['total_cost'], 2); ?></td>
                                <td>
                                    <span class="badge 
                                        <?= $row['status'] == 'Approved' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?= htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['admin_comment']); ?></td>
                                <td>
                                    <?php if (!empty($row['receipt_path'])) { ?>
                                        <a href="<?= htmlspecialchars($row['receipt_path']); ?>" target="_blank" class="btn btn-primary btn-sm">View Receipt</a>
                                    <?php } ?>
                                </td>
                                <td class="action-btns">
                                    <a href="edit_expenses.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <!-- Back Button -->
                <div class="button-container">
                    <button class="back-button" onclick="location.href='admin.php'">Back</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>