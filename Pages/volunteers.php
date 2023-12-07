<?php
    include_once('../routeHandler.php');
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Volunteers</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

    <nav class="navbar">
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="volunteers.php">Volunteers</a></li>
			<li><a href="adopters.php">Adopters</a></li>
            <li><a href="vets.php">Vets</a></li>
			<li><a href="inspectors.php">Inspectors</a></li>
			<li><a href="events_ws.php">Events and Workshops</a></li>
            <li><a href="animals.php">Animals</a></li>
			<li><a href="dogs.php">Dogs</a></li>
			<li><a href="cats.php">Cats</a></li>
			<li><a href="birds.php">Birds</a></li>
			<li><a href="login.php">Logout</a></li>
            <li>
                <form method="POST" action="volunteers.php">
                <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
                <p><input type="submit" value="Reset" name="reset"></p>
                </form>
            </li>
		</ul>
	</nav>

    <main>
    <p>If you wish to reset the table press on the reset button on the navigation bar above. If this is the first time you're running this page, you MUST use reset</p>


    <!-- example of insert query -->
    <h2>Add new volunteer below:</h2>
        <p>ID's are in the format 'VXXX' where X are numbers.
            Available days are in the format 'XXXXXXX', where each X corresponds to each day of the week.
            X is represented with T (true) or F (false) values to indicate whether volunteer is available that day.
            Phone numbers must be positive.</p>
        <form method="POST" action="volunteers.php">
            <input type="hidden" id="insertVolunteerRequest" name="insertVolunteerRequest">
            Id: <input type="text" name="volID" pattern="V\d{3}" title="Invalid entry. Please follow the format above." required> <br /><br />
            Name: <input type="text" name="volName" maxlength="255" required> <br /><br />
            Days Available: <input type="text" name="volDays" pattern="[TF]{7}" title="Invalid entry. Please follow the format above."> <br /><br />
            Phone Number: <input type="number" name="volNum"> <br /><br />
        <input type="submit" value="Insert" name="insertSubmit"></p>
    </form>

    <h1>List of volunteers</h1>
    <p> Available volunteers have filled out their schedule and are consistently available at least once a week. </p>

    <?php
        connectToDB();

        $currShelterName = $_SESSION["shelterName"];
        $currShelterLoc = $_SESSION["shelterLocation"];
        
        $sql = "SELECT *
                FROM VolunteersAtShelter s
                INNER JOIN Volunteer v ON s.volunteerID = v.volunteerID
                LEFT OUTER JOIN AvailableDaysRegularVolunteer a ON v.availableDays = a.availableDays
                WHERE s.shelterName = '$currShelterName' AND s.shelterLocation = '$currShelterLoc'
                ORDER BY v.volunteerID DESC";
        $result = executePlainSQL($sql);

        $sql2 = "SELECT volunteerID
                FROM Volunteer
                ORDER BY volunteerID DESC";
        $result2 = executePlainSQL($sql2);
    ?>

    <table border="1">
        <thead>
            <tr>
                <th>Volunteer ID</th>
                <th>Name</th>
                <th>Days Available</th>
                <th>Regular Volunteer</th>
                <th>Phone Number</th>
                <th>Start date</th>
            </tr>
        </thead>
        <tbody>

        <?php
            while ($row = oci_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['VOLUNTEERID'] . '</td>';
                echo '<td>' . $row['NAME'] . '</td>';
                echo '<td>' . $row['AVAILABLEDAYS'] . '</td>';
                echo '<td>' . ($row['REGULARVOLUNTEER'] ? 'Yes' : 'No') . '</td>';
                echo '<td>' . $row['PHONENUMBER'] . '</td>';
                echo '<td>' . $row['SINCE'] . '</td>';
                echo '</tr>';
            }
        ?>

        </tbody>
    </table>

    <h1>Volunteer IDs already in use:</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Volunteer ID</th>
            </tr>
        </thead>
        <tbody>

        <?php
            while ($row = oci_fetch_assoc($result2)) {
                echo '<tr>';
                echo '<td>' . $row['VOLUNTEERID'] . '</td>';
                echo '</tr>';
            }
        ?>

        </tbody>
    </table>

    <!-- example of join query -->
    <h2>Find all regular or not regular volunteers in this shelter (enter 1 for regular and 0 otherwise)</h2>
        <form method="POST" action="volunteers.php">
            <input type="hidden" id="findVolunteerRequest" name="findVolunteerRequest">
            Regular: <input type="text" name="findVolDays" pattern="[01]" title="Invalid entry. Please follow the format above." required> <br /><br />
        <input type="submit" value="Insert" name="insertSubmit"></p>
    </form>

    <?php 
    global $findVolRequestResult;
    if ($findVolRequestResult) { ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Volunteer ID</th>
                </tr>
            </thead>
            <tbody>
    <?php } ?>

    <?php
        while ($row = oci_fetch_assoc($findVolRequestResult)) {
            echo '<tr>';
            echo '<td>' . $row['VOLUNTEERID'] . '</td>';
            echo '</tr>';
        }
    ?>

    </main>

    <?php
        disconnectFromDB();
    ?>

</body>
</html>