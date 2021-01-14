<h2>Add new service</h2>
<form method="post" action="[form_action]" class="validate">
    <div class="admin_booking_form">
        <div class="admin-services">
            <label for="new_service_name">Service name</label>
            <input id="new_service_name" name="new_service_name" type="text" value="" size="10">
        </div>
        <div class="admin-services">
            <label for="new_service_title">Service title</label>
            <input id="new_service_title" name="new_service_title" type="text" value="" size="10">
        </div>
    </div>
    <div class="admin_booking_form">
        <div class="admin-services">
            <label for="parent_list">Parent service</label>
            <select name="parent_id" id="parent_list">
                <option value="0"></option>
                [parent_list]
            </select>
        </div>
        <div class="admin-services">
            <label for="new_service_price">Service price $</label>
            <input id="new_service_price" name="new_service_price" type="text" value="" size="2">
        </div>
        <div class="admin-services">
            <label for="new_btn_type">Button type</label>
            <select name="new_btn_type" id="new_btn_type">
                <option value="checkbox" selected>Checkbox</option>
                <option value="radio">Radio</option>
            </select>
        </div>

        <div class="admin-services">
            <button type="submit">
                Add service
            </button>
        </div>
    </div>
    <div class="admin_booking_form">
        <div class="admin-services">
            <label for="managers_list">Manager</label>
            <select name="manager_id" id="managers_list">
                [managers_list]
            </select>
        </div>
        <div class="admin-services">
            <label for="new_service_option">Is option? (not services)</label>
            <input id="new_service_option" name="new_service_option" type="checkbox" value="1">
        </div>
    </div>
    <input type="hidden" name="action" value="add_booking_services">
</form>

<!-- [booking_modal_form] --> 

<h3>Online reservation settings</h3>

<h4>0 field - no service charge</h4>

[services_form]

<script>
    function delete_service(slug) {
        var $j = jQuery.noConflict();
        $j.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'delete_service',
                'slug': slug
            },
            success:function(data){
                if (data) {
                    var arr = JSON.parse(data);
                    arr.forEach(function (element) {
                        $j('input[name*='+element+']').each(function () {
                            $j(this).parent().empty().remove();
                        });
                        $j('select[name*='+element+']').parent().empty().remove();
                        $j('button[onclick*='+element+']').empty().remove();
                        $j('div[id=title-for-'+element+']').empty().remove();
                    });
                }
            }
        });
        return;
    }
</script>

