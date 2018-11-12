$('.delete-article').on('click', function() {
    var message = $('.delete-article').data('trans');
    return confirm(message);
});

$('.delete-image').on('click', function() {
    var message = $('.delete-image').data('trans');
    if (confirm(message)) {
        $('.delete-img-confirm').prop('checked', true);
        $(this).parents("form").submit();
    }
});
