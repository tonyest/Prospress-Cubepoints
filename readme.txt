=== Plugin Name ===
Contributors: tonyest
Donate link: http://leonardsego.org
Tags: Prospress, Cubepoints, auctions, auction, points
Requires at least: Wordpress 2.0.2, Prospress 1.1, Cubepoints 3
Tested up to: 3.1
Stable tag: 0.1.1

Prospress-Cubepoints is a module for Cubepoints plugin that extends the functionality of the Prospress plugin to award cubepoints or run on cubepoints as an auction currency.

== Description ==

This plugin allows a [Prospress](http://prospress.org "Prospress auctions") auction site to use [Cubepoints](http://cubepoints.com "Cubepoints") as its trading currency. There is also the option for simple awarding of points for basic Prospress actions ( bid, win/sell ).

== Installation ==

This section describes how to install the plugin and get it working.

e.g.
1. Install and activate both [Prospress](http://prospress.org "Prospress auctions") & [Cubepoints](http://cubepoints.com "Cubepoints") plugins.
2. Upload `Prospress-Cubepoints` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. In the Admin backend of Wordpress under the Modules submenu of Cubepoints ( wp-admin : Cubepoints>>Modules ) activate 'Prospress Cubepoints'
5. The submenu 'Cubepoints Auctions' is now available in the Cubepoints group. You may alter any Prospress specific cubepoints options on this screen.
6. To activate 'Cubepoints mode' and use Cubepoints as the currency for your Prospress auction site simply select the Cubepoints currency 'CPS' from 'General Settings' under the Prospress menu group.

== Changelog ==

= 0.1.0 =
Release Version.

= 0.1.1 =
removed typecasting error affecting large bids and some admin panels.