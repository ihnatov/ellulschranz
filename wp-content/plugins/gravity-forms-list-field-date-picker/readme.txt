=== Date Picker in List Fields for Gravity Forms ===
Contributors: ovann86
Donate link: https://www.itsupportguides.com/donate/
Tags: Gravity Forms
Requires at least: 5.0
Tested up to: 5.1
Stable tag: 1.7.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to turn a list field column into a 'date' field

== Description ==

> This plugin is an add-on for the Gravity Forms plugin. If you don't yet own a license for Gravity Forms - <a href="https://rocketgenius.pxf.io/dbOK" target="_blank">buy one now</a>! (affiliate link)

**What does this plugin do?**

* make any list field column a date picker field
* works with single and multi-column list fields

**What options are available?**

Front end options are available to

 - set the date format for each datepicker. Options include:

* mm/dd/yyyy
* dd/mm/yyyy
* dd-mm-yyyy
* dd.mm.yyyy
* yyyy/mm/dd
* yyyy-mm-dd
* yyyy.mm.dd

 - have no icon or a calendar icon
 - set a default date

There are also a number of filters available to customise the date picker fields, including:

* set a default date format - if your forms always use a format, e.g. DD/MM/YYYY, you can set this as the default format
* set a default date
* configure the date picker, e.g. restrict the range of dates selectable
* disable date format validation - for when you customise the date picker to use a non-standard date format
* disable CSS styles provided by plugin

See the FAQ page for more information.

**How to use**

For multi-column lists, the date picker options are under the 'General' tab, below the list of columns. 

For single-column lists the date picker options are under the 'Appearance' tab.

