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

// Получаем данные рейса по ID
if (isset($_GET['id'])) {
    $flight_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM Flights WHERE flight_id = ?");
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $flight = $result->fetch_assoc();
    $stmt->close();
}

// Обработка формы редактирования рейса
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_flight'])) {
    $departure_location = $_POST['departure_location'];
    $arrival_location = $_POST['arrival_location'];
    $duration = $_POST['duration'];
    $price = $_POST['price'];
    $date = $_POST['date'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $total_seats = $_POST['total_seats'];
    $available_seats = $_POST['available_seats'];

    // Обновляем данные рейса в базе данных
    $stmt = $conn->prepare("UPDATE Flights SET departure_location = ?, arrival_location = ?, duration = ?, price = ?, date = ?, departure_time = ?, arrival_time = ?, total_seats = ?, available_seats = ? WHERE flight_id = ?");
    // Строка типов: ss...d...s...i (для строк, дробного числа и целых чисел)
    $stmt->bind_param("ssddssssii",
        $departure_location,
        $arrival_location,
        $duration,
        $price,
        $date,
        $departure_time,
        $arrival_time,
        $total_seats,
        $available_seats,
        $flight_id // добавляем этот параметр
    );

    if ($stmt->execute()) {
        echo "Рейс успешно обновлен.";
        header("Location: flights.php");  // Перенаправляем на страницу со списком рейсов
        exit;
    } else {
        echo "Ошибка: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать рейс</title>
</head>
<body>
<h1>Редактировать рейс</h1>
<form method="POST">
    <label for="departure_location">Место отправления:</label>
    <input type="text" name="departure_location" value="<?php echo htmlspecialchars($flight['departure_location']); ?>" required><br>

    <label for="arrival_location">Место назначения:</label>
    <input type="text" name="arrival_location" value="<?php echo htmlspecialchars($flight['arrival_location']); ?>" required><br>

    <label for="duration">Продолжительность (чч:мм):</label>
    <input type="text" name="duration" value="<?php echo htmlspecialchars($flight['duration']); ?>" required><br>

    <label for="price">Цена:</label>
    <input type="number" name="price" value="<?php echo htmlspecialchars($flight['price']); ?>" step="0.01" required><br>

    <label for="date">Дата:</label>
    <input type="date" name="date" value="<?php echo htmlspecialchars($flight['date']); ?>" required><br>

    <label for="departure_time">Время отправления:</label>
    <input type="time" name="departure_time" value="<?php echo htmlspecialchars($flight['departure_time']); ?>" required><br>

    <label for="arrival_time">Время прибытия:</label>
    <input type="time" name="arrival_time" value="<?php echo htmlspecialchars($flight['arrival_time']); ?>" required><br>

    <label for="total_seats">Всего мест:</label>
    <input type="number" name="total_seats" value="<?php echo htmlspecialchars($flight['total_seats']); ?>" required><br>

    <label for="available_seats">Доступные места:</label>
    <input type="number" name="available_seats" value="<?php echo htmlspecialchars($flight['available_seats']); ?>" required><br>

    <button type="submit" name="edit_flight">Сохранить изменения</button>
</form>

<a href="flights.php">Назад к списку рейсов</a>
</body>
</html>

<?php
$conn->close();
?>
