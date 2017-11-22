(function ($) {


    $(document).ready(function () {

        $('#wpwrap').append('<div id="modal-content">' +
            '<form action="post-new.php" method="post"><input type="text" name="url" id="url"><button id="modal-close">Close Modal Window</button><button type="submit">submit</button></form></div>')

        

        $(".page-title-action, #modal-background, #modal-close").click(function (e) {
            e.preventDefault();
            $("#modal-content,#modal-background").toggleClass("active");
        });


    })


}(jQuery));