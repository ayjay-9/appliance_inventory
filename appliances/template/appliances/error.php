<!-- Assignment 2 -->
<!-- Name: Emmanuel Ayobanjo -->
<!-- Student ID: 3173959 -->

<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $duplicate_error = $_SESSION['duplicate_error'] ?? false;
    $update_error = $_SESSION['update_error'] ?? false;
    unset($_SESSION['duplicate_error']);
    unset($_SESSION['update_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384- EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../static/appliances/style.css" type="text/css">
</head>
<body>
    <div id="error_back_to_home">
        <a href="../../../index.html" class="btn btn-secondary mt-3 ms-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"/>
            </svg>
        </a>
    </div>
    <!-- If adding a duplicate appliance -->
    <?php if ($duplicate_error) { ?>
        <div class="alert alert-danger text-center" role="alert">
            <h1>Error</h1>
            <img src="../../static/appliances/error.png" alt="Error" class="img-fluid mt-3 mb-3" style="max-width: 150px;">
            <p>
                Appliance already exists in the inventory. Please try re-submitting the
                <a href="add_appliance.php">Inventory Application</a>
            </p>
        </div>
    <?php } elseif ($update_error) { ?>
        <div class="alert alert-danger text-center" role="alert">
            <h1>Error</h1>
            <img src="../../static/appliances/error.png" alt="Error" class="img-fluid mt-3 mb-3" style="max-width: 150px;">
            <p>
                An error occurred while updating the appliance. Please try re-submitting the
                <a href="update_appliance.php">Update Appliance Form</a>
            </p>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger text-center" role="alert">
            <h1>Error</h1>
            <img src="../../static/appliances/error.png" alt="Error" class="img-fluid mt-3 mb-3" style="max-width: 150px;">
            <p>
                An error occurred while processing your request. Please try re-submitting the
                <a href="add_appliance.php">Inventory Application</a>
            </p>
        </div>
    <?php } ?>
</body>
</html>