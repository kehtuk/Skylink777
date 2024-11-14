<?php
session_start(); // Запуск сессии

// Проверка, что пользователь авторизован
if (!isset($_SESSION['passenger_id'])) {
    die("Пожалуйста, войдите в систему, чтобы сделать заказ.");
}

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SkyLink777";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение данных из формы
$departure = isset($_POST['departure']) ? $_POST['departure'] : '';
$destination = isset($_POST['destination']) ? $_POST['destination'] : '';
$departure_date = isset($_POST['departure_date']) ? $_POST['departure_date'] : '';
$return_date = isset($_POST['return_date']) ? $_POST['return_date'] : '';
$amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 0;

// Формирование SQL-запроса с фильтрацией
$sql = "SELECT * FROM Flights WHERE 1=1";

if (!empty($departure)) {
    $sql .= " AND departure_location LIKE '%" . $conn->real_escape_string($departure) . "%'";
}
if (!empty($destination)) {
    $sql .= " AND arrival_location LIKE '%" . $conn->real_escape_string($destination) . "%'";
}
if (!empty($departure_date)) {
    $sql .= " AND date = '" . $conn->real_escape_string($departure_date) . "'";
}
if (!empty($return_date)) {
    $sql .= " AND date <= '" . $conn->real_escape_string($return_date) . "'";
}
if ($amount > 0) {
    $sql .= " AND available_seats >= " . $amount;
}

// Выполнение запроса
$result = $conn->query($sql);

