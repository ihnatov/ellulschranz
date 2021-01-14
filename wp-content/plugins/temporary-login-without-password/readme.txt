=== Temporary Login Without Password ===
Contributors: storeapps, niravmehta, malayladu
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BMFRMVXQ87JWA&source=url
Tags: temporary access, developer access, admin login, temporary login, passwordless login, customer login, secure login, access, admin, log in, login, login security, protection, user login, user login, wordpress admin login, wordpress login, wp-admin, wp-login, expiration, login, Login Without Password, user, WordPress Admin, wp-admin, developer login
Requires at least: 3.0.1
Tested up to: 5.3.2
Stable tag: 1.6.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Create self-expiring, temporary admin accounts. Easily share direct login links (no need for username / password) with your developers or editors.

== Description ==

Create self-expiring, automatic login links for WordPress. Give them to developers when they ask for admin access to your site. Or an editor for a quick review of work done. Login works just by opening the link, no password needed.

Using "Temporary Login Without Password" plugin you can create a self expiring account for someone and give them a special link with which they can login to your WordPress without needing a username and password.

You can choose when the login expires, as well as the role of the temporary account. 

Really useful when you need to give admin access to a developer for support or for performing routine tasks.

Read [this article](https://www.storeapps.org/create-secure-login-without-password-for-wordpress/) to know more about what's the Current Problem – Creating a Separate Admin Login for Outsiders (Devs/ Guest bloggers) and how to avoid this pain, Top Benefits of using this plugin & Why and Who need Temporary Login links.

**Spread The Word**

If you like Temporary Login Without Password, please leave a [five stars review](https://wordpress.org/support/plugin/temporary-login-without-password/reviews/#new-post) and also spread the word about it via [Facebook](https://www.facebook.com/sharer.php?u=https://wordpress.org/plugins/temporary-login-without-password/) and [Twitter](https://twitter.com/intent/tweet?url=https://wordpress.org/plugins/temporary-login-without-password/). That helps fellow website owners assess Temporary Login Without Password easily and benefit from it!

**What's Next**

If you like this plugin then consider checking out our other solutions:

[Smart Manager](https://wordpress.org/plugins/smart-manager-for-wp-e-commerce/) - Manage & Bulk edit Products, Orders & more..

[Icegram](https://wordpress.org/plugins/icegram/) - Popups, Welcome Bar, Opt-ins & Lead Generation plugin

[Email Subscribers & Newsletters](https://wordpress.org/plugins/email-subscribers/) - A complete newsletter plugin which lets you collect leads, send automated new blog post notification emails, create & send broadcasts and also manage them all in one single place.

Also, check our other [Premium WooCommerce plugins.](https://www.storeapps.org/shop/?utm_source=wprepo&utm_medium=tlwp&utm_campaign=sa_products_upsell&utm_content=readme)


== Installation ==

1. Unzip downloaded folder and upload it to 'wp-content/plugins/' folder
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Users > Temporary Login section and create a new temporary login.

== Frequently Asked Questions ==

= 1. Do I need username & password to login using Temporary Login? =

No. Temporary Login Without Password plugin creates temporary login link to login into WordPress. User only have to go
to click on the temporary login link and they will automatically login into WordPress.

= 2. Can I disable Temporary user manually? =

Yes, you can disable temporary user manually.

= 3. Can I delete temporary user?

Yes, you can delete temporary user.

= 4. Is temporary user login with username & password?

No. temporary user can't login with username & password.

= 5. Can I convert temporary user into normal user?

No. at this moment it's not possible to do this.

== Screenshots ==

1. Create a new Temporary Login.
2. List of all (Active/Expired) Temporary Login.
3. Temporary Login settings panel.
4. Language Option for Temporary User
5. Create Temporary "Super Admin" for WordPress Multisite

== Changelog ==

**1.6.0 [2020-02-20]**

* New: Now, able to create temporary login link that will expire on specific time after access.
* Fix: Conflict of Sweetalert JS

**1.5.23 [2020-01-14]**

* Fix: Incorrect temporary Login URL shows up while using iThemes Security plugin with Custom URL Login enabled

**1.5.22 [2019-12-23]**

* Update: Compatibility test with WordPress 5.3.2

**1.5.21 [2019-11-04]**

* Update: Make it compatible with iThemes Security (formerly Better WP Security) plugin with Custom URL Login enabled

**1.5.20 [2019-09-09]**

* Update: Add Temporary Super Admin to all sites
* Fix: JS & CSS loading issue for WordPress Multi sites
* New: Added Italian translation. (Thanks [@alessioangeloro](https://profiles.wordpress.org/alessioangeloro/) for providing Italian translation)

**1.5.19.1 [2019-08-19]**

* Fix: Redirection issue with WordPress sub directory installation.

**1.5.19 [2019-08-16]**

* Fix: "404 Not Found" issue after clicking on temporary login link for WordPress sub directory installation.

**1.5.18 [2019-07-29]**

* Fix: Browser console error

**1.5.17 [2019-06-17]**

* New: added "locale" support for temporary user
* New: Show number of times temporary user logged in via temporary login link
* Fix: CSS issue with Divi theme (Thanks [@lordsnake](https://wordpress.org/support/users/lordsnake/) for reporting)

**1.5.16 [2019-05-20]**

* Update: Compatibility test with WordPress 5.2
* Fix: Typo in success message.

**1.5.15.2 [2019-04-17]**

* Update: Release Note.

**1.5.15.1 [2019-04-17]**

* Update: Minor update.

**1.5.15 [2019-03-08]**

* Fix: Redirection after login gives "404 error" error on sub-site of WordPress multi site installation. (Thanks [@csigncsign](https://wordpress.org/support/users/csigncsign/) for reporting this.)

**1.5.14 [2019-01-14]**

* Update: Added System info page and allow temporary user to access system info page

**1.5.13 [2018-11-19]**

* Update: Now, temporary user can be redirected to any page without using redirect_to query params.

**1.5.12 [2018-10-24]**

* Fix: Temporary user redirected to admin page even if 'redirect_to' query param present once user logged in with temporary login link.

**1.5.11 [2018-10-08]**

* Update: Added filter for expiry_option. Now, one can add any custom expiry_option for the temporary login. [See example](https://wordpress.org/support/topic/additional-expiry-option/)

**1.5.10 [2018-09-12]**

* Fix: 'redirect_to' parameter in request didn't use for user redirection. Now, temporary user will be redirected to url available in 'redirect_to' parameter.

**1.5.9 [2018-07-25]**

* Update: Added settings to set default expiration time. Now, admin don't have to select expiry time from dropdown every time when they
create a new temporary login.

**1.5.8 [2018-04-25]**

* Fix: Format temporary login link email for Apple Mail. (Thanks to [@danielgm](https://wordpress.org/support/users/danielgm/))

**1.5.7 [2018-04-11]**

* Update: Added settings link on Plugins page
* Update: URL parameters sanitized as keys (Thanks to [@danielgm](https://wordpress.org/support/users/danielgm/))
* Update: Paste temporary login link directly into email
* Fix: Lock and delete icon not clickable (Thanks to [@danielgm](https://wordpress.org/support/users/danielgm/))

**1.5.6 [2018-03-14]**

* Fix: Datepicker doesn't show up when edit temporary login and select "custom date" value from expiry time dropdown

**1.5.5 [2018-03-05]**

* Update: Set default expiry time as a "Week" for new temporary login instead of an "Hour".
* Fix: PHP Warning: in_array() expects parameter 2 to be array, string given in temporary-login-without-password/includes/class-wp-temporary-login-without-password-common.php
* Fix: Existing temporary user's role is not available into roles dropdown while edit.

**1.5.4 [2018-02-20]**

* Fix: Invalid argument supplied for foreach() PHP Warning in class-wp-temporary-login-without-password-deactivator.php

**1.5.3 [2018-02-06]**

* Update: Now, admin can change the role and expiry of temporary login

**1.5.2 [2018-01-29]**

* Update: Now, admin can select roles from which they want to create a Temporary Login.
* Fix: Temporary User with 'administrator' role shows as a 'Super Admin' for WordPress single site installation.

**1.5.1 [2018-01-19]**

* Fix: Parse error: syntax error, unexpected ‘[‘ (PHP < 5.4)

**1.5 [2018-01-08]**

* Update: Now, Temporary Login can be created for WordPress Multi site. Super Admin can create a temporary super admin for multi site
* Update: Restrict Temporary user to delete other users.

**1.4.6 [2017-11-18]**

* Update: Now, admin can set the default role for temporary user from settings panel

**1.4.5 [2017-11-13]**

* Update: Restrict temporary user to deactivate/delete Temporary Login Without Password plugin

**1.4.4 [2017-10-23]**

* Fix: Trying to load scripts from unauthorized sources error.

**1.4.3 [2017-08-04]**

* Fix: Localization issue

**1.4.2 [2017-06-28]**

* Fix: Uncaught Error: Call to undefined function wc_enqueue_js().

**1.4.1 [2017-06-23]**

* Update: Now, create a temporary login with custom expiry date.

**1.4 [2016-09-07]**

* Added: Support for "Theme My Login" plugin. Now, temporary user will be redirected to page which is defined in Theme My Login plugin.

**1.3 [2016-09-01]**

* Fix: Temporary user is able to login with email address. Now onwards, temporary user is not able to login using username/email and password
* Fix: Temporary user was able to reset password. Now onwards, they won't be able to reset password.
* Update: Now, role of temporary user is downgrade to "none" on deactivation of plugin and change to default on re activation of plugin

**1.2 [2016-09-01]**

* Fix: Temporary user is able to login with username and password.

**1.1 [2016-08-05]**

* Fix: Temporary user redirected to login page instead of admin dashboard after successful login.

**1.0 [2016-08-04]**

* Initial Release
