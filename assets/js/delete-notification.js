$('.delete').on('click', function () {
    $('.notification').fadeOut("slow", function () {
        $('.notification-container').remove();
    });
});
