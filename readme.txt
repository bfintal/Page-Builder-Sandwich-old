=== Page Builder Sandwich ===
Contributors: bfintal
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=D2MK28E7BDLHC
Tags: page builder, builder, page, visual, editor, column, columns, shortcode, layout, table, nested, composer, build, post
Requires at least: 4.1
Tested up to: 4.2.1
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The native visual editor page builder. Empower your visual editor with shortcodes and drag and drop & column capabilities.

== Description ==

Page Builder Sandwich is a drag and drop page builder that enables you to easily layout your pages all within the familiar visual editor. Sandwich empowers WordPress' visual editor to let you manipulate your rendered shortcodes, and lets you layout your content without those infamous shortcode square brackets.

Visit our website at [pbsandwi.ch](http://pbsandwi.ch). We now have [awesome extensions](http://pbsandwi.ch/downloads) to further empower your page building experience.

= The page builder that: =

* allows you to visually create columns that actually look like columns,
* enables you to edit margins, paddings, borders and background colors and images of columns and whole rows,
* lets you preview your rendered content and shortcodes from your backend,
* adds page builder capabilities without hijacking your visual editor,
* gives you a nice interface to edit your favorite shortcodes: Jetpack, bbPress, BuddyPress, and plenty more;
* looks & feels native to WordPress,
* just works without any set ups and hassles, and
* you'll forget that you have it turned on since it blends so well with WordPress

**Requires PHP 5.3+**

One of the most frustrating things when creating content is layouting and writing them inside the visual editor.

Sure you can use **shortcodes columns**, but are not user friendly with all those distracting square brackets. Not to mention that column shortcodes are just displayed as a single column in the visual editor.

Existing **Page builders** are also great, but we didn't want to hijack the content editor and we wanted to find a way to do it using the existing visual editor that everyone knows. Page builders have a ton of code in them for creating brand new interfaces, but more code means more stuff which can go wrong in the future.

= Shortcodes =

We support the following shortcodes, widgets & elements:

* Archives (Widget)
* Audio (Add Media)
* Audio Playlists (Add Media)
* Button
* Calendar (Widget)
* Categories (Widget)
* Contact Form (Jetpack)
* Custom Menu (Widget)
* Display WordPress Posts (Jetpack)
* Embed
	* Supported URLs include Animoto, Blip, CollegeHumor, DailyMotion, Flickr, FunnyOrDie, Hulu, Imgur, Instagram, iSnare, Issuu, Meetup, EmbedArticles, Mixcloud, Photobucket, PollDaddy, Rdio, Revision3, Scribd, SlideShare, SmugMug, SoundCloud, Spotify, TED, Vimeo, Vine, WordPress.tv and YouTube
	* If you have Jetpack's Shortcode Embeds module enabled, you can also embed Facebook, Github Gist, Google+, and Medium links
* Facebook Like Box (Jetpack)
* Gravatar Profile (Jetpack)
* Google Map (Jetpack)
* HTML5 Video - Self-hosted
* HTML5 Video - Remote-hosted
* Images (Add Media)
* Image Galleries (Add Media)
* Meta (Widget)
* Pages (Widget)
* Portfolio (Jetpack)
* Progress bar
* Recent Comments (Widget)
* Recent Posts (Widget)
* RSS (Widget)
* RSS Links (Jetpack)
* Search (Widget)
* Subscribe (Jetpack)
* Tag Cloud (Widget)
* Toggle / FAQ
* Video (Add Media)
* Video Playlists (Add Media)
* *More being created. Have a suggestion? [Let us know here](https://github.com/gambitph/Page-Builder-Sandwich/issues/new)*

Third-party shortcodes - these show up when the necessary plugin is activated

* bbPress
* Contact Form 7
* Events Manager
* Jetpack
* MailChimp for WP
* Ninja Forms
* *Have a plugin suggestion? [Let us know here](https://github.com/gambitph/Page-Builder-Sandwich/issues/new)*

*Contributions are welcome at the [Github repository](https://github.com/gambitph/Page-Builder-Sandwich/)*

= Contributing =

**We're looking for help creating shortcodes to be included in PB Sandwich. Check out the [Developer Resources](https://github.com/gambitph/Page-Builder-Sandwich/wiki) for more information.**

Report bugs and help out in the code from the [Github repository](https://github.com/gambitph/Page-Builder-Sandwich/)

== Installation ==

1. Head over to Plugins > Add New in the admin
2. Search for "Sandwich"
3. Install & activate the plugin
4. Create your content:
	* Click the "Columns" button while editing your posts and pages to insert your columns, or
	* Click on "Add Post Element" to insert shortcodes
	* Some third-party plugin shortcodes are supported, they will appear if we detect that the plugin is activated
5. Click, drap, drop, edit away on your visual editor

== Screenshots ==

1. Drag and drop stuff
2. You can do this while editing posts and pages
3. You then get these columns as your output
4. Shortcodes
5. Creating a registration/login page for bbPress

== Frequently Asked Questions ==

= I want more features! =

We now have [awesome extensions](http://pbsandwi.ch/downloads) to further empower your page building experience.

= I just installed Sandwich. Where is it? =

Go ahead and edit a post or page using the visual editor. You might not notice it, but your visual editor is now a page builder *(mic drop)*.

= How do I add columns? =

We have added a **column** button in your visual editor (first row of buttons, right-most), click on that to create columns

= How do I add shortcodes? =

Click on **Add Media**, then **Insert Post Element**. Click on one, edit its settings, then hit **insert**. Some shortcodes are only available if you have Jetpack activated.

As of v0.9+, we've added an **Add Post Element** button beside the **Add Media** button for easy access.

= You don't have shortcode _____! I need it! =

*Suggest it to us.* We're thinking of more ways to further expand the capabilities of Sandwich, [suggest it in our Github Repo](https://github.com/gambitph/Page-Builder-Sandwich/issues/new)

= Do I need Jetpack installed to make this work? =

*Nope,* although if you have it activated, then additional widgets and shortcodes will become available in Sandwich.

= I'm getting a `Parse error: syntax error, unexpected T_FUNCTION error` =

Page Builder Sandwich requires PHP 5.3 or higher. Some of the stuff we use require PHP 5.3, so unfortunately we cannot support lower versions.

= WTF, I hate tables! Why are tables used to display columns? =

*Be calm!* Tables are **only** used in the backend / visual editor. In the front end, those tables will be **gone** and will be replaced by good ol' clean divs that use Bootstrap's grid to display your content.

Why did we do it this way? Because table editing is handled pretty well by TinyMCE. Instead of re-inventing the wheel, we simply used what TinyMCE already does well and extended that into what we needed.

**So once again, I repeat, no tables are present in the front end.**

= Where can I report bugs? =

You can do so in our [Issue Tracker](https://github.com/gambitph/Page-Builder-Sandwich/issues)

= I want to contribute! =

Take a look at the [Page Builder Sandwich GitHub Repository](https://github.com/gambitph/Page-Builder-Sandwich/), star it, do a pull request, we're grateful for any help

(View more FAQs)[https://github.com/gambitph/Page-Builder-Sandwich/wiki/FAQ]

== Upgrade Notice ==

== Changelog ==

= 1.1.1 =

* Fixed bug that prevented newly created columns/rows in new pages from being dragged
* Updated Shortcake that fixes a bunch of errors, including:
* Fixed bug that changed shortcodes into their loading state when the same shortcode attributes were used
* Moved `content` attributes to `inner_content` attributes due to a change in Shortcake
* Fixed handling of column creation when a shortcode was currently selected

= 1.1 =

* WordPress 4.2 compatibility
* Selecting shortcodes then creating a column now correctly puts them inside the new column
* Modal window now resizes correctly for colorpickers
* Fixed insert via URL
* Now properly stops ctrl/option+backspace from deleting a single column
* Fixed bug that prevented clicks on columns
* You can now drag videos right after dragging them
* Fixed bug where row styles got deleted when changing a row layout
* Fixed bug that modified the background image path when opening an edit modal window
* Added unit tests to ensure future builds
* Button alignment attribute now works properly

= 1.0.2 =

* Fixed duplicate class declaration error

= 1.0.1 =

* Fixed invalid header error which was encountered when activating the plugin right away
* Fixed some tooltip typos

= 1.0 =

* A waaay better dragging and dropping experience
* Sped up & optimized dragging experience, previously, more content meant more lag when dragging
* [New Toolbar API](https://github.com/gambitph/Page-Builder-Sandwich/wiki/Adding-Toolbar-Buttons)
* Added alignment buttons for rows for greater content control, now you can add items side by side
* Added Jetpack Google Map shortcode (although existing googlemaps shortcodes will not work because of an existing issue)
* Added clone toolbar button for images
* Added a page break button in TinyMCE for convenience
* Security fixes for CWE-200
* Images now won't make columns wider
* Fixed video sizes while in the editor
* Fixed issue where empty paragraph tags were added in the frontend
* A ton of stability & bug fixes

= 0.11 =

* Added column & row style editing capabilities!
* Significantly enhanced page builder dragging experience
* Introduced more stability in the page builder
* Added Events Manager shortcodes
* New column toolbar
* New progress bar shortcode
* New button shortcode

= 0.10 =

* New installation welcome screen
* Added new shortcodes (accessible if these plugins are activated):
	* bbPress
	* MailChimp for WP
	* Ninja Forms
	* WordPress SEO by Yoast
* Minor speed enhancements
* Updated Shortcake to include a new shortcode search box

= 0.9.1 =

* Brought back descrption in the embed shortcode

= 0.9 =

* Added "Add Post Element" button beside the "Add Media" button for easier access
* Added new shortcodes:
	* Contact Form 7 shortcode (only accessible if Contact Form 7 is activated)
	* Self hosted HTML5 video
	* Remote hosted HTML5 video
* Added FitVids for embedded videos to auto-resize videos in the frontend
* Updated Shortcake

= 0.8 =

* Added new shortcodes/widget. All of these are accessible via Add Media > Insert Post Element
	* Media/URL Embed
	* Twitter timeline (Jetpack)
* Updated Shortcake
* Fixed: editor column styles get overridden
* Fixed: Bootstrap conflicting with other non-sandwich elements

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
