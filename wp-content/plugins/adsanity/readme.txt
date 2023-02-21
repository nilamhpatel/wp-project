=== AdSanity ===
Contributors: brandondove, jeffreyzinn, nateconley
Tags: Advertising, Banners
Requires at least: 3.9
Requires PHP: 5.6.1
Tested up to: 6.0
Stable tag: 1.9
License: GPLv2 or later

Simplified banner advertising uses WordPress core functionality to group, display and track advertising campaigns.

== Description ==

Simplified banner advertising uses WordPress core functionality to group, display and track advertising campaigns.

== Installation ==

1. Upload the `adsanity` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy the sanity and simplicity that this plugin offers for your ad campaigns

== Frequently Asked Questions ==

= Where can I find help? =

We have very detailed [online documentation](https://adsanityplugin.com/help/) to help you with all of your usage questions.

= Help! I didn't find my answer in your online documentation! =

We encourage you to [submit a support ticket](https://pixeljarsupport.zendesk.com/hc/en-us/requests/new) where our support team will be happy to answer your specific questions.

== Changelog ==

= 1.0 =
* Launch!

= 1.0.1 =
* Removed non-standard ad sizes from the defaults
* Tracking filters can now be disabled by setting the constant ADSANITY_TRACK_THIS to true in wp-config.php
* Fixed default CSS for 125s and 140s

= 1.0.2 =
* Update far future date for ADSANITY_EOL to Dec 31, 2035 to keep within unix time limit for all hosts.

= 1.0.3 =
* WordPress 3.5 compatibility. The upgrade of the jQuery javascript library in 3.5 introduced an error in the jQuery UI Datepicker widget we have bundled. In 1.0.3, we're eliminating the redundancy of this external library. WordPress now ships with the datapicker functionality, so we're going to be using that moving forward.
* Fixed "Add featured image" and "Remove featured image" text being replaced by "Set banner ad image" and "Remove banner ad image" on non-ad post types
* Moved theme_support call to after_setup_theme hook

= 1.0.4 =
* Added adsanity_group shortcode
* Added TinyMCE buttons for both `[adsanity]` (single ad) and `[adsanity_group]` (group of ads)
* Introduced script debug mode
* Compressed all images to be as small as possible
* Fixed undefined index notices, silence is golden
* View/Click tracking now works in accordance with the time zone set it WordPress

= 1.0.4.1 =
* Fixed a bug where shortcode form file paths were incorrect on some installs

= 1.0.4.2 =
* Fixed a bug where a filter would apply an order to non-ad posts. props @bradyvercher

= 1.0.4.3 =
* Return the original request

= 1.0.4.4 =
* Fixed issue on multi-site or with in WordPress in a subdirectory where shortcode pop-ups would not pull in ads or ad groups (props Mike Dowling)

= 1.0.4.5 =
* Fixed issue on the custom stats page where the search filter wouldnwasn't working
* Show ads that are scheduled to be published in the future in sidebar widgets.

= 1.0.4.6 =
* Fixed javascript enqueuing errors (props Heather & Amy for the catch)

= 1.0.5 =
* Adds PressTrends anonymous usage tracking
* Fixes CSS issue when the plugin is not authorized
* Visual updates for WordPress 3.8 "Parker"
* Fixes Javascript issue when not authorized
* Added Notes field to create/edit screen to track data about ad units
* If theme doesn't support thumbnails, add thumbnail support globally
* Show size label instead of size value on ad list View
* Reworked menu and screen titles due to change in WordPress admin style
* Updated admin styles for WordPress 3.8

= 1.0.5.1 =
* Fixes an issue with the single ad widget where the clicking on the text label for the ad's checkbox would always select the first radio button. (props Taylor Johnson & Greg Yoder)

= 1.0.5.2 =
* Fixed timezone string functionality for sites using UTC with offsets
* Removed Presstrends tracking

= 1.0.6 =
* Interesting tidbit: 32-bit systems can only handle integers up to 2147483647. Timestamps are integers. Sometime in 2038, timestamps will stop being valid integers.
* Fixed: Custom stats engine bug. Users can now return results in any time range.
* Upgraded jQuery Flot library to 0.8.3
* Adds ability to export custom stats to csv

= 1.0.7 =
* Fixes a Javascript error on the Ad Edit page with the jQuery Flot library

= 1.0.7.1 =
* Fixes date formatting on charts

= 1.0.8 =
* Fixes a bug when a new plugin is added so that AdSanity doesn't throw an error.
* Changes plugin activation workflow so that AdSanity is functional without being authenticated, but won't receive automatic updates until it's properly authenticated.
* Fixes issue when rewrite rules aren't flushed after activation causing ads to not redirect to their destination properly.
* Adds helpful language to the ad edit screen for advanced HTML/text ad creation via network ads.
* Fixes issue where Tracking URL field was hidden from network ad code when it might have been useful for creating HTML/text ads.
* General code formatting updates for consistency.

= 1.0.8.1 =
* Removes PHP4 style constructors from widgets because WordPress 4.3 deprecated them
* Fixes an undefined notice in the tracking filters admin page

= 1.0.9 =
* Fixed CSS font issues
* Moved AdRoatate importer into free add-on to streamline codebase and improve UI
* Removed legacy files
* Added loads of inline documentation
* Improved internationalization (if you're interested in helping translate AdSanity, let us know!)
* Switched WP_Cache API to Transients API for greater performance compatibility
* Continued code improvements using PHP 5.3 compatible functionality
* Fixed Authorization Engine
* Updated styling for admin screens
* Fixed errors for static function calls on non static functions.

= 1.0.9.1 =
* Introduces ad theme templates for individual ads (ad-123.php)

= 1.0.9.2 =
* Fixes issue with new update engine to eliminate erroneous update notices (props @danielbleich)

= 1.0.9.3 =
* Add css widths for all default ad sizes to help with centering in some themes

= 1.1 =
* New: Introduces new default ad sizes based on the IAB standards
* New: Quick copy/paste buttons have been added to the ad list, ad edit, and ad group list areas in the dashboard
* New: Now fully set up for translations
* New: Added actions to ad display for easier modifying of the ad markup without using theme templates
* New: Added action to click tracking to allow custom code to be run on ad click
* Fix: In WordPress Multi-Site with subdirectories, we now prevent a sub-site from being created with the name "ads"
* Fix: Multiple widgets with the same ad group selected will now choose random ads for each group
* Fix: Removed globals during rewrite rule flushing
* Fix: Fixes permalink flushing issues on activation, deactivation, and upgrades for avoid 404s on clicked ads
* Change: Switches from using the WP_Cache API to the Transients API for caching for broader compatibility
* Update: Updates the plugin update library
* Update: Updates to admin markup for WordPress compatibility and accessibility
* Update: Updated default CSS to include fixed widths for fixed pixel based ad sizes
* Update: Code standardization and formatting
* Update: Improved inline documentation
* Update: Introduced data management system for future proofing updates
* Update: Overhauled the User Agent detection system
* Update: Performance boosts for queries
* Update: Removes dependency on Google APIs for external javascript libraries
* Security: Increased security by adding index files so directories can't be indexed in search engines
* Security: Increased security by making sure indivisual PHP files can't be loaded directly in a browser

= 1.1.1 =
* Fixed an issue with the automatic updater

= 1.2 =
* New: Settings screen framework for add-ons (nerdy developer stuff)
* New: Setting to toggle tracking of views and clicks per user role
* New: Setting to enable automatic inclusion of ads in content areas
* New: Filter for toggling tracking on or off
* Change: Removes clearfix from ad group widget and template tags
* Update: Cleans up old files from plugin
* Update: Adds 'widget' class to widgets so our default styling applies more consistently
* Update: Updates the browsecap file for improved bot detection
* Update: Updated the Update Engine and licensing APIs for add-ons
* Fix: Fixes issue where "December 31, 2035" would appear instad of "Forever"

= 1.2.1 =
* New: Filters for hiding ads programmatically
* New: Compatibility for hot swapping charts in Google Analytics Tracking add-on
* Update: Translation files have been updated to remove html
* Update: TinyMCE dialogs have been modernized
* Fix: Expiring and Expired Ads are now being correctly highlighted in ad list
* Fix: PHP Notice for undefined offset has been cleared
* Fix: Compatibility with Custom Ad Sizes add-on has been restored

= 1.2.2 =
* New: Advertiser role for upcoming add-on
* New: Actions and filters

= 1.3 =
* New: Ad Group Statistics Block
* New: Add-ons submenu
* New: Adds CSS Selectors (ID and Class) to Ad list
* Updated: Format of Statistics dashboard
* Updated: License UI bug
* Fix: PHP errors in Statistics ajax requests
* Fix: Better checks for stored ad sizes to eliminate errors

= 1.3.1 =
* Updated: Show missing Add-Ons on the add-ons page
* Fix: Undefined index notice
* Fix: Statistics were inverted
* Fix: Number formatting in Statistics
* Fix: Javascript errors when using a WYSIWYG editor on the front-end of a website - props @mizziness

= 1.4 =
* New: Added new classes to ad output, prefixed with "adsanity" for less collisions with theme styles
* New: Adds PHPUnit Tests to ensure code is functional prior to each release
* New: Adds new filters for error messaging if something goes wrong when a visitor clicks an ad
* New: Ads.txt settings
* New: Push Notification System
* Updated: Update Engine is now even more maintainable
* Updated: Additional data escaping and sanitization to improve security
* Updated: Theme template files have new improvements. If you've copied the theme templates into your theme in order to make customizations and you want to take advantage of the new error handling or hooks, you'll need to go through the same process again to pick up the new changes.

= 1.4.1 =
* Fix: Bug in Ads.txt upgrade process when checking for existing google ads
* Updated: Reduced notification check down to twice daily

= 1.4.2 =
* Fix: Fixes ads.txt functionality when a WordPress site isn't located at the root of the domain.
* Updated: Checks put in place for ads.txt file in the filesystem that rendered our settings useless

= 1.4.3 =
* Fix: Updates license activation endpoint to resolve requests being blocked by Sucuri Web Application Firewall

= 1.5.0 =
* New: Alignment for Ad Group shortcodes, template tags, and widgets
* New: Max-Width parameter for ads and ad groups to help with responsive styling
* New: Native HTML5 Ad Support
* New: Replaced browsecap bot detection library for piwik bot detection library. HUGE performance increase.
* Fix: Each shortcode, template tag, and widget now has it's own cache group to minimize duplicate ads
* Fix: Javascript errors when editing ads if ads or ad groups had special characters
* Fix: Set default values for ad groups if one wasn't selected
* Fix: Empty fields in Quick Edit are no longer empty
* Updated: New UI for automatic ad inclusion
* Updated: New UI for tracking role exclusions
* Updated: AdSanity branding - logos, icons, etc.
* Updated: General code cleanup and standardization
* Removed: PHP4 style constructors for better PHP7 support
* Removed: Old TinyMCE logic and UI

= 1.5.1 =
* New: Added Max Width settings for Random Ad widget
* New: Added Alignment settings for Random Ad widget
* Fix: Add spacing in between ads in a group
* Fix: Default value of alignnone for ad group alignment

= 1.6 =
* New: Gutenberg blocks for embedding single, random, and ad groups into content.
* New: Beaver Builder Modules for embedding single, random, and ad groups into Beaver Builder content.
* New: Alignment options for Ad Groups.
* New: Webpack-based build process.
* New: Ads are now exposed in the REST API for more embedding options.
* New: max-width parameter introduced to make sure that ads don't grow bigger than intended.
* Updated: Ad publish and expiration can now be set to a specific date and time of day.
* Updated: Automatic Inclusion rules have been improved to support multiple inclusion spots.
* Updated: New CSS to handle responsive ads more reliably.
* Updated: Reformatted legacy code to conform to WordPress Core standards.
* Updated: Caching has been reworked to provide better garbage collection so your wp-options table won't balloon.
* Fix: Eliminated TinyMCE-related Javascript errors.
* Fix: Publish and expiration dates now work in languages other than English.
* Fix: Views and Clicks will now display properly in reporting as expected.
* Fix: Resolved an issue with the "Select All" trigger in Custom Reports that would make it select more ads than were visible in some cases.
* Removed: Backwards compatibility for WordPress <3.9
* Stats: 124 commits, 15 branches, 6 months have occurred since our last update.

= 1.6.1 =
* Fix: Changes ordering of the results when searching for an ad in the single ad block.
* Fix: Allows .zip files to be uploaded for HTML5 ads on platforms with non-standard mime types.
* Fix: Prevents PHP errors when automatic inclusion is not set in the settings.
* Fix: Stops console errors in some cases when jQuery wasn't defined as expected.

= 1.6.2 =
* Updated: Makes cache keys more unique to resolve issues with the Conditional Ads add-on.
* Updated: The EDD plugin update engine.

= 1.7 =
* New: Adds visual preview of ad size when selecting an ad size.
* New: Display the total views in the past 7 days on the Ad Edit screen.
* New: New specific functionality for creating and styling text based ads.
* New: Lots of new developer hooks to provide for more customization.
* Update: All charts in AdSanity Core now use the Chart.js library. This means our charts are now responsive, yay!
* Update: For sites with lots of ads with similar names, more results will be returned in gutenberg blocks.
* Update: Default ad size is 300x250 and can be filtered to your preference with the 'adsanity_ad_size_default' filter.
* Update: Adds placeholder text to increase usability on Beaver Builder modules.
* Update: New icons for blocks, shortcodes, and Beaver Builder modules to improve contrast.
* Fix: Daily stats column now shows correct stats.
* Fix: Views and clicks on the Ad Edit screen now show the correct stats.
* Fix: Adds missing post_id variable for localization.
* Fix: HTML5 ads now render properly without mixed content issues.
* Fix: Changes ordering of the results when searching for an ad in the random ad and ad group blocks.
* Removed: Push notifications to remove the annoying notifications that would never go away.

= 1.7.1 =
* New: A brand new hook has been added to the support page for our add-ons to use.
* Update: When click through rates are really low, they weren't displaying. We've made it so that they'll be more likely to display.
* Fix: If you forget to place a value in the max width field, AdSanity will now display the ad.
* Fix: You can now delete rows in the automatic inclusion again.
* Fix: Searching for ad group names in the blockBlock UI wasn't working. It is now.

= 1.7.2 =
* Update: We have made it clear which add-ons you have installed and active under our Add-Ons page.
* Fix: We fixed a bug in our Rotating Ad Widget Add-On, but it needed a compatibility patch in AdSanity Core. Make sure to update AdSanity Core first!

= 1.8 =
* New: Added extensibility to automatic inclusion.
* New: Custom Divi Modules for tighter integration with Divi.
* New: Column in Ad Groups shows how many active ads are in each group.
* New: Developer actions/filters for AdSanity reporting screens.
* New: Allow shortcodes to be used in Text ads.
* New: Disable lazy loading for hosted ads.
* Update: WordPress 5.5 Compatibility.
* Update: Single Ad Widget now supports Max Width setting.
* Update: HTML5 Upload enhancements.
* Update: Speed improvements to the widget interface in the Customizer.
* Update: New selection interface for custom reports.
* Update: Security enhancements to align with WordPress coding standards.
* Update: Device Detector library has been updated.
* Update: Filter prioritization to remove extraneous columns in Ad list.
* Fix: Automatic inclusion no longer runs in the admin.
* Fix: Enhanced compatibility with page builders by switching from using WP_Query to get_posts.
* Fix: Compatibility fixes for Google Analytics Tracking Integration add-on.

= 1.8.1 =
* Fix: The "Headers already output" error has been fixed.

= 1.8.2 =
* Fix: Limited access to upload files to author users and above.

= 1.8.3 =
* Fix: WordPress 5.9 Block Editor compatibility.

= 1.9 =
* New: We've added a "Reset" button to delete (permanently) all internal statistics. This is a destructive operation and the data will no longer be retrievable.
* New: We have added Single ad, Random Ad, and Ad Group Elementor modules for more easily include AdSanity ads in your Elementor sites.
* New: Completely rewrote our metadata methodology to allow for alternative statistic storage which will increase performance overall.
* New: We added more developer oriented hooks to allow for further customization of the AdSanity platform.
* Update: More obvious visual indicators have been added while custom reports are loading.
* Fix: Compatibility with page builders and nested WordPress loops has been improved across the board.
* Fix: Wordpress 6.0 Full-Site Editing Compatibility.
