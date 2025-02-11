<?php
// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "tours_management", 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data for the activity to be updated
if (isset($_GET['id'])) {
    $activity_id = intval($_GET['id']); // Sanitize the input
    $sql = "SELECT * FROM activities1 WHERE id = $activity_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $activity = $result->fetch_assoc();
    } else {
        echo "Activity not found!";
        exit();
    }
} else {
    echo "Invalid request!";
    exit();
}

// Check if the form is submitted for updating the activity
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Loop through each activity data to update it
    foreach ($_POST['tour_id'] as $index => $tour_id) {
        // Sanitize input
        $tour_id = $conn->real_escape_string($tour_id);
        $activity_name = $conn->real_escape_string($_POST['activity_name'][$index]);
        $date = $conn->real_escape_string($_POST['date'][$index]);
        $start_time = $conn->real_escape_string($_POST['start_time'][$index]);
        $end_time = $conn->real_escape_string($_POST['end_time'][$index]);
        $description = $conn->real_escape_string($_POST['description'][$index]);

        // Update the activity record
        $update_sql = "UPDATE activities1 SET 
                        tour_id = '$tour_id', 
                        activity_name = '$activity_name', 
                        date = '$date', 
                        start_time = '$start_time', 
                        end_time = '$end_time', 
                        description = '$description' 
                       WHERE id = $activity_id";

        if ($conn->query($update_sql) === TRUE) {
            header("Location: table.php");
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    // Optionally, redirect after the update is done
    // header("Location: dashboard.php"); // Uncomment this line to redirect after update
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activity Management Form</title>
  <style>
    /* General Styling */
    html {
      font-family: Arial, sans-serif;
    }

    body {
      margin: 0;
      background-color: #f4f4f9;
    }

    h1 {
      font-size: 2rem;
      margin: 0;
      padding: 1.5rem 0;
      background-color: rgb(79, 176, 0);
      color: white;
      text-align: center;
      text-transform: uppercase;
      width: 100%;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    .logout-link {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: white;
      text-decoration: none;
      font-size: 1.2rem;
      font-weight: bold;
    }

    .logout-link:hover {
      color: rgb(255, 200, 0);
    }

    form {
      width: 35%;
      margin: 2rem auto;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 2rem;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.5rem;
      border: 1px solid rgb(79, 176, 0);
      border-radius: 8px;
      padding: 1rem;
      background: #f9f9f9;
      display: flex;
      flex-wrap: wrap;
    }

    .form-group > h2 {
      margin: 0 0 1rem;
      font-size: 1.5rem;
      color: rgb(79, 176, 0);
      border-bottom: 2px solid #ccc;
      padding-bottom: 0.5rem;
      width: 100%;
    }

    .label {
      font-weight: bold;
      display: inline-block;
      margin-bottom: 0.5rem;
      color: #333;
      font-size: 1rem;
      width: 100%;
      max-width: 150px;
    }

    .input-group {
      margin-bottom: 1rem;
      width: 100%;
    }

    .input-group input,
    .input-group textarea {
      width: 98%;
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #f9f9f9;
    }

    .input-group textarea {
      resize: vertical;
      min-height: 80px;
    }

    .input-group.inline {
      display: flex;
      justify-content: space-between;
      gap: 1rem;
      width: 98%;
    }

    .input-group.inline > div {
      flex: 1;
    }

    input:invalid,
    textarea:invalid {
      border: 2px solid red;
    }

    .control-group {
      display: flex;
      justify-content: flex-end;
      align-items: end;
      margin-top: 2rem;
    }

    .control-group > span {
      font-size: 1.1rem;
      font-weight: bold;
      color: rgb(79, 176, 0);
    }

    .control-group button {
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      border: none;
      border-radius: 5px;
      background-color: rgb(79, 176, 0);
      color: white;
      cursor: pointer;
      align-items: center;
      justify-content: flex-end;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s;
    }

    .control-group button:hover {
      background-color: rgb(79, 176, 0);
     
    }

    .remove-btn {
      position: absolute;
      top: 0;
      right: 0;
      margin-top: 1rem;
      margin-right: 1rem;
      padding: 0.25rem 0.75rem;
      background-color: rgb(250, 248, 248);
      color: rgb(231, 27, 27);
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
    }

    .remove-btn:hover {
      color: #d20202;
    }

    @media (max-width: 768px) {
      form {
        width: 90%;
      }

      .input-group.inline {
        flex-direction: column;
      }

      .control-group {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
      }

      .form-group > h2 {
        text-align: center;
      }
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
    #et{
      padding-top: 2%;
      text-align: center;
    }
  </style>

  <!-- Bootstrap CSS (for navbar) -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>

 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Employee Panel</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

<body>
  <h2 id="et">Activity Management Form</h2>
  <form method="POST" action="">
    <div class="form-group" id="activity-form-0">
      <h2>Activity 1</h2>
      <!-- Tour ID -->
      <div class="input-group">
        <label class="label" for="id_example-0-tour_id">Tour ID</label>
        <input type="text" name="tour_id[]" id="id_example-0-tour_id" value="<?php echo htmlspecialchars($activity['tour_id']); ?>">
      </div>

      <!-- Activity Name -->
      <div class="input-group">
        <label class="label" for="id_example-0-activity_name">Activity Name</label>
        <input type="text" name="activity_name[]" id="id_example-0-activity_name" value="<?php echo htmlspecialchars($activity['activity_name']); ?>">
      </div>

      <!-- Date, Start Time, and End Time -->
      <div class="input-group inline">
        <div>
          <label for="id_example-0-date">Date</label>
          <input type="date" name="date[]" id="id_example-0-date" value="<?php echo htmlspecialchars($activity['date']); ?>">
        </div>
        <div>
          <label for="id_example-0-start_time">Start Time</label>
          <input type="time" name="start_time[]" id="id_example-0-start_time" value="<?php echo htmlspecialchars($activity['start_time']); ?>">
        </div>
        <div>
          <label for="id_example-0-end_time">End Time</label>
          <input type="time" name="end_time[]" id="id_example-0-end_time" value="<?php echo htmlspecialchars($activity['end_time']); ?>">
        </div>
      </div>

      <!-- Description -->
      <div class="input-group">
        <label class="label" for="id_example-0-description">Description</label>
        <textarea name="description[]" id="id_example-0-description" maxlength="2500"><?php echo htmlspecialchars($activity['description']); ?></textarea>
      </div>

      <!-- Remove Button -->
      <button type="button" class="remove-btn" onclick="removeActivity(0)">
        <i class="fas fa-trash"></i>
      </button>
    </div>

    <div class="control-group">
      
      <div>
        
        <button type="submit" class="submit-btn" onclick="validateForm(event)">Update</button>
      </div>
    </div>
  </form>

  <script>
    function removeActivity(index) {
      const activityForm = document.getElementById(`activity-form-${index}`);
      activityForm.remove();
      updateActivityCount();
    }

    function updateActivityCount() {
      const activityCount = document.querySelectorAll('.form-group').length;
      document.getElementById('activity-count').textContent = `Total Activities: ${activityCount}`;
    }

    function validateForm(event) {
      const formGroups = document.querySelectorAll(".form-group");
      let isValid = true;
      let errorMessage = "";
      let tourIdValue = null;

      formGroups.forEach((group, index) => {
        const activityNumber = index + 1;
        const tourIdInput = group.querySelector("input[name='tour_id[]']");
        const activityNameInput = group.querySelector("input[name='activity_name[]']");
        const dateInput = group.querySelector("input[name='date[]']");
        const startTimeInput = group.querySelector("input[name='start_time[]']");
        const endTimeInput = group.querySelector("input[name='end_time[]']");
        const descriptionInput = group.querySelector("textarea[name='description[]']");

        const tourId = tourIdInput.value.trim();
        const activityName = activityNameInput.value.trim();
        const date = dateInput.value.trim();
        const startTime = startTimeInput.value.trim();
        const endTime = endTimeInput.value.trim();
        const description = descriptionInput.value.trim();

        // Reset border color
        tourIdInput.style.borderColor = '';
        activityNameInput.style.borderColor = '';
        dateInput.style.borderColor = '';
        startTimeInput.style.borderColor = '';
        endTimeInput.style.borderColor = '';
        descriptionInput.style.borderColor = '';

        if (!tourId || !activityName || !date || !startTime || !endTime || !description) {
          isValid = false;
          errorMessage += `Please fill out all fields for Activity ${activityNumber}.\n`;

          // Highlight the empty fields in red
          if (!tourId) tourIdInput.style.borderColor = 'red';
          if (!activityName) activityNameInput.style.borderColor = 'red';
          if (!date) dateInput.style.borderColor = 'red';
          if (!startTime) startTimeInput.style.borderColor = 'red';
          if (!endTime) endTimeInput.style.borderColor = 'red';
          if (!description) descriptionInput.style.borderColor = 'red';
        }

        // Check if the Tour ID is the same across all activities
        if (tourIdValue === null) {
          tourIdValue = tourId;
        } else if (tourId !== tourIdValue) {
          isValid = false;
          errorMessage += `Tour ID must be the same for all activities. Mismatch in Activity ${activityNumber}.\n`;
          tourIdInput.style.borderColor = 'red';
        }
      });

      if (!isValid) {
        event.preventDefault();
        alert(errorMessage);
      }
    }
  </script>

</body>
</html>
