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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить пользователя</title>
</head>
<body>
<h1>Изменить пользователя</h1>

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
<a href="admin.php">Назад к админ-панели</a>
</body>
</html>
