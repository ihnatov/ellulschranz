jQuery(document).ready(function($){
    'use strict';

    $(".booking_time").on("click", '.time-cell', select_time);
    function select_time() {
        if (my_calendar && change_calendar) {
            if ($(this).hasClass('meet-time')) return;

            var selected_times = JSON.parse(localStorage.getItem('times'));

            var date = $('#booking_date').val();
            var time = $(this).text();
            var datetime = date+'|'+time;

            $('#booking_time').val(time);

            if ($(this).hasClass('blocked-time')) {
                $(this).removeClass('blocked-time');
                selected_times = selected_times.filter(function(item) {
                    return item !== datetime;
                });
            } else {
                $(this).addClass('blocked-time');
                var insert = selected_times.every(function(item) {
                    return item !== datetime;
                });

                if (insert) {
                    selected_times.push(datetime);
                }
            }

            localStorage.setItem('times', JSON.stringify(selected_times));
        } else {
            if ($(this).hasClass('selected-time')) return;
    
            $(".selected-time").removeClass('selected-time');
            $(this).addClass('selected-time');
    
            $('#booking_time').val($(this).text());
            $('#datetime_err').css('display', 'none');
        }
    }

    $(".datepicker-here").on("click", '.datepicker--cell-month', check_load_dates);
    $(".datepicker-here").on("click", '.datepicker--cell-day', select_date);
    function select_date() {
        if (my_calendar && change_calendar) {
            if ($(this).hasClass('meet-time')) return;

            var selected_dates = JSON.parse(localStorage.getItem('dates'));

            var date = $('#booking_date').val();

            if ($(this).hasClass('blocked-time')) {
                $(this).removeClass('blocked-time');
                selected_dates = selected_dates.filter(function(item) {
                    return item !== date;
                });
            } else {
                $(this).addClass('blocked-time');
                var insert = selected_dates.every(function(item) {
                    return item !== date;
                });

                if (insert) {
                    selected_dates.push(date);
                }
            }

            localStorage.setItem('dates', JSON.stringify(selected_dates));
        }
    }
    
    $("#change_calendar").on("click", change);
    function change() {
        change_calendar = !change_calendar;
    }

    $("#save_calendar").on("click", save_data);
    function save_data() {
        var selected_dates = JSON.parse(localStorage.getItem('dates'));
        var selected_times = JSON.parse(localStorage.getItem('times'));
        var user_id = 0;

        $("#save_calendar").prop('disabled', true);
        $("#save_calendar").text('pending');
        if ($("#managers_list").length > 0) {
            user_id = $("#managers_list").val();
        }

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'save_reservate_datetimes',
                dates: selected_dates,
                times: selected_times,
                user: user_id
            },
            success: function(data) {
                var res = JSON.parse(data);
                $("#save_calendar").prop('disabled', false);
                if (res.status) {
                    $("#save_calendar").text('Success!');
                    setTimeout(() => $("#save_calendar").text('Save'), 5000);
                } else {
                    $("#save_calendar").text('Error. Try again');
                    setTimeout(() => $("#save_calendar").text('Save'), 5000);
                }
            }
        });
    }

    $("#show_manager_calendar").on("click", show_manager_calendar);
    function show_manager_calendar() {        
        var user_id = 0;
        if ($("#managers_list").length > 0) {
            user_id = $("#managers_list").val();
        }

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'show_manager_calendar',
                user: user_id
            },
            success: function(data) {
                var res = JSON.parse(data);
                if (res.status) {
                    localStorage.setItem('dates', res.b_dates);
                    localStorage.setItem('times', res.b_datetimes);
                
                    localStorage.setItem('meet_dates', res.m_dates);
                    localStorage.setItem('meet_times', res.m_datetimes);

                    clear_load_dates();
                    check_load_times();
                    check_load_dates();
                }
            }
        });
    }
});

function check_load_times() {
    var jq = jQuery.noConflict();
    var selected_times = JSON.parse(localStorage.getItem('times'));
    var meet_times = JSON.parse(localStorage.getItem('meet_times'));
    var date = jq('#booking_date').val();

    jq('.time-cell').each(function() {
        var qq = jq(this).text();

        var block = false;
        var meet = false;

        if (selected_times.length > 0) {
            block = selected_times.some(function(item) { return item == date+'|'+qq });
        }
        if (meet_times.length > 0) {
            meet = meet_times.some(function(item) { return item == date+'|'+qq });
        }

        if (block)
            jq(this).addClass('blocked-time');

        if (meet)
            jq(this).addClass('meet-time');
    });
}

function check_load_dates() {
    var jq = jQuery.noConflict();
    var selected_dates = JSON.parse(localStorage.getItem('dates'));
    var meet_dates = JSON.parse(localStorage.getItem('meet_dates'));

    jq('.datepicker--cell-day').each(function() {
        var day = jq(this).data('date');
        var month = jq(this).data('month');
        var year = jq(this).data('year');

        var block = false;
        if (selected_dates.length > 0) {
            block = selected_dates.some(function(item) {
                var arr = item.split('.');
                return Number(arr[0]) == year && (Number(arr[1]) - 1) == month && Number(arr[2]) == day;
            });
        }

        var meet = false;
        if (meet_dates.length > 0) {
            meet = meet_dates.some(function(item) {
                var arr = item.split('.');
                return Number(arr[0]) == year && (Number(arr[1]) - 1) == month && Number(arr[2]) == day;
            });
        }

        if (block)
            jq(this).addClass('blocked-time');

        if (meet)
            jq(this).addClass('meet-time');
        
        if (jq(this).hasClass('blocked-time') && jq(this).hasClass('-selected-')) {
            jq(this).removeClass('-selected-');
            jq(this).addClass('selected-time');
        }
    });
}

function clear_load_dates() {
    var jq = jQuery.noConflict();
    jq('.datepicker--cell-day').each(function() {
        jq(this).removeClass('blocked-time');
        jq(this).removeClass('selected-time');
    });
    jq('.time-cell').each(function() {
        jq(this).removeClass('blocked-time');
        jq(this).removeClass('meet-time');
    });
}
