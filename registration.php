<?php
session_start();
$notification = "";

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'crime_portal');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

// Handle form submission
if (isset($_POST['s'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $u_name = mysqli_real_escape_string($con, $_POST['name']);
        $u_id = mysqli_real_escape_string($con, $_POST['email']);
        $u_pass = mysqli_real_escape_string($con, $_POST['password']);
        $u_addr = mysqli_real_escape_string($con, $_POST['adress']);
        $a_no = mysqli_real_escape_string($con, $_POST['aadhar_number']);
        $gen = mysqli_real_escape_string($con, $_POST['gender']);
        $mob = mysqli_real_escape_string($con, $_POST['mobile_number']);

        // Check if user already exists
        $checkQuery = "SELECT * FROM user WHERE u_id='$u_id'"; // Change 'user_id' to the correct column name for unique identification
        $checkResult = mysqli_query($con, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // User already exists
            $notification = "<div class='alert alert-danger'>User Already Exists</div>";
        } else {
            // Insert the new user
            $reg = "INSERT INTO user VALUES ('$u_name', '$u_id', '$u_pass', '$u_addr', '$a_no', '$gen', '$mob')";
            $res = mysqli_query($con, $reg);

            if (!$res) {
                $notification = "<div class='alert alert-danger'>Error occurred during registration: " . mysqli_error($con) . "</div>";
            } else {
                $notification = "<div class='alert alert-success'>User Registered Successfully</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="complainer_page.css" rel="stylesheet" type="text/css" media="all" />
    <script>
        function hideNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 2000);
            }
        }
    </script>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="home.php"><b>Crime Portal</b></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="registration.php">Registration</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="video" style="margin-top: 5%">
    <div class="center-container">
        <div class="bg-agile">
            <br><br>
            <div class="container" style="margin-top: 20px;" id="notification">
                <?php if ($notification): ?>
                    <?= $notification; ?>
                    <script>hideNotification();</script>
                <?php endif; ?>
            </div>

            <div class="login-form">
                <form action="#" method="post">
                    <p style="color:#dfdfdf">Full Name</p>
                    <input type="text" name="name" required id="name1" oninvalid="this.setCustomValidity('Full Name is required and cannot contain spaces.')" oninput="this.setCustomValidity('')"/>
                    
                    <p style="color:#dfdfdf">Email-Id</p>
                    <input type="email" name="email" required id="email1" oninvalid="this.setCustomValidity('Please enter a valid Email ID.')" oninput="this.setCustomValidity('')"/>
                    
                    <p style="color:#dfdfdf">Password</p>
                    <input type="text" name="password" placeholder="6 Character minimum" pattern=".{6,}" id="pass" oninvalid="this.setCustomValidity('Password must be at least 6 characters long and cannot contain spaces.')" oninput="this.setCustomValidity('')"/>
                    
                    <p style="color:#dfdfdf">Home Address</p>
                    <input type="text" name="adress" required id="addr" oninvalid="this.setCustomValidity('Home Address is required and cannot contain spaces.')" oninput="this.setCustomValidity('')"/>
                    
                    <p style="color:#dfdfdf">Aadhar Number</p>
                    <input type="text" name="aadhar_number" minlength="12" maxlength="12" required pattern="\d{12}" id="aadh" oninvalid="this.setCustomValidity('Aadhar Number must be exactly 12 digits.')" oninput="this.setCustomValidity('')"/>
                    
                    <div class="left-w3-agile">
                        <p style="color:#dfdfdf">Gender</p>
                        <select class="form-control" name="gender">
                            <option value="" disabled selected hidden></option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    
                    <div class="right-agileits">
                        <p style="color:#dfdfdf">Mobile</p>
                        <input type="text" name="mobile_number" required pattern="[6789][0-9]{9}" minlength="10" maxlength="10" id="mobno" oninvalid="this.setCustomValidity('Mobile Number must be exactly 10 digits and start with 6, 7, 8, or 9.')" oninput="this.setCustomValidity('')"/>
                    </div>
                    
                    <input type="submit" value="Submit" name="s">
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
