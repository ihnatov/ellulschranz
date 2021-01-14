<?php 
    $today = date('Y.m.d');
    $h = date('H');
    $m = 0;
    date('i') > 30 ? $h++ : $m = 30;
    if ($h > 23) $h -= 24;
?>

<h1>My calendar </h1>
<input type="checkbox" id="change_calendar">Reservate dates and times</input>
<button type="button" id="save_calendar">Save</button>

[is_admin]

<div class="admin-container">
    <div class="admin-stat-block adm_calendar ">
        <div 
            class="datepicker-here"
            data-min-date="0"
            style="width: 100%;"
        ></div>
        <input type="hidden" name="date" value="<?php echo $today; ?>" id="booking_date" required>

        <div class="booking_time" time="<?php echo date('H:i'); ?>">
        </div>
        <input type="hidden" name="time" value="" id="booking_time" required>
    </div>
    <div class="admin-stat-block adm_calendar ">
        
    </div>
</div>

<script>
    var jq = jQuery.noConflict();
    jq(document).ready(function() {
        
    } );

    var change_calendar = false;
    var my_calendar = true;
    var currentBookingStep = 99;

    localStorage.setItem('dates', '[b_dates]');
    localStorage.setItem('times', '[b_datetimes]');

    localStorage.setItem('meet_dates', '[m_dates]');
    localStorage.setItem('meet_times', '[m_datetimes]');

    if (!ajaxurl)
        var ajaxurl = '[ajax_url]';
</script>
