<?php
/*
 *   Setup the settings page for configuring the options
 */
if ( class_exists( 'GFForms' ) ) {
	GFForms::include_addon_framework();
	class GFListFieldDatePicker extends GFAddOn {
		protected $_version = '1.7.6';
		protected $_min_gravityforms_version = '2.0';
		protected $_slug = 'GFListFieldDatePicker';
		protected $_full_path = __FILE__;
		protected $_title = 'Date Picker in List Fields for Gravity Forms';
		protected $_short_title = 'Date Picker in List Fields';

		public function scripts() {
			wp_deregister_script( 'gform_datepicker_init' ); // deregister default datepicker script - the default script doesnt work for list fields
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';
			$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? mt_rand() : $this->_version;

			$scripts = array(
				array(
					'handle'    => 'itsg_listdatepicker_js',
					'src'       => $this->get_base_url() . "/js/listdatepicker-script{$min}.js",
					'version'   => $version,
					'deps'      => array( 'jquery', 'gform_datepicker_init' ),
					'enqueue'   => array( array( $this, 'requires_scripts' ) ),
					'in_footer' => true,
					'callback'  => array( $this, 'localize_scripts' ),
				),
				array(
					'handle'    => 'gform_datepicker_init',
					'src'       => $this->get_base_url() . "/js/datepicker{$min}.js",
					'version'   => $version,
					'deps'      => array( 'jquery', 'jquery-ui-datepicker', 'gform_gravityforms' ),
					'enqueue'   => array( array( $this, 'requires_scripts' ) ),
					'in_footer' => true
				)
			);

			 return array_merge( parent::scripts(), $scripts );
		} // END scripts

		public function styles() {
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';
			$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? mt_rand() : $this->_version;

			$styles = array(
				array(
					'handle'  => 'listdatepicker-css',
					'src'     => $this->get_base_url() . "/css/listdatepicker-css{$min}.css",
					'version' => $version,
					'media'   => 'screen',
					'enqueue' => array( array( $this, 'requires_scripts' ) ),
				),
				array(
					'handle'  => 'gforms_datepicker_css',
					'src'     => GFCommon::get_base_url() . "/css/datepicker{$min}.css",
					'version' => GFCommon::$version,
					'media'   => 'screen',
					'enqueue' => array( array( $this, 'requires_gf_datepicker_css' ) ),
				),
			);

			return array_merge( parent::styles(), $styles );
		} // END styles

		public function localize_scripts( $form, $is_ajax ) {
			// Localize the script with new data
			$form_id = $form['id'];
			$default_datepicker_date = apply_filters( 'itsg_default_datepicker_date', '' );
			$datepicker_fields = array();
			$is_entry_detail = GFCommon::is_entry_detail();

			if ( is_array( $form ) ) {
				foreach ( $form['fields'] as $field ) {
					if ( 'list' == $field->get_input_type() ) {
						$field_id = $field->id;
						$has_columns = is_array( $field->choices );
						if ( $has_columns ) {
							foreach( $field->choices as $key => $choice ){
								if ( rgar( $choice, 'isDatePicker' ) )  {
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

		} // END localize_scripts

		public function requires_scripts( $form, $is_ajax ) {
			if ( ! $this->is_form_editor() && is_array( $form ) ) {
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

		public function requires_gf_datepicker_css( $form, $is_ajax ) {
			if ( $this->requires_scripts( $form, $is_ajax ) ) {
				$use_gf_css = apply_filters( 'itsg_listdatepicker_usegfcss', true );
				if ( $use_gf_css ) {
					if ( ! wp_style_is( 'gforms_css' ) ) {
						return true;
					}
				}
			}

			return false;
		} // END requires_gf_datepicker_css
    }
    new GFListFieldDatePicker();
}