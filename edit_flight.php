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
    <h1>Редактирование рейсов</h1>
    <a href="seats.php">Назад к админ-панели</a>
</div>
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
</body>
</html>

<?php
$conn->close();
?>
