# PB Sandwich
*Turn your typical WordPress visual editor into a super charged visual-editor-page-builder hybrid*

One of the most frustrating things when creating content is layouting and writing them inside the visual editor.

Sure you can use **shortcodes columns**, but are not user friendly with all those distracting square brackets. Not to mention that column shortcodes are just displayed as a single column in the visual editor.

Existing **Page builders** are also great, but we didn't want to hijack the content editor and we wanted to find a way to do it using the existing visual editor that everyone knows. Page builders have a ton of code in them for creating brand new interfaces, but more code means more stuff which can go wrong in the future.

# Goal

The goal is to create a page builder that
* Feels and acts *native* and part of WordPress
* Leaves *most* of the content working fine event if the plugin is deactivated or deleted
* Lazy, no settings pages specific for the plugin

# What you can do with it

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

#### Shortcodes

Aside from the shortcodes and embeds listed above, we've included other shortcodes as well:

* *Still being created*

*Contributions are welcome at the [Github repository](https://github.com/gambitph/Page-Builder-Sandwich/)*

# Preview

Drag and drop stuff
![Drag and Drop](https://raw.githubusercontent.com/gambitph/Page-Builder-Sandwich/master/preview/drag-and-drop.jpg)

It lets you do this while editing posts and pages:
![Visual Column Editing](https://raw.githubusercontent.com/gambitph/Page-Builder-Sandwich/master/preview/visual-editor.jpg)

You'll get this as your output:
![Column Results](https://raw.githubusercontent.com/gambitph/Page-Builder-Sandwich/master/preview/frontend.jpg)

# Usage

1. Download the zip from the right side of this page
2. Install & activate the plugin
3. Create/edit a page or post
4. Create shortcodes (via shortcake), add a media or create a column

# Contributing

* [Report bugs](https://github.com/gambitph/Page-Builder-Sandwich/issues) that you may encounter, this is quite new so I'm betting there're some
* We're looking to add common shortcodes via Shortcake to be included in the page builder. [Pull requests on new shortcodes](https://github.com/gambitph/Page-Builder-Sandwich/pulls) are very welcome.
