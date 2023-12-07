<?php
    $db_conn = NULL;

    // Function from: https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/php-setup.html
    function connectToDB() {
        global $db_conn;

        $db_conn = OCILogon("USER_NAME", "PASSWORD", "dbhost.students.cs.ubc.ca:1522/stu");

        if ($db_conn) {
            return true;
        } else {
            $e = OCI_Error(); // For OCILogon errors pass no handle
            echo htmlentities($e['message']);
            echo "failed connection to db";
            return false;
        }
    }

    function disconnectFromDB() {
        global $db_conn;
        OCILogoff($db_conn);
    }    
?>