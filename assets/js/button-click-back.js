$('#login-form').on('submit', function () {
$('button:submit.button').prop("disabled", true).html("Connexion en cours...");
});

$('#registration-form').on('submit', function () {
    $('button:submit.button').prop("disabled", true).html("Inscription en cours...");
});
