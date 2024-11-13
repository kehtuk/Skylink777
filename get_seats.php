<?php
// Получаем ID рейса
if (!isset($_GET['flight_id']) || empty($_GET['flight_id'])) {
    echo json_encode([]);
    exit;
}

$flight_id = (int)$_GET['flight_id'];

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

// Запрос для получения информации о местах рейса
$sql = "SELECT seat_number, is_booked FROM Seats WHERE flight_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $flight_id);
$stmt->execute();
$result = $stmt->get_result();

$seats = [];
while ($row = $result->fetch_assoc()) {
    $seats[] = [
        'seat_number' => $row['seat_number'],
        'is_booked' => (bool)$row['is_booked'],
    ];
}

$stmt->close();
$conn->close();

// Отправка данных о местах в формате JSON
echo json_encode($seats);
?>
