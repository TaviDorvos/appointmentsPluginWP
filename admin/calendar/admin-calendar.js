//Add appointment and rescheduled appointment
//Datepickers + forms

(function($) {

    //Auto-complete inputs for clients
    $("#input-client").change(function() {

        var value_id = $('#add-appointment-form').find('select#input-client').val();
        console.log(value_id);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                'action': 'autocomplete_add_appointment_form_process',
                'id_client': value_id,
            },
            dataType: 'json',
            success: function(response) {
                console.log("success");
                console.log(response);
                if (value_id != "new") {
                    $('#input-name').val(response['name']).attr('readonly', true);
                    $('#input-phone').val(response['phone']).attr('readonly', true);
                    $('#input-email').val(response['email']).attr('readonly', true);
                } else {
                    $('#input-name').val("").attr('readonly', false);
                    $('#input-phone').val("").attr('readonly', false);
                    $('#input-email').val("").attr('readonly', false);
                }
            },
            error: function(response) {
                console.log("error");
                console.log(response);
                alert("Autocomplete Failed \n Error code: " + response);
            }
        });
    })

    //ADD APPOINTMENT FORM
    $(document).on("submit", "#add-appointment-form", function(e) {
        e.preventDefault();

        var formData = new FormData(this);

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

    //Select client from ADD APPOINMENT FORM
    $(document).ready(function() {
        //SELECT2.JS
        $('#input-client').select2({});
    });

    //UPDATE MODIFY APPOINTMENT FORM
    $("#modify-appointment-form #update").on("click", function(e) {
        e.preventDefault();

        var formData = new FormData(document.getElementById('modify-appointment-form'));
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

    //DELETE BUTTON FROM MODIFY APPOINTMENT FORM
    $("#modify-appointment-form #delete").on("click", function(e) {
        e.preventDefault();

        // var formData = new FormData(document.getElementById('modify-appointment-form'));
        var delete_id = $('#modify-appointment-form').find('input#input-id-modify').val();

        if (window.confirm('Ești sigur că vrei să ștergi programarea?')) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    'action': 'delete_appointment_form_process',
                    'delete_id': delete_id
                },
                success: function(response) {
                    console.log("success");
                    console.log(response);
                    if (response === "success") {
                        alert("Programare ștearsă");
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
        }
    });

    //ARRAYS WITH THE WORKING PROGRAM HOURS
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

    //Datepicker from ADD APPOINTMENT FORM modal
    $(document).ready(function() {
        $hourpickerStartAdd = $("#timepicker-start #hourpicker-start-add-appointment");
        $minutespickerStartAdd = $('#timepicker-start #minutespicker-start-add-appointment');
        $hourpickerEndAdd = $('#timepicker-end #hourpicker-end-add-appointment');
        $minutespickerEndAdd = $('#timepicker-end #minutespicker-end-add-appointment');

        $("#datepicker-add-appointment").datepicker({
            dateFormat: "yy-mm-dd",
            firstDay: 1,
            minDate: 1, // +1 day from dateToday
            beforeShowDay: function(date) {
                return [(date.getDay() == 2 || date.getDay() == 4) || date.getDay() == 5, ""]
            },
            onSelect: function(date) {
                $('#datepicker-add-appointment').val(date);
                var theDate = new Date(date);

                //minute's options
                $minutespickerStartAdd.empty(); //reseting options
                $minutespickerEndAdd.empty(); //reseting options
                $.each(minutesDay, function(value, index) {
                        var $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $minutespickerStartAdd.append($option);
                        $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $minutespickerEndAdd.append($option);
                    })
                    //if it's Thursday show some options
                if (theDate.getDay() === 4) {
                    $hourpickerStartAdd.empty(); //reseting options
                    $hourpickerEndAdd.empty(); //reseting options
                    $.each(oreJoi, function(value, index) {
                            var $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                            $hourpickerStartAdd.append($option);
                            $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                            $hourpickerEndAdd.append($option);
                        })
                        //if it's Tuesday or Friday show the options
                } else if (theDate.getDay() === 2 || theDate.getDay() === 5) {
                    $hourpickerStartAdd.empty(); //reseting options
                    $hourpickerEndAdd.empty(); //reseting options
                    $.each(oreMartiVineri, function(value, index) {
                        var $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $hourpickerStartAdd.append($option);
                        $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $hourpickerEndAdd.append($option);
                    })
                }
            }
        });
    });

    //Datepicker from MODIFY APPOINTMENTS FORM modal
    $(document).ready(function() {
        $hourpickerStart = $("#timepicker-start-modify-appointment #hourpicker-start-modify-appointment");
        $minutespickerStart = $('#timepicker-start-modify-appointment #minutespicker-start-modify-appointment');
        $hourpickerEnd = $('#timepicker-end-modify-appointment #hourpicker-end-modify-appointment');
        $minutespickerEnd = $('#timepicker-end-modify-appointment #minutespicker-end-modify-appointment');

        $("#datepicker-modify-appointment").datepicker({
            dateFormat: "yy-mm-dd",
            firstDay: 1,
            minDate: 1, // +1 day from dateToday
            beforeShowDay: function(date) {
                return [(date.getDay() == 2 || date.getDay() == 4) || date.getDay() == 5, ""]
            },
            onSelect: function(date) {
                $('#datepicker-modify-appointment').val(date);
                var theDate = new Date(date);

                //minute's options
                $minutespickerStart.empty(); //reseting options
                $minutespickerEnd.empty(); //reseting options
                $.each(minutesDay, function(value, index) {
                        var $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $minutespickerStart.append($option);
                        $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $minutespickerEnd.append($option);
                    })
                    //if it's Thursday show some options
                if (theDate.getDay() === 4) {
                    $hourpickerStart.empty(); //reseting options
                    $hourpickerEnd.empty(); //reseting options
                    $.each(oreJoi, function(value, index) {
                            var $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                            $hourpickerStart.append($option);
                            $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                            $hourpickerEnd.append($option);
                        })
                        //if it's Tuesday or Friday show the options
                } else if (theDate.getDay() === 2 || theDate.getDay() === 5) {
                    $hourpickerStart.empty(); //reseting options
                    $hourpickerEnd.empty(); //reseting options
                    $.each(oreMartiVineri, function(value, index) {
                        var $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $hourpickerStart.append($option);
                        $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $hourpickerEnd.append($option);
                    })
                }
            }
        });
    });

    //Datepicker from RESCHEDULED APPOINTMENTS FORM modal
    //OPTION 1
    $(document).ready(function() {
        $hourpickerStartOption1 = $("#timepicker-option1 #hourpicker-option1");
        $minutespickerStartOption1 = $('#timepicker-option1 #minutespicker-option1');

        $("#datepicker-option1").datepicker({
            dateFormat: "yy-mm-dd",
            firstDay: 1,
            minDate: 1, // +1 day from dateToday
            beforeShowDay: function(date) {
                return [(date.getDay() == 2 || date.getDay() == 4) || date.getDay() == 5, ""]
            },
            onSelect: function(date) {
                $('#datepicker-option1').val(date);
                var theDate = new Date(date);

                //minute's options
                $minutespickerStartOption1.empty(); //reseting options
                $.each(minutesDay, function(value, index) {
                        var $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $minutespickerStartOption1.append($option);
                        $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                    })
                    //if it's Thursday show some options
                if (theDate.getDay() === 4) {
                    $hourpickerStartOption1.empty(); //reseting options
                    $.each(oreJoi, function(value, index) {
                            var $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                            $hourpickerStartOption1.append($option);
                            $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                        })
                        //if it's Tuesday or Friday show the options
                } else if (theDate.getDay() === 2 || theDate.getDay() === 5) {
                    $hourpickerStartOption1.empty(); //reseting options
                    $.each(oreMartiVineri, function(value, index) {
                        var $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $hourpickerStartOption1.append($option);
                        $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                    })
                }
            }
        });
    });

    //Datepicker from RESCHEDULED APPOINTMENTS FORM modal
    //OPTION 2
    $(document).ready(function() {
        $hourpickerStartOption2 = $("#timepicker-option2 #hourpicker-option2");
        $minutespickerStartOption2 = $('#timepicker-option2 #minutespicker-option2');

        $("#datepicker-option2").datepicker({
            dateFormat: "yy-mm-dd",
            firstDay: 1,
            minDate: 1, // +1 day from dateToday
            beforeShowDay: function(date) {
                return [(date.getDay() == 2 || date.getDay() == 4) || date.getDay() == 5, ""]
            },
            onSelect: function(date) {
                $('#datepicker-option2').val(date);
                var theDate = new Date(date);

                //minute's options
                $minutespickerStartOption2.empty(); //reseting options
                $.each(minutesDay, function(value, index) {
                        var $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $minutespickerStartOption2.append($option);
                        $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                    })
                    //if it's Thursday show some options
                if (theDate.getDay() === 4) {
                    $hourpickerStartOption2.empty(); //reseting options
                    $.each(oreJoi, function(value, index) {
                            var $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                            $hourpickerStartOption2.append($option);
                            $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                        })
                        //if it's Tuesday or Friday show the options
                } else if (theDate.getDay() === 2 || theDate.getDay() === 5) {
                    $hourpickerStartOption2.empty(); //reseting options
                    $.each(oreMartiVineri, function(value, index) {
                        var $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $hourpickerStartOption2.append($option);
                        $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                    })
                }
            }
        });
    });

    //Datepicker from RESCHEDULED APPOINTMENTS FORM modal
    //OPTION 3
    $(document).ready(function() {
        $hourpickerStartOption3 = $("#timepicker-option3 #hourpicker-option3");
        $minutespickerStartOption3 = $('#timepicker-option3 #minutespicker-option3');
        var dateToday = new Date();

        $("#datepicker-option3").datepicker({
            dateFormat: "yy-mm-dd",
            firstDay: 1,
            minDate: 1, // +1 day from dateToday
            beforeShowDay: function(date) {
                return [(date.getDay() == 2 || date.getDay() == 4) || date.getDay() == 5, ""]
            },
            onSelect: function(date) {
                $('#datepicker-option3').val(date);
                var theDate = new Date(date);

                //minute's options
                $minutespickerStartOption3.empty(); //reseting options
                $.each(minutesDay, function(value, index) {
                        var $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $minutespickerStartOption3.append($option);
                        $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                    })
                    //if it's Thursday show some options
                if (theDate.getDay() === 4) {
                    $hourpickerStartOption3.empty(); //reseting options
                    $.each(oreJoi, function(value, index) {
                            var $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                            $hourpickerStartOption3.append($option);
                            $option = $("<option/>", {
                                value: index,
                                text: value
                            });
                        })
                        //if it's Tuesday or Friday show the options
                } else if (theDate.getDay() === 2 || theDate.getDay() === 5) {
                    $hourpickerStartOption3.empty(); //reseting options
                    $.each(oreMartiVineri, function(value, index) {
                        var $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                        $hourpickerStartOption3.append($option);
                        $option = $("<option/>", {
                            value: index,
                            text: value
                        });
                    })
                }
            }
        });
    });
})(jQuery);