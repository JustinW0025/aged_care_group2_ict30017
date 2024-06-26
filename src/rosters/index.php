<?php include '../config.php'; ?>
<?php
// Get the staff ID from the query string
$staffId = isset($_SESSION['staffid']) ? intval($_SESSION['staffid']) : 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rosters</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div>
            <!-- Display the generated breadcrumbs -->
            <?php generateBreadcrumbs(); ?>
        </div>
        <h1>Upcoming Rosters</h1>
        <?php
        // Query to fetch rosters
        $query = "SELECT r.*, s.Name AS StaffName, ml.Name AS ManagedLocationName 
                FROM Rosters r
                INNER JOIN Staff s ON r.StaffId = s.Id
                INNER JOIN ManagedLocations ml ON r.ManagedLocationId = ml.Id
                WHERE r.StartTime > CURRENT_TIME";
        $result = $mysqli->query($query);
        if ($result->num_rows > 0) {
        ?>
        <div class="row">
            <div class="col">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Staff Name</th>
                            <th>Service Type</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Managed Location</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Display the data in the table rows
                        while ($row = $result->fetch_assoc()) {
                            // Construct the URL with rosterId as query parameter
                            $url = "/service_records/index.php?rosterid=" . $row['Id'];
                            $urlAdd = "/service_records/add.php?rosterid=" . $row['Id'];
                            $urlEdit = "/rosters/edit.php?id=" . $row['Id'];
                            echo "<tr>";
                            echo "<td>{$row['StaffName']}</td>";
                            echo "<td>{$row['ServiceType']}</td>";
                            echo "<td>{$row['StartTime']}</td>";
                            echo "<td>{$row['EndTime']}</td>";
                            echo "<td>{$row['ManagedLocationName']}</td>";
                            echo "<td>{$row['Notes']}</td>";
                            echo "<td>";
                            echo "<a href='{$url}' class='btn btn-primary add-button m-2'>View Services</a>";
                            $startTime = strtotime($row['StartTime']);
                            $currentTime = time();
                            if ($startTime > $currentTime) {
                                echo "<a href='{$urlAdd}' class='btn btn-primary add-button'>Add Service</a>";
                                echo "<a href='{$urlEdit}' class='btn btn-primary add-button'>Edit</a>";
                            } else {
                                echo "<a href='{$urlAdd}' class='btn btn-primary add-button disabled' aria-disabled>Add Service</a>";
                                echo "<a href='{$urlEdit}' class='btn btn-primary add-button disabled' aria-disabled>Edit</a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                        // Close database connection
                        // $mysqli->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } else {
            echo "<p>No upcoming rosters</p>";
        }
        ?>

        <?php
        // Table names
        $availabilitiesTable = "Availabilities";
        ?>

        <?php
        // SQL query to retrieve availabilities with staff names
        $availabilitiesQuery = "SELECT Availabilities.Id, Availabilities.StartTime, Availabilities.EndTime, Availabilities.StaffId, Staff.Name AS StaffName FROM $availabilitiesTable JOIN Staff ON Availabilities.StaffId = Staff.Id WHERE Availabilities.StartTime > CURRENT_TIME";

        // Execute the availabilities query
        $availabilitiesResult = $mysqli->query($availabilitiesQuery);
        ?>

        <h1>Current Availabilities</h1>

        <?php
        // Display availabilities with staff names in a table
        if ($availabilitiesResult->num_rows > 0) {
            echo "<table class='table'>";
            echo "<thead><tr>";
            echo "<th>Staff Name</th>";
            echo "<th>Start Time</th>";
            echo "<th>End Time</th>";
            echo "<th>Actions</th>";
            echo "</tr></thead><tbody>";
            while ($row = $availabilitiesResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['StaffName']}</td>";
                echo "<td>{$row['StartTime']}</td>";
                echo "<td>{$row['EndTime']}</td>";
                $startTime = strtotime($row['StartTime']);
                $currentTime = time();
                if ($startTime > $currentTime) {
                    echo "<td><a href='/availabilities/edit.php?id={$row['Id']}' class='btn btn-primary add-button'>Edit</a></td>";
                } else {
                    echo "<td><a href='/availabilities/edit.php?id={$row['Id']}' class='btn btn-primary add-button disabled' aria-disabled>Edit</a></td>";
                }
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No staff availabilities at the moment</p>";
        }
        $availabilitiesResult->free();
        ?>

        <a href="/rosters/add.php" class="btn btn-primary add-button button-gap my-4">Create Roster</a>
        <a href="/rosters/past.php" class="btn btn-primary add-button button-gap my-4">View past rosters and availabilities</a>

        <?php
        // Close connection
        $mysqli->close();
        ?>
    </div>
</body>

</html>