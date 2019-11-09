import $ from 'jquery';

$('#login-form').on('submit', function () {
    var translation = $('button:submit.button').data('trans');
    $('button:submit.button').prop("disabled", true).html(translation);
});

$('#registration-form').on('submit', function () {
    var translation = $('button:submit.button').data('trans');
    $('button:submit.button').prop("disabled", true).html(translation);
});

$('#password-reset-request-form').on('submit', function () {
    var translation = $('button:submit.button').data('trans');
    $('button:submit.button').prop("disabled", true).html(translation);
});

$('#password-reset-new-form').on('submit', function () {
    var translation = $('button:submit.button').data('trans');
    $('button:submit.button').prop("disabled", true).html(translation);
});

$('#comment-form').on('submit', function () {
    $('button:submit.button').prop("disabled", true);
});
