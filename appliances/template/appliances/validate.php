<!-- Assignment 2 -->
<!-- Name: Emmanuel Ayobanjo -->
<!-- Student ID: 3173959 -->

<!-- This file contains the server-side validation logic for the add_appliance.php and update_appliance.php forms. -->
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'database_con.php';

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
    $_SESSION['appliance_types'] = [
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
        else if (!in_array($appliance_type, $_SESSION['appliance_types'])) {
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


        // If any of the inputs is invalid, redirect to the form and show the error messages. Otherwise, add it to the database and show a confirmation message.
        if ($is_first_name_valid && $is_last_name_valid && $is_address_valid && 
            $is_mobile_valid && $is_email_valid && $is_eircode_valid && 
            $is_appliance_type_valid && $is_brand_valid && 
            $is_model_number_valid && $is_serial_number_valid && 
            $is_purchase_date_valid && $is_warranty_expiration_valid && $is_cost_valid)
        {
            // Check if user already exists by email
            $stmt = mysqli_prepare($con, "SELECT user_id FROM $table1 WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $check_user_result = mysqli_stmt_get_result($stmt);
        }
    }
?>