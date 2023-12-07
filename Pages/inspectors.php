<?php
    include_once('../routeHandler.php');
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inspectors</title>
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
                <form method="POST" action="inspectors.php">
                <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
                <p><input type="submit" value="Reset" name="reset"></p>
                </form>
            </li>
		</ul>
	</nav>

    <main>

        <p>If you wish to reset the table press on the reset button on the navigation bar above. If this is the first time you're running this page, you MUST use reset</p>

    <h2>Add new inspector below:</h2>
        <p>ID's are in the format 'IXXX' where X are numbers. Put 1 if passed inspection, else put 0.</p>
        <form method="POST" action="inspectors.php">
            <input type="hidden" id="insertInspectorRequest" name="insertInspectorRequest">
            Id: <input type="text" name="insID" pattern="I\d{3}" title="Invalid entry. Please follow the format above." required> <br /><br />
            Name: <input type="text" name="insName" maxlength="255" required> <br /><br />
            Did the shelter pass their inspection?: <input type="text" name="standardsMet" pattern="[01]" title="Invalid entry. Please follow the format above."> <br /><br />
        <input type="submit" value="Insert" name="insertSubmit"></p>
    </form>

    <h1>List of inspectors in this Shelter</h1>

    <?php
        connectToDB();

        $currShelterName = $_SESSION["shelterName"];
        $currShelterLoc = $_SESSION["shelterLocation"];

        $sql1 = "SELECT I.insID, I.insName, S.standardsMet
                FROM Inspector I
                INNER JOIN Inspect S ON I.insID = S.insID
                WHERE S.shelterName = '$currShelterName' AND S.shelterLocation = '$currShelterLoc'
                ORDER BY I.insID DESC";
        $result = executePlainSQL($sql1);

        $sql2 = "SELECT insID
                FROM Inspector
                ORDER BY insID DESC";
        $result2 = executePlainSQL($sql2);
    ?>

    <table border="1">
        <thead>
            <tr>
                <th>Inspector ID</th>
                <th>Name</th>
                <th>Shelter Passed Inspection</th>
            </tr>
        </thead>
        <tbody>

        <?php
            while ($row = oci_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['INSID'] . '</td>';
                echo '<td>' . $row['INSNAME'] . '</td>';
                echo '<td>' . ($row['STANDARDSMET'] === NULL ? '' : ($row['STANDARDSMET'] == 0 ? 'No' : 'Yes')) . '</td>';
                echo '</tr>';
            }
        ?>

        </tbody>
    </table>

    <h1>Inspector IDs already in use:</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Inspector ID</th>
            </tr>
        </thead>
        <tbody>

        <?php
            while ($row = oci_fetch_assoc($result2)) {
                echo '<tr>';
                echo '<td>' . $row['INSID'] . '</td>';
                echo '</tr>';
            }
        ?>

        </tbody>
    </table>

    </main>

    <?php
        disconnectFromDB();
    ?>

</body>
</html>