// Обработка добавления в заказ
if (isset($_POST['order_flight'])) {
    $flight_id = (int)$_POST['flight_id'];
    $passenger_id = isset($_SESSION['passenger_id']) ? (int)$_SESSION['passenger_id'] : 0;
    $order_amount = (int)$_POST['order_amount'];
    $selected_seats = isset($_POST['selected_seats']) ? $_POST['selected_seats'] : '';
    $trip_class = isset($_POST['trip_class']) ? $_POST['trip_class'] : 'economy';

    if (!in_array($trip_class, ['economy', 'business', 'first'])) {
        die("Некорректный выбор класса.");
    }

    if ($passenger_id <= 0) {
        die("Ошибка: идентификатор пассажира не задан.");
    }

    if ($order_amount <= 0) {
        die("Количество билетов должно быть больше 0.");
    }

    // Проверка выбранных мест
    if (empty($selected_seats)) {
        die("Не выбраны места.");
    }

    // Получаем цену билета для выбранного класса
    $flight_sql = "SELECT price FROM Flights WHERE flight_id = ?";
    $stmt = $conn->prepare($flight_sql);
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();

    // Для класса бизнес и первый класс устанавливаем другие коэффициенты для цены
    if ($trip_class == 'business') {
        $price *= 1.5; // Например, бизнес-класс стоит в 1.5 раза дороже
    } elseif ($trip_class == 'first') {
        $price *= 2; // Первый класс стоит в 2 раза дороже
    }

    // Рассчитываем общую стоимость
    $total_price = $price * $order_amount;

    // Добавление заказа в таблицу Orders
    $order_sql = "INSERT INTO Orders (flight_id, passenger_id, amount, price, trip_class, status, order_date) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($order_sql);
    $status = 'pending'; // Заказ ожидает обработки
    $stmt->bind_param("iiidss", $flight_id, $passenger_id, $order_amount, $total_price, $trip_class, $status);

    if ($stmt->execute()) {
        echo "<p>Билет успешно добавлен в заказы!</p>";
        $order_id = $stmt->insert_id; // Получаем ID добавленного заказа

// Сохранение выбранных мест в таблице OrderSeats
$seats = explode(',', $selected_seats);
foreach ($seats as $seat_number) {
    // Получаем seat_id для каждого номера места
    $seat_sql = "SELECT seat_id FROM Seats WHERE flight_id = ? AND seat_number = ?";
    $seat_stmt = $conn->prepare($seat_sql);
    $seat_stmt->bind_param("is", $flight_id, $seat_number);
    $seat_stmt->execute();
    $seat_stmt->bind_result($seat_id);
    $seat_stmt->fetch();
    $seat_stmt->close();

    if ($seat_id) {
        // Проверка, что запись не существует
        $check_sql = "SELECT COUNT(*) FROM OrderSeats WHERE order_id = ? AND seat_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $order_id, $seat_id);
        $check_stmt->execute();
        $check_stmt->bind_result($exists);
        $check_stmt->fetch();
        $check_stmt->close();

        // Добавляем запись только если она еще не существует
        if (!$exists) {
            $order_seat_sql = "INSERT INTO OrderSeats (order_id, seat_id) VALUES (?, ?)";
            $order_seat_stmt = $conn->prepare($order_seat_sql);
            $order_seat_stmt->bind_param("ii", $order_id, $seat_id);
            $order_seat_stmt->execute();
            $order_seat_stmt->close();

            // Обновляем статус места на "занято" в таблице Seats
            $update_seat_sql = "UPDATE Seats SET is_booked = 1 WHERE seat_id = ?";
            $update_stmt = $conn->prepare($update_seat_sql);
            $update_stmt->bind_param("i", $seat_id);
            $update_stmt->execute();
            $update_stmt->close();
        }
    } else {
        echo "<p>Ошибка: Место с номером $seat_number не найдено для данного рейса.</p>";
    }
}

    } else {
        echo "<p>Ошибка при добавлении билета в заказ: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ru,en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyLink - Главная</title>
    <link rel="stylesheet" type="text/css" href="scss/style.css">
    <link rel="stylesheet" type="text/css" href="scss/media.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>

</head>
    <body>
<header>
            <nav class="menu">
                <ul class="menu-top">
                    <li class="menu-item">
                        <a href="index.php" class="menu-link"><img src="img/Логотип.svg" alt="Logo"></a>
                    </li>
                    <li class="menu-item">
                        <a href="flightss.php" class="menu-link">Рейсы</a>
                    </li>
                    <li class="menu-item">
                        <a href="promotions.php" class="menu-link">Акции</a>
                    </li>
                    <li class="menu-item">
                        <a href="information.php" class="menu-link">Информация</a>
                    </li>
                    <li class="menu-item">
                        <a href="contacts.php" class="menu-link">Контакты</a>
                    </li>

                    <?php session_start(); // Убедитесь, что сессия запущена ?>
                    <?php if (isset($_SESSION['passenger_id'])): ?>
                        <li class="menu-item">
                            <a href="profile.php" class="menu-link reg">Профиль</a>
                        </li>
                    <?php else: ?>
                        <li class="menu-item">
                            <a href="#" class="menu-link enter">Вход</a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-link reg">Регистрация</a>
                        </li>
                    <?php endif; ?>


                </ul>
            </nav>
        </header>
        <?php
        // HTML для вывода рейсов
if ($result->num_rows > 0) {
    echo '<div class="flights-container">';
    while($row = $result->fetch_assoc()) {
        echo '<div class="flight-card">';
        echo '<h3>Рейс ID: ' . $row["flight_id"] . '</h3>';
        echo '<p><strong>Откуда:</strong> ' . $row["departure_location"] . '</p>';
        echo '<p><strong>Куда:</strong> ' . $row["arrival_location"] . '</p>';
        echo '<p><strong>Продолжительность:</strong> ' . $row["duration"] . ' мин</p>';
        echo '<p><strong>Цена:</strong> ' . $row["price"] . ' руб.</p>';
        echo '<p><strong>Дата:</strong> ' . $row["date"] . '</p>';
        echo '<p><strong>Время вылета:</strong> ' . $row["departure_time"] . '</p>';
        echo '<p><strong>Время прибытия:</strong> ' . $row["arrival_time"] . '</p>';
        echo '<p><strong>Всего мест:</strong> ' . $row["total_seats"] . '</p>';
        echo '<p><strong>Доступные места:</strong> ' . $row["available_seats"] . '</p>';
        echo '<button type="button" class="select-seat-button" data-flight-id="' . $row["flight_id"] . '" data-available-seats="' . $row["available_seats"] . '">Выбрать билет</button>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>Нет доступных рейсов.</p>';
}
?>
<!-- Модальное окно для выбора мест -->
<div class="modal" id="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Выбор мест для рейса</h2>
        <div class="seats-selection" id="seats-selection"></div>

        <!-- Выбор класса -->
        <label for="trip-class">Выберите класс:</label>
        <select name="trip_class" id="trip-class" required>
            <option value="economy">Эконом</option>
            <option value="business">Бизнес</option>
            <option value="first">Первый класс</option>
        </select>

        <!-- Кнопка для сброса выбранных мест -->
        <button type="button" id="reset-seats" class="reset-seats-button">Сбросить выбор</button>

        <form method="post" action="">
            <input type="hidden" name="flight_id" id="flight_id">
            <input type="hidden" name="selected_seats" id="selected-seats">
            <label for="order-amount">Количество мест:</label>
            <input type="number" name="order_amount" min="1" id="order-amount" class="order-amount-input">
            <button type="submit" name="order_flight" class="order-button">Добавить в заказ</button>
        </form>
    </div>
</div>



<script>
    // Открытие модального окна
    document.querySelectorAll('.select-seat-button').forEach(button => {
        button.addEventListener('click', function() {
            const flightId = this.getAttribute('data-flight-id');
            const availableSeats = this.getAttribute('data-available-seats');

            // Устанавливаем ID рейса в форму
            document.getElementById('flight_id').value = flightId;
            document.getElementById('order-amount').max = availableSeats;

            // Генерация кнопок мест
            const seatsSelection = document.getElementById('seats-selection');
            seatsSelection.innerHTML = ''; // Очищаем предыдущие места

            // Запрашиваем доступные места для выбранного рейса
            fetch(`get_seats.php?flight_id=${flightId}`)
                .then(response => response.json())
                .then(seats => {
                    seats.forEach(seat => {
                        let seatButton = document.createElement('button');
                        seatButton.type = 'button';
                        seatButton.classList.add('seat');
                        seatButton.textContent = 'Место ' + seat.seat_number;
                        seatButton.dataset.seatNumber = seat.seat_number;
                        if (seat.is_booked) {

                            seatButton.disabled = true; // Заблокировать занятые места
                        }
                        seatsSelection.appendChild(seatButton);
                    });
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
// Обработчик кликов для выбора мест
            document.getElementById('seats-selection').addEventListener('click', function(event) {
                // Проверка, что клик был по кнопке места (кнопки для мест имеют класс 'seat')
                if (event.target.classList.contains('seat')) {
                    const seatButton = event.target;

                    // Если место уже выбрано, не делаем ничего
                    if (seatButton.disabled) {
                        return;
                    }

                    // Подсветка выбранного места
                    seatButton.style.backgroundColor = '#cc7a00'; // Цвет подсветки
                    seatButton.disabled = true; // Делаем место неактивным (нельзя выбрать снова)

                    // Обновление формы выбранных мест
                    const selectedSeats = document.querySelectorAll('.seat');
                    let selectedSeatNumbers = [];
                    selectedSeats.forEach(seat => {
                        if (seat.disabled) {
                            selectedSeatNumbers.push(seat.dataset.seatNumber);
                        }
                    });

                    // Если на форме есть поле для выбранных мест, обновляем его
                    let seatInput = document.getElementById('selected-seats');
                    if (!seatInput) {
                        seatInput = document.createElement('input');
                        seatInput.type = 'hidden';
                        seatInput.name = 'selected_seats';
                        seatInput.id = 'selected-seats';
                        document.querySelector('form').appendChild(seatInput);
                    }

                    seatInput.value = selectedSeatNumbers.join(',');
                }
            });
// Обработчик для сброса выбора мест
            document.getElementById('reset-seats').addEventListener('click', function() {
                // Очищаем выделенные места
                document.querySelectorAll('.seat').forEach(seat => {
                    seat.style.backgroundColor = ''; // Сброс цвета подсветки
                    seat.disabled = false; // Делаем место доступным для повторного выбора
                });

                // Сброс значений в форме
                document.getElementById('selected-seats').value = '';
                document.getElementById('order-amount').value = ''; // Обнуляем количество мест
            });

            // Показываем модальное окно
            document.getElementById('modal').style.display = 'block';
            document.body.style.overflow = 'hidden'; // Блокируем прокрутку страницы
        });
    });

    // Закрытие модального окна
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('modal').style.display = 'none';
        document.body.style.overflow = 'auto'; // Разблокировать прокрутку страницы
    });

    // Закрытие модального окна при клике за его пределами
    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById('modal')) {
            document.getElementById('modal').style.display = 'none';
            document.body.style.overflow = 'auto'; // Разблокировать прокрутку страницы
        }
    });
