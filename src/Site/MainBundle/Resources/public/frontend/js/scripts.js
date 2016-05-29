function detectmob() {
    if( navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)
    ){
        return true;
    }
    else {
        return false;
    }
}

var readMore = function(note_id, url) {
    var data = {
        media_id : note_id
    };

    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'html',
        data: data,
        success: function(data) {
            var $cur_note = $('#media' + note_id);
            $cur_note.find(".text").html(data);
            $cur_note.find(".readmore").hide();
        }
    });
}

$(document).ready(function () {

  /*footer social*/
  (function () {
    if (window.pluso)if (typeof window.pluso.start == "function") return;
    if (window.ifpluso == undefined) {
      window.ifpluso = 1;
      var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
      s.type = 'text/javascript';
      s.charset = 'UTF-8';
      s.async = true;
      s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://share.pluso.ru/pluso-like.js';
      var h = d[g]('body')[0];
      h.appendChild(s);
    }
  })();
  /* end of footer social*/

  /*Browser Detect*/
  browserDetect = {
    matchGroups: [[{uaString: 'win', className: 'win'}, {
      uaString: 'mac', className: 'mac'
    }, {uaString: ['linux', 'x11'], className: 'linux'}], [{
      uaString: 'msie', className: 'trident'
    }, {uaString: 'applewebkit', className: 'webkit'}, {uaString: 'gecko', className: 'gecko'}, {
      uaString: 'opera', className: 'presto'
    }], [{uaString: 'msie 9.0', className: 'ie9'}, {uaString: 'msie 8.0', className: 'ie8'}, {
      uaString: 'msie 7.0', className: 'ie7'
    }, {uaString: 'msie 6.0', className: 'ie6'}, {uaString: 'firefox/2', className: 'ff2'}, {
      uaString: 'firefox/3', className: 'ff3'
    }, {uaString: 'firefox/4', className: 'ff4'}, {
      uaString: ['opera', 'version/11'], className: 'opera11'
    }, {uaString: ['opera', 'version/10'], className: 'opera10'}, {
      uaString: 'opera/9', className: 'opera9'
    }, {uaString: ['safari', 'version/3'], className: 'safari3'}, {
      uaString: ['safari', 'version/4'], className: 'safari4'
    }, {uaString: ['safari', 'version/5'], className: 'safari5'}, {
      uaString: 'chrome', className: 'chrome'
    }, {uaString: 'safari', className: 'safari2'}, {uaString: 'unknown', className: 'unknown'}]], init: function () {
      this.detect();
      return this;
    }, addClass: function (className) {
      this.pageHolder = document.documentElement;
      document.documentElement.className += ' ' + className;
    }, detect: function () {
      for (var i = 0, curGroup; i < this.matchGroups.length; i++) {
        curGroup = this.matchGroups[i];
        for (var j = 0, curItem; j < curGroup.length; j++) {
          curItem = curGroup[j];
          if (typeof curItem.uaString === 'string') {
            if (this.uaMatch(curItem.uaString)) {
              this.addClass(curItem.className);
              break;
            }
          } else {
            for (var k = 0, allMatch = true; k < curItem.uaString.length; k++) {
              if (!this.uaMatch(curItem.uaString[k])) {
                allMatch = false;
                break;
              }
            }
            if (allMatch) {
              this.addClass(curItem.className);
              break;
            }
          }
        }
      }
    }, uaMatch: function (s) {
      if (!this.ua) {
        this.ua = navigator.userAgent.toLowerCase();
      }
      return this.ua.indexOf(s) != -1;
    }
  }.init();
  /* end of Browser Detect*/

    /* Wow */
    new WOW().init();
    var scrollable = $('.page_holder');
    scrollable.on('scroll.wow', function(){
        scrollable.find('.wow:not(.animated):in-viewport').removeAttr('style').addClass('animated');
    });
    /* end Wow */

    /* Accordion */
    $( "#accordion" ).accordion({
        collapsible: true,
        active: false
    });
    /* end Accordion */

    /* Blur */

    /* end Blur */

    /* Background Video */
    var setSizeBackgroundVideo = function(){
        var video = document.getElementById('video');

        //video.addEventListener('loadeddata', function() {
            if ($(".background-video").length > 0) {
                $('.background-video').css({
                    'height': $('body').height(),
                    'width': $('body').width()
                });

                if ($('body').width() > $('body').height()) {
                    $('.background-video video').css({
                        'width': $('.background-video').width()
                    });
                } else {
                    $('.background-video video').css({
                        'height': $('body').height()
                    });
                }
            }
        //});
    };

    if(detectmob() == false){
        $('.slider').remove();
        setSizeBackgroundVideo();
    }else{
        $('.background-video video').remove();

//      Инициализация слайдера
        $('.slider').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true,
            autoplay: true,
            autoplaySpeed: 3000
        });

        $('.slider').on('init setPosition', function (slick) {
            $('.slider .slick-slide div').css({
                'height': $('body').height(),
                'width': $('body').width()
            });
        });
    }

    if ($('.null-block').length > 0) {
        $('.null-block').css({
            'height': $('body').height() - $('header').height() - $('.wrap-form').height() - $('footer').height()
        });
    }


    $(window).resize(function(){
        if(detectmob() == false) {
            setSizeBackgroundVideo();

            if ($('.null-block').length > 0) {
                $('.null-block').css({
                    'height': $('body').height() - $('header').height() - $('.wrap-form').height() - $('footer').height()
                });
            }
        }
    });
    /* end Background Video */

   /* Show question-form */
   $('.ask-question').unbind('click').bind('click', function(){
       if($('.question-form').css('display') == 'none'){
           $('.question-form').slideDown(500);
           $('.ask-question').addClass('active');
       }else{
           $('.question-form').slideUp(500);
           $('.ask-question').removeClass('active');
       }
   });
   /* end Show question-form */

   /* Validate and Submit Question form */
    $('.question-form form').submit(function(){
        var $form = $('.question-form form');

        $.post($form.attr('action'), $form.serialize(), function(response){
            if(response.status == "OK"){
                $form[0].reset();
                $form.find('input[type="text"]').removeClass('error');
                $form.find('input[type="email"]').removeClass('error');
                $form.find('textarea').removeClass('error');
                $form.parent().find('.flash-notice').html(response.message).show();
                setTimeout(function(){
                    $form.parent().find('.flash-notice').hide();
                }, 4000);
            }else{
                if(response.name.status != "OK"){
                    $form.find('input[type="text"]').addClass('error');
                } else {
                    $form.find('input[type="text"]').removeClass('error');
                }
                if(response.email.status != "OK"){
                    $form.find('input[type="email"]').addClass('error');
                } else {
                    $form.find('input[type="email"]').removeClass('error');
                }
                if(response.question.status != "OK"){
                    $form.find('textarea').addClass('error');
                } else {
                    $form.find('textarea').removeClass('error');
                }
            }
        });

        return false;
    });
   /* end Validate and Submit Question form */

   /* Auth Room actions */
    var changeAccountData = function(e){
        e.preventDefault();
        $(this).text('Сохранить');
        $(this).parent().find('div').before('<input type="text" value="' + $(this).parent().find('div').text() + '" />');
        $(this).parent().find('div').hide();
        $(this).click(function(e){
            var link = $(this),
                oldValue = link.parent().find('div').text();
            e.preventDefault();

            link.text('Изменить');
            link.parent().find('div').text($(this).parent().find('input').val());
            link.parent().find('input').remove();
            link.parent().find('div').show();
            link.unbind('click').click(changeAccountData);

            var params = {};
            if(link.parent().hasClass('email')){
                params['email'] = link.parent().find('div').text();
            } else if(link.parent().hasClass('phone')){
                params['phone'] = link.parent().find('div').text();
            }

            $.post('/client/updatefield', params, function(response){
                if(response.status == "ERROR"){
                    link.parent().find('div').text(oldValue);
                    link.after("<span class='error'>" + response.message + "</span>");
                    setTimeout(function(){
                        link.parent().find('.error').remove();
                    }, 4000);
                }
            });
        });
    };
    $('.wrap-container-room .bottom .email a, .wrap-container-room .bottom .phone a').click(changeAccountData);
   /* end Auth Room actions */

    /* Validate and Submit Login form */
    $('#login-dialog form.form-horizontal').submit(function(){

        var $form = $(this);

        $.post($form.attr('action'), $form.serialize(), function(response, status, request){

            if(response.indexOf("html") == -1) {

                $form.find('input').addClass('error');

            } else {

                $.get($form.attr('data-redirect'), {ajax: true}, function(response) {

                    if(response.status == 'OK') {

                        if(response.url_addr != undefined) {

                            window.location.href = response.url_addr;

                        } else if(response.redirect != undefined) {

                            $.get(response.redirect, {ajax: true}, function (data) {
                                $('#payment-dialog').remove();
                                $('body').append(data);

                                var dlg = new DialogFx(document.getElementById('payment-dialog'));

                                $('.dialog--open').removeClass('dialog--open');

                                dlg.toggle.bind(dlg);

                                $('#payment-dialog').addClass('dialog--open');
                                validatePaymentDialog();
                            });

                        }

                    }

                });

            }

            return false;
        });

        return false;
    });
    /* end Validate and Submit Login form */

    /* Validate and Submit Register form */
    $('#register-dialog form').submit(function(){
        var $form = $(this);

        $.post($form.attr('action'), $form.serialize(), function(response){

            if (response.status == "OK"){

                $.get(response.redirect, {ajax: true}, function(data) {
                    $('#payment-dialog').remove();
                    $('body').append(data);

                    var dlg = new DialogFx( document.getElementById('payment-dialog') );

                    $('.dialog--open').removeClass('dialog--open');

                    dlg.toggle.bind(dlg);

                    $('#payment-dialog').addClass('dialog--open');
                    validatePaymentDialog();
                    $('.payment-form form #site_mainbundle_payment_tariff').val($('.teriffs_block_item button.select').data('tariff-id'));
                });

            } else {

                if(response.username.status != "OK"){
                    $form.find('#username').addClass('error');
                } else {
                    $form.find('#username').removeClass('error');
                }
                if(response.password.status != "OK"){
                    $form.find('#password').addClass('error');
                } else {
                    $form.find('#password').removeClass('error');
                }
                if(response.email.status != "OK"){
                    $form.find('#email').addClass('error');
                    $form.find('#email').parent().find('div.error').remove();
                    $form.find('#email').after('<div class="error">' + response.email.message + '</div>');
                    setTimeout(function(){
                        $form.find('#email').parent().find('div.error').remove();
                    }, 5000);
                } else {
                    $form.find('#email').removeClass('error');
                }
                if(response.phone.status != "OK"){
                    $form.find('#phone').addClass('error');
                } else {
                    $form.find('#phone').removeClass('error');
                }

            }
        });

        return false;
    });
    /* end Validate and Submit Register form */

    /* Validate payment dialog */

    if($('.teriffs_block_item').length > 0) {
        $('.teriffs_block_item button')
            .unbind('click.button-tariff')
            .bind('click.button-tariff', function() {
                var $tariffId = $(this).data('tariff-id');

                $('.teriffs_block_item button').removeClass('select');
                $(this).addClass('select');
                $('.payment-form form #site_mainbundle_payment_tariff').val($tariffId);
            });
    }

    var validatePaymentDialog = function() {

      $('.payment-form form').submit(function() {

          var $form = $(this);

          $.post($form.attr('action'), $form.serialize(), function(response) {

              if (response.status == "OK") {

                  window.location.href = response.redirect;

              }
          });

          return false;
      });
    };
    /* end Validate payment dialog */

    if($('#payment-dialog').length > 0) {
        validatePaymentDialog();
    }

    /* Client room */
    var clientRoomValidate = function() {
        if($('.client-room-button').length > 0) {
            $('.client-room-button').unbind('click').bind('click', function(e) {

                e.preventDefault();

                $.get($('.client-room-button').attr('href'), {ajax: true}, function(response) {

                    if(response.status == 'OK') {

                        if(response.url_addr != undefined) {

                            window.location.href = response.url_addr;

                        } else if(response.redirect != undefined) {

                            $.get(response.redirect, {ajax: true}, function (data) {
                                $('#payment-dialog').remove();
                                $('body').append(data);

                                var dlg = new DialogFx(document.getElementById('payment-dialog'));

                                $('.dialog--open').removeClass('dialog--open');

                                dlg.toggle.bind(dlg);

                                $('#payment-dialog').addClass('dialog--open');
                                validatePaymentDialog();
                            });

                        }

                    }

                });

            });
        }
    };

    clientRoomValidate();
    /* end Client room */

    /* Validate and Submit Feedback form */
    $('#feedback-dialog form').submit(function(){

        var $form = $(this);

        $.post($form.attr('action'), $form.serialize(), function(response){

            if (response.status == "OK"){

                $form.find('input, textarea').val('');
                $form.find('input, textarea').removeClass('error');
                $form.parent().find('.flash-notice').html(response.notice);
                $form.parent().find('.flash-notice').show();

                setTimeout(function() {
                    $form.parent().find('.flash-notice').hide();
                }, 4000);

            } else {

                if(response.name.status != "OK"){
                    $form.find('#name').addClass('error');
                } else {
                    $form.find('#name').removeClass('error');
                }

                if(response.phone.status != "OK"){
                    $form.find('#phone').addClass('error');
                } else {
                    $form.find('#phone').removeClass('error');
                }

                if(response.email.status != "OK"){
                    $form.find('#email').addClass('error');
                } else {
                    $form.find('#email').removeClass('error');
                }

                if(response.theme.status != "OK"){
                    $form.find('#theme').addClass('error');
                } else {
                    $form.find('#theme').removeClass('error');
                }

                if(response.message.status != "OK"){
                    $form.find('#message').addClass('error');
                } else {
                    $form.find('#message').removeClass('error');
                }

            }
        });

        return false;
    });
    /* end Validate and Submit Feedback form */

    /* Validate and Submit Change-password form */
    $('#change-password-dialog form').submit(function(){

        var $form = $(this);

        $.post($form.attr('action'), $form.serialize(), function(response){

            if (response.status == "OK"){

                $form.find('input, textarea').val('');
                $form.find('input, textarea').removeClass('error');
                $form.parent().find('.flash-notice').html(response.notice);
                $form.parent().find('.flash-notice').show();

                setTimeout(function() {
                    $form.parent().find('.flash-notice').hide();
                }, 4000);

            } else {

                if(response.new.status != "OK"){
                    $form.find('#new_first').addClass('error');
                    $form.find('#new_second').addClass('error');
                } else {
                    $form.find('#new_first').removeClass('error');
                    $form.find('#new_second').removeClass('error');
                }

            }
        });

        return false;
    });
    /* end Validate and Submit Change-password form */

    /* Validate and Submit Forget-password form */
    $('#forget-password-dialog form').unbind('submit').bind('submit', function(){

        var $form = $(this);

        $.post($form.attr('action'), $form.serialize(), function(response){

            if (response.status == "OK"){

                $form.find('input, textarea').val('');
                $form.find('input, textarea').removeClass('error');
                $form.parent().find('.flash-notice').html(response.notice);
                $form.parent().find('.flash-notice').show();

                setTimeout(function() {
                    $form.parent().find('.flash-notice').hide();
                }, 4000);

            } else {

                if(response.notice != undefined) {

                    $form.parent().find('.flash-notice').html(response.notice);
                    $form.parent().find('.flash-notice').show();

                    setTimeout(function() {
                        $form.parent().find('.flash-notice').hide();
                    }, 4000);

                }

                if(response.email.status != "OK"){
                    $form.find('#email').addClass('error');
                } else {
                    $form.find('#email').removeClass('error');
                }

            }
        });

        return false;
    });
    /* end Validate and Submit Forget-password form */

    /* Validate and Submit Reset-password form */
    $('#reset-password-dialog form').submit(function(){

        var $form = $(this);

        $.post($form.attr('action'), $form.serialize(), function(response){

            if (response.status == "OK"){

                window.location.href = response.redirect;

            } else {

                if(response.password.status != "OK"){
                    $form.find('#password_first').addClass('error');
                    $form.find('#password_second').addClass('error');
                } else {
                    $form.find('#password_first').removeClass('error');
                    $form.find('#password_second').removeClass('error');
                }

            }
        });

        return false;
    });
    /* end Validate and Submit Reset-password form */

});