(function($) {

    $(document).on("submit", "#edit-client-form", function(e) {
        e.preventDefault();
        console.log("clicked");

        var formData = new FormData(document.getElementById('edit-client-form'));

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log("success");
                console.log(response);
                if (response === "success") {
                    alert("Modificarile au fost realizate cu succes!");
                    location.reload();
                } else {
                    alert("Submit failed! \nError code: " + response);
                }

            },
            error: function(response) {
                console.log("error");
                console.log(response);
                alert("Submit failed! \n Error code: " + response);
            }
        });
    });
})(jQuery)