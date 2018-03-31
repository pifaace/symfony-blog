$('#login-form').on('submit', function () {
    $('button:submit.button').prop("disabled", true).html("Connexion en cours...");
});

$('#registration-form').on('submit', function () {
    $('button:submit.button').prop("disabled", true).html("Inscription en cours...");
});

$('#password-reset-form').on('submit', function () {
    $('button:submit.button').prop("disabled", true).html("Envoie de l'email...");
});
