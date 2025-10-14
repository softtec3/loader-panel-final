<?php
require_once("./php/db_connect.php");
$all_invoices = [];
// Get all invoices details
$sql = $conn->query("SELECT * FROM invoices");

if ($sql) {
    // Fetch all rows as an associative array
    $all_invoices = $sql->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Query failed: " . $conn->error;
}
// unpaid invoices
$unpaid_filter = array_filter($all_invoices, function ($invoice) {
    if ($invoice["status"] != "unpaid") {
        return false;
    }
    return true;
});

$unpaid_invoices = array_values($unpaid_filter);

// Update after payment

if (isset($_GET["payId"]) && $_GET["payId"]) {
    $invoice_id = $_GET["payId"];
    $status = "pending";
    $stmt = $conn->prepare("UPDATE invoices SET status=? WHERE id=?");
    if (!$stmt) {
        die("Preparing problem" . $conn->error);
    }
    $stmt->bind_param("si", $status, $invoice_id);

    if (!$stmt->execute()) {
        die("Execution problem" . $stmt->error);
    }
}

// pending invoices
$pending_filter = array_filter($all_invoices, function ($invoice) {
    if ($invoice["status"] != "pending") {
        return false;
    }
    return true;
});

$pending_invoices = array_values($pending_filter);

// paid invoices
$paid_filter = array_filter($all_invoices, function ($invoice) {
    if ($invoice["status"] != "paid") {
        return false;
    }
    return true;
});

$paid_invoices = array_values($paid_filter);
// rejected invoices
$rejected_filter = array_filter($all_invoices, function ($invoice) {
    if ($invoice["status"] != "rejected") {
        return false;
    }
    return true;
});

$rejected_invoices = array_values($rejected_filter);
