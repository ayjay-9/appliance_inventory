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
    <title>Add Appliance</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384- EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../static/appliances/style.css" type="text/css">
</head>
<body>
    <?php
        // If any of the inputs is invalid, redirect to the form and show the error messages. Otherwise, add it to the database and show a confirmation message.
        // Rest of logic is in validate.php.
        if ($is_first_name_valid && $is_last_name_valid && $is_address_valid && 
            $is_mobile_valid && $is_email_valid && $is_eircode_valid && 
            $is_appliance_type_valid && $is_brand_valid && 
            $is_model_number_valid && $is_serial_number_valid && 
            $is_purchase_date_valid && $is_warranty_expiration_valid && $is_cost_valid)
        {
            // Check if the serial number already exists in the database to prevent duplicate entries.
            $serial_number_check_sql = "SELECT * FROM $table2 WHERE serial_number = '$serial_number'";
            $serial_number_check_result = mysqli_query($con, $serial_number_check_sql);

            if (mysqli_num_rows($serial_number_check_result) > 0) {
                $_SESSION['duplicate_error'] = true;
                header("Location: error.php");
                exit();
            }

            mysqli_begin_transaction($con);

            if (mysqli_num_rows($check_user_result) > 0) {
                // User already exists, get their user_id
                $user_row = mysqli_fetch_assoc($check_user_result);
                $user_id = $user_row['user_id'];
                $user_result = true; // No insert needed
            } else {
                // New user, insert them
                $user_sql = "INSERT INTO $table1 (first_name, last_name, address, mobile, email, eircode) 
                    VALUES ('" . mysqli_real_escape_string($con, $first_name) . "', 
                            '" . mysqli_real_escape_string($con, $last_name) . "', 
                            '" . mysqli_real_escape_string($con, $address) . "', 
                            '" . mysqli_real_escape_string($con, $mobile) . "', 
                            '" . mysqli_real_escape_string($con, $email) . "', 
                            '" . mysqli_real_escape_string($con, $eircode) . "')";
                $user_result = mysqli_query($con, $user_sql);
                $user_id = mysqli_insert_id($con);
            }

            // Insert appliance with the user_id
            $appliance_sql = "INSERT INTO $table2 (user_id, appliance_type, brand, model_number, serial_number, purchase_date, warranty_exp_date, appliance_cost) 
                VALUES ('$user_id', 
                        '" . mysqli_real_escape_string($con, $appliance_type) . "', 
                        '" . mysqli_real_escape_string($con, $brand) . "', 
                        '" . mysqli_real_escape_string($con, $model_number) . "', 
                        '" . mysqli_real_escape_string($con, $serial_number) . "', 
                        '" . mysqli_real_escape_string($con, $purchase_date) . "', 
                        '" . mysqli_real_escape_string($con, $warranty_expiration) . "', 
                        '" . mysqli_real_escape_string($con, $cost) . "'
                )";
            $appliance_result = mysqli_query($con, $appliance_sql);

            if ($user_result && $appliance_result) {
                unset($_SESSION['appliance_updated']);
                unset($_SESSION['appliance_registered']);
                unset($_SESSION['appliance_deleted']);
                $_SESSION['appliance_registered'] = true;
                mysqli_commit($con);
                header("Location: confirmation.php");
                exit();
            } else {
                mysqli_rollback($con);
                // Show the actual error to help debug
                die("SQL Error: " . mysqli_error($con));
            }
        }
    ?>

    <a href="../../../index.html" class="btn btn-secondary mt-3 ms-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"/>
        </svg>
    </a>
    <header id="appliance_page_header" class="text-center mt-2">
        <h1>House Appliance Inventory</h1>
        <p class="lead">Please fill in the form below to register a new appliance</p>
        <hr class="mb-1">
    </header>

    <div id="appliance_form" class="container mt-4 p-3">
        <!-- Form does not include client-side validation -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" novalidate>
            <!-- User Details with auto focus on first name on page load -->
            <label for="first_name" class="form-label">First Name<span>*</span></label>
            <input type="text" id="first_name" name="first_name" class="form-control mb-3" placeholder="First Name" value="<?php if(isset($first_name)) {echo htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8');} ?>" required autofocus>
            <span class="error_message"><?php echo htmlspecialchars($first_name_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="last_name" class="form-label">Last Name<span>*</span></label>
            <input type="text" id="last_name" name="last_name" class="form-control mb-3" placeholder="Last Name" value="<?php if(isset($last_name)) {echo htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8');} ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($last_name_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="address" class="form-label">Address<span>*</span></label>
            <input type="text" id="address" name="address" class="form-control mb-3" placeholder="Address" value="<?php if(isset($address)) {echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8');} ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($address_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="mobile" class="form-label">Mobile Number<span>*</span></label>
            <input type="text" id="mobile" name="mobile" class="form-control mb-3" placeholder="Mobile Number e.g., +353 871234567 or 0871234567" value="<?php if(isset($mobile)) {echo htmlspecialchars($mobile, ENT_QUOTES, 'UTF-8');} ?>" pattern="^(\+353|0)\d{9}$" required>
            <span class="error_message"><?php echo htmlspecialchars($mobile_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="email" class="form-label">Email<span>*</span></label>
            <input type="email" id="email" name="email" class="form-control mb-3" placeholder="Email e.g., john.doe@example.com" value="<?php if(isset($email)) {echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8');} ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($email_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <!-- Eircode input field -->
            <label for="eircode" class="form-label">Eircode<span>*</span></label>
            <input type="text" id="eircode" name="eircode" class="form-control mb-3" placeholder="Eircode e.g., T12 BC34 or P51 B3C4" pattern="^(T12|T23|T34|P12|P17|P24|P25|P31|P32|P36|P43|P47|P51|P56|P61|P67|P72|P75|P81|P85) ?([a-zA-Z0-9]{2}\d{2}|[a-zA-Z]\d[a-zA-Z]\d)$" value="<?php if(isset($eircode)) {echo htmlspecialchars($eircode, ENT_QUOTES, 'UTF-8');} ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($eircode_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <!-- Appliance type selection dropdown -->
            <label for="appliance_type" class="form-label">Appliance Type<span>*</span></label>
            <select id="appliance_type" name="appliance_type" class="form-select mb-3" required>
                <option value="" selected>--Select Appliance Type--</option>
                <!-- Dynamically generate the options for the appliance type dropdown from the predefined list of appliance types. This ensures that any updates to the list of appliance 
                     types will be automatically reflected in the dropdown without needing to manually update the HTML. Additionally, if the form is submitted with an invalid appliance type, 
                     the previously selected value will be retained in the dropdown when the form is redisplayed with error messages. 
                -->
                <?php foreach ($_SESSION['appliance_types'] as $type) { ?>
                    <option value="<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>" <?php if (isset($_POST['appliance_type']) && $_POST['appliance_type'] === $type) { echo 'selected'; } ?>>
                        <?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php } ?>

            </select>
            <span class="error_message"><?php echo htmlspecialchars($appliance_type_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <!-- Brand input field -->
            <label for="brand" class="form-label">Brand<span>*</span></label>
            <input type="text" id="brand" name="brand" class="form-control mb-3" title="Format: Between 2 and 30 characters" placeholder="Brand e.g., Samsung, De'Longhi, Hewlett-Packard, e.t.c" value="<?php if(isset($brand)) {echo htmlspecialchars($brand, ENT_QUOTES, 'UTF-8');} ?>" pattern="^[a-zA-Z '\-]{1,30}$" required>
            <span class="error_message"><?php echo htmlspecialchars($brand_error, ENT_QUOTES, 'UTF-8'); ?></span>

             <!-- Model Number input field -->
            <label for="model_number" class="form-label">Model Number<span>*</span></label>
            <input type="text" id="model_number" name="model_number" class="form-control mb-3" title="Format: 2 letters followed by 4 digits" placeholder="Model Number e.g., AB1234" value="<?php if(isset($model_number)) {echo htmlspecialchars($model_number, ENT_QUOTES, 'UTF-8');} ?>" pattern="^[a-zA-Z]{2}\d{4}$" required>
            <span class="error_message"><?php echo htmlspecialchars($model_number_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <!-- Serial Number input field -->
            <label for="serial_number" class="form-label">Serial Number<span>*</span></label>
            <input type="text" id="serial_number" name="serial_number" class="form-control mb-3" title="Format: SN followed by 8 digits" placeholder="Serial Number e.g., SN12345678" value="<?php if(isset($_GET['serial_number'])) {echo htmlspecialchars($_GET['serial_number'], ENT_QUOTES, 'UTF-8');} else if(isset($serial_number)) {echo htmlspecialchars($serial_number, ENT_QUOTES, 'UTF-8');} ?>" pattern="^[Ss][Nn]\d{8}$" required>
            <span class="error_message"><?php echo htmlspecialchars($serial_number_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <!-- Purchase date input field -->
            <label for="purchase_date" class="form-label">Purchase Date<span>*</span></label>
            <input type="date" id="purchase_date" name="purchase_date" class="form-control mb-3" value="<?php if(isset($purchase_date)) {echo htmlspecialchars($purchase_date, ENT_QUOTES, 'UTF-8');} ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($purchase_date_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <!-- Warranty Expiration Date -->
            <label for="warranty_expiration" class="form-label">Warranty Expiration Date<span>*</span></label>
            <input type="date" id="warranty_expiration" name="warranty_expiration" class="form-control mb-3" value="<?php if(isset($warranty_expiration)) {echo htmlspecialchars($warranty_expiration, ENT_QUOTES, 'UTF-8');} ?>" required>
            <span class="error_message"><?php echo htmlspecialchars($warranty_expiration_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <label for="cost" class="form-label">Cost (€)<span>*</span></label>
            <input type="number" id="cost" name="cost" class="form-control mb-3" title="Cost must be a positive number" placeholder="Cost e.g., 499.99" value="<?php if(isset($cost)) {echo htmlspecialchars($cost, ENT_QUOTES, 'UTF-8');} ?>" min="0" step="0.01">
            <span class="error_message"><?php echo htmlspecialchars($cost_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <!-- Submit button to register the appliance -->
            <button type="submit" class="btn btn-primary mt-3">Add Appliance</button>
        </form>
    </div>
</body>
</html>