> See a demo of this plugin at [demo.itsupportguides.com/gravity-forms-list-field-date-picker/](http://demo.itsupportguides.com/gravity-forms-list-field-date-picker/ "demo website")

**Disclaimer**

*Gravity Forms is a trademark of Rocketgenius, Inc.*

*This plugins is provided “as is” without warranty of any kind, expressed or implied. The author shall not be liable for any damages, including but not limited to, direct, indirect, special, incidental or consequential damages or losses that occur out of the use or inability to use the plugin.*

== Installation ==

1. Install plugin from WordPress administration or upload folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in the WordPress administration
1. Open the Gravity Forms 'Forms' menu
1. Open the forms editor for the form you want to change
1. Add or open an existing list field
1. With multiple columns enabled you will see a 'date field' option - when ticked the front end will use the jQuery datapicker

== Frequently Asked Questions ==

**How do I disable the CSS styles for the datepicker**

By default the CSS styles from Gravity Forms are applied, regardless of what the Gravity Forms 'Output CSS' setting is set to.

If you want to disable these styles you will need to apply the filter below.

`add_filter( 'itsg_listdatepicker_usegfcss', function () {
	return true;
});`

**How do I set a default date format?**

The default date format is mm/dd/yyyy.

The 'itsg_list_field_datepicker_default_format' filter is available to set a default date format.

To set the default date format at dd/mm/yyyy you would use the following code

`add_filter( 'itsg_list_field_datepicker_default_format', 'change_default_format', 10, 4 );
function change_default_format( $fedault, $form_id, $field_id, $column_number) {
	return 'dmy';
}`

The other format options are:

*   **mdy** for mm/dd/yyyy
*   **dmy** for dd/mm/yyyy
*   **dmy_dash** for dd-mm-yyyy
*   **dmy_dot** for dd.mm.yyyy
*   **ymd_slash** for yyyy/mm/dd
*   **ymd_dash** for yyyy-mm-dd
*   **ymd_dot** for yyyy.mm.dd

**How do I set a default date?**

There are three ways to change the default date.

**1. Set the default date in the field settings**

Use the 'Default Date' option for the date picker enabled field in the form editor.

**2. Use the 'itsg_datepicker_fields' filter**

The 'itsg_datepicker_fields' filter can be used to set a default field for a particular field.

To use the filter you need to know the form id, the field id and the column position.

The example below shows how to use this filter on form id 50, field id 22 and column 1

`add_filter( 'itsg_datepicker_fields', function ( $datepicker_fields, $form_id ) {
	if ( 50 == $form_id ) {
		$datepicker_fields['22'] = array( '1' => date( 'm/d/Y', strtotime( 'friday this week' ) ) );
	}
	return $datepicker_fields;
}, 10, 2);`

**3. Use the 'itsg_default_datepicker_date' action to set a default date for all datepicker enabled list fields.**

The 'itsg_default_datepicker_date' action is available to set a default date.

To use this action you will need to add some code to your themes functions.php file, below the starting <?php line.

The action takes a date formatted as a string, for example 01/01/2015. Your code could return a simple date, e.g. 01/06/2015 or using the PHP date function you could create a dynamic date, such as 'Monday of this week'.

It is important that the format of the string matches the formatting of the date picker field.

The example below will set the default date to the Monday of the current week - note the date format is d/m/Y

`add_filter( 'itsg_default_datepicker_date', function () {
	return date("d/m/Y",strtotime('monday this week'));
});`

The example below will set the default date to 20 days ahead of the current date - note the date format is yyyy.mm.dd

`add_filter( 'itsg_default_datepicker_date', function () {
	return date("Y.m.d",strtotime('+20 days'));
});`

The example below will set the default date to 15-01-2015 (15 January 2015) - note the date format is d-m-Y

`add_filter( 'itsg_default_datepicker_date', function () {
	return '15-01-2015';
});`

The example below will set the default date to the first day of the following year - note the date format is in m/d/Y

`add_filter( 'itsg_default_datepicker_date', function () {
    return date("m/d/Y",strtotime('first day of January next year'));
});`

For more information on the strtotime function, see [strtotime](http://php.net/manual/en/function.strtotime.php)

For more information on the date function, see [date](http://php.net/manual/en/function.date.php)

**How do I configure the datepicker**

Rocketgenius have documentation on how to configure the datepicker in Gravity Forms using the [gform_datepicker_options_pre_init hook](https://www.gravityhelp.com/documentation/article/gform_datepicker_options_pre_init/#2-no-weekends).

As an example of how to implement this, the following code will disable weekends from the datepicker for all forms and all datepicker fields.

`	add_action('wp_footer', function () {
	?>
	<script>
	gform.addFilter( 'gform_datepicker_options_pre_init', function( optionsObj, formId, fieldId ) {
		optionsObj.firstDay = 1;
		optionsObj.beforeShowDay = jQuery.datepicker.noWeekends;
		return optionsObj;
	});
	</script>
	<?php
	},10);`
	
This plugin introduces a modifies version of 'gform_datepicker_options_pre_init' to allow you to target specific list field columns.

The example below shows how to use the filter against a list field column. Note the additional variable - columnNum.

`	add_action('wp_footer', function () {
	?>
	<script>
	gform.addFilter( 'gform_datepicker_options_pre_init', function( optionsObj, formId, fieldId, columnNum ) {
	if ( formId == 43 && fieldId == 24 && columnNum == 2 ) {
		optionsObj.minDate = '-10 Y';
		optionsObj.maxDate = 0;
	}
	return optionsObj;
	});
	</script>
	<?php
	},10);`

**How do I disable format validation for custom date formats**

By default this plugin will validate given dates to ensure it's in the right format.

If you're setting a non-standard date format, for example iso8601Week you will need to disable validation for the column(s).

You can do this for all forms or on a per-form, per-field, per-column basis.

The example below shows how to use the filter to disable validation for a particular column.

`add_filter( 'itsg_list_field_datepicker_disable_validation', 'itsg_disable_validation', 10, 4 );
function itsg_disable_validation( $default, $form_id, $field_id, $column_number ) {
	if ( 43 == $form_id && 31 == $field_id && 1 == $column_number ) {
		return true;
	}
}`

== Screenshots ==

1. Shows the 'Date field' option in the forms editor
2. Shows a list field using the jQuery datepicker
3. Shows a list field using the jQuery datepicker, with the default Gravity Forms styles

== Changelog ==

= 1.7.6 =
* Maintenance: general code tidy up and testing

= 1.7.5 =
* Fix: resolve conflict with <a href="https://en-au.wordpress.org/plugins/gravity-forms-list-field-select-drop-down/" target="_blank">Drop Down Options in List Fields for Gravity Forms</a> plugin
* Fix: fix CSS styles appearing in non-Gravity Forms pages
* Maintenance: tidy php (use object notation for $field)
* Maintenance: change how plugin detects that Gravity Forms is installed and enabled

= 1.7.4 =
* Fix: Patch to allow scripts to enqueue when loading Gravity Form through wp-admin. Gravity Forms 2.0.3.5 currently has a limitation that stops the required scripts from loading through the addon framework.
* Maintenance: Add minified JavaScript and CSS
* Maintenance: Confirm working with WordPress 4.6.0 RC1
* Maintenance: Update to improve support for Gravity Flow plugin

= 1.7.3 =
* Feature: New filter (itsg_list_field_datepicker_disable_validation) to disable date validation, can be applied on a all forms, per form, per field, per column basis.
* Feature: Extended the itsg_list_field_datepicker_default_format filter to include the form id, field id and column number.
* Maintenance: Improve behaviour in backend forms (entry editor, wp-ajax etc)

= 1.7.2 =
* Fix: Resolve bug with datepicker appearing in page footer
* Maintenance: Improve support for field conditional logic

= 1.7.1 =
* Maintenance: Change how date picker calendar icon is presented in forms so it is more like the default date field.

= 1.7.0 =
* Feature: Add ability to set dafault date for list field date picker fields.
* Feature: Add filter to set default date using PHP, for example 'first Monday of this week'. See FAQ for more information.
* Feature: Add support for customising the datepicker using Gravity Forms filter, 'gform_datepicker_options_pre_init'. See FAQ for more information.

= 1.6.2 =
* Fix: Fixed issue with settings for single-column list field not working correctly.
* Fix: Fixed issue calendar icon always appearing for single-column list fields.
* Maintenance: Add some styling to the options in the form editor.
* Maintenance: Change JavaScript and CSS to load using Gravity Forms addon framework.
* Maintenance: Tested against Gravity Forms 2.0 RC1.
* Maintenance: Tested against Gravity PDF 4.0 RC4.

= 1.6.1 =
* Fix: Resolve debug error message when submitting form (Warning: Invalid argument supplied for foreach())
* Fix: Resolve issue with all 'Make date picker' options in the form editor displaying as ticked dispite not being date-picker enabled
* Fix: Tweak CSS for calendar icon displayed in front end forms (added white-space: nowrap;)

= 1.6.0 =
* Feature: Ability to control if a datepicker enabled field uses a calendar icon or not
* Feature: Display calendar icons in the form editor
* Maintenance: Move CSS to file instead of in the page footer
* Maintenance: Move JavaScript to a file instead of in the page footer
* Maintenance: Add filter to allow Gravity Forms datepicker CSS to be disabled, see plugin frequently asked questions for example usage
* Maintenance: General tidy up of PHP code - replace a handful of isset() checks with gravity forms rgar() function

= 1.5.0 =
* Feature: Added validation. If a user manually types in a date that doesn't use the required format (for example, month-day-year) an error will appear when they navigate to the next page or try to submit the form.
* Feature: Added default date format and the ability to set the default date format using the 'itsg_list_field_datepicker_default_format' filter - see frequently asked question page for more information.
* Feature: Added date icon to date field enabled columns.
* Maintenance: Improve translation support.
* Maintenance: Improve WordPress multi-site support.
* Maintenance: General tidy up of PHP code, working towards WordPress standards.

= 1.4.1 =
* Fix: Resolve issue with the 'Enable datepicker' option displaying in the form editor for non-list fields. 

= 1.4 =
* Feature: Added support for text translation - uses 'itsg_list_field_datepicker' text domain.
* Feature: Added warning message if plugin is ran without Gravity Forms being activated. Message is only displayed in the wp-admin.
* Fix: Change CSS enqueue method to stop using a depreciated WordPress function (wp_print_styles). Now using wp_enqueue_styles.
* Fix: Change 'list_has_datepicker_field' function to use 'list' == $field->get_input_type()
* Maintenance: Slight change to datepicker jQuery to improve performance. (set datepicker as a variable instead of doing multiple queries)
* Maintenance: Added blank index.php file to plugin directory to ensure directory browsing does not occur. This is a security precaution.
* Maintenance: Added ABSPATH check to plugin PHP files to ensure PHP files cannot be accessed directly. This is a security precaution.

= 1.3 =
* Fix: Update datepicker jQuery to improve performance.
* Fix: Add CSS override to allow the list field datepicker to use the full column width. Override only applies if Gravity Forms styles are enabled.

= 1.2.5 =
* Feature: Added the ability to set a default date by calling the 'itsg_default_datepicker_date' action. See frequently asked questions for how to use this.

= 1.2.4 =
* Feature: Added the ability to apply date picker to a single column list field (found under the appearance tab options).
* Feature: Included Gravity Forms date picker CSS. The same styles will be applied as seen in the 'date' field. This will be disabled if you have configured the Gravity Forms settings to not use their CSS styles.
* Feature: Added check so that JavaScript does not load on front-end form page if there are no pick picker enabled lists in the form.

= 1.2.3 =
* Fix: Resolve issue with date picker not loading beyond the first row in ajax enabled multi-page forms.

= 1.2.2 =
* Fix: Resolve issue with plugin attempting to load before Gravity Forms has loaded, making this plugin not function.

= 1.2 =
* Feature: Updated how field 'Date picker' option appears when editing a list field in the back-end form editor.
* Maintenance: Updated back-end form editor JavaScript so it wont conflict with other plugins and is more adaptable to changes in the Gravity Forms JavaScript.
* Fix: Resolve issue with plugin breaking single column list fields, but checking that field has columns before attempting to load and modify column contents.
* Fix: Resolve PHP error messages - added isset( $choice["isDatePicker"] ) before calling array item, and check that list field has columns before calling column data.
* Maintenance: Changed name from 'Gravity Forms - List Field Date Picker' to 'Date Picker in List Fields for Gravity Forms'.

= 1.1 =
* Feature: Added ability to select the date format for each datepicker field. Formats available are mm/dd/yyyy, dd/mm/yyyy, dd-mm-yyyy, dd.mm.yyyy, yyyy/mm/dd, yyyy-mm-dd, yyyy.mm.dd.

= 1.0 =
* First public release.

== Upgrade Notice ==

= 1.0 =
First public release.