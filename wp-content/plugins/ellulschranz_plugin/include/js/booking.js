jQuery(document).ready(function($){
    'use strict';

    $("button[id=btn-next]").on("click", next_booking_step);    
    function next_booking_step() {
        if (currentBookingStep < 3) {
            currentBookingStep++;
            if (currentBookingStep === 3) {
                send_booking_form();
                $(".booking_submit").css('visibility', 'hidden');
                $("button[id=btn-next").css('visibility', 'hidden');
            }
            booking_modal_step_by_step();
        }
    }

    function send_booking_form() {
        $("#confirm_message").text('Please wait while we process your request');
        $("#confirm_message").css('color', 'black');
        var form = $('#booking_form');
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: form.serialize(),
            success: function(data) {
                var res = JSON.parse(data);
                if (res.status) {
                    currentBookingStep++;
                    booking_modal_step_by_step();
                    $("#confirm_message").text("Thank you for your booking.\nA member of our team will be in touch with you shortly.");
                    $("#confirm_message").css('color', 'rgba(239, 92, 92, 1)');
                    $("button[id=btn-prev").css('display', 'none');
                } else {
                    $("#confirm_message").text("Something went wrong.\nTry again");
                    $("#confirm_message").css('color', 'red');
                    $(".booking_submit").css('visibility', 'visible');
                    $("button[id=btn-prev").css('display', 'block');
                }
            }
        });        
    }
    
    $("button[id=btn-prev]").on("click", prev_booking_step);
    function prev_booking_step() {
        if (currentBookingStep > 0) {
            currentBookingStep--;
            booking_modal_step_by_step();
        }
    }

    function booking_modal_step_by_step() {
        var prev =  $("button[id=btn-prev]");
        var next =  $("button[id=btn-next]");
        var progress =  $("ul.progressbar");

        switch (currentBookingStep) {
            case 0:
                $("#services_block").removeClass('booking-hide');
                $("#datetime_block").addClass('booking-hide');
                $("#contact_block").addClass('booking-hide');
                $("#confirmation_block").addClass('booking-hide');
                check_services();
                break;
            case 1:
                $("#services_block").addClass('booking-hide');
                $("#datetime_block").removeClass('booking-hide');
                $("#contact_block").addClass('booking-hide');
                $("#confirmation_block").addClass('booking-hide');
                checkBlockedDatetimes();
                check_time();
                break;
            case 2:
                $("#services_block").addClass('booking-hide');
                $("#datetime_block").addClass('booking-hide');
                $("#contact_block").removeClass('booking-hide');
                $("#confirmation_block").addClass('booking-hide');
                check_contacts();
                break;
            case 3:
                $("#services_block").addClass('booking-hide');
                $("#datetime_block").addClass('booking-hide');
                $("#contact_block").addClass('booking-hide');
                $("#confirmation_block").removeClass('booking-hide');
                break;
            default:
                break;
        }

        if (currentBookingStep < 1 || currentBookingStep === 3) {
            prev.css('display', 'none');
        } else {
            prev.css('display', 'block');
        }

        if (currentBookingStep < 3)
            next.css('visibility', 'visible');

        if (currentBookingStep == 2) {
            next.text('Book now');
        } else {
            next.text('  Next  ');
        }

        progress.children().each(function () {
            if ($(this).data('step') < currentBookingStep) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }

            if ($(this).data('step') == currentBookingStep) {
                $(this).addClass('current');
            } else {
                $(this).removeClass('current');
            }
        });
    }

    $("input:checkbox").on("click", check_services);
    function check_services() {
        if (currentBookingStep !== 0) return;

        var boxCheck = true;
        var disabled = true;

        if ($(this).length > 0) {
            var thisId = $(this).prop('id').replace(/services_/i, '');
            if ($('#child-'+thisId).length > 0) {
                if ($(this).is(':checked')) {
                    $('#child-'+thisId).prop('hidden', false);
                } else {
                    $('#child-'+thisId).prop('hidden', true);
                    $('#child-'+thisId).find("input[type=checkbox]:checked").each(function () {        
                        $(this).prop('checked',false);
                    });
                }
            }
        }

        $("div[id*=child-]:visible").each(function () {
            if ($(this).find("input[type=checkbox]:checked").length < 1) boxCheck = false;
        });

        calculate_summ();

        if (boxCheck && $("#total").text() !== '$0' && true) disabled = false;

        $("button[id=btn-next]").prop('disabled', disabled);
    }

    $("div.booking_datapicker").on('click', check_time);
    $("input:radio").on("click", check_time);
    function check_time(e) {
        if (currentBookingStep !== 1) return;

        if (e && e.target.classList.contains("blocked-time")) {
            if (e.target.classList.contains("datepicker--cell-day")) {
                $('#booking_date').val('');
                $('#booking_time').val('');
            }
            
            if (e.target.classList.contains("time-cell")) {
                $('#booking_time').val('');
                e.target.classList.remove('selected-time');
            }
        }

        var disabled = true;
        var checkDate = false;
        var checkTime = false;
        var checkRadio = true;

        if ($('#booking_date').val()) checkDate = true;
        if ($('#booking_time').val()) checkTime = true;

        $("div[id*=child-]").each(function () {
            var id = $(this).prop('id').replace(/child-/i, '');

            if ($(this).find("input[type=radio]").length > 0)
                if (!$("input[name*=radio-group-"+id+"]:checked").val()) 
                    checkRadio = false;
        });

        if (checkRadio && checkTime && checkDate && true) disabled = false;

        $("button[id=btn-next]").prop('disabled', disabled);
        calculate_summ();
    }

    $("#contact_block input").on("click change keyup keypress", check_contacts);
    $("#contact_block textarea").on("click change keyup keypress", check_contacts);
    function check_contacts() {
        var disabled = false;
        $("#contact_block input").each(function() {
            if (!$(this).val()) disabled = true;
        });
        $("#contact_block textarea").each(function() {
            if (!$(this).val()) disabled = true;
        });
        $("button[id=btn-next]").prop('disabled', disabled);
    }

    $("input:checkbox").on("click", show_other_input);
    function show_other_input() {
        var name = $(this).prop('name');
        if ($("input").is("[name='"+name+"_value']")) {
            if ($(this).is(':checked')) {
                $("input[name='"+name+"_value']").css('display', 'block');
            } else {
                $("input[name='"+name+"_value']").css('display', 'none');
            }
        }
    }

    function calculate_summ() {
        var total = 0;
        $("input:checkbox:checked").each(function () {
            total += isNaN(parseInt($(this).data('price'))) ? 0 : parseInt($(this).data('price'));
        });

        $("input:radio:checked").each(function () {
            total += isNaN(parseInt($(this).data('price'))) ? 0 : parseInt($(this).data('price'));
        });

        $("#total").text('$' + total);
        $("#total_field").val(total);
    }

    function checkBlockedDatetimes() {
        var form = $('#booking_form');

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'show_booking_calendar_disabled',
                form: form.serializeArray()
            },
            success: function(data) {
                var res = JSON.parse(data);
                if (res.status) {
                    localStorage.setItem('dates', res.b_dates);
                    localStorage.setItem('times', res.b_datetimes);

                    clear_load_dates();
                    check_load_times();
                    check_load_dates();
                    check_time();

                    if (res.b_dates.includes((new Date()).toISOString().substr(0, 10).replace(/[-]/g, '.'))) {
                        $('#booking_date').val('');
                        $('#booking_time').val('');
                    }
                }
            }
        });
    }
});