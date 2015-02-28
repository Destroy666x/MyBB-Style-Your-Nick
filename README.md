**Style Your Nick**
===============

![Style Your Nick](https://raw.github.com/Destroy666x/MyBB-Style-Your-Nick/master/preview1.png "Preview")  

**Name**: Style Your Nick  
**Author**: Destroy666  
**Version**: 1.1  

**Info**:
---------

Plugin for MyBB forum software, coded for versions 1.8.x (will probably also work in 1.6.x/1.4.x - not without hook changes anymore).  
It allows users to change their nickname styling in the User CP (based on group permissions).  
1 core edit, 1 new database table, 14 new database columns, 8 new templates, 1 template edit, 11 new settings  
Released under GNU GPL v3, 29 June 2007. Read the LICENSE.md file for more information.  

**Support/bug reports**: 
------------------------

**Support**: official MyBB forum - http://community.mybb.com/mods.php?action=profile&uid=58253 (don't PM me, post on forums)  
**Bug reports**: my github - https://github.com/Destroy666x  

**Changelog**:
--------------

**1.1** - small bugfixes, added disallowed colors setting, modified User Cleanup task to delete unused (lack of permissions) records from the `styleyournick` table, they're also removed on user's content deletion or profile clearance. Upgrade **required** (more info below).  
**1.0** - initial release  

**Plans for the future**:
------------------------

- [ ] add a jQuery colorpicker to the styling page - 1.1
- [x] clean up old unused stylings - 1.1
- [ ] allow users to save multiple styles and then choose whichever they want (with usergroup maximum styles per user option) - 1.1
- [ ] move the limits size/color/image limits to usergroup options
- [ ] predefined ACP stylings made by admins which the user can choose
- [ ] add any other possible effects (image before/after nick, rainbow JS, etc.) - very low priority, probably will add them only after donations

**Requirements**:
-----------------

Plugin Library is required for templates installation and core edit.  
You can download it here: https://github.com/frostschutz/MyBB-PluginLibrary/archive/master.zip  
Installation guide: https://github.com/frostschutz/MyBB-PluginLibrary/blob/master/README.txt  
After uploading it ignore the compatibility warning.  

The **format_name()** function is modified inside the **inc/functions.php** file, keep that in mind when upgrading or replacing the file in any other way.  

If you're using PostgreSQL, version 9.1 is required.  

**Installation**:
-----------------

1. Get Plugin Library (check Requirements section for more info).
2. Upload everything from upload folder to your forum root (where index.php, forumdisplay.php etc. are located).
3. Install and activate plugin in ACP -> Configuration -> Plugins.
4. Configure it.

**Templates troubleshooting**:
------------------------------

* User CP - add **{$GLOBALS['nav_style_your_nick']}** to the usercp_nav_profile template.

**Translations**:
-----------------

Feel free to submit translation to github in Pull Requests. Also, if you want them to be included on the MyBB mods site, ask me to provide you the contributor status for my project.

**Donations**:
-------------

Donations will motivate me to work on further MyBB plugins. Feel free to use the button in the ACP Plugins section anytime.  
Thanks in advance for any input.