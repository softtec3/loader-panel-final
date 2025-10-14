
<?php
// Database connection configuration
$servername = "localhost";
$username = "softtec3_coo_bd";
$password = "Soft085245tech@";
$dbname = "softtec3_emp_llc";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8 for proper character encoding
$conn->set_charset("utf8");
?>