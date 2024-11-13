<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['passenger_id'])) {
    header("Location: index.php");
    exit;
}

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Skylink777";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получаем данные пользователя
$login = $_SESSION['login'];

// SQL-запрос для получения данных о пользователе
$sql = "SELECT email, phone, name, surname, age, gender, country, avatar_path FROM Passengers WHERE login = ?";
$stmt = $conn->prepare($sql);

// Проверка на успешность подготовки запроса
if (!$stmt) {
    die("Ошибка подготовки запроса: " . $conn->error); // Вывод ошибки
}

$stmt->bind_param("s", $login);
$stmt->execute();
$stmt->bind_result($email, $phone, $name, $surname, $age, $gender, $country, $avatarPath);
$stmt->fetch();
$stmt->close();

// Устанавливаем значения по умолчанию, если они пустые
$email = $email ?: "Данные не указаны";
$phone = $phone ?: "Данные не указаны";
$name = $name ?: "Данные не указаны";
$surname = $surname ?: "Данные не указаны";
$age = $age ?: "Данные не указаны";
$gender = $gender ?: "Данные не указаны";
$country = $country ?: "Данные не указаны";

// Получаем заказы пользователя, разделяя их по статусам
$orderSql = "SELECT order_id, flight_id, amount, order_date, status FROM Orders WHERE passenger_id = (SELECT passenger_id FROM Passengers WHERE login = ?)";
$orderStmt = $conn->prepare($orderSql);

if (!$orderStmt) {
    die("Ошибка подготовки запроса: " . $conn->error); // Вывод ошибки
}

$orderStmt->bind_param("s", $login);
$orderStmt->execute();
$orderStmt->bind_result($orderId, $flightId, $amount, $orderDate, $status);

// Создаем массивы для хранения заказов по статусам
$orders = [];

while ($orderStmt->fetch()) {
    $orders[] = [
        'order_id' => $orderId,
        'flight_id' => $flightId,
        'amount' => $amount,
        'order_date' => $orderDate,
        'status' => $status
    ];
}

$orderStmt->close();

