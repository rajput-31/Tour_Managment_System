<?php
// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "tours_management", 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // Sanitize input
    $sql = "DELETE FROM activities1 WHERE id = $delete_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to avoid multiple deletions on refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
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
    word-wrap: break-word;
  }
  th {
    background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
    color: white;
  }
  td {
    max-width: 200px; /* Adjust width as needed */
    white-space: normal; /* Allow text wrapping */
  }
  .btn-container {
    display: flex;
    justify-content: center;
    gap: 5px; /* Space between buttons */
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
  </style>
  <script>
    function confirmDelete(id) {
      if (confirm("Are you sure you want to delete this activity?")) {
        window.location.href = "?delete_id=" + id; // Reload same page
      }
    }
  </script>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Employee Panel</a>
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

  <h2>Activity Management Table</h2>
  <div class="card">
    <table>
      <thead>
        <tr>
          <th>Username</th> <!-- Added column for Username -->
          <th>Tour ID</th>
          <th>Activity Name</th>
          <th>Start Time</th>
          <th>End Time</th>
          <th>Description</th>
          <th>Reason</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Fetch data from database including 'username'
        $sql = "SELECT * FROM activities1";  // Ensure 'username' and 'status' columns are included in the query
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['status'];
                $statusColor = "";
                
                // Set status color
                switch (strtolower($status)) {
                    case "pending":
                        $statusColor = "color: orange; ";
                        break;
                    case "approved":
                        $statusColor = "color: green; ";
                        break;
                    case "rejected":
                        $statusColor = "color: red; ";
                        break;
                    default:
                        $statusColor = "color: gray; "; // Default for unknown statuses
                        break;
                }

                echo "<tr>
                        <td>" . $row['username'] . "</td> <!-- Display Username -->
                        <td>" . $row['tour_id'] . "</td>
                        <td>" . $row['activity_name'] . "</td>
                        <td>" . $row['start_time'] . "</td>
                        <td>" . $row['end_time'] . "</td>
                        <td>" . $row['description'] . "</td>
                        <td>" . $row['reason'] . "</td>
                        <td><span style='padding: 6px 12px; border-radius: 5px; font-weight: bold; $statusColor'>" . $status . "</span></td>
                        <td>
                          <a href='updatebutton.php?id=" . $row['id'] . "' class='btn update-btn'>Update</a>
                          <button onclick='confirmDelete(" . $row['id'] . ")' class='btn delete-btn'>Delete</button>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='9' style='text-align:center;'>No activities found.</td></tr>"; // Updated colspan to 9
        }
        $conn->close();
        ?>
      </tbody>
    </table>
    <div style="text-align: center; margin-top: 20px;">
    <button onclick="window.location.href='employee.php'" 
            style="padding: 10px 20px; background:linear-gradient(90deg, rgb(119, 210, 45), rgb(103, 219, 114)); color: white; border: none; border-radius: 5px; cursor: pointer;">
        Back to Dashboard
    </button>
</div>
  </div>
</body>
</html>