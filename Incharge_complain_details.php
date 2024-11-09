<!DOCTYPE html>
<html>
<head>
    <?php
    session_start();
    if (!isset($_SESSION['x'])) {
        header("location:inchargelogin.php");
    }

    $conn = mysqli_connect("localhost", "root", "");
    if (!$conn) {
        die("Could not connect: " . mysqli_error($conn));
    }
    mysqli_select_db($conn, "crime_portal");

    $cid = $_SESSION['cid'];
    $i_id = $_SESSION['email'];
    $result1 = mysqli_query($conn, "SELECT location FROM police_station WHERE i_id='$i_id'");
    $q2 = mysqli_fetch_assoc($result1);
    $location = $q2['location'];

    $query = "SELECT c_id, type_crime, d_o_c, description FROM complaint WHERE c_id='$cid' AND location='$location'";
    $result = mysqli_query($conn, $query);

    $message = "";
    if (isset($_POST['assign'])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $pname = $_POST['police_name'];
            $res1 = mysqli_query($conn, "SELECT p_id FROM police WHERE p_name='$pname'");
            $q3 = mysqli_fetch_assoc($res1);
            $pid = $q3['p_id'];

            $res = mysqli_query($conn, "UPDATE complaint SET inc_status='Assigned', pol_status='In Process', p_id='$pid' WHERE c_id='$cid'");

            $message = "Case Assigned Successfully"; // Set the message
        }
    }
    ?>

    <title>Assign Police</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <style>
       .notification {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #5cb85c;
    color: white;
    padding: 30px; /* Increased padding for a larger alert */
    border-radius: 10px; /* More rounded corners */
    z-index: 1000;
    font-size: 18px; /* Increased font size */
    width: 300px; /* Fixed width to prevent it from being too wide */
    text-align: center; /* Center text within the notification */
}

    </style>
    <script>
        function showNotification(message) {
            var notification = document.getElementById("notification");
            notification.innerHTML = message;
            notification.style.display = "block";
            setTimeout(function () {
                notification.style.display = "none";
            }, 2000);
        }
    </script>
</head>
<body style="color: black;background-image: url(ai-generated-8775948_1280.jpg);background-size: 100%;background-repeat: no-repeat;">
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
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="Incharge_complain_page.php">View Complaints</a></li>
                    <li class="active"><a href="incharge_complain_details.php">Complaints Details</a></li>
                    <li><a href="inc_logout.php">Logout &nbsp <i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Notification Section -->
    <div id="notification" class="notification">
        <?php if ($message): ?>
            <script>showNotification("<?php echo $message; ?>");</script>
        <?php endif; ?>
    </div>

    <div style="padding:50px; margin-top:10px;">
        <table class="table table-bordered">
            <thead class="thead-dark" style="background-color: black; color: white;">
            <tr>
                <th scope="col">Complaint Id</th>
                <th scope="col">Type of Crime</th>
                <th scope="col">Date of Crime</th>
                <th scope="col">Description</th>
            </tr>
            </thead>
            <?php
            while ($rows = mysqli_fetch_assoc($result)) {
                ?>
                <tbody style="background-color: white; color: black;">
                <tr>
                    <td><?php echo $rows['c_id']; ?></td>
                    <td><?php echo $rows['type_crime']; ?></td>
                    <td><?php echo $rows['d_o_c']; ?></td>
                    <td><?php echo $rows['description']; ?></td>
                </tr>
                </tbody>
                <?php
            }
            ?>
        </table>
    </div>
    <div>
        <form method="post">
            <select class="form-control" name="police_name" style="margin-left:40%; width:250px;">
                <?php
                $p_name = mysqli_query($conn, "SELECT p_name FROM police WHERE location='$location'");
                while ($row = mysqli_fetch_array($p_name)) {
                    ?>
                    <option><?php echo $row[0]; ?></option>
                    <?php
                }
                ?>
            </select>
            <input type="submit" name="assign" value="Assign Case" class="btn btn-primary" style="margin-top:10px; margin-left:45%;">
        </form>
    </div>

    <div style="position: fixed; left: 0; bottom: 0; width: 100%; height: 30px; background-color: rgba(0,0,0,0.8); color: white; text-align: center;">
        <h4 style="color: white;">&copy <b>E-Crime Management</b></h4>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
