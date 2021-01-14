<h1>Upcoming meetings</h1>


<div class="admin-container">
    <div class="admin-stat-block">
        <h2>Details booking № <span id="b_number"></span></h2>
        <table id="booking_datatable_detail" class="display">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Manager</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="admin-stat-block admin-stat-block-comments">
        <h2>All active booking</h2>
        <table id="booking_datatable" class="display" style="width:100%">
            <thead>
                <tr>
                    [table_header]
                </tr>
            </thead>
            <tbody>
                [table_body]
            </tbody>
            <tfoot>
                <tr>
                    [table_header]
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    var jq = jQuery.noConflict();
    jq(document).ready(function() {
        var t_details = jq('#booking_datatable_detail').DataTable({
            "searching": false,
            "paging":   false,
            "ordering": false,
            "bInfo" : false
        });
        
        jq('#booking_datatable').DataTable({
            "order": [
                [ 4, "asc" ],
                [ 5, "asc" ]
            ],
            "iDisplayLength": 10
        });

        jq('tr[id*="booking_id_"]').on('click', function () {
            var id = jq(this).prop('id').replace(/booking_id_/i, '');
            if (id == jq('#b_number').text()) return;

            jq.ajax({
                type: 'POST',
                url: '[ajax_url]',
                data: {
                    'action': 'get_booking_details',
                    'id': id
                },
                success:function(data){
                    if (data) {
                        jq('#b_number').text(id);

                        data = JSON.parse(data);
                        if (data.status) { 
                            t_details.clear();
                            t_details.rows.add(jq(data.rows)).draw();
                        }
                    }
                }
            });

            if (!jq(this).hasClass('tr-selected')) {
                jq('.tr-selected').removeClass('tr-selected');
                jq(this).addClass('tr-selected');
            }
        });
    } );
</script>
