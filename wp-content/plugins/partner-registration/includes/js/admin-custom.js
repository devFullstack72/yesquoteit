jQuery(document).ready(function($) {
    $('#service-providers-list .toggle-address').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $parent = $this.closest('td');

        $parent.find('.short-address').toggle();
        $parent.find('.full-address').toggle();

        if ($this.text() === "More") {
            $this.text("Less");
        } else {
            $this.text("More");
        }
    });

    $('#service-providers-list .lead-toggle').on('click', function() {
        var $details = $(this).next('.lead-details');
        var $icon = $(this).find('i');

        $details.slideToggle(); // Toggle visibility
        $icon.toggleClass('dashicons-arrow-down dashicons-arrow-up'); // Toggle chevron icon
    });
});