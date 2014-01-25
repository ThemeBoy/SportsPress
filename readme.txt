=== SportsPress ===
Contributors: themeboy
Tags: sports, sports journalism, teams, team management, fixtures, results, standings, league tables, leagues, reporting, themeboy, wordpress sports, configurable
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=support@themeboy.com&item_name=Donation+for+SportsPress
Requires at least: 3.5
Tested up to: 3.8
Stable tag: 0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

SportsPress is a flexible sports management plugin that adds team management functionality to WordPress. Currently in beta for internal testing.

== Installation ==

= Minimum Requirements =
* WordPress 3.5 or greater (WordPress 3.8 recommended)
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

= Automatic Installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t even need to leave your web browser. To do an automatic install of SportsPress, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New.

In the search field type “SportsPress” and click Search Plugins. Once you’ve found our sports plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking Install Now. After clicking that link you will be asked if you’re sure you want to install the plugin. Click yes and WordPress will automatically complete the installation.

= Manual Installation =

The manual installation method involves downloading our sports plugin and uploading it to your webserver via your favorite FTP application.

1. Download the plugin file to your computer and unzip it
2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation’s wp-content/plugins/ directory.
3. Activate the plugin from the Plugins menu within the WordPress admin.

= Upgrading =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

If on the off-chance you do encounter issues with the event/team/player/staff pages after an update you simply need to flush the permalinks by going to WordPress > Settings > Permalinks and hitting 'save'. That should return things to normal.

= Settings =

SportsPress comes with settings for some sports that you can apply by going to WordPress > Settings > SportsPress. You can also add your own table columns, event results, and player statistics by clicking on the tabs on this screen.

== Frequently Asked Questions ==

= Which sports does this plugin support? =

The plugin will support most team sports with a scoring system. You can customize the table columns and player statistics by going to WordPress > Settings > SportsPress. It includes presets for many of the popular sports, and you can also add your own.

= Will SportsPress work with my theme? =

Yes; SportsPress will work with any theme, but may require some styling to make it match nicely.

= Where can I report bugs or contribute to the project? =

Bugs can be reported either in our support forum or preferably on the [SportsPress GitHub repository](https://github.com/ThemeBoy/SportsPress/issues).

= SportsPress is awesome! Can I contribute? =

Yes you can! Join in on our [GitHub repository](http://github.com/ThemeBoy/SportsPress/) :)

= Is this plugin ready for production? =

SportsPress is currently in beta and is undergoing testing. We are still actively making adjustments to the code, so we do not recommend installing it on a live server until we officially leave the beta phase.

== Screenshots ==

1. Events admin.
2. Teams admin.
3. Players admin.
4. SportsPress Settings panel.

== Changelog ==

= 0.2 =
* Feature - Add option to select whether statistics are calculated as a sum or average.
* Feature - Enable pageview tracking for posts and custom post types.
* Feature - Responsive datatables.
* Fix - Add site admin capabilities for multisite.
* Fix - Force numerical sorting of number column.
* Tweak - Enable SportsPress content functions to be called without explicit ID.
* Tweak - Remove redundant admin menu links via filter.

= 0.1.10 =
* Documentation - Add Installation, FAQ and Screenshots to assets.

= 0.1.9 =
* Fix - Calculation dependencies.

= 0.1.8 =
* Tweak - Update subversion.

= 0.1.7 =
* Feature - Enable selecting venues to use uploaded images.

= 0.1.6 =
* Tweak - Activate per post type permissions.
* Tweak - Give admin all permissions for custom posts.

= 0.1.5 =
* Tweak - Remove flag images to lighten download size.

= 0.1.4 =
* Fix - All Plugin-Check warnings.
* Tweak - Split templates into files in subdirectory.
* Tweak - Simplify gettext filters.

= 0.1.3 =
* Fix - Style conflict with Foundation framework in table columns.
* Feature - HTML output added to custom post types with data table sorting.
* Feature - Enable metrics and statistics per player position.
* Feature - Save team played per season per league in player edit screen.
* Feature - Give teams the option to show and hide past seasons.
* Feature - Venues, Seasons, and Leagues added as taxonomies.
* Feature - Add L10 counter to report last 10 outcomes.
* Feature - Add STRK counter to report current outcome streak.
* Localization - Add country names in Czech, German, Spanish, French, Italian, Japanese, Polish, Russian, and Slovak.

= 0.1.2 =
* Tweak - Use custom post types for metrics and statistics configuration.
* Feature - Display HTML tables when viewing League Table and Player List post types.
* Feature - Link player and team names to single post pages.
* Feature - Register default configuration for soccer.
* Feature - Sort league table and player list by priority.

= 0.1.1 =
* Tweak - Update description.

= 0.1 =
* Alpha release for first look and testing.