</script>
<script src="js/filter-bilet.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script src="js/script.js"></script>
    </body>
</html>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f8fb;
        margin: 0;
        overflow: auto;
    }

    .flights-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        max-width: 1200px;
        margin: 0 auto;
        justify-content: center;
        padding: 20px;
    }

    .flight-card {
        background-color: #ffffff; /* Белый фон */
        color: #333333;
        border-radius: 10px;
        padding: 20px;
        width: calc(33.33% - 20px); /* Ширина для трех карточек в ряд */
        max-width: 300px; /* Ограничение максимальной ширины */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s;
        display: flex;
        flex-direction: column;
    }

    .flight-card:hover {
        transform: translateY(-8px);
    }

    .select-seat-button {
        background-color: #51B0BA; /* Бирюзовый цвет кнопки */
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 15px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 15px;
        width: 100%;
        text-align: center;
    }

    .select-seat-button:hover {
        background-color: #3a8a91; /* Более тёмный оттенок бирюзового при наведении */
    }

    /* Модальное окно */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        padding-top: 60px;
        overflow-y: auto;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 40px;
        border: 1px solid #888;
        width: 80%;
        max-width: 900px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        border-radius: 8px;
        position: relative;
    }

    .close {
        color: #aaa;
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .seats-selection {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin: 20px 0;
    }

    .seat {
        background-color: #51B0BA; /* Бирюзовый цвет */
        color: white;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .seat:hover {
        background-color: #3a8a91;
    }

    .order-amount-input {
        width: 60px;
        padding: 5px;
        margin: 10px 0;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .order-button {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
    }

    .order-button:hover {
        background-color: #45a049;
    }

    .reset-seats-button {
        background-color: #f44336;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 15px;
    }

    .reset-seats-button:hover {
        background-color: #d32f2f;
    }

    p{
        font-size: 20px;
    }
    /* Адаптивные стили */
    @media (max-width: 768px) {
        .flights-container {
            flex-direction: column;
            gap: 20px;
        }

        .flight-card {
            width: 100%;
            padding: 15px;
        }

        .select-seat-button {
            width: 100%;
            padding: 12px 15px;
        }

        .modal-content {
            width: 90%;
            padding: 20px;
        }

        .seats-selection {
            gap: 10px;
        }

        .seat {
            padding: 8px;
        }

        .order-amount-input {
            width: 50px;
        }

        .order-button, .reset-seats-button {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .flights-container {
            padding: 10px;
        }

        .flight-card {
            width: 100%;
            padding: 10px;
        }

        .select-seat-button {
            width: 100%;
            padding: 10px;
        }

        .modal-content {
            width: 95%;
            padding: 15px;
        }

        .seats-selection {
            flex-direction: column;
            gap: 8px;
        }

        .seat {
            padding: 6px;
        }

        .order-amount-input {
            width: 50px;
            font-size: 14px;
        }

        .order-button, .reset-seats-button {
            width: 100%;
        }
    }
</style>

