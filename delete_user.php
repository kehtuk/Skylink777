<?php
session_start();
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

    // Начинаем транзакцию для предотвращения неполного удаления
    $conn->begin_transaction();

    try {
        // Удаляем заказы пользователя
        $stmtOrders = $conn->prepare("DELETE FROM Orders WHERE passenger_id = ?");
        $stmtOrders->bind_param("i", $id);
        $stmtOrders->execute();
        $stmtOrders->close();

        // Удаляем пользователя из таблицы Passengers
        $stmtPassenger = $conn->prepare("DELETE FROM Passengers WHERE passenger_id = ?");
        $stmtPassenger->bind_param("i", $id);
        $stmtPassenger->execute();
        $stmtPassenger->close();

        // Завершаем транзакцию
        $conn->commit();
    } catch (Exception $e) {
        // В случае ошибки откатываем изменения
        $conn->rollback();
        die("Ошибка удаления: " . $e->getMessage());
    }
}

$conn->close();
header("Location: admin.php");

?>

