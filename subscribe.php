<?php
session_start(); // Начало сессии для доступа к данным о пользователе

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['passenger_id'])) {
    // Если пользователь не авторизован, перенаправляем его на страницу логина
    header('Location: login.php');
    exit();
}

// Подключение к базе данных
require_once('db_connect.php');  // Подключение файла с настройками базы данных

// Проверка, был ли отправлен email
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Валидация email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Если email невалидный
        echo "Неверный формат email.";
        exit();
    }

    // Получение информации о текущем пользователе
    $passenger_id = $_SESSION['passenger_id'];

    // Проверка, не подписан ли уже пользователь на рассылку
    $checkSubscriptionQuery = "SELECT * FROM Newsletter_Subscriptions WHERE passenger_id = ? AND status = 'subscribed'";
    $stmt = $pdo->prepare($checkSubscriptionQuery);
    $stmt->execute([$passenger_id]);

    if ($stmt->rowCount() > 0) {
        echo "Вы уже подписаны на рассылку.";
        exit();
    }

    // Вставка новой подписки (без subscription_type)
    $subscriptionQuery = "INSERT INTO Newsletter_Subscriptions (passenger_id, status) 
                          VALUES (?, 'subscribed')";
    $stmt = $pdo->prepare($subscriptionQuery);
    $stmt->execute([$passenger_id]);

    // Сообщение о успешной подписке
    echo "Вы успешно подписались на рассылку!";
}
?>