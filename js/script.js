// <!--------------Slider-------------->

function validateRegistration() {
  let valid = true;

  // Поиск полей
  const login = document.getElementById('regLogin');
  const email = document.getElementById('regEmail');
  const phone = document.getElementById('regPhone');
  const password = document.getElementById('regPassword');
  const confirmPassword = document.getElementById('confirmPassword');

  // Очистка ошибок
  document.getElementById('loginError').innerText = '';
  document.getElementById('emailError').innerText = '';
  document.getElementById('phoneError').innerText = '';
  document.getElementById('passwordError').innerText = '';
  document.getElementById('confirmPasswordError').innerText = '';

  // Валидация логина
  if (login.value.trim() === "") {
    document.getElementById('loginError').innerText = 'Поле логина обязательно для заполнения.';
    valid = false;
  }

  // Валидация email
  if (!email.checkValidity()) {
    document.getElementById('emailError').innerText = 'Введите корректный email.';
    valid = false;
  }

  // // Валидация телефона с использованием регулярного выражения
  // const phoneRegex = /^\+\d \(\d{3}\) \d{3} \d{2} \d{2}$/;
  // const phoneValue = phone.value.trim();
  // console.log('Значение телефона:', phoneValue);
  //
  // if (!phoneRegex.test(phoneValue)) {
  //   document.getElementById('phoneError').innerText = 'Введите корректный номер телефона в формате +X (XXX) XXX XX XX.';
  //   valid = false;
  // }

  // Валидация пароля
  if (password.value.length < 6) {
    document.getElementById('passwordError').innerText = 'Пароль должен содержать минимум 6 символов.';
    valid = false;
  }

  // Валидация совпадения паролей
  if (password.value !== confirmPassword.value) {
    document.getElementById('confirmPasswordError').innerText = 'Пароли не совпадают.';
    valid = false;
  }

  return valid;
}

$(document).ready(function() {
  $("#regPhone").inputmask({
    mask: "+9 (999) 999-99-99", // Пример маски для телефона
    placeholder: " ",
    clearIncomplete: true
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const slides = document.querySelectorAll('.intro-slider-slide');
  const prevButton = document.querySelector('.intro-slider-controls-prev');
  const nextButton = document.querySelector('.intro-slider-controls-next');

  if (slides.length && prevButton && nextButton) {
    let currentSlide = 0;
    const totalSlides = slides.length;

    function showSlide(index) {
      slides.forEach(slide => slide.style.display = 'none');
      slides[index].style.display = 'block';
      currentSlide = index;
    }

    function nextSlide() {
      const nextIndex = (currentSlide + 1) % totalSlides;
      showSlide(nextIndex);
    }

    function prevSlide() {
      const prevIndex = (currentSlide - 1 + totalSlides) % totalSlides;
      showSlide(prevIndex);
    }

    prevButton.addEventListener('click', prevSlide);
    nextButton.addEventListener('click', nextSlide);

    setInterval(nextSlide, 5000);
  }

  // <!--------------popUps-------------->

  const enterLink = document.querySelector('.menu-link.enter');
  const regLink = document.querySelector('.menu-link.reg');

  if (enterLink) {
    enterLink.addEventListener('click', function() {
      document.getElementById('loginModal').style.display = 'block';
    });
  }

  if (regLink) {
    regLink.addEventListener('click', function() {
      document.getElementById('registerModal').style.display = 'block';
    });
  }

  document.querySelectorAll('.switchPopupButton').forEach(button => {
    button.addEventListener('click', function() {
      document.getElementById('loginModal').style.display = 'none';
      document.getElementById('registerModal').style.display = 'none';
      const popupId = this.getAttribute('id') === 'registerOpenButton' ? 'registerModal' : 'loginModal';
      document.getElementById(popupId).style.display = 'block';
    });
  });

  window.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      document.getElementById('loginModal').style.display = 'none';
      document.getElementById('registerModal').style.display = 'none';
    }
  });

  document.addEventListener('click', function(event) {
    if (event.target === document.getElementById('loginModal')) {
      document.getElementById('loginModal').style.display = 'none';
    }
    if (event.target === document.getElementById('registerModal')) {
      document.getElementById('registerModal').style.display = 'none';
    }
  });
});
