<!DOCTYPE html>
<html lang="ru,en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyLink - Акции</title>
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
                    <a href="promotions.php" class="menu-link"><span class="current-page">Акции</span></a>
                </li>
                <li class="menu-item">
                    <a href="information.php" class="menu-link">Информация</a>
                </li>
                <li class="menu-item">
                    <a href="contacts.php" class="menu-link">Контакты</a>
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
    <section class="sale">
        <div class="container">
            <h2 class="sale-topic">
                Специальные предложения
            </h2>
            <div class="sale-content">
                <div class="sale-content-card">
                    <div class="sale-content-card-info">
                        <h3 class="sale-content-card-info-title">
                            Летние скидки
                        </h3>
                        <p class="sale-content-card-info-text">
                            Бронируйте прямо сейчас и экономьте. 
                        </p>
                        <p class="sale-content-card-info-text">
                            ¡Спешите, количество мест ограничено!
                        </p>
                        <a href="#" class="sale-content-card-info-link">Оформить</a>
                    </div>
                </div>
                <div class="sale-content-card">
                    <div class="sale-content-card-info">
                        <h3 class="sale-content-card-info-title">
                            Летние скидки
                        </h3>
                        <p class="sale-content-card-info-text">
                            Бронируйте прямо сейчас и экономьте. 
                        </p>
                        <p class="sale-content-card-info-text">
                            ¡Спешите, количество мест ограничено!
                        </p>
                        <a href="#" class="sale-content-card-info-link">Оформить</a>
                    </div>
                </div>
                <div class="sale-content-card">
                    <div class="sale-content-card-info">
                        <h3 class="sale-content-card-info-title">
                            Летние скидки
                        </h3>
                        <p class="sale-content-card-info-text">
                            Бронируйте прямо сейчас и экономьте. 
                        </p>
                        <p class="sale-content-card-info-text">
                            ¡Спешите, количество мест ограничено!
                        </p>
                        <a href="#" class="sale-content-card-info-link">Оформить</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--------------Section_2-------------->
    <section class="special">
        <div class="container">
            <h2 class="special-topic">
                Эксклюзивные предложения
            </h2>
            <div class="special-form">
                <h3 class="special-form-title">Не пропустите наши эксклюзивные предложения!</h3>
                <p class="special-form-description">Подпишитесь на нашу рассылку и первыми узнавайте о новых акциях, скидках и специальных предложениях.</p>
                <form method="post" action="subscribe.php" class="special-form-subscription">
                    <input type="email" name="email" class="special-form-subscription-email" placeholder="Ваш адрес электронной почты" required>
                    <button type="submit" class="special-form-subscription-button">Подписаться</button>
                </form>
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