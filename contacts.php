<!DOCTYPE html>
<html lang="ru,en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyLink - Контакты</title>
    <link rel="stylesheet" type="text/css" href="scss/style.css">
    <link rel="stylesheet" type="text/css" href="scss/media.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <!-------------Header--------------->
    <header>
        <nav class="menu">
            <ul class="menu-top">
                <li class="menu-item">
                    <a href="index.php" class="menu-link"><img src="img/Логотип.svg" alt="Logo"></a>
                </li>
                <li class="menu-item">
                    <a href="flightss.php" class="menu-link">Рейсы</a>
                </li>
                <li class="menu-item">
                    <a href="promotions.php" class="menu-link">Акции</a>
                </li>
                <li class="menu-item">
                    <a href="information.php" class="menu-link">Информация</a>
                </li>
                <li class="menu-item">
                    <a href="contacts.php" class="menu-link"><span class="current-page">Контакты</span></a>
                </li>

                <?php session_start(); // Убедитесь, что сессия запущена ?>
                <?php if (isset($_SESSION['passenger_id'])): ?>
                    <li class="menu-item">
                        <a href="profile.php" class="menu-link reg">Профиль</a>
                    </li>
                <?php else: ?>
                    <li class="menu-item">
                        <a href="#" class="menu-link enter">Вход</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link reg">Регистрация</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <!--------------popUps-------------->
<!-- Попап авторизации -->
<div id="loginModal" class="modal">
    <div class="modal-content">
      <img class="modal-content-logo" src="img/LogoPopup.svg" alt="SkyLink">
      <h2 class="modal-content-title">Авторизация</h2>
      <input type="text" placeholder="Логин">
      <input type="password" placeholder="Пароль">
      <button class="login" id="loginButton">Войти</button>
      <button id="registerOpenButton" class="switchPopupButton">Регистрация</button>
    </div>
  </div>
  
  <!-- Попап регистрации -->
  <div id="registerModal" class="modal">
    <div class="modal-content">
      <img class="modal-content-logo" src="img/LogoPopup.svg" alt="SkyLink">
      <h2 class="modal-content-title">Регистрация</h2>
      <input type="text" placeholder="Логин">
      <input type="email" placeholder="Email">
      <input type="tel" placeholder="Телефон">
      <input type="password" placeholder="Пароль">
      <input type="password" placeholder="Подтверждение пароля">
      <button class="register" id="registerButton">Зарегистрироваться</button>
      <button id="loginOpenButton" class="switchPopupButton">Авторизация</button>
    </div>
  </div>
    <!--------------Section_1-------------->
    <section class="contact">
        <div class="container">
            <h2 class="contact-topic">
                Контактные данные
            </h2>
            <p class="contact-text">
                На данной странице вы можете найти информацию для связи с нами, включая наш номер телефона, адрес офиса и электронную почту. 
            </p>
            <p class="contact-text">
                Не стесняйтесь обращаться к нам для получения помощи или задать вопросы. Мы всегда готовы помочь вам! 
            </p>
            <div class="contact-structure">
                <div class="contact-structure-row">
                    <h3 class="contact-structure-row-subtitle">
                        Телефон:
                    </h3>
                    <p class="contact-structure-row-info">
                        +7 (777) 777-77-77
                    </p>
                </div>
                <div class="contact-structure-row">
                    <h3 class="contact-structure-row-subtitle">
                        Адрес:
                    </h3>
                    <p class="contact-structure-row-info">
                        Улица Облаков, 10, Летящий Город, Россия, 12345
                    </p>
                </div>
                <div class="contact-structure-row">
                    <h3 class="contact-structure-row-subtitle">
                        Эл.почта:
                    </h3>
                    <p class="contact-structure-row-info">
                        contact@skylinkairlines.com
                    </p>
                </div>
                <div class="contact-structure-socials">
                    <a href="#" class="contact-structure-socials-item"><img src="img/Телеграм.svg" alt="Телеграм"></a>
                    <a href="#" class="contact-structure-socials-item"><img src="img/ВКонтакте.svg" alt="ВКонтакте"></a>
                    <a href="#" class="contact-structure-socials-item"><img src="img/Ютуб.svg" alt="Ютуб"></a>
                </div>
            </div>
        </div>
    </section>
    <!--------------Footer-------------->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-content-element">
                    <a href="index.php" class="footer-content-element-link"><img src="img/Минилого.svg" alt="Логотип"></a>
                </div>
                <div class="footer-content-element">
                    <ul class="footer-content-list">
                        <li class="footer-content-list-item"><a href="flightss.php" class="footer-content-list-item-link">Рейсы</a></li>
                        <li class="footer-content-list-item"><a href="promotions.php" class="footer-content-list-item-link">Акции</a></li>
                        <li class="footer-content-list-item"><a href="information.php" class="footer-content-list-item-link">Информация</a></li>
                        <li class="footer-content-list-item"><a href="contacts.php" class="footer-content-list-item-link">Контакты</a></li>
                        <li class="footer-content-list-item"><a href="profile.html" class="footer-content-list-item-link">Профиль</a></li>
                    </ul>
                    <p class="footer-content-copyright">© 2024 SkyLink. All rights reserved.</p>
                </div>
                <div class="footer-content-element">
                    <a href="#" class="footer-content-socials"><img src="img/Телеграм.svg" alt="Телеграм"></a>
                    <a href="#" class="footer-content-socials"><img src="img/ВКонтакте.svg" alt="ВКонтакте"></a>
                    <a href="#" class="footer-content-socials"><img src="img/Ютуб.svg" alt="Ютуб"></a>
                </div>
            </div>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>