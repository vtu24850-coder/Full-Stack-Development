/**
 * REUSABLE VALIDATION LOGIC
 */

const validators = {
    isValidName: (name) => name.trim().length >= 3,
    
    isValidEmail: (email) => {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    },
    
    isNotEmpty: (text) => text.trim().length > 0
};

/**
 * UI UPDATE FUNCTIONS
 */

const updateUI = (elementId, isValid) => {
    const group = document.getElementById(elementId);
    if (isValid) {
        group.classList.remove('invalid');
        group.classList.add('valid');
    } else {
        group.classList.remove('valid');
        group.classList.add('invalid');
    }
};

/**
 * EVENT HANDLERS
 */

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('feedbackForm');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const messageInput = document.getElementById('message');
    const submitBtn = document.getElementById('submitBtn');
    const modal = document.getElementById('confirmationModal');
    const modalClose = document.getElementById('modalClose');

    // 1. Validate inputs on keypress (keyup for real-time)
    nameInput.addEventListener('keyup', () => {
        updateUI('nameGroup', validators.isValidName(nameInput.value));
    });

    emailInput.addEventListener('keyup', () => {
        updateUI('emailGroup', validators.isValidEmail(emailInput.value));
    });

    messageInput.addEventListener('keyup', () => {
        updateUI('messageGroup', validators.isNotEmpty(messageInput.value));
    });

    // 2. Highlight fields on mouse hover
    const inputs = [nameInput, emailInput, messageInput];
    inputs.forEach(input => {
        input.addEventListener('mouseenter', () => {
            input.classList.add('field-highlight');
        });
        input.addEventListener('mouseleave', () => {
            input.classList.remove('field-highlight');
        });
    });

    // 3. Show confirmation on double-click submit
    // We prevent default on single click and only act on double click
    form.addEventListener('submit', (e) => {
        e.preventDefault();
    });

    submitBtn.addEventListener('dblclick', (e) => {
        e.preventDefault();
        
        // Final validation check
        const isNameValid = validators.isValidName(nameInput.value);
        const isEmailValid = validators.isValidEmail(emailInput.value);
        const isMessageValid = validators.isNotEmpty(messageInput.value);

        if (isNameValid && isEmailValid && isMessageValid) {
            showModal();
            form.reset();
            // Clear validation classes
            ['nameGroup', 'emailGroup', 'messageGroup'].forEach(id => {
                document.getElementById(id).classList.remove('valid', 'invalid');
            });
        } else {
            // Trigger validation UI for all fields if invalid
            updateUI('nameGroup', isNameValid);
            updateUI('emailGroup', isEmailValid);
            updateUI('messageGroup', isMessageValid);
            
            // Visual feedback for failed submission
            submitBtn.classList.add('shake');
            setTimeout(() => submitBtn.classList.remove('shake'), 500);
        }
    });

    // Modal logic
    const showModal = () => {
        modal.classList.add('show');
    };

    const hideModal = () => {
        modal.classList.remove('show');
    };

    modalClose.addEventListener('click', hideModal);
    
    // Close modal on background click
    window.addEventListener('click', (e) => {
        if (e.target === modal) hideModal();
    });
});
