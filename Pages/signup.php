<?php

	include("connection.php");

?>

<?php
    include_once('../routeHandler.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>


	<style type="text/css">
	
	#text{

		height: 25px;
		border-radius: 5px;
		padding: 4px;
		border: solid thin #aaa;
		width: 100%;
	}

	#button{

		padding: 10px;
		width: 100px;
		color: white;
		background-color: lightblue;
		border: none;
	}

	#box{

		background-color: grey;
		margin: auto;
		width: 300px;
		padding: 20px;
	}

	</style>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="signup.php">
                <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
                <p><input type="submit" value="Reset" name="reset"></p>
            </form>

    <div id="box">
        <div style="font-size: 20px;margin: 10px;color: white;">Sign up as a new manager below</div>
            <p>ID's are in the format 'MXXX' where X are numbers.</p>
            <form method="POST" action="signup.php">
            
                <input type="hidden" id="insertSignupRequest" name="insertSignupRequest">
                Manager ID: <input id = "text" type="text" name="manID" pattern="M\d{3}" title="Invalid entry. Please follow the format above." required> <br /><br />
                Password: <input id = "text" type="text" name="manPassword" maxlength="12" required> <br /><br />
                Shelter Name: <input id = "text" type="text" name="shelterName" required> <br /><br />
                Sheleter Location: <input id = "text" type="text" name="shelterLocation" required> <br /><br />

            <input id = "button" type="submit" value="Signup" name="signupSubmit"></p>

            <a href="login.php">Click to Login</a><br><br>
        </form>

    </div>


    <h1>List of Managers</h1>

    <?php
        connectToDB();
        $sql = 'SELECT * FROM Manager
                ORDER BY manID DESC';
        $result = executePlainSQL($sql);
    ?>

    <table border="1">
        <thead>
            <tr>
                <th>ManagerID</th>
                <th>Password</th>
                <th>Shelter Location</th>
                <th>Shelter Name</th>
            </tr>
        </thead>
        <tbody>

        <?php
            while ($row = oci_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['MANID'] . '</td>';
                echo '<td>' . $row['MANPASSWORD'] . '</td>';
                echo '<td>' . $row['SHELTERLOCATION'] . '</td>';
                echo '<td>' . $row['SHELTERNAME'] . '</td>';
                echo '</tr>';
            }
        ?>

        </tbody>
    </table>

    <?php
        oci_free_statement($result);
        disconnectFromDB();
    ?>

</body>
</html>