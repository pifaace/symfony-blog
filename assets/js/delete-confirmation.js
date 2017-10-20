$('.delete-article').on('click', function() {
    var message = "Voulez vous vraiment supprimer cette annonce ?";
    return confirm(message);
});

$('.delete-image').on('click', function() {
    var message = "Voulez vous vraiment supprimer cette image ?";
    if (confirm(message)) {
        $('.delete-img-confirm').prop('checked', true);
        $(this).parents("form").submit();
    }
});
