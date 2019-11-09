import $ from 'jquery';

export default class {
  constructor (bell) {
    this.bell = bell
    this.bellContainer = $('.bell-container')
    this.updateNotificationRoute = bell.data('update')
    this.active = false
    this.unreadCount = this.bell.data('notification-count')

    this.mounted()
  }

  mounted () {
    $(document).click(() => this.hide())
    this.bellContainer.click(e => e.stopPropagation())
    this.bell.click(e => e.stopPropagation())

    this.bell.click(() => {
      this.toggleVisibility()
      this.update()
    })
  }

  isActive () {
    return this.active
  }

  toggleVisibility () {
    if (this.isActive()) {
      return this.hide()
    }

    return this.show()
  }

  hide () {
    this.bellContainer.removeClass('bell-open')
    this.bell.removeClass('bell-active')
    this.active = false
  }

  show () {
    this.bellContainer.addClass('bell-open')
    this.bell.addClass('bell-active')
    this.active = true
  }

  update () {
    if (this.unreadCount <= 0) {
      return
    }

    $.ajax({
      url: this.updateNotificationRoute,
      type: 'POST',
      done () {
        this.bell.data('notification-count', 0).addClass('notification-read')
      },
      error () {
        alert(err.Message)
      }
    })
  }
}
