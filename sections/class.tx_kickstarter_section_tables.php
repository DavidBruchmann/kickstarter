<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2001-2004 Kasper Skaarhoj (kasperYYYY@typo3.com)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * @author	Kasper Skårhøj <kasperYYYY@typo3.com>
 */
 
require_once(t3lib_extMgm::extPath("kickstarter")."sections/class.tx_kickstarter_section_fields.php");

class tx_kickstarter_section_tables extends tx_kickstarter_section_fields {
	var $catName = "New Database Tables";

	/**
	 * Renders the form in the kickstarter; this was add_cat_tables()
	 */
	function render_wizard() {
		$lines=array();

		$catID = "tables";
		$action = explode(":",$this->wizard->modData["wizAction"]);
		if ($action[0]=="edit")	{
			$this->wizard->regNewEntry($catID,$action[1]);
			$lines = $this->wizard->catHeaderLines($lines,$catID,$this->wizard->options[$catID],"&nbsp;",$action[1]);
			$piConf = $this->wizard->wizArray[$catID][$action[1]];
			$ffPrefix='['.$catID.']['.$action[1].']';

				// Unique table name:
			$table_suffixes=array();
			if (is_array($this->wizard->wizArray[$catID]))	{
				foreach($this->wizard->wizArray[$catID] as $kk => $vv)	{
					if (!strcmp($action[1],$kk))	{
						if (count($table_suffixes) && t3lib_div::inList(implode(",",$table_suffixes),$vv["tablename"]."Z"))	{
							$piConf["tablename"].=$kk;
						}
						break;
					}
					$table_suffixes[]=$vv["tablename"]."Z";
				}
			}


				// Enter title of the table
			$subContent="<strong>Tablename:</strong><BR>".
				$this->wizard->returnName($this->wizard->extKey,"tables")."_".$this->wizard->renderStringBox($ffPrefix."[tablename]",$piConf["tablename"]).
				"<BR><strong>Notice:</strong> Use characters a-z0-9 only. Only lowercase, no spaces.<BR>
				This becomes the table name in the database. ";
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';


				// Enter title of the table
			$subContent="<strong>Title of the table:</strong><BR>".
				$this->wizard->renderStringBox_lang("title",$ffPrefix,$piConf);
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';



				// Fields - overview
			$c=array(0);
			$this->wizard->usedNames=array();
			if (is_array($piConf["fields"]))	{
				$piConf["fields"] = $this->wizard->cleanFieldsAndDoCommands($piConf["fields"],$catID,$action[1]);

				// Do it for real...
				$lines[]='<tr'.$this->wizard->bgCol(1).'><td><strong> Fields Overview </strong></td></tr>';
//				$lines[]='<tr'.$this->wizard->bgCol(2).'><td>'.$this->wizard->fw($v[1]).'</td></tr>';
				$lines[]='<tr><td></td></tr>';

				$subContent ='<tr '.$this->wizard->bgCol(2).'>
					<td><strong>Name</strong></td>
					<td><strong>Title</strong></td>
					<td><strong>Type</strong></td>
					<td><strong>Exclude?</strong></td>
					<td><strong>Details</strong></td>
				</tr>';
				foreach($piConf["fields"] as $k=>$v)	{
					$c[]=$k;
					$subContent .=$this->wizard->renderFieldOverview($ffPrefix."[fields][".$k."]",$v);
				}
				$lines[]='<tr'.$this->wizard->bgCol(3).'><td><table>'.$this->wizard->fw($subContent).'</table></td></tr>';
			}

			$lines[]='<tr'.$this->wizard->bgCol(1).'><td><strong> Edit Fields </strong></td></tr>';
//			$lines[]='<tr'.$this->wizard->bgCol(2).'><td>'.$this->wizard->fw($v[1]).'</td></tr>';
			$lines[]='<tr><td></td></tr>';




				// Admin only
			$subContent = "";
			$subContent.= $this->wizard->renderCheckBox($ffPrefix."[add_deleted]",$piConf["add_deleted"],1)."Add 'Deleted' field ".$this->wizard->whatIsThis("Whole system: If a table has a deleted column, records are never really deleted, just 'marked deleted'. Thus deleted records can actually be restored by clearing a deleted-flag later.\nNotice that all attached files are also not deleted from the server, so if you expect the table to hold some heavy size uploads, maybe you should not set this...")."<BR>";
			$subContent.= $this->wizard->renderCheckBox($ffPrefix."[add_hidden]",$piConf["add_hidden"],1)."Add 'Hidden' flag ".$this->wizard->whatIsThis("Frontend: The 'Hidden' flag will prevent the record from being displayed on the frontend.")."<BR>".$this->wizard->resImg("t_flag_hidden.png",'hspace=20','','<BR><BR>');
			$subContent.= $this->wizard->renderCheckBox($ffPrefix."[add_starttime]",$piConf["add_starttime"])."Add 'Starttime' ".$this->wizard->whatIsThis("Frontend: If a 'Starttime' is set, the record will not be visible on the website, before that date arrives.")."<BR>".$this->wizard->resImg("t_flag_starttime.png",'hspace=20','','<BR><BR>');
			$subContent.= $this->wizard->renderCheckBox($ffPrefix."[add_endtime]",$piConf["add_endtime"])."Add 'Endtime' ".$this->wizard->whatIsThis("Frontend: If a 'Endtime' is set, the record will be hidden from that date and into the future.")."<BR>".$this->wizard->resImg("t_flag_endtime.png",'hspace=20','','<BR><BR>');
			$subContent.= $this->wizard->renderCheckBox($ffPrefix."[add_access]",$piConf["add_access"])."Add 'Access group' ".$this->wizard->whatIsThis("Frontend: If a frontend user group is set for a record, only frontend users that are members of that group will be able to see the record.")."<BR>".$this->wizard->resImg("t_flag_access.png",'hspace=20','','<BR><BR>');
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Sorting
			$optValues = array(
				"crdate" => "[crdate]",
				"cruser_id" => "[cruser_id]",
				"tstamp" => "[tstamp]",
			);
			$subContent = "";
			$subContent.= $this->wizard->renderCheckBox($ffPrefix."[localization]",$piConf["localization"])."Enabled localization features".$this->wizard->whatIsThis("If set, the records will have a selector box for language and a reference field which can point back to the original default translation for the record. These features are part of the internal framework for localization.").'<BR>';
			$subContent.= $this->wizard->renderCheckBox($ffPrefix."[versioning]",$piConf["versioning"])."Enable versioning ".$this->wizard->whatIsThis("If set, you will be able to versionize records from this table. Highly recommended if the records are passed around in a workflow.").'<BR>';
			$subContent.= $this->wizard->renderCheckBox($ffPrefix."[sorting]",$piConf["sorting"])."Manual ordering of records ".$this->wizard->whatIsThis("If set, the records can be moved up and down relative to each other in the backend. Just like Content Elements. Otherwise they are sorted automatically by any field you specify").'<BR>';
			$subContent.= $this->wizard->textSetup("","If 'Manual ordering' is not set, order the table by this field:<BR>".
				$this->wizard->renderSelectBox($ffPrefix."[sorting_field]",$piConf["sorting_field"],$this->wizard->currentFields($optValues,$piConf["fields"]))."<BR>".
				$this->wizard->renderCheckBox($ffPrefix."[sorting_desc]",$piConf["sorting_desc"])." Descending");
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Type field
			$optValues = array(
				"0" => "[none]",
			);
			$subContent = "<strong>'Type-field', if any:<BR></strong>".
					$this->wizard->renderSelectBox($ffPrefix."[type_field]",$piConf["type_field"],$this->wizard->currentFields($optValues,$piConf["fields"])).
					$this->wizard->whatIsThis("A 'type-field' is the field in the table which determines how the form is rendered in the backend, eg. which fields are shown under which circumstances.\nFor instance the Content Element table 'tt_content' has a type-field, CType. The value of this field determines if the editing form shows the bodytext field as is the case when the type is 'Text' or if also the image-field should be shown as when the type is 'Text w/Image'");
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Header field
			$optValues = array(
				"0" => "[none]",
			);
			$subContent = "<strong>Label-field:<BR></strong>".
					$this->wizard->renderSelectBox($ffPrefix."[header_field]",$piConf["header_field"],$this->wizard->currentFields($optValues,$piConf["fields"])).
					$this->wizard->whatIsThis("A 'label-field' is the field used as record title in the backend.");
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Icon
			$optValues = array(
				"default.gif" => "Default (white)",
				"default_black.gif" => "Black",
				"default_gray4.gif" => "Gray",
				"default_blue.gif" => "Blue",
				"default_green.gif" => "Green",
				"default_red.gif" => "Red",
				"default_yellow.gif" => "Yellow",
				"default_purple.gif" => "Purple",
			);

			$subContent= $this->wizard->renderSelectBox($ffPrefix."[defIcon]",$piConf["defIcon"],$optValues)." Default icon ".$this->wizard->whatIsThis("All tables have at least one associated icon. Select which default icon you wish. You can always substitute the file with another.");
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Allowed on pages
			$subContent = "<strong>Allowed on pages:<BR></strong>".
					$this->wizard->renderCheckBox($ffPrefix."[allow_on_pages]",$piConf["allow_on_pages"])." Allow records from this table to be created on regular pages.";
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Allowed in "Insert Records"
			$subContent = "<strong>Allowed in 'Insert Records' field in content elements:<BR></strong>".
					$this->wizard->renderCheckBox($ffPrefix."[allow_ce_insert_records]",$piConf["allow_ce_insert_records"])." Allow records from this table to be linked to by content elements.";
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Add new button
			$subContent = "<strong>Add 'Save and new' button in forms:<BR></strong>".
					$this->wizard->renderCheckBox($ffPrefix."[save_and_new]",$piConf["save_and_new"])." Will add an additional save-button to forms by which you can save the item and instantly create the next.";
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';


			$subContent = "<strong>Notice on fieldnames:<BR></strong>".
				"Don't use fieldnames from this list of reserved names/words: <BR>
				<blockquote><em>".implode(", ",explode(",",$this->wizard->reservedTypo3Fields.",".$this->wizard->mysql_reservedFields))."</em></blockquote>";
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';



				// PRESETS:
			$selPresetBox=$this->wizard->presetBox($piConf["fields"]);

				// Fields
			$c=array(0);
			$this->wizard->usedNames=array();
			if (is_array($piConf["fields"]))	{

				// Do it for real...
				foreach($piConf["fields"] as $k=>$v)	{
					$c[]=$k;
					$subContent=$this->wizard->renderField($ffPrefix."[fields][".$k."]",$v);
					$lines[]='<tr'.$this->wizard->bgCol(2).'><td>'.$this->wizard->fw("<strong>FIELD:</strong> <em>".$v["fieldname"]."</em>").'</td></tr>';
					$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';
				}
			}

				// New field:
			$k=max($c)+1;
			$v=array();
			$lines[]='<tr'.$this->wizard->bgCol(2).'><td>'.$this->wizard->fw("<strong>NEW FIELD:</strong>").'</td></tr>';
			$subContent=$this->wizard->renderField($ffPrefix."[fields][".$k."]",$v,1);
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';


			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw("<BR><BR>Load preset fields: <BR>".$selPresetBox).'</td></tr>';
		}

		/* HOOK: Place a hook here, so additional output can be integrated */
		if(is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter']['add_cat_tables'])) {
		  foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter']['add_cat_tables'] as $_funcRef) {
		    $lines = t3lib_div::callUserFunction($_funcRef, $lines, $this);
		  }
		}

