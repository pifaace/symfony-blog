var file = $('.coverage-file');

$('.coverage-input').on('change', function () {
    $('.file-name').text(file[0].files[0].name);
    file.blur();
});

