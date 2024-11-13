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

// Обработка добавления нового рейса
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_flight'])) {
    $departure_location = $_POST['departure_location'];
    $arrival_location = $_POST['arrival_location'];
    $duration = $_POST['duration'];
    $price = $_POST['price'];
    $date = $_POST['date'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $total_seats = $_POST['total_seats'];
    $available_seats = $_POST['available_seats'];

    // Вставляем рейс в базу данных
    $stmt = $conn->prepare("INSERT INTO Flights (departure_location, arrival_location, duration, price, date, departure_time, arrival_time, total_seats, available_seats) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdssssi", $departure_location, $arrival_location, $duration, $price, $date, $departure_time, $arrival_time, $total_seats, $available_seats);

    if ($stmt->execute()) {
        echo "Рейс успешно добавлен.";
    } else {
        echo "Ошибка: " . $stmt->error;
    }
    $stmt->close();
}

// Получаем список рейсов
$result_flights = $conn->query("SELECT * FROM Flights");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyLink</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
/* Общие стили */
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
input[type="password"] {
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

/* Адаптивные стили */
@media (max-width: 768px) {
    /* Адаптация хедера */
    .header {
        flex-direction: column;
        align-items: flex-start;
    }

    /* Адаптация карточек */
    .card-container {
        flex-direction: column;
        align-items: stretch;
    }

    .card {
        margin-bottom: 20px;
        flex: unset;
    }

    /* Адаптация форм */
    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 8px;
        font-size: 14px;
    }

    button {
        width: 100%;
        padding: 12px;
    }

    /* Скрытие некоторых столбцов на мобильных устройствах */
    table {
        font-size: 14px;
        display: block;
        overflow-x: auto;
    }

    th, td {
        padding: 8px;
    }

    th:nth-child(n+6), td:nth-child(n+6) {
        display: none; /* Скрытие столбцов с 6-го и далее */
    }

    /* Стили для ссылок в таблице */
    a {
        display: inline-block;
        margin-top: 5px;
    }
}

@media (max-width: 480px) {
    /* Уменьшение размера шрифта для мобильных устройств */
    h1, h2 {
        font-size: 16px;
    }

    .card h3 {
        font-size: 18px;
    }

    .header a {
        padding: 8px 12px;
    }

    table {
        font-size: 12px;
    }

    th, td {
        padding: 6px;
    }

    /* Скрытие большинства столбцов на мобильных устройствах */
    th:nth-child(n+4), td:nth-child(n+4) {
        display: none; /* Скрытие столбцов с 4-го и далее */
    }
}

    </style>
</head>
<body>
<div class="header">
    <h1>Админ Панель SkyLink</h1>
    <a href="index.php">На главную</a>
</div>

<div class="card-container">
    <div class="card">
        <h3><i class="fas fa-plane"></i> Заказы</h3>
        <a href="orders.php">Перейти к заказам</a>
    </div>
    <div class="card">
        <h3><i class="fas fa-user-cog"></i> Администратор</h3>
        <a href="admin.php">Перейти к админским настройкам</a>
    </div>
    <div class="card">
        <h3><i class="fas fa-plane-departure"></i> Рейсы</h3>
        <a href="flights.php">Перейти к рейсам</a>
    </div>
</div>

<div class="card-container">
    <div class="card">
        <h3><i class="fas fa-plane"></i> Добавить рейс</h3>
        <form method="POST">
            <input type="text" name="departure_location" placeholder="Место отправления" required>
            <input type="text" name="arrival_location" placeholder="Место назначения" required>
            <input type="text" name="duration" placeholder="Продолжительность (чч:мм)" required>
            <input type="number" name="price" placeholder="Цена" step="0.01" required>
            <input type="date" name="date" required>
            <input type="time" name="departure_time" required>
            <input type="time" name="arrival_time" required>
            <input type="number" name="total_seats" placeholder="Всего мест" required>
            <input type="number" name="available_seats" placeholder="Доступные места" required>
            <button type="submit" name="add_flight">Добавить</button>
        </form>
    </div>
    <div class="card">
        <h3><i class="fas fa-plane-departure"></i> Список рейсов</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Место отправления</th>
                <th>Место назначения</th>
                <th>Продолжительность</th>
                <th>Цена</th>
                <th>Дата</th>
                <th>Время отправления</th>
                <th>Время прибытия</th>
                <th>Всего мест</th>
                <th>Доступные места</th>
                <th>Действия</th>
            </tr>
            <?php if ($result_flights->num_rows > 0): ?>
                <?php while ($row = $result_flights->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['flight_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['departure_location']); ?></td>
                        <td><?php echo htmlspecialchars($row['arrival_location']); ?></td>
                        <td><?php echo htmlspecialchars($row['duration']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['departure_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['arrival_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_seats']); ?></td>
                        <td><?php echo htmlspecialchars($row['available_seats']); ?></td>
                        <td>
                            <a href="edit_flight.php?id=<?php echo $row['flight_id']; ?>">Изменить</a>
                            <a href="delete_flight.php?id=<?php echo $row['flight_id']; ?>">Удалить</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11">Нет рейсов для отображения.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