		$content = '<table border=0 cellpadding=2 cellspacing=2>'.implode("",$lines).'</table>';

		return $content;
	}

	
	
	
	/**
	 * Renders the files to be created; this was renderExtPart_tables()
	 */
	function render_extPart() {
		$WOP="[tables][".$k."]";
		$tableName=$config["tablename"];
		$tableName = $this->wizard->returnName($extKey,"tables",$tableName);

		$DBfields=array();
		$columns=array();
		$ctrl=array();
		$enFields=array();

//str_replace("\\'","'",addslashes($this->wizard->getSplitLabels($config,"title")))
		$ctrl[] = trim($this->wizard->sPS('
			"title" => "'.$this->wizard->getSplitLabels_reference($config,"title",$tableName).'",		'.$this->wizard->WOPcomment('WOP:'.$WOP.'[title]').'
			"label" => "'.($config["header_field"]?$config["header_field"]:"uid").'",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[header_field]').'
			"tstamp" => "tstamp",
			"crdate" => "crdate",
			"cruser_id" => "cruser_id",
		',0));
		$DBfields[] = trim($this->wizard->sPS("
			uid int(11) DEFAULT '0' NOT NULL auto_increment,
			pid int(11) DEFAULT '0' NOT NULL,
			tstamp int(11) unsigned DEFAULT '0' NOT NULL,
			crdate int(11) unsigned DEFAULT '0' NOT NULL,
			cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
		",0));

		if ($config["type_field"])	{
			$ctrl[] = '"type" => "'.$config["type_field"].'",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[type_field]');
		}
		if ($config["versioning"])	{
			$ctrl[] = '"versioning" => "1",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[versioning]');
			$DBfields[] = "t3ver_oid int(11) unsigned DEFAULT '0' NOT NULL,";
			$DBfields[] = "t3ver_id int(11) unsigned DEFAULT '0' NOT NULL,";
			$DBfields[] = "t3ver_label varchar(30) DEFAULT '' NOT NULL,";
		}
		if ($config["localization"])	{
			$ctrl[] = '"languageField" => "sys_language_uid",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[localization]');
			$ctrl[] = '"transOrigPointerField" => "l18n_parent",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[localization]');
			$ctrl[] = '"transOrigDiffSourceField" => "l18n_diffsource",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[localization]');

			$DBfields[] = "sys_language_uid int(11) DEFAULT '0' NOT NULL,";
			$DBfields[] = "l18n_parent int(11) DEFAULT '0' NOT NULL,";
			$DBfields[] = "l18n_diffsource mediumblob NOT NULL,";

			$columns["sys_language_uid"] = trim($this->wizard->sPS("
				'sys_language_uid' => Array (		".$this->wizard->WOPcomment('WOP:'.$WOP.'[localization]')."
					'exclude' => 1,
					'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
					'config' => Array (
						'type' => 'select',
						'foreign_table' => 'sys_language',
						'foreign_table_where' => 'ORDER BY sys_language.title',
						'items' => Array(
							Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
							Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
						)
					)
				),
			"));

			$columns["l18n_parent"] = trim($this->wizard->sPS("
				'l18n_parent' => Array (		".$this->wizard->WOPcomment('WOP:'.$WOP.'[localization]')."
					'displayCond' => 'FIELD:sys_language_uid:>:0',
					'exclude' => 1,
					'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
					'config' => Array (
						'type' => 'select',
						'items' => Array (
							Array('', 0),
						),
						'foreign_table' => '".$tableName."',
						'foreign_table_where' => 'AND ".$tableName.".pid=###CURRENT_PID### AND ".$tableName.".sys_language_uid IN (-1,0)',
					)
				),
			"));

			$columns["l18n_diffsource"] = trim($this->wizard->sPS("
				'l18n_diffsource' => Array (		".$this->wizard->WOPcomment('WOP:'.$WOP.'[localization]')."
					'config' => Array (
						'type' => 'passthrough'
					)
				),
			"));
		}
		if ($config["sorting"])	{
			$ctrl[] = '"sortby" => "sorting",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[sorting]');
			$DBfields[] = "sorting int(10) unsigned DEFAULT '0' NOT NULL,";
		} else {
			$ctrl[] = '"default_sortby" => "ORDER BY '.trim($config["sorting_field"].' '.($config["sorting_desc"]?"DESC":"")).'",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[sorting] / '.$WOP.'[sorting_field] / '.$WOP.'[sorting_desc]');
		}
		if ($config["add_deleted"])	{
			$ctrl[] = '"delete" => "deleted",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[add_deleted]');
			$DBfields[] = "deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,";
		}
		if ($config["add_hidden"])	{
			$enFields[] = '"disabled" => "hidden",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[add_hidden]');
			$DBfields[] = "hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,";
			$columns["hidden"] = trim($this->wizard->sPS('
				"hidden" => Array (		'.$this->wizard->WOPcomment('WOP:'.$WOP.'[add_hidden]').'
					"exclude" => 1,
					"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
					"config" => Array (
						"type" => "check",
						"default" => "0"
					)
				),
			'));
		}
		if ($config["add_starttime"])	{
			$enFields[] = '"starttime" => "starttime",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[add_starttime]');
			$DBfields[] = "starttime int(11) unsigned DEFAULT '0' NOT NULL,";
			$columns["starttime"] = trim($this->wizard->sPS('
				"starttime" => Array (		'.$this->wizard->WOPcomment('WOP:'.$WOP.'[add_starttime]').'
					"exclude" => 1,
					"label" => "LLL:EXT:lang/locallang_general.php:LGL.starttime",
					"config" => Array (
						"type" => "input",
						"size" => "8",
						"max" => "20",
						"eval" => "date",
						"default" => "0",
						"checkbox" => "0"
					)
				),
			'));
		}
		if ($config["add_endtime"])	{
			$enFields[] = '"endtime" => "endtime",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[add_endtime]');
			$DBfields[] = "endtime int(11) unsigned DEFAULT '0' NOT NULL,";
			$columns["endtime"] = trim($this->wizard->sPS('
				"endtime" => Array (		'.$this->wizard->WOPcomment('WOP:'.$WOP.'[add_endtime]').'
					"exclude" => 1,
					"label" => "LLL:EXT:lang/locallang_general.php:LGL.endtime",
					"config" => Array (
						"type" => "input",
						"size" => "8",
						"max" => "20",
						"eval" => "date",
						"checkbox" => "0",
						"default" => "0",
						"range" => Array (
							"upper" => mktime(0,0,0,12,31,2020),
							"lower" => mktime(0,0,0,date("m")-1,date("d"),date("Y"))
						)
					)
				),
			'));
		}
		if ($config["add_access"])	{
			$enFields[] = '"fe_group" => "fe_group",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[add_access]');
			$DBfields[] = "fe_group int(11) DEFAULT '0' NOT NULL,";
			$columns["fe_group"] = trim($this->wizard->sPS('
				"fe_group" => Array (		'.$this->wizard->WOPcomment('WOP:'.$WOP.'[add_access]').'
					"exclude" => 1,
					"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
					"config" => Array (
						"type" => "select",
						"items" => Array (
							Array("", 0),
							Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
							Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
							Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
						),
						"foreign_table" => "fe_groups"
					)
				),
			'));
		}
			// Add enable fields in header:
		if (is_array($enFields) && count($enFields))	{
			$ctrl[]=trim($this->wizard->wrapBody('
				"enablecolumns" => Array (		'.$this->wizard->WOPcomment('WOP:'.$WOP.'[add_hidden] / '.$WOP.'[add_starttime] / '.$WOP.'[add_endtime] / '.$WOP.'[add_access]').'
				',implode(chr(10),$enFields),'
				),
			'));
		}
			// Add dynamic config file.
		$ctrl[]= '"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",';
		$ctrl[]= '"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_'.$tableName.'.gif",';

		if ($config["allow_on_pages"])	{
			$this->wizard->ext_tables[]=$this->wizard->sPS('
				'.$this->wizard->WOPcomment('WOP:'.$WOP.'[allow_on_pages]').'
				t3lib_extMgm::allowTableOnStandardPages("'.$tableName.'");
			');
		}
		if ($config["allow_ce_insert_records"])	{
			$this->wizard->ext_tables[]=$this->wizard->sPS('
				'.$this->wizard->WOPcomment('WOP:'.$WOP.'[allow_ce_insert_records]').'
				t3lib_extMgm::addToInsertRecords("'.$tableName.'");
			');
		}
		if ($config["save_and_new"])	{
			$this->wizard->ext_localconf[]=trim($this->wizard->wrapBody("
				t3lib_extMgm::addUserTSConfig('
					","options.saveDocNew.".$tableName."=1","
				');
			"));
		}

		if (is_array($config["fields"]))	{
			reset($config["fields"]);
			while(list($i,$fConf)=each($config["fields"]))	{
				$this->makeFieldTCA($DBfields,$columns,$fConf,$WOP."[fields][".$i."]",$tableName,$extKey);
			}
		}



			// Finalize tables.sql:
		$DBfields[]=$this->wizard->sPS('
			PRIMARY KEY (uid),
			KEY parent (pid)
		');
		$createTable = $this->wizard->wrapBody('
			#
			# Table structure for table \''.$tableName.'\'
			#
			CREATE TABLE '.$tableName.' (
		', implode(chr(10),$DBfields), '
			);
		');
		$this->wizard->ext_tables_sql[]=chr(10).$createTable.chr(10);

			// Finalize tca.php:
		$tca_file="";
		list($typeList,$palList) = $this->implodeColumns($columns);
		$tca_file.=$this->wizard->wrapBody('
			$TCA["'.$tableName.'"] = Array (
				"ctrl" => $TCA["'.$tableName.'"]["ctrl"],
				"interface" => Array (
					"showRecordFieldList" => "'.implode(",",array_keys($columns)).'"
				),
				"feInterface" => $TCA["'.$tableName.'"]["feInterface"],
				"columns" => Array (
			', trim(implode(chr(10),$columns))	,'
				),
				"types" => Array (
					"0" => Array("showitem" => "'.$typeList.'")
				),
				"palettes" => Array (
					"1" => Array("showitem" => "'.$palList.'")
				)
			);
		',2);
		$this->wizard->ext_tca[]=chr(10).$tca_file.chr(10);

			// Finalize ext_tables.php:
		$this->wizard->ext_tables[]=$this->wizard->wrapBody('
			$TCA["'.$tableName.'"] = Array (
				"ctrl" => Array (
			', implode(chr(10),$ctrl)	,'
				),
				"feInterface" => Array (
					"fe_admin_fieldList" => "'.implode(", ",array_keys($columns)).'",
				)
			);
		',2);


				// Add wizard icon
			$this->wizard->addFileToFileArray($pathSuffix."icon_".$tableName.".gif",t3lib_div::getUrl(t3lib_extMgm::extPath("kickstarter")."res/".$config["defIcon"]));

	}

}



// Include ux_class extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kickstarter/sections/class.tx_kickstarter_section_tables.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kickstarter/sections/class.tx_kickstarter_section_tables.php']);
}

?>