<?php
// db.php - Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Skylink777";

// Подключение
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}
?>
