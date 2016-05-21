(function() {

    var dlgtrigger = document.querySelectorAll( '[data-dialog]'),
        dlgArr = [];

    NodeList.prototype.forEach = Array.prototype.forEach;

    dlgtrigger.forEach(function(elem){

        var somedialog = document.getElementById( elem.getAttribute( 'data-dialog' )),
            dlg = null;

        if(dlgArr[elem.getAttribute( 'data-dialog' )] == undefined) {
            dlgArr[elem.getAttribute( 'data-dialog' )] = new DialogFx( somedialog );
        }

        dlg = dlgArr[elem.getAttribute( 'data-dialog' )];

        elem.addEventListener( 'click', dlg.toggle.bind(dlg) );

    });

})();
		