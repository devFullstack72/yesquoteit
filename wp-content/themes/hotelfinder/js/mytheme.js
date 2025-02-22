var $ = jQuery;

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('wpcf7submit', function() {
        document.querySelector('.htlfndr-loader-overlay').style.display = 'none'; // Hide loader after submission
    });

    document.addEventListener('wpcf7submit', function(event) {
        if (event.detail.status === 'validation_failed' || event.detail.status === 'spam') {
            document.querySelector('.htlfndr-loader-overlay').style.display = 'none'; // Hide loader if form fails
        }
    });

    document.addEventListener('wpcf7submit', function(event) {
        if (event.detail.status === 'mail_sent') {
            document.querySelector('.htlfndr-loader-overlay').style.display = 'none'; // Hide on success
        }
    });

    document.querySelectorAll('.wpcf7-form').forEach(function(form) {
        form.addEventListener('submit', function() {
            document.querySelector('.htlfndr-loader-overlay').style.display = 'inline-flex'; // Show loader on submit
        });
    });
});