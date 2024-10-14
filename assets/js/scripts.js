// assets/js/scripts.js

// Theme toggle functionality
document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.createElement('button');
    toggleButton.textContent = 'Toggle Dark/Light Mode';
    toggleButton.classList.add('theme-toggle');
    document.body.appendChild(toggleButton);

    // Check for saved user preference
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme) {
        document.body.classList.toggle('dark-mode', currentTheme === 'dark');
    }

    toggleButton.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        // Save user preference
        const theme = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
        localStorage.setItem('theme', theme);
    });
});
