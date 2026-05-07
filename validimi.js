console.log('validimi.js loaded');
const usernameRe = /^[^\s]{3,20}$/;
const passwordRe = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function setText(id, text) {
    const el = document.getElementById(id);
    if (el) {
        el.textContent = text;
    }
}

function validateLoginForm() {
    const form = document.getElementById('loginForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const username = document.getElementById('username');
        const password = document.getElementById('password');

        let valid = true;
        setText('usernameError', '');
        setText('passwordError', '');

        if (!usernameRe.test(username.value.trim())) {
            setText('usernameError', 'Username i pavlefshëm (3–20 karaktere).');
            valid = false;
        }

        if (!passwordRe.test(password.value)) {
            setText('passwordError', 'Password duhet min 8 karaktere, 1 uppercase, 1 numër, 1 simbol.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

function validateRegisterForm() {
    const form = document.getElementById('registerForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        console.log('register form submit event');
        const submitStatus = document.getElementById('submitStatus');
        if (submitStatus) {
            submitStatus.textContent = 'Submit event fired.';
        }

        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');

        let valid = true;
        setText('usernameError', '');
        setText('emailError', '');
        setText('passwordError', '');
        setText('confirmPasswordError', '');

        if (!usernameRe.test(username.value.trim())) {
            setText('usernameError', 'Username must be 3-20 characters with no spaces.');
            valid = false;
        }
        if (!emailRe.test(email.value.trim())) {
            setText('emailError', 'Email is invalid.');
            valid = false;
        }
        if (!passwordRe.test(password.value)) {
            setText('passwordError', 'Password duhet min 8 karaktere, 1 uppercase, 1 numër, 1 simbol.');
            valid = false;
        }
        if (password.value !== confirmPassword.value) {
            setText('confirmPasswordError', 'Passwords do not match.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

function validateContactForm() {
    const form = document.getElementById('contactForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const message = document.getElementById('message');

        let valid = true;
        setText('nameError', '');
        setText('emailError', '');
        setText('messageError', '');

        if (name.value.trim() === '') {
            setText('nameError', 'Name is required.');
            valid = false;
        }
        if (!emailRe.test(email.value.trim())) {
            setText('emailError', 'Email i pavlefshëm.');
            valid = false;
        }
        if (message.value.trim().length < 10) {
            setText('messageError', 'Message must be at least 10 characters.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

function validateActivateForm() {
    const form = document.getElementById('activateForm');
    if (!form) return;

    const nameRe = /^[A-Za-z]{2,30}$/;
    const personalRe = /^\d{8,15}$/;
    const phoneRe = /^\+?\d{8,15}$/;

    form.addEventListener('submit', function (e) {
        const name = document.getElementById('name');
        const surname = document.getElementById('surname');
        const personal = document.getElementById('personal');
        const phone = document.getElementById('phone');
        const email = document.getElementById('email');

        setText('nameError', '');
        setText('surnameError', '');
        setText('personalError', '');
        setText('phoneError', '');
        setText('emailError', '');
        setText('formSuccess', '');

        let valid = true;
        if (!nameRe.test(name.value.trim())) {
            setText('nameError', 'Name i pavlefshëm.');
            valid = false;
        }
        if (!nameRe.test(surname.value.trim())) {
            setText('surnameError', 'Surname i pavlefshëm.');
            valid = false;
        }
        if (!personalRe.test(personal.value.trim())) {
            setText('personalError', 'Personal number i pavlefshëm.');
            valid = false;
        }
        if (phone.value.trim() !== '' && !phoneRe.test(phone.value.trim())) {
            setText('phoneError', 'Phone number i pavlefshëm.');
            valid = false;
        }
        if (!emailRe.test(email.value.trim())) {
            setText('emailError', 'Email i pavlefshëm.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

function initSlider() {
    const slider = document.querySelector('.slider');
    if (!slider) return;

    const slides = slider.querySelectorAll('img');
    if (slides.length <= 1) return;

    let currentIndex = 0;
    const interval = 5000;
    let timer = null;

    const scrollToIndex = index => {
        currentIndex = (index + slides.length) % slides.length;
        slides[currentIndex].scrollIntoView({ behavior: 'smooth', inline: 'start' });
    };

    const startTimer = () => {
        timer = setInterval(() => scrollToIndex(currentIndex + 1), interval);
    };

    const stopTimer = () => {
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
    };

    slider.addEventListener('mouseenter', stopTimer);
    slider.addEventListener('mouseleave', startTimer);

    const navLinks = document.querySelectorAll('.slider-nav a');
    navLinks.forEach((link, index) => {
        link.addEventListener('click', e => {
            e.preventDefault();
            stopTimer();
            scrollToIndex(index);
        });
    });

    startTimer();
}

function initValidation() {
    console.log('validimi initValidation');
    const jsStatus = document.getElementById('jsStatus');
    if (jsStatus) {
        jsStatus.textContent = 'JS validation loaded.';
    }
    validateLoginForm();
    validateRegisterForm();
    validateContactForm();
    validateActivateForm();
    initSlider();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initValidation);
} else {
    initValidation();
}
