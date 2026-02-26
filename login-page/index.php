<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Login | Secure Access</title>
    <meta name="description" content="Secure login portal with real-time validation and modern aesthetic.">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="status-alert"></div>

    <div class="login-container">
        <div class="login-header">
            <h1>Nexus</h1>
            <p>Enter your credentials to access your account</p>
        </div>

        <form id="loginForm" novalidate>
            <div class="form-group">
                <label for="username">Username or Email</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" placeholder="e.g. admin" autocomplete="username" required>
                </div>
                <div class="error-msg" id="username-error"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                </div>
                <div class="error-msg" id="password-error"></div>
            </div>

            <button type="submit" class="login-btn" id="submitBtn">Login to Nexus</button>
        </form>

        <div class="footer-links">
            <p>Don't have an account? <a href="#">Create Account</a></p>
            <p style="margin-top: 10px;"><a href="#" style="font-weight: 400; color: var(--text-muted); font-size: 0.8rem;">Forgot password?</a></p>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
