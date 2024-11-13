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
    <title>Запросы к БД Skylink777</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .query-block { margin-bottom: 30px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .error { color: red; }
    </style>
</head>
<body>
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