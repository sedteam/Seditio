<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ratings/ratings.setup.php
Version=185
Updated=2026-feb-18
Type=Plugin
Author=Seditio Team
Description=Ratings plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ratings
Name=Ratings
Description=Ratings for pages, gallery, users and polls
Version=2.0
Date=2026-feb-18
Author=Seditio Team
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
maxratsperpage=01:select:5,10,15,20,25,30,40,50:30:Ratings per page in admin
maxstars=02:select:3,5,10:5:Maximum number of stars
ratingstep=03:select:0.5,1:0.5:Rating step
css=04:radio:0,1:1:Include plugin CSS
starsize=05:select:16,20,24,28,32,36,40,44,48,52,56,60:20:Star size (px)
starshape=06:select:straight,rounded:straight:Star shape
strokewidth=07:string::0:Stroke width
strokecolor=08:string::#333:Stroke color
emptycolor=09:string::lightgray:Empty star color
hovercolor=10:string::orange:Hover color
activecolor=11:string::gold:Active star color
ratedcolor=12:string::crimson:Rated star color
usegradient=13:radio:0,1:1:Use gradient for active stars
gradient_start=14:string::#FEF7CD:Gradient start color
gradient_end=15:string::#FF9511:Gradient end color
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
