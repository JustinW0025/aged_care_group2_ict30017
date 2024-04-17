<?php
// Database connection parameters
$host = "db";
$port = "3306";
$user = "admin";
$password = "admin";
$database = "aged_care";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    $mysqli = new mysqli($host, $user, $password, $database, $port);
    // Check connection
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    // Prepare and bind parameters
    $stmt = $mysqli->prepare("INSERT INTO ServiceRecords (MemberId, StaffId, ServiceType, StartTime, EndTime, ManagedLocationId, Notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssis", $memberId, $staffId, $serviceType, $startTime, $endTime, $location, $notes);

    // Set parameters from form data
    $memberId = $_POST['member_id'];
    $staffId = $_POST['staff_id'];
    $serviceType = $_POST['service_type'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];
    $location = isset($_POST['managed_location_id']) ? $_POST['managed_location_id'] : null; // Handle if no location is selected
    $notes = $_POST['notes'];

    // Execute statement
    if ($stmt->execute()) {
        echo "New record added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Service Record</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Service Record</h2>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <div class="mb-3">
                <label for="member_id" class="form-label">Member:</label>
                <select id="member_id" name="member_id" class="form-select">
                    <option value="">Please select member</option>
                    <?php
                    // Fetch Member IDs and full names
                    $mysqli = new mysqli($host, $user, $password, $database, $port);
                    $member_query = "SELECT Id, CONCAT(FirstName, ' ', LastName) AS FullName FROM Members";
                    $member_result = $mysqli->query($member_query);
                    while ($row = $member_result->fetch_assoc()) {
                        echo "<option value='" . $row['Id'] . "'>" . $row['FullName'] . "</option>";
                    }
                    $mysqli->close();
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="staff_id" class="form-label">Staff:</label>
                <select id="staff_id" name="staff_id" class="form-select">
                    <option value="">Please select staff</option>
                    <?php
                    // Fetch Staff IDs and names
                    $mysqli = new mysqli($host, $user, $password, $database, $port);
                    $staff_query = "SELECT Id, Name FROM Staff";
                    $staff_result = $mysqli->query($staff_query);
                    while ($row = $staff_result->fetch_assoc()) {
                        echo "<option value='" . $row['Id'] . "'>" . $row['Name'] . "</option>";
                    }
                    $mysqli->close();
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="service_type" class="form-label">Service Type:</label>
                <select id="service_type" name="service_type" class="form-select" required>
                    <option value="">Select Service Type</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="caring">Caring</option>
                    <option value="consultation">Consultation</option>
                    <option value="special_request">Special Request</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="start_time" class="form-label">Start Time:</label>
                <input type="datetime-local" id="start_time" name="start_time" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="end_time" class="form-label">End Time:</label>
                <input type="datetime-local" id="end_time" name="end_time" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="managed_location_id" class="form-label">Managed Location:</label>
                <select id="managed_location_id" name="managed_location_id" class="form-select">
                    <option value="">Please select managed location</option>
                    <?php
                    // Fetch Managed Location IDs and names
                    $mysqli = new mysqli($host, $user, $password, $database, $port);
                    $location_query = "SELECT Id, Name FROM ManagedLocations";
                    $location_result = $mysqli->query($location_query);
                    while ($row = $location_result->fetch_assoc()) {
                        echo "<option value='" . $row['Id'] . "'>" . $row['Name'] . "</option>";
                    }
                    $mysqli->close();
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes:</label>
                <textarea id="notes" name="notes" class="form-control" rows="4"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
