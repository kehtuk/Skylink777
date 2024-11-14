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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Изменить место</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    h1, h2 {
        color: #333;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header a {
        background-color: #51B0BA;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 4px;
    }

    .header a:hover {
        background-color: #0056b3;
    }

    .card-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
        flex: 1;
        margin: 0 10px;
        text-align: center;
    }

    .card h3 {
        margin: 0 0 10px;
        font-size: 20px;
        color: #51B0BA;
    }

    .card a {
        display: inline-block;
        padding: 10px 15px;
        background-color: #51B0BA;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        margin-top: 10px;
    }

    .card a:hover {
        background-color: #0056b3;
    }

    form {
        margin-bottom: 20px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="number"] {
        width: calc(100% - 20px);
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background: #51B0BA;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 15px;
        cursor: pointer;
    }

    button:hover {
        background: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table, th, td {
        border: 1px solid #ccc;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }

    th {
        background: #51B0BA;
        color: white;
    }

    a {
        color: #51B0BA;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    /* Адаптив для устройств с шириной до 768px (планшеты) */
    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            align-items: flex-start;
        }

        .card-container {
            flex-direction: column;
        }

        .card {
            margin-bottom: 20px;
        }

        .card-container {
            justify-content: center;
        }

        table {
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
        }

        button {
            width: 100%;
        }
    }

    /* Адаптив для устройств с шириной до 480px (мобильные устройства) */
    @media (max-width: 480px) {
        body {
            padding: 10px;
        }

        h1 {
            font-size: 24px;
        }

        h2 {
            font-size: 20px;
        }

        .header a {
            padding: 8px 12px;
        }

        .card-container {
            flex-direction: column;
        }

        .card {
            margin-bottom: 15px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
        }

        button {
            width: 100%;
            padding: 12px 20px;
        }

        table {
            font-size: 12px;
        }

        th, td {
            padding: 8px;
        }

    }

</style>
<body>
<div class="header">
    <h1>Редактирование заказов</h1>
    <a href="seats.php">Назад к админ-панели</a>
</div>

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

</body>
</html>

<?php
$conn->close();
?>
