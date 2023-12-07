<?php 
	include("../connection.php");
	include("../routeHandler.php");
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
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
                <form method="POST" action="index.php">
                <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
                <p><input type="submit" value="Reset" name="reset"></p>
                </form>
            </li>
		</ul>
	</nav>

	<main>

	<h1>Welcome to your Animal Shelter Management System!</h1>

	<h3>If you wish to reset the system press on the reset button on the navigation bar.
		If this is the first time you're running this page, you MUST use reset button.</h3>

	<h4>Below is some useful information about the shelter you manage: </h4>

	<?php
        connectToDB();

		$currShelterName = $_SESSION["shelterName"];
        $currShelterLoc = $_SESSION["shelterLocation"];

        $sql1 = "SELECT capacity
                FROM Shelter
                WHERE shelterName = '$currShelterName' AND shelterLocation = '$currShelterLoc'";
        $result1 = executePlainSQL($sql1);

		$sql2 = "SELECT COUNT(*) AS count
				FROM VolunteersAtShelter s
				INNER JOIN Volunteer v ON s.volunteerID = v.volunteerID
				WHERE s.shelterName = '$currShelterName' AND s.shelterLocation = '$currShelterLoc'
				ORDER BY v.volunteerID DESC";
		$result2 = executePlainSQL($sql2);

		$rowExisting1 = oci_fetch_assoc($result1);
        $countExisting1 = $rowExisting1['CAPACITY'];

		$rowExisting2 = oci_fetch_assoc($result2);
        $countExisting2 = $rowExisting2['COUNT'];

		// Projection
		$tables = array();
		global $attributes;
		$attributes = array();
	
		$allTablesSql = "SELECT table_name FROM user_tables";
		$allTables = executePlainSQL($allTablesSql);
	
		while ($row = oci_fetch_assoc($allTables)) {
			$tableName = $row['TABLE_NAME'];
			$tables[] = $tableName;
	
			$attributes[$tableName] = array();
	
			$attrSql = "SELECT column_name FROM user_tab_cols WHERE table_name = '$tableName'";
			$attrResult = executePlainSQL($attrSql);
	
			while ($attrRow = oci_fetch_assoc($attrResult)) {
				$attributes[$tableName][] = $attrRow['COLUMN_NAME'];
			}
		}
    ?>

	<p>Shelter Name: <?php echo $_SESSION["shelterName"]; ?></p>
	<p>Shelter Location: <?php echo $_SESSION["shelterLocation"]; ?></p>
	<p>Shelter Capacity: <?php echo $countExisting1; ?></p>
	<p>Number of Volunteers: <?php echo $countExisting2; ?></p>

	<h2> Select a table below to view it's contents: </h2>
	<form method='post' action='index.php'>
		<select name='selectedTable' onchange='this.form.submit()'>
			<option value=""></option>
			<?php
				foreach ($tables as $table) {
					echo "<option value='$table'>$table</option>";
				}
			?>
		</select>
	</form>

	<?php
		if (isset($_POST['selectedTable'])) {
			$_SESSION['currTable'] = $_POST['selectedTable'];
			$attributeOptions = $attributes[$_POST['selectedTable']];
		}
	?>

	<h2>Select attributes to view (hold control to select multiple)</h2>
	<form method='post' action='index.php'>
	<select multiple name='selectedAttributes[]'>
		<?php
		if (isset($attributeOptions)) {
			foreach ($attributeOptions as $attribute) {
				echo "<option value='$attribute'>$attribute</option>";
			}
		}
		?>
	</select>
	<input type='submit' name='submitAttributes' value='View Table'>
	</form>

	<?php
		$selectedAttributes = $_POST['selectedAttributes'];

		if (isset($_POST['selectedAttributes'])) {
			$sql = "SELECT " . implode(', ', $_POST['selectedAttributes']) . " FROM " . $_SESSION['currTable'];
    		$result = executePlainSQL($sql);

			echo "<h2>Table for: " . $_SESSION['currTable'] . "</h2>";
		}
	?>
		
	<?php
	if (!empty($selectedAttributes)) {
	?>
		<table border="1">
			<thead>
				<tr>
					<?php
						foreach ($selectedAttributes as $attribute) {
							echo "<th>" . $attribute . "</th>";
						}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				while ($row = oci_fetch_assoc($result)) {
					echo "<tr>";
					foreach ($selectedAttributes as $attribute) {
						echo "<td>" . $row[$attribute] . "</td>";
					}
					echo "</tr>";
				}
				?>
			</tbody>
		</table>
	<?php
	}
	?>

	</main>

	<?php
        disconnectFromDB();
    ?>
</body>
</html>