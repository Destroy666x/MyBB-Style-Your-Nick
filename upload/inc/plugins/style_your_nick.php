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

if(!defined('IN_MYBB'))
{
	die('What are you doing?!');
}

// PluginLibrary for new templates and core edits
if(!defined('PLUGINLIBRARY'))
{
    define('PLUGINLIBRARY', MYBB_ROOT.'inc/plugins/pluginlibrary.php');
}

function style_your_nick_info()
{
    global $db, $lang;
	
	$lang->load('style_your_nick_acp');
	
	// Plugin Library notice
	$style_your_nick_pl = '';
	if(!file_exists(PLUGINLIBRARY))
		$style_your_nick_pl = $lang->pluginlibrary_missing.'<br />';
	
	// Configuration link
	$style_your_nick_cfg = '<br />';
	$gid = $db->fetch_field($db->simple_select('settinggroups', 'gid', "name='style_your_nick'"), 'gid');
	
	if($gid)
		$style_your_nick_cfg = '<a href="index.php?module=config&amp;action=change&amp;gid='.$gid.'">'.$lang->configuration.'</a>
<br />
<br />';
	
	return array(
		'name'			=> $lang->style_your_nick,
		'description'	=> $lang->style_your_nick_info.'<br />
'.$style_your_nick_pl.$style_your_nick_cfg.'
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="ZRC6HPQ46HPVN">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif" style="border: 0;" name="submit" alt="Donate">
<img alt="" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" style="border: 0; width: 1px; height: 1px;">
</form>',
		'website'		=> 'https://github.com/Destroy666x',
		'author'		=> 'Destroy666',
		'authorsite'	=> 'https://github.com/Destroy666x',
		'version'		=> 1.0,
		'codename'		=> 'style_your_nick',
		'compatibility'	=> '18*'
    );
}


