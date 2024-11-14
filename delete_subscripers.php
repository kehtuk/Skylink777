<?php
session_start();

// Проверка на роль администратора
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Skylink777";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM Newsletter_Subscriptions WHERE subscription_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: subscripers.php");
        exit;
    } else {
        echo "Ошибка при удалении: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
