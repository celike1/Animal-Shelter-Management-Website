<?php
include_once('../routeHandler.php');
session_start();
?>

<!DOCTYPE html>
<html>

<head>
	<title>Animals</title>
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
				<form method="POST" action="animals.php">
					<input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
					<p><input type="submit" value="Reset" name="reset"></p>
				</form>
			</li>
		</ul>
	</nav>

	<main>
		<p>If you wish to reset the table press on the reset button on the navigation bar above. If this is the first
			time you're running this page, you MUST use reset</p>


			<!-- Add new animals -->
			<form method="POST" action="animals.php"
				style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;margin: 20px;background-color: #cccccc;">
				<h2 style="margin: 0; padding-bottom: 10px;">Add a new Animal below:</h2>
				<p>AnmialID's are in the format 'CXXX or BXXX or DXXX' where X are integers between 0-9.
				    Enter BXXX if you want to add Birds.
					Enter CXXX if you want to add Cats.
					Enter DXXX if you want to add Dogs.
					Enter 1 for True, 0 otherwise.
					You can only update existing animals in this shelter.
					You should fill in ALL blanks in the form.
				</p>
				<input type="hidden" id="insertAnimalRequest" name="insertAnimalRequest">
				AnimalID: <input type="text" name="animalID" maxlength="255" pattern="[CDB]\d{3}" title="Please enter the animal ID in the required format" required>
				Name: <input type="text" name="name" maxlength="255" required> 
				Adopted (type 1 or 0): <input type="text" name="adopted" maxlength="255" required
					title="Please follow the required format above"> <br /><br />
				Description: <input type="text" name="description" required> <br /><br />
				Age: <input type="number" name="age" required> 
				Weight: <input type="number" name="weight" required> 
				Breed: <input type="text" name="breed" required> <br /><br />
				<p>Fill in the following blanks if AnimalID Starts with C</p>
				HasFur (type 1 or 0): <input type="text" name="hasFur" > 
				Social (type 1 or 0): <input type="text" name="social"> <br /><br />
				<p>Fill in the following blanks if AnimalID Starts with D</p>
				MedicallyTrained (type 1 or 0): <input type="text" name="medicallyTrained"> 
				HasFur(type 1 or 0): <input type="text" name="hasFur"> <br /><br />
				<p>Fill in the following blanks if AnimalID Starts with B</p>
				BeakSize: <input type="number" name="beakSize"> 
				WingSpan: <input type="number" name="wingSpan"> 
				Color: <input type="text" name="color" > <br /><br />
				<input type="submit" value="Insert" name="insertSubmit">
			</form>


			<!-- Update Animal Information -->
			<form method="POST" action="animals.php"
				style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;margin: 20px;background-color: #cccccc;">
				<h2 style="margin: 0; padding-bottom: 10px;">Update Animal Info:</h2>
				<p>AnmialID's are in the format 'CXXX or BXXX or DXXX' where X are integers between 0-9.
				    Enter BXXX if you want to add Birds.
					Enter CXXX if you want to add Cats.
					Enter DXXX if you want to add Dogs.
					Enter 1 for True, 0 otherwise.
					You can only update existing animals in this shelter.
					You should fill in ALL blanks in the form.
				</p>
				<input type="hidden" id="upateAnimalRequest" name="updateAnimalRequest">
				AnimalID: <input type="text" name="animalID" maxlength="255" pattern="[CDB]\d{3}" title="Please enter the animal ID in the required format" required>
				Name: <input type="text" name="name" maxlength="255" required> 
				Adopted (type 1 or 0): <input type="text" name="adopted" maxlength="255" required
					title="Please follow the required format above"> <br /><br />
				Description: <input type="text" name="description" required> <br /><br />
				Age: <input type="number" name="age" required> 
				Weight: <input type="number" name="weight" required> 
				Breed: <input type="text" name="breed" required> <br /><br />
				<p>Fill in the following blanks if AnimalID Starts with C</p>
				HasFur (type 1 or 0): <input type="text" name="hasFurC" > 
				Social (type 1 or 0): <input type="text" name="social"> <br /><br />
				<p>Fill in the following blanks if AnimalID Starts with D</p>
				MedicallyTrained (type 1 or 0): <input type="text" name="medicallyTrained"> 
				HasFur(type 1 or 0): <input type="text" name="hasFurD"> <br /><br />
				<p>Fill in the following blanks if AnimalID Starts with B</p>
				BeakSize: <input type="number" name="beakSize"> 
				WingSpan: <input type="number" name="wingSpan"> 
				Color: <input type="text" name="color" > <br /><br />
				<input type="submit" value="Update" name="updateSubmit">
			</form>

			<!-- Delete Animals -->
			<form method="POST" action="animals.php"
				style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;margin: 20px;background-color: #cccccc;">
				<h2 style="margin: 0; padding-bottom: 10px;">Delete Animal:</h2>
				<p>AnmialID's are in the format 'CXXX or BXXX or DXXX' where X are integers between 0-9.
					You can only delete existing animals in this shelter.
				</p>
				<input type="hidden" id="deleteAnimalRequest" name="deleteAnimalRequest">
				AnimalID: <input type="text" name="animalID" maxlength="255" pattern="[CDB]\d{3}"
					title="Please enter the animal ID in the required format" required> <br /><br />
				<input type="submit" value="Delete" name="deleteSubmit">
			</form>


		<!-- List of Animals-->
		<h2>List of Registered Animals in this shelter</h2>
		<?php
		connectToDB();

		$currShelterName = $_SESSION["shelterName"];
		$currShelterLoc = $_SESSION["shelterLocation"];

		$sql = "SELECT * 
                FROM RegisteredAnimal
                WHERE shelterName = '$currShelterName' AND shelterLocation = '$currShelterLoc'";

		$result = executePlainSQL($sql);
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
				</tr>
			</thead>
			<tbody>

				<?php
				while ($row = oci_fetch_assoc($result)) {
					echo '<tr>';
					echo '<td>' . $row['ANIMALID'] . '</td>';
					echo '<td>' . $row['NAME'] . '</td>';
					echo '<td>' . ($row['ADOPTED'] ? 'Yes' : 'No') . '</td>';
					echo '<td>' . $row['DESCRIPTION'] . '</td>';
					echo '<td>' . $row['AGE'] . '</td>';
					echo '<td>' . $row['WEIGHT'] . '</td>';
					echo '<td>' . $row['BREED'] . '</td>';
					echo '</tr>';
				}
				?>

			</tbody>
		</table>

		<!-- Calculate average age of each breed-->
		<h2>Average age of each breed:</h2>
		<form method="POST" action="animals.php">
			<input type="hidden" id="calculateAvgRequest" name="calculateAvgRequest">
			<input type="submit" value="Calculate Average" name="insertSubmit">
		</form>
		<br>

		<?php
		global $calculateAvgRequestResult;
		if ($calculateAvgRequestResult != NULL) { ?>
			<table border="1">
				<thead>
					<tr>
						<th>Breed</th>
						<th>Average Age</th>
					</tr>
				</thead>
				<tbody>

				<?php
				while ($row = oci_fetch_assoc($calculateAvgRequestResult)) {
					echo '<tr>';
					echo '<td>' . $row['BREED'] . '</td>';
					echo '<td>' . $row['AVERAGEAGE'] . '</td>';
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
		<?php } ?>


	</main>


	<?php
	disconnectFromDB();
	?>

</body>

</html>