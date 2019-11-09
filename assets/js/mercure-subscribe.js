import $ from 'jquery';

const url = new URL('http://localhost:3000/hub')
url.searchParams.append('topic', 'http://symfony-blog.fr/new/article')

const eventSource = new EventSource(url, {withCredentials: true})

// The callback will be called every time an update is published
eventSource.onmessage = e => new newNotification(e)

function newNotification(event) {
    const bell = $('#bell')
    const notificationCenter = $('#notification-center')
    const notification = JSON.parse(event.data)
    const url = new URL(notification.targetLink, window.location.href).href

    const notificationHtml = `
      <a href="${url}" class="no-link-color">
        <div class="bell-notification-item pb-10 pt-10 pr-20 pl-15">
            <i class="fa fa-check-circle bell-notification-icon" aria-hidden="true"></i>
            <span class="bell-notification-content ml-20">
            <b>${notification.createdBy.username}</b> ${translations.article_created}
            </span>
        </div>
      </a>
    `


    if (notificationCenter.hasClass('bell-empty-content')) {
        notificationCenter.removeClass('bell-empty-content').addClass('bell-content')
        notificationCenter.parent().find('span').remove()
    }

    $('.bell-content').append(notificationHtml)

    animateBell()

    bell.removeClass('notification-read')
    let count = bell.attr('data-notification-count')
    count++
    bell.attr('data-notification-count', count)
}

function animateBell() {
    $('.fa').addClass('ding').delay(1000).queue(function (next) {
        $(this).removeClass('ding')
        next()
    })
}
