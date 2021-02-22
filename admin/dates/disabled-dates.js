(function($) {

    //DISABLED DATES FORM
    $(document).on("submit", "#disabled-dates-form", function(e) {
        e.preventDefault();

        var input_date = $('#disabled-dates-form').find('input#input-disabled-date').val();
        console.log(input_date);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                'action': 'add_disabled_date_process',
                'input_date': input_date,
            },
            success: function(response) {
                console.log("success");
                console.log(response);
                if (response === "success") {
                    window.location.reload();
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

    //Datepicker for disabling dates
    $(document).ready(function() {
        $("#datepicker-disabled-dates").datepicker({
            dateFormat: "yy-mm-dd",
            firstDay: 1,
            minDate: 1, // +1 day from dateToday
            beforeShowDay: function(date) {
                return [(date.getDay() == 2 || date.getDay() == 4) || date.getDay() == 5, ""]
            },
            onSelect: function(date) {
                $('#input-disabled-date').val(date);
            }
        });
    });
})(jQuery)