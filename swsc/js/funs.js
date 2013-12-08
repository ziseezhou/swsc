(function($) {
    $.f = {};

    $.f.validateEmail  = function($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test($email);
    };

    $.f.isIE6 = function() {
         return ((window.XMLHttpRequest == undefined) && (ActiveXObject != undefined));
    };

    $.f.showDeleteConfirm = function(elem, btnTitle, _id, action) {
        var floatId = 'float_id_delete_confirm';
        var view = $('<div class="btn_red">'+btnTitle+'</div>');

        var dialog = $.f.floatDialogGet(floatId);
        $.f.floatDialogInflate(dialog, view);

        var btnDelete = dialog.find('.btn_red');
        btnDelete
            .plbtn({cssNormal:'btn_red_normal', cssHover:'btn_red_hover'})
            .plbtn('addIcon', 'img/icon/trash.png')
            .click(function(e){
                action(_id);
                $(document).trigger('mouseup', e);
            });

        var options ={borderSize: 2, borderColor:'#C70E0C', backColor: '#C70E0C', gravity: 'w'};
        $.f.floatDialogAssemble(elem, dialog, options);
    };

    $.f.floatDialogGet = function(floatId) {
        if (!$('body').data(floatId)) {
            $('body').data(floatId, true);
            $('<div id="'+floatId+'" class="z-float-container"></div>')
                .html('<div class="z-float-arrow" ></div><div class="z-float-arrow2" ></div><div class="z-float-inner"></div>')
                .css({top: 0, left: 0, visibility: 'hidden', display: 'block'})
                .prependTo(document.body);
        }

        return $('body #'+floatId);
    };

    $.f.floatDialogInflate = function(dialog, view) {
        dialog.find('.z-float-inner').html(view);
    };

    $.f.floatDialogAssemble = function(elem, $dialog, options) {
        var optionsDefault = {
            borderSize: 1,
            borderColor: '#CCC',
            backColor: 'white',
            gravity: 'w'
        };

        options = $.extend({}, optionsDefault, options);
        $dialog.className = 'z-float-container';

        var $elem = $(elem);
        var gravity = options.gravity;
        var tp, borderFlag;

        var onResize = function() {
            var pos = $.extend({}, $elem.offset(), {
                width: $elem[0].offsetWidth,
                height: $elem[0].offsetHeight
            });

            var actualWidth = $dialog[0].offsetWidth,
                actualHeight = $dialog[0].offsetHeight;

            switch (gravity.charAt(0)) {
                case 'n':
                    tp = {top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2};
                    borderFlag = 'border-bottom-color';
                    break;
                case 's':
                    tp = {top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2};
                    borderFlag = 'border-top-color';
                    break;
                case 'e':
                    tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth}; // - this.options.offset};
                    borderFlag = 'border-left-color';
                    break;
                case 'w':
                    tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width}; // + this.options.offset};
                    borderFlag = 'border-right-color';
                    break;
            }
            
            if (gravity.length == 2) {
                if (gravity.charAt(1) == 'w') {
                    tp.left = pos.left + pos.width / 2 - 15;
                } else {
                    tp.left = pos.left + pos.width / 2 - actualWidth + 15;
                }
            }

            $dialog.css(tp);
        };

        $(window).resize(onResize);
        onResize();
        
        $dialog.addClass('z-float-container-' + gravity);
        $dialog.find('.z-float-arrow')[0].className = 'z-float-arrow z-float-arrow-' + gravity.charAt(0);

        if (optionsDefault.backColor != options.backColor) {
            $dialog.find('.z-float-inner').css('background-color', options.backColor);
            $dialog.find('.z-float-arrow2').css('border-color', 'transparent');
            $dialog.find('.z-float-arrow2').css(borderFlag, options.backColor);
        }

        if (optionsDefault.borderColor != options.borderColor) {
            $dialog.find('.z-float-inner').css('border-color', options.borderColor);
            $dialog.find('.z-float-arrow').css('border-color', 'transparent');
            $dialog.find('.z-float-arrow').css(borderFlag, options.borderColor);
        }

        if (options.borderSize != 1) {
            $dialog.find('.z-float-inner').css('borderWidth', options.borderSize+'px');

            if (options.borderSize <= 5) {
                var newOffsetValue = (options.borderSize+1) + 'px';
                var arrow2 = $dialog.find('.z-float-arrow2');
                var x = arrow2.css('left');

                if (arrow2.css('left') == '2px') arrow2.css('left', newOffsetValue);
                else if (arrow2.css('right') == '2px') arrow2.css('right', newOffsetValue);
                else if (arrow2.css('top') == '2px')  arrow2.css('top', newOffsetValue);
                else if (arrow2.css('bottom') == '2px') arrow2.css('bottom', newOffsetValue);
            } else {
                $dialog.find('.z-float-arrow2').css({visibility: 'hidden'});
            }
        }

        var funClear = function(e) {
            var target = e.target;

            if(!$elem.is(target) && !$dialog.is(target) && $dialog.has(target).length === 0 && $dialog.is(':visible')) {
                $dialog.css({visibility: 'hidden'});
                $(document).off('mouseup', funClear);
            }
        };

        $(document).on('mouseup', funClear);
        $dialog.css({visibility: 'visible'});
    };


})(jQuery);
