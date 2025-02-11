<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['tour_id'])) {
    $tour_id = (int)$_POST['tour_id'];
    $status = ($_POST['action'] === 'approve') ? 'approved' : 'rejected';
    $reason = isset($_POST['rejection_reason']) ? trim($_POST['rejection_reason']) : '';

    // Ensure rejection reason is required when rejecting
    if ($status === 'rejected' && empty($reason)) {
        echo "<script>
                alert('Rejection reason is required!');
                window.location.href='admin.php'; 
              </script>";
        exit();
    }

    // Update tour status with optional rejection reason
    $stmt = $conn->prepare("UPDATE tour SET status=?, rejection_reason=? WHERE id=?");
    $stmt->bind_param("ssi", $status, $reason, $tour_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Tour request has been $status successfully.');
                window.location.href='admin.php'; 
              </script>";
    } else {
        echo "<script>
                alert('Error updating request: " . addslashes($conn->error) . "');
                window.location.href='admin.php'; 
              </script>";
    }

    $stmt->close();
}

// Fetch all tour requests **after processing approvals/rejections**
$result = $conn->query("SELECT * FROM tour");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Tour Request Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: linear-gradient(90deg, rgb(119, 210, 45), rgb(103, 219, 114)); }
        .approved { color: green; font-weight: bold; }
        .rejected { color: red; font-weight: bold; }
        .pending { color: orange; font-weight: bold; }
        .navbar {
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
        }
        .navbar-nav .nav-link {
            color: white !important;
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

<h2 style="text-align: center; color:green">Your Tour Requests</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Destination</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Days</th>
        <th>Description</th>
        <th>Reason</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['destination'] ?></td>
            <td><?= $row['start_date'] ?></td>
            <td><?= $row['end_date'] ?></td>
            <td><?= $row['days'] ?></td>
            <td><?= $row['description'] ?></td>
            <td><?= $row['rejection_reason'] ?? '-' ?></td>
            <td class="<?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></td>
            <td>
                <a href="updatebuttonV.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Update</a>
                <button onclick="confirmDelete(<?= $row['id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
            </td>
        </tr>
    <?php } ?>
</table>

<div style="text-align: center; margin-top: 20px;">
    <button onclick="window.location.href='indexV.php'" 
            style="padding: 10px 20px; background:linear-gradient(90deg, rgb(119, 210, 45), rgb(103, 219, 114)); color: white; border: none; border-radius: 5px; cursor: pointer;">
        Back to Dashboard
    </button>
</div>

<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this tour request?")) {
            window.location.href = 'delete.php?id=' + id;
        }
    }

    function rejectTour(tourId) {
        let reason = prompt("Enter the rejection reason:");
        if (reason !== null && reason.trim() !== "") {
            let form = document.createElement("form");
            form.method = "POST";
            form.action = "statusV.php";

            let tourInput = document.createElement("input");
            tourInput.type = "hidden";
            tourInput.name = "tour_id";
            tourInput.value = tourId;
            form.appendChild(tourInput);

            let actionInput = document.createElement("input");
            actionInput.type = "hidden";
            actionInput.name = "action";
            actionInput.value = "reject";
            form.appendChild(actionInput);

            let reasonInput = document.createElement("input");
            reasonInput.type = "hidden";
            reasonInput.name = "rejection_reason";
            reasonInput.value = reason;
            form.appendChild(reasonInput);

            document.body.appendChild(form);
            form.submit();
        } else {
            alert("Rejection reason is required!");
        }
    }
</script>

</body>
</html>
