<?php
session_start();
$sql = "CREATE TABLE IF NOT EXISTS loader_log(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(100) NOT NULL,
    password VARCHAR(50) NOT NULL 
)";

if ($conn->query($sql) === TRUE) {
} else {
    echo "Error creating table: " . $conn->error;
}

if (isset($_POST["user_id"])) {
    $user_id = $_POST["user_id"];
    $user_password = $_POST["user_password"];

    // Select all user details
    $stmt = $conn->prepare("SELECT * FROM loader_log WHERE user_id=?");
    if (!$stmt) {
        die("Preparing failed: " . $conn->error);
    }

    $stmt->bind_param("s", $user_id);
    if (!$stmt->execute()) {
        die("SQL operation failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user_password != $user["password"]) {
            echo "<script>
                alert('Password not matched');
            </script>";
        } else {
            $_SESSION["user_id"] = $user["user_id"];
            header("Location: ./index.php");
        }
    } else {
        echo "<script>
                alert('User id not found');
            </script>";
    }
}
