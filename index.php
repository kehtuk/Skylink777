<?php
session_start(); // Убедитесь, что сессия запущена

// Отладка: выводим содержимое сессии
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>

<!DOCTYPE html>
<html lang="ru,en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyLink - Главная</title>
    <link rel="stylesheet" type="text/css" href="scss/style.css">
    <link rel="stylesheet" type="text/css" href="scss/media.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>

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
                        <a href="flights.html" class="menu-link">Рейсы</a>
                    </li>
                    <li class="menu-item">
                        <a href="promotions.html" class="menu-link">Акции</a>
                    </li>
                    <li class="menu-item">
                        <a href="information.html" class="menu-link">Информация</a>
                    </li>
                    <li class="menu-item">
                        <a href="contacts.html" class="menu-link">Контакты</a>
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

        <form action="login.php" id="AuthorizationForm" name="AuthorizationForm" method="POST">
            <input id="login" name="login" type="text" placeholder="Логин">
            <input id="password" name="password" type="password" placeholder="Пароль">

            <div class="recaptcha-wrapper">
                <div class="g-recaptcha" data-sitekey="6LfDD18qAAAAAB0Ryd9C96i7dzX94bTbp6Ai3f0C"></div>
            </div>

            <button type="submit" class="login" id="loginButton">Войти</button>
            <button id="registerOpenButton" type="button" class="switchPopupButton">Регистрация</button>
        </form>
    </div>
</div>


        <!-- Попап регистрации -->
        <div id="registerModal" class="modal">
            <div class="modal-content">
                <img class="modal-content-logo" src="img/LogoPopup.svg" alt="SkyLink">
                <h2 class="modal-content-title">Регистрация</h2>
                <form id="registrationForm" method="POST" action="register.php" onsubmit="return validateRegistration();">
                    <input
                            id="regName"
                            name="name"
                            type="text"
                            placeholder="Имя"
                            required
                    >
                    <div class="error" id="nameError"></div>

                    <input
                            id="regSurname"
                            name="surname"
                            type="text"
                            placeholder="Фамилия"
                            required
                    >
                    <div class="error" id="surnameError"></div>

                    <input
                            id="regAge"
                            name="age"
                            type="number"
                            placeholder="Возраст"
                            required
                            min="0" 
                    >
                    <div class="error" id="ageError"></div>

                    <select id="regGender" name="gender" required>
                        <option value="" disabled selected>Выберите пол</option>
                        <option value="male">Мужчина</option>
                        <option value="female">Женщина</option>
                        <option value="other">Другой</option>
                    </select>
                    <div class="error" id="genderError"></div>

                    <input
                            id="regCountry"
                            name="country"
                            type="text"
                            placeholder="Страна"
                            required
                    >
                    <div class="error" id="countryError"></div>

                    <input
                            id="regLogin"
                            name="login"
                            type="text"
                            placeholder="Логин"
                            required
                            pattern="^[a-zA-Z0-9]{3,}$"
                            title="Логин должен содержать только буквы и цифры и быть не менее 3 символов"
                    >
                    <div class="error" id="loginError"></div>

                    <input
                            id="regEmail"
                            name="email"
                            type="email"
                            placeholder="Email"
                            required
                    >
                    <div class="error" id="emailError"></div>

                    <input
                            id="regPhone"
                            name="phone"
                            type="tel"
                            placeholder="Телефон"
                            required
                            autocomplete="off"
                    >
                    <div class="error" id="phoneError"></div>

                    <input
                            id="regPassword"
                            name="password"
                            type="password"
                            placeholder="Пароль"
                            required
                            minlength="6"
                            title="Пароль должен содержать минимум 6 символов"
                    >
                    <div class="error" id="passwordError"></div>

                    <input
                            id="confirmPassword"
                            name="confirmPassword"
                            type="password"
                            placeholder="Подтверждение пароля"
                            required
                    >
                    <div class="error" id="confirmPasswordError"></div>

                    <button type="submit" class="register">Зарегистрироваться</button>
                    <button id="loginOpenButton" type="button" class="switchPopupButton">Авторизация</button>
                </form>
            </div>
        </div>


<!-- <div id="registerModal" class="modal">
  <div class="modal-content">
    <img class="modal-content-logo" src="img/LogoPopup.svg" alt="SkyLink">
    <h2 class="modal-content-title">Регистрация</h2>
    <form id="registrationForm" name="registrationForm" method="POST" action="register.php">
        <input id="login" name="login" type="text" placeholder="Логин" required>
        <input id="email" name="email" type="email" placeholder="Email" required>
        <input id="phone" name="phone" type="tel" placeholder="Телефон" required>
        <input id="password" name="password" type="password" placeholder="Пароль" required>
        <input id="confirmPassword" name="confirmPassword" type="password" placeholder="Подтверждение пароля" required>
        <button type="submit" class="register" id="registerButton">Зарегистрироваться</button>
        <button id="loginOpenButton" type="button" class="switchPopupButton">Авторизация</button>
    </form>
  </div>
