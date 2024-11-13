<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $uploadDir = 'avatars/';
        $avatarFile = $uploadDir . basename($_FILES['avatar']['name']);

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarFile)) {
            // Сохраните путь к аватару в сессии
            $_SESSION['avatar'] = $avatarFile;

            // Подключение к базе данных
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "Skylink777";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Обновление записи с новым путём к аватару
            $login = $_SESSION['login'];
            $stmt = $conn->prepare("UPDATE Passengers SET avatar_path = ? WHERE login = ?");
            $stmt->bind_param("ss", $avatarFile, $login);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            echo "Аватар загружен успешно!";
        } else {
            echo "Ошибка загрузки аватара.";
        }
    } else {
        echo "Файл не выбран или произошла ошибка при загрузке.";
    }
}
?>
