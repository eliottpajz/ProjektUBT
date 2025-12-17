const usernameRe = /^[a-zA-Z0-9._-]{3,20}$/; 
const passwordRe = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/; 

const form = document.getElementById('registerForm');
const username = document.getElementById('username');
const password = document.getElementById('password');
const confirm = document.getElementById('confirm');

const usernameError = document.getElementById('usernameError');
const passwordError = document.getElementById('passwordError');
const confirmError = document.getElementById('confirmError');
const formSuccess = document.getElementById('formSuccess');

function clearErrors() {
            [usernameError, passwordError, confirmError, 
            formSuccess].forEach(el => el.textContent = '');
        }

 function validateField() {
            let valid = true;
            clearErrors();
            if (!usernameRe.test(username.value.trim())) {
                usernameError.textContent = 'Username i pavlefshëm. 3-20 karaktere; shkronja, numra, . _ - lejohet.';
                valid = false;
            }
            if (!passwordRe.test(password.value)) {
                passwordError.textContent = 'Fjalëkalimi duhet të ketë të paktën 8 karaktere, 1 shkronjë të madhe, 1 të vogël, 1 numër dhe 1 simbol.';
                valid = false;
            }
            if (password.value !== confirm.value) {
                confirmError.textContent = 'Fjalëkalimet nuk përputhen.';
                valid = false;
            }
             return valid;
        }

username.addEventListener('input', () => { if (usernameRe.test(username.value.trim())) 
            usernameError.textContent = ''; });
password.addEventListener('input', () => { if (passwordRe.test(password.value)) 
            passwordError.textContent = ''; });
confirm.addEventListener('input', () => { if (password.value === confirm.value) 
            confirmError.textContent = ''; });

             form.addEventListener('submit', (e) => {
            e.preventDefault();
            formSuccess.textContent = '';

            if (validateField()) {
                formSuccess.textContent = 'Regjistrimi i suksesshëm! (kjo është vetëm demo në klient)';
                form.reset();
            } else {
                formSuccess.textContent = '';
            }
        });
