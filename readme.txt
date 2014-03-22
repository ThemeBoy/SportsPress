=== SportsPress - flexible league management ===
Contributors: ThemeBoy
Tags: sports, sports journalism, teams, team management, fixtures, results, standings, league tables, leagues, reporting, themeboy, wordpress sports, configurable
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=support@themeboy.com&item_name=Donation+for+SportsPress
Requires at least: 3.8
Tested up to: 3.8.1
Stable tag: 0.6.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

SportsPress is a fully configurable sports plugin that seamlessly automates league, team, and player statistics. Currently in beta.

== Description ==

Created by the developers at [ThemeBoy](http://themeboy.com/) and featured on Softpedia as [Script of the Day](http://news.softpedia.com/news/Script-of-the-Day-SportsPress-409247.shtml), SportsPress is the ultimate all-in-one plugin for transforming your WordPress blog into a fully configurable league website.

Add schedules, results, league tables, player profiles and statistics to your team or league site with SportsPress. It uses core WordPress markup syntax and is designed to work with virtually every theme. Custom shortcodes, CSV importers, and several language translations are included.

= Features =
* Team Profiles
* League Tables
* Events (Fixtures & Results)
* Events Calendar
* Player Profiles & Statistics Per Position
* Player Lists
* Staff Profiles
* Season Archives
* Venue Information & Maps
* Statistics & League Table Columns Configuration
* Import Events, Teams, and Players from CSV Files

= Customizable =

League table columns, player statistics, and match results can be customized to fit any sport. Presets are available for some of the most popular sports including soccer, rugby, American football, Australian Rules football, baseball, basketball, cricket, and hockey.

= Available Languages =
* English (en_US)
* Czech - Čeština (cs_CZ)
* German - Deutsch (de_DE)
* Spanish - Español (es_ES)
* French - Français (fr_FR)
* Italian - Italiano (it_IT)
* Japanese - 日本語 (ja)
* Polish - Polski (pl_PL)
* Slovak – Slovenčina (sk_SK)
* Swedish - Svenska (sv_SE)

= Get involved =

Developers can contribute via the [SportsPress GitHub Repository](https://github.com/ThemeBoy/SportsPress/blob/master/CONTRIBUTING.md).

Translators can contribute new languages to SportsPress through [Transifex](https://www.transifex.com/projects/p/sportspress/).

== Installation ==

= Minimum Requirements =
* WordPress 3.8 or greater
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

= General Settings =

SportsPress comes with settings for some sports that you can apply by going to WordPress > Settings > SportsPress. By selecting a sport, presets will be applied to Events, League Tables, and Players.

= Event Settings =

Manage the results and outcomes you would like to track for each event.

Main Result is the default result that will be displayed in the admin list.

Results are the values that you want to keep track of and display on your event pages. In Association Football, for example, typical results are "1st half", "2nd half", and "Goals". For Baseball, you would have 9+ "Innings", "Hits", "Runs", and "Errors".

To add a new result, go to Settings > SportsPress > Results > Add New. Enter a name that you would like to be displayed in the column.

The "Key" is the variable name used in league table calculations and will be automatically generated when you create a new result, but you can also change this. The Order Attribute is the order that your result will be displayed among your other results.

= League Table Settings =

Manage the columns you would like to calculate and display in league tables.

Create a Title that you want to use as the column label, the Key will automatically be generated but you do have the option to change it here.

You can define an equation for a column by selecting the options from the dropdown menu. As an example, a “Wins” column should just be “W” from the dropdown. For more complex calculations, you'll need to select multiple elements to create your equations. For example, the equation for Pts in Association Football would be W x 3 + D.

"Events Played" accounts for the number of events that have an outcome selected. If an outcome has not been selected for an event, it will not be counted towards this number. For example if you played a friendly match and do not want to include the points towards your league table, do not select an outcome.

Results are tracked for each team. When you create equations, the left arrow (&larr;) represents “for” and the right arrow (&rarr;) represents “against.” For example, Goals &rarr; represents “Goals For.”

"Rounding" is the number of decimal points you want to round to at the end of a calculation.
Example: if the equation is "W &divide; Events Played" where W = 2 and Events Played = 3, setting "Rounding" to 3 will output "1.667" and rounding to 1 will output "1.7". Rounding is set to 0 by default, which returns a whole number (integer), in this case, "2".

Sort Order is for the way you want to sort your League Table. You can create and specify multiple sort orders. The first dropdown is the priority, and the second dropdown is the direction. If you want a secondary column, in the event of a tie, set another column to sort with priority "2".

As an example, in Association Football, Pts would be 1 descending, and GD would be 2 descending. This means that the leading team is the team with the most points, then the highest goal difference (GD).

Outcomes are very similar to results but you can only have one outcome per team per event. An outcome determines the ultimate result (win, draw, loss, etc.) of an event. Examples of outcomes are: W, D, L, and OT.

= Player Settings =

Manage the metrics and statistics you would like to track for each player.

Metrics are useful for variables like their height, weight, hobbies, etc. that will be displayed on player profile pages. When you create a new metric, remember to select the position(s) that the metric applies to or it will not show up in player profiles. Metrics are independent of leagues, seasons, and teams.

Statistics are for keeping track of the performance variables like goals, assists, yellow cards, and red cards. They are displayed on player profile pages, event pages, and player lists. Each player will have their own set of statistics for each event and league per season. You can choose whether to calculate the total or average of each variable by selecting from the “Calculate” dropdown menu. Be sure to select the position(s) that each statistic applies to so it shows up on the appropriate players' profile pages.

== Frequently Asked Questions ==

= Which sports does this plugin support? =

The plugin will support most team sports with a scoring system. You can customize the table columns and player statistics by going to WordPress > Settings > SportsPress. It includes presets for many of the popular sports, and you can also add your own.

= Will SportsPress work with my theme? =

Yes; SportsPress will work with any theme, but may require some styling to make it match nicely.

= Where can I report bugs or contribute to the project? =

Bugs can be reported either in our support forum or preferably on the [SportsPress GitHub repository](https://github.com/ThemeBoy/SportsPress/issues).

= Is this plugin ready for production? =

SportsPress is currently in beta and is undergoing testing. We are still actively making adjustments to the code, so we do not recommend using it until we officially leave the beta phase.

== Screenshots ==

1. Events admin.
2. Teams admin.
3. Players admin.
4. SportsPress Settings panel.
5. League Table widget settings.
6. Player List widget settings.
7. Events Calendar widget settings.
8. SportsPress Status dashboard widget.

== Changelog ==

= 0.6.1 =
* Feature - Display full event results on hover over main team result in admin.
* Feature - Add option to choose delimiter to use between team names in event titles.
* Tweak - Adjust text options to modify front-end only.
* Fix - Responsive league table output and styling.

= 0.6 =
* Feature - New events shortcodes: countdown, events-calendar, and events-list.
* Feature - New teams shortcode: league-table.
* Feature - New players shortcodes: player-list and player-gallery.
* Feature - Display available shortcodes in post edit screen.
* Feature - Add new settings page to change default text output.
* Feature - Add new section to permalinks settings to change post and term slugs.
* Tweak - Display teams as link list in admin page for league table teams columns.
* Tweak - Vertically align team logos in league table.
* Fix - Check if player belongs to leagues to avoid warnings in player profile.
* Fix - Total player statistics calculation in events.
* Fix - Responsive tables in event details and outcomes.
* Fix - Display players from all seasons or leagues when none have been created.

= 0.5 =
* Feature - Import tool added for importing events from CSV file.
* Feature - New post type Calendar added.
* Feature - League and Friendly format options added to events.
* Feature - List and Gallery format options added to player lists.
* Feature - Calendar and List format options added to calendars.
* Feature - Options added to turn on and off player list columns.
* Feature - Options added to player list widget to limit rows and display link to view all players.
* Feature - Options added to calendar widget to filter events.
* Feature - New widget Events List added.
* Feature - New widget Player Gallery added.
* Refactor - Use singular slugs for secondary post types.
* Tweak - Add tooltips to icons in admin table headings.
* Tweak - Style event results in admin events list. 
* Tweak - Separate event date and time in admin events list.
* Fix - Enable custom post type sorting in admin.
* Fix - Added check before displaying deleted posts in league tables and player lists.
* Fix - Adjust Select All filter in player lists and league tables.

= 0.4.3 =
* Feature - Enable selecting multiple outcomes per team per event.
* Tweak - Use icons in dashboard column labels for teams and roster.
* Tweak - Mark current team with check icon in admin player table.
* Fix - Check that selected columns are in array to avoid warning.
* Localization - Add Czech translation.
* Localization - Add Slovak translation.
* Localization - Add Polish translation.

= 0.4.2 =
* Feature - Enable selecting columns to display in single league table.
* Feature - Add options to limit rows, display logos, and display full table link.
* Feature - Add option to display national flags in player profiles.
* Refactor - Group Players and Staff under Roster menu group.
* Tweak - Reorder default player profile content.
* Tweak - Display player number before page title instead of metrics section.
* Tweak - Display player metrics as definition list instead of table.
* Fix - Check if static player list is array to avoid warning message.

= 0.4.1 =
* Tweak - Activate checkbox when all players are added to player list.
* Fix - Function date_diff added for PHP < 5.3.
* Localization - Swedish translation by jenszackrisson.

= 0.4 =
* Feature - SportsPress Status dashboard widget added to display number of events and countdown in admin.
* Feature - New dashboard menu icons.
* Feature - More intuitive player edit screen.
* Feature - Enable spreadsheet style keyboard navigation in admin data tables.
* Feature - Add hover action on league table team names to edit display name.
* Refactor - Remove min and max outcome options from column equation.
* Refactor - Change Rounding precision to default to 0.
* Tweak - Used jQuery Chosen for inputs where useful.
* Tweak - Prepend plugin name to widget titles.
* Tweak - Highlight settings in admin menu when adding new config post type.
* Tweak - Display Events as Schedule in admin sidebar menu.
* Tweak - Update widget descriptions.
* Tweak - Remove unused external class eqGraph.
* Tweak - Display sort order priority options for number of columns available.
* Fix - Apply table column rounding when precision is set.
* Fix - Display event results on events page when available.
* Fix - Check that event results are available before displaying a warning under certain conditions.
* Fix - Namespace eqEOS class to avoid conflict with other plugins.
* Localization - Use specific strings instead of dynamic ones for more accurate translations.
* Localization - Use generic strings where appropriate.
* Localization - Update German translation.
* Localization - Update Spanish translation.
* Localization - Update French translation.
* Localization - Update Italian translation.
* Localization - Update Japanese translation.

= 0.3.3 =
* Feature - Add default sorting options per player list.
* Feature - Add option to sort player list alphabetically by name or by default.

= 0.3.2 =
* Feature - Add England, Scotland, Northern Ireland, and Wales to countries selector.
* Feature - Enable searching for countries in dropdown.

= 0.3.1 =
* Feature - Import tool added for importing teams from CSV file.
* Tweak - Added option to select custom sport and enter sport name.
* Tweak - Display player number under photo in admin screen.
* Tweak - Positions, Leagues, and Seasons columns added to player import tool.
* Tweak - Styled SportsPress setup notice.
* Refactor - Sum changed to Total in player statistic calculation settings.

= 0.3 =
* Feature - Import tool added for importing players from CSV file.
* Feature - Add ability to select 
* Tweak - Display current team indicator in players admin screen.

= 0.2.10 =
* Fix - Team filtering in events, tables, players, and lists.
* Tweak - Display statistics for all league/season events played in player profiles and player lists.
* Tweak - Count events as played when in starting lineup or made substitution.
* Tweak - Display player metrics only when value is set.

= 0.2.9 =
* Feature - Ability to select players from all teams in player list.
* Fix - Decimal sorting in league tables and player lists.

= 0.2.8 =
* Feature - Add player list widget.
* Localization - Add German translations.
* Localization - Add Spanish translations.
* Localization - Add Italian translations.

= 0.2.7 =
* Feature - Select columns to display in league table widget.
* Tweak - Start league table positions at 1 instead of 0.

= 0.2.6 =
* Localization - Add French translations.
* Preset - Update soccer preset.

= 0.2.5 =
* Fix - Update deprecated function to prevent error in Player Lists.

= 0.2.4 =
* Feature - Display venue map on event page and venue archive.
* Fix - Add checks to prevent league table dividing by zero when no events have been played.
* Fix - Flush rewrite rules for taxonomies on activation.
* Tweak - Sort sports presets alphabetically by localized name.

= 0.2.3 =
* Feature - Enable selecting main event result.
* Feature - Add Last 5 counter to table columns.
* Localization - Update Japanese translations.
* Preset - Complete American Football preset.

= 0.2.2 =
* Feature - League Table widget added.
* Feature - Recent Events widget added.
* Feature - Future Events widget added.
* Feature - Countdown widget added.
* Fix - Syntax error fixed for PHP version 5.2 and below.
* Tweak - Editor section added to League Tables and Player Lists.

= 0.2.1 =
* Feature - Events Calendar widget added.
* Fix - Player settings table markup fixed.
* Tweak - Refine custom post type capabilities for user roles.

= 0.2 =
* Feature - Add option to select whether statistics are calculated as a sum or average.
* Feature - Enable pageview tracking for posts and custom post types.
* Feature - Responsive datatables.
* Fix - Add site admin capabilities for multisite.
* Fix - Force numerical sorting of number column.
* Tweak - Enable SportsPress content functions to be called without explicit ID.
* Tweak - Remove redundant admin menu links via filter.

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
