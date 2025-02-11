<?php
// Database connection details
$host = "127.0.0.1";
$port = "3306"; // MySQL port
$dbname = "tours_management"; // Change this to your database name
$username = "root"; // Default username for XAMPP
$password = "";     // Default password for XAMPP (usually empty)

// Establish the connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select the database
$conn->select_db($dbname);
if ($conn->error) {
    die("Database selection failed: " . $conn->error);
}

// Loop through all the form fields for multiple activities
$total_activities = count($_POST['activity_name']); // Get the total number of activities

// Start a transaction
$conn->begin_transaction();

try {
    // Prepare the SQL statement for inserting data
    $stmt = $conn->prepare("INSERT INTO activities1 (username, activity_name, date, start_time, end_time, description, tour_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Check for errors in prepared statement
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    // Iterate through all activities and insert them into the database
    for ($i = 0; $i < $total_activities; $i++) {
        // Sanitize inputs for each activity (loop through the arrays)
        $username = mysqli_real_escape_string($conn, $_POST['username'][$i]);
        $activity_name = mysqli_real_escape_string($conn, $_POST['activity_name'][$i]);
        $date = mysqli_real_escape_string($conn, $_POST['date'][$i]);
        $start_time = mysqli_real_escape_string($conn, $_POST['start_time'][$i]);
        $end_time = mysqli_real_escape_string($conn, $_POST['end_time'][$i]);
        $description = mysqli_real_escape_string($conn, $_POST['description'][$i]);
        $tour_id = $_POST['tour_id'][0]; // Assuming tour_id is common for all activities in one session

        // Validate inputs
        if (empty($username) || empty($activity_name) || empty($date) || empty($start_time) || empty($end_time) || empty($description) || empty($tour_id)) {
            throw new Exception("Please fill out all fields.");
        }

        // Bind the parameters
        $stmt->bind_param("sssssss", $username, $activity_name, $date, $start_time, $end_time, $description, $tour_id);

        // Execute the query
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
    }

    // Commit the transaction
    $conn->commit();

    // Success message
    echo 'Activities are added successfully.';

} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $conn->rollback();

    // Error message
    echo "Error: " . $e->getMessage();
}

echo "<script>
alert('Activity has been submitted successfully!');
window.location.href='table.php';
</script>";

// Close the statement and the connection
$stmt->close();
$conn->close();
?>