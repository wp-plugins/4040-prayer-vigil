=== 40/40 Prayer Vigil ===
Contributors: danielsummers
Tags: prayer, guide
Requires at least: 3.2
Tested up to: 3.4.1
Stable tag: 2012.0

This plugin provides the daily or hourly prayer guides for the 40/40 Prayer Vigil (English) and 40/40 Campaña de
Oración (Español).

== Description ==

This plugin utilizes a web service from DJS Consulting (http://services.djs-consulting.com/FortyForty) to display the
current day or hour's suggested prayer guide for the 40/40 Prayer Vigil.  The "40/40" part means that you can pray for
either 40 days or 40 hours.  For days, the 2012 vigil runs from September 26th through November 4th; for hours, it runs
from 4pm on November 2nd through 7am on November 4th.

This plugin utilizes your blog's local date/time to display the current guide as the vigil is proceeding.  It caches
the results, so there should be next to no extra work for the page displays on your blog.  You can select the language,
the version that is used for the Scripture links, and the number of days before and after the actual vigil that the
widget will be displayed.

== Installation ==

Unzip the archive, and upload the "4040-prayer-vigil" directory to your wp-content/plugins directory.  Once activated,
the widget will be available for use, and the options page should appear under the "Settings" menu.

This plugin requires PHP 5, libxml, and a server that can make outbound web (HTTP) requests and does not block
services.djs-consulting.com.

== Frequently Asked Questions ==

= How Can I Know What It Looks Like Before the Vigil Begins? =

On the options page, there are settings that will force the guide to display for a particular date or date/time, or for
a day or hour number.  Select one of those to set, and you can see the results.  Be sure to clear them when you are
done, so you won't miss the actual guides once the vigil begins!

= Can I Change the Styling? =

Yes.  The div tag containing the guide has the id attribute of "FortyFortyPrayerGuide".  You can create CSS based on
that ID, and it will style the guide the way you like.  To see the tags to style, you can enable the debugging settings
described above and view the source, or you can go to
http://services.djs-consulting.com/FortyForty/PrayerGuide/html/2012/en/ESV/day/1 for a sample.

== Changelog ==

= 2012.0 =

Initial release

