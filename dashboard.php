<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destination = $_POST['destination'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $days = $_POST['days'];
    $description = $_POST['description'];

    $sql = "INSERT INTO tour (destination, start_date, end_date, days, description) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssds", $destination, $start_date, $end_date, $days, $description);

    if ($stmt->execute()) {
        // Redirect to admin.php after successful insertion
        header("Location: admin.php");
        exit(); // Prevent further execution
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Tour Request</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e8f5e9;
            color: rgb(19, 23, 19);
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(103, 219, 114));
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }
        .navbar a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }
        .container {
            width: 90%;
            max-width: 600px;
            background: white;
            padding: 20px;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            color: #388e3c;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin: 10px 0 5px;
        }
        input, textarea, button {
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #00b09b;
            border-radius: 5px;
            font-size: 1.5rem;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #96c93d;
        }
        button {
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(103, 219, 114));
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;

        }
        button:hover {
            transform: scale(1.05);
        }
        .message {
            text-align: center;
            font-weight: bold;
            color: rgb(79, 176, 0);
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <a href="#">Employee Dashboard</a>
        <a href="#">Logout</a>
    </div>

    <div class="container">
        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>
        
        <h2>Create Tour Request</h2>
        <form method="post">
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" placeholder="Enter destination" required>

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>

            <label for="days">Number of Days:</label>
            <input type="number" id="days" name="days" placeholder="Enter number of days" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" placeholder="Enter description" required></textarea>

            <button type="submit" name="create_tour">Create Request</button>
        </form>
    </div>

</body>
</html>
