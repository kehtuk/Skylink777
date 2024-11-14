<!DOCTYPE html>
<html lang="ru,en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SkyLink - Рейсы</title>
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
                    <a href="flightss.php" class="menu-link"><span class="current-page">Рейсы</span></a>
                </li>
                <li class="menu-item">
                    <a href="promotions.php" class="menu-link">Акции</a>
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
    <section class="new">
        <div class="container">
            <h2 class="new-topic">
                Новые рейсы
            </h2>
            <p class="new-subtitle">
                Открой новые горизонты с нашими новыми рейсами!
            </p>
            <div class="new-content">
                <a href="purchase.php" class="new-content-card-link">
                    <div class="new-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="new-content-card-picture">
                        <h3 class="new-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="new-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
                <a href="purchase.php" class="new-content-card-link">
                    <div class="new-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="new-content-card-picture">
                        <h3 class="new-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="new-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
                <a href="purchase.php" class="new-content-card-link">
                    <div class="new-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="new-content-card-picture">
                        <h3 class="new-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="new-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </section>
    <!--------------Section_2-------------->
    <section class="actual">
        <div class="container">
            <h2 class="actual-topic">
                Актуальные рейсы
            </h2>
            <p class="actual-subtitle">
                Экономь на перелетах с нашими актуальными предложениями!
            </p>
            <div class="actual-content">
                <a href="#" class="actual-content-card-link">
                    <div class="actual-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="actual-content-card-picture">
                        <h3 class="actual-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="actual-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
                <a href="#" class="actual-content-card-link">
                    <div class="actual-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="actual-content-card-picture">
                        <h3 class="actual-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="actual-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
                <a href="#" class="actual-content-card-link">
                    <div class="actual-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="actual-content-card-picture">
                        <h3 class="actual-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="actual-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </section>
    <!--------------Section_3-------------->
    <section class="popular">
        <div class="container">
            <h2 class="popular-topic">
                Популярные рейсы
            </h2>
            <p class="popular-subtitle">
                Летай, куда летают другие - самые популярные рейсы!
            </p>
            <div class="popular-content">
                <a href="#" class="popular-content-card-link">
                    <div class="popular-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="popular-content-card-picture">
                        <h3 class="popular-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="popular-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
                <a href="#" class="popular-content-card-link">
                    <div class="popular-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="popular-content-card-picture">
                        <h3 class="popular-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="popular-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
                <a href="#" class="popular-content-card-link">
                    <div class="popular-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="popular-content-card-picture">
                        <h3 class="popular-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="popular-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
                <a href="#" class="popular-content-card-link">
                    <div class="popular-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="popular-content-card-picture">
                        <h3 class="popular-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="popular-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
                <a href="#" class="popular-content-card-link">
                    <div class="popular-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="popular-content-card-picture">
                        <h3 class="popular-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="popular-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
                <a href="#" class="popular-content-card-link">
                    <div class="popular-content-card">
                        <img src="img/Майами.svg" alt="Майами" class="popular-content-card-picture">
                        <h3 class="popular-content-card-title">
                            Майами - Флорида
                        </h3>
                        <p class="popular-content-card-text">
                            Солнечные пляжи и разнообразная ночная жизнь
                        </p>
                    </div>
                </a>
            </div>
    </section>
    <!--------------Section_4-------------->
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