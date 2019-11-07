let bell = $('#bell');
let bellContainer = $('.bell-container');
let updateNotificationRoute = bell.data('update');

bell.click(function () {
    if (bell.hasClass('bell-active')) {
        hideBellNotification()
    } else {
        showBellNotification()
    }

    bell.addClass('notification-read');

    console.log(bell.data('notification-count'));

    if (bell.data('notification-count') > 0) {
        $.ajax({
            url: updateNotificationRoute,
            type: 'POST',
            error: function(xhr) {
                let err = eval("(" + xhr.responseText + ")");
                alert(err.Message);
            }
        });

        bell.data('notification-count', 0)
    }
});

$(document).click(function(){
    hideBellNotification()
});

bellContainer.click(function(e){
    e.stopPropagation();
});

bell.click(function(e){
    e.stopPropagation();
});

function hideBellNotification() {
    bellContainer.removeClass('bell-open');
    bell.removeClass('bell-active');
}

function showBellNotification() {
    bellContainer.addClass('bell-open');
    bell.addClass('bell-active');
}
