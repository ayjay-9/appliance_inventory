<!-- Assignment 2 -->
<!-- Name: Emmanuel Ayobanjo -->
<!-- Student ID: 3173959 -->

<?php 
    // Start the session to access session variables
    session_start(); 

    // Connect to the database like in the add_appliance.php file
    require_once '../../config/database.php';
    $con = mysqli_connect($host, $username, $password, $dbname);
    // Check connection to the database. If the connection fails, terminate the script and display an error message indicating the reason for the failure. This ensures that any issues with the database connection are promptly identified and handled gracefully.
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Appliance</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384- EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../static/House_Appliance_Inventory/style.css" type="text/css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Search Appliance</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Enter appliance name or type..." required>
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
</body>
</html>