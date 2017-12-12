(function ($) {

    $(document).ready(function () {

        var captureModal = new FewenModal(
            {
                content :
                        '<p>'+
                            '<label for="url">URL</label>' +
                            '<input id="url" name="url"/>' +
                        '</p>'+
                        '<p>' +
                            '<input type="button" id="fw-insert-article" class="button-primary" value="Insert" />'+
                        '</p>',

                close : true,
                title : 'Enter Url',
                onOpen: insertEvent

            }

        );

        $('.fw-insert-article').click(function () {
            captureModal.open();
        });




        function insertEvent(){
            $('#fw-insert-article').click(function (e) {

                $.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data: { action: "capture_article", for_url: $('input#url').val() },
                    success:function (response) {
                        if(response.article){
                            window.parent.send_to_editor( response.article );
                            captureModal.close();
                        }
                    }
                })
            })
        }


    });



}(jQuery));
