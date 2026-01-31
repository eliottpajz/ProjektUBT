<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Banka</title>
    <link rel="stylesheet" href="style.css">
     
</head>
<body>
    <main class="auth-page">
        <section class="auth-card">
            <h1>Login</h1>
<form id="registerForm" novalidate>

    <div class="input-box">
        <label for="username">Username</label>
        <input id="username" name="username" type="text" required placeholder="min 3 karaktere">
        <div id="usernameError" class="error" aria-live="polite"></div>
    </div>

    <div class="input-box">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required placeholder="min 8 (1 uppercase, 1 digit, 1 special)">
        <div id="passwordError" class="error" aria-live="polite"></div>
    </div>

    <div class="input-box">
        <label>
            <input id="remember" name="remember" type="checkbox" value="1"> Mbaje mend (Remember me)
        </label>
    </div>

    <button type="submit" class="auth-btn">Login</button>
    <div id="formSuccess" class="success" aria-live="polite"></div>
</form>
           
            <div class="register-link">
                <p>
                    Don't have an account?
                    <a class="btn-link" href="activateaccount.php">Activate account</a>
                </p>
            </div>
            <div class="register-link">

               
            </div>
            <p>
                    <a class="btn-link" href="homepage.php">← Back to homepage</a>
                </p>
        </section>
    </main>
    <script src="validimi.js"></script>

</body>
</html>
