const usernameRe = /^[a-zA-Z0-9._-]{3,20}$/;
const passwordRe = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

const form = document.getElementById('registerForm');
const username = document.getElementById('username');
const password = document.getElementById('password');

const usernameError = document.getElementById('usernameError');
const passwordError = document.getElementById('passwordError');
const formSuccess = document.getElementById('formSuccess');

function clearErrors() {
    usernameError.textContent = '';
    passwordError.textContent = '';
    formSuccess.textContent = '';
}

form.addEventListener('submit', function (e) {
    e.preventDefault();
    clearErrors();

    let valid = true;

    if (!usernameRe.test(username.value.trim())) {
        usernameError.textContent =
            'Username i pavlefshëm (3–20 karaktere).';
        valid = false;
    }

    if (!passwordRe.test(password.value)) {
        passwordError.textContent =
            'Password duhet min 8 karaktere, 1 uppercase, 1 numër, 1 simbol.';
        valid = false;
    }

    if (valid) {
        formSuccess.textContent = 'Login i suksesshëm (demo)';
        form.reset();
    }
});
