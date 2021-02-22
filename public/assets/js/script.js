// Submit Form
(function($) {
    $("#contact-form").submit(function(e) {
        e.preventDefault();
        $('#contact-form input').css({
            "border": "1px solid #ccc"
        });

        // every field variable
        var inputName = $('input[name="input-name"]');
        var inputEmail = $('input[name="input-email"]');
        var inputPhone = $('input[name="input-phone"]');

        var formData = new FormData(this);
        var ajaxurl = ajax_params.ajax_url; // I can acces ajax_url through ajax_params object
        var errors = validateFields();

        //checking if i have errors
        if (errors === undefined || errors.length === 0) {

            $("button.btn-primary").hide();
            $("img.loader").show();

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
                        $('#contact-form').before("<p class='form-success-message'><b>Formularul a fost trimis cu success!</b></p>");
                        $('#contact-form').empty();
                    } else {
                        alert("Submit failed! \nError code: " + response);
                    }
                },
                error: function(response) {
                    console.log("error");
                    console.log(response);
                    alert("Submit failed! \n Error code: " + response);
                }
            })
        } else show_errors(errors);

        function validateFields() {
            var errors = [];
            var nameCondition = new RegExp("[A-Za-z]+");
            var phoneCondition = new RegExp("^[0-9]+$");
            var emailCondition = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            //Validarea numelui
            if (inputName.val() === "") {
                errors.push({
                    "field": "input-name",
                    "error": "Câmp necompletat."
                });
            } else if (nameCondition.test(inputName.val()) == false) {
                errors.push({
                    "field": "input-name",
                    "error": "Nu utilizați caractere speciale precum: ()/\*&^%$#@!"
                });
            }

            //Validarea telefonului
            if (inputPhone.val() === "") {
                errors.push({
                    "field": "input-phone",
                    "error": "Câmp necompletat."
                });
            } else if (phoneCondition.test(inputPhone.val()) == false) {
                errors.push({
                    "field": "input-phone",
                    "error": "Utilizați doar cifre de la 0-9."
                });
            }

            //Validarea emailului
            if (inputEmail.val() === "") {
                errors.push({
                    "field": "input-email",
                    "error": "Câmp necompletat."
                });
            } else if (emailCondition.test(inputEmail.val()) == false) {
                errors.push({
                    "field": "input-email",
                    "error": "Introduceți o adresă de email validă."
                });
            }

            return errors;
        }

        //clearing the old erorrs and then print the new ones
        function show_errors() {
            $(".error").remove();

            errors.forEach(function(value, index) {
                switch (value.field) {
                    default: $('#contact-form input[name*="' + value.field + '"]').before("<p class='error'>" + value.error + "</p>");
                    $('#contact-form input[name*="' + value.field + '"]').css({
                        "border": "1px solid red"
                    });
                    break;
                }
            })
            $(".error").fadeOut(3000);
        }
    });

    //Calendar
    $(function() {

        $(document).ready(function() {
            $hourpicker = $("#timepicker #hourpicker");
            $minutespicker = $('#timepicker #minutespicker');

            var oreJoi = {
                "8": "8",
                "9": "9",
                "10": "10",
                "11": "11",
                "12": "12",
                "13": "13",
            }

            var oreMartiVineri = {
                "14": "14",
                "15": "15",
                "16": "16",
                "17": "17",
                "18": "18",
                "19": "19"
            }

            var minutesDay = {
                "00": "00",
                "15": "15",
                "30": "30",
                "45": "45",
            }

            //Creating disabled dates array
            var disabledDatesArray = [];
            $.each(ajax_params.disabled_date, function(id, value) {
                disabledDatesArray.push(value['disabled_date'])
            })

            $("#datepicker").datepicker({
                dateFormat: "yy-mm-dd",
                firstDay: 1,
                minDate: 1, // +1 day from dateToday
                beforeShowDay: function(date) {
                    if ($.inArray($.datepicker.formatDate('yy-mm-dd', date), disabledDatesArray) > -1) {
                        return [false, ''];
                    }
                    return [(date.getDay() == 2 || date.getDay() == 4) || date.getDay() == 5];
                },
                onSelect: function(date) {
                    $('#input-date').val(date);
                    var startDate = new Date(date);

                    //minute's options
                    minutespicker.innerHTML = ''; //reseting options
                    $.each(minutesDay, function(value, index) {
                            var $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                            $minutespicker.append($option);
                        })
                        //if it's Thursday show some options
                    if (startDate.getDay() === 4) {
                        hourpicker.innerHTML = ''; //reseting options
                        $.each(oreJoi, function(value, index) {
                                var $option = $("<option/>", {
                                    value: index,
                                    text: value
                                });
                                $hourpicker.append($option);
                            })
                            //if it's Tuesday or Friday show the options
                    } else if (startDate.getDay() === 2 || startDate.getDay() === 5) {
                        hourpicker.innerHTML = ''; //reseting options
                        $.each(oreMartiVineri, function(value, index) {
                            var $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                            $hourpicker.append($option);
                        })
                    }
                }
            });
        });
    })
})(jQuery)