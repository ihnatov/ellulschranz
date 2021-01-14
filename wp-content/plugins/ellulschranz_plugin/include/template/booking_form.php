<?php
    $today = date('Y.m.d');
    $h = date('H');
    $m = 0;
    date('i') > 30 ? $h++ : $m = 30;
    if ($h > 23) $h -= 24;
?>
    
<div class="booking-container">
    <h3>Online Booking Form – Services</h3>

    <div style="width:100%; display: flex;">
        <ul class="progressbar">
            <li data-step="0" class="current">Services</li>
            <li data-step="1">Date & Time</li>
            <li data-step="2">Contact Details</li>
            <li data-step="3">Payment & Confirmation</li>
        </ul>
    </div>

    <form method="post" action="[form_action]" class="validate" id="booking_form">
        <div class="booking_form">
            <!-- Step 1 -->
            <div class="booking_col" id="services_block">
                [services_block]
            </div>

            <!-- Step 2 -->
            <div class="booking_col booking-hide" id="datetime_block">
                <div class="booking_datapicker">
                    <label id="datetime_err" style="display:none;" class="valid_err"> Please select date and time</label>
                    <div class="datepicker-here" style="width: 100%;"></div>
                    <input type="hidden" name="date" value="<?php echo $today; ?>" id="booking_date" data-price="[booking]" required>

                    <label style="margin-top: 10px;">
                        Meetings will be conducted at CET/CEST Time
                    </label>
                    <div class="booking_time" time="<?php echo date('H:i'); ?>">
                    </div>
                    <input type="hidden" name="time" value="" id="booking_time" required>

                    <div class="booking_col" id="options_block">
                        [options_block]
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="booking_col booking-hide" id="contact_block">
                <div class="booking_field_row">
                    <div class="booking_field_col_2">
                        <label for="name">Name <strong>*</strong></label>
                        <div>
                            <input name="name" id="name" type="text" value="" required>
                        </div>
                    </div>
                    <div class="booking_field_col_2">
                        <label for="surname">Surname <strong>*</strong></label>
                        <div>
                            <input name="surname" id="surname" type="text" value="" required>
                        </div>
                    </div>
                    <div class="booking_field_col_2">
                        <label for="email">Email Address <strong>*</strong></label>
                        <div>
                            <input name="email" id="email" type="email" value="" required>
                        </div>
                    </div>
                    <div class="booking_field_col_2">
                        <label for="phone">Mobile Phone <strong>*</strong></label>
                        <div>
                            <input name="phone" id="phone" type="text" value="" required>
                        </div>
                    </div>
                    <div class="booking_field_col">
                        <label for="query">Please provide more information on the type of service(s) you require <strong>*</strong></label>
                        <div>
                            <textarea name="query" id="query" type="text" value="" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="booking_col booking-hide" id="confirmation_block">
                <span id="confirm_message">Please wait while we process your request</span>
            </div>
        </div>
        
        <div class="booking_submit">
            <button type="button" id="btn-prev" style="display: none;">
                &nbsp;&nbsp;Back&nbsp;&nbsp;
            </button>

            <span>
                Total <strong id="total">&dollar;0</strong>
            </span>

            <button type="button" id="btn-next" disabled>
                Next
            </button>
        </div>

        <input type="hidden" name="action" value="booking_form">
        <input type="hidden" name="total" value="0" id="total_field">
    </form>
</div>

<script>
    var my_calendar = 'booking_page';
    var change_calendar = false;

    localStorage.setItem('dates', '["2020.07.07"]');
    localStorage.setItem('times', '[]');

    localStorage.setItem('meet_dates', '[]');
    localStorage.setItem('meet_times', '[]');

    if (!currentBookingStep)
        var currentBookingStep = 0;
    if (!lastBookingStep)
        var lastBookingStep = 0;
    if (!ajaxurl)
        var ajaxurl = '[ajax_url]';
</script>