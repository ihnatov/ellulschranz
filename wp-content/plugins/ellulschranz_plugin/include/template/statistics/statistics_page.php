<h1>Statistics</h1>

<div class="admin-container">
    <div class="admin-stat-block">
        <h2>Booked appointments</h2>
        <table id="booking_datatable_count_booked" class="display">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reservation count</th>
                    <th>Services count</th>
                </tr>
            </thead>
            <tbody>
                [t_booked_appointments_body]
            </tbody>
            <tfoot>
                <tr>
                    <th>Date</th>
                    <th>Reservation count</th>
                    <th>Services count</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="admin-stat-block">
        <h2>Most used services</h2>
        <table id="booking_datatable_bestseller" class="display">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                [t_muse_used_services_body]
            </tbody>
            <tfoot>
                <tr>
                    <th>Service</th>
                    <th>Count</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="admin-stat-block admin-stat-block-comments">
        <h2>Recent customer comments</h2>
        <table id="booking_datatable_bestseller2" class="display">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Status</th>
                    <th>Maneger</th>
                    <th>Service</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Booking ID</th>
                    <th>Status</th>
                    <th>Maneger</th>
                    <th>Service</th>
                    <th>Comments</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    var jq = jQuery.noConflict();
    jq(document).ready(function() {
        jq('#booking_datatable_count_booked').DataTable({
            "order": [[ 0, "desc" ]]
        });
        jq('#booking_datatable_bestseller').DataTable({
            "order": [[ 1, "desc" ]]
        });
        jq('#booking_datatable_bestseller2').DataTable({
            "order": [[ 0, "desc" ]]
        });
    } );
</script>
