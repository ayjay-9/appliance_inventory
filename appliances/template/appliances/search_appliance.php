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
    <title>Search Appliance</title>
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
        <h1 class="text-center mb-4">Search Appliance</h1>
        <hr class="mb-1">
    </header>
    <div class="container mt-5">   
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET" class="mb-4" novalidate>
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Enter appliance serial number, e.g. SN12345678" value="<?php echo htmlspecialchars($_GET['query'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    
    <?php
        $query = $_GET['query'] ?? '';

        if (isset($_GET['query']) && $query !== '') {
            $sql = "SELECT * FROM $table2 
                        JOIN $table1 ON $table2.user_id = $table1.user_id 
                        WHERE $table2.serial_number = ?
                    ";

            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "s", $query);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $appliance = mysqli_fetch_assoc($result);
                echo '<div class="container mt-4">';
                echo '<h2>Appliance Details</h2>';
                echo '<table class="table table-bordered">';
                echo '<tr><th>Serial Number</th><td>' . htmlspecialchars($appliance['serial_number']) . '</td></tr>';
                echo '<tr><th>Appliance Type</th><td>' . htmlspecialchars($appliance['appliance_type']) . '</td></tr>';
                echo '<tr><th>Brand</th><td>' . htmlspecialchars($appliance['brand']) . '</td></tr>';
                echo '<tr><th>Model</th><td>' . htmlspecialchars($appliance['model_number']) . '</td></tr>';
                echo '<tr><th>Purchase Date</th><td>' . htmlspecialchars($appliance['purchase_date']) . '</td></tr>';
                echo '<tr><th>Warranty Expiration Date</th><td>' . htmlspecialchars($appliance['warranty_exp_date']) . '</td></tr>';
                echo '<tr><th>Appliance Cost</th><td>€' . htmlspecialchars($appliance['appliance_cost']) . '</td></tr>';
                echo '<tr><th>Owner Name</th><td>' . htmlspecialchars($appliance['first_name']) . ' ' . htmlspecialchars($appliance['last_name']) . '</td></tr>';
                echo '</table>';
                echo '<p> Showing ' . mysqli_num_rows($result) . ' result(s).</p>';
                echo '</div>';

                // Optionally update or delete the appliance with a link to the update_appliance.php
                echo '<div class="container mt-3">';
                echo '<a href="update_appliance.php?serial_number=' . urlencode($query) . '" class="btn btn-warning">Update Appliance?</a>';
                echo '<a href="delete_appliance.php?serial_number=' . urlencode($query) . '" class="btn btn-danger ms-2">Delete Appliance?</a>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-warning text-center" role="alert">';
                echo 'No appliance found with serial number: ' . '<strong>' . htmlspecialchars($query) . '</strong>. Would you like to <a href="add_appliance.php?serial_number=' . urlencode($query) . '" class="alert-link">add it to the inventory</a> or <a href="../../../index.html" class="alert-link">return to the homepage</a>?';
                echo '</div>';
            }
        }
        else if (isset($_GET['query'])) {
            echo '<div class="alert alert-info text-center" role="alert">';
            echo 'Please enter a serial number to search for an appliance in the inventory.';
            echo '</div>';
        }
    ?>
</body>
</html>