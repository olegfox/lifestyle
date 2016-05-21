var videoClick = function(link, html){
    $(link).parent().html(decodeURIComponent(html));
};