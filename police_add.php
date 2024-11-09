<?php
session_start();
if (!isset($_SESSION['x'])) {
    header("location:inchargelogin.php");
    exit();
}

// Initialize notification variable
$notification = "";

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'crime_portal');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

$i_id = $_SESSION['email'];

// Get the location of the police station
$result1 = mysqli_query($con, "SELECT location FROM police_station WHERE i_id='$i_id'");
$q2 = mysqli_fetch_assoc($result1);
$location = $q2['location'];

// Handle form submission
if (isset($_POST['s'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $p_name = mysqli_real_escape_string($con, $_POST['police_name']);
        $p_id = mysqli_real_escape_string($con, $_POST['police_id']);
        $spec = mysqli_real_escape_string($con, $_POST['police_spec']);
        $p_pass = mysqli_real_escape_string($con, $_POST['password']);

        // Check if the police officer already exists
        $checkQuery = "SELECT * FROM police WHERE p_id='$p_id'";
        $checkResult = mysqli_query($con, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // Police officer already exists
            $notification = "<div class='alert alert-danger'>Police Officer with this ID already exists!</div>";
        } else {
            // Insert the new police officer
            $reg = "INSERT INTO police  VALUES ('$p_name', '$p_id', '$spec', '$location', '$p_pass')";
            $res = mysqli_query($con, $reg);

            if (!$res) {
                $notification = "<div class='alert alert-danger'>Error occurred while adding the police officer.</div>";
            } else {
                $notification = "<div class='alert alert-success'>Police Officer Added Successfully</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complainer Home Page</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="complainer_page.css" rel="stylesheet" type="text/css" media="all" />
    
    <script>
        // Function to hide notifications after 2 minutes
        function hideNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 2000); // 120000 ms = 2 minutes
            }
        }
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
                    <li><a href="official_login.php">Official Login</a></li>
                    <li><a href="incharge_view_police.php">Incharge Home</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="police_add.php">Log Police Officer</a></li>
                    <li><a href="Incharge_complain_page.php">Complaint History</a></li>
                    <li><a href="inc_logout.php">Logout &nbsp <i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
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
                        <script>hideNotification();</script>
                    <?php endif; ?>
                </div>

                <div class="login-form">
                    <h2>Log Police Officer</h2><br>
                    <form method="post" style="color: gray">
                        Police Name
                        <input type="text" name="police_name" placeholder="Police Name" required="" id="pname"/>
                        Police Id
                        <input type="text" name="police_id" placeholder="Police Id" required="" id="pid"/>
                        Specialist
                        <input type="text" name="police_spec" placeholder="Specialist" id="pspec" required/>
                        Location of Police Officer
                        <input type="text" required name="location" disabled value="<?php echo "$location"; ?>">
                        <br>
                        Password
                         <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required name="password" onfocusout="f1()">
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
