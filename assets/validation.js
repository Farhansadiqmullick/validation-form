; (function ($) {
    $(document).ready(function () {
        $("#validation-submit").on('click', function (e) {
            e.preventDefault();
            var name = $.trim($("#name").val());
            var email = $.trim($("#email").val());
            var phone = $.trim($("#phone").val());
            var zip = $.trim($("#zip").val());

            var data = {
                action: "validation",
                name: name,
                email: email,
                phone: phone,
                zipcode: zip,
            }
            if (email.length > 0 && name.length > 0 && zip.length > 0 && phone.length > 0) {

                $.ajax({
                    type: "post",
                    url: validurl.ajaxurl,
                    dataType:"text",
                    data: data,
                    beforeSend: function(){
                    $(this).text('Loading ...');
                }, success: function (data) {
                    console.log(data);
                },error: function (jqXHR, textStatus, errorThrown) {
                    console.log("jqXHR:" + jqXHR);
                    console.log("TestStatus: " + textStatus);
                    console.log("ErrorThrown: " + errorThrown);
                }
                })
            }
        });

    });
})(jQuery);