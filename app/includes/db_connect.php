<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "student_portal_db";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($hostname, $username, $password, $database);
    $conn->set_charset("utf8mb4");

    if ($conn->connect_error) {
        die("Database Connection Failed: " . $conn->connect_error);
    }
} catch (mysqli_sql_exception $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
