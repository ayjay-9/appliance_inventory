<!-- Assignment 2 -->
<!-- Name: Emmanuel Ayobanjo -->
<!-- Student ID: 3173959 -->

<?php
    require_once 'validate.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete Appliance</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384- EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../static/appliances/style.css" type="text/css">
</head>
<body>
    <a href="../../../index.html" class="btn btn-secondary mt-3 ms-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"/>
        </svg>
    </a>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['confirm_delete'])) {
            echo '<div class="container mt-4">
                    <div class="alert alert-danger text-center" role="alert">
                        <h4 class="alert-heading">Are you sure you want to delete this appliance?</h4>
                        <p>This action cannot be undone. Please confirm that you want to permanently delete this appliance from the inventory.</p>
                        <hr>
                        <form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST" class="d-inline">
                            <input type="hidden" name="first_name" value="' . htmlspecialchars($_POST['first_name']) . '">
                            <input type="hidden" name="last_name" value="' . htmlspecialchars($_POST['last_name']) . '">
                            <input type="hidden" name="address" value="' . htmlspecialchars($_POST['address']) . '">
                            <input type="hidden" name="mobile" value="' . htmlspecialchars($_POST['mobile']) . '">
                            <input type="hidden" name="email" value="' . htmlspecialchars($_POST['email']) . '">
                            <input type="hidden" name="eircode" value="' . htmlspecialchars($_POST['eircode']) . '">
                            <input type="hidden" name="appliance_type" value="' . htmlspecialchars($_POST['appliance_type']) . '">
                            <input type="hidden" name="brand" value="' . htmlspecialchars($_POST['brand']) . '">
                            <input type="hidden" name="model_number" value="' . htmlspecialchars($_POST['model_number']) . '">
                            <input type="hidden" name="serial_number" value="' . htmlspecialchars($_POST['serial_number']) . '">
                            <input type="hidden" name="purchase_date" value="' . htmlspecialchars($_POST['purchase_date']) . '">
                            <input type="hidden" name="warranty_exp_date" value="' . htmlspecialchars($_POST['warranty_exp_date']) . '">
                            <input type="hidden" name="cost" value="' . htmlspecialchars($_POST['cost']) . '">
                            <input type="hidden" name="confirm_delete" value="yes">
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </form>
                        <a href="delete_appliance.php" class="btn btn-secondary ms-2">No, Cancel</a>
                    </div>
                </div>';
        }
        else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
            $serial_num = $_POST['serial_number'];

            // Get the user ID associated with the appliance to be deleted
            $stmt = mysqli_prepare($con, "SELECT user_id FROM $table2 WHERE serial_number = ?");
            mysqli_stmt_bind_param($stmt, "s", $serial_num);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user_id = mysqli_fetch_assoc($result)['user_id'];

            // Delete the appliance from the database
            $stmt = mysqli_prepare($con, "DELETE FROM $table2 WHERE serial_number = ?");
            mysqli_stmt_bind_param($stmt, "s", $serial_num);
            mysqli_stmt_execute($stmt);

            // then check if user still has appliances
            $stmt = mysqli_prepare($con, "SELECT COUNT(*) FROM $table2 WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "s", $user_id);
            mysqli_stmt_execute($stmt);
            $appliance_count_result = mysqli_stmt_get_result($stmt);
            
            // if the user has no more appliances, delete the user from the database as well
            $appliance_count = mysqli_fetch_row($appliance_count_result)[0];
            if ($appliance_count == 0) {
                $stmt = mysqli_prepare($con, "DELETE FROM $table1 WHERE user_id = ?");
                mysqli_stmt_bind_param($stmt, "s", $user_id);
                mysqli_stmt_execute($stmt);
            }

            // If the appliance was successfully deleted, show a confirmation message.
            unset($_SESSION['appliance_updated']);
            unset($_SESSION['appliance_registered']);
            unset($_SESSION['appliance_deleted']);
            $_SESSION['appliance_deleted'] = true;
            header("Location: confirmation.php");
            exit();            
        }
        else {
            // If the user tries to access this page directly without confirming deletion, redirect them to the error page
            header("Location: error.php");
            exit();
        }
    ?>
</body>
</html>