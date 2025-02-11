<?php
// Start the session
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: ../login.php");
    exit();
}

// Get the logged-in username
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert for alerts -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            background-color: #f4f4f4;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(90deg, rgb(79, 176, 0), rgb(68, 219, 83));
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 10px 20px;
        }

        .navbar-brand {
            font-weight: bold;
            color: white;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .dropdown-menu {
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard {
            display: flex;
            gap: 20px;
            justify-content: center;
            align-items: center;
            background: #ffffff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            width: 100%;
            margin-top: 80px;
            flex-wrap: wrap;
        }

        .card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            width: 300px;
            padding: 20px;
            text-align: center;
            transition: transform 0.4s ease-out, box-shadow 0.4s ease-out;
            height: 250px;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            margin-bottom: 15px;
            color: #4CAF50;
            font-size: 24px;
        }

        .card p {
            color: #555;
        }

        .btn-custom {
            background: linear-gradient(to bottom right, #32a852, #8bc34a);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.4s, opacity 0.4s;
        }

        .btn-custom:hover {
            opacity: 0.85;
            background: linear-gradient(to bottom right, #8bc34a, #32a852);
        }

        @media (max-width: 768px) {
            .dashboard {
                flex-direction: column;
            }

            .card {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<!-- Navbar with Profile Dropdown -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">User Panel</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <!-- <img src="https://via.placeholder.com/40" class="rounded-circle" alt="User Image"> Profile Image -->
                        <span class="welcome-message">Welcome, <?php echo htmlspecialchars($username); ?></span> <!-- Display Username -->
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <!-- <li><a class="dropdown-item" href="profile.html">View Profile</a></li> -->
                        <li><a class="dropdown-item" href="#" onclick="confirmLogout()">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Dashboard Section -->
<div class="dashboard">
    <div class="card">
        <h2>Tour Requests</h2>
        <p>Manage and oversee all tour requests.</p>
        <a href="indexV.php" class="btn-custom">View Details</a>
    </div>

    <div class="card">
        <h2>Activities Requests</h2>
        <p>Track and monitor activity requests of tours.</p>
        <a href="demo.html" class="btn-custom">View Details</a>
    </div>

    <div class="card">
        <h2>Expenses Requests</h2>
        <p>Review and analyze expenses related to tours.</p>
        <a href="expenses.php" class="btn-custom">View Details</a>
    </div>

    <!-- <div class="card">
        <h2>Total Tours</h2>
        <p>View all completed tours.</p>
        <a href="tours.php" class="btn-custom">View Details</a>
    </div> -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function confirmLogout() {
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out of your account.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, logout",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "login.php"; // Redirect to login page
            }
        });
    }
</script>

</body>
</html>
