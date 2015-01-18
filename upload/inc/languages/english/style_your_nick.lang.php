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
$l['style_your_nick_options'] = 'Styling Options';
$l['style_your_nick_no_options'] = 'All styling options are currently disabled for your group.';
$l['style_your_nick_save'] = 'Save';
$l['style_your_nick_clear'] = 'Clear';
$l['style_your_nick_success'] = 'The styling has been successfully updated. You will be now taken back to the nick styling page.';
$l['style_your_nick_clear_success'] = 'The styling has been successfully cleared. You will be now taken back to the nick styling page.';

$l['style_your_nick_desc_color'] = '<strong>Font color</strong><br />
Enter a valid CSS color. Supported formats: predefined names (red, green, etc.), HEX #123DEF, RGB(255,255,255).';
$l['style_your_nick_desc_background'] = '<strong>Background color</strong><br />
Enter a valid CSS color. Supported formats: predefined names (red, green, etc.), HEX #123DEF, RGB(255,255,255).';
$l['style_your_nick_desc_backgroundimg'] = '<strong>Background image</strong><br />
Enter a valid link to an image.';
$l['style_your_nick_desc_backgroundrepeat'] = 'Make the background image repeat itself vertically and horizontally?';
$l['style_your_nick_desc_size'] = '<strong>Font size</strong><br />
Enter a valid size. Supported formats: 3px, 0.2in, 1.7cm, 14mm';
$l['style_your_nick_desc_italic'] = 'Change the font style to italic?';
$l['style_your_nick_desc_bold'] = 'Change the font weight to bold?';
$l['style_your_nick_desc_underline'] = 'Underline the username?';
$l['style_your_nick_desc_overline'] = 'Overline the username?';
$l['style_your_nick_desc_strike'] = 'Strikethrough the username?';
$l['style_your_nick_desc_capital'] = 'Capitalize the whole username?';
$l['style_your_nick_desc_shadowx'] = '<strong>Shadow X</strong><br />
Enter a valid horizontal position. Required for the shadow to work. Supported formats: 3px, 0.2in, 1.7cm, 14mm';
$l['style_your_nick_desc_shadowy'] = '<strong>Shadow Y</strong><br />
Enter a valid vertical position. Required for the shadow to work. Supported formats: 3px, 0.2in, 1.7cm, 14mm';
$l['style_your_nick_desc_shadowlength'] = '<strong>Shadow length/radius</strong><br />
Enter a valid size. Supported formats: 3px, 0.2in, 1.7cm, 14mm';
$l['style_your_nick_desc_shadowcolor'] = '<strong>Shadow color</strong><br />
Enter a valid CSS color or leave blank to use the font color. Supported formats: predefined names (red, green, etc.), HEX #123DEF, RGB(255,255,255).';

$l['style_your_nick_error_disallowed'] = "You're trying to set a field which your usergroup is not allowed to edit.";
$l['style_your_nick_error_not_filled'] = "You haven't filled any fields. At least one field should be modified.";
$l['style_your_nick_error_trans'] = ' Transparent colors including RGBA are disallowed.';
$l['style_your_nick_error_max_px'] = ' Maximum: {1}px.';
$l['style_your_nick_error_min_px'] = ' Minimum: {1}px.';
$l['style_your_nick_error_color'] = "The font color you entered is invalid. Make sure you're using a correct format.{1}";
$l['style_your_nick_error_background'] = "The background color you entered is invalid. Make sure you're using a correct format.{1}";
$l['style_your_nick_error_shadowcolor'] = "The shadow color you entered is invalid. Make sure you're using a correct format.{1}";
$l['style_your_nick_error_shadowxy'] = 'Both shadow X and Y positions have to be entered.';
$l['style_your_nick_error_size'] = "The font size you entered is invalid. Make sure you're using a correct format.{1}{2}";
$l['style_your_nick_error_shadowx'] = "The shadow X position you entered is invalid. Make sure you're using a correct format.{1}{2}";
$l['style_your_nick_error_shadowy'] = "The shadow Y position you entered is invalid. Make sure you're using a correct format.{1}{2}";
$l['style_your_nick_error_shadowlength'] = "The shadow length/radius you entered is invalid. Make sure you're using a correct format.{1}{2}";
$l['style_your_nick_error_backgroundimgrepeat'] = 'You need to choose a background image which should be repeated.';
$l['style_your_nick_error_backgroundimg_wrong'] = "The background image URL you specified doesn't link to a valid image.";
$l['style_your_nick_error_backgroundimg_size'] = 'The background image is too big. The maximal allowed dimensions are {1}x{2} while the image is {3}x{4}.';