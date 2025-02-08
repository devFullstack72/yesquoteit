jQuery(document).ready(function($) {
    $("#leadWizardForm").steps({
        headerTag: "h3",
        bodyTag: "div",
        transitionEffect: "slideLeft",
        autoFocus: true,
        enableCancelButton: false,
        onFinished: function (event, currentIndex) {
            alert("Form submitted!"); // Redirect or process form
        }
    });

    // Scroll to Form When Clicked
    $("#scroll-to-content-btn").on("click", function(event) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: $("#lead-generation-wizard").offset().top
        }, 800);
    });
});
