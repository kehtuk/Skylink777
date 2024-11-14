<?php
session_start();
include 'db_connect.php';
// Проверка, авторизован ли пользователь
if (!isset($_SESSION['passenger_id'])) {
    header("Location: index.php");
    exit;
}

$route = 'Москва, Россия - Лондон, Великобритания';
$duration = 180;
$price = 600.00;
$min_price = 500.00;
$max_price = 1000.00;
$flight_id = 3;
$class = 'economy';
$date = '2024-10-15';
$destination_country = 'США';

function executeQuery($conn, $sql) {
    $result = $conn->query($sql);
    if ($result === false) {
        echo "<div class='error'>Ошибка SQL: " . $conn->error . "</div>";
        return;
    }
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>";
        foreach ($result->fetch_fields() as $field) {
            echo "<th>{$field->name}</th>";
        }
        echo "</tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Нет данных для отображения.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyLink</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .query-block { margin-bottom: 30px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .error { color: red; }

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

<h1>Результаты SQL-запросов</h1>

<div class="query-block">
    <h2>1. Список и общее число рейсов по маршруту, длительности, цене и всем критериям сразу</h2>
    <?php
    $sql1 = "SELECT flight_id, departure_location, arrival_location, duration, price, COUNT(*) AS total_flights 
             FROM Flights 
             WHERE departure_location = 'Москва, Россия' 
             AND arrival_location = 'Лондон, Великобритания' 
             AND duration = $duration 
             AND price = $price
             GROUP BY flight_id";
    executeQuery($conn, $sql1);
    ?>
</div>

<div class="query-block">
    <h2>2. Список проданных авиабилетов в определенном ценовом диапазоне по указанному маршруту</h2>
    <?php
    $sql2 = "SELECT Orders.order_id, Orders.passenger_id, Orders.flight_id, Flights.departure_location, Flights.arrival_location, Flights.price 
             FROM Orders 
             JOIN Flights ON Orders.flight_id = Flights.flight_id 
             WHERE Flights.price BETWEEN $min_price AND $max_price 
             AND Flights.departure_location = 'Москва, Россия' 
             AND Flights.arrival_location = 'Техас, США'";
    executeQuery($conn, $sql2);
    ?>
</div>

<div class="query-block">
    <h2>3. Список пассажиров, купивших билеты на определенный рейс за последний месяц</h2>
    <?php
    $sql3 = "SELECT Passengers.passenger_id, Passengers.name, Passengers.age 
             FROM Orders 
             JOIN Passengers ON Orders.passenger_id = Passengers.passenger_id 
             WHERE Orders.flight_id = $flight_id 
             AND Orders.order_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
    executeQuery($conn, $sql3);
    ?>
</div>

<div class="query-block">
    <h2>4. Список и общее число авиабилетов по классу мест, проданных за неделю</h2>
    <?php
    $sql4 = "SELECT trip_class, COUNT(*) AS total_tickets 
             FROM Orders 
             WHERE trip_class = '$class' 
             AND order_date >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
             GROUP BY trip_class";
    executeQuery($conn, $sql4);
    ?>
</div>

<div class="query-block">
    <h2>5. Список и общее число пассажиров на данном рейсе, улетевших в указанный день или за границу</h2>
    <?php
    $sql5_1 = "SELECT Passengers.passenger_id, Passengers.name, COUNT(*) AS total_passengers 
               FROM Orders 
               JOIN Flights ON Orders.flight_id = Flights.flight_id 
               JOIN Passengers ON Orders.passenger_id = Passengers.passenger_id 
               WHERE Flights.date = '$date' 
               AND Orders.flight_id = $flight_id
               GROUP BY Passengers.passenger_id";
    executeQuery($conn, $sql5_1);

    $sql5_2 = "SELECT Passengers.passenger_id, Passengers.name, COUNT(*) AS total_passengers 
               FROM Orders 
               JOIN Flights ON Orders.flight_id = Flights.flight_id 
               JOIN Passengers ON Orders.passenger_id = Passengers.passenger_id 
               WHERE Flights.date = '$date' 
               AND Flights.arrival_location LIKE '%$destination_country%'
               GROUP BY Passengers.passenger_id";
    executeQuery($conn, $sql5_2);
    ?>
</div>

<div class="query-block">
    <h2>6. Список и общее число свободных и забронированных мест на указанном рейсе на определенный день</h2>
    <?php
    $sql6 = "SELECT total_seats, available_seats, (total_seats - available_seats) AS booked_seats 
             FROM Flights 
             WHERE flight_id = $flight_id AND date = '$date'";
    executeQuery($conn, $sql6);
    ?>
</div>

<div class="query-block">
    <h2>7. Список свободных и забронированных мест по маршруту, цене и времени вылета</h2>
    <?php
    $departure_time = '12:00:00';
    $price = 600.00;  // Убедимся, что эта переменная имеет значение
    $sql7 = "SELECT flight_id, departure_location, arrival_location, price, departure_time, total_seats, available_seats 
             FROM Flights 
             WHERE departure_location = 'Москва, Россия' 
             AND arrival_location = 'Лондон, Великобритания' 
             AND price = $price 
             AND departure_time = '$departure_time'";
    executeQuery($conn, $sql7);
    ?>
</div>

<div class="query-block">
    <h2>8. Список совершеннолетних пассажиров, купивших билеты по указанному маршруту</h2>
    <?php
    $sql8 = "SELECT DISTINCT Passengers.passenger_id, Passengers.name, Passengers.age 
             FROM Orders 
             JOIN Passengers ON Orders.passenger_id = Passengers.passenger_id 
             JOIN Flights ON Orders.flight_id = Flights.flight_id 
             WHERE Flights.departure_location = 'Москва, Россия' 
             AND Flights.arrival_location = 'Лондон, Великобритания' 
             AND Passengers.age >= 18";
    executeQuery($conn, $sql8);
    ?>
</div>

<div class="query-block">
    <h2>9. Список несовершеннолетних пассажиров, купивших билеты в определенную страну</h2>
    <?php
    $destination_country = 'Атланта';
    // Упростим запрос для теста, временно убрав фильтр по возрасту
    $sql9 = "SELECT DISTINCT Passengers.passenger_id, Passengers.name, Passengers.age 
             FROM Orders 
             JOIN Passengers ON Orders.passenger_id = Passengers.passenger_id 
             JOIN Flights ON Orders.flight_id = Flights.flight_id 
             AND Passengers.age <= 17";
    executeQuery($conn, $sql9);
    ?>
</div>

<div class="query-block">
    <h2>10. Общее число сданных билетов на рейс, в указанный день, по маршруту или цене билета</h2>
    <?php
    $sql10 = "SELECT COUNT(*) AS total_refunded_tickets 
              FROM Orders 
              JOIN Flights ON Orders.flight_id = Flights.flight_id 
              WHERE Orders.status = 'canceled' 
              AND Orders.flight_id = 7 
              AND Flights.date = '2024-11-06' 
              AND Flights.departure_location = 'Москва' 
              AND Flights.arrival_location = 'Атланта' 
              AND Flights.price = 1000";
    executeQuery($conn, $sql10);
    ?>
</div>

</body>
</html>

<?php $conn->close(); ?>

<style>
/* Основной стиль для страницы */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f8fb;
    margin: 20px;
    padding: 0;
    color: #333;
}

h1 {
    font-size: 2em;
    text-align: center;
    margin-bottom: 20px;
}

.query-block {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    font-size: 1.5em;
    margin-bottom: 10px;
    color: #333;
}

/* Стили для таблиц */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
}

th {
    background-color: #51B0BA;
    color: white;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

/* Ошибки и сообщения */
.error {
    color: red;
    background-color: #ffe6e6;
    padding: 10px;
    border: 1px solid #ffcccc;
    border-radius: 5px;
}

/* Адаптивные стили */
@media (max-width: 768px) {
    /* Для планшетов */
    body {
        padding: 10px;
    }

    h1 {
        font-size: 1.8em;
    }

    .query-block {
        padding: 15px;
    }

    h2 {
        font-size: 1.3em;
    }

    table th, table td {
        padding: 8px;
    }
}

@media (max-width: 480px) {
    /* Для мобильных устройств */
    body {
        padding: 5px;
    }

    h1 {
        font-size: 1.5em;
    }

    .query-block {
        padding: 10px;
    }

    h2 {
        font-size: 1.1em;
    }

    table th, table td {
        padding: 6px;
    }

    table {
        font-size: 14px;
    }
}

</style>