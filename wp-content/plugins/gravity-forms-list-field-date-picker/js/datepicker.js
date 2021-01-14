jQuery( document ).ready( gformInitDatepicker );

function gformInitDatepicker() {
    jQuery( '.gform_wrapper .datepicker, #entry_form .datepicker' ).each(function () {

        var element = jQuery(this);
        var inputId = jQuery(this).parents( 'li.gfield, td.detail-view' ).attr( 'id' );
        var optionsObj = {
                yearRange: '-100:+20',
                showOn: 'focus',
                dateFormat: 'mm/dd/yy',
                changeMonth: true,
                changeYear: true,
                suppressDatePicker: false,
                onClose: function () {
                    element.focus();
                    var self = this;
                    this.suppressDatePicker = true;
                    setTimeout( function() {
                        self.suppressDatePicker = false;
                    }, 200 );
                },
                beforeShow: function( input, inst ) {
                    return ! this.suppressDatePicker;
                }
            };

        if ( element.hasClass( 'dmy' ) ) {
            optionsObj.dateFormat = 'dd/mm/yy';
        } else if ( element.hasClass( 'dmy_dash' ) ) {
            optionsObj.dateFormat = 'dd-mm-yy';
        } else if ( element.hasClass( 'dmy_dot' ) ) {
            optionsObj.dateFormat = 'dd.mm.yy';
        } else if ( element.hasClass( 'ymd_slash' ) ) {
            optionsObj.dateFormat = 'yy/mm/dd';
        } else if ( element.hasClass( 'ymd_dash' ) ) {
            optionsObj.dateFormat = 'yy-mm-dd';
        } else if ( element.hasClass( 'ymd_dot' ) ) {
            optionsObj.dateFormat = 'yy.mm.dd';
        }
		
		inputId = inputId.split('_');
		columnNum = jQuery(this).parents( 'td.gfield_list_cell' ).index() + 1;
		
        if ( element.hasClass( 'datepicker_with_icon' ) ) {
            optionsObj.showOn = 'both';
			if ( columnNum > 0 ) {
				optionsObj.buttonImage = jQuery( '#gforms_calendar_icon_input_' + inputId[1] + '_' + inputId[2] + '_' + columnNum ).val();
			} else {
				optionsObj.buttonImage = jQuery( '#gforms_calendar_icon_input_' + inputId[1] + '_' + inputId[2] ).val();
			}
            optionsObj.buttonImageOnly = true;
        }

        

        // allow the user to override the datepicker options object
        optionsObj = gform.applyFilters( 'gform_datepicker_options_pre_init', optionsObj, inputId[1], inputId[2], columnNum );

        element.datepicker( optionsObj );
    });
}