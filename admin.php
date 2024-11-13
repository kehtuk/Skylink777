<?php
session_start();

// Отладочный вывод
var_dump($_SESSION);

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
} else {
    echo "Соединение успешно установлено.";
}

// Обработка добавления нового пользователя
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $login = $_POST['login'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $country = $_POST['country'];
    $avatar_path = $_POST['avatar_path']; // Путь к аватару (если есть)

    // Хешируем пароль
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Вставляем пользователя в базу данных
    $stmt = $conn->prepare("INSERT INTO Passengers (name, surname, age, gender, email, phone, login, password_hash, role, country, avatar_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'user', ?, ?)");
    $stmt->bind_param("ssssssssss", $name, $surname, $age, $gender, $email, $phone, $login, $password_hash, $country, $avatar_path);

    if ($stmt->execute()) {
        echo "Пользователь успешно добавлен.";
    } else {
        echo "Ошибка: " . $stmt->error;
    }
    $stmt->close();
}

// Получаем список пользователей
$result = $conn->query("SELECT * FROM Passengers");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyLink</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
    <div class="card">
        <h3><i class="fas fa-plane-departure"></i> Места</h3>
        <a href="Seats.php">Перейти к местам</a>
    </div>
    <div class="card">
        <h3><i class="fas fa-plane-departure"></i> Места пассажиров</h3>
        <a href="OrderSeats.php">Перейти к местам пассажиров</a>
    </div>
    <div class="card">
        <h3><i class="fas fa-plane-departure"></i> Подписки</h3>
        <a href="Subscripers.php">Перейти к подпискам</a>
    </div>
    <div class="card">
        <h3><i class="fas fa-plane-departure"></i> Запросы</h3>
        <a href="zapros.php">Перейти к запросам</a>
    </div>
</div>

<div class="card-container">
    <div class="card">
        <h3><i class="fas fa-user-plus"></i> Добавить пользователя</h3>
        <form method="POST">
            <input type="text" name="name" placeholder="Имя" required>
            <input type="text" name="surname" placeholder="Фамилия" required>
            <input type="number" name="age" placeholder="Возраст" required>
            <input type="text" name="gender" placeholder="Пол (м/ж)" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Телефон">
            <input type="text" name="country" placeholder="Страна" required>
            <input type="text" name="login" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="text" name="avatar_path" placeholder="Путь к аватару (опционально)">
            <button type="submit" name="add_user">Добавить</button>
        </form>
    </div>
    <div class="card">
        <h3><i class="fas fa-users"></i> Список пользователей</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Возраст</th>
                <th>Пол</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Страна</th>
                <th>Логин</th>
                <th>Действия</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['passenger_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['surname']); ?></td>
                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['country']); ?></td>
                        <td><?php echo htmlspecialchars($row['login']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['passenger_id']; ?>">Изменить</a>
                            <a href="delete_user.php?id=<?php echo $row['passenger_id']; ?>">Удалить</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">Нет пользователей для отображения.</td>
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
