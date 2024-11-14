<?php
session_start();

// Проверка на роль администратора
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

// Обработка добавления новой подписки
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subscription'])) {
    $passenger_id = $_POST['passenger_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO Newsletter_Subscriptions (passenger_id, status) VALUES (?, ?)");
    $stmt->bind_param("is", $passenger_id, $status);

    if ($stmt->execute()) {
        echo "Подписка успешно добавлена.";
    } else {
        echo "Ошибка: " . $stmt->error;
    }
    $stmt->close();
}

// Получаем список подписок
$result = $conn->query("SELECT subscription_id, passenger_id, status, subscription_date FROM Newsletter_Subscriptions");
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
        <h3><i class="fas fa-chair"></i> Места</h3>
        <a href="seats.php">Перейти к местам</a>
    </div>
    <div class="card">
        <h3><i class="fas fa-ticket-alt"></i> Места пассажиров</h3>
        <a href="ordersseat.php">Перейти к местам пассажиров</a>
    </div>
    <div class="card">
        <h3><i class="fas fa-envelope"></i> Подписки</h3>
        <a href="subscripers.php">Перейти к подпискам</a>
    </div>
    <div class="card">
        <h3><i class="fas fa-question-circle"></i> Запросы</h3>
        <a href="zapros.php">Перейти к запросам</a>
    </div>

</div>
<h1>Управление подписками на рассылку</h1>

<form method="POST">
    <h3>Добавить подписку</h3>
    <label for="passenger_id">Passenger ID:</label>
    <input type="number" name="passenger_id" required><br>
    <label for="status">Status:</label>
    <select name="status" required>
        <option value="subscribed">Subscribed</option>
        <option value="unsubscribed">Unsubscribed</option>
    </select><br>
    <button type="submit" name="add_subscription">Добавить подписку</button>
</form>

<h3>Список подписок</h3>
<table>
    <tr>
        <th>Subscription ID</th>
        <th>Passenger ID</th>
        <th>Status</th>
        <th>Subscription Date</th>
        <th>Действия</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['subscription_id']; ?></td>
                <td><?php echo htmlspecialchars($row['passenger_id']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo htmlspecialchars($row['subscription_date']); ?></td>
                <td>
                    <a href="edit_subscripers.php?id=<?php echo $row['subscription_id']; ?>">Изменить</a> |
                    <a href="delete_subscripers.php?id=<?php echo $row['subscription_id']; ?>">Удалить</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">Нет данных для отображения.</td>
        </tr>
    <?php endif; ?>
</table>
</body>
</html>

<?php
$conn->close();
?>
