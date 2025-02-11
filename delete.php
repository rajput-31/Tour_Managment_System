<?php
include 'db.php'; // Database connection

if (isset($_GET['id'])) {
    $tour_id = (int)$_GET['id']; // Get the tour ID and cast it to an integer for security

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM tour WHERE id = ?");
    $stmt->bind_param("i", $tour_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Tour request deleted successfully.');
                window.location.href = 'statusV.php'; 
              </script>";
    } else {
        echo "<script>
                alert('Error deleting request: " . $conn->error . "');
                window.location.href = 'statusV.php'; 
              </script>";
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href = 'statusV.php'; 
          </script>";
}

$conn->close();
?>
