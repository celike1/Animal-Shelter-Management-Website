<?php
    include_once('../routeHandler.php');
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adopters</title>
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
                <form method="POST" action="adopters.php">
                <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
                <p><input type="submit" value="Reset" name="reset"></p>
                </form>
            </li>
		</ul>
	</nav>

    <main>

    <p>If you wish to reset the table press on the reset button on the navigation bar above. If this is the first time you're running this page, you MUST use reset</p>

    <h2>Add new Adopter below:</h2>
        <p>ID's are in the format 'AXXX' where X are numbers. National ID must be 10 characters. Animal ID is 'BXXX', 'CXXX', or 'DXXX' (representing bird, cat or dog).
            Phone numbers are positive.
        </p>
        <form method="POST" action="adopters.php">
            <input type="hidden" id="insertAdopterRequest" name="insertAdopterRequest">
            Id: <input type="text" name="adptID" pattern="A\d{3}" title="Invalid entry. Please follow the format above." required> <br /><br />
            National Id: <input type="text" name="natID" pattern=".{10}" title="Invalid entry. Please follow the format above."> <br /><br />
            Name: <input type="text" name="adptName" maxlength="255"> <br /><br />
            Phone Number: <input type="number" name="adptNum"> <br /><br />
            Email: <input type="text" name="adptEmail" maxlength="225"> <br /><br />
            House number: <input type="text" name="adptHouseNum" maxlength="225"> <br /><br />
            Postal Code: <input type="text" name="adptPostalCode" maxlength="225"> <br /><br />
            City: <input type="text" name="adptCity" maxlength="225"> <br /><br />
            Steet name: <input type="text" name="adptStreetName" maxlength="225"> <br /><br />
            Province: <input type="text" name="adptProvince" maxlength="225"> <br /><br />
            ID of Animal they are adopting: <input type="text" name="adptAnimalID" pattern="^[BCD]\d{3}" required> <br /><br />
        <input type="submit" value="Insert" name="insertSubmit"></p>
    </form>

    <!-- example of update query -->
    <h2>Update Adopter info below:</h2>
        <p>ID entered must match with an already existing ID. ID cannot be changed. Please select and ID and then input the value you will be changing. Emails must be unique and National IDs must be unique.</p>
        <form method="POST" action="adopters.php">
            <input type="hidden" id="updateAdopterRequest" name="updateAdopterRequest">
            Id: <input type="text" name="adptID" pattern="A\d{3}" title="Invalid entry. Please follow the format above." required> <br /><br />
            <br /><br />
            National Id: <input type="text" name="natID" pattern=".{10}" title="Invalid entry. Please follow the format above.">
            <input type="submit" value="Update" name="updateSubmit"></p>
            Name: <input type="text" name="adptName" maxlength="255">
            <input type="submit" value="Update" name="updateSubmit"></p>
            Phone Number: <input type="number" name="adptNum">
            <input type="submit" value="Update" name="updateSubmit"></p>
            Email: <input type="text" name="adptEmail" maxlength="225">
            <input type="submit" value="Update" name="updateSubmit"></p>
            House number: <input type="text" name="adptHouseNum" maxlength="225">
            <input type="submit" value="Update" name="updateSubmit"></p>
            Postal Code: <input type="text" name="adptPostalCode" maxlength="225">
        <input type="submit" value="Update" name="updateSubmit"></p>
    </form>

    <h1>List of adopters</h1>

    <form method="GET">
    <label for="view">Select View:</label>
    <select name="view" id="view" onchange="this.form.submit()">
        <option value=""></option>
        <option value="with_address">View with Address</option>
        <option value="without_address">View without Address</option>
    </select>
    </form>

    <br></br>

    <?php
        $currShelterName = $_SESSION["shelterName"];
        $currShelterLoc = $_SESSION["shelterLocation"];

        $view = isset($_GET['view']) ? $_GET['view'] : 'with_address';
        connectToDB();
        $sql = '';
        if ($view === 'with_address') {
            // Include the address columns in the query
            $sql = "SELECT *
                    FROM AdoptersInfo i NATURAL LEFT OUTER JOIN AdoptersLocation l
                    INNER JOIN Adopt a ON a.adopterId = i.adopterID
                    INNER JOIN RegisteredAnimal r ON a.animalID = r.animalID
                    INNER JOIN Shelter s ON s.shelterName = r.shelterName AND s.shelterLocation = r.shelterLocation
                    WHERE s.shelterName = '$currShelterName' AND s.shelterLocation = '$currShelterLoc'
                    ORDER BY i.adopterID DESC";
        } elseif ($view === 'without_address') {
            // Exclude the address columns in the query
            $sql = "SELECT i.ADOPTERID, i.NATIONALID, i.ADOPTERNAME, i.PHONENUMBER, i.EMAIL, a.animalID
                    FROM AdoptersInfo i
                    INNER JOIN Adopt a ON a.adopterId = i.adopterID
                    INNER JOIN RegisteredAnimal r ON a.animalID = r.animalID
                    INNER JOIN Shelter s ON s.shelterName = r.shelterName AND s.shelterLocation = r.shelterLocation
                    WHERE s.shelterName = '$currShelterName' AND s.shelterLocation = '$currShelterLoc'
                    ORDER BY i.ADOPTERID DESC";
        }
        $result = executePlainSQL($sql);

        // get animalIDs for animals in current shelter:
        $sql2 = "SELECT *
                FROM RegisteredAnimal
                WHERE shelterName = '$currShelterName' AND shelterLocation = '$currShelterLoc' AND adopted = 0";
        $result2 = executePlainSQL($sql2);
    ?>

    <table border="1">
        <thead>
            <tr>
                <th>Adopter ID</th>
                <th>National ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>ID of Animal Adopted</th>
                <?php if ($view === 'with_address') { ?>
                    <th>House Number</th>
                    <th>Postal Code</th>
                    <th>City</th>
                    <th>Street Name</th>
                    <th>Province</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>

        <?php
            while ($row = oci_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['ADOPTERID'] . '</td>';
                echo '<td>' . $row['NATIONALID'] . '</td>';
                echo '<td>' . $row['ADOPTERNAME'] . '</td>';
                echo '<td>' . $row['PHONENUMBER'] . '</td>';
                echo '<td>' . $row['EMAIL'] . '</td>';
                echo '<td>' . $row['ANIMALID'] . '</td>';

                if ($view === 'with_address') {
                    echo '<td>' . $row['HOUSENUMBER'] . '</td>';
                    echo '<td>' . $row['POSTALCODE'] . '</td>';
                    echo '<td>' . $row['CITY'] . '</td>';
                    echo '<td>' . $row['STREETNAME'] . '</td>';
                    echo '<td>' . $row['PROVINCE'] . '</td>';
                }
            }
        ?>

        </tbody>
    </table>

    <h3>List of UNADOPTED animal IDs in this shelter</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Animal ID</th>
            </tr>
        </thead>
        <tbody>

        <?php
            while ($row = oci_fetch_assoc($result2)) {
                echo '<tr>';
                echo '<td>' . $row['ANIMALID'] . '</td>';
            }
        ?>

        </tbody>
    </table>

    </main>
    <?php
        oci_free_statement($result);
        disconnectFromDB();
    ?>

</body>
</html>