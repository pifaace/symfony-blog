let bell = $('#bell');
let bellContainer = $('.bell-container');

bell.click(function () {
   if (bell.hasClass('bell-active')) {
      hideBellNotification()
   } else {
       showBellNotification()
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
