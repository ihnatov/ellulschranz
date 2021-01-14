<?php
/*
Plugin Name: Date Picker in List Fields for Gravity Forms
Description: Gives the option of adding a date picker to a list field column
Version: 1.7.6
Author: Adrian Gordon
Author URI: https://www.itsupportguides.com
License: GPL2
Text Domain: gravity-forms-list-field-date-picker

------------------------------------------------------------------------
Copyright 2015 Adrian Gordon

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

load_plugin_textdomain( 'gravity-forms-list-field-date-picker', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

add_action( 'admin_notices', array( 'ITSG_GF_List_Field_Date_Picker', 'admin_warnings' ), 20 );

if ( !class_exists( 'ITSG_GF_List_Field_Date_Picker' ) ) {
    class ITSG_GF_List_Field_Date_Picker
    {
		private static $name = 'Date Picker in List Fields for Gravity Forms';
		private static $slug = 'gravity-forms-list-field-date-picker';

		/**
         * Construct the plugin object
         */
		 function __construct() {
			// register plugin functions through 'gform_loaded' -
			// this delays the registration until Gravity Forms has loaded, ensuring it does not run before Gravity Forms is available.
            add_action( 'gform_loaded', array( &$this, 'register_actions' ) );
		}

		/*
         * Register plugin functions
         */
		function register_actions() {
            if ( ( self::is_gravityforms_installed() ) ) {

				// addon framework
				require_once( plugin_dir_path( __FILE__ ).'gravity-forms-list-field-datepicker-addon.php' );

				// start the plugin
				add_filter( 'gform_column_input_content', array( &$this, 'change_column_content' ), 10, 6 );
				add_action( 'gform_editor_js', array( &$this, 'editor_js' ) );

				add_action( 'gform_field_appearance_settings', array( &$this, 'field_datepicker_settings' ) , 10, 2 );
				add_filter( 'gform_tooltips', array( &$this, 'field_datepicker_tooltip' ) );

				add_filter( 'gform_validation', array( &$this, 'validate_datepicker_fields' ) );

				// patch to allow JS and CSS to load when loading forms through wp-ajax requests
				add_action( 'gform_enqueue_scripts', array( &$this, 'datepicker_js' ), 90, 2 );
			}
		}

	/**
	 * BEGIN: patch to allow JS and CSS to load when loading forms through wp-ajax requests
	 *
	 */

		/*
         * Enqueue JavaScript to footer
         */
		public function datepicker_js( $form, $is_ajax ) {
			if ( $this->requires_scripts( $form, $is_ajax ) ) {
				$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';

				wp_enqueue_script( 'gform_datepicker_init' );
				wp_enqueue_style( 'gforms_datepicker_css', GFCommon::get_base_url() . "/css/datepicker{$min}.css", null, GFCommon::$version );
				wp_enqueue_style( 'itsg_listdatepicker_css',  plugins_url( "/css/listdatepicker-css{$min}.css", __FILE__ ) );
				wp_register_script( 'itsg_listdatepicker_js', plugins_url( "/js/listdatepicker-script{$min}.js", __FILE__ ),  array( 'jquery' ) );

				// Localize the script with new data
				$this->localize_scripts( $form, $is_ajax );

			}
		} // END datepicker_js

		public function requires_scripts( $form, $is_ajax ) {
			if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX && ! GFCommon::is_form_editor() && is_array( $form ) ) {
				foreach ( $form['fields'] as $field ) {
					if ( 'list' == $field->get_input_type() ) {
						$has_columns = is_array( $field->choices );
						if ( $has_columns ) {
							foreach( $field->choices as $choice ) {
								if ( true  == rgar( $choice, 'isDatePicker' ) )  {
									return true;
								}
							}
						} else if ( true == $field->itsg_list_field_datepicker ) {
							return true;
						}
					}
				}
			}

			return false;
		} // END requires_scripts

		function localize_scripts( $form, $is_ajax ) {
			// Localize the script with new data
			$form_id = $form['id'];
			$default_datepicker_date = apply_filters( 'itsg_default_datepicker_date', '' );
			$datepicker_fields = array();
			$is_entry_detail = GFCommon::is_entry_detail();

			if ( is_array( $form['fields'] ) ) {
				foreach ( $form['fields'] as $field ) {
					if ( 'list' == $field->type ) {
						$field_id = $field->id;
						$has_columns = is_array( $field->choices );
						if ( $has_columns ) {
							foreach( $field->choices as $key => $choice ){
								if ( true  == rgar( $choice, 'isDatePicker' ) )  {
									$column_number = $key + 1;
									$datepicker_default_format = strlen( $default_datepicker_date) > 0 ? $default_datepicker_date : rgar( $choice, 'isDatePickerDefaultDate' );
									if ( strlen( $datepicker_default_format ) > 0 ) {
										$datepicker_fields[ $field_id ][ $column_number ]['setDate'] = $datepicker_default_format;
									}
								}
							}
						} elseif ( true == $field->itsg_list_field_datepicker ) {
							$datepicker_default_format = strlen( $default_datepicker_date) > 0 ? $default_datepicker_date : rgar( $field, 'itsg_list_field_datepicker_default_date' );
							if ( strlen( $datepicker_default_format ) > 0 ) {
								$datepicker_fields[ $field_id ][1]['setDate'] = $datepicker_default_format;
							}
						}
					}
				}
			}

			// get Ajax Upload options
			$datepicker_fields = apply_filters( 'itsg_datepicker_fields', $datepicker_fields, $form_id );

			$settings_array = array(
					'gf_base_url' => esc_js( GFCommon::get_base_url() ),
					'datepicker_fields' => $datepicker_fields,
					'form_id' => $form_id,
					'is_entry_detail' => $is_entry_detail ? $is_entry_detail : 0,
			);

			wp_localize_script( 'itsg_listdatepicker_js', 'datepicker_settings', $settings_array );

			// Enqueued script with localized data.
			wp_enqueue_script( 'itsg_listdatepicker_js' );

		} // END localize_scripts

	/**
	 * END: patch to allow JS and CSS to load when loading forms through wp-ajax requests
	 *
	 */

		/*
		 * Handles custom validation for datepicker enabled fields
		 */
		function validate_datepicker_fields( $validation_result ) {
			$form = $validation_result['form'];
			if ( is_array( $form ) && self::list_has_datepicker_field( $form ) ) {
				$current_page = rgpost( 'gform_source_page_number_' . $form['id'] ) ? rgpost( 'gform_source_page_number_' . $form['id'] ) : 1;
				foreach( $form['fields'] as &$field )  {
					$field_page = $field->pageNumber;
					$is_hidden = RGFormsModel::is_field_hidden( $form, $field, array() );
					if ( $field_page != $current_page || $is_hidden ) {
						continue;
					}
					$has_columns = is_array( $field->choices );
					if ( $has_columns ) {
						$number_of_columns = sizeof( $field->choices );
						$column_number = 0;
						$value = rgpost( "input_{$field->id}" );
						if ( is_array( $value ) ) {
							foreach( $value as $key => $column_value ) {
								if ( true  == rgar( $field['choices'][ $column_number ], 'isDatePicker' ) )  {
									if ( apply_filters( 'itsg_list_field_datepicker_disable_validation', false, $form['id'], $field->id, $column_number + 1 ) ) {
										break;
									}
									$default_format = apply_filters( 'itsg_list_field_datepicker_default_format', 'mdy', $form['id'], $field->id, $column_number + 1 );
									$date_format = '' !== rgar( $field['choices'][ $column_number ], 'isDatePickerFormat' ) ?  $field['choices'][ $column_number ]['isDatePickerFormat'] : $default_format;
									$value = $column_value;
									if ( ! empty( $value ) ) {
										$date = GFCommon::parse_date( $value, $date_format );
										if ( empty( $date ) || ! $this->checkdate( $date['month'], $date['day'], $date['year'] ) ) {
											$validation_result['is_valid'] = false; // set the form validation to false
											$field->failed_validation = true;
											$format_name  = '';
											switch ( $date_format ) {
												case 'mdy' :
													$format_name = 'mm/dd/yyyy';
													break;
												case 'dmy' :
													$format_name = 'dd/mm/yyyy';
													break;
												case 'dmy_dash' :
													$format_name = 'dd-mm-yyyy';
													break;
												case 'dmy_dot' :
													$format_name = 'dd.mm.yyyy';
													break;
												case 'ymd_slash' :
													$format_name = 'yyyy/mm/dd';
													break;
												case 'ymd_dash' :
													$format_name = 'yyyy-mm-dd';
													break;
												case 'ymd_dot' :
													$format_name = 'yyyy.mm.dd';
													break;
											}
											$message = sprintf( esc_html__( "The column '%s' requires a valid date in %s format.", 'gravity-forms-list-field-date-picker' ), $field['choices'][ $column_number ]['text'], $format_name );
											$field->validation_message = $message;
										}
									}
								}
								if ( $column_number >= ( $number_of_columns - 1 ) ) {
									$column_number = 0; // reset column number
								} else {
									$column_number = $column_number + 1; // increment column number
								}
							}
						}
					} elseif ( true == $field->itsg_list_field_datepicker ) {
						$default_format = apply_filters( 'itsg_list_field_datepicker_default_format', 'mdy', $form['id'], $field['id'], 1 );
						$date_format = '' !== $field->itsg_list_field_datepicker_format ?  $field['itsg_list_field_datepicker_format'] : $default_format;
						$value = rgpost( "input_{$field->id}" );
						if ( is_array( $value ) ) {
							foreach( $value as $key => $column_value ) {
								$value = $column_value;
								if ( ! empty( $value ) ) {
									$date = GFCommon::parse_date( $value, $date_format );
									if ( empty( $date ) || ! $this->checkdate( $date['month'], $date['day'], $date['year'] ) ) {
										$validation_result['is_valid'] = false; // set the form validation to false
										$field->failed_validation = true;
										$format_name = '';
										switch ( $date_format ) {
											case 'mdy' :
												$format_name = 'mm/dd/yyyy';
												break;
											case 'dmy' :
												$format_name = 'dd/mm/yyyy';
												break;
											case 'dmy_dash' :
												$format_name = 'dd-mm-yyyy';
												break;
											case 'dmy_dot' :
												$format_name = 'dd.mm.yyyy';
												break;
											case 'ymd_slash' :
												$format_name = 'yyyy/mm/dd';
												break;
											case 'ymd_dash' :
												$format_name = 'yyyy-mm-dd';
												break;
											case 'ymd_dot' :
												$format_name = 'yyyy.mm.dd';
												break;
										}
										$message = sprintf( esc_html__( 'Requires a valid date in %s format.', 'gravity-forms-list-field-date-picker' ), $format_name );
										$field->validation_message = $message;
									}
								}
							}
						}
					}
				}
			}
			//Assign modified $form object back to the validation result
			$validation_result['form'] = $form;
			return $validation_result;
		} // END validate_datepicker_fields

		public function checkdate( $month, $day, $year ) {
			if ( empty( $month ) || ! is_numeric( $month ) || empty( $day ) || ! is_numeric( $day ) || empty( $year ) || ! is_numeric( $year ) || strlen( $year ) != 4 ) {
				return false;
			}

			return checkdate( $month, $day, $year );
		}

		/*
         * Changes column field if 'date field' option is ticked. Adds 'datepicker' class.
         */
		function change_column_content( $input, $input_info, $field, $text, $value, $form_id ) {
			if ( GFCommon::is_form_editor() ) {
				$has_columns = is_array( $field->choices );
				if ( $has_columns ) {
					foreach( $field->choices as $choice ) {
						if ( $text == rgar( $choice, 'text' ) && true == rgar( $choice, 'isDatePicker' ) &&  'itsg_list_field_datepicker_icon_none' != rgar( $choice, 'isDatePickerIcon' ) ) {
							$new_input = str_replace( "<input ", "<input style='width:80%' ", $input );
							$new_input .=  '<img style="display:inline" id="gfield_input_datepicker_icon" src="' . GFCommon::get_base_url() . '/images/calendar.png">';
							return $new_input;
						} else if ( $text == rgar( $choice, 'text' ) ) {
							return $input;
						}
					}
				} else {
					if ( true == $field->itsg_list_field_datepicker && 'itsg_list_field_datepicker_icon_none' != $field->itsg_list_field_datepicker_icon ) {
						$new_input = str_replace( "<input ", "<input style='width:80%' ", $input );
						$new_input .=  '<img style="display:inline" id="gfield_input_datepicker_icon" src="' . GFCommon::get_base_url() . '/images/calendar.png">';
						return $new_input;
					}
					return $input;
				}
			} else {
				$field_id = $field->id;
				$column_number = 1;
				$has_columns = is_array( $field->choices );
				if ( $has_columns ) {
					$number_of_columns = sizeof( $field->choices );
					foreach( $field->choices as $choice ) {
						if ( $text == rgar( $choice, 'text' )  && true == rgar( $choice, 'isDatePicker' ) ) {
							$default_format = apply_filters( 'itsg_list_field_datepicker_default_format', 'mdy', $form_id, $field_id, $column_number );
							$date_format = '' !== rgar( $choice, 'isDatePickerFormat' ) ? $choice['isDatePickerFormat'] : esc_html( $default_format );
							$datepicker_class =  'itsg_list_field_datepicker_icon_none' == rgar( $choice, 'isDatePickerIcon' ) ? 'datepicker_no_icon datepicker' :'datepicker_with_icon datepicker';
							$new_input = str_replace( "<input ", "<input class='{$datepicker_class} {$date_format} ' ", $input );
							$new_input .=  "<input id='gforms_calendar_icon_input_{$form_id}_{$field_id}_{$column_number}' class='gform_hidden' type='hidden' value='" . GFCommon::get_base_url() . "/images/calendar.png'  />";
							return $new_input;
						} else if ( $text == rgar( $choice, 'text' ) ) {
							return $input;
						}
						if ( $column_number >= ( $number_of_columns ) ) {
							$column_number = 1; // reset column number
						} else {
							$column_number = $column_number + 1; // increment column number
						}
					}
				} else {
					if ( true == $field->itsg_list_field_datepicker ) {
						$default_format = apply_filters( 'itsg_list_field_datepicker_default_format', 'mdy', $form_id, $field_id, 1 );
						$date_format = '' !== $field->itsg_list_field_datepicker_format ? $field['itsg_list_field_datepicker_format'] : esc_html( $default_format );
						$datepicker_class =  'itsg_list_field_datepicker_icon_none' == $field->itsg_list_field_datepicker_icon ? 'datepicker_no_icon datepicker' :'datepicker_with_icon datepicker';
						$new_input = str_replace( "<input ","<input class='{$datepicker_class} {$date_format} ' ", $input );
						$new_input .=  "<input id='gforms_calendar_icon_input_{$form_id}_{$field_id}_{$column_number}' class='gform_hidden' type='hidden' value='" . GFCommon::get_base_url() . "/images/calendar.png'  />";
						return $new_input;
					}
					return $input;
				}
			}
		} // change_column_content

		/*
         * JavaScript used by form editor - Functions taken from Gravity Forms source and extended to handle the 'Date field' option
         */
		function editor_js() {
		?>
		<script>
		// ADD drop down options to list field in form editor - hooks into existing GetFieldChoices function.
		(function (w){
			var GetFieldChoicesOld = w.GetFieldChoices;

			w.GetFieldChoices = function (){

				str = GetFieldChoicesOld.apply(this, [field]);

				if ( typeof field.choices == 'undefined' ) {
					return "";
				}

				for( var i = 0; i < field.choices.length; i++ ) {
					var inputType = GetInputType( field );
					var isDatePicker = ( typeof field.choices[i].isDatePicker !== 'undefined' && field.choices[i].isDatePicker ) ? 'checked' : '';
					var value = ( typeof field.choices[i].value !== 'undefined' ) ? String( field.choices[i].value ) : field.choices[i].text;
					var isDatePickerDefaultDate = typeof field.choices[i].isDatePickerDefaultDate !== 'undefined' ? field.choices[i].isDatePickerDefaultDate : "";
					if ( inputType == 'list' ) {
						if (i == 0 ){
							str += "<p><strong><?php _e( 'Date Picker fields', 'gravity-forms-list-field-date-picker' ); ?></strong><br><?php _e( "Place a tick next to the column name to make it a date picker field. Select the date format from the 'Date Format' options.", 'gravity-forms-list-field-date-picker' ); ?></p>";
						}
						str += "<div>";
						str += "<input type='checkbox' name='choice_datepicker' id='" + inputType + "_choice_datepicker_" + i + "' " + isDatePicker + " onclick=\"SetFieldChoiceDP( '" + inputType + "', " + i + ");itsg_gf_list_datepicker_function();\" /> ";
						str += "	<label class='inline' for='"+ inputType + "_choice_datepicker_" + i + "'>"+value+" - <?php _e( 'Make Date Picker', 'gravity-forms-list-field-date-picker' ); ?></label>";
						str += "<div style='display:none; background: rgb(244, 244, 244) none repeat scroll 0px 0px; padding: 10px; border-bottom: 1px solid grey; margin: 10px 0;' class='itsg_datepicker'>";
						str += "<label for='" + inputType + "_choice_datepickerformat_" + i + "'>";
						str += "<?php _e( 'Date Format', 'gravity-forms-list-field-date-picker' ); ?></label>";
						str += "<select class='choice_datepickerformat' id='" + inputType + "_choice_datepickerformat_" + i + "' onchange=\"SetFieldChoiceDP( '" + inputType + "', " + i + ");\" style='margin-bottom: 10px;' >";
						str += "<option value='mdy'>mm/dd/yyyy</option>";
						str += "<option value='dmy'>dd/mm/yyyy</option>";
						str += "<option value='dmy_dash'>dd-mm-yyyy</option>";
						str += "<option value='dmy_dot'>dd.mm.yyyy</option>";
						str += "<option value='ymd_slash'>yyyy/mm/dd</option>";
						str += "<option value='ymd_dash'>yyyy-mm-dd</option>";
						str += "<option value='ymd_dot'>yyyy.mm.dd</option>";
						str += "</select>";
						str += "<div class='datepickericon'>";
						str += "<input style='margin: 8px;' id='" + inputType + "_choice_datepickericonnone_" + i + "' type='radio' onclick=\"SetFieldChoiceDP( '" + inputType + "', " + i + ");\" value='itsg_list_field_datepicker_icon_none' name='" + inputType + "_field_datepicker_icon_" + i + "'>";
						str += "<label class='inline' for='" + inputType + "_choice_datepickericonnone_" + i + "'> <?php _e( 'No Icon', 'gravity-forms-list-field-date-picker' ); ?> </label>";
						str += "<input style='margin: 8px;' id='" + inputType + "_choice_datepickericoncalendar_" + i + "' type='radio' onclick=\"SetFieldChoiceDP( '" + inputType + "', " + i + ");\" value='itsg_list_field_datepicker_icon_calendar' name='" + inputType + "_field_datepicker_icon_" + i + "'>";
						str += "<label class='inline' for='" + inputType + "_choice_datepickericoncalendar_" + i + "'> <?php _e( 'Calendar Icon', 'gravity-forms-list-field-date-picker' ); ?> </label>";
						str += "</div>";
						str += "<br>";
						str += "<label for='" + inputType + '_choice_defaultdate_' + i + "'>";
						str += "<?php _e( 'Default Date', 'gravity-forms-list-field-date-picker' ); ?></label>";
						str += "<input type='text' value=\"" + isDatePickerDefaultDate.replace(/"/g, "&quot;" ) + "\" class='choice_datepickerdefaultdate' id='" + inputType + '_choice_defaultdate_' + i + "' onblur=\"SetFieldChoiceDP( '" + inputType + "', " + i + " );\">";
						str += "</div>";
						str += "</div>";
					}
				}
				jQuery( '.ginput_container_list img#gfield_input_datepicker_icon' ).css( 'display', 'inline'); // work around to ensure icon is displayed - GF is in the habit of hiding the icon
				return str;
			}
		})(window || {});

		function SetFieldChoiceDP( inputType, index ) {

			var element = jQuery("#" + inputType + "_choice_selected_" + index);

			if ( 'list' == inputType ) {
				isDatePicker = jQuery( '#' + inputType + '_choice_datepicker_' + index ).is( ':checked' );
				isDatePickerFormat = jQuery( '#' + inputType + '_choice_datepickerformat_' + index ).val();
				isDatePickerIcon = jQuery( 'input:radio[name=' + inputType + '_field_datepicker_icon_' + index + ']:checked' ).val();
				isDatePickerDefaultDate = jQuery( '#' + inputType + '_choice_defaultdate_' + index ).val();

				field = GetSelectedField();

				field.choices[index].isDatePicker = isDatePicker;
				field.choices[index].isDatePickerFormat = isDatePickerFormat;
				field.choices[index].isDatePickerIcon = isDatePickerIcon;
				field.choices[index].isDatePickerDefaultDate = isDatePickerDefaultDate;
			}

			LoadBulkChoices( field );

			UpdateFieldChoices( GetInputType( field ) );

			for( var i=0; i < field.choices.length; i++ ) {
				isDatePicker = jQuery( '#' + inputType + '_choice_datepicker_' + i ).is( ':checked' );
				isDatePickerIcon = jQuery( 'input:radio[name=' + inputType + '_field_datepicker_icon_' + i + ']:checked' ).val();
				column = i + 1;
				if ( true == isDatePicker && 'itsg_list_field_datepicker_icon_none' != isDatePickerIcon ) {
					calendar_input = '<input type="text" disabled="disabled" style="width:80%"><img id="gfield_input_datepicker_icon" src="<?php echo GFCommon::get_base_url() ?>/images/calendar.png" style="display:inline">';
					jQuery( 'li#field_' + field.id + ' table.gfield_list_container tbody tr td:nth-child(' + column + ')' ).html( calendar_input );
				}
			}
			jQuery( '.ginput_container_list img#gfield_input_datepicker_icon' ).css( 'display', 'inline'); // work around to ensure icon is displayed - GF is in the habit of hiding the icon
		}

		function itsg_gf_list_datepicker_function() {
			// handles displaying the date format option for multi column lists
			jQuery( '#field_columns input[name="choice_datepicker"]' ).each( function() {
				if ( jQuery(this).is( ':checked' ) ) {
					jQuery(this).parent( 'div' ).find( '.itsg_datepicker' ).show();
				} else {
					jQuery(this).parent( 'div' ).find( '.itsg_datepicker' ).hide();
				}
			});

			// handles displaying the date format option for single column lists
			jQuery( '.ui-tabs-panel input#itsg_list_field_datepicker' ).each( function() {
				if (jQuery( this ).is( ':checked' ) ) {
					jQuery( this ).parent( 'li' ).find( '#itsg_list_field_datepicker_options_div' ).show();
				} else {
					jQuery( this ).parent( 'li' ).find( '#itsg_list_field_datepicker_options_div' ).hide();
				}
			});

			// only display this option if a single column list field
			jQuery( '#field_settings input[id=field_columns_enabled]:visible' ).each(function() {
				if (jQuery( this ).is( ':checked' ) ) {
					jQuery( this ).closest( '#field_settings' ).find( '.itsg_list_field_datepicker' ).hide();
				} else {
					jQuery( this ).closest( '#field_settings' ).find( '.itsg_list_field_datepicker' ).show();
				}
			});

			// set setting values
			jQuery( '#field_settings input[id=field_columns_enabled]:visible' ).each(function() {
				if ( jQuery(this).is( ':checked' ) ) {
					// for multi-column list field
					jQuery(this).closest( '#field_settings' ).find( '.itsg_list_field_datepicker' ).hide();
					jQuery( '#field_columns:visible select.choice_datepickerformat' ).each( function( index ){
						var format_value = ( typeof field.choices[index].isDatePickerFormat !== 'undefined' ) ? field.choices[index].isDatePickerFormat : '';
						jQuery(this).val( format_value );
						var icon_value = ( typeof field.choices[index].isDatePickerIcon !== 'undefined' ) ? field.choices[index].isDatePickerIcon : 'itsg_list_field_datepicker_icon_calendar';
						jQuery( 'input:radio[name="list_field_datepicker_icon_' + index + '"]' ).filter('[value=' + icon_value + ']' ).prop( 'checked', true );
					});
				} else {
					// for single-column list field
					jQuery(this).closest( '#field_settings' ).find( '.itsg_list_field_datepicker' ).show();
				}
			});
		}

		// trigger for when field is opened
		jQuery( document ).on( 'click', 'ul.gform_fields', function() {
			itsg_gf_list_datepicker_function();
		});

		// trigger when 'Enable multiple columns' is ticked
		jQuery( document ).on( 'change', '#field_settings input[id=field_columns_enabled], .ui-tabs-panel input#itsg_list_field_datepicker', function(){
			itsg_gf_list_datepicker_function();
		});

		// trigger for when column titles are updated
		jQuery( document ).on( 'change', '#gfield_settings_columns_container #field_columns li.field-choice-row', function() {
			InsertFieldChoice(0);
			DeleteFieldChoice(0);
			itsg_gf_list_datepicker_function();
		});

		// handle 'Enable datepicker' option in the Gravity forms editor
		jQuery( document ).ready( function($) {
			//adding setting to fields of type "list"
			fieldSettings['list'] += ', .itsg_list_field_datepicker';

			//set field values when field loads
			jQuery( document ).bind( 'gform_load_field_settings', function( event, field, form ){
				jQuery( '#itsg_list_field_datepicker' ).prop( 'checked', field['itsg_list_field_datepicker'] );
				jQuery( '#itsg_list_field_datepicker_format' ).val( field['itsg_list_field_datepicker_format'] );
				var icon_value = ( typeof field['itsg_list_field_datepicker_icon'] !== 'undefined' ) ? field['itsg_list_field_datepicker_icon'] : 'itsg_list_field_datepicker_icon_calendar';
				if ( '' != icon_value ) {
					jQuery( 'input:radio[name="itsg_list_field_datepicker_icon"]' ).filter('[value=' + icon_value + ']' ).prop('checked', true);
				}
				jQuery( '#itsg_list_field_datepicker_default_date' ).val( field['itsg_list_field_datepicker_default_date'] );
			});

		});
		</script>
		<?php
		} // END editor_js

		/*
          * Adds custom setting for field
          */
        function field_datepicker_settings( $position, $form_id ) {
            // Create settings on position 50 (top position)
            if ( 50 == $position ) {
				?>
				<li class="itsg_list_field_datepicker field_setting">
					<input type="checkbox" id="itsg_list_field_datepicker" onclick="SetFieldProperty( 'itsg_list_field_datepicker', this.checked);">
					<label class="inline" for="itsg_list_field_datepicker">
					<?php _e( 'Enable datepicker', 'gravity-forms-list-field-date-picker' ); ?>
					<?php gform_tooltip( 'itsg_list_field_datepicker' ); ?>
					</label>
					<div id="itsg_list_field_datepicker_options_div" style="background: rgb(244, 244, 244) none repeat scroll 0px 0px; padding: 10px; border-bottom: 1px solid grey; margin-top: 10px;" >
						<p><strong><?php esc_attr_e( 'Configure Date Picker Field', 'gravity-forms-list-field-date-picker' ); ?></strong>
						<br>
						<?php esc_attr_e( "Place a tick next to the column name to make it a date picker field. Select the date format from the 'Date Format' options.", 'gravity-forms-list-field-date-picker' ); ?>
						</p>
						<label for="itsg_list_field_datepicker_format" ><? _e( 'Date Format', 'gravity-forms-list-field-date-picker' ) ?></label>
						<select onchange="SetFieldProperty( 'itsg_list_field_datepicker_format', this.value);" id="itsg_list_field_datepicker_format" class="itsg_list_field_datepicker_format" style="margin-bottom: 10px;">
							<option value="mdy">mm/dd/yyyy</option>
							<option value="dmy">dd/mm/yyyy</option>
							<option value="dmy_dash">dd-mm-yyyy</option>
							<option value="dmy_dot">dd.mm.yyyy</option>
							<option value="ymd_slash">yyyy/mm/dd</option>
							<option value="ymd_dash">yyyy-mm-dd</option>
							<option value="ymd_dot">yyyy.mm.dd</option>
						</select>
						<br>
						<input style='margin: 8px 0;' id="itsg_list_field_datepicker_icon_none" type="radio" onclick="SetFieldProperty( 'itsg_list_field_datepicker_icon', this.value);" value="itsg_list_field_datepicker_icon_none" name="itsg_list_field_datepicker_icon">
						<label class="inline" for="itsg_list_field_datepicker_icon_none" style="margin-bottom: 10px;"> <?php _e( 'No Icon', 'gravity-forms-list-field-date-picker' ); ?> </label>
						<input id="itsg_list_field_datepicker_icon_calendar" type="radio" onclick="SetFieldProperty( 'itsg_list_field_datepicker_icon', this.value);" value="itsg_list_field_datepicker_icon_calendar" name="itsg_list_field_datepicker_icon">
						<label class="inline" for="itsg_list_field_datepicker_icon_calendar" style="margin-bottom: 10px;"> <?php _e( 'Calendar Icon', 'gravity-forms-list-field-date-picker' ); ?> </label>
						<label for="itsg_list_field_datepicker_default_date" ><?php esc_attr_e( 'Default Date', 'gravity-forms-list-field-date-picker' ); ?></label>
						<input onchange="SetFieldProperty( 'itsg_list_field_datepicker_default_date', this.value);" type="text" id="itsg_list_field_datepicker_default_date" style="margin-bottom: 10px;" >
						<br>
					</div>
				</li>
			<?php
            }
        } // END field_datepicker_settings

		/*
         * Tooltip for for datepicker option
         */
		function field_datepicker_tooltip( $tooltips ) {
			$tooltips['itsg_list_field_datepicker'] = "<h6>". __( 'Datepicker', 'gravity-forms-list-field-date-picker' )."</h6>". __( 'Makes list field column a datepicker. Only applies to single column list fields.', 'gravity-forms-list-field-date-picker' );
			return $tooltips;
		} // END field_datepicker_tooltip

		/*
         * Warning message if Gravity Forms is installed and enabled
         */
		public static function admin_warnings() {
			if ( ! self::is_gravityforms_installed() ) {
				printf(
					'<div class="error"><h3>%s</h3><p>%s</p><p>%s</p></div>',
						__( 'Warning', 'gravity-forms-list-field-date-picker' ),
						sprintf ( __( 'The plugin %s requires Gravity Forms to be installed.', 'gravity-forms-list-field-date-picker' ), '<strong>'.self::$name.'</strong>' ),
						sprintf ( esc_html__( 'Please %sdownload the latest version of Gravity Forms%s and try again.', 'gravity-forms-list-field-date-picker' ), '<a href="https://rocketgenius.pxf.io/dbOK" target="_blank">', '</a>' )
				);
			}
		} // END admin_warnings

		/*
         * Check if GF is installed
         */
        private static function is_gravityforms_installed() {
            return class_exists( 'GFCommon' );
        } // END is_gravityforms_installed

		/*
         * Check if list field has a date picker in the current form
         */
		public static function list_has_datepicker_field( $form ) {
			if ( is_array( $form ) ) {
				foreach ( $form['fields'] as $field ) {
					if ( 'list' == $field->get_input_type() ) {
						$has_columns = is_array( $field->choices );
						if ( $has_columns ) {
							foreach( $field->choices as $choice ) {
								if ( true  == rgar( $choice, 'isDatePicker' ) )  {
									return true;
								}
							}
						} else if ( true == $field->itsg_list_field_datepicker ) {
							return true;
						}
					}
				}
			}
			return false;
		} // END list_has_datepicker_field

	}
    $ITSG_GF_List_Field_Date_Picker = new ITSG_GF_List_Field_Date_Picker();
}