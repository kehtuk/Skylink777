<?php
session_start();

// Проверяем, является ли пользователь администратором
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Подключаемся к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Skylink777";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Удаляем заказ и связанные места
if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);

    // Начинаем транзакцию для целостности данных
    $conn->begin_transaction();

    try {
        // Удаляем связанные места из таблицы OrderSeats
        $stmtSeats = $conn->prepare("DELETE FROM OrderSeats WHERE order_id = ?");
        $stmtSeats->bind_param("i", $order_id);
        $stmtSeats->execute();
        $stmtSeats->close();

        // Удаляем сам заказ из таблицы Orders
        $stmtOrder = $conn->prepare("DELETE FROM Orders WHERE order_id = ?");
        $stmtOrder->bind_param("i", $order_id);
        $stmtOrder->execute();
        $stmtOrder->close();

        // Подтверждаем транзакцию
        $conn->commit();
    } catch (Exception $e) {
        // В случае ошибки откатываем изменения
        $conn->rollback();
        die("Ошибка при удалении заказа: " . $e->getMessage());
    }
}

$conn->close();
header("Location: orders.php"); // Перенаправляем на страницу с заказами
exit;
?>
