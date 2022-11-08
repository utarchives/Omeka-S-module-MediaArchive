$(document).ready( function() {
    $("#media-zip-download").click(function(){
        window.location.href = $(this).data('url');
    });
});