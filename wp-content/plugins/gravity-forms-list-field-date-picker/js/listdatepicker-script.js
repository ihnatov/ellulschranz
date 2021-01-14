/*
* JavaScript used by front end - assigns datepicker to fields, unbinds/destorys then re-assigns when field row is repeated
*/

function itsg_gf_ajax_datepicker_init( self ) {
	gformInitDatepicker();
	jQuery( '#ui-datepicker-div' ).hide(); // stop the date picker from appearing in the footer
}

function itsg_gf_ajax_datepicker_setdefaultdate() {
	var datepicker_fields = datepicker_settings.datepicker_fields;
	var form_id = datepicker_settings.form_id;
	
	itsg_gf_ajax_datepicker_init();
	
	for ( var key in datepicker_fields ) {
		// skip loop if the property is from prototype
		if ( !datepicker_fields.hasOwnProperty( key ) ) continue;

		var obj = datepicker_fields[ key ];
		for ( var prop in obj ) {
			// skip loop if the property is from prototype
			if( !obj.hasOwnProperty( prop ) ) continue;
			
			var field_id = key;
			var field_column = prop;
			var field_default_date = obj[ prop ]['setDate'];
			
			console.log( 'gravity-forms-list-field-date-picker :: field_id: ' + field_id + ' field_column: ' + field_column + ' field_default_date: ' + field_default_date );
			
			jQuery( '#field_'+form_id+'_'+field_id+' .gfield_list .gfield_list_'+field_id+'_cell'+field_column+' .datepicker' ).each(
				function() {
					if ( '' == jQuery( this ).val() ) {
						jQuery( this ).datepicker( 'setDate', field_default_date );
					}
				}
			);
			
		}
	};
}

function itsg_gf_datepicker_add_list_item( new_row ) {
	// run for each existing list row
	jQuery( new_row ).find( 'input.datepicker' ).each( function() {
		var datepickerField = jQuery( this );
		datepickerField.removeClass( 'hasDatepicker' ).removeAttr( 'id' );
		datepickerField.unbind( '.datepicker' );
		datepickerField.datepicker( 'destroy' );
		var datepickerIcon = datepickerField.next( '.ui-datepicker-trigger' );
		datepickerIcon.remove();
	});
	itsg_gf_ajax_datepicker_setdefaultdate();
}

// runs the main function when the page loads
if ( '1' == datepicker_settings.is_entry_detail ) {
	// runs the main function when the page loads -- entry editor -- configures any existing upload fields
	jQuery(document).ready( function($) {
		itsg_gf_ajax_datepicker_setdefaultdate();
		
		// bind the datepicker function to the 'add list item' button click event
		jQuery( '.gfield_list' ).on( 'click', '.add_list_item', function(){
			var new_row = jQuery( this ).parents( 'tr.gfield_list_group' ).next( 'tr.gfield_list_group' );
			itsg_gf_datepicker_add_list_item( new_row );  
		});
		
		// bind to post conditional logic trigger
		jQuery( document ).bind( 'gform_post_conditional_logic', function(event, formId, fields, isInit){
			itsg_gf_ajax_datepicker_setdefaultdate();
		});
	});
} else {
	jQuery( document ).bind( 'gform_post_render', function($) {
		itsg_gf_ajax_datepicker_setdefaultdate();
		
		// bind the datepicker function to the 'add list item' button click event
		jQuery( '.gfield_list' ).on( 'click', '.add_list_item', function(){
			var new_row = jQuery( this ).parents( 'tr.gfield_list_group' ).next( 'tr.gfield_list_group' );
			itsg_gf_datepicker_add_list_item( new_row );  
		});
		
		// bind to post conditional logic trigger
		jQuery( document ).bind( 'gform_post_conditional_logic', function(event, formId, fields, isInit){
			itsg_gf_ajax_datepicker_setdefaultdate();
		});
	});
}