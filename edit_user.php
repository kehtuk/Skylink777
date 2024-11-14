<?php
session_start();
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

    // Получаем данные пользователя из базы
    $stmt = $conn->prepare("SELECT login, email, phone, name, surname, age, gender, country, avatar_path FROM Passengers WHERE passenger_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($login, $email, $phone, $name, $surname, $age, $gender, $country, $avatar_path);
    $stmt->fetch();
    $stmt->close();

    // Если пользователь не найден
    if (!$login) {
        echo "Пользователь не найден.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $country = $_POST['country'];
    $avatar_path = $_POST['avatar_path']; // Путь к аватару (если есть)

    // Получаем новый и подтвержденный пароль
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка на пустые значения (если необходимо)
    if (empty($login) || empty($email) || empty($name) || empty($surname) || empty($age) || empty($gender) || empty($country)) {
        echo "Пожалуйста, заполните все обязательные поля.";
        exit;
    }

    // Проверка на совпадение пароля и подтверждения пароля
    if ($new_password !== $confirm_password) {
        echo "Пароли не совпадают.";
        exit;
    }

    // Если пароль был изменен, хешируем новый пароль
    if (!empty($new_password)) {
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE Passengers SET login = ?, email = ?, phone = ?, name = ?, surname = ?, age = ?, gender = ?, country = ?, avatar_path = ?, password_hash = ? WHERE passenger_id = ?");
        $stmt->bind_param("ssssssssssi", $login, $email, $phone, $name, $surname, $age, $gender, $country, $avatar_path, $password_hash, $id);
    } else {
        // Если пароль не изменяется, просто обновляем другие данные
        $stmt = $conn->prepare("UPDATE Passengers SET login = ?, email = ?, phone = ?, name = ?, surname = ?, age = ?, gender = ?, country = ?, avatar_path = ? WHERE passenger_id = ?");
        $stmt->bind_param("sssssssssi", $login, $email, $phone, $name, $surname, $age, $gender, $country, $avatar_path, $id);
    }

    if ($stmt->execute()) {
        // Редирект после успешного обновления
        header("Location: admin.php");
        exit;
    } else {
        // Выводим ошибку, если запрос не удается выполнить
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
    <h1>Редактирование пользователя</h1>
    <a href="seats.php">Назад к админ-панели</a>
</div>

<form method="POST">
    <label for="login">Логин:</label>
    <input type="text" name="login" value="<?php echo htmlspecialchars($login); ?>" required>
    <br>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    <br>

    <label for="phone">Телефон:</label>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
    <br>

    <label for="name">Имя:</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
    <br>

    <label for="surname">Фамилия:</label>
    <input type="text" name="surname" value="<?php echo htmlspecialchars($surname); ?>" required>
    <br>

    <label for="age">Возраст:</label>
    <input type="number" name="age" value="<?php echo htmlspecialchars($age); ?>" required>
    <br>

    <label for="gender">Пол:</label>
    <select name="gender" required>
        <option value="male" <?php echo ($gender == 'male') ? 'selected' : ''; ?>>Мужской</option>
        <option value="female" <?php echo ($gender == 'female') ? 'selected' : ''; ?>>Женский</option>
    </select>
    <br>

    <label for="country">Страна:</label>
    <input type="text" name="country" value="<?php echo htmlspecialchars($country); ?>" required>
    <br>

    <label for="avatar_path">Путь к аватару:</label>
    <input type="text" name="avatar_path" value="<?php echo htmlspecialchars($avatar_path); ?>">
    <br>

    <!-- Поля для смены пароля -->
    <label for="new_password">Новый пароль:</label>
    <input type="password" name="new_password">
    <br>

    <label for="confirm_password">Подтверждение пароля:</label>
    <input type="password" name="confirm_password">
    <br>

    <button type="submit">Сохранить изменения</button>
</form>

<br>
</body>
</html>
