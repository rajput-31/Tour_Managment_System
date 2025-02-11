<?php
include 'db.php';

// if ($stmt->execute()) {
//     echo "<script>
//             alert('Tour request has been  successfully.');
//           </script>";
//     exit(); 
// }
// Fetch all pending tour requests
$result = $conn->query("SELECT * FROM tour WHERE status = 'pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tour Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        button { padding: 5px 10px; margin: 5px; border: none; cursor: pointer; }
        .approve { background: green; color: white; }
        .reject { background: red; color: white; }
        .navbar {
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
        }

        .navbar-nav .nav-link {
            color: white !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                   
                </li>
            </ul>
        </div>
    </div>
</nav>

<h2 style="padding:20px"><span Style="color:green">Admin Panel - Pending Tour Requests</span></h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Destination</th>
        <th>Start Date</th>
        <th>End Date</th> <!-- Added End Date Column -->
        <th>Days</th>
        <th>Description</th>
        <th>Reason</th>
        <th>Action</th>
    </tr>
    
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['destination'] ?></td>
            <td><?= $row['start_date'] ?></td>
            <td><?= $row['end_date'] ?></td> <!-- Displaying End Date -->
            <td><?= $row['days'] ?></td>
            <td><?= $row['description'] ?></td>
            <td><?= $row['rejection_reason'] ?? '-' ?></td> <!-- Display Rejection Reason -->
            <td>
                <form method="post" action="statusV.php">
                    <input type="hidden" name="tour_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="rejectTour(<?= $row['id'] ?>)">Reject</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
<div style="text-align: center; margin-top: 20px;">
    <button onclick="window.location.href='admin.php'" 
            style="padding: 10px 20px; background:linear-gradient(90deg, rgb(119, 210, 45), rgb(103, 219, 114));; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Back to Dashboard
    </button>
</div>
</body>
</html>
<script>
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
