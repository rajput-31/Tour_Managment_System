<?php
// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "tours_management", 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Reject Request
if (isset($_GET['reject_id']) && isset($_GET['reason'])) {
    $reject_id = intval($_GET['reject_id']); // Sanitize input
    $reason = $conn->real_escape_string($_GET['reason']); // Sanitize reason input

    $sql = "UPDATE activities1 SET status = 'Rejected', reason = '$reason' WHERE id = $reject_id";

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

    // Update status to 'Approved' and set reason to NULL
    $sql = "UPDATE activities1 SET status = 'Approved', reason = '---' WHERE id = $approve_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to avoid multiple submissions
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activity List</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 20px;
    }
    h1 {
      text-align: center;
      color: white;
      background-color: rgb(79, 176, 0);
      padding: 1rem;
      text-transform: uppercase;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .card {
      border-radius: 10px;
      border: none;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 20px;
      background: white;
    }
    table {
      width: 80%;
      margin: auto;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }
    th {
      background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
      color: white;
      text-align: center;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .btn {
      padding: 6px 12px;
      border: none;
      cursor: pointer;
      border-radius: 4px;
      text-decoration: none;
      display: inline-block;
    }
    .update-btn {
      background-color: rgb(43, 220, 99) !important;
      color: white !important;
    }
    .delete-btn {
      background-color: red !important;
      color: white !important;
    }
    h2 {
      padding-top: 2%;
      padding-bottom: 2%;
      font-size: 2.5rem;
      font-weight: bold;
      color: black;
      text-align: center;
      margin-bottom: 20px;
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
    /* Updated Action Column */
td:nth-child(10), th:nth-child(10) {
  width: 200px;  /* Increase width of the action column */
}

td:nth-child(10) {
  display: flex;
  justify-content: space-between;  /* Space out the buttons */
  align-items: center;
}
 


.update-btn {
  background-color: rgb(43, 220, 99) !important;
  color: white !important;
}

.delete-btn {
  background-color: red !important;
  color: white !important;
}

  </style>
  <script>
  function confirmReject(id) {
    var reason = prompt("Please enter the reason for rejection:");

    if (reason != null && reason != "") {
      // Redirect to a PHP script that will update the record with the reason
      window.location.href = "?reject_id=" + id + "&reason=" + encodeURIComponent(reason);
    }
  }
  </script>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Admin Panel</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <!-- <li class="nav-item">
            <a class="nav-link" href="dashboard.php">Logout</a>
          </li> -->
        </ul>
      </div>
    </div>
  </nav>

  <h2>Activity Table</h2>
  <div class="card">
    <table>
      <thead>
        <tr>
        <th> ID</th> 
        <th>Username</th>
          <th>Tour ID</th>
          <th>Activity Name</th>
          <th>Start Time</th>
          <th>End Time</th>
          <th>Description</th>
          <th>Status</th>
          <th>Reason</th> <!-- Added column for Reason -->
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM activities1";  // Make sure to fetch the status and reason field
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                         <td>" . $row['id'] . "</td>
                         <td>" . $row['username'] . "</td>
                        <td>" . $row['tour_id'] . "</td>
                        <td>" . $row['activity_name'] . "</td>
                        <td>" . $row['start_time'] . "</td>
                        <td>" . $row['end_time'] . "</td>
                        <td>" . $row['description'] . "</td>
                        <td>" . $row['status'] . "</td>
                        <td>" . $row['reason'] . "</td> <!-- Display Reason here -->
                        <td>
                          <a href='?approve_id=" . $row['id'] . "' class='btn update-btn'>Approve</a>
                          <button onclick='confirmReject(" . $row['id'] . ")' class='btn delete-btn'>Reject</button>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8' style='text-align:center;'>No activities found.</td></tr>";
        }
        $conn->close();
        ?>
      </tbody>
    </table>
    <div style="text-align: center; margin-top: 20px;">
    <button onclick="window.location.href='admin.php'" 
            style="padding: 10px 20px; background:linear-gradient(90deg, rgb(119, 210, 45), rgb(103, 219, 114)); color: white; border: none; border-radius: 5px; cursor: pointer;">
        Back to Dashboard
    </button>
</div>
  </div>
</body>
</html>