/**
 * Admin Settings Js.
 *
 * @package Temporary Login Without Password
 */

(function ($) {
	'use strict';

	jQuery( document ).ready(
		function () {

			jQuery( '#add-new-wtlwp-form-button' ).click(
				function () {
					jQuery( '#new-wtlwp-form' ).show();
					jQuery( '#update-wtlwp-form' ).hide();
				}
			);

			jQuery( '#cancel-new-login-form' ).click(
				function () {
					jQuery( '#new-wtlwp-form' ).hide();
					jQuery( '#update-wtlwp-form' ).show();
				}
			);

			jQuery( '#cancel-update-login-form' ).click(
				function () {
					jQuery( '#update-wtlwp-form' ).hide();
				}
			);

			if (jQuery( '.wtlwp-click-to-copy-btn' ).get( 0 )) {

				var clipboard = new Clipboard( '.wtlwp-click-to-copy-btn' );

				clipboard.on(
					'success', function (e) {
						var elem      = e.trigger;
						var className = elem.getAttribute( 'class' );
						jQuery( '#copied-text-message-' + className ).text( 'Copied' ).fadeIn();
						jQuery( '#copied-text-message-' + className ).fadeOut( 'slow' );
					}
				);
			}

			if (jQuery( '.wtlwp-copy-to-clipboard' ).get( 0 )) {
				var clipboard_link = new Clipboard( '.wtlwp-copy-to-clipboard' );

				clipboard_link.on(
					'success', function (e) {
						var elem = e.trigger;
						var id   = elem.getAttribute( 'id' );
						jQuery( '#copied-' + id ).text( 'Copied' ).fadeIn();
						jQuery( '#copied-' + id ).fadeOut( 'slow' );
					}
				);
			}

			jQuery( '#new-user-expiry-time' ).change(
				function () {
					var value = jQuery( this ).val();
					showDatePicker( value, 'new' );
				}
			);

			jQuery( '#update-user-expiry-time' ).change(
				function () {
					var value = jQuery( this ).val();
					showDatePicker( value, 'update' );
				}
			);

			function showDatePicker(value, datePickerClass) {

				var customDatePickerClass = '';
				var customDatePickerID    = '';
				if ( 'new' === datePickerClass ) {
					customDatePickerClass = '.new-custom-date-picker';
					customDatePickerID    = '#new-custom-date-picker';
				} else {
					customDatePickerClass = '.update-custom-date-picker';
					customDatePickerID    = '#update-custom-date-picker';
				}

				if (value === 'custom_date') {
					var tomorrowDate = new Date( new Date().getTime() + 24 * 60 * 60 * 1000 );
					jQuery( customDatePickerClass ).datepicker(
						{
							dateFormat: 'yy-mm-dd',
							minDate: tomorrowDate
						}
					);
					jQuery( customDatePickerID ).show();
				} else {
					jQuery( customDatePickerID ).hide();
				}
			}

		}
	);

})( jQuery );
