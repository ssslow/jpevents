jQuery(document).ready(function($) {
    $('.event-categories a').on('click', function(e) {
        e.preventDefault();
        var categorySlug = $(this).attr('href').split('=')[1];

        $('.event').each(function() {
            var categories = $(this).data('category');
            if (categories) {
                categories = categories.split(',');
                if (categories.includes(categorySlug)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else {
                $(this).hide();
            }
        });
    });

    $('#all-events').on('click', function(e) {
        e.preventDefault();

        $('.event').show();
    });

    $('.event-categories a').on('click', function() {
        $('.event-categories a').removeClass('active');
        $(this).addClass('active');
    });
});
