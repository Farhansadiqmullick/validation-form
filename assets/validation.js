; (function ($) {
    $(document).ready(function () {
        $("#validation-submit").on('click', function (e) {
            e.preventDefault();
            var name = $.trim($("#name").val());
            var email = $.trim($("#email").val());
            var phone = $.trim($("#phone").val());
            var zip = $.trim($("#zip").val());
            var nonce= $.trim($("#vform").val());

            var data = {
                action: "validation",
                nonce:nonce,
                name: name,
                email: email,
                phone: phone,
                zipcode: zip,
            }
            if (email.length > 0 && name.length > 0 && zip.length > 0 && phone.length > 0) {

                $.ajax({
                    type: "post",
                    url: validurl.ajaxurl,
                    dataType: "text",
                    data: data,
                    beforeSend: function () {
                        $(this).text('Loading ...');
                    }, success: function (data) {
                        console.log(data);
                        let success = $('.alert').addClass('alert-success');
                        success.removeClass('d-none');
                        success.text('The Form Submitted Successfully');
                        setInterval(function () {
                            success.addClass('d-none');
                        }, 2000);
                        $('#valid-form')[0].reset();

                    }, error: function (jqXHR, textStatus, errorThrown) {
                        console.log("jqXHR:" + jqXHR);
                        console.log("TestStatus: " + textStatus);
                        console.log("ErrorThrown: " + errorThrown);
                        let error = $('.alert').addClass('alert-danger');
                        error.removeClass('d-none');
                        error.text('The Form can\'t be submitted');
                        setInterval(function () {
                            error.addClass('d-none');
                        }, 2000);
                    }
                })
            }
        });

    });
})(jQuery);