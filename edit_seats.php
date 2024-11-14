<?php
session_start();

// Проверка на роль администратора
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Skylink777";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT flight_id, seat_number, is_booked, class FROM Seats WHERE seat_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($flight_id, $seat_number, $is_booked, $class);
    $stmt->fetch();
    $stmt->close();

    if (!$flight_id) {
        echo "Место не найдено.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_id = $_POST['flight_id'];
    $seat_number = $_POST['seat_number'];
    $is_booked = $_POST['is_booked'];
    $class = $_POST['class'];

    $stmt = $conn->prepare("UPDATE Seats SET flight_id = ?, seat_number = ?, is_booked = ?, class = ? WHERE seat_id = ?");
    $stmt->bind_param("isisi", $flight_id, $seat_number, $is_booked, $class, $id);

    if ($stmt->execute()) {
        header("Location: seats.php");
        exit;
    } else {
        echo "Ошибка при сохранении изменений: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
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
        <h1>Редактирование места</h1>
        <a href="seats.php">Назад к админ-панели</a>
    </div>
    <form method="POST">
        <label for="flight_id">Flight ID:</label>
        <input type="number" name="flight_id" value="<?php echo htmlspecialchars($flight_id); ?>" required><br>
        <label for="seat_number">Seat Number:</label>
        <input type="text" name="seat_number" value="<?php echo htmlspecialchars($seat_number); ?>" required><br>
        <label for="is_booked">Booked:</label>
        <select name="is_booked" required>
            <option value="0" <?php echo $is_booked == 0 ? 'selected' : ''; ?>>No</option>
            <option value="1" <?php echo $is_booked == 1 ? 'selected' : ''; ?>>Yes</option>
        </select><br>
        <label for="class">Class:</label>
        <input type="text" name="class" value="<?php echo htmlspecialchars($class); ?>" required><br>
        <button type="submit">Сохранить изменения</button>
    </form>
    </body>
    </html>
<?php
