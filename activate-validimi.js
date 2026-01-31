const nameRe = /^[A-Za-z]{2,30}$/;
const usernameRe = /^[a-zA-Z0-9._-]{3,20}$/;
const passwordRe = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
const personalRe = /^\d{8,15}$/;
const phoneRe = /^\+?\d{8,15}$/;
const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const form = document.getElementById('activateForm');

const name = document.getElementById('name');
const surname = document.getElementById('surname');
const personal = document.getElementById('personal');
const phone = document.getElementById('phone');
const email = document.getElementById('email');
const username = document.getElementById('username');
const password = document.getElementById('password');

const nameError = document.getElementById('nameError');
const surnameError = document.getElementById('surnameError');
const personalError = document.getElementById('personalError');
const phoneError = document.getElementById('phoneError');
const emailError = document.getElementById('emailError');
const usernameError = document.getElementById('usernameError');
const passwordError = document.getElementById('passwordError');
const formSuccess = document.getElementById('formSuccess');

function clearErrors() {
    [nameError, surnameError, personalError, phoneError, emailError, usernameError, passwordError, formSuccess]
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

    if (!usernameRe.test(username.value.trim())) {
        usernameError.textContent = 'Username i pavlefshëm (3–20 chars).';
        valid = false;
    }

    if (!passwordRe.test(password.value)) {
        passwordError.textContent = 'Password duhet min 8 karaktere, 1 uppercase, 1 numër, 1 simbol.';
        valid = false;
    }

    if (valid) {
        const data = new FormData();
        data.append('name', name.value.trim());
        data.append('surname', surname.value.trim());
        data.append('personal', personal.value.trim());
        data.append('phone', phone.value.trim());
        data.append('email', email.value.trim());
        data.append('username', username.value.trim());
        data.append('password', password.value);

        fetch('php/activate.php', { method: 'POST', body: data })
            .then(async r => {
                let resText;
                try {
                    resText = await r.text();
                    const parsed = JSON.parse(resText || '{}');
                    return { ok: r.ok, status: r.status, body: parsed };
                } catch (err) {
                    return { ok: r.ok, status: r.status, body: { success: false, error: 'Invalid JSON response', detail: resText } };
                }
            })
            .then(({ ok, status, body }) => {
                if (body.success) {
                    formSuccess.textContent = 'Account activated successfully.';
                    form.reset();
                } else {
                    formSuccess.textContent = '';
                    const msg = body.error || `Server error (${status})`;
                    const detail = body.detail ? ` — ${body.detail}` : '';
                    usernameError.textContent = msg + detail;
                    console.error('Activation failed:', msg, body.detail || 'no detail');
                }
            })
            .catch((err) => {
                formSuccess.textContent = '';
                usernameError.textContent = 'Network/server error. See console for details.';
                console.error('Fetch error:', err);
            });
    }
});
