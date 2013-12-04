var localDowNames = [ '日', '一', '二', '三', '四', '五', '六' ];
var localMonthNames = [ '1月', '2月', '3月', '4月', '5月', '6月', '6月', '8月', '9月', '10月', '11月', '12月' ];

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test($email);
}

function showDeleteConfirm(elem, _id, action) {
    var $dialog;
    if ($('body #_delete_confirm_dialog').length == 0) {
        $('body').append('<div id="_delete_confirm_dialog" style="display:none">delete</div>');
    }

    $dialog = $('body #_delete_confirm_dialog');

    var $elem = $(elem);
    var pos = $.extend({}, $elem.offset(), {
        width: $elem.offsetWidth,
        height: $elem.offsetHeight
    });
    
    var actualWidth = $dialog.offsetWidth,
        actualHeight = $dialog.offsetHeight,
        gravity = 'e'; //maybeCall(this.options.gravity, this.$element[0]);
    
    var tp;
    switch (gravity.charAt(0)) {
        case 'n':
            tp = {top: pos.top + pos.height + this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
            break;
        case 's':
            tp = {top: pos.top - actualHeight - this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
            break;
        case 'e':
            tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth - this.options.offset};
            break;
        case 'w':
            tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width + this.options.offset};
            break;
    }
    
    if (gravity.length == 2) {
        if (gravity.charAt(1) == 'w') {
            tp.left = pos.left + pos.width / 2 - 15;
        } else {
            tp.left = pos.left + pos.width / 2 - actualWidth + 15;
        }
    }
    
    $dialog.css(tp); //.addClass('tipsy-' + gravity);
    //$tip.find('.tipsy-arrow')[0].className = 'tipsy-arrow tipsy-arrow-' + gravity.charAt(0);
    //if (this.options.className) {
    //    $tip.addClass(maybeCall(this.options.className, this.$element[0]));
    //}
    
    //if (this.options.fade) {
    //    $tip.stop().css({opacity: 0, display: 'block', visibility: 'visible'}).animate({opacity: this.options.opacity});
    //} else {
    //    $tip.css({visibility: 'visible', opacity: this.options.opacity});
    //}

    $(document).bind('mouseup', function(e) {
        var target = e.target;

        if(!el.is(target) && !$dialog.is(target) && $dialog.has(target).length === 0 && $dialog.is(':block')) {
            $dialog.css('display', 'none');
        }
    });

    $dialog.css('display', 'block');
}