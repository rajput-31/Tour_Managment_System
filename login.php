<?php
session_start();
require_once 'db.php'; // Database connection

$error = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Both username and password are required.";
    } else {
        $query = "SELECT id, password, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($id, $hashed_password, $role);
            $stmt->fetch();
            $stmt->close();

            if ($id && password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                $success_message = "Login successful...!";
                // Redirect with JS after successful login
                echo "<script>
                        alert('$success_message');
                        window.location.href = '" . ($role === 'admin' ? "admin.php" : "employee.php") . "';
                      </script>";
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Database error.";
        }
    }
}

// Registration logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (empty($username) || empty($password) || empty($role)) {
        echo "<script>
                alert('All fields are required. Please fill them out!');
                window.location.href='login.php';
              </script>";
        exit();
    }

    $checkQuery = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkQuery);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>
                    alert('Username already exists! Please choose another.');
                    window.location.href='login.php';
                  </script>";
            exit();
        }        
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insertQuery = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    if ($stmt) {
        $stmt->bind_param("sss", $username, $hashedPassword, $role);
        if ($stmt->execute()) {
            echo "<script>
                    alert('Registration successful..! Please log in.');
                    window.location.href='login.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Error: " . $stmt->error . "');
                    window.location.href='login.php';
                  </script>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Styling (same as previous) */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}
body {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background:white;
  padding: 30px;
}
.container {
  position: relative;
  max-width: 850px;
  width: 100%;
  background: #fff;
  padding: 40px 30px;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  perspective: 2700px;
}
.container .cover {
  position: absolute;
  top: 0;
  left: 50%;
  height: 100%;
  width: 50%;
  z-index: 98;
  transition: all 1s ease;
  transform-origin: left;
  transform-style: preserve-3d;
  backface-visibility: hidden;
}
.container #flip:checked ~ .cover {
  transform: rotateY(-180deg);
}
.container #flip:checked ~ .forms .login-form {
  pointer-events: none;
}
.container .cover .front,
.container .cover .back {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
}
.cover .back {
  transform: rotateY(180deg);
}
.container .cover img {
  position: absolute;
  height: 100%;
  width: 100%;
  object-fit: cover;
  z-index: 10;
}
.container .cover .text {
  position: absolute;
  z-index: 10;
  height: 100%;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.container .cover .text::before {
  content: '';
  position: absolute;
  height: 100%;
  width: 100%;
  opacity: 0.5;
  background:rgb(66, 176, 112);
}
.cover .text .text-1,
.cover .text .text-2 {
  z-index: 20;
  font-size: 26px;
  font-weight: 600;
  color: #fff;
  text-align: center;
}
.cover .text .text-2 {
  font-size: 15px;
  font-weight: 500;
}
.container .forms {
  height: 100%;
  width: 100%;
  background: #fff;
}
.container .form-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.form-content .login-form,
.form-content .signup-form {
  width: calc(100% / 2 - 25px);
}
.forms .form-content .title {
  position: relative;
  font-size: 24px;
  font-weight: 500;
  color: #333;
}
.forms .form-content .title:before {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  height: 3px;
  width: 25px;
  background:rgb(59, 192, 22);
}
.forms .signup-form .title:before {
  width: 20px;
}
.forms .form-content .input-boxes {
  margin-top: 30px;
}
.forms .form-content .input-box {
  display: flex;
  align-items: center;
  height: 50px;
  width: 100%;
  margin: 10px 0;
  position: relative;
}
.form-content .input-box input {
  height: 100%;
  width: 100%;
  outline: none;
  border: none;
  padding: 0 30px;
  font-size: 16px;
  font-weight: 500;
  border-bottom: 2px solid rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}
.form-content .input-box input:focus,
.form-content .input-box input:valid {
  border-color:rgb(59, 192, 22);
}
.form-content .input-box i {
  position: absolute;
  color:rgb(59, 192, 22);
  font-size: 17px;
}
.forms .form-content .text {
  font-size: 14px;
  font-weight: 500;
  color: #333;
}
.forms .form-content .text a {
  text-decoration: none;
}
.forms .form-content .text a:hover {
  text-decoration: underline;
}
.forms .form-content .button {
  color: #fff;
  margin-top: 40px;
}
.forms .form-content .button input {
  color: #fff;
  background:rgb(59, 192, 22);
  border-radius: 6px;
  padding: 0;
  cursor: pointer;
  transition: all 0.4s ease;
}
.forms .form-content .button input:hover {
  background:rgb(49, 229, 115)
}
.forms .form-content label {
  color:blue;
  cursor: pointer;
}
.forms .form-content label:hover {
  text-decoration: underline;
}
.forms .form-content .login-text,
.forms .form-content .sign-up-text {
  text-align: center;
  margin-top: 25px;
}
.container #flip {
  display: none;
}
@media (max-width: 730px) {
  .container .cover {
    display: none;
  }
  .form-content .login-form,
  .form-content .signup-form {
    width: 100%;
  }
  .form-content .signup-form {
    display: none;
  }
  .container #flip:checked ~ .forms .signup-form {
    display: block;
  }
  .container #flip:checked ~ .forms .login-form {
    display: none;
  }
}
.input-box .fa-eye, 
.input-box .fa-eye-slash {
    position: absolute;
    right: 10px;
    color:rgb(59, 192, 22);
    font-size: 18px;
    cursor: pointer;
}
    </style>
</head>
<body>
  <div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
      <div class="front">
        <img src="https://png.pngtree.com/thumb_back/fh260/background/20221223/pngtree-login-with-email-and-password-website-web-password-photo-image_7733608.jpg" alt="">
        <!-- <div class="text">
          <span class="text-1">Every new friend is a <br> new adventure</span>
          <span class="text-2">Let's get connected</span>
        </div> -->
      </div>
      <div class="back">
      <img src="https://img.freepik.com/premium-photo/engineering-night-hands-person-laptop-maintenance-repair-inspection-server-room-information-technology-dark-it-technician-computer-analysis-data-control-panel_590464-213903.jpg?w=2000" alt="">
        <!-- <div class="text">
          <span class="text-1">Complete miles of journey <br> with one step</span>
          <span class="text-2">Let's get started</span>
        </div> -->
      </div>
    </div>
    <div class="forms">
    <div class="form-content">
        <!-- Login Form -->
        <div class="login-form">
            <div class="title">Login</div>
            <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
            <form action="" method="POST">
                <div class="input-boxes">
                    <div class="input-box">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-eye" id="toggle-login-password" onclick="togglePassword('login-password', 'toggle-login-password')"></i>
                    </div>
                    <div class="button input-box">
                        <input type="submit" name="login" value="Submit">
                    </div>
                    <div class="text sign-up-text">Don't have an account? <label for="flip">Signup now</label></div>
                </div>
            </form>
        </div>

        <!-- Signup Form -->
        <div class="signup-form">
            <div class="title">Signup</div>
            <form action="" method="POST">
                <div class="input-boxes">
                    <div class="input-box">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="signup-password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-eye" id="toggle-signup-password" onclick="togglePassword('signup-password', 'toggle-signup-password')"></i>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-user-tag"></i>
                        <select class="input-box" name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>
                    <div class="button input-box">
                        <input type="submit" name="register" value="Submit">
                    </div>
                    <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<script>
function togglePassword(inputId, iconId) {
    var passwordField = document.getElementById(inputId);
    var icon = document.getElementById(iconId);
    
    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>
