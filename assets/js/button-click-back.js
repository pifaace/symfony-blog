$('#login-form').on('submit', function () {
    $('button:submit.button').prop("disabled", true).html("Connexion en cours...");
});

$('#registration-form').on('submit', function () {
    $('button:submit.button').prop("disabled", true).html("Inscription en cours...");
});

$('#password-reset-request-form').on('submit', function () {
    $('button:submit.button').prop("disabled", true).html("Envoie de l'email...");
});

$('#password-reset-new-form').on('submit', function () {
    $('button:submit.button').prop("disabled", true).html("Changement en cours...");
});

$('#comment-form').on('submit', function () {
    $('button:submit.button').prop("disabled", true);
});