function style_your_nick_activate()
{
	global $db, $lang;
	
	$lang->load('style_your_nick_acp');
	
	if(!file_exists(PLUGINLIBRARY))
	{
		flash_message($lang->pluginlibrary_missing, 'error');
		admin_redirect('index.php?module=config-plugins');
	}
	
	global $PL, $db, $cache;
	$PL or require_once PLUGINLIBRARY;
	
	//Modify core file
	if($PL->edit_core('style_your_nick', 'inc/functions.php', array(
			'search' => '$format = stripslashes($format);',
			'after' => '
static $users;

if(!is_array($users))
{
	global $db;
	
	$usedefgids = $users = array();
	
	foreach($groupscache as $g)
		if(!$g["syn_usedefault"])
			$usedefgids[] = $g["gid"];
	
	// If there are no usergroups with removed default styling, use a simplier query
	if(empty($usedefgids))
		$q = $db->query("SELECT u.username, u.usergroup, u.additionalgroups, s.*
			FROM {$db->table_prefix}styleyournick s
			LEFT JOIN {$db->table_prefix}users u ON(u.uid = s.uid)
		");
	else
		$q = $db->query("SELECT u.username, u.usergroup, u.additionalgroups, s.*
			FROM {$db->table_prefix}users u
			LEFT JOIN {$db->table_prefix}styleyournick s ON(s.uid = u.uid)
		");	

	while($u = $db->fetch_array($q))
	{
		if($u["additionalgroups"])
			$u["usergroup"] .= ",".$u["additionalgroups"];
		
		$perms = usergroup_permissions($u["usergroup"]);
		
		if($perms["syn_style"])
			$users[$u["username"]] = array_merge($perms, $u);
	}
}

if(isset($users[$username]) && $usergroup != 1)
{
	global $mybb;
	
	$u = $users[$username];
	$under = $over = $through = $style = "";
	
	if($u["color"] && $u["syn_cancolor"] && style_your_nick_validate_color($u["color"], $mybb->settings["style_your_nick_transparent"]))
		$style .= "color: ".htmlspecialchars_uni($u["color"])."; ";
	
	if($u["background"] && $u["syn_canbackground"] && style_your_nick_validate_color($u["background"], $mybb->settings["style_your_nick_transparent"]))
		$style .= "background-color: ".htmlspecialchars_uni($u["background"])."; ";
	
	// No size checks unfortunately since they are way too slow or unreliable... Unless I would upload all images to server, which may happen in the future.
	if($u["backgroundimg"] && $u["syn_canbackgroundimg"])
	{
		$style .= "background-image: url(".htmlspecialchars_uni($u["backgroundimg"])."); ";
		
		if($u["backgroundrepeat"] && $u["syn_canbackgroundrepeat"])
			$style .= "background-repeat: repeat; ";
	}
	
	if($u["size"] && $u["syn_cansize"] && style_your_nick_validate_size($u["size"], $mybb->settings["style_your_nick_max_font"], $mybb->settings["style_your_nick_min_font"]))
		$style .= "font-size: ".htmlspecialchars_uni($u["size"])."; ";
	
	if($u["bold"] && $u["syn_canbold"])
		$style .= "font-weight: bold; ";
	
	if($u["italic"] && $u["syn_canitalic"])
		$style .= "font-style: italic; ";
	
	if($u["underline"] && $u["syn_canunderline"])
		$under = " underline";
	
	if($u["overline"] && $u["syn_canoverline"])
		$over = " overline";
	
	if($u["strike"] && $u["syn_canstrike"])
		$through = " line-through";
	
	if($under || $through || $over)
		$style .= "text-decoration:$under$over$through; ";
	
	if($u["capitalize"] && $u["syn_cancapitalize"])
		$style .= "text-transform: capitalize; ";
	
	if($u["shadowx"] && $u["shadowy"] && $u["syn_canshadow"] && style_your_nick_validate_size($u["shadowx"], $mybb->settings["style_your_nick_max_shadowx"], $mybb->settings["style_your_nick_min_shadowx"])
	&& style_your_nick_validate_size($u["shadowy"], $mybb->settings["style_your_nick_max_shadowy"], $mybb->settings["style_your_nick_min_shadowy"]))
	{
		$length = $u["shadowlength"] && style_your_nick_validate_size($u["shadowlength"], $mybb->settings["style_your_nick_max_shadowlength"], $mybb->settings["style_your_nick_min_shadowlength"])
				? " ".htmlspecialchars_uni($u["shadowlength"])
				: "";
		$color = $u["shadowcolor"] && style_your_nick_validate_color($u["shadowcolor"], $mybb->settings["style_your_nick_transparent"])
				? " ".htmlspecialchars_uni($u["shadowcolor"])
				: "";
		$style .= "text-shadow: ".htmlspecialchars_uni($u["shadowx"])." ".htmlspecialchars_uni($u["shadowy"])."$length$color;";
	}
	
	if($style)
		$format = "<span style=\"$style\">{username}</span>";
	elseif(!$u["syn_usedefault"])
		$format = "{username}";
}'), true) !== true)
		flash_message($lang->core_changes_error, 'error');
	
	// Modify templates
	require_once MYBB_ROOT.'/inc/adminfunctions_templates.php';
	find_replace_templatesets('usercp_nav_profile', '#'.preg_quote('{$changenameop}').'#i', '{$changenameop}
		{$GLOBALS[\'nav_style_your_nick\']}');
		
	// Settings
	if(!$db->fetch_field($db->simple_select('settinggroups', 'COUNT(1) AS cnt', "name ='style_your_nick'"), 'cnt'))
	{
		$style_your_nick_settinggroup = array(
			'gid'			=> NULL,
			'name'			=> 'style_your_nick',
			'title'			=> $db->escape_string($lang->style_your_nick),
			'description'	=> $db->escape_string($lang->style_your_nick_settings),
			'disporder'		=> 666,
			'isdefault'		=> 0
		); 
		
		$db->insert_query('settinggroups', $style_your_nick_settinggroup);
		$gid = (int)$db->insert_id();
		
		$d = -1;
		
		$style_your_nick_settings[] = array(
			'name'			=> 'style_your_nick_transparent',
			'title'			=> $db->escape_string($lang->style_your_nick_transparent),
			'description'	=> $db->escape_string($lang->style_your_nick_transparent_desc),
			'optionscode'	=> 'yesno',
			'value'			=> 0
		);
		
		$style_your_nick_settings[] = array(
			'name'			=> 'style_your_nick_max_font',
			'title'			=> $db->escape_string($lang->style_your_nick_max_font),
			'description'	=> $db->escape_string($lang->style_your_nick_max_font_desc),
			'optionscode'	=> 'numeric',
			'value'			=> 15
		);
		
		$style_your_nick_settings[] = array(
			'name'			=> 'style_your_nick_min_font',
			'title'			=> $db->escape_string($lang->style_your_nick_min_font),
			'description'	=> $db->escape_string($lang->style_your_nick_min_font_desc),
			'optionscode'	=> 'numeric',
			'value'			=> 7
		);
		
		$style_your_nick_settings[] = array(
			'name'			=> 'style_your_nick_max_shadowx',
			'title'			=> $db->escape_string($lang->style_your_nick_max_shadowx),
			'description'	=> $db->escape_string($lang->style_your_nick_max_shadowx_desc),
			'optionscode'	=> 'numeric',
			'value'			=> 5
		);
		
		$style_your_nick_settings[] = array(
			'name'			=> 'style_your_nick_min_shadowx',
			'title'			=> $db->escape_string($lang->style_your_nick_min_shadowx),
			'description'	=> $db->escape_string($lang->style_your_nick_min_shadowx_desc),
			'optionscode'	=> 'numeric',
			'value'			=> 1
		);
		
		$style_your_nick_settings[] = array(
			'name'			=> 'style_your_nick_max_shadowy',
			'title'			=> $db->escape_string($lang->style_your_nick_max_shadowy),
			'description'	=> $db->escape_string($lang->style_your_nick_max_shadowy_desc),
			'optionscode'	=> 'numeric',
			'value'			=> 5
		);
		
		$style_your_nick_settings[] = array(
			'name'			=> 'style_your_nick_min_shadowy',
			'title'			=> $db->escape_string($lang->style_your_nick_min_shadowy),
			'description'	=> $db->escape_string($lang->style_your_nick_min_shadowy_desc),
			'optionscode'	=> 'numeric',
			'value'			=> 1
		);
		
		$style_your_nick_settings[] = array(
			'name'			=> 'style_your_nick_max_shadowlength',
			'title'			=> $db->escape_string($lang->style_your_nick_max_shadowlength),
			'description'	=> $db->escape_string($lang->style_your_nick_max_shadowlength_desc),
			'optionscode'	=> 'numeric',
			'value'			=> 3
		);
		
		$style_your_nick_settings[] = array(
			'name'			=> 'style_your_nick_min_shadowlength',
			'title'			=> $db->escape_string($lang->style_your_nick_min_shadowlength),
			'description'	=> $db->escape_string($lang->style_your_nick_min_shadowlength_desc),
			'optionscode'	=> 'numeric',
			'value'			=> 1
		);
		
		$style_your_nick_settings[] = array(
			'name'			=> 'style_your_nick_max_backgroundimg',
			'title'			=> $db->escape_string($lang->style_your_nick_max_backgroundimg),
			'description'	=> $db->escape_string($lang->style_your_nick_max_backgroundimg_desc),
			'optionscode'	=> 'text',
			'value'			=> '30x20'
		);
		
		foreach($style_your_nick_settings as &$current_setting)
		{
			$current_setting['sid'] = NULL;
			$current_setting['disporder'] = ++$d;
			$current_setting['gid'] = $gid;
		}
		
		$db->insert_query_multiple('settings', $style_your_nick_settings);
		
		rebuild_settings();
	}
}

function style_your_nick_deactivate()
{
	global $lang;
	
	$lang->load('style_your_nick_acp');
	
	if(!file_exists(PLUGINLIBRARY))
	{
		flash_message($lang->pluginlibrary_missing, 'error');
		admin_redirect('index.php?module=config-plugins');
	}
	
	global $PL, $db, $cache;
	$PL or require_once PLUGINLIBRARY;
	
	if(!$PL->edit_core('style_your_nick', 'inc/functions.php', array(), true) !== true)
		flash_message($lang->core_changes_error, 'error');
	
	require_once MYBB_ROOT.'/inc/adminfunctions_templates.php';
	find_replace_templatesets('usercp_nav_profile', '#\s*'.preg_quote("{\$GLOBALS['nav_style_your_nick']}").'#i', '');
}

function style_your_nick_install()
{
	global $lang;
	
	$lang->load('style_your_nick_acp');
	
	if(!file_exists(PLUGINLIBRARY))
	{
		flash_message($lang->pluginlibrary_missing, 'error');
		admin_redirect('index.php?module=config-plugins');
	}
	
	global $PL, $db, $cache;
	$PL or require_once PLUGINLIBRARY;
	
	$PL->templates('styleyournick', $lang->style_your_nick, array(
		'menu' => '<div><a href="usercp.php?action=style_your_nick" class="usercp_nav_item usercp_nav_editsig">{$lang->style_your_nick}</a></div>',
		'edit' => '<html>
<head>
<title>{$mybb->settings[\'bbname\']} - {$lang->style_your_nick}</title>
{$headerinclude}
</head>
<body>
{$header}
<table style="width: 100%;">
	<tr>
		{$usercpnav}
		<td valign="top">
			{$errors}
			{$lang->post_preview}: {$preview}<br />
			<br />
			<form action="{$mybb->settings[\'bburl\']}/usercp.php?action=do_style_your_nick" method="post">
			<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
				<tr>
					<td class="thead" colspan="2"><strong>{$lang->style_your_nick_options}</strong></td>
				</tr>
				{$textboxes}
				{$checkboxes}
			</table>
			{$save}
			</form>
		</td>
	</tr>
</table>
{$footer}
</body>
</html>',
		'textbox' => '<label class="smalltext" for="{$key}">{$desc}</label><br />
<input type="text" class="textbox" name="{$key}" value="{$val}" /><br />
<br />',
		'checkbox' => '<input type="checkbox" class="checkbox" name="{$key}" value="1"{$checked} /><label class="smalltext" for="{$key}">{$desc}</label><br />
<br />',
		'textboxes' => '<td class="trow1" style="vertical-align: top;">{$tboxes}</td>',
		'checkboxes' => '<td class="trow1" style="vertical-align: top;">{$cboxes}</td>',
		'save' => '<input type="hidden" name="my_post_key" value="{$mybb->post_code}" />
<br />
<div style="text-align: center;">
	<input value="{$lang->style_your_nick_save}" type="submit" class="button" name="go" />
	{$clear}
</div>',
		'clear' => '<input value="{$lang->style_your_nick_clear}" type="submit" class="button" name="clear" />'
	));
	
	$tinyint = 'tinyint(1)';
	$unsigned = ' unsigned';
	$inc = 'int unsigned NOT NULL auto_increment';
	$coll = $db->build_create_table_collation();
	$engcoll = " ENGINE = MyISAM{$coll}";
	
	if($db->type == 'pgsql')
	{
		$tinyint = 'smallint';
		$inc = 'serial';
		$engcoll = $unsigned = '';
	}
	elseif($db->type == 'sqlite')
	{
		$inc = 'integer';
		$engcoll = '';
	}
	
	// Pgsql 9.1 required
	$db->write_query("CREATE TABLE IF NOT EXISTS {$db->table_prefix}styleyournick (
		synid {$inc},
		uid int{$unsigned} NOT NULL default 0,
		color varchar(30) NOT NULL default '',
		background varchar(30) NOT NULL default '',
		backgroundimg varchar(250) NOT NULL default '',
		backgroundrepeat {$tinyint} NOT NULL default 0,
		size varchar(10) NOT NULL default '',
		bold {$tinyint} NOT NULL default 0,
		italic {$tinyint} NOT NULL default 0,		
		underline {$tinyint} NOT NULL default 0,
		overline {$tinyint} NOT NULL default 0,
		strike {$tinyint} NOT NULL default 0,
		capital {$tinyint} NOT NULL default 0,
		shadowx varchar(10) NOT NULL default '',
		shadowy varchar(10) NOT NULL default '',
		shadowlength varchar(10) NOT NULL default '',
		shadowcolor varchar(30) NOT NULL default '',
		PRIMARY KEY(synid)
	){$engcoll}");

	$style_your_nick_columns = array('style', 'usedefault', 'cancolor', 'canbackground', 'canbackgroundimg', 'canbackgroundrepeat', 'cansize', 'canbold',
		'canitalic', 'canunderline', 'canoverline', 'canstrike', 'cancapital', 'canshadow'
	);
	
	foreach($style_your_nick_columns as $col)
		if(!$db->field_exists('syn_'.$col, 'usergroups'))
			$db->add_column('usergroups', 'syn_'.$col, "$tinyint NOT NULL default ".($col == 'usedefault' ? 1 : 0));

	$cache->update_usergroups();		
}

function style_your_nick_is_installed()
{
	return $GLOBALS['db']->table_exists('styleyournick');
}

function style_your_nick_uninstall()
{   
	global $lang;
	
	$lang->load('style_your_nick_acp');
	
	if(!file_exists(PLUGINLIBRARY))
	{
		flash_message($lang->pluginlibrary_missing, 'error');
		admin_redirect('index.php?module=config-plugins');
	}
	
	global $PL, $db, $cache;
	$PL or require_once PLUGINLIBRARY;
	
	$PL->templates_delete('styleyournick');
	$db->drop_table('styleyournick');
	
	$style_your_nick_columns = array('style', 'usedefault', 'cancolor', 'canbackground', 'canbackgroundimg', 'canbackgroundrepeat', 'cansize', 'canbold',
		'canitalic', 'canunderline', 'canoverline', 'canstrike', 'cancapital', 'canshadow'
	);
	
	foreach($style_your_nick_columns as $col)
		if($db->field_exists('syn_'.$col, 'usergroups'))
			$db->drop_column('usergroups', 'syn_'.$col);
	
	$db->delete_query('settings', "name LIKE ('style\_your\_nick\_%')");
	$db->delete_query('settinggroups', "name = 'style_your_nick'");
	$cache->update_usergroups();
	
	rebuild_settings();
}

// ACP

$plugins->add_hook('admin_user_groups_edit_graph_tabs', 'style_your_nick_group_tab');
$plugins->add_hook('admin_user_groups_edit_graph', 'style_your_nick_group_tab_content');
$plugins->add_hook('admin_user_groups_edit_commit', 'style_your_nick_group_commit');

function style_your_nick_group_tab(&$tabs)
{
	global $usergroup;
	
	// Don't show it for guests
	if($usergroup['gid'] != 1)
	{
		global $lang;
	
		$lang->load('style_your_nick_acp');
	
		$tabs['style_your_nick'] = $lang->style_your_nick;
	}
}

function style_your_nick_group_tab_content()
{
	global $usergroup;
	
	if($usergroup['gid'] != 1)
	{
		global $form, $lang, $mybb;
		
		echo '<div id="tab_style_your_nick">';
		$form_container = new FormContainer($lang->style_your_nick);
		$gen = array(
			$form->generate_check_box('syn_style', 1, $lang->style_your_nick_can_style, array('checked' => $mybb->get_input('syn_style', MyBB::INPUT_INT))),
			$form->generate_check_box('syn_usedefault', 1, $lang->style_your_nick_use_default, array('checked' => $mybb->get_input('syn_usedefault', MyBB::INPUT_INT)))
		);
		$form_container->output_row($lang->style_your_nick_general, '', '<div class="group_settings_bit">'.implode('</div><div class="group_settings_bit">', $gen).'</div>');
		
		$style = array(
			$form->generate_check_box('syn_cancolor', 1, $lang->style_your_nick_can_color, array('checked' => $mybb->get_input('syn_cancolor', MyBB::INPUT_INT))),
			$form->generate_check_box('syn_canbackground', 1, $lang->style_your_nick_can_background, array('checked' => $mybb->get_input('syn_canbackground', MyBB::INPUT_INT))),
			$form->generate_check_box('syn_canbackgroundimg', 1, $lang->style_your_nick_can_background_img, array('checked' => $mybb->get_input('syn_canbackgroundimg', MyBB::INPUT_INT))),
			$form->generate_check_box('syn_canbackgroundrepeat', 1, $lang->style_your_nick_can_background_repeat, array('checked' => $mybb->get_input('syn_canbackgroundrepeat', MyBB::INPUT_INT))),
			$form->generate_check_box('syn_cansize', 1, $lang->style_your_nick_can_size, array('checked' => $mybb->get_input('syn_cansize', MyBB::INPUT_INT))),
			$form->generate_check_box('syn_canbold', 1, $lang->style_your_nick_can_bold, array('checked' => $mybb->get_input('syn_canbold', MyBB::INPUT_INT))),
			$form->generate_check_box('syn_canitalic', 1, $lang->style_your_nick_can_italic, array('checked' => $mybb->get_input('syn_canitalic', MyBB::INPUT_INT))),		
			$form->generate_check_box('syn_canunderline', 1, $lang->style_your_nick_can_underline, array('checked' => $mybb->get_input('syn_canunderline', MyBB::INPUT_INT))),
			$form->generate_check_box('syn_canoverline', 1, $lang->style_your_nick_can_overline, array('checked' => $mybb->get_input('syn_canoverline', MyBB::INPUT_INT))),
			$form->generate_check_box('syn_canstrike', 1, $lang->style_your_nick_can_strike, array('checked' => $mybb->get_input('syn_canstrike', MyBB::INPUT_INT))),
			$form->generate_check_box('syn_cancapital', 1, $lang->style_your_nick_can_capital, array('checked' => $mybb->get_input('syn_cancapital', MyBB::INPUT_INT))),
			$form->generate_check_box('syn_canshadow', 1, $lang->style_your_nick_can_shadow, array('checked' => $mybb->get_input('syn_canshadow', MyBB::INPUT_INT)))
		);
		$form_container->output_row($lang->style_your_nick_styling, '', '<div class="group_settings_bit">'.implode('</div><div class="group_settings_bit">', $style).'</div>');
		$form_container->end();
		echo '</div>';
	}
}

function style_your_nick_group_commit()
{
	global $usergroup;
	
	if($usergroup['gid'] != 1)
	{
		global $updated_group, $mybb;
		
		$updated_group = array_merge($updated_group, array(
			'syn_style' => $mybb->get_input('syn_style', MyBB::INPUT_INT),
			'syn_usedefault' => $mybb->get_input('syn_usedefault', MyBB::INPUT_INT),
			'syn_cancolor' => $mybb->get_input('syn_cancolor', MyBB::INPUT_INT),
			'syn_canbackground' => $mybb->get_input('syn_canbackground', MyBB::INPUT_INT),
			'syn_canbackgroundimg' => $mybb->get_input('syn_canbackgroundimg', MyBB::INPUT_INT),
			'syn_canbackgroundrepeat' => $mybb->get_input('syn_canbackgroundrepeat', MyBB::INPUT_INT),
			'syn_cansize' => $mybb->get_input('syn_cansize', MyBB::INPUT_INT),
			'syn_canbold' => $mybb->get_input('syn_canbold', MyBB::INPUT_INT),
			'syn_canitalic' => $mybb->get_input('syn_canitalic', MyBB::INPUT_INT),		
			'syn_canunderline' => $mybb->get_input('syn_canunderline', MyBB::INPUT_INT),
			'syn_canoverline' => $mybb->get_input('syn_canoverline', MyBB::INPUT_INT),
			'syn_canstrike' => $mybb->get_input('syn_canstrike', MyBB::INPUT_INT),
			'syn_cancapital' => $mybb->get_input('syn_cancapital', MyBB::INPUT_INT),
			'syn_canshadow' => $mybb->get_input('syn_canshadow', MyBB::INPUT_INT)
		));
	}
}

// Who's Online hooks
$plugins->add_hook('fetch_wol_activity_end', 'style_your_nick_online');
$plugins->add_hook('build_friendly_wol_location_end', 'style_your_nick_online_friendly');

function style_your_nick_online(&$activity)
{
	if(strpos($activity['location'], 'action=style_your_nick') !== false || strpos($activity['location'], 'action=do_style_your_nick') !== false)
		$activity['activity'] = 'style_your_nick';
}

function style_your_nick_online_friendly(&$activity)
{
	global $lang;
	
	$lang->load('style_your_nick_wol');
	
	if($activity['user_activity']['activity'] == 'style_your_nick')
		$activity['location_name'] = $lang->style_your_nick_online;
}

// User CP hooks
$plugins->add_hook('usercp_start', 'style_your_nick_ucp');
$plugins->add_hook('usercp_menu', 'style_your_nick_ucp_menu');

function style_your_nick_ucp()
{
	global $mybb;

	if($mybb->input['action'] == 'do_style_your_nick' && $mybb->request_method == 'post')
	{
		if(!$mybb->usergroup['syn_style'])
			error_no_permission();
		
		verify_post_check($mybb->get_input('my_post_key'));
		
		global $db, $lang;
		
		if(isset($mybb->input['clear']))
		{
			$db->delete_query('styleyournick', 'uid = '.$mybb->user['uid']);
			redirect('usercp.php?action=style_your_nick', $lang->style_your_nick_clear_success);
		} else {
			$errs = array();
			
			// Check if any input is filled and the user has permission to use it
			$inps = array('color', 'background', 'backgroundimg', 'size', 'shadowx', 'shadowy', 'shadowlength', 'shadowcolor', 'backgroundrepeat',
				'bold', 'italic', 'underline', 'overline', 'strike', 'capital'
			);
			
			$disallowed = $filled = false;
			foreach($inps as $inp)
			{
				if(!empty($mybb->input[$inp]))
				{
					if(substr($inp, 0, 6) == 'shadow')
						$inp = 'shadow';
					
					if(!$mybb->usergroup['syn_can'.$inp])
					{
						$disallowed = true;
						break;
					}
					
					$filled = true;
				}
			}
			
			if($disallowed)
				$errs[] = $lang->style_your_nick_error_disallowed;
			
			if(!$filled)
				$errs[] = $lang->style_your_nick_error_not_filled;
			
			$trans = '';
			if($mybb->settings['style_your_nick_transparent'])
				$trans = $lang->style_your_nick_error_trans;
			
			$color = style_your_nick_validate_color($mybb->get_input('color'), $mybb->settings['style_your_nick_transparent']);
			if(!empty($mybb->input['color']) && !$color)
				$errs[] = $lang->sprintf($lang->style_your_nick_error_color, $trans);
			
			$background = style_your_nick_validate_color($mybb->get_input('background'), $mybb->settings['style_your_nick_transparent']);
			if(!empty($mybb->input['background']) && !$background)
				$errs[] = $lang->sprintf($lang->style_your_nick_error_background, $trans);

			$shadowcolor = style_your_nick_validate_color($mybb->get_input('shadowcolor'), $mybb->settings['style_your_nick_transparent']);
			if(!empty($mybb->input['shadowcolor']) && !$shadowcolor)
				$errs[] = $lang->sprintf($lang->style_your_nick_error_shadowcolor, $trans);
			
			if(!empty($mybb->input['shadowx']) && empty($mybb->input['shadowy']) || !empty($mybb->input['shadowy']) && empty($mybb->input['shadowx']))
				$errs[] = $lang->style_your_nick_error_shadowxy;
			
			$shadowx = style_your_nick_validate_size($mybb->get_input('shadowx'), $mybb->settings['style_your_nick_max_shadowx'], $mybb->settings['style_your_nick_min_shadowx']);
			if(!empty($mybb->input['shadowx']) && !$shadowx)
				$errs[] = $lang->sprintf($lang->style_your_nick_error_shadowx,
					($mybb->settings['style_your_nick_max_shadowx'] ? $lang->sprintf($lang->style_your_nick_error_max_px, $mybb->settings['style_your_nick_max_shadowx']) : ''),
					($mybb->settings['style_your_nick_min_shadowx'] ? $lang->sprintf($lang->style_your_nick_error_min_px, $mybb->settings['style_your_nick_min_shadowx']) : ''));

			$shadowy = style_your_nick_validate_size($mybb->get_input('shadowy'), $mybb->settings['style_your_nick_max_shadowy'], $mybb->settings['style_your_nick_min_shadowy']);
			if(!empty($mybb->input['shadowy']) && !$shadowy)
				$errs[] = $lang->sprintf($lang->style_your_nick_error_shadowy,
					($mybb->settings['style_your_nick_max_shadowy'] ? $lang->sprintf($lang->style_your_nick_error_max_px, $mybb->settings['style_your_nick_max_shadowy']) : ''),
					($mybb->settings['style_your_nick_min_shadowy'] ? $lang->sprintf($lang->style_your_nick_error_min_px, $mybb->settings['style_your_nick_min_shadowy']) : ''));
			
			$shadowlength = style_your_nick_validate_size($mybb->get_input('shadowlength'), $mybb->settings['style_your_nick_max_shadowlength'], $mybb->settings['style_your_nick_min_shadowlength']);
			if(!empty($mybb->input['shadowlength']) && !$shadowlength)
				$errs[] = $lang->sprintf($lang->style_your_nick_error_shadowlength,
					($mybb->settings['style_your_nick_max_shadowlength'] ? $lang->sprintf($lang->style_your_nick_error_max_px, $mybb->settings['style_your_nick_max_shadowlength']) : ''),
					($mybb->settings['style_your_nick_min_shadowlength'] ? $lang->sprintf($lang->style_your_nick_error_min_px, $mybb->settings['style_your_nick_min_shadowlength']) : ''));
					
			$size = style_your_nick_validate_size($mybb->get_input('size'), $mybb->settings['style_your_nick_max_font'], $mybb->settings['style_your_nick_min_font']);
			if(!empty($mybb->input['size']) && !$size)
				$errs[] = $lang->sprintf($lang->style_your_nick_error_size,
					($mybb->settings['style_your_nick_max_font'] ? $lang->sprintf($lang->style_your_nick_error_max_px, $mybb->settings['style_your_nick_max_font']) : ''),
					($mybb->settings['style_your_nick_min_font'] ? $lang->sprintf($lang->style_your_nick_error_min_px, $mybb->settings['style_your_nick_min_font']) : ''));
			
			if(!empty($mybb->input['backgroundimg']))
			{
				list($width, $height) = @getimagesize($mybb->get_input('backgroundimg'));
				
				if(!$width)
					$errs[] = $lang->style_your_nick_error_backgroundimg_wrong;
				elseif($mybb->settings['style_your_nick_max_backgroundimg'])
				{
					$max = explode('x', strtolower($mybb->settings['style_your_nick_max_backgroundimg']));
					
					if($width > $max[0] || $height > $max[1])
						$errs[] = $lang->sprintf($lang->style_your_nick_error_backgroundimg_size, $max[0], $max[1], $width, $height);
				}
			}
			
			if(!empty($mybb->input['backgroundrepeat']) && empty($mybb->input['backgroundimg']))
				$errs[] = $lang->style_your_nick_error_backgroundimgrepeat;
			
			if(empty($errs))
			{
				$vals = array(
					'color' => $db->escape_string($color),
					'background' => $db->escape_string($background),
					'backgroundimg' => $db->escape_string($mybb->get_input('backgroundimg')),
					'backgroundrepeat' => $mybb->get_input('backgroundrepeat', MyBB::INPUT_INT),
					'size' => $db->escape_string($size),
					'bold' => $mybb->get_input('bold', MyBB::INPUT_INT),
					'italic' => $mybb->get_input('italic', MyBB::INPUT_INT),
					'underline' => $mybb->get_input('underline', MyBB::INPUT_INT),
					'overline' => $mybb->get_input('overline', MyBB::INPUT_INT),
					'strike' => $mybb->get_input('strike', MyBB::INPUT_INT),
					'capital' => $mybb->get_input('capital', MyBB::INPUT_INT),
					'shadowx' => $db->escape_string($shadowx),
					'shadowy' => $db->escape_string($shadowy),
					'shadowlength' => $db->escape_string($shadowlength),
					'shadowcolor' => $db->escape_string($shadowcolor)				
				);
				
				$synid = $db->fetch_field($db->simple_select('styleyournick', 'synid', 'uid = '.$mybb->user['uid']), 'synid');
				
				if($synid)
					$db->update_query('styleyournick', $vals, "synid = $synid");
				else
				{
					$vals['uid'] = $mybb->user['uid'];
					$db->insert_query('styleyournick', $vals);
				}
				
				redirect('usercp.php?action=style_your_nick', $lang->style_your_nick_success);
			}	
			else
			{
				$mybb->input['action'] = 'style_your_nick';
				$errors = inline_error($errs);
			}
		}
	}	
	
	if($mybb->input['action'] == 'style_your_nick')
	{
		if(!$mybb->usergroup['syn_style'])
			error_no_permission();
		
		global $lang, $db, $templates, $header, $headerinclude, $footer, $usercpnav, $theme;
		
		add_breadcrumb($lang->style_your_nick);
		
		// Get only allowed fields
		$perms = array();
		foreach($mybb->usergroup as $key => $val)
		{
			if(substr($key, 0, 7) == 'syn_can' && $val)
			{
				$fd = substr($key, 7);
				
				if($fd == 'shadow')
				{
					$perms['shadowx'] = ''; 
					$perms['shadowy'] = ''; 
					$perms['shadowlength'] = ''; 
					$perms['shadowcolor'] = '';
				}
				else
					$perms[$fd] = '';
			}
		}
		
		// Get current values
		$fields = empty($perms) ? '1' : implode(',', array_keys($perms));
		$q = $db->simple_select('styleyournick', $fields, 'uid = '.$mybb->user['uid']);
		
		$filled = false;
		if($fd = $db->fetch_array($q))
		{	
			foreach($fd as $key => $val)
				$perms[$key] = $val;
			
			$filled = true;
		}
		
		// Get user's inputs
		$perms = array_merge($perms, $mybb->input);
		
		$textboxes = $tboxes = $checkboxes = $cboxes = '';
		foreach($perms as $key => $val)
		{
			$k = 'style_your_nick_desc_'.$key;
			$desc = $lang->$k;
			if(in_array($key, array('color', 'background', 'backgroundimg', 'size', 'shadowx', 'shadowy', 'shadowlength', 'shadowcolor')))
			{
				$val = htmlspecialchars_uni($val);
				eval('$tboxes .= "'.$templates->get('styleyournick_textbox').'";');
			}
			elseif(in_array($key, array('backgroundrepeat', 'bold', 'italic', 'underline', 'overline', 'strike', 'capital')))
			{
				$checked = $val ? ' checked="checked"' : '';
				eval('$cboxes .= "'.$templates->get('styleyournick_checkbox').'";');
			}
		}
		
		$clear = '';
		if($filled)
			eval('$clear = "'.$templates->get('styleyournick_clear').'";');		

		if(!$tboxes && !$cboxes)
			$tboxes = $lang->style_your_nick_no_options;
		else
			eval('$save = "'.$templates->get('styleyournick_save').'";');
		
		if($tboxes)
			eval('$textboxes = "'.$templates->get('styleyournick_textboxes').'";');
		
		if($cboxes)
			eval('$checkboxes = "'.$templates->get('styleyournick_checkboxes').'";');
		
		$preview = format_name($mybb->user['username'], $mybb->user['usergroup'], $mybb->user['displaygroup']);
		
		eval('$page = "'.$templates->get('styleyournick_edit').'";');
		output_page($page);
	}
}

function style_your_nick_ucp_menu()
{
	global $nav_style_your_nick, $lang;
	
	$lang->load('style_your_nick');
	
	if($GLOBALS['mybb']->usergroup['syn_style'])
		eval('$nav_style_your_nick = "'.$GLOBALS['templates']->get('styleyournick_menu').'";');
	else
		$nav_style_your_nick = '';
}

/*
Check if the provided string is a valid CSS color.
	
@param string - A color.
@param bool - Whether to allow transparent colors or not.
@return mixed - The lowercased color if it's valid, false if not.
*/
function style_your_nick_validate_color($color, $allow_trans = false)
{
	global $mybb;
	
	$color = strtolower(preg_replace('/\s+/', '', $color));
	
	if(!$color || strlen($color) > 30)
		return false;
	
	// HEX 3 or 6 chars
	if(preg_match('/^#([a-f0-9]){3}(([a-f0-9]){3})?$/', $color))
		return $color;
	
	// Predefined color names
	if($color == 'transparent' && $allow_trans)
		return $color;
	
	$colors = array('aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond',
		'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk',
		'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta',
		'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray',
		'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite',
		'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew',
		'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue',
		'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgray', 'lightgreen', 'lightpink', 'lightsalmon', 'lightseagreen',
		'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon',
		'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen',
		'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy',
		'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise',
		'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue',
		'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray',
		'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white',
		'whitesmoke', 'yellow', 'yellowgreen'
	);
		
	if(in_array($color, $colors))
		return $color;
	
	// RGB or RGBA
	if(style_your_nick_validate_rgba($color, $allow_trans))
		return $color;
		
	return false;
}

/*
Check if the provided color has a valid RGB(A) syntax.
	
@param string - A color.
@param bool - Whether to allow transparent colors or not.
@return bool - True if the RGB(A) color is valid, false if not.
*/
function style_your_nick_validate_rgba($color, $allow_trans = false)
{
	global $mybb;
	
	// Eliminate RGBA if the transparency setting is disabled
	if($color[3] == 'a' && !$allow_trans)
		return false;
	
	// Basic regex check
	if(!preg_match('/^rgba?\(([0-9]+%?),([0-9]+%?),([0-9]+%?)(,((0\.[0-9]+)|1|0))?\)$/', $color, $pieces))
		return false;
		
	// RGB can only have 3 values, RGBA requires 4
	if($color[3] == 'a' && empty($pieces[4]) || $color[3] != 'a' && isset($pieces[4]))
		return false;
	
	// Check the 3 values - should be either 0-100% or 0-255
	$values = array($pieces[1], $pieces[2], $pieces[3]);
	foreach($values as $val)
	{
		if(substr($val, -1) == '%')
		{
			$val = (int)substr($val, 0, -1);
			if($val < 0 || $val > 100)
				return false;
		} else {
			$val = (int)$val;
			if($val < 0 || $val > 255)
				return false;
		}
	}
	
	return true;
}

/*
Check if the provided size is valid.
	
@param string - A size.
@param integer - Max size (in pixels).
@param integer - Min size (in pixels).
@return mixed - The lowercased size if it's valid, false if not.
*/
function style_your_nick_validate_size($size, $maxsize = 50, $minsize = 0)
{
	$size = strtolower(preg_replace('/\s+/', '', $size));
	
	if(!$size || strlen($size) > 10)
		return false;
	
	// Basic regex check
	if(!preg_match('/^[0-9]+(\.[0-9]+)?(px|cm|mm|in)$/', $size, $pieces))
		return false;
	
	// Check if it's between the min/max
	$val = (float)substr($size, 0, -2);
	
	if($pieces[2] == 'cm')
		$val *= '37.8';
	elseif($pieces[2] == 'mm')
		$val *= '3.78';
	elseif($pieces[2] == 'in')
		$val *= '96';
		
	if($val > $maxsize || $val < $minsize)
		return false;
	
	return $size;
}