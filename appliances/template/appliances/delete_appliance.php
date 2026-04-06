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
    <title>Delete Appliance</title>
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
    <header>
        <h1 class="text-center mb-4">Delete Appliance</h1>
        <hr class="mb-1">
    </header>

    <div class="container mt-5">   
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET" class="mb-4" novalidate>
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="What appliance would you like to delete? e.g. SN12345678" value="<?php if (isset($_GET['serial_number'])) {echo htmlspecialchars($_GET['serial_number'], ENT_QUOTES, 'UTF-8');} else {echo htmlspecialchars($serial_num ?? '', ENT_QUOTES, 'UTF-8');} ?>" required>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <?php
        $query = htmlspecialchars($_GET['query'] ?? '', ENT_QUOTES, 'UTF-8');
        $query = mysqli_real_escape_string($con, $query);

        if (isset($_GET['serial_number'])) {
            $serial_num = mysqli_real_escape_string($con, $_GET['serial_number']);
        }
        else if (isset($_GET['query'])) {
            $serial_num = mysqli_real_escape_string($con, $_GET['query']);
        }

        if (!empty($serial_num)) {
            // Show the appliance from the database
            $appliance_query = "SELECT * FROM $table2 
                                    JOIN $table1 ON $table2.user_id = $table1.user_id 
                                    WHERE serial_number = '$serial_num'
                                ";
            $appliance_result = mysqli_query($con, $appliance_query);

            // If the appliance exists, store it in a variable.
            if (mysqli_num_rows($appliance_result) > 0) {
                $appliance = mysqli_fetch_assoc($appliance_result);
                echo '<div id="appliance_form" class="container mt-4 p-3">';
                echo "<form action='confirm_delete.php' method='POST'>";
                echo "<label for='first_name' class='form-label'>First Name</label>";
                echo "<input type='text' id='first_name' name='first_name' class='form-control mb-3' value='" . htmlspecialchars($appliance['first_name'], ENT_QUOTES, 'UTF-8') . "' readonly>";
                
                echo "<label for='last_name' class='form-label'>Last Name</label>";
                echo "<input type='text' id='last_name' name='last_name' class='form-control mb-3' value='" . htmlspecialchars($appliance['last_name'], ENT_QUOTES, 'UTF-8') . "' readonly>";
                
                echo "<label for='address' class='form-label'>Address</label>";
                echo "<input type='text' id='address' name='address' class='form-control mb-3' value='" . htmlspecialchars($appliance['address'], ENT_QUOTES, 'UTF-8') . "' readonly>";
                
                echo "<label for='mobile' class='form-label'>Mobile</label>";
                echo "<input type='text' id='mobile' name='mobile' class='form-control mb-3' value='" . htmlspecialchars($appliance['mobile'], ENT_QUOTES, 'UTF-8') . "' readonly>";

                echo "<label for='email' class='form-label'>Email</label>";
                echo "<input type='email' id='email' name='email' class='form-control mb-3' value='" . htmlspecialchars($appliance['email'], ENT_QUOTES, 'UTF-8') . "' readonly>";

                echo "<label for='eircode' class='form-label'>Eircode</label>";
                echo "<input type='text' id='eircode' name='eircode' class='form-control mb-3' value='" . htmlspecialchars($appliance['eircode'], ENT_QUOTES, 'UTF-8') . "' readonly>";

                echo "<label for='appliance_type' class='form-label'>Appliance Type</label>";
                echo "<input type='text' id='appliance_type' name='appliance_type' class='form-control mb-3' value='" . htmlspecialchars($appliance['appliance_type'], ENT_QUOTES, 'UTF-8') . "' readonly>";
                
                echo "<label for='brand' class='form-label'>Brand</label>";
                echo "<input type='text' id='brand' name='brand' class='form-control mb-3' value='" . htmlspecialchars($appliance['brand'], ENT_QUOTES, 'UTF-8') . "' readonly>";

                echo "<label for='model_number' class='form-label'>Model Number</label>";
                echo "<input type='text' id='model_number' name='model_number' class='form-control mb-3' value='" . htmlspecialchars($appliance['model_number'], ENT_QUOTES, 'UTF-8') . "' readonly>";

                echo "<label for='serial_number' class='form-label'>Serial Number</label>";
                echo "<input type='text' id='serial_number' name='serial_number' class='form-control mb-3' value='" . htmlspecialchars($appliance['serial_number'], ENT_QUOTES, 'UTF-8') . "' readonly>";
                
                echo "<label for='purchase_date' class='form-label'>Purchase Date</label>";
                echo "<input type='date' id='purchase_date' name='purchase_date' class='form-control mb-3' value='" . htmlspecialchars($appliance['purchase_date'], ENT_QUOTES, 'UTF-8') . "' readonly>";
                
                echo "<label for='warranty_exp_date' class='form-label'>Warranty Expiration Date</label>";
                echo "<input type='date' id='warranty_exp_date' name='warranty_exp_date' class='form-control mb-3' value='" . htmlspecialchars($appliance['warranty_exp_date'], ENT_QUOTES, 'UTF-8') . "' readonly>";
                
                echo "<label for='cost' class='form-label'>Appliance Cost (€)</label>";
                echo "<input type='number' id='cost' name='cost' class='form-control mb-3' value='" . htmlspecialchars($appliance['appliance_cost'], ENT_QUOTES, 'UTF-8') . "' readonly>";
                
                echo "<button type='submit' class='btn btn-danger'>Delete Appliance?</button>";
                echo "</form>";
            } 
            else {
                // If the appliance does not exist, show an error message.
                echo '<div class="alert alert-danger text-center" role="alert">';
                echo 'No appliance found with the serial number: <strong>' . htmlspecialchars($_GET['query']) . '</strong>';
                echo '</div>';
            }
        }
        else if (isset($_GET['query'])) {
            // If the appliance does not exist, show an error message.
            echo '<div class="alert alert-danger text-center" role="alert">';
            echo 'No longer wish to delete an appliance? You can <a href="search_appliance.php" class="alert-link">search the inventory</a> or <a href="../../../index.html" class="alert-link">return to the homepage</a>.';
            echo '</div>';
        }
    ?>
</body>
</html>