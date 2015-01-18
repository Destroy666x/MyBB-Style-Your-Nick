<?php

/*
Name: Style Your Nick
Author: Destroy666
Version: 1.0
Requirements: Plugin Library, PostgreSQL 9.1
Info: Plugin for MyBB forum software, coded for versions 1.8.x (may also work in 1.6.x/1.4.x after some changes).
It allows users to change their nickname styling in the User CP (based on group permissions).
1 core edit, 1 new database table, 14 new database columns, 8 new templates, 1 template edit, 10 new settings
Released under GNU GPL v3, 29 June 2007. Read the LICENSE.md file for more information.
Support: official MyBB forum - http://community.mybb.com/mods.php?action=profile&uid=58253 (don't PM me, post on forums)
Bug reports: my github - https://github.com/Destroy666x

© 2015 - date("Y")
*/

$l['style_your_nick'] = 'Style Your Nick';
$l['style_your_nick_info'] = 'Allows users in chosen usergroups to change their nickname style.';
$l['pluginlibrary_missing'] = '<strong>Note:</strong> Plugin Library is needed to create/delete new templates and edit core files in this plugin. You can download it from <a href="https://github.com/frostschutz/MyBB-PluginLibrary/archive/master.zip">here</a>.';
$l['core_changes_error'] = "The required core file changes couldn't be completed.";

$l['style_your_nick_settings'] = 'Settings for the Style Your Nick plugin.';
$l['style_your_nick_transparent'] = 'Allow transparent colors?';
$l['style_your_nick_transparent_desc'] = 'Set to Yes to enable the predefined transparent and RGBA colors.';
$l['style_your_nick_max_font'] = 'Maximal Font Size';
$l['style_your_nick_max_font_desc'] = 'Enter a number representing the maximal font size (in pixels).';
$l['style_your_nick_min_font'] = 'Minimal Font Size';
$l['style_your_nick_min_font_desc'] = 'Enter a number representing the minimal font size (in pixels).';
$l['style_your_nick_max_shadowx'] = 'Maximal Shadow X Position';
$l['style_your_nick_max_shadowx_desc'] = 'Enter a number representing the maximal horizontal shadow position (in pixels).';
$l['style_your_nick_min_shadowx'] = 'Minimal Shadow X Position';
$l['style_your_nick_min_shadowx_desc'] = 'Enter a number representing the minimal horizontal shadow position (in pixels).';
$l['style_your_nick_max_shadowy'] = 'Maximal Shadow Y Position';
$l['style_your_nick_max_shadowy_desc'] = 'Enter a number representing the maximal vertical shadow position (in pixels).';
$l['style_your_nick_min_shadowy'] = 'Minimal Shadow Y Position';
$l['style_your_nick_min_shadowy_desc'] = 'Enter a number representing the minimal vertical shadow position (in pixels).';
$l['style_your_nick_max_shadowlength'] = 'Maximal Shadow Length/Radius';
$l['style_your_nick_max_shadowlength_desc'] = 'Enter a number representing the maximal shadow length (in pixels).';
$l['style_your_nick_min_shadowlength'] = 'Minimal Shadow Length/Radius';
$l['style_your_nick_min_shadowlength_desc'] = 'Enter a number representing the minimal shadow length (in pixels).';
$l['style_your_nick_max_backgroundimg'] = 'Maximal Background Image Dimension';
$l['style_your_nick_max_backgroundimg_desc'] = 'Enter maximal width and height of the background image separated by the x letter.';

$l['style_your_nick_general'] = 'General';
$l['style_your_nick_can_style'] = 'Can style username?<br />
<small><strong>Note:</strong> ticking this option is required for the rest to work.</small>';
$l['style_your_nick_use_default'] = "Use default group styling if an user doesn't choose their own styling?
<small><strong>Note:</strong> unticking it for any group is not recommended on big forums since a slower query will be used.</small>";
$l['style_your_nick_styling'] = 'Styling';
$l['style_your_nick_can_color'] = 'Can change the font color?';
$l['style_your_nick_can_background'] = 'Can change the background color?';
$l['style_your_nick_can_background_img'] = 'Can change the background image?';
$l['style_your_nick_can_background_repeat'] = 'Can make the background image repeat itself?';
$l['style_your_nick_can_size'] = 'Can change the font size?';
$l['style_your_nick_can_italic'] = 'Can change the font style to italic?';
$l['style_your_nick_can_bold'] = 'Can change the font weight to bold?';
$l['style_your_nick_can_underline'] = 'Can underline the username?';
$l['style_your_nick_can_overline'] = 'Can overline the username?';
$l['style_your_nick_can_strike'] = 'Can strikethrough the username?';
$l['style_your_nick_can_capital'] = 'Can capitalize the whole username?';
$l['style_your_nick_can_shadow'] = 'Can add a shadow to the username?';