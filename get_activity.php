<?php
$host = "127.0.0.1";
$port = "3306"; // MySQL port
$dbname = "tours_management"; // Change this to your database name
$username = "root"; // Default username for XAMPP
$password = "";     // Default password for XAMPP (usually empty)

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer to prevent SQL injection

    $query = "SELECT * FROM activities WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            "success" => true,
            "tour_id" => $row['tour_id'],
            "activity_name" => $row['activity_name'],
            "date" => $row['date'],
            "start_time" => $row['start_time'],
            "end_time" => $row['end_time'],
            "description" => $row['description']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "No data found"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>
