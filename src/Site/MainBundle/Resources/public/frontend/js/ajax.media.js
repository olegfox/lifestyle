$(function(){
    $(window).scroll(function () {
        if ($(".indexByTaxonAjax").length > 0) {
            var scrollTop = document.documentElement.scrollTop || jQuery(this).scrollTop();
            if ((scrollTop >= ($(document).height() - $(window).height()) - 500)) {
                var link = $(".indexByTaxonAjax").attr('href');
                $(".indexByTaxonAjax").addClass('hold');
                $.get(link, {}, function (data) {
                    $('.temp').remove();
                    $('.media').append("<div class='temp'></div>");

                    var documentFragment = $('.temp');

                    documentFragment.append(data);

                    $('.media > .column').each(function(i, e){
                        if($(e).attr('id') == 'col0'){
                            $(e).append(documentFragment.find('#col0 .media-one:first'));
                            documentFragment.find('#col0 .media-one:first').remove();
                        }else if($(e).attr('id') == 'col1'){
                            $(e).append(documentFragment.find('#col1 .media-one:first'));
                            documentFragment.find('#col1 .media-one:first').remove();
                        }else if($(e).attr('id') == 'col2'){
                            $(e).append(documentFragment.find('#col2 .media-one:first'));
                            documentFragment.find('#col2 .media-one:first').remove();
                        }
                    });

                    $(".indexByTaxonAjax.hold").before(documentFragment.find('.indexByTaxonAjax'));

                    $(".indexByTaxonAjax.hold").remove();
                }).fail(function(){
                    $(".indexByTaxonAjax").hide();
                });
            }
        }
    });
});