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

// Получаем данные заказа по ID
$order = null;
if (isset($_GET['id'])) {
    $order_id = (int)$_GET['id']; // Преобразуем в число для безопасности

    // Подготовленный запрос для получения данных заказа
    $stmt = $conn->prepare("SELECT * FROM Orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Проверяем, найден ли заказ
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        echo "Заказ не найден.";
        exit;
    }
    $stmt->close();
} else {
    echo "ID заказа не указан.";
    exit;
}

// Обработка формы редактирования заказа
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_order'])) {
    $flight_id = (int)$_POST['flight_id'];
    $passenger_id = (int)$_POST['passenger_id'];
    $amount = (int)$_POST['amount'];
    $price = (float)$_POST['price']; // Считываем цену как число с плавающей точкой
    $trip_class = $_POST['trip_class']; // Строка для класса
    $status = $_POST['status']; // Строка для статуса

    // Проверка на корректность данных
    if ($flight_id <= 0 || $passenger_id <= 0 || $amount <= 0 || $price <= 0) {
        echo "Все поля должны быть заполнены корректно.";
    } else {
        // Обновляем данные заказа в базе данных
        $stmt = $conn->prepare("UPDATE Orders SET flight_id = ?, passenger_id = ?, amount = ?, price = ?, trip_class = ?, status = ? WHERE order_id = ?");
        $stmt->bind_param("iiidsss", $flight_id, $passenger_id, $amount, $price, $trip_class, $status, $order_id);

        if ($stmt->execute()) {
            echo "Заказ успешно обновлен.";
            header("Location: orders.php");  // Перенаправляем на страницу со списком заказов
            exit;
        } else {
            echo "Ошибка: " . $stmt->error;
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать заказ</title>
</head>
<body>
<h1>Редактировать заказ</h1>

<?php if ($order): ?>
    <form method="POST">
        <label for="flight_id">ID рейса:</label>
        <input type="number" name="flight_id" value="<?php echo htmlspecialchars($order['flight_id']); ?>" required><br>

        <label for="passenger_id">ID пассажира:</label>
        <input type="number" name="passenger_id" value="<?php echo htmlspecialchars($order['passenger_id']); ?>" required><br>

        <label for="amount">Количество мест:</label>
        <input type="number" name="amount" value="<?php echo htmlspecialchars($order['amount']); ?>" required><br>

        <label for="price">Стоимость заказа:</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($order['price']); ?>" required><br>

        <label for="trip_class">Класс:</label>
        <select name="trip_class" required>
            <option value="economy" <?php echo ($order['trip_class'] == 'economy') ? 'selected' : ''; ?>>Эконом</option>
            <option value="business" <?php echo ($order['trip_class'] == 'business') ? 'selected' : ''; ?>>Бизнес</option>
            <option value="first" <?php echo ($order['trip_class'] == 'first') ? 'selected' : ''; ?>>Первый</option>
        </select><br>

        <label for="status">Статус:</label>
        <select name="status" required>
            <option value="pending" <?php echo ($order['status'] == 'pending') ? 'selected' : ''; ?>>Ожидается</option>
            <option value="completed" <?php echo ($order['status'] == 'completed') ? 'selected' : ''; ?>>Выполнен</option>
            <option value="canceled" <?php echo ($order['status'] == 'canceled') ? 'selected' : ''; ?>>Отменен</option>
        </select><br>

        <button type="submit" name="edit_order">Сохранить изменения</button>
    </form>
<?php endif; ?>

<a href="orders.php">Назад к списку заказов</a>

</body>
</html>

<?php
$conn->close();
?>
