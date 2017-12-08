(function ($) {
    $(document).ready(function () {

        $('#wpwrap').append(
            '<div id="fw-load-article-modal-background">\n' +
            '        </div>\n' +
            '        <div id="fw-load-article-modal" >\n' +
            '            <p>\n' +
            '                <label for="url">URL</label>\n' +
            '                <input id="url" name="url"/>\n' +
            '            </p>\n' +
            '            <p>\n' +
            '                <input type="button" id="fw-insert-article" class="button-primary" value="Insert" />\n' +
            '                <input type="button" id="fw-replace-article" class="button-primary" value="Replace"/>\n' +
            '            </p>\n' +
            '        </div>'
        );


        $('.fw-insert-article,#fw-load-article-modal-background').click(function (e) {
            $('#fw-load-article-modal-background, #fw-load-article-modal').toggleClass('active');
        })

        $('#fw-insert-article').click(function (e) {

            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: { action: "capture_article", for_url: $('input#url').val() },
                success:function (response) {
                    if(response.article){
                        window.parent.send_to_editor( response.article );
                    }
                }
            })
        })
    });



}(jQuery));
