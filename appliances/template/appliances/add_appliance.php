<!-- Assignment 2 -->
<!-- Name: Emmanuel Ayobanjo -->
<!-- Student ID: 3173959 -->

<?php
    // Start the session to store error messages that may occur during form processing. This allows the error messages to persist across page redirects, enabling the display of specific error messages on the error page based on the type of error that occurred (e.g., duplicate entry).
    session_start();
    // Connect to the database using the configuration settings defined in the config.php file. This allows for a centralized location to manage database connection details, making it easier to maintain and update the connection settings if needed.
    require_once '../../../config.php';
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
    <title>Part C</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384- EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../static/appliances/style.css" type="text/css">
</head>
<body>
    <?php
        // Initialise error message variables to store any validation errors that may occur during form processing
        $first_name_error = "";
        $last_name_error = "";
        $address_error = "";
        $mobile_error = "";
        $email_error = "";        
        $eircode_error = "";
        $appliance_type_error = "";
        $brand_error = "";
        $model_number_error = "";
        $serial_number_error = "";
        $purchase_date_error = "";
        $warranty_expiration_error = "";
        $cost_error = "";

        $confirmation_message = "";

        // Initialise a boolean variable for each input to track whether the input is valid or not. This will be used to determine if the form can be successfully submitted.
        $is_first_name_valid = false;
        $is_last_name_valid = false;
        $is_address_valid = false;
        $is_mobile_valid = false;
        $is_email_valid = false;
        $is_eircode_valid = false;
        $is_appliance_type_valid = false;
        $is_brand_valid = false;
        $is_model_number_valid = false;
        $is_serial_number_valid = false;
        $is_purchase_date_valid = false;
        $is_warranty_expiration_valid = false;
        $is_cost_valid = false;

        // Initialise an array for existing appliance types.
        $appliance_types = [
            'Refrigerator',
            'Washing Machine',
            'Oven',
            'Microwave',
            'Dishwasher',
            'Dryer',
            'Air Conditioner',
            'Heater',
            'Vacuum Cleaner',
            'Television',
            'Sound System',
            'Computer',
            'Printer',
            'Coffee Maker',
            'Toaster',
            'Blender',
            'Food Processor',
            'Iron',
            'Fan',
            'Water Heater'
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve appliance details from the POST request and sanitize the input to prevent XSS attacks
            $first_name = trim(htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8'));
            $last_name = trim(htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8'));
            $address = trim(htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8'));
            $mobile = trim(htmlspecialchars($_POST['mobile'], ENT_QUOTES, 'UTF-8'));
            $email = trim(htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'));
            $eircode = trim(htmlspecialchars($_POST['eircode'], ENT_QUOTES, 'UTF-8'));
            $appliance_type = trim(htmlspecialchars($_POST['appliance_type'], ENT_QUOTES, 'UTF-8'));
            $brand = trim(htmlspecialchars($_POST['brand'], ENT_QUOTES, 'UTF-8'));
            $model_number = trim(htmlspecialchars($_POST['model_number'], ENT_QUOTES, 'UTF-8'));
            $serial_number = trim(htmlspecialchars($_POST['serial_number'], ENT_QUOTES, 'UTF-8'));
            $purchase_date = trim(htmlspecialchars($_POST['purchase_date'], ENT_QUOTES, 'UTF-8'));
            // In case the warranty_expiration is not set in the POST request, which can occur if the 
            // field is disabled and not submitted with the form. This ensures that $warranty_expiration 
            // will be an empty string instead of throwing an undefined index notice.
            // The default value of '0000-00-00' is used to indicate an invalid date, which will be caught during validation.
            $warranty_expiration = trim(htmlspecialchars($_POST['warranty_expiration'] ?? '0000-00-00', ENT_QUOTES, 'UTF-8'));
            $cost = trim(htmlspecialchars($_POST['cost'], ENT_QUOTES, 'UTF-8'));

            // Set the patterns for validating the Eircode, Brand, Model Number, 
            // Serial Number formats, purchase date, and warranty expiration date using regular expressions.
            $mobile_pattern = '/^(\+353|0)\d{9}$/'; // Validates Irish mobile numbers in the format +353 871234567 or 0871234567
            $eircode_pattern = '/^[a-zA-Z]\d{2} ?([a-zA-Z0-9]{2}\d{2}|[a-zA-Z]\d[a-zA-Z]\d)$/i'; // Valid Eircode Format (Case insensitive)
            $cork_eircode_pattern = '/^(T12|T23|T34|P12|P17|P24|P25|P31|P32|P36|P43|P47|P51|P56|P61|P67|P72|P75|P81|P85) ?([a-zA-Z0-9]{2}\d{2}|[a-zA-Z]\d[a-zA-Z]\d)$/i'; // Valid Cork Eircode Format (Case insensitive)
            $brand_pattern = '/^[a-zA-Z \'\-]{1,50}$/i'; // Validates that the brand name contains only letters, spaces, apostrophes, and hyphens, and is between 1 and 30 characters long (Case insensitive)            
            $model_number_pattern = '/^[a-zA-Z]{2}\d{4}$/i'; // Validates that the model number consists of 2 letters followed by 4 digits (Case insensitive)            
            $serial_number_pattern = '/^[Ss][Nn]\d{8}$/i'; // Validates that the serial number starts with "SN" followed by 8 digits (Case insensitive)           
            $purchase_date_pattern = '/^\d{4}-\d{2}-\d{2}$/'; // Validates that the purchase date is in the format YYYY-MM-DD         
            $warranty_expiration_pattern = '/^\d{4}-\d{2}-\d{2}$/'; // Validates that the warranty expiration date is in the format YYYY-MM-DD           
            $cost_pattern = '/^\d+(\.\d{2})?$/'; // Validates cost as a positive decimal number with up to 2 decimal places


            /*
            * Validate each field to ensure they are not empty and match the expected formats
            */

            // Check if the first name field is empty and if it matches the specified pattern. If validation fails, set the appropriate error message and redirect the user back to the registration form.
            if (empty($first_name)) {
                $first_name_error = "First name is required.";
            }
            else if (!preg_match("/^[a-zA-Z '-]+$/", $first_name)) {
                $first_name_error = "First name can only contain letters, spaces, apostrophes, and hyphens.";
            }
            else if (strlen($first_name) < 2) {
                $first_name_error = "First name should not be less than 2 characters";
            }
            else if (strlen($first_name) > 50) {
                $first_name_error = "First name should not be more than 50 characters";
            }
            else {
                $is_first_name_valid = true;
            }

            // Check if the last name field is empty and if it matches the specified pattern. If validation fails, set the appropriate error message and redirect the user back to the registration form.
            if (empty($last_name)) {
                $last_name_error = "Last name is required.";
            }
            else if (!preg_match("/^[a-zA-Z '-]+$/", $last_name)) {
                $last_name_error = "Last name can only contain letters, spaces, apostrophes, and hyphens.";
            }
            else if (strlen($last_name) < 2) {
                $last_name_error = "Last name should not be less than 2 characters";
            }
            else if (strlen($last_name) > 50) {
                $last_name_error = "Last name should not be more than 50 characters";
            }
            else {
                $is_last_name_valid = true;
            }

            // Check if the address field is empty. If it is, set the error message and redirect back to the form.
            if (empty($address)) {
                $address_error = "Address is required.";
            }
            else if (strlen($address) < 5) {
                $address_error = "Address should not be less than 5 characters";
            }
            else if (strlen($address) > 100) {
                $address_error = "Address should not be more than 100 characters";
            }
            else {
                $is_address_valid = true;
            }

            // Check if the mobile number field is empty and if it matches the specified pattern. If validation fails, set the appropriate error message and redirect the user back to the registration form.
            if (empty($mobile)) {
                $mobile_error = "Mobile number is required.";
            }
            else if (!preg_match($mobile_pattern, $mobile)) {
                $mobile_error = "Invalid mobile number format.";
            }
            else {
                $is_mobile_valid = true;
            }

            // Check if the email field is empty and if it matches the specified pattern. If validation fails, set the appropriate error message and redirect the user back to the registration form.
            if (empty($email)) {
                $email_error = "Email is required.";
            }
            else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_error = "Invalid email format.";
            }
            else if (strlen($email) > 254) {
                $email_error = "Email should not be more than 254 characters";
            }
            else {
                $is_email_valid = true;
            }

            // Check if the Eircode field is empty and if it matches the specified pattern. 
            // If validation fails, set the appropriate error message and redirect the user back 
            // to the registration form.
            if (empty($eircode)) {
                $eircode_error = "Eircode is required.";
            }
            else if (!preg_match($eircode_pattern, $eircode)) {
                $eircode_error = "Invalid Eircode format.";
            }
            else {
                if (!preg_match($cork_eircode_pattern, $eircode)) {
                    $eircode_error = "Eircode must be a valid Cork Eircode.";
                }
                else {
                    $is_eircode_valid = true;
                }
            }


            // Check if the appliance type field is empty. If it is, set the error message and 
            // redirect back to the form.
            if (empty($appliance_type)) {
                $appliance_type_error = "Appliance type is required.";
            }
            // Check if the selected appliance type is valid by comparing it against the 
            // predefined list of appliance types. If the selected type is not in the list, 
            // set the error message and redirect back to the form
            else if (!in_array($appliance_type, $appliance_types)) {
                $appliance_type_error = "Invalid appliance type selected.";
            }
            else {
                $is_appliance_type_valid = true;
            }


            // Check if the brand field is empty and if it matches the specified pattern. If validation fails,
            // set the appropriate error message and redirect the user back to the registration form.
            if (empty($brand)) {
                $brand_error = "Brand is required.";
            }
            else if (!preg_match($brand_pattern, $brand)) {
                $brand_error = "Invalid brand format.";
            }
            else if (strlen($brand) < 2) {
                $brand_error = "Brand name should not be less than 2 characters";
            }
            else if (strlen($brand) > 50) {
                $brand_error = "Brand name should not be more than 50 characters";
            }
            else {
                $is_brand_valid = true;
            }

            // Check if the model number field is empty and if it matches the specified pattern.
            // If validation fails, set the appropriate error message and redirect back to the registration form.
            if (empty($model_number)) {
                $model_number_error = "Model number is required.";
            }
            else if (!preg_match($model_number_pattern, $model_number)) {
                $model_number_error = "Invalid model number format.";
            }
            else {
                $is_model_number_valid = true;
            }

            // Check if the serial number field is empty and if it matches the specified pattern.
            // If validation fails, set the appropriate error message and redirect back to the registration form.
            if (empty($serial_number)) {
                $serial_number_error = "Serial number is required.";
            }
            else if (!preg_match($serial_number_pattern, $serial_number)) {
                $serial_number_error = "Invalid serial number format.";
            }
            else {
                $is_serial_number_valid = true;
            }

            // Check if the purchase date field is empty and if it matches the specified pattern. 
            // If validation fails, set the appropriate error message and redirect back to the registration form.
            if (empty($purchase_date)) {
                $purchase_date_error = "Purchase date is required.";
            }
            else if (!preg_match($purchase_date_pattern, $purchase_date)) {
                $purchase_date_error = "Invalid purchase date format.";
            }
            else {
                $is_purchase_date_valid = true;
            }
            
            // Check if the warranty expiration field is empty and if it matches the specified pattern.
            // If validation fails, set the appropriate error message and redirect back to the registration form.
            if (empty($warranty_expiration)) {
                $warranty_expiration_error = "Warranty expiration date is required.";
            }
            else if (!preg_match($warranty_expiration_pattern, $warranty_expiration)) {
                $warranty_expiration_error = "Invalid warranty expiration date format.";
            }
            // Ensure the warranty expiration date is not before the purchase date. 
            // If it is, set the error message and redirect back to the form.
            else if (strtotime($warranty_expiration) < strtotime($purchase_date)) {
                $warranty_expiration_error = "Warranty expiration date cannot be before the purchase date.";
            }
            else {
                $is_warranty_expiration_valid = true;
            }

            // Check if the cost field is empty and if it matches the specified pattern. If validation fails, set the appropriate error message and redirect back to the registration form.
            if (empty($cost)) {
                $cost_error = "Cost is required.";
            }
            else if (!preg_match($cost_pattern, $cost)) {
                $cost_error = "Invalid cost format. Cost must be a positive number with up to 2 decimal places.";
            }
            else {
                $is_cost_valid = true;
            }


            // If any of the inputs is invalid, redirect to the form and show the error messages. Otherwise, show a confirmation message.
            if ($is_first_name_valid && $is_last_name_valid && $is_address_valid && 
                $is_mobile_valid && $is_email_valid && $is_eircode_valid && 
                $is_appliance_type_valid && $is_brand_valid && 
                $is_model_number_valid && $is_serial_number_valid && 
                $is_purchase_date_valid && $is_warranty_expiration_valid && $is_cost_valid)
            {
                // Check if the serial number already exists in the database to prevent duplicate entries.
                $serial_number_check_sql = "SELECT * FROM appliance WHERE serial_number = '$serial_number'";
                $serial_number_check_result = mysqli_query($con, $serial_number_check_sql);

                if (mysqli_num_rows($serial_number_check_result) > 0) {
                    $_SESSION['duplicate_error'] = true;
                    header("Location: error.php");
                    exit();
                }

                // If all inputs are valid, insert the appliance and user details into the database and redirect to the confirmation page.
                // The table names are retrieved from the config.php file to ensure that any changes to the table names will be automatically reflected in the SQL queries without needing to manually update the code. Also for security reasons, the input values are sanitized using prepared statements to prevent SQL injection attacks.
                $user_sql = "INSERT INTO $table1 (first_name, last_name, address, mobile, email, eircode) 
                        VALUES ('" . mysqli_real_escape_string($con, $first_name) . "', '" . mysqli_real_escape_string($con, $last_name) . "', '" . mysqli_real_escape_string($con, $address) . "', '" . mysqli_real_escape_string($con, $mobile) . "', '" . mysqli_real_escape_string($con, $email) . "', '" . mysqli_real_escape_string($con, $eircode) . "')";

                // Start a transaction to ensure that both the appliance and user details are inserted successfully. If either of the queries fail, roll back the transaction and redirect to the error page.
                mysqli_begin_transaction($con);

                // Insert the user before the appliance because of the foreign key constraint on the appliance table that references the user table. This ensures that the user record is created before the appliance record that references it, preventing any foreign key constraint violations.
                $user_result = mysqli_query($con, $user_sql);

                if ($user_result) {
                    // Get the auto-incremented user_id generated from the user insertion to use as a foreign key in the appliance table. This allows us to associate the appliance with the correct user in the database.
                    $user_id = mysqli_insert_id($con);
                    
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
                }

                if ($user_result && $appliance_result) {
                    mysqli_commit($con);
                    header("Location: confirmation.html");
                    exit();
                } else {
                    mysqli_rollback($con);
                    // Show the actual error to help debug
                    die("SQL Error: " . mysqli_error($con));
                }
            }
        }
    ?>

    <a href="../../../index.html" class="btn btn-secondary mt-3 ms-3">Back to Home</a>
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
                <?php foreach ($appliance_types as $type) { ?>
                    <option value="<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>" <?php if (isset($appliance_type) && $appliance_type === $type) { echo 'selected'; } ?>>
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
            <input type="text" id="serial_number" name="serial_number" class="form-control mb-3" title="Format: SN followed by 8 digits" placeholder="Serial Number e.g., SN12345678" value="<?php if(isset($serial_number)) {echo htmlspecialchars($serial_number, ENT_QUOTES, 'UTF-8');} ?>" pattern="^[Ss][Nn]\d{8}$" required>
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