=== Page Builder Sandwich ===
Contributors: bfintal
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=D2MK28E7BDLHC
Tags: page builder, builder, page, visual, editor, column, columns, shortcode, layout, table, nested, composer, build, post
Requires at least: 3.9
Tested up to: 4.1.1
Stable tag: 0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The native visual editor page builder. Empower your visual editor with drag and drop & column capabilities.

== Description ==

= Call it PB Sandwich for short =

One of the most frustrating things when creating content is layouting and writing them inside the visual editor.

Sure you can use **shortcodes columns**, but are not user friendly with all those distracting square brackets. Not to mention that column shortcodes are just displayed as a single column in the visual editor.

Existing **Page builders** are also great, but we didn't want to hijack the content editor and we wanted to find a way to do it using the existing visual editor that everyone knows. Page builders have a ton of code in them for creating brand new interfaces, but more code means more stuff which can go wrong in the future.

*Check out the screenshots to see how PB Sandwich works.*

= What it does =

Extends your visual editor to include page builder capabilities

= The Goal =

The goal is to create a page builder that:

* Feels and acts *native* and part of WordPress
* Lazy, no settings pages specific for the plugin

= What can you do with it =

* Create editable, nestable columns
* Edit text and content using the TinyMCE visual editor like how you normally would
* Live working preview of embedded content (e.g. put in a YouTube video and you can play it while still being able to drag it)
* Drag and drop TinyMCE views into other locations. Works with:
	* WordPress:
		* Image galleries
		* Videos
		* Video playlists
		* Audio
		* Audio playlists
	* Jetpack:
	  	* Video embeds *via Add Media > Insert From URL*
			* Dailymotion embed
			* Flickr videos
			* TED talks embed
			* Vimeo embed
			* Vine embed
			* Youtube embed
		* Audio embeds *via Add Media > Insert From URL*
			* Rdio embeds
			* SoundCloud embeds
			* Spotify embeds
		* Other embeds *via Add Media > Insert From URL*
			* Github Gists
	* All shortcake post elements
* Clone shortcodes/embeds & columns
* Create different shortcodes
* Working undo

= Shortcodes =

Shortcodes are added by clicking on **Add Media > Insert Post Element**.

We support the following shortcodes & widgets:

* Archives (Widget)
* Calendar (Widget)
* Categories (Widget)
* Contact Form (Jetpack)
* Custom Menu (Widget)
* Display WordPress Posts (Jetpack)
* Facebook Like Box (Jetpack)
* Gravatar Profile (Jetpack)
* Meta (Widget)
* Pages (Widget)
* Portfolio (Jetpack)
* Recent Comments (Widget)
* Recent Posts (Widget)
* RSS (Widget)
* RSS Links (Jetpack)
* Search (Widget)
* Subscribe (Jetpack)
* Tag Cloud (Widget)
* Toggle / FAQ
* *More being created. Have a suggestion? [Let us know here](https://github.com/gambitph/Page-Builder-Sandwich/issues/new)*

*Contributions are welcome at the [Github repository](https://github.com/gambitph/Page-Builder-Sandwich/)*

= Contributing =

**We're looking for help creating shortcodes to be included in PB Sandwich. Check out the [Developer Resources](https://github.com/gambitph/Page-Builder-Sandwich/wiki) for more information.**

Report bugs and help out in the code from the [Github repository](https://github.com/gambitph/Page-Builder-Sandwich/)

== Installation ==

1. Head over to Plugins > Add New in the admin
2. Search for "PB Sandwich"
3. Install & activate the plugin
4. Create your content:
	* Click the "Columns" button while editing your posts and pages to insert your columns, or
	* Click on the "Add Media" button to insert shortcodes
5. Click, drap, drop, edit away on your visual editor

== Screenshots ==

1. Drag and drop stuff
2. You can do this while editing posts and pages
3. You then get these columns as your output
4. Shortcodes

== Frequently Asked Questions ==

= WTF, I hate tables! Why are tables used to display columns? =

*Be calm!* Tables are **only** used in the backend / visual editor. In the front end, those tables will be **gone** and will be replaced by good ol' clean divs that use Bootstrap's grid to display your content.

Why did we do it this way? Because table editing is handled pretty well by TinyMCE. Instead of re-inventing the wheel, we simply used what TinyMCE already does well and extended that into what we needed.

**So once again, I repeat, no tables are present in the front end.**

= Where can I report bugs? =

You can do so in our [Issue Tracker](https://github.com/gambitph/Page-Builder-Sandwich/issues)

= I want to contribute! =

Take a look at the [Page Builder Sandwich GitHub Repository](https://github.com/gambitph/Page-Builder-Sandwich/), star it, do a pull request, we're grateful for any help

== Upgrade Notice ==

== Changelog ==

= 0.7 =

* Added a lot of shortcodes/widgets. All of these are accessible via Add Media > Insert Post Element
	* Archives
	* Calendar
	* Categories
	* Custom Menu
	* Meta
	* Pages
	* Recent Comments
	* Recent Posts
	* RSS
	* Search
	* Tag Cloud
	* Display WordPress Posts (Jetpack)
	* Facebook Like Box (Jetpack)
	* Gravatar Profile (Jetpack)
	* RSS Links (Jetpack)
	* Subscribe (Jetpack)

= 0.6 =

* Fixed: Shortcake not initializing properly

= 0.5 =

* New shortcode: Toggle
* Shortcodes with dependencies are now grayed out instead of hidden
* Fixed: columns did not display side-by-side in some cases
* Fixed: bug introduced in v0.4 where views cannot be dragged and clone buttons were not appearing
* Updated translations
* Code cleanups

= 0.4 =

* Included our very first supported Jetpack shortcode: contact form
* More modular code
* Included Shortcake
* Fixed: dragging in Firefox
* Fixed: embeded video widths in Firefox
* Fixed: shortcodes with iframes drag when clicked

= 0.3 =

* Fixed bug where images were being removed inside columns
* Removed page & post type restriction

= 0.2 =

* Now uses namespaced Bootstrap classes for columns

= 0.1 =

* First release
