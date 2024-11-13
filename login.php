<?php
session_start(); // Начинаем сессию

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Skylink777";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверка, был ли отправлен POST запрос
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $recaptchaResponse = $_POST['g-recaptcha-response'];  // Получаем значение g-recaptcha-response

    // Проверка reCAPTCHA
    $secretKey = "6LfDD18qAAAAAPJzLjfw_2D8lIuqYeZS4Hu0FpIA"; // Замените вашим секретным ключом
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    // Если проверка не прошла
    if (intval($responseKeys["success"]) !== 1) {
        die("Проверка reCAPTCHA не пройдена. Пожалуйста, попробуйте снова.");
    }

    // Подготовляем SQL запрос для предотвращения SQL-инъекций
    $stmt = $conn->prepare("SELECT password_hash, role, passenger_id FROM Passengers WHERE login = ?");
    if ($stmt === false) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }

    $stmt->bind_param('s', $login);
    $stmt->execute();
    $stmt->store_result();

    // Проверяем, существует ли пользователь с таким логином
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($password_hash, $role, $passenger_id); // Добавляем id пользователя
        $stmt->fetch();

        // Проверяем правильность пароля
        if (password_verify($password, $password_hash)) {
            // Если пароль верный, устанавливаем сессию
            $_SESSION['login'] = htmlspecialchars($login);
            $_SESSION['role'] = htmlspecialchars($role); // Сохраняем роль пользователя
            $_SESSION['passenger_id'] = (int)$passenger_id; // Сохраняем ID пользователя

            // Обновляем идентификатор сессии для безопасности
            session_regenerate_id(true);

            // Перенаправление на админ-панель, если роль администратора
            if ($role === 'admin') {
                header("Location: admin.php"); // Перенаправляем на админ-панель
            } else {
                header("Location: profile.php"); // Перенаправляем на профиль пользователя
            }
            exit;
        } else {
            echo "Неверный пароль."; // Обработка ошибочного пароля
        }
    } else {
        echo "Пользователь не найден."; // Обработка отсутствующего пользователя
    }

    $stmt->close();
}

$conn->close();
?>
