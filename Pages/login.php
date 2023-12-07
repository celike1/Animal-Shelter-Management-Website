<?php 

	include("connection.php");
	include_once('../routeHandler.php');
	session_start();

?>


<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
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


	<div id="box">
        <div style="font-size: 20px;margin: 10px;color: white;">Log in below</div>
            <p>Remember ID's are in the format 'MXXX' where X are numbers.</p>
            <form method="POST" action="login.php">
            
                <input type="hidden" id="insertLoginRequest" name="insertLoginRequest">
                Manager ID: <input id = "text" type="text" name="manID" pattern="M\d{3}" title="Invalid entry. Please follow the format above." required> <br /><br />
                Password: <input id = "text" type="text" name="manPassword" maxlength="12" required> <br /><br />
            <input id = "button" type="submit" value="Login" name="loginSubmit"></p>

            <a href="signup.php">Click to Signup</a><br><br>
        </form>

    </div>






</body>
</html>