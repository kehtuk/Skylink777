<?php
$flight_id = isset($_GET['flight_id']) ? (int)$_GET['flight_id'] : 0;
$trip_class = isset($_GET['trip_class']) ? $_GET['trip_class'] : 'economy';

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SkyLink777";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Запрос для получения доступных мест определенного класса
$sql = "SELECT seat_id, seat_number, is_booked FROM Seats WHERE flight_id = ? AND class = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $flight_id, $trip_class);
$stmt->execute();
$result = $stmt->get_result();

$seats = [];
while ($row = $result->fetch_assoc()) {
    $seats[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($seats);
?>
