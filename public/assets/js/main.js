// Main JavaScript File
document.addEventListener('DOMContentLoaded', function() {
    console.log('QA Master Blog - Main JS Loaded');

    // Example of simple interactivity: Fade out flash messages after 3 seconds
    const alerts = document.querySelectorAll('.alert-success, .alert-danger');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500); // Remove after fade transition
            });
        }, 3000);
    }
});
