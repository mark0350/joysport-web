(function ($) {

    $(document).ready(function () {

        var captureModal = new MKACModal(
            {
                content :
                        '<p>'+
                            '<label for="url">URL</label>' +
                            '<input id="url" name="url"/>' +
                        '</p>'+
                        '<p>' +
                            '<input type="button" id="mkac-insert-article" class="button-primary" value="Insert" />'+
                        '</p>',

                close : true,
                title : 'Enter dUrl',
                onOpen: insertEvent

            }

        );

        $('.mkac-insert-article').click(function () {
            captureModal.open();
        });




        function insertEvent(){
            $('#mkac-insert-article').click(function (e) {

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
