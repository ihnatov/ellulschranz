<h1>My appointments</h1>


<div class="admin-container">
    <div class="admin-stat-block">
        <div style="display:flex;">
            <h2>Details booking № <span id="b_number"></span></h2> 
            <div class="detail-button">
            <!---NOT_ADMIN 
                <div id="status_btn" style="display: none;">
                    <button style="background: #7dfd7d;" class="update_details_statuses">Accept</button> 
                    <b>/</b> 
                    <button style="background: #ff6f6f;" class="update_details_statuses">Reject</button>

                    <input type="hidden" name="my_ids" id="my_ids">
                </div>
            NOT_ADMIN--->
            </div>
        </div>

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
            <tbody id="book_received_container">
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

        jq('#book_received_container').on('click', function (e) {
            if (e.target.parentElement.id.indexOf("booking_id_") === -1) return;

            var id = e.target.parentElement.id.replace(/booking_id_/i, '');
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
                            jq('#my_ids').val(data.my_ids);

                            if (data.show_btn) {
                                jq('#status_btn').show();
                            } else {
                                jq('#status_btn').hide();
                            }
                        }
                    }
                }
            });

            if (!e.target.parentElement.classList.contains('tr-selected')) {
                jq('.tr-selected').removeClass('tr-selected');
                e.target.parentElement.classList.add('tr-selected');
            }
        });

        jq('.update_details_statuses').on('click', function (e) {
            var newStatus = e.target.innerText;
            var my_ids = jq('#my_ids').val();

            jq.ajax({
                type: 'POST',
                url: '[ajax_url]',
                data: {
                    'action': 'update_details_statuses',
                    'id': jq('#b_number').text(),
                    'my_ids': my_ids,
                    'status': newStatus.toLowerCase()
                },
                success:function(data){
                    if (data) {
                        data = JSON.parse(data);
                        if (data.status) {
                            var arr = my_ids.split(',');
                            arr.forEach(id => jq('#my_id_'+id).text(newStatus.toLowerCase()));

                            jq('#status_btn').hide();
                        }
                    }
                }
            });
        });
    } );
</script>
