const nameRe = /^[A-Za-z]{2,30}$/;
const personalRe = /^\d{8,15}$/;
const phoneRe = /^\+?\d{8,15}$/;
const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const form = document.getElementById('activateForm');

const name = document.getElementById('name');
const surname = document.getElementById('surname');
const personal = document.getElementById('personal');
const phone = document.getElementById('phone');
const email = document.getElementById('email');

const nameError = document.getElementById('nameError');
const surnameError = document.getElementById('surnameError');
const personalError = document.getElementById('personalError');
const phoneError = document.getElementById('phoneError');
const emailError = document.getElementById('emailError');
const formSuccess = document.getElementById('formSuccess');

function clearErrors() {
    [nameError, surnameError, personalError, phoneError, emailError, formSuccess]
        .forEach(el => el.textContent = '');
}

form.addEventListener('submit', e => {
    e.preventDefault();
    clearErrors();

    let valid = true;

    if (!nameRe.test(name.value.trim())) {
        nameError.textContent = 'Name i pavlefshëm.';
        valid = false;
    }

    if (!nameRe.test(surname.value.trim())) {
        surnameError.textContent = 'Surname i pavlefshëm.';
        valid = false;
    }

    if (!personalRe.test(personal.value.trim())) {
        personalError.textContent = 'Personal number i pavlefshëm.';
        valid = false;
    }

    if (phone.value.trim() !== '' && !phoneRe.test(phone.value.trim())) {
        phoneError.textContent = 'Phone number i pavlefshëm.';
        valid = false;
    }

    if (!emailRe.test(email.value.trim())) {
        emailError.textContent = 'Email i pavlefshëm.';
        valid = false;
    }

    if (valid) {
        formSuccess.textContent = 'Account activated successfully (demo).';
        form.reset();
    }
});
