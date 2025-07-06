=== Neos Connector for Fakturama ===
Contributors: neo2oo5, boarschti42
Donate link: https://www.paypal.com/donate/?hosted_button_id=849ALP7AUZSK4
Tags: fakturama, faktura, faktura connector, fakturama connector, faktura bridge, fakturama bridge, woocommerce faktura, woocommerce
Requires at least: 4.0
Tested up to: 6.8.1
Stable tag: 0.1.2
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html




== Description ==

**Deutsch:**

Dieses Plugin basiert auf dem NEOS-Connector für Fakturama. Weitere Informationen zum Original-Plugin finden sie auf [www.neosuniverse.de](https://www.neosuniverse.de)

Fakturama Connector importiert Produkte und Bestellungen von Woocommerce zu Fakturama.

Wenn dir Neos Connector for Fakturama gef&auml;llt und unser Plugin seine Arbeit gut macht, w&auml;re es super wenn du eine Rezension &uuml;ber Neos Connector for Fakturama auf Wordpress.org schreiben w&uuml;rdest. **Danke f&uuml;r deine Unterst&uuml;tzung!**


**English:**

This plugin is based on NEOS-Connector for Fakturama. More information about the original is available at [www.neosuniverse.de](https://www.neosuniverse.de)

Neos Connector for Fakturama imports Products and Orders from Woocommerce to Fakturama.

If you like Neos Connector for Fakturama and our Plugin does a good job it would be great if you would write a review about Neos Connector for Fakturama on WordPress.org. **Thank you for your support!**

##Hilfe / Help

### 1. Neu installieren / Reinstall

1. Ins **WP DASHBOARD** einloggen
2. Klick auf den "Plugins" link
3. Suche "Neos Connector for Fakturama" und deaktiviere es.
4. L&ouml;sche das "Neos Connector for Fakturama" Plugin
5. Scrolle an den Anfang der Seite und klicke auf "Installieren"
6. Tippe in die Suchbox rechts oben "Neos Connector for Fakturama" ein
7. Nun solltest du das Plugin sehen. Klicke auf installieren und anschlie&szlig;end auf aktivieren (die gleiche Schaltfl&auml;che)

#

1. Log in the **WP DASHBOARD**
2. Click on the "Plugins" link
3. Look for "Neos Connector for Fakturama" and deactivate it.
4. Delete "Neos Connector for Fakturama Plugin"
5. Scroll the page to the begin an klick on the "add new"
6. Go to the search box and type in "Neos Connector for Fakturama"
7. Now you should see the Plugin. Klick on install then on activate (same Button)

### 2. Forum

Du findest auch Hilfe im offiziellen Forum von [Fakturama](http://forum.fakturama.info/index.php)

You can also find help in the official Forum of [Fakturama](http://forum.fakturama.info/index.php)


== Installation ==

1. Upload the `Neos Connector for Fakturama` folder to the `/wp-content/plugins/` directory or install directly through the plugin installer.
2. Activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer.

== Frequently Asked Questions ==

- My Plugin does not work: Check settings in Fakturama. Ensure DEBUG mode is set to off. 
- Whats the URL to use? https://<url>/wp-json/neos/v2/fakturama


== Upgrade Notice ==
- 0.1.0: It is required to change connection settings in Fakturama since the API was changed to REST instead of admin-ajax.


== Changelog ==

= 0.1.2 =
- Fixes to work with latest beta of fakturama
- Debug output to errorlog removed

= 0.1.0 =
- Current version of Fakturama (2.2.0) works with this plugin
- API changed to Wordpress REST-API

= 0.0.14 =
-Fix Timezone error

= 0.0.13 =
-Fix Update error in ncff_reset_notice_after_update action
-Hotfix call undefined function plugin data
-Error notice missing woocommerce Plugin


= 0.0.12 =
-Add German/English translation
-Fix get_plugin_data error in *-xml.php
-Revised readme.txt

= 0.0.11 =
-Fix Bug in setstate function
-Structure Changes

= 0.0.10 =
--

= 0.0.9 =
-Fix XML well formed Error

= 0.0.8 =
-Fix UTF-8 Encoding Error
-Fix XML Entitiy Error

= 0.0.7 =
-


= 0.0.6 =
fix wrong payment calculating
fix order id error

= 0.0.5 =
fix timezone option error

= 0.0.4 =
fix install error

= 0.0.3 =
xml error fix - missing products element
delete die() from neosconnectorforfakturama.php


= 0.0.2 =
bug fixes

= 0.0.1 =
release

== License ==



    Neos Connector for Fakturama
    Copyright (C) 2016  Kevin Bonner

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, see <http://www.gnu.org/licenses/>.



