<!-- Assignment 2 -->
<!-- Name: Emmanuel Ayobanjo -->
<!-- Student ID: 3173959 -->

<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384- EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../static/appliances/style.css" type="text/css">
</head>
<body>
    <div id="confirmation_back_to_home">
        <a href="../../../index.html" class="btn btn-secondary mt-3 ms-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"/>
        </svg>
    </a>
    </div>
    <!-- Display the confirmation message -->
     <?php 
        if (isset($_SESSION['appliance_registered']) && $_SESSION['appliance_registered'] === true) { 
            // Unset the session variable to prevent the message from showing on page refresh
            unset($_SESSION['appliance_registered']);
            echo '<div class="alert alert-success text-center" role="alert">
                    <h1>Appliance Successfully Registered</h1>
                    <img src="../../static/appliances/success.png" alt="Success" class="img-fluid mt-3" style="max-width: 150px;">
                </div>
                <div class="text-center">
                    <a href="add_appliance.php" class="btn btn-primary mt-3">Register Another Appliance</a>
                </div>';
        } 
        else if (isset($_SESSION['appliance_updated']) && $_SESSION['appliance_updated'] === true) {
            // Unset the session variable to prevent the message from showing on page refresh
            unset($_SESSION['appliance_updated']);
            unset($_SESSION['appliance']);        // clear the stored appliance
            unset($_SESSION['appliance_type']);   // clear the appliance type
            echo '<div class="alert alert-success text-center" role="alert">
                    <h1>Appliance Successfully Updated</h1>
                    <img src="../../static/appliances/success.png" alt="Success" class="img-fluid mt-3" style="max-width: 150px;">
                </div>
                <div class="text-center">
                    <a href="../../../index.html" class="btn btn-primary mt-3">Back to home</a>
                </div>';
        } 
        else if (isset($_SESSION['appliance_deleted']) && $_SESSION['appliance_deleted'] === true) {
            // Unset the session variable to prevent the message from showing on page refresh
            unset($_SESSION['appliance_deleted']);
            echo '<div class="alert alert-success text-center" role="alert">
                    <h1>Appliance Successfully Deleted</h1>
                    <img src="../../static/appliances/success.png" alt="Success" class="img-fluid mt-3" style="max-width: 150px;">
                </div>
                <div class="text-center">
                    <a href="../../../index.html" class="btn btn-primary mt-3">Back to home</a>
                </div>';
        }
        else {
            // If the user tries to access this page directly without registering an appliance, redirect them to the error page
            header("Location: error.php");
            exit();
        }
    ?>
    </div>
</body>
</html>