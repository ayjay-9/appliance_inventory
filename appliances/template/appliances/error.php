<!-- Assignment 2 -->
<!-- Name: Emmanuel Ayobanjo -->
<!-- Student ID: 3173959 -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384- EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../static/House_Appliance_Inventory/style.css" type="text/css">
</head>
<body>
    <?php
        require_once 'add_appliance.php';
    ?>
    <?php if ($duplicate_error) { ?>
        <div class="alert alert-danger text-center" role="alert">
            <h1>Error</h1>
            <img src="../../static/appliances/error.png" alt="Error" class="img-fluid mt-3 mb-3" style="max-width: 150px;">
            <p>
                Appliance already exists in the inventory. Please try re-submitting the
                <a href="add_appliance.php">Inventory Application</a>
            </p>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger text-center" role="alert">
            <h1>Error</h1>
            <img src="../../static/appliances/error.png" alt="Error" class="img-fluid mt-3 mb-3" style="max-width: 150px;">
            <p>
                An error occurred while processing your request. Please try re-submitting the
                <a href="add_appliance.php">Inventory Application</a>
            </p>
        </div>
    <?php } ?>
</body>
</html>