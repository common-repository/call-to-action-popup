=== Call To Action Popup ===
Contributors: cactusthemes, lampd
Collaborators: cactusthemes, lampd
Donate link: 
Tags: advertise, light-box, Mailing list pop-up, marketing, pop, pop-up, popup, promotion, Responsive Popup,cta, action, call to action
Requires at least: 4.0
Tested up to: 5.1.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Call To Action Popup – it’s an incredibly easy to use and completely free popup plugin for WordPress.

== Description ==

Call To Action Popup – it’s an incredibly easy to use and completely free popup plugin for WordPress.

= Main Features: =
* Create targeted marketing campaigns, keep customers from leaving your site, and build your mailing list easily and quickly with Call To Action Popup.
* Create an unlimited number of popups with different looks and configurations.
* Record number of PopUp displayed
* Record number of Subscribed

= Binding Event: =
To record number of Subscribed, we have created Binding Event `subscribed`, you can use JavaScript to call to that Event on JavaScript event.
Example for event `click`:

	$( "#foo" ).on( "click", function() {
		$( "#cactus-popup" ).trigger("subscribed");
	});

= Shortcode: =
	[cta_popup id=""]

= Parameter required: =
* `ID`: ID off PopUp post in PopUp Custom Post Type.

== Installation ==

1. Upload `call-to-action-popup.zip` to the `/wp-content/plugins/` directory and extract it.
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==

== Screenshots ==
1. PopUp Settings
2. PopUp Custom Post Type

== Changelog ==
= 1.0.2 =
* Tested up to WordPress 5.1.1
* Add Settings link in Plugin action link

= 1.0.1 =
* Minor bug fixes
* Add option Custom CSS
* Add option Custom Class

= 1.0 =
* First Release