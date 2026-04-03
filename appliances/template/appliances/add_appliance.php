<!-- Assignment 2 -->
<!-- Name: Emmanuel Ayobanjo -->
<!-- Student ID: 3173959 -->

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
        $eircode_error = "";
        $appliance_type_error = "";
        $brand_error = "";
        $model_number_error = "";
        $serial_number_error = "";
        $purchase_date_error = "";
        $warranty_expiration_error = "";

        $confirmation_message = "";

        // Initialise a boolean variable for each input to track whether the input is valid or not. This will be used to determine if the form can be successfully submitted.
        $is_eircode_valid = false;
        $is_appliance_type_valid = false;
        $is_brand_valid = false;
        $is_model_number_valid = false;
        $is_serial_number_valid = false;
        $is_purchase_date_valid = false;
        $is_warranty_expiration_valid = false;

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
            $eircode = htmlspecialchars($_POST['eircode'], ENT_QUOTES, 'UTF-8');
            $appliance_type = htmlspecialchars($_POST['appliance_type'], ENT_QUOTES, 'UTF-8');
            $brand = htmlspecialchars($_POST['brand'], ENT_QUOTES, 'UTF-8');
            $model_number = htmlspecialchars($_POST['model_number'], ENT_QUOTES, 'UTF-8');
            $serial_number = htmlspecialchars($_POST['serial_number'], ENT_QUOTES, 'UTF-8');
            $purchase_date = htmlspecialchars($_POST['purchase_date'], ENT_QUOTES, 'UTF-8');
            // In case the warranty_expiration is not set in the POST request, which can occur if the 
            // field is disabled and not submitted with the form. This ensures that $warranty_expiration 
            // will be an empty string instead of throwing an undefined index notice.
            // The default value of '0000-00-00' is used to indicate an invalid date, which will be caught during validation.
            $warranty_expiration = htmlspecialchars($_POST['warranty_expiration'] ?? '0000-00-00', ENT_QUOTES, 'UTF-8');

            // Set the patterns for validating the Eircode, Brand, Model Number, 
            // Serial Number formats, purchase date, and warranty expiration date using regular expressions.
            $eircode_pattern = '/^[a-zA-Z]\d{2} ?([a-zA-Z0-9]{2}\d{2}|[a-zA-Z]\d[a-zA-Z]\d)$/i'; // Valid Eircode Format (Case insensitive)
            $cork_eircode_pattern = '/^(T12|T23|T34|P12|P17|P24|P25|P31|P32|P36|P43|P47|P51|P56|P61|P67|P72|P75|P81|P85) ?([a-zA-Z0-9]{2}\d{2}|[a-zA-Z]\d[a-zA-Z]\d)$/i'; // Valid Cork Eircode Format (Case insensitive)
            
            $brand_pattern = '/^[a-zA-Z \'\-]{1,30}$/i'; // Validates that the brand name contains only letters, spaces, apostrophes, and hyphens, and is between 1 and 30 characters long (Case insensitive)
            
            $model_number_pattern = '/^[a-zA-Z]{2}\d{4}$/i'; // Validates that the model number consists of 2 letters followed by 4 digits (Case insensitive)
            
            $serial_number_pattern = '/^[Ss][Nn]\d{8}$/i'; // Validates that the serial number starts with "SN" followed by 8 digits (Case insensitive)
            
            $purchase_date_pattern = '/^\d{4}-\d{2}-\d{2}$/'; // Validates that the purchase date is in the format YYYY-MM-DD
            
            $warranty_expiration_pattern = '/^\d{4}-\d{2}-\d{2}$/'; // Validates that the warranty expiration date is in the format YYYY-MM-DD


            /*
            * Validate each field to ensure they are not empty and match the expected formats
            */

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
            else if (strlen($brand) > 30) {
                $brand_error = "Brand name should not be more than 30 characters";
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


            // If any of the inputs is invalid, redirect to the form and show the error messages. Otherwise, show a confirmation message.
            if ($is_eircode_valid && $is_appliance_type_valid && $is_brand_valid && 
                $is_model_number_valid && $is_serial_number_valid && 
                $is_purchase_date_valid && $is_warranty_expiration_valid) 
            {
                header("Location: confirmation.php");
                exit(); // Terminate the script after redirecting to prevent further code execution
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

            <label for="last_name" class="form-label">Last Name<span>*</span></label>
            <input type="text" id="last_name" name="last_name" class="form-control mb-3" placeholder="Last Name" value="<?php if(isset($last_name)) {echo htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8');} ?>" required>

            <label for="address" class="form-label">Address<span>*</span></label>
            <input type="text" id="address" name="address" class="form-control mb-3" placeholder="Address" value="<?php if(isset($address)) {echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8');} ?>" required>

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

            <label for="cost" class="form-label">Cost<span>*</span></label>
            <input type="number" id="cost" name="cost" class="form-control mb-3" title="Cost must be a positive number" placeholder="Cost e.g., 499.99" value="<?php if(isset($cost)) {echo htmlspecialchars($cost, ENT_QUOTES, 'UTF-8');} ?>" min="0" step="0.01">
            <span class="error_message"><?php echo htmlspecialchars($cost_error, ENT_QUOTES, 'UTF-8'); ?></span>

            <!-- Submit button to register the appliance -->
            <button type="submit" class="btn btn-primary mt-3">Add Appliance</button>
        </form>
    </div>
</body>
</html>