// Обработка отмены заказа
if (isset($_POST['cancel_order'])) {
    $orderIdToCancel = $_POST['order_id'];
    $cancelSql = "UPDATE Orders SET status = 'canceled' WHERE order_id = ?";
    $cancelStmt = $conn->prepare($cancelSql);
    $cancelStmt->bind_param("i", $orderIdToCancel);

    if ($cancelStmt->execute()) {
        echo "<p>Заказ успешно отменен.</p>";
    } else {
        echo "<p>Ошибка при отмене заказа: " . $cancelStmt->error . "</p>";
    }

    $cancelStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru,en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyLink - Профиль</title>
    <link rel="stylesheet" type="text/css" href="scss/style.css">
    <link rel="stylesheet" type="text/css" href="scss/media.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&family=Open+Sans:wght@300..800&display=swap" rel="stylesheet">
    <style>
        /* Стили для контейнера заказов */
        .orders-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Стиль карточек заказов */
        .order-card {
            background: linear-gradient(to bottom right, #00aaff, #0077cc);
            color: white;
            border-radius: 10px;
            padding: 20px;
            width: calc(33% - 40px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .order-card:hover {
            transform: translateY(-5px);
        }

        .order-card p {
            margin: 5px 0;
            font-size: 16px;
        }

        .order-card .order-title {
            font-size: 20px;
            font-weight: 600;
        }

        .order-card .order-amount, .order-card .order-date {
            font-size: 14px;
            font-weight: 500;
        }

        .cancel-order-button {
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 10px;
        }

        .cancel-order-button:hover {
            background-color: #d32f2f;
        }

        footer {
            margin-top: 40px;
        }

        .status-section {
            margin-top: 30px;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 10px;
        }

        .status-section h3 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<header>
    <nav class="menu">
        <ul class="menu-top">
            <li class="menu-item"><a href="index.php" class="menu-link"><img src="img/Логотип.svg" alt="Logo"></a></li>
            <li class="menu-item"><a href="flights.html" class="menu-link">Рейсы</a></li>
            <li class="menu-item"><a href="promotions.html" class="menu-link">Акции</a></li>
            <li class="menu-item"><a href="information.html" class="menu-link">Информация</a></li>
            <li class="menu-item"><a href="contacts.html" class="menu-link">Контакты</a></li>
            <li class="menu-item"><a href="logout.php" class="menu-link reg">Выйти</a></li>
        </ul>
    </nav>
</header>

<section class="account">
    <h2 class="account-topic">Профиль</h2>
    <div class="profile-layout">
        <div class="left-column">
            <div class="user-avatar">
                <?php if (!empty($avatarPath) && file_exists($avatarPath)): ?>
                    <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="Аватар" style="width:100px; height:100px;">
                <?php else: ?>
                    <p>Аватар не загружен.</p>
                <?php endif; ?>
            </div>

            <div class="avatar-upload">
                <form action="upload_avatar.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="avatar" accept="image/*">
                    <button type="submit">Загрузить аватар</button>
                </form>
                <form action="delete_avatar.php" method="POST">
                    <button type="submit">Удалить аватар</button>
                </form>
            </div>
        </div>

        <div class="right-column">
            <div class="user-info">
                <p><strong>Логин:</strong> <?php echo htmlspecialchars($login); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Телефон:</strong> <?php echo htmlspecialchars($phone); ?></p>
                <h3>Личная информация:</h3>
                <p><strong>Имя:</strong> <?php echo htmlspecialchars($name); ?></p>
                <p><strong>Фамилия:</strong> <?php echo htmlspecialchars($surname); ?></p>
                <p><strong>Возраст:</strong> <?php echo htmlspecialchars($age); ?></p>
                <p><strong>Пол:</strong> <?php echo htmlspecialchars($gender); ?></p>
                <p><strong>Страна:</strong> <?php echo htmlspecialchars($country); ?></p>
            </div>
        </div>
    </div>

    <h2 class="account-topic">Ваши билеты</h2>

    <!-- Раздел для заказов с разделением по статусам -->
    <div class="status-section">
        <h3>Ожидает</h3>
        <div class="orders-container">
            <?php foreach ($orders as $order): ?>
                <?php if ($order['status'] == 'pending'): ?>
                    <div class="order-card">
                        <p class="order-title">ID Заказа: <?php echo htmlspecialchars($order['order_id']); ?></p>
                        <p class="order-flight">ID Рейса: <?php echo htmlspecialchars($order['flight_id']); ?></p>
                        <p class="order-amount">Количество: <?php echo htmlspecialchars($order['amount']); ?></p>
                        <p class="order-date">Дата Заказа: <?php echo htmlspecialchars($order['order_date']); ?></p>
                        <p class="order-status">Статус: <?php echo htmlspecialchars($order['status']); ?></p>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                            <button type="submit" name="cancel_order" class="cancel-order-button">Отменить</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Раздел для подтвержденных заказов -->
    <div class="status-section">
        <h3>Подтверждено</h3>
        <div class="orders-container">
            <?php foreach ($orders as $order): ?>
                <?php if ($order['status'] == 'completed'): ?>
                    <div class="order-card">
                        <p class="order-title">ID Заказа: <?php echo htmlspecialchars($order['order_id']); ?></p>
                        <p class="order-flight">ID Рейса: <?php echo htmlspecialchars($order['flight_id']); ?></p>
                        <p class="order-amount">Количество: <?php echo htmlspecialchars($order['amount']); ?></p>
                        <p class="order-date">Дата Заказа: <?php echo htmlspecialchars($order['order_date']); ?></p>
                        <p class="order-status">Статус: <?php echo htmlspecialchars($order['status']); ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Раздел для отмененных заказов -->
    <div class="status-section">
        <h3>Отменено</h3>
        <div class="orders-container">
            <?php foreach ($orders as $order): ?>
                <?php if ($order['status'] == 'canceled'): ?>
                    <div class="order-card">
                        <p class="order-title">ID Заказа: <?php echo htmlspecialchars($order['order_id']); ?></p>
                        <p class="order-flight">ID Рейса: <?php echo htmlspecialchars($order['flight_id']); ?></p>
                        <p class="order-amount">Количество: <?php echo htmlspecialchars($order['amount']); ?></p>
                        <p class="order-date">Дата Заказа: <?php echo htmlspecialchars($order['order_date']); ?></p>
                        <p class="order-status">Статус: <?php echo htmlspecialchars($order['status']); ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

</section>

<!--<footer>-->
<!--    <div class="container">-->
<!--        <div class="footer-content">-->
<!--            <div class="footer-content-element">-->
<!--                <a href="index.php" class="footer-content-element-link"><img src="img/Минилого.svg" alt="Логотип"></a>-->
<!--            </div>-->
<!--            <div class="footer-content-element">-->
<!--                <ul class="footer-content-list">-->
<!--                    <li class="footer-content-list-item"><a href="flights.html" class="footer-content-list-item-link">Рейсы</a></li>-->
<!--                    <li class="footer-content-list-item"><a href="promotions.html" class="footer-content-list-item-link">Акции</a></li>-->
<!--                    <li class="footer-content-list-item"><a href="information.html" class="footer-content-list-item-link">Информация</a></li>-->
<!--                    <li class="footer-content-list-item"><a href="contacts.html" class="footer-content-list-item-link">Контакты</a></li>-->
<!--                    <li class="footer-content-list-item"><a href="profile.html" class="footer-content-list-item-link">Профиль</a></li>-->
<!--                </ul>-->
<!--                <p class="footer-content-copyright">© 2024 SkyLink. All rights reserved.</p>-->
<!--            </div>-->
<!--            <div class="footer-content-element">-->
<!--                <a href="#" class="footer-content-socials"><img src="img/Телеграм.svg" alt="Телеграм"></a>-->
<!--                <a href="#" class="footer-content-socials"><img src="img/ВКонтакте.svg" alt="ВКонтакте"></a>-->
<!--                <a href="#" class="footer-content-socials"><img src="img/Ютуб.svg" alt="Ютуб"></a>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</footer>-->

<script src="js/script.js"></script>
</body>
</html>

