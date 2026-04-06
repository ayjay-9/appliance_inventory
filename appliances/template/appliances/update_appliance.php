<!-- Assignment 2 -->
<!-- Name: Emmanuel Ayobanjo -->
<!-- Student ID: 3173959 -->

<?php
    require_once 'validate.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // If any of the inputs is invalid, redirect to the form and show the error messages. Otherwise, add it to the database and show a confirmation message.
        if ($is_first_name_valid && $is_last_name_valid && $is_address_valid && 
            $is_mobile_valid && $is_email_valid && $is_eircode_valid && 
            $is_appliance_type_valid && $is_brand_valid && 
            $is_model_number_valid && $is_serial_number_valid && 
            $is_purchase_date_valid && $is_warranty_expiration_valid && $is_cost_valid)
        {
            $update_query = "UPDATE $table2 JOIN $table1 
                                ON $table2.user_id = $table1.user_id 
                                SET $table1.first_name = ?, $table1.last_name = ?, 
                                $table1.address = ?, $table1.mobile = ?, 
                                $table1.email = ?, $table1.eircode = ?, 
                                $table2.appliance_type = ?, $table2.brand = ?, 
                                $table2.model_number = ?, $table2.purchase_date = ?, 
                                $table2.warranty_exp_date = ?, $table2.appliance_cost = ? 
                                WHERE $table2.serial_number = ?
                            ";
            $stmt = mysqli_prepare($con, $update_query);
            mysqli_stmt_bind_param($stmt, "sssssssssssss", 
                $first_name, $last_name, $address, $mobile, 
                $email, $eircode, 
                $appliance_type,
                $brand, $model_number, 
                $purchase_date, $warranty_expiration, 
                $cost,
                $serial_number  // serial number stays the same
            );
            mysqli_stmt_execute($stmt);

            // If all inputs are valid, update the appliance details in the database and show a confirmation message
            unset($_SESSION['appliance_updated']);
            unset($_SESSION['appliance_registered']);
            unset($_SESSION['appliance_deleted']);
            $_SESSION['appliance_updated'] = true;
            header("Location: confirmation.php");
            exit();
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['serial_number'])) {
            $serial_num = mysqli_real_escape_string($con, $_GET['serial_number']);
        }
        else if (isset($_GET['query'])) {
            $serial_num = mysqli_real_escape_string($con, $_GET['query']);
        }

        if (isset($_GET['serial_number']) || isset($_GET['query'])) {
            if (!empty($serial_num)) {
                 // Search for the appliance with the given serial number and get the user details using a JOIN query.
                $sql = "SELECT * FROM $table2 
                        JOIN $table1 ON $table2.user_id = $table1.user_id 
                        WHERE $table2.serial_number = '$serial_num'";

                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $appliance = mysqli_fetch_assoc($result);
                }
                // If no appliance is found with the given serial number.
                else {
                    echo '<div class="alert alert-warning text-center" role="alert">';
                    echo 'No appliance found with serial number: ' . '<strong>' . htmlspecialchars($serial_num) . '</strong>. Would you like to <a href="add_appliance.php?serial_number=' . urlencode($serial_num) . '" class="alert-link">add it to the inventory</a> or <a href="../../../index.html" class="alert-link">return to the homepage</a>?';
                    echo '</div>';
                }
            }
            else {
                echo '<div class="alert alert-warning text-center" role="alert">';
                echo 'Please enter a serial number to update an appliance. Would you like to <a href="add_appliance.php" class="alert-link">add a new appliance to the inventory</a> or <a href="../../../index.html" class="alert-link">return to the homepage</a>?';
                echo '</div>';
            }
        }
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appliance</title>
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
        <h1 class="text-center mb-4">Update Appliance</h1>
        <hr class="mb-1">
    </header>

    <div class="container mt-5">   
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET" class="mb-4" novalidate>
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="What appliance would you like to update? e.g. SN12345678" value="<?php if (isset($_GET['serial_number'])) {echo htmlspecialchars($_GET['serial_number'], ENT_QUOTES, 'UTF-8');} else {echo htmlspecialchars($serial_num ?? '', ENT_QUOTES, 'UTF-8');} ?>" required>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <div id="appliance_form" class="container mt-4 p-3">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" novalidate>
            <!-- Update Appliance User Details with auto focus on first name on page load -->
            <label for="first_name" class="form-label">First Name<span>*</span></label>
            <input type="text" id="first_name" name="first_name" class="form-control mb-3" placeholder="First Name" value="<?php echo htmlspecialchars($first_name ?? $appliance['first_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required autofocus>
            <span class="error_message"><?php echo htmlspecialchars($first_name_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="last_name" class="form-label">Last Name<span>*</span></label>
            <input type="text" id="last_name" name="last_name" class="form-control mb-3" placeholder="Last Name" value="<?php echo htmlspecialchars($last_name ?? $appliance['last_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($last_name_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="address" class="form-label">Address<span>*</span></label>
            <input type="text" id="address" name="address" class="form-control mb-3" placeholder="Address" value="<?php echo htmlspecialchars($address ?? $appliance['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($address_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="mobile" class="form-label">Phone Number<span>*</span></label>
            <input type="tel" id="mobile" name="mobile" class="form-control mb-3" placeholder="Phone Number" value="<?php echo htmlspecialchars($mobile ?? $appliance['mobile'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($mobile_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="email" class="form-label">Email<span>*</span></label>
            <input type="email" id="email" name="email" class="form-control mb-3" placeholder="Email" value="<?php echo htmlspecialchars($email ?? $appliance['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($email_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="eircode" class="form-label">Eircode<span>*</span></label>
            <input type="text" id="eircode" name="eircode" class="form-control mb-3" placeholder="Eircode" value="<?php echo htmlspecialchars($eircode ?? $appliance['eircode'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($eircode_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <!-- Update Appliance Details -->
            <label for="appliance_type" class="form-label">Appliance Type<span>*</span></label>
            <select id="appliance_type" name="appliance_type" class="form-select mb-3" required>
                <option value="">--Select Appliance Type--</option>
                <?php foreach ($_SESSION['appliance_types'] as $type) { ?>
                    <option value="<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>" <?php if (isset($appliance['appliance_type']) && $appliance['appliance_type'] === $type) { echo 'selected'; } ?>>
                         <?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>
                     </option>
                <?php } ?>
            </select>
            <span class="error_message"><?php echo htmlspecialchars($appliance_type_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="brand" class="form-label">Brand<span>*</span></label>
            <input type="text" id="brand" name="brand" class="form-control mb-3" placeholder="Brand" value="<?php echo htmlspecialchars($brand ?? $appliance['brand'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($brand_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="model_number" class="form-label">Model Number<span>*</span></label>
            <input type="text" id="model_number" name="model_number" class="form-control mb-3" placeholder="Model Number" value="<?php echo htmlspecialchars($model_number ?? $appliance['model_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($model_number_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <!-- Serial number is read only as it is the unique identifier for each appliance and should not be changed. -->
            <label for="serial_number" class="form-label">Serial Number<span>*</span></label>
            <input type="text" id="serial_number" name="serial_number" class="form-control mb-3" placeholder="Serial Number" value="<?php echo htmlspecialchars($serial_number ?? $appliance['serial_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly>

            <label for="purchase_date" class="form-label">Purchase Date<span>*</span></label>
            <input type="date" id="purchase_date" name="purchase_date" class="form-control mb-3" value="<?php echo htmlspecialchars($purchase_date ?? $appliance['purchase_date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($purchase_date_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="warranty_expiration" class="form-label">Warranty Expiration Date<span>*</span></label>
            <input type="date" id="warranty_expiration" name="warranty_expiration" class="form-control mb-3" value="<?php echo htmlspecialchars($warranty_expiration ?? $appliance['warranty_exp_date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($warranty_expiration_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="cost" class="form-label">Appliance Cost (€)<span>*</span></label>
            <input type="number" id="cost" name="cost" class="form-control mb-3" placeholder="Appliance Cost" value="<?php echo htmlspecialchars($cost ?? $appliance['appliance_cost'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($cost_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <button type="submit" class="btn btn-warning">Update Appliance</button>
        </form>
    </div>
</body>
</html>