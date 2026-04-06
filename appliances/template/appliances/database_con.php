<?php
    // Connect to the database using the configuration settings defined in the config.php file. This allows for a centralized location to manage database connection details, making it easier to maintain and update the connection settings if needed.
    require_once '../../../config.php';
    
    $con = mysqli_connect($host, $username, $password, $dbname);
    // Check connection to the database. If the connection fails, terminate the script and display an error message indicating the reason for the failure. This ensures that any issues with the database connection are promptly identified and handled gracefully.
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>