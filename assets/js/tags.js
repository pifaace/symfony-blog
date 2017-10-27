var tagInput = $('.tag-input');
var tagArray = [];
var tagList = $('.hidden-tag-input').val();

tagInput.focus(function () {
    $(this).parent().addClass("input-focus");
});

tagInput.focusout(function () {
    $(this).parent().removeClass("input-focus");
});

if (tagList !== undefined) {
    tagArray = tagList.split(',');
}

tagInput.on('keyup', function () {
    var tag = tagInput.val();
    if (/^[,,.]*$/.test(tag)) {
        $(this).val("");
    } else if (tag.indexOf(',') !== -1) {
        var word = tag.replace(",", "");
        tagArray.push(word);
        $('.hidden-tag-input').val(tagArray);
        tagInput.before("<div class='tag'>" + word + "<i class='fa fa-times tag-remove' aria-hidden='true'></i></div>");

        $(this).val("");
    }
});

$('.tags').on('click', '.tag-remove', function () {
    var tag = $(this).parent();
    var word = tag.text();
    tagArray.splice($.inArray(word, tagArray), 1);

    $('.hidden-tag-input').val(tagArray);

    tag.remove();
});
