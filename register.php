<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Функция для обработки ошибок
function handleError($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Подключение к базе данных
$host = 'localhost';
$username = 'root'; // Замените на ваше имя пользователя
$password = ''; // Замените на ваш пароль
$database = 'Skylink777'; // Замените на ваше имя базы данных

$conn = new mysqli($host, $username, $password, $database);

// Проверка подключения
if ($conn->connect_error) {
    handleError("Ошибка подключения: " . $conn->connect_error);
}

// Получение данных из формы с защитой от SQL-инъекций
$name = $conn->real_escape_string(trim($_POST['name'] ?? ''));
$surname = $conn->real_escape_string(trim($_POST['surname'] ?? ''));
$age = intval(trim($_POST['age'] ?? ''));
$gender = $conn->real_escape_string(trim($_POST['gender'] ?? ''));
$email = $conn->real_escape_string(trim($_POST['email'] ?? ''));
$phone = trim($_POST['phone'] ?? ''); // Убираем лишние пробелы
$password = trim($_POST['password'] ?? '');
$confirmPassword = trim($_POST['confirmPassword'] ?? '');
$login = $conn->real_escape_string(trim($_POST['login'] ?? '')); // Добавлено поле логина
$country = $conn->real_escape_string(trim($_POST['country'] ?? '')); // Добавлено поле страны

// Логируем номер телефона для проверки
error_log("Phone number received: " . var_export($phone, true));

// Проверка на незаполненные поля
if (empty($name) || empty($surname) || empty($age) || empty($gender) || empty($email) || empty($phone) || empty($password) || empty($confirmPassword) || empty($login) || empty($country)) {
    handleError("Заполните все поля.");
}

// Дополнительная серверная валидация
if (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s]+$/u', $name)) {
    handleError("Имя должно содержать только буквы.");
}

if (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s]+$/u', $surname)) {
    handleError("Фамилия должна содержать только буквы.");
}

if ($age < 0) {
    handleError("Возраст не может быть отрицательным.");
}

if (!in_array($gender, ['male', 'female', 'other'])) {
    handleError("Выберите корректный пол.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    handleError("Некорректный email.");
}

// Валидация номера телефона
if (!preg_match('/^\+\d{1,3} \(\d{3}\) \d{3}[- ]\d{2}[- ]\d{2}$/', $phone)) {
    handleError("Телефон должен быть в формате +X (XXX) XXX-XX-XX или +X (XXX) XXX XX XX.");
}

// Проверка на совпадение паролей
if ($password !== $confirmPassword) {
    handleError("Пароли не совпадают.");
}

// Хеширование пароля
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// SQL-запрос на вставку данных без поля role
$sql = "INSERT INTO Passengers (name, surname, age, gender, email, phone, password_hash, login, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Подготовка и выполнение запроса
$stmt = $conn->prepare($sql);
if (!$stmt) {
    handleError("Ошибка подготовки запроса: " . $conn->error);
}

// Привязка параметров
$stmt->bind_param("ssissssss", $name, $surname, $age, $gender, $email, $phone, $password_hash, $login, $country);

// Выполнение запроса и проверка результата
if ($stmt->execute()) {
    echo "Регистрация прошла успешно!";
} else {
    handleError("Ошибка: " . $stmt->error);
}


// Закрытие запроса и соединения
$stmt->close();
$conn->close();

