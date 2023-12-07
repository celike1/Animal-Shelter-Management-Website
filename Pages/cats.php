<?php
include_once('../routeHandler.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Cats</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
		<!-- Navbar -->
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
				<form method="POST" action="cats.php">
					<input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
					<p><input type="submit" value="Reset" name="reset"></p>
				</form>
			</li>
		</ul>
	</nav>

	<main>
		<p>If you wish to reset the table press on the reset button on the navigation bar above. If this is the first
			time you're running this page, you MUST use reset</p>

		<!-- Cats -->
		<h1>Lovely Cats</h1>

		<h2>List of Cats</h2>

		<?php
		connectToDB();

		$currShelterName = $_SESSION["shelterName"];
		$currShelterLoc = $_SESSION["shelterLocation"];

		$sql1 = "SELECT * 
                FROM Cats c
				INNER JOIN RegisteredAnimal a ON c.animalID = a.animalID
                WHERE a.shelterName = '$currShelterName' AND a.shelterLocation = '$currShelterLoc'";

		$result1 = executePlainSQL($sql1);
		?>

		<table border="1" style="margin: auto;">
			<thead>
				<tr>
					<th>AnimalID</th>
					<th>Name</th>
					<th>Adopted</th>
					<th>Description</th>
					<th>Age</th>
					<th>Weight</th>
					<th>Breed</th>
					<th>Has Fur</th>
					<th>Social</th>
				</tr>
			</thead>
			<tbody>

				<?php
				while ($row = oci_fetch_assoc($result1)) {
					echo '<tr>';
					echo '<td>' . $row['ANIMALID'] . '</td>';
					echo '<td>' . $row['NAME'] . '</td>';
					echo '<td>' . ($row['ADOPTED'] ? 'Yes' : 'No') . '</td>';
					echo '<td>' . $row['DESCRIPTION'] . '</td>';
					echo '<td>' . $row['AGE'] . '</td>';
					echo '<td>' . $row['WEIGHT'] . '</td>';
					echo '<td>' . $row['BREED'] . '</td>';
					echo '<td>' . ($row['HASFUR'] ? 'Yes' : 'No') . '</td>';
					echo '<td>' . ($row['SOCIAL'] ? 'Yes' : 'No') . '</td>';
					echo '</tr>';
				}
				?>

			</tbody>
		</table>

		<h2>List of Overweight Cats in this Shelter</h2>

		<?php
		connectToDB();

		$currShelterName = $_SESSION["shelterName"];
		$currShelterLoc = $_SESSION["shelterLocation"];

		$sql2 = "SELECT c.animalID,a.breed,a.weight
                FROM Cats c
				INNER JOIN RegisteredAnimal a ON c.animalID = a.animalID
                WHERE a.shelterName = '$currShelterName' AND a.shelterLocation = '$currShelterLoc'
				GROUP BY a.breed,c.animalID,a.weight
				HAVING a.weight > (SELECT AVG(b.weight) FROM RegisteredAnimal b
				                   INNER JOIN Cats d ON b.animalID = d.animalID
								   WHERE b.shelterName = '$currShelterName' AND b.shelterLocation = '$currShelterLoc')";

		$result2 = executePlainSQL($sql2);
		?>

		<table border="1" style="margin: auto;">
			<thead>
				<tr>
					<th>AnimalID</th>
					<th>Weight</th>
					<th>Breed</th>
				</tr>
			</thead>
			<tbody>

				<?php
				while ($row = oci_fetch_assoc($result2)) {
					echo '<tr>';
					echo '<td>' . $row['ANIMALID'] . '</td>';
					echo '<td>' . $row['WEIGHT'] . '</td>';
					echo '<td>' . $row['BREED'] . '</td>';
					echo '</tr>';
				}
				?>

			</tbody>
		</table>

		<h2>List of Cats get all vaccines in this shelter</h2>
		<form method="POST" action="cats.php">
			<input type="hidden" id="catUnvaccinatedRequest" name="catUnvaccinatedRequest">
			<input type="submit" value="View Result" name="insertSubmit">
		</form>
		<br>

		<?php
		global $catUnvaccinatedResult;
		if ($catUnvaccinatedResult) { ?>

			<table border="1">
				<thead>
					<tr>
						<th>AnimalID</th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody>


				<?php
				while ($row = oci_fetch_assoc($catUnvaccinatedResult)) {
					echo '<tr>';
					echo '<td>' . $row['ANIMALID'] . '</td>';
					echo '<td>' . $row['NAME'] . '</td>';
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
		<?php } ?>
		<hr />

		<h2>Looking for the perfect cat? We can search ALL the shelters for you! Select based on your interest below! (put 1 for if you want fur/social cat, 0 otherwise)</h2>
		<form method="POST" action="cats.php">
			<input type="hidden" id="selectAnimalRequest" name="selectAnimalRequest">
			Cat ID: <input type="text" name="animalID" pattern="C\d{3}">  
			<br /><br />
			<select name="operator1">
			    <option value="Or"> OR </option>
				<option value="And"> AND</option>
			</select>
			hasFur: <input type="text" name="hasFur" pattern="[01]">
			<br /><br />
			<select name="operator2">
			    <option value="Or"> OR </option>
				<option value="And"> AND</option>
			</select>
			Social: <input type="text" name="social" pattern="[01]"> 
			<input type="submit" value="Submit" name="insertSubmit">
		</form>
		<br>

		<?php
		global $selectAnimalRequestResult;
		if ($selectAnimalRequestResult != NULL) { ?>

			<table border="1">
				<thead>
					<tr>
						<th>Animal ID</th>
						<th>Has Fur</th>
						<th>Social</th>
						<th>Shelter Location</th>
						<th>Shelter Name</th>
					</tr>
				</thead>
				<tbody>

				<?php
				while ($row = oci_fetch_assoc($selectAnimalRequestResult)) {
					echo '<tr>';
					echo '<td>' . $row['ANIMALID'] . '</td>';
					echo '<td>' . $row['HASFUR'] . '</td>';
					echo '<td>' . $row['SOCIAL'] . '</td>';
					echo '<td>' . $row['SHELTERLOCATION'] . '</td>';
					echo '<td>' . $row['SHELTERNAME'] . '</td>';
					echo '</tr>';
				} ?>
			    </tbody>
		    </table>
		<?php } ?>


		<hr />



		
		<?php
	    oci_free_statement($result1);
	    oci_free_statement($result2);
	    disconnectFromDB();
	    ?>




</body>

</html>