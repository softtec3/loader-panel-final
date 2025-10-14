<?php
require_once("./db_connect.php");

$sql = "CREATE TABLE IF NOT EXISTS invoices(
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_email VARCHAR(100) DEFAULT NULL,
    customer_name VARCHAR(100) DEFAULT NULL,
    desired_product VARCHAR(255) DEFAULT NULL,
    invoice_amount DECIMAL(10, 2) DEFAULT 0,
    invoice_number VARCHAR(50) DEFAULT NULL,
    invoice_link TEXT DEFAULT NULL,
    invoice_purpose VARCHAR(255) DEFAULT NULL,
    cost DECIMAL(10, 2) DEFAULT 0,
    payable_amount DECIMAL(10, 2) DEFAULT 0,
    due_date VARCHAR(255) DEFAULT NULL,
    remark VARCHAR(255) DEFAULT NULL,
    status ENUM('requested','unpaid','pending','paid','rejected') DEFAULT 'requested'
)";

if ($conn->query($sql) === TRUE) {
} else {
    echo "Error creating table: " . $conn->error;
}
if (isset($_POST["customer_email"]) && $_POST["customer_email"] != "") {
    $customer_email = $_POST["customer_email"];
    $customer_name = $_POST["customer_name"];
    $desired_product = $_POST["desired_product"];
    $invoice_amount = $_POST["invoice_amount"];

    $stmt = $conn->prepare("INSERT INTO invoices (customer_email, customer_name, desired_product, invoice_amount ) VALUES(?,?,?,?)");
    if (!$stmt) {
        die("Problem preparing" . $conn->error);
    }
    $stmt->bind_param("sssd", $customer_email, $customer_name, $desired_product, $invoice_amount);

    if (!$stmt->execute()) {
        echo "Problem with insert" . $stmt->error;
    }
    header("Location: ../index.php");
}
