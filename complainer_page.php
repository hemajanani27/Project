<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['x'])) {
    header("location:userlogin.php");
    exit();
}

require_once('TCPDF-main/tcpdf.php'); // Include TCPDF library

$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die("Could not connect: " . mysqli_error($conn));
}
mysqli_select_db($conn, "crime_portal");

$u_id = $_SESSION['u_id'];

$result = mysqli_query($conn, "SELECT a_no FROM user WHERE u_id='$u_id'");
$q2 = mysqli_fetch_assoc($result);
$a_no = $q2['a_no'];

$result1 = mysqli_query($conn, "SELECT u_name FROM user WHERE u_id='$u_id'");
$q2 = mysqli_fetch_assoc($result1);
$u_name = $q2['u_name'];

// Handle form submission
if (isset($_POST['s'])) {
    $location = $_POST['location'];
    $type_crime = $_POST['type_crime'];
    $d_o_c = $_POST['d_o_c'];
    $description = $_POST['description'];

    $var = strtotime(date("Ymd")) - strtotime($d_o_c);

    if ($var >= 0) {
        $comp = "INSERT INTO complaint(a_no, location, type_crime, d_o_c, description) VALUES ('$a_no', '$location', '$type_crime', '$d_o_c', '$description')";
        $res = mysqli_query($conn, $comp);

        if (!$res) {
            $message1 = "Complaint already filed";
            echo "<script type='text/javascript'>alert('$message1');</script>";
        } else {
            // Generate PDF acknowledgment
            ob_start(); // Start output buffering
            $pdf = new TCPDF();
            $pdf->AddPage();

            // Set header
            $pdf->SetFont('helvetica', 'B', 20);
            $pdf->Cell(0, 10, 'Complaint Acknowledgment', 0, 1, 'C');
            $pdf->Ln(10); // Add a line break

            // Set font for the body
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetTextColor(0, 0, 0); // Set text color to black

            // Add content with formatting
            $pdf->Cell(0, 10, 'Dear ' . htmlspecialchars($u_name) . ',', 0, 1, 'L');
            $pdf->Ln(5); // Add a line break

            $pdf->MultiCell(0, 10, 'Thank you for filing your complaint. We take such matters very seriously and appreciate your cooperation in helping us maintain the safety of our community. Below are the details of your complaint:', 0, 'L', 0, 1, '', '', true);
            $pdf->Ln(10); // Add a line break

            $pdf->Cell(0, 10, 'Aadhar Number: ' . htmlspecialchars($a_no), 0, 1, 'L');
            $pdf->Cell(0, 10, 'Location: ' . htmlspecialchars($location), 0, 1, 'L');
            $pdf->Cell(0, 10, 'Type of Crime: ' . htmlspecialchars($type_crime), 0, 1, 'L');
            $pdf->Cell(0, 10, 'Date of Crime: ' . htmlspecialchars($d_o_c), 0, 1, 'L');
            $pdf->Cell(0, 10, 'Description: ', 0, 1, 'L');
            $pdf->MultiCell(0, 10, htmlspecialchars($description), 0, 'L', 0, 1, '', '', true);

            // Closing statement
            $pdf->Ln(10); // Add a line break
            $pdf->Cell(0, 10, 'If you have any questions, feel free to contact us.', 0, 1, 'C');
            $pdf->Ln(5); // Add a line break
            $pdf->Cell(0, 10, 'Best regards,', 0, 1, 'C');
            $pdf->Cell(0, 10, 'E Crime Management Team', 0, 1, 'C');

            $pdfOutput = 'acknowledgment.pdf';
            $pdf->Output($pdfOutput, 'D'); // Force download the PDF
            ob_end_flush(); // Flush output buffer

            // Show success alert after downloading
            echo "<script type='text/javascript'>alert('Complaint registered successfully!');</script>";
            // Redirect back to the same page after alert
            echo "<script type='text/javascript'>setTimeout(function() { window.location.href = '" . $_SERVER['PHP_SELF'] . "'; }, 1000);</script>";
            exit();
        }
    } else {
        $message = "Enter a valid date.";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
}
?>
<head>
    <title>Complainer Home Page</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="complainer_page.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body style="background-size: cover; background-image: url(registerbg.jpg); background-position: center;">
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
                    <li><a href="userlogin.php">User Login</a></li>
                    <li class="active"><a href="complainer_page.php">User Home</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="complainer_page.php">Log New Complaint</a></li>
                    <li><a href="complainer_complain_history.php">Complaint History</a></li>
                    <li><a href="logout.php">Logout &nbsp <i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="video" style="margin-top: 5%">
        <div class="center-container">
            <div class="bg-agile">
                <br><br>
                <div class="login-form">
                    <p><h2 style="color:white">Welcome <?php echo htmlspecialchars($u_name); ?></h2></p><br>
                    <p><h2>Log New Complaint</h2></p><br>

                    <form action="#" method="post" style="color: gray">
                        Aadhar
                        <input type="text" name="aadhar_number" placeholder="Aadhar Number" required="" disabled value="<?php echo htmlspecialchars($a_no); ?>">
                        <div class="top-w3-agile" style="color: gray">
                            Location of Crime:
                            <select class="form-control" name="location" required>
                                <option value="" disabled selected hidden>Select Location</option>
                                <?php
                                $locQuery = mysqli_query($conn, "SELECT location FROM police_station");
                                while ($row = mysqli_fetch_array($locQuery)) {
                                    echo "<option>" . htmlspecialchars($row[0]) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="top-w3-agile" style="color: gray">
                            Type of Crime
                            <select class="form-control" name="type_crime" required>
                                <option value="" disabled selected hidden>Select Type of Crime</option>
                                <option>Theft</option>
                                <option>Robbery</option>
                                <option>Pick Pocket</option>
                                <option>Murder</option>
                                <option>Rape</option>
                                <option>Molestation</option>
                                <option>Kidnapping</option>
                                <option>Missing Person</option>
                            </select>
                        </div>
                        <div class="top-w3-agile" style="color: gray">
                            Date Of Crime : &nbsp &nbsp
                            <input style="background-color: #313131;color: gray" type="date" name="d_o_c" required>
                        </div>
                        <br>
                        <div class="top-w3-agile" style="color: gray">
                            Description
                            <textarea name="description" rows="5" cols="50" placeholder="Describe the incident in detail with time" required></textarea>
                        </div>
                        <input type="submit" value="Submit" name="s" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div style="position: relative; left: 0; bottom: 0; width: 100%; height: 30px; background-color: rgba(0,0,0,0.8); color: white; text-align: center;">
        <h4 style="color: white;">&copy <b>E Crime Management</b></h4>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