</div> -->

        <!--------------Section_1-------------->
        <section class="intro">
            <div class="intro-slider">
                <div class="intro-slider-slide">
                    <img src="img/ДубайСлайдер.png" alt="Dubai">
                    <div class="intro-slider-slide-slogan"><h1 class="intro-slider-slide-slogan-topic">Лети в Дубаи вместе с SkyLink!</h1></div>
                </div>
                <div class="intro-slider-slide">
                    <img src="img/Анталья.png" alt="Antalya">
                    <div class="intro-slider-slide-slogan"><h1 class="intro-slider-slide-slogan-topic">Лети в Анталью вместе с SkyLink!</h1></div>  
                </div>
                <div class="intro-slider-slide">
                    <img src="img/Мальдивы.png" alt="Antalya">
                    <div class="intro-slider-slide-slogan"><h1 class="intro-slider-slide-slogan-topic">Лети на Мальдивы вместе с SkyLink!</h1></div>
                </div>
                <div class="intro-slider-slide">
                    <img src="img/Гавайи.png" alt="Antalya">
                    <div class="intro-slider-slide-slogan"><h1 class="intro-slider-slide-slogan-topic">Лети на Гавайи вместе с SkyLink!</h1></div>
                </div>
                <div class="intro-slider-slide">
                    <img src="img/Мексика.png" alt="Antalya">
                    <div class="intro-slider-slide-slogan"><h1 class="intro-slider-slide-slogan-topic">Лети в Мексику вместе с SkyLink!</h1></div>
                </div>
                    <div class="intro-slider-controls">
                        <button class="intro-slider-controls-prev"><img src="img/влево.svg" alt="Previous"></button>
                        <button class="intro-slider-controls-next"><img src="img/вправо.svg" alt="Next"></button>
                    </div>
            </div>
        </section>
        <!--------------Section_1.5-------------->
        <section class="purchase">
            <div class="container">
                <div class="purchase-form">
                    <h2 class="purchase-form-topic">Покупка билета</h2>
                    <form method="post" action="purchase.php">
                    <div class="purchase-form-line">
                        <div class="purchase-form-input">
                            <input class="purchase-form-input-background" type="text" name="departure" placeholder="Откуда">
                            <input class="purchase-form-input-background" type="text" name="destination" placeholder="Куда">
                        </div>
                        <div class="purchase-form-input">
                            <input class="purchase-form-input-background" type="date" name="departure_date" placeholder="Туда">
                            <input class="purchase-form-input-background" type="date" name="return_date" placeholder="Обратно">
                        </div>
                        <div class="purchase-form-input">
                            <input class="purchase-form-input-background amount" type="number" name="amount" placeholder="Количество">
                        </div>
                        <div class="purchase-form-input"></div>
                        <button class="purchase-form-input-btn" type="Submit"><img src="img/Билет.svg" alt="Билет"></button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </section>
        <!--------------Section_2-------------->
        <section class="story">
            <div class="container">
                <h2 class="story-topic">История создания и развития</h2>
                <div class="story-top">
                    <div class="story-top-element">
                        <img src="img/Лого.svg" alt="Logo">
                    </div>
                    <div class="story-top-element">
                        <p class="story-text">
                            Авиакомпания SkyLink была основана в 2005 году группой энтузиастов авиации с целью предоставления высококачественных и доступных услуг пассажирской авиации. 
                        </p>
                    </div>
                </div>
                <div class="story-bottom">
                    <p class="story-text">
                        С самого начала компания стремилась создать уникальный опыт для путешественников, предлагая широкий выбор маршрутов, комфортное обслуживание на борту и инновационные технологии. Благодаря своей постоянной стратегии развития и внедрения новых сервисов, SkyLink стала одним из лидеров в сфере авиаперевозок, обеспечивая безопасные и комфортные полеты для своих клиентов по всему миру.
                    </p>
                </div>
            </div>
        </section>
        <!--------------Section_3-------------->
        <section class="advantages">
            <div class="container">
                <h2 class="advantages-topic">
                    Наши преимущества
                </h2>
                <div class="advantages-content">
                    <div class="advantages-content-element">
                        <img src="img/Индивидуально.svg" alt="Индивидуально">
                        <h3 class="advantages-content-element-text">Индивидуальный подход</h3>
                    </div>
                    <div class="advantages-content-element">
                        <img src="img/Маршруты.svg" alt="Маршруты">
                        <h3 class="advantages-content-element-text">Широкий выбор маршрутов</h3>
                    </div>
                    <div class="advantages-content-element">
                        <img src="img/Дешево.svg" alt="Дешево">
                        <h3 class="advantages-content-element-text">Доступные цены</h3>
                    </div>
                    <div class="advantages-content-element">
                        <img src="img/Инновации.svg" alt="Инновации">
                        <h3 class="advantages-content-element-text">Инновационные технологии</h3>
                    </div>
                </div>
            </div>
        </section>
        <!--------------Section_4-------------->
        <section class="plans">
            <div class="container">
                <h2 class="plans-topic">
                    Планы и задачи
                </h2>
                <div class="plans-content">
                    <div class="plans-content-element">
                        <div class="plans-content-element-icon">
                            <img src="img/Лидерство.svg" alt="Лидерство">
                        </div>
                        <div class="plans-content-element-text left">
                            <h3 class="plans-content-element-text-title">
                                Стратегия компании
                            </h3>
                            <p class="plans-content-element-text-info">SkyLink стремится к лидерству в авиаперевозках, обеспечивая высокий уровень комфорта и обслуживания пассажиров. Расширяем маршруты для удобства путешествий.
                            </p>
                        </div>
                    </div>
                    <div class="plans-content-element">
                        <div class="plans-content-element-text">
                            <h3 class="plans-content-element-text-title">
                                Расширение маршрутов и инновации
                            </h3>
                            <p class="plans-content-element-text-info">Открываем новые направления в Азии и Латинской Америке, такие как Токио, Сингапур и Рио-де-Жанейро. Внедряем инновации для улучшения комфорта полетов.
                            </p>
                        </div>
                        <div class="plans-content-element-icon">
                            <img src="img/Направления.svg" alt="Направления">
                        </div>
                    </div>
                    <div class="plans-content-element">
                        <div class="plans-content-element-icon">
                            <img src="img/Скидки.svg" alt="Скидки">
                        </div>
                        <div class="plans-content-element-text left">
                            <h3 class="plans-content-element-text-title">
                                Акции и предложения
                            </h3>
                            <p class="plans-content-element-text-info">Запускаем акции и партнерские программы. Следите за нашими новостями для получения выгодных предложений и скидок. Лучший сервис и качество от SkyLink!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="plans-btn"><a href="promotions.html" class="plans-btn-link">Акции</a></div>
            </div>
        </section>
        <!--------------Section_5-------------->
        <section class="popular">
            <div class="container">
                <h2 class="popular-topic">
                    Популярные рейсы
                </h2>
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
                <div class="popular-btn"><a href="flights.html" class="popular-btn-link">Все рейсы</a></div>
            </div>
        </section>
        <!--------------Section_6-------------->
        <section class="rules">
            <div class="container">
                <h2 class="rules-topic">
                    Правила перевозки
                </h2>
                <div class="rules-top">
                    <div class="rules-top-element">
                        <p class="rules-top-element-text">
                            В соответствии с правилами авиакомпании, каждому пассажиру разрешается провести определенное количество и размер ручной клади бесплатно. 
                        </p>
                        <p class="rules-top-element-text">
                            Размер и вес ручной клади определяются авиаперевозчиком и могут различаться в зависимости от класса билета и направления полета. 
                        </p>
                        <p class="rules-top-element-text">
                            Мы рекомендуем заранее ознакомиться с правилами перевозки ручной клади для вашего удобства и комфорта во время путешествия. 
                        </p>
                    </div>
                    <div class="rules-top-element">
                        <img src="img/Багаж.svg" alt="Багаж">
                    </div>
                </div>
                <p class="rules-bottom">
                    Желаете узнать подробную информацию о правилах перевозки? Нажмите на кнопку ниже
                </p>
                <div class="rules-btn">
                    <a href="information.html" class="rules-btn-link">Подробнее</a>
                </div>
            </div>
        </section>
        <!--------------Section_7-------------->
        <section class="contact">
            <div class="container">
                <h2 class="contact-topic">
                    Связь с нами
                </h2>
                <div class="contact-top">
                    <div class="contact-top-element">
                        <img src="img/Поддержка.svg" alt="Поддержка">
                    </div>
                    <div class="contact-top-element">
                        <p class="contact-top-element-text">
                            Для оперативной поддержки и решения вопросов вы всегда можете обратиться к нашей службе поддержки.
                        </p>
                        <p class="contact-top-element-text">
                            Мы рады помочь вам с любыми вопросами и предложениями.
                        </p>
                    </div>
                </div>
                <p class="contact-bottom">
                    Хотите задать вопрос и связаться с нами? 
                    Нажмите на кнопку ниже
                </p>
                <div class="contact-btn">
                    <a href="contacts.html" class="contact-btn-link">Контакты</a>
                </div>
            </div>
        </section>
        <!--------------Section_8-------------->
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
                            <li class="footer-content-list-item"><a href="flights.html" class="footer-content-list-item-link">Рейсы</a></li>
                            <li class="footer-content-list-item"><a href="promotions.html" class="footer-content-list-item-link">Акции</a></li>
                            <li class="footer-content-list-item"><a href="information.html" class="footer-content-list-item-link">Информация</a></li>
                            <li class="footer-content-list-item"><a href="contacts.html" class="footer-content-list-item-link">Контакты</a></li>
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
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script src="js/script.js"></script>
    </body>
</html>