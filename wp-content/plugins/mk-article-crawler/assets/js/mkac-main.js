(function ($) {

    $(document).ready(function () {

        var captureModal = new MKACModal(
            {
                content :
                        '<div class="mkac-notice">'+
                        '</div>'+
                        '<p>'+
                            '<label for="url">URL</label>' +
                            '<input class="mkac-input" id="url" name="url"/>' +
                        '</p>'+
                        '<p>' +
                            '<input type="button" id="mkac-insert-article" class="button-primary" value="Insert" />'+
                        '</p>',

                close : true,
                title : 'Enter Url',
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
                    data: { action: "capture_article", mkac_url: $('input#url').val() },
                    success:function (response) {
                        if(response.success){
                            window.parent.send_to_editor( response.data.article );
                            captureModal.close();
                        }else {
                            $('.scotch-content .mkac-notice').empty();
                            $.each(response.data, function (i, v) {
                                $('.scotch-content .mkac-notice').append('<p>'+v['message']+'</p>')
                            });

                        }
                    }
                })
            })
        }


    });



}(jQuery));
