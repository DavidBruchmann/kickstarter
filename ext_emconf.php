<?php

########################################################################
# Extension Manager/Repository config file for ext: "kickstarter"
#
# Auto generated 30-01-2007 12:44
#
# Manual updates:
# Only the data in the array - anything else is removed by next write
########################################################################

$EM_CONF[$_EXTKEY] = Array (
	'title' => 'Extension Kickstarter',
	'description' => 'Creates a framework for a new extension',
	'category' => 'be',
	'shy' => 0,
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'TYPO3_version' => '',
	'PHP_version' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Ingo Renner',
	'author_email' => 'typo3@ingo-renner.com',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.3.8',	// Don't modify this! Managed automatically during upload to repository.
	'_md5_values_when_last_written' => 'a:338:{s:8:".project";s:4:"719c";s:9:"ChangeLog";s:4:"2a53";s:9:"build.xml";s:4:"415d";s:37:"class.tx_kickstarter_compilefiles.php";s:4:"e9c4";s:38:"class.tx_kickstarter_reservedwords.php";s:4:"b11d";s:36:"class.tx_kickstarter_sectionbase.php";s:4:"cafb";s:31:"class.tx_kickstarter_wizard.php";s:4:"0afc";s:12:"ext_icon.gif";s:4:"1bf7";s:17:"ext_localconf.php";s:4:"e8a5";s:14:"ext_tables.php";s:4:"8304";s:16:"locallang_db.xml";s:4:"4fa0";s:16:".svn/all-wcprops";s:4:"b38a";s:18:".svn/dir-prop-base";s:4:"f230";s:12:".svn/entries";s:4:"72ba";s:11:".svn/format";s:4:"c30f";s:33:".svn/prop-base/ChangeLog.svn-base";s:4:"685f";s:61:".svn/prop-base/class.tx_kickstarter_compilefiles.php.svn-base";s:4:"3c71";s:62:".svn/prop-base/class.tx_kickstarter_reservedwords.php.svn-base";s:4:"cd30";s:60:".svn/prop-base/class.tx_kickstarter_sectionbase.php.svn-base";s:4:"685f";s:55:".svn/prop-base/class.tx_kickstarter_wizard.php.svn-base";s:4:"3c71";s:38:".svn/prop-base/ext_emconf.php.svn-base";s:4:"3c71";s:36:".svn/prop-base/ext_icon.gif.svn-base";s:4:"945a";s:41:".svn/prop-base/ext_localconf.php.svn-base";s:4:"685f";s:38:".svn/prop-base/ext_tables.php.svn-base";s:4:"3c71";s:33:".svn/text-base/ChangeLog.svn-base";s:4:"2a53";s:33:".svn/text-base/build.xml.svn-base";s:4:"415d";s:61:".svn/text-base/class.tx_kickstarter_compilefiles.php.svn-base";s:4:"e9c4";s:62:".svn/text-base/class.tx_kickstarter_reservedwords.php.svn-base";s:4:"b11d";s:60:".svn/text-base/class.tx_kickstarter_sectionbase.php.svn-base";s:4:"cafb";s:55:".svn/text-base/class.tx_kickstarter_wizard.php.svn-base";s:4:"0afc";s:38:".svn/text-base/ext_emconf.php.svn-base";s:4:"ddad";s:36:".svn/text-base/ext_icon.gif.svn-base";s:4:"1bf7";s:41:".svn/text-base/ext_localconf.php.svn-base";s:4:"e8a5";s:38:".svn/text-base/ext_tables.php.svn-base";s:4:"8304";s:40:".svn/text-base/locallang_db.xml.svn-base";s:4:"4fa0";s:14:"icons/bold.png";s:4:"aff1";s:16:"icons/center.png";s:4:"782e";s:15:"icons/class.png";s:4:"0975";s:14:"icons/copy.png";s:4:"430b";s:13:"icons/cut.png";s:4:"1af3";s:18:"icons/emoticon.png";s:4:"928c";s:18:"icons/fontsize.png";s:4:"8efa";s:19:"icons/fontstyle.png";s:4:"cc24";s:21:"icons/formatblock.png";s:4:"9f63";s:15:"icons/image.png";s:4:"8d35";s:16:"icons/indent.png";s:4:"782f";s:16:"icons/italic.png";s:4:"c91b";s:14:"icons/left.png";s:4:"5b83";s:14:"icons/line.png";s:4:"7885";s:14:"icons/link.png";s:4:"fa8a";s:21:"icons/orderedlist.png";s:4:"f658";s:17:"icons/outdent.png";s:4:"8890";s:15:"icons/paste.png";s:4:"f4cb";s:15:"icons/right.png";s:4:"e998";s:15:"icons/table.png";s:4:"5f9c";s:19:"icons/textcolor.png";s:4:"3988";s:19:"icons/underline.png";s:4:"a0ad";s:23:"icons/unorderedlist.png";s:4:"cdef";s:14:"icons/user.png";s:4:"8ae5";s:18:"icons/.svn/entries";s:4:"976d";s:17:"icons/.svn/format";s:4:"c30f";s:38:"icons/.svn/prop-base/bold.png.svn-base";s:4:"945a";s:40:"icons/.svn/prop-base/center.png.svn-base";s:4:"945a";s:39:"icons/.svn/prop-base/class.png.svn-base";s:4:"945a";s:38:"icons/.svn/prop-base/copy.png.svn-base";s:4:"945a";s:37:"icons/.svn/prop-base/cut.png.svn-base";s:4:"945a";s:42:"icons/.svn/prop-base/emoticon.png.svn-base";s:4:"945a";s:42:"icons/.svn/prop-base/fontsize.png.svn-base";s:4:"945a";s:43:"icons/.svn/prop-base/fontstyle.png.svn-base";s:4:"945a";s:45:"icons/.svn/prop-base/formatblock.png.svn-base";s:4:"945a";s:39:"icons/.svn/prop-base/image.png.svn-base";s:4:"945a";s:40:"icons/.svn/prop-base/indent.png.svn-base";s:4:"945a";s:40:"icons/.svn/prop-base/italic.png.svn-base";s:4:"945a";s:38:"icons/.svn/prop-base/left.png.svn-base";s:4:"945a";s:38:"icons/.svn/prop-base/line.png.svn-base";s:4:"945a";s:38:"icons/.svn/prop-base/link.png.svn-base";s:4:"945a";s:45:"icons/.svn/prop-base/orderedlist.png.svn-base";s:4:"945a";s:41:"icons/.svn/prop-base/outdent.png.svn-base";s:4:"945a";s:39:"icons/.svn/prop-base/paste.png.svn-base";s:4:"945a";s:39:"icons/.svn/prop-base/right.png.svn-base";s:4:"945a";s:39:"icons/.svn/prop-base/table.png.svn-base";s:4:"945a";s:43:"icons/.svn/prop-base/textcolor.png.svn-base";s:4:"945a";s:43:"icons/.svn/prop-base/underline.png.svn-base";s:4:"945a";s:47:"icons/.svn/prop-base/unorderedlist.png.svn-base";s:4:"945a";s:38:"icons/.svn/prop-base/user.png.svn-base";s:4:"945a";s:38:"icons/.svn/text-base/bold.png.svn-base";s:4:"aff1";s:40:"icons/.svn/text-base/center.png.svn-base";s:4:"782e";s:39:"icons/.svn/text-base/class.png.svn-base";s:4:"0975";s:38:"icons/.svn/text-base/copy.png.svn-base";s:4:"430b";s:37:"icons/.svn/text-base/cut.png.svn-base";s:4:"1af3";s:42:"icons/.svn/text-base/emoticon.png.svn-base";s:4:"928c";s:42:"icons/.svn/text-base/fontsize.png.svn-base";s:4:"8efa";s:43:"icons/.svn/text-base/fontstyle.png.svn-base";s:4:"cc24";s:45:"icons/.svn/text-base/formatblock.png.svn-base";s:4:"9f63";s:39:"icons/.svn/text-base/image.png.svn-base";s:4:"8d35";s:40:"icons/.svn/text-base/indent.png.svn-base";s:4:"782f";s:40:"icons/.svn/text-base/italic.png.svn-base";s:4:"c91b";s:38:"icons/.svn/text-base/left.png.svn-base";s:4:"5b83";s:38:"icons/.svn/text-base/line.png.svn-base";s:4:"7885";s:38:"icons/.svn/text-base/link.png.svn-base";s:4:"fa8a";s:45:"icons/.svn/text-base/orderedlist.png.svn-base";s:4:"f658";s:41:"icons/.svn/text-base/outdent.png.svn-base";s:4:"8890";s:39:"icons/.svn/text-base/paste.png.svn-base";s:4:"f4cb";s:39:"icons/.svn/text-base/right.png.svn-base";s:4:"e998";s:39:"icons/.svn/text-base/table.png.svn-base";s:4:"5f9c";s:43:"icons/.svn/text-base/textcolor.png.svn-base";s:4:"3988";s:43:"icons/.svn/text-base/underline.png.svn-base";s:4:"a0ad";s:47:"icons/.svn/text-base/unorderedlist.png.svn-base";s:4:"cdef";s:38:"icons/.svn/text-base/user.png.svn-base";s:4:"8ae5";s:42:"modfunc1/class.tx_kickstarter_modfunc1.php";s:4:"d79a";s:22:"modfunc1/locallang.xml";s:4:"0b36";s:25:"modfunc1/.svn/all-wcprops";s:4:"f1c6";s:21:"modfunc1/.svn/entries";s:4:"c32d";s:20:"modfunc1/.svn/format";s:4:"c30f";s:66:"modfunc1/.svn/prop-base/class.tx_kickstarter_modfunc1.php.svn-base";s:4:"3c71";s:66:"modfunc1/.svn/text-base/class.tx_kickstarter_modfunc1.php.svn-base";s:4:"d79a";s:46:"modfunc1/.svn/text-base/locallang.xml.svn-base";s:4:"0b36";s:13:"res/clear.gif";s:4:"cc11";s:10:"res/cm.png";s:4:"df60";s:15:"res/default.gif";s:4:"475a";s:21:"res/default_black.gif";s:4:"355b";s:20:"res/default_blue.gif";s:4:"4ad7";s:21:"res/default_gray4.gif";s:4:"a25c";s:21:"res/default_green.gif";s:4:"1e24";s:22:"res/default_purple.gif";s:4:"78eb";s:19:"res/default_red.gif";s:4:"dc05";s:22:"res/default_yellow.gif";s:4:"401f";s:14:"res/module.png";s:4:"9c10";s:23:"res/modulefunc_func.png";s:4:"af99";s:23:"res/modulefunc_task.png";s:4:"5667";s:16:"res/notfound.gif";s:4:"1bdc";s:23:"res/notfound_module.gif";s:4:"8074";s:13:"res/pi_ce.png";s:4:"6ac3";s:16:"res/pi_cewiz.png";s:4:"57db";s:17:"res/pi_header.png";s:4:"0d49";s:23:"res/pi_menu_sitemap.png";s:4:"fbfc";s:13:"res/pi_pi.png";s:4:"01e2";s:18:"res/pi_textbox.png";s:4:"ed57";s:17:"res/t_check10.png";s:4:"1d11";s:16:"res/t_check4.png";s:4:"d094";s:14:"res/t_date.png";s:4:"0c8b";s:18:"res/t_datetime.png";s:4:"1726";s:18:"res/t_file_all.png";s:4:"9018";s:18:"res/t_file_img.png";s:4:"8eed";s:19:"res/t_file_size.png";s:4:"f082";s:20:"res/t_file_thumb.png";s:4:"56a1";s:18:"res/t_file_web.png";s:4:"e722";s:21:"res/t_flag_access.png";s:4:"23a4";s:22:"res/t_flag_endtime.png";s:4:"2dfc";s:21:"res/t_flag_hidden.png";s:4:"a0ce";s:24:"res/t_flag_starttime.png";s:4:"dd68";s:15:"res/t_input.png";s:4:"c430";s:21:"res/t_input_check.png";s:4:"712e";s:24:"res/t_input_colorwiz.png";s:4:"2a07";s:20:"res/t_input_link.png";s:4:"0ca9";s:21:"res/t_input_link2.png";s:4:"15ad";s:24:"res/t_input_password.png";s:4:"d51a";s:24:"res/t_input_required.png";s:4:"3b9f";s:17:"res/t_integer.png";s:4:"537b";s:14:"res/t_link.png";s:4:"a333";s:15:"res/t_radio.png";s:4:"eb1e";s:19:"res/t_rel_group.png";s:4:"6d4e";s:18:"res/t_rel_sel1.png";s:4:"6a9e";s:22:"res/t_rel_selmulti.png";s:4:"5bdb";s:18:"res/t_rel_selx.png";s:4:"810e";s:21:"res/t_rel_wizards.png";s:4:"9d71";s:13:"res/t_rte.png";s:4:"200b";s:14:"res/t_rte2.png";s:4:"d27c";s:19:"res/t_rte_class.png";s:4:"786e";s:19:"res/t_rte_color.png";s:4:"0d25";s:25:"res/t_rte_colorpicker.png";s:4:"b69e";s:24:"res/t_rte_fullscreen.png";s:4:"f043";s:20:"res/t_rte_hideHx.png";s:4:"c67d";s:13:"res/t_sel.png";s:4:"c49b";s:22:"res/t_select_icons.png";s:4:"24c7";s:18:"res/t_textarea.png";s:4:"1212";s:22:"res/t_textarea_wiz.png";s:4:"cf7b";s:11:"res/wiz.gif";s:4:"02b6";s:16:"res/.svn/entries";s:4:"e6df";s:15:"res/.svn/format";s:4:"c30f";s:37:"res/.svn/prop-base/clear.gif.svn-base";s:4:"945a";s:34:"res/.svn/prop-base/cm.png.svn-base";s:4:"945a";s:39:"res/.svn/prop-base/default.gif.svn-base";s:4:"945a";s:45:"res/.svn/prop-base/default_black.gif.svn-base";s:4:"945a";s:44:"res/.svn/prop-base/default_blue.gif.svn-base";s:4:"945a";s:45:"res/.svn/prop-base/default_gray4.gif.svn-base";s:4:"945a";s:45:"res/.svn/prop-base/default_green.gif.svn-base";s:4:"945a";s:46:"res/.svn/prop-base/default_purple.gif.svn-base";s:4:"945a";s:43:"res/.svn/prop-base/default_red.gif.svn-base";s:4:"945a";s:46:"res/.svn/prop-base/default_yellow.gif.svn-base";s:4:"945a";s:38:"res/.svn/prop-base/module.png.svn-base";s:4:"945a";s:47:"res/.svn/prop-base/modulefunc_func.png.svn-base";s:4:"945a";s:47:"res/.svn/prop-base/modulefunc_task.png.svn-base";s:4:"945a";s:40:"res/.svn/prop-base/notfound.gif.svn-base";s:4:"945a";s:47:"res/.svn/prop-base/notfound_module.gif.svn-base";s:4:"945a";s:37:"res/.svn/prop-base/pi_ce.png.svn-base";s:4:"945a";s:40:"res/.svn/prop-base/pi_cewiz.png.svn-base";s:4:"945a";s:41:"res/.svn/prop-base/pi_header.png.svn-base";s:4:"945a";s:47:"res/.svn/prop-base/pi_menu_sitemap.png.svn-base";s:4:"945a";s:37:"res/.svn/prop-base/pi_pi.png.svn-base";s:4:"945a";s:42:"res/.svn/prop-base/pi_textbox.png.svn-base";s:4:"945a";s:41:"res/.svn/prop-base/t_check10.png.svn-base";s:4:"945a";s:40:"res/.svn/prop-base/t_check4.png.svn-base";s:4:"945a";s:38:"res/.svn/prop-base/t_date.png.svn-base";s:4:"945a";s:42:"res/.svn/prop-base/t_datetime.png.svn-base";s:4:"945a";s:42:"res/.svn/prop-base/t_file_all.png.svn-base";s:4:"945a";s:42:"res/.svn/prop-base/t_file_img.png.svn-base";s:4:"945a";s:43:"res/.svn/prop-base/t_file_size.png.svn-base";s:4:"945a";s:44:"res/.svn/prop-base/t_file_thumb.png.svn-base";s:4:"945a";s:42:"res/.svn/prop-base/t_file_web.png.svn-base";s:4:"945a";s:45:"res/.svn/prop-base/t_flag_access.png.svn-base";s:4:"945a";s:46:"res/.svn/prop-base/t_flag_endtime.png.svn-base";s:4:"945a";s:45:"res/.svn/prop-base/t_flag_hidden.png.svn-base";s:4:"945a";s:48:"res/.svn/prop-base/t_flag_starttime.png.svn-base";s:4:"945a";s:39:"res/.svn/prop-base/t_input.png.svn-base";s:4:"945a";s:45:"res/.svn/prop-base/t_input_check.png.svn-base";s:4:"945a";s:48:"res/.svn/prop-base/t_input_colorwiz.png.svn-base";s:4:"945a";s:44:"res/.svn/prop-base/t_input_link.png.svn-base";s:4:"945a";s:45:"res/.svn/prop-base/t_input_link2.png.svn-base";s:4:"945a";s:48:"res/.svn/prop-base/t_input_password.png.svn-base";s:4:"945a";s:48:"res/.svn/prop-base/t_input_required.png.svn-base";s:4:"945a";s:41:"res/.svn/prop-base/t_integer.png.svn-base";s:4:"945a";s:38:"res/.svn/prop-base/t_link.png.svn-base";s:4:"945a";s:39:"res/.svn/prop-base/t_radio.png.svn-base";s:4:"945a";s:43:"res/.svn/prop-base/t_rel_group.png.svn-base";s:4:"945a";s:42:"res/.svn/prop-base/t_rel_sel1.png.svn-base";s:4:"945a";s:46:"res/.svn/prop-base/t_rel_selmulti.png.svn-base";s:4:"945a";s:42:"res/.svn/prop-base/t_rel_selx.png.svn-base";s:4:"945a";s:45:"res/.svn/prop-base/t_rel_wizards.png.svn-base";s:4:"945a";s:37:"res/.svn/prop-base/t_rte.png.svn-base";s:4:"945a";s:38:"res/.svn/prop-base/t_rte2.png.svn-base";s:4:"945a";s:43:"res/.svn/prop-base/t_rte_class.png.svn-base";s:4:"945a";s:43:"res/.svn/prop-base/t_rte_color.png.svn-base";s:4:"945a";s:49:"res/.svn/prop-base/t_rte_colorpicker.png.svn-base";s:4:"945a";s:48:"res/.svn/prop-base/t_rte_fullscreen.png.svn-base";s:4:"945a";s:44:"res/.svn/prop-base/t_rte_hideHx.png.svn-base";s:4:"945a";s:37:"res/.svn/prop-base/t_sel.png.svn-base";s:4:"945a";s:46:"res/.svn/prop-base/t_select_icons.png.svn-base";s:4:"945a";s:42:"res/.svn/prop-base/t_textarea.png.svn-base";s:4:"945a";s:46:"res/.svn/prop-base/t_textarea_wiz.png.svn-base";s:4:"945a";s:35:"res/.svn/prop-base/wiz.gif.svn-base";s:4:"945a";s:37:"res/.svn/text-base/clear.gif.svn-base";s:4:"cc11";s:34:"res/.svn/text-base/cm.png.svn-base";s:4:"df60";s:39:"res/.svn/text-base/default.gif.svn-base";s:4:"475a";s:45:"res/.svn/text-base/default_black.gif.svn-base";s:4:"355b";s:44:"res/.svn/text-base/default_blue.gif.svn-base";s:4:"4ad7";s:45:"res/.svn/text-base/default_gray4.gif.svn-base";s:4:"a25c";s:45:"res/.svn/text-base/default_green.gif.svn-base";s:4:"1e24";s:46:"res/.svn/text-base/default_purple.gif.svn-base";s:4:"78eb";s:43:"res/.svn/text-base/default_red.gif.svn-base";s:4:"dc05";s:46:"res/.svn/text-base/default_yellow.gif.svn-base";s:4:"401f";s:38:"res/.svn/text-base/module.png.svn-base";s:4:"9c10";s:47:"res/.svn/text-base/modulefunc_func.png.svn-base";s:4:"af99";s:47:"res/.svn/text-base/modulefunc_task.png.svn-base";s:4:"5667";s:40:"res/.svn/text-base/notfound.gif.svn-base";s:4:"1bdc";s:47:"res/.svn/text-base/notfound_module.gif.svn-base";s:4:"8074";s:37:"res/.svn/text-base/pi_ce.png.svn-base";s:4:"6ac3";s:40:"res/.svn/text-base/pi_cewiz.png.svn-base";s:4:"57db";s:41:"res/.svn/text-base/pi_header.png.svn-base";s:4:"0d49";s:47:"res/.svn/text-base/pi_menu_sitemap.png.svn-base";s:4:"fbfc";s:37:"res/.svn/text-base/pi_pi.png.svn-base";s:4:"01e2";s:42:"res/.svn/text-base/pi_textbox.png.svn-base";s:4:"ed57";s:41:"res/.svn/text-base/t_check10.png.svn-base";s:4:"1d11";s:40:"res/.svn/text-base/t_check4.png.svn-base";s:4:"d094";s:38:"res/.svn/text-base/t_date.png.svn-base";s:4:"0c8b";s:42:"res/.svn/text-base/t_datetime.png.svn-base";s:4:"1726";s:42:"res/.svn/text-base/t_file_all.png.svn-base";s:4:"9018";s:42:"res/.svn/text-base/t_file_img.png.svn-base";s:4:"8eed";s:43:"res/.svn/text-base/t_file_size.png.svn-base";s:4:"f082";s:44:"res/.svn/text-base/t_file_thumb.png.svn-base";s:4:"56a1";s:42:"res/.svn/text-base/t_file_web.png.svn-base";s:4:"e722";s:45:"res/.svn/text-base/t_flag_access.png.svn-base";s:4:"23a4";s:46:"res/.svn/text-base/t_flag_endtime.png.svn-base";s:4:"2dfc";s:45:"res/.svn/text-base/t_flag_hidden.png.svn-base";s:4:"a0ce";s:48:"res/.svn/text-base/t_flag_starttime.png.svn-base";s:4:"dd68";s:39:"res/.svn/text-base/t_input.png.svn-base";s:4:"c430";s:45:"res/.svn/text-base/t_input_check.png.svn-base";s:4:"712e";s:48:"res/.svn/text-base/t_input_colorwiz.png.svn-base";s:4:"2a07";s:44:"res/.svn/text-base/t_input_link.png.svn-base";s:4:"0ca9";s:45:"res/.svn/text-base/t_input_link2.png.svn-base";s:4:"15ad";s:48:"res/.svn/text-base/t_input_password.png.svn-base";s:4:"d51a";s:48:"res/.svn/text-base/t_input_required.png.svn-base";s:4:"3b9f";s:41:"res/.svn/text-base/t_integer.png.svn-base";s:4:"537b";s:38:"res/.svn/text-base/t_link.png.svn-base";s:4:"a333";s:39:"res/.svn/text-base/t_radio.png.svn-base";s:4:"eb1e";s:43:"res/.svn/text-base/t_rel_group.png.svn-base";s:4:"6d4e";s:42:"res/.svn/text-base/t_rel_sel1.png.svn-base";s:4:"6a9e";s:46:"res/.svn/text-base/t_rel_selmulti.png.svn-base";s:4:"5bdb";s:42:"res/.svn/text-base/t_rel_selx.png.svn-base";s:4:"810e";s:45:"res/.svn/text-base/t_rel_wizards.png.svn-base";s:4:"9d71";s:37:"res/.svn/text-base/t_rte.png.svn-base";s:4:"200b";s:38:"res/.svn/text-base/t_rte2.png.svn-base";s:4:"d27c";s:43:"res/.svn/text-base/t_rte_class.png.svn-base";s:4:"786e";s:43:"res/.svn/text-base/t_rte_color.png.svn-base";s:4:"0d25";s:49:"res/.svn/text-base/t_rte_colorpicker.png.svn-base";s:4:"b69e";s:48:"res/.svn/text-base/t_rte_fullscreen.png.svn-base";s:4:"f043";s:44:"res/.svn/text-base/t_rte_hideHx.png.svn-base";s:4:"c67d";s:37:"res/.svn/text-base/t_sel.png.svn-base";s:4:"c49b";s:46:"res/.svn/text-base/t_select_icons.png.svn-base";s:4:"24c7";s:42:"res/.svn/text-base/t_textarea.png.svn-base";s:4:"1212";s:46:"res/.svn/text-base/t_textarea_wiz.png.svn-base";s:4:"cf7b";s:35:"res/.svn/text-base/wiz.gif.svn-base";s:4:"02b6";s:44:"sections/class.tx_kickstarter_section_cm.php";s:4:"23d4";s:48:"sections/class.tx_kickstarter_section_emconf.php";s:4:"3a9f";s:48:"sections/class.tx_kickstarter_section_fields.php";s:4:"11df";s:51:"sections/class.tx_kickstarter_section_languages.php";s:4:"ea5d";s:48:"sections/class.tx_kickstarter_section_module.php";s:4:"12dd";s:56:"sections/class.tx_kickstarter_section_modulefunction.php";s:4:"79a9";s:44:"sections/class.tx_kickstarter_section_pi.php";s:4:"c4e5";s:44:"sections/class.tx_kickstarter_section_sv.php";s:4:"7828";s:48:"sections/class.tx_kickstarter_section_tables.php";s:4:"c849";s:44:"sections/class.tx_kickstarter_section_ts.php";s:4:"a1b9";s:50:"sections/class.tx_kickstarter_section_tsconfig.php";s:4:"33af";s:25:"sections/.svn/all-wcprops";s:4:"bfdc";s:21:"sections/.svn/entries";s:4:"397a";s:20:"sections/.svn/format";s:4:"c30f";s:68:"sections/.svn/prop-base/class.tx_kickstarter_section_cm.php.svn-base";s:4:"685f";s:72:"sections/.svn/prop-base/class.tx_kickstarter_section_emconf.php.svn-base";s:4:"685f";s:72:"sections/.svn/prop-base/class.tx_kickstarter_section_fields.php.svn-base";s:4:"685f";s:75:"sections/.svn/prop-base/class.tx_kickstarter_section_languages.php.svn-base";s:4:"685f";s:72:"sections/.svn/prop-base/class.tx_kickstarter_section_module.php.svn-base";s:4:"685f";s:80:"sections/.svn/prop-base/class.tx_kickstarter_section_modulefunction.php.svn-base";s:4:"685f";s:68:"sections/.svn/prop-base/class.tx_kickstarter_section_pi.php.svn-base";s:4:"685f";s:68:"sections/.svn/prop-base/class.tx_kickstarter_section_sv.php.svn-base";s:4:"685f";s:72:"sections/.svn/prop-base/class.tx_kickstarter_section_tables.php.svn-base";s:4:"685f";s:68:"sections/.svn/prop-base/class.tx_kickstarter_section_ts.php.svn-base";s:4:"685f";s:74:"sections/.svn/prop-base/class.tx_kickstarter_section_tsconfig.php.svn-base";s:4:"685f";s:68:"sections/.svn/text-base/class.tx_kickstarter_section_cm.php.svn-base";s:4:"23d4";s:72:"sections/.svn/text-base/class.tx_kickstarter_section_emconf.php.svn-base";s:4:"3a9f";s:72:"sections/.svn/text-base/class.tx_kickstarter_section_fields.php.svn-base";s:4:"5a43";s:75:"sections/.svn/text-base/class.tx_kickstarter_section_languages.php.svn-base";s:4:"ea5d";s:72:"sections/.svn/text-base/class.tx_kickstarter_section_module.php.svn-base";s:4:"12dd";s:80:"sections/.svn/text-base/class.tx_kickstarter_section_modulefunction.php.svn-base";s:4:"79a9";s:68:"sections/.svn/text-base/class.tx_kickstarter_section_pi.php.svn-base";s:4:"c4e5";s:68:"sections/.svn/text-base/class.tx_kickstarter_section_sv.php.svn-base";s:4:"7828";s:72:"sections/.svn/text-base/class.tx_kickstarter_section_tables.php.svn-base";s:4:"c849";s:68:"sections/.svn/text-base/class.tx_kickstarter_section_ts.php.svn-base";s:4:"a1b9";s:74:"sections/.svn/text-base/class.tx_kickstarter_section_tsconfig.php.svn-base";s:4:"33af";}',
);

?>