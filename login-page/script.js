document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const statusAlert = document.getElementById('status-alert');

    // Input fields
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');

    // Error message elements
    const usernameError = document.getElementById('username-error');
    const passwordError = document.getElementById('password-error');

    const showAlert = (message, type) => {
        statusAlert.textContent = message;
        statusAlert.className = `show ${type}`;
        
        setTimeout(() => {
            statusAlert.classList.remove('show');
        }, 4000);
    };

    const validateForm = () => {
        let isValid = true;

        // Username validation
        if (usernameInput.value.trim() === '') {
            usernameError.textContent = 'Username is required';
            usernameError.classList.add('visible');
            isValid = false;
        } else if (usernameInput.value.length < 3) {
            usernameError.textContent = 'Too short (min 3 chars)';
            usernameError.classList.add('visible');
            isValid = false;
        } else {
            usernameError.classList.remove('visible');
        }

        // Password validation
        if (passwordInput.value === '') {
            passwordError.textContent = 'Password is required';
            passwordError.classList.add('visible');
            isValid = false;
        } else if (passwordInput.value.length < 6) {
            passwordError.textContent = 'Too short (min 6 chars)';
            passwordError.classList.add('visible');
            isValid = false;
        } else {
            passwordError.classList.remove('visible');
        }

        return isValid;
    };

    // Real-time validation on input
    [usernameInput, passwordInput].forEach(input => {
        input.addEventListener('input', () => {
            const errorElement = document.getElementById(`${input.id}-error`);
            if (input.value.trim() !== '') {
                errorElement.classList.remove('visible');
            }
        });
    });

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!validateForm()) return;

        // Disable button and show loading state
        submitBtn.disabled = true;
        const originalBtnText = submitBtn.textContent;
        submitBtn.textContent = 'Authenticating...';

        try {
            const formData = new FormData(loginForm);
            const response = await fetch('auth.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showAlert(data.message, 'success');
                // Redirect on success
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showAlert(data.message, 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
        } catch (error) {
            showAlert('A connection error occurred.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
        }
    });
});
