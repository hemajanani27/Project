<?php
session_start();
if (!isset($_SESSION['x'])) {
    header("location:headlogin.php");
    exit();
}

// Initialize notification variable
$notification = "";

// Handle form submission
if (isset($_POST['s'])) {
    $con = mysqli_connect('localhost', 'root', '', 'crime_portal');
    if (!$con) {
        die('Could not connect: ' . mysqli_error($con));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $loc = mysqli_real_escape_string($con, $_POST['location']);
        $i_name = mysqli_real_escape_string($con, $_POST['incharge_name']);
        $i_id = mysqli_real_escape_string($con, $_POST['incharge_id']);
        $u_pass = mysqli_real_escape_string($con, $_POST['password']);

        // Check if the police station already exists based on ID and Location
        $checkQuery = "SELECT * FROM police_station WHERE i_id='$i_id' OR location='$loc'";
        $checkResult = mysqli_query($con, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // Police station or location already exists
            $notification = "<div class='alert alert-danger'>A Police Station with this ID or Location already exists!</div>";
        } else {
            // Insert the new police station
            $reg = "INSERT INTO police_station VALUES ('$i_id', '$i_name', '$loc', '$u_pass')";
            
            if (mysqli_query($con, $reg)) {
                // Success notification
                $notification = "<div class='alert alert-success'>Police Station Added Successfully</div>";
            } else {
                $notification = "<div class='alert alert-danger'>Error occurred while adding the police station: " . mysqli_error($con) . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Police Station Log</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
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

        document.addEventListener('DOMContentLoaded', (event) => {
            hideNotification();
        });
    </script>
</head>

<body style="background-size: cover; background-image: url(ai-generated-8775948_1280.jpg); background-position: center;">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php"><b>Home</b></a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="official_login.php">HQ Login</a></li>
                    <li><a href="headHome.php">HQ Home</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="police_station_add.php">Log Police Station</a></li>
                    <li><a href="h_logout.php">Logout &nbsp <i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="video" style="margin-top: 5%">
        <div class="center-container">
            <div class="bg-agile">
                <br><br>

                <!-- Display notification at the top -->
                <div class="container" style="margin-top: 20px;" id="notification">
                    <?php if ($notification): ?>
                        <?= $notification; ?>
                    <?php endif; ?>
                </div>

                <div class="login-form">
                    <h2>Log Police Station</h2><br>
                    <form method="post" style="color: gray">
                        Police Station Location
                        <input type="text" name="location" placeholder="Station Location" required="" id="station"/>
                        Incharge Name
                        <input type="text" name="incharge_name" placeholder="Incharge Name" required="" id="iname"/>
                        Incharge Id
                        <input type="text" name="incharge_id" placeholder="Incharge Id" required="" id="iid"/>
                        <br>
                        Password
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required name="password">
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
