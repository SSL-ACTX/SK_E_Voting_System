function createNotification1(message) {
    const notification = document.createElement('div');
    notification.className = `bg-white rounded-xl shadow-lg overflow-hidden animate-slidein transition-all duration-500`;

    notification.innerHTML = `
    <div class="flex items-center space-x-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
    <img class="h-12 w-12" src="https://icon-library.com/images/warning-icon-svg/warning-icon-svg-11.jpg" alt="Logo">
    <div>
        <div class="text-xl font-medium text-red-800">System Alert</div>
        <p>${message}</p>
    </div>
    </div>
    `;

    const notificationContainer = document.getElementById('notificationContainer');
    notificationContainer.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function () {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(function (input) {
        input.addEventListener('input', function (event) {
            const password = event.target.value;
            if (password.length < 6) {
                event.target.setCustomValidity('Password must be at least 6 characters long.');
            } else {
                event.target.setCustomValidity('');
            }
            const illegalCharacters = ['<', '>', '&', '"', '\''];
            for (let char of illegalCharacters) {
                if (password.includes(char)) {
                    event.target.value = password.replace(new RegExp(char, 'g'), '');
                    createNotification1("Warning: You're using illegal characters!");
                    return;
                }
            }
        });
    });

    const allInputs = document.querySelectorAll('input[type="text"], input[type="email"]');
    allInputs.forEach(function (input) {
        input.addEventListener('input', function (event) {
            const inputValue = event.target.value;
            const illegalCharacters = ['<', '>', '&', '"', '\''];
            for (let char of illegalCharacters) {
                if (inputValue.includes(char)) {
                    event.target.value = inputValue.replace(new RegExp(char, 'g'), '');
                    createNotification1("Warning: You're using illegal characters!");
                    return;
                }
            }
        });
    });

    const form = document.querySelector('form');
    form.addEventListener('submit', function (event) {
        const allInputs = document.querySelectorAll('input[type="text"], input[type="email"]');
        allInputs.forEach(function (input) {
            const inputValue = input.value;
            if (inputValue.includes('<script>') || inputValue.includes('<?php') || inputValue.includes('script')) {
                event.preventDefault();
                createNotification1("Warning: Server-side scripting isn't allowed!");
                return;
            }
        });
    });
});
