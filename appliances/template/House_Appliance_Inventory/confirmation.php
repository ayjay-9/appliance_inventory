<!-- Assignment 1 Part C - House Appliance Inventory (Sticky) -->
<!-- Name: Emmanuel Ayobanjo -->
<!-- Student ID: 3173959 -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384- EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../static/House_Appliance_Inventory/style.css" type="text/css">
</head>
<body>

    <?php
        // Start the session and check if the appliance registration was successful. If not, 
        // redirect to an error page. If yes, display the confirmation message and then unset 
        // the session variable to prevent the confirmation message from being displayed again 
        // on page refresh.
        session_start();
        if (!isset($_SESSION['appliance_registered'])) {
            header("Location: error.html");
            exit;
        }
    ?>

    <div id="confirmation_back_to_home">
        <a href="../../../../index.html" class="btn btn-secondary mt-3 ms-3">Back to Home</a>
    </div>
    <!-- Display the confirmation message -->
    <div class="alert alert-success text-center" role="alert">
        <h1>Appliance Successfully Registered</h1>
    </div>

    <!-- Show the registration details -->
     <div class="container mt-4">
        <div class="table-responsive">
            <h2>Registered Appliance Details:</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Eircode</th>
                        <th>Appliance Type</th>
                        <th>Brand</th>
                        <th>Model Number</th>
                        <th>Serial Number</th>
                        <th>Purchase Date</th>
                        <th>Warranty Expiration Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Display the registered appliance details from the session variable
                        if (isset($_SESSION['inventory']) && !empty($_SESSION['inventory'])) {
                            for ($i = 1; $i < count($_SESSION['inventory']) + 1; $i++) {
                                $appliance = $_SESSION['inventory'][$i - 1];
                                echo "<tr>";
                                echo "<td>" . $i . "</td>";
                                echo "<td>" . htmlspecialchars($appliance['eircode'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($appliance['appliance_type'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($appliance['brand'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($appliance['model_number'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($appliance['serial_number'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($appliance['purchase_date'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($appliance['warranty_expiration'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            // If there are no appliance details available in the session variable, display a message indicating that no details are available.
                            echo "<tr><td colspan='8' class='text-center'>No appliance details available.</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="register_appliance.php" class="btn btn-primary mt-3">Register Another Appliance</a>
    </div>
</body>
</html>