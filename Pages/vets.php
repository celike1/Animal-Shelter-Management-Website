<?php
    include_once('../routeHandler.php');
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vets</title>
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
                <form method="POST" action="vets.php">
                <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
                <p><input type="submit" value="Reset" name="reset"></p>
                </form>
            </li>
		</ul>
	</nav>

    <main>
        <p>If you wish to reset the table press on the reset button on the navigation bar above. If this is the first time you're running this page, you MUST use reset</p>

        <div style="display: flex; justify-content: center;">
    
        <form method="POST" action="vets.php" style="border: 1px solid #ccc; padding: 15px; border-radius: 10px; background-color: #cccccc;">
            <h2>Add a new Vet below:</h2>    
            <p>ID's are in the format 'VXXX' where V are numbers.</p>
        <input type="hidden" id="insertVetRequest" name="insertVetRequest">
            Id: <input type="text" name="vetID" pattern="V\d{3}" title="Invalid entry. Please follow the format above." required> <br /><br />
            Name: <input type="text" name="vetName" maxlength="255" required> <br /><br />
            Specialty: <input type="text" name="specialty" maxlength="255" required> <br /><br />
            Years of Experience: <input type="number" name="yearsOfExperience" required> <br /><br />
            Vet Location: <input type="text" name="vetLocation" maxlength="255" required> <br /><br />
        <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <form action="vets.php" method="post" style="border: 1px solid #ccc; padding: 15px; border-radius: 10px; background-color: #cccccc; margin-left: 10px;">
            <h2>Specify experience level of Vet:</h2>  
            <p>Enter a number below to see which specialties have vets in your shelter that match the minimum years of experience you specified.</p>
            <input type="hidden" id="insertYearsRequest" name="insertYearsRequest">
            Minimum Years of Experience: <input type="number" name="minYearsOfExperience" required> <br /><br />
            <input type="submit" value="Find Vets" name="insertSubmit">
            
        </form>   

        </div>

        

    

    <?php
        connectToDB();

        $currShelterName = $_SESSION["shelterName"];
        $currShelterLoc = $_SESSION["shelterLocation"];
        $minYears = $_POST['minYearsOfExperience'];

        $sql = "SELECT v.vetID, v.vetName, v.specialty, v.yearsOfExperience, v.vetLocation
                FROM VetWorksAtShelter s
                INNER JOIN Vet v ON s.vetID = v.vetID
                WHERE s.shelterName = '$currShelterName' AND s.shelterLocation = '$currShelterLoc'";
        $result = executePlainSQL($sql);


        if (isset($minYears)){

            $sql_years = "SELECT DISTINCT(v.specialty)
            FROM VetWorksAtShelter s
            INNER JOIN Vet v ON s.vetID = v.vetID
            WHERE s.shelterName = '$currShelterName' AND s.shelterLocation = '$currShelterLoc'
            GROUP BY v.specialty, v.yearsOfExperience
            HAVING v.yearsOfExperience >= $minYears";
            
            $result_years = executePlainSQL($sql_years);
        } 

    ?>


<div style="display: flex; justify-content: center;">
    

    <table border="1" style="margin-right: 10px;">
        <caption style="caption-side: top; font-size: 1.5em; font-weight: bold; padding: 10px;">
            Vet Information
        </caption>
        <thead>
            <tr>
                <th>Vet ID</th>
                <th>Name</th>
                <th>Specialty</th>
                <th>Years of Experience</th>
                <th>Vet Location</th>
            </tr>
        </thead>
        <tbody>

        <?php
            while ($row = oci_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['VETID'] . '</td>';
                echo '<td>' . $row['VETNAME'] . '</td>';
                echo '<td>' . $row['SPECIALTY'] . '</td>';
                echo '<td>' . $row['YEARSOFEXPERIENCE'] . '</td>';
                echo '<td>' . $row['VETLOCATION'] . '</td>';
                echo '</tr>';
            }
        ?>

        </tbody>
    </table>


    <table border="1" style="margin-left: 10px;">
        <caption style="caption-side: top; font-size: 1.5em; font-weight: bold; padding: 10px;">
            Specialties  
        </caption>
        <thead>
            <tr>
                <th>Specialty</th>
            </tr>
        </thead>
        <tbody>

        <?php
            while ($row_years = oci_fetch_assoc($result_years)) {
                echo '<tr>';
                echo '<td>' . $row_years['SPECIALTY'] . '</td>';
                echo '</tr>';
            }
        ?>

        </tbody>
    </table>
</div>

    </main>

    <?php
        oci_free_statement($result);
        disconnectFromDB();
    ?>

</body>
</html>