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

$(function(){
    var setSizeBackgroundVideoBg = function(){
        console.log('setSizeBackgroundVideoBg');
        if(detectmob() == false){
            var video = document.getElementById('video');

            $('.slider').remove();

            video.addEventListener('loadeddata', function() {
                $('.st-container').fadeIn();

                $('.wrap-video').css({
                    'height': $('body').height() - 160
                });

                $('.wrap-video video').css({
                    'min-width': $('.wrap-video .video').width(),
                    'min-height': $('body').height()
                });

            }, false);
        }else{
            $('.st-container').fadeIn();
            $('.wrap-video video').remove();
            $('.wrap-video .gradient').remove();

            $('.wrap-video .video').css({
                'height': $('body').height() - 160,
                'width': $('body').width()
            });

            //  Инициализация слайдера на главной
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
                    'height': $('body').height() - 160,
                    'width': $('body').width()
                });
            });
        }
    };

    setSizeBackgroundVideoBg();

    $(window).bind('resize.video', function() {
        setSizeBackgroundVideoBg();
    });

    $("a[href='#main']").click(function(e){
        e.preventDefault();
        $(".page_holder").animate({scrollTop: $("#main").offset().top - $("header").height()}, 1000);
    });
});

//$('.page_holder').scroll(function(){
//    var wScroll = $(this).scrollTop();
//
//    $(".wrap-video .logo").css({
//        'transform' : 'translate(0px, '+wScroll/3+'%)'
//    });
//
//    if(wScroll >= $('body').height() - 500){
//        $('header').switchClass('no-show', 'show', 500);
//    }else{
//        $('header').switchClass('show', 'no-show', 500);
//    }
//});
