<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_SESSION['avatar'])) {
    $avatarFile = $_SESSION['avatar'];
    if (file_exists($avatarFile)) {
        unlink($avatarFile); // Удаляем файл с сервера
        unset($_SESSION['avatar']); // Убираем из сессии

        // Удаляем путь к аватару из базы данных
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "Skylink777";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $login = $_SESSION['login'];
        $stmt = $conn->prepare("UPDATE Passengers SET avatar_path = NULL WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        echo "Аватар удалён успешно!";
    } else {
        echo "Аватар не найден.";
    }
} else {
    echo "Аватар не загружен.";
}
?>
