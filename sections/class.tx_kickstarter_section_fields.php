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

require_once(t3lib_extMgm::extPath("kickstarter")."class.tx_kickstarter_sectionbase.php");
 
class tx_kickstarter_section_fields extends tx_kickstarter_sectionbase {
	var $catName = "";

	/**
	 * Renders the form in the kickstarter; this was add_cat_fields()
	 */
	function render_wizard() {
		$lines=array();

		$catID = "fields";
		$action = explode(":",$this->wizard->modData["wizAction"]);
		if ($action[0]=="edit")	{
			$this->wizard->regNewEntry($catID,$action[1]);
			$lines = $this->wizard->catHeaderLines($lines,$catID,$this->wizard->options[$catID],"&nbsp;",$action[1]);
			$piConf = $this->wizard->wizArray[$catID][$action[1]];
			$ffPrefix='['.$catID.']['.$action[1].']';

		}


				// Header field
			$optValues = array(
				"tt_content" => "Content (tt_content)",
				"fe_users" => "Frontend Users (fe_users)",
				"fe_groups" => "Frontend Groups (fe_groups)",
				"be_users" => "Backend Users (be_users)",
				"be_groups" => "Backend Groups (be_groups)",
				"tt_news" => "News (tt_news)",
				"tt_address" => "Address (tt_address)",
				"pages" => "Pages (pages)",
			);

			foreach($GLOBALS['TCA'] as $tablename => $tableTCA) {
				if(!$optValues[$tablename]) {
					$optValues[$tablename] = $GLOBALS['LANG']->sL($tableTCA['ctrl']['title']).' ('.$tablename.')';
				}
			}

			$subContent = "<strong>Which table:<BR></strong>".
					$this->wizard->renderSelectBox($ffPrefix."[which_table]",$piConf["which_table"],$optValues).
					$this->wizard->whatIsThis("Select the table which should be extended with these extra fields.");
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).
				'<input type="hidden" name="'.$this->wizard->piFieldName("wizArray_upd").$ffPrefix.'[title]" value="'.($piConf["which_table"]?$optValues[$piConf["which_table"]]:"").'"></td></tr>';





				// PRESETS:
			$selPresetBox=$this->wizard->presetBox($piConf["fields"]);

				// FIelds
			$c=array(0);
			$this->wizard->usedNames=array();
			if (is_array($piConf["fields"]))	{
				$piConf["fields"] = $this->wizard->cleanFieldsAndDoCommands($piConf["fields"],$catID,$action[1]);

					// Do it for real...
				reset($piConf["fields"]);
				while(list($k,$v)=each($piConf["fields"]))	{
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

		/* HOOK: Place a hook here, so additional output can be integrated */
		if(is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter']['add_cat_fields'])) {
		  foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter']['add_cat_fields'] as $_funcRef) {
		    $lines = t3lib_div::callUserFunction($_funcRef, $lines, $this);
		  }
		}

		$content = '<table border=0 cellpadding=2 cellspacing=2>'.implode("",$lines).'</table>';
		return $content;
	}








	/**
	 * Renders the extension PHP codee
	 */
	function render_extPart($k,$config,$extKey) {
		$WOP="[fields][".$k."]";
		$tableName=$config["which_table"];
	#	$tableName = $this->wizard->returnName($extKey,"fields",$tableName);
#		$prefix = "tx_".str_replace("_","",$extKey)."_";
		$prefix = $this->wizard->returnName($extKey,"fields")."_";

		$DBfields=array();
		$columns=array();
		$ctrl=array();
		$enFields=array();

		if (is_array($config["fields"]))	{
			reset($config["fields"]);
			while(list($i,$fConf)=each($config["fields"]))	{
				$fConf["fieldname"] = $prefix.$fConf["fieldname"];
				$this->makeFieldTCA($DBfields,$columns,$fConf,$WOP."[fields][".$i."]",$tableName,$extKey);
			}
		}

		if ($tableName=="tt_address")	$this->wizard->EM_CONF_presets["dependencies"][]="tt_address";
		if ($tableName=="tt_news")	$this->wizard->EM_CONF_presets["dependencies"][]="tt_news";
		if (t3lib_div::inList("tt_content,fe_users,fe_groups",$tableName))	$this->wizard->EM_CONF_presets["dependencies"][]="cms";

		$createTable = $this->wizard->wrapBody('
			#
			# Table structure for table \''.$tableName.'\'
			#
			CREATE TABLE '.$tableName.' (
		', ereg_replace(",[[:space:]]*$","",implode(chr(10),$DBfields)), '

			);
		');
		$this->wizard->ext_tables_sql[]=chr(10).$createTable.chr(10);


			// Finalize ext_tables.php:
		$this->wizard->ext_tables[]=$this->wizard->wrapBody('
			$tempColumns = Array (
				', implode(chr(10),$columns)	,'
			);
		');


		list($typeList) = $this->implodeColumns($columns);
		$applyToAll=1;
		if (is_array($this->wizard->wizArray["pi"]))	{
			reset($this->wizard->wizArray["pi"]);
			while(list(,$fC)=each($this->wizard->wizArray["pi"]))	{
				if ($fC["apply_extended"]==$k)	{
					$applyToAll=0;
					$this->wizard->_apply_extended_types[$k]=$typeList;
				}
			}
		}
		$this->wizard->ext_tables[]=$this->wizard->sPS('
			t3lib_div::loadTCA("'.$tableName.'");
			t3lib_extMgm::addTCAcolumns("'.$tableName.'",$tempColumns,1);
			'.($applyToAll?'t3lib_extMgm::addToAllTCAtypes("'.$tableName.'","'.$typeList.'");':'').'
		');
	}

}



	function implodeColumns($columns)	{
		reset($columns);
		$outems=array();
		$paltems=array();
		$c=0;
		$hiddenFlag=0;
		$titleDivFlag=0;
		while(list($fN)=each($columns))	{
			if (!$hiddenFlag || !t3lib_div::inList("starttime,endtime,fe_group",$fN))	{
				$outTem = array($fN,"","","","");
				$outTem[3] = $this->wizard->_typeP[$fN];
				if ($c==0)	$outTem[4]="1-1-1";
				if ($fN=="title")	{
					$outTem[4]="2-2-2";
					$titleDivFlag=1;
				} elseif ($titleDivFlag)	{
					$outTem[4]="3-3-3";
					$titleDivFlag=0;
				}
				if ($fN=="hidden")	{
					$outTem[2]="1";
					$hiddenFlag=1;
				}
				$outems[] = str_replace(",","",str_replace(chr(9),";",trim(str_replace(";","",implode(chr(9),$outTem)))));
				$c++;
			} else {
				$paltems[]=$fN;
			}
		}
		return array(implode(", ",$outems),implode(", ",$paltems));
	}
	function makeFieldTCA(&$DBfields,&$columns,$fConf,$WOP,$table,$extKey)	{
		if (!(string)$fConf["type"])	return;
		$id = $table."_".$fConf["fieldname"];
#debug($fConf);

		$configL=array();
		$t = (string)$fConf["type"];
		switch($t)	{
			case "input":
			case "input+":
				$isString =1;
				$configL[]='"type" => "input",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[type]');
				$configL[]='"size" => "'.t3lib_div::intInRange($fConf["conf_size"],5,48,30).'",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_size]');
				if (intval($fConf["conf_max"]))	$configL[]='"max" => "'.t3lib_div::intInRange($fConf["conf_max"],1,255).'",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_max]');

				$evalItems=array();
				if ($fConf["conf_required"])	{$evalItems[0][] = "required";			$evalItems[1][] = $WOP.'[conf_required]';}

				if ($t=="input+")	{
					$isString = !$fConf["conf_eval"] || t3lib_div::inList("alphanum,upper,lower",$fConf["conf_eval"]);
					if ($fConf["conf_varchar"] && $isString)		{$evalItems[0][] = "trim";			$evalItems[1][] = $WOP.'[conf_varchar]';}
					if ($fConf["conf_eval"]=="int+")	{
						$configL[]='"range" => Array ("lower"=>0,"upper"=>1000),	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_eval] = int+ results in a range setting');
						$fConf["conf_eval"]="int";
					}
					if ($fConf["conf_eval"])		{$evalItems[0][] = $fConf["conf_eval"];			$evalItems[1][] = $WOP.'[conf_eval]';}
					if ($fConf["conf_check"])	$configL[]='"checkbox" => "'.($isString?"":"0").'",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_check]');

					if ($fConf["conf_stripspace"])		{$evalItems[0][] = "nospace";			$evalItems[1][] = $WOP.'[conf_stripspace]';}
					if ($fConf["conf_pass"])		{$evalItems[0][] = "password";			$evalItems[1][] = $WOP.'[conf_pass]';}
					if ($fConf["conf_unique"])	{
						if ($fConf["conf_unique"]=="L")		{$evalItems[0][] = "uniqueInPid";			$evalItems[1][] = $WOP.'[conf_unique] = Local (unique in this page (PID))';}
						if ($fConf["conf_unique"]=="G")		{$evalItems[0][] = "unique";			$evalItems[1][] = $WOP.'[conf_unique] = Global (unique in whole database)';}
					}

					$wizards =array();
					if ($fConf["conf_wiz_color"])	{
						$wizards[] = trim($this->wizard->sPS('
							'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_wiz_color]').'
							"color" => Array(
								"title" => "Color:",
								"type" => "colorbox",
								"dim" => "12x12",
								"tableStyle" => "border:solid 1px black;",
								"script" => "wizard_colorpicker.php",
								"JSopenParams" => "height=300,width=250,status=0,menubar=0,scrollbars=1",
							),
						'));
					}
					if ($fConf["conf_wiz_link"])	{
						$wizards[] = trim($this->wizard->sPS('
							'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_wiz_link]').'
							"link" => Array(
								"type" => "popup",
								"title" => "Link",
								"icon" => "link_popup.gif",
								"script" => "browse_links.php?mode=wizard",
								"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
							),
						'));
					}
					if (count($wizards))	{
						$configL[]=trim($this->wizard->wrapBody('
							"wizards" => Array(
								"_PADDING" => 2,
								',implode(chr(10),$wizards),'
							),
						'));
					}
				} else {
					if ($fConf["conf_varchar"])		{$evalItems[0][] = "trim";			$evalItems[1][] = $WOP.'[conf_varchar]';}
				}

				if (count($evalItems))	$configL[]='"eval" => "'.implode(",",$evalItems[0]).'",	'.$this->wizard->WOPcomment('WOP:'.implode(" / ",$evalItems[1]));

				if (!$isString)	{
					$DBfields[] = $fConf["fieldname"]." int(11) DEFAULT '0' NOT NULL,";
				} elseif (!$fConf["conf_varchar"])		{
					$DBfields[] = $fConf["fieldname"]." tinytext NOT NULL,";
				} else {
					$varCharLn = (intval($fConf["conf_max"])?t3lib_div::intInRange($fConf["conf_max"],1,255):255);
					$DBfields[] = $fConf["fieldname"]." ".($varCharLn>$this->wizard->charMaxLng?'var':'')."char(".$varCharLn.") DEFAULT '' NOT NULL,";
				}
			break;
			case "link":
				$DBfields[] = $fConf["fieldname"]." tinytext NOT NULL,";
				$configL[]=trim($this->wizard->sPS('
					"type" => "input",
					"size" => "15",
					"max" => "255",
					"checkbox" => "",
					"eval" => "trim",
					"wizards" => Array(
						"_PADDING" => 2,
						"link" => Array(
							"type" => "popup",
							"title" => "Link",
							"icon" => "link_popup.gif",
							"script" => "browse_links.php?mode=wizard",
							"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
						)
					)
				'));
			break;
			case "datetime":
			case "date":
				$DBfields[] = $fConf["fieldname"]." int(11) DEFAULT '0' NOT NULL,";
				$configL[]=trim($this->wizard->sPS('
					"type" => "input",
					"size" => "'.($t=="datetime"?12:8).'",
					"max" => "20",
					"eval" => "'.$t.'",
					"checkbox" => "0",
					"default" => "0"
				'));
			break;
			case "integer":
				$DBfields[] = $fConf["fieldname"]." int(11) DEFAULT '0' NOT NULL,";
				$configL[]=trim($this->wizard->sPS('
					"type" => "input",
					"size" => "4",
					"max" => "4",
					"eval" => "int",
					"checkbox" => "0",
					"range" => Array (
						"upper" => "1000",
						"lower" => "10"
					),
					"default" => 0
				'));
			break;
			case "textarea":
			case "textarea_nowrap":
				$DBfields[] = $fConf["fieldname"]." text NOT NULL,";
				$configL[]='"type" => "text",';
				if ($t=="textarea_nowrap")	{
					$configL[]='"wrap" => "OFF",';
				}
				$configL[]='"cols" => "'.t3lib_div::intInRange($fConf["conf_cols"],5,48,30).'",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_cols]');
				$configL[]='"rows" => "'.t3lib_div::intInRange($fConf["conf_rows"],1,20,5).'",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rows]');
				if ($fConf["conf_wiz_example"])	{
					$wizards =array();
					$wizards[] = trim($this->wizard->sPS('
						'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_wiz_example]').'
						"example" => Array(
							"title" => "Example Wizard:",
							"type" => "script",
							"notNewRecords" => 1,
							"icon" => t3lib_extMgm::extRelPath("'.$extKey.'")."'.$id.'/wizard_icon.gif",
							"script" => t3lib_extMgm::extRelPath("'.$extKey.'")."'.$id.'/index.php",
						),
					'));

					$cN = $this->wizard->returnName($extKey,"class",$id."wiz");
					$this->wizard->writeStandardBE_xMod(
						$extKey,
						array("title"=>"Example Wizard title..."),
						$id.'/',
						$cN,
						0,
						$id."wiz"
					);
					$this->wizard->addFileToFileArray($id."/wizard_icon.gif",t3lib_div::getUrl(t3lib_extMgm::extPath("kickstarter")."res/notfound.gif"));

					$configL[]=trim($this->wizard->wrapBody('
						"wizards" => Array(
							"_PADDING" => 2,
							',implode(chr(10),$wizards),'
						),
					'));
				}
			break;
			case "textarea_rte":
				$DBfields[] = $fConf["fieldname"]." text NOT NULL,";
				$configL[]='"type" => "text",';
				$configL[]='"cols" => "30",';
				$configL[]='"rows" => "5",';
				if ($fConf["conf_rte_fullscreen"])	{
					$wizards =array();
					$wizards[] = trim($this->wizard->sPS('
						'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rte_fullscreen]').'
						"RTE" => Array(
							"notNewRecords" => 1,
							"RTEonly" => 1,
							"type" => "script",
							"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
							"icon" => "wizard_rte2.gif",
							"script" => "wizard_rte.php",
						),
					'));
					$configL[]=trim($this->wizard->wrapBody('
						"wizards" => Array(
							"_PADDING" => 2,
							',implode(chr(10),$wizards),'
						),
					'));
				}

				$rteImageDir = "";
				if ($fConf["conf_rte_separateStorageForImages"] && t3lib_div::inList("moderate,basic,custom",$fConf["conf_rte"]))	{
					$this->wizard->EM_CONF_presets["createDirs"][]=$this->wizard->ulFolder($extKey)."rte/";
					$rteImageDir = "|imgpath=".$this->wizard->ulFolder($extKey)."rte/";
				}

				$transformation="ts_images-ts_reglinks";
				if ($fConf["conf_mode_cssOrNot"] && t3lib_div::inList("moderate,custom",$fConf["conf_rte"]))	{
					$transformation="ts_css";
				}


				switch($fConf["conf_rte"])	{
					case "tt_content":
						$typeP = 'richtext[paste|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts]';
					break;
					case "moderate":
						$typeP = 'richtext[*]:rte_transform[mode='.$transformation.''.$rteImageDir.']';
					break;
					case "basic":
						$typeP = 'richtext[cut|copy|paste|formatblock|textcolor|bold|italic|underline|left|center|right|orderedlist|unorderedlist|outdent|indent|link|table|image|line|chMode]:rte_transform[mode=ts_css'.$rteImageDir.']';
						$this->wizard->ext_localconf[]=trim($this->wizard->wrapBody("
								t3lib_extMgm::addPageTSConfig('

									# ***************************************************************************************
									# CONFIGURATION of RTE in table \"".$table."\", field \"".$fConf["fieldname"]."\"
									# ***************************************************************************************

									",trim($this->wizard->slashValueForSingleDashes(str_replace(chr(9),"  ",$this->wizard->sPS("
										RTE.config.".$table.".".$fConf["fieldname"]." {
											hidePStyleItems = H1, H4, H5, H6
											proc.exitHTMLparser_db=1
											proc.exitHTMLparser_db {
												keepNonMatchedTags=1
												tags.font.allowedAttribs= color
												tags.font.rmTagIfNoAttrib = 1
												tags.font.nesting = global
											}
										}
									")))),"
								');
						",0));
					break;
					case "none":
						$typeP = 'richtext[*]';
					break;
					case "custom":
						$enabledButtons=array();
						$traverseList = explode(",","cut,copy,paste,formatblock,class,fontstyle,fontsize,textcolor,bold,italic,underline,left,center,right,orderedlist,unorderedlist,outdent,indent,link,table,image,line,user,chMode");
						$HTMLparser=array();
						$fontAllowedAttrib=array();
						$allowedTags_WOP = array();
						$allowedTags=array();
						while(list(,$lI)=each($traverseList))	{
							$nothingDone=0;
							if ($fConf["conf_rte_b_".$lI])	{
								$enabledButtons[]=$lI;
								switch($lI)	{
									case "formatblock":
									case "left":
									case "center":
									case "right":
										$allowedTags[]="div";
										$allowedTags[]="p";
									break;
									case "class":
										$allowedTags[]="span";
									break;
									case "fontstyle":
										$allowedTags[]="font";
										$fontAllowedAttrib[]="face";
									break;
									case "fontsize":
										$allowedTags[]="font";
										$fontAllowedAttrib[]="size";
									break;
									case "textcolor":
										$allowedTags[]="font";
										$fontAllowedAttrib[]="color";
									break;
									case "bold":
										$allowedTags[]="b";
										$allowedTags[]="strong";
									break;
									case "italic":
										$allowedTags[]="i";
										$allowedTags[]="em";
									break;
									case "underline":
										$allowedTags[]="u";
									break;
									case "orderedlist":
										$allowedTags[]="ol";
										$allowedTags[]="li";
									break;
									case "unorderedlist":
										$allowedTags[]="ul";
										$allowedTags[]="li";
									break;
									case "outdent":
									case "indent":
										$allowedTags[]="blockquote";
									break;
									case "link":
										$allowedTags[]="a";
									break;
									case "table":
										$allowedTags[]="table";
										$allowedTags[]="tr";
										$allowedTags[]="td";
									break;
									case "image":
										$allowedTags[]="img";
									break;
									case "line":
										$allowedTags[]="hr";
									break;
									default:
										$nothingDone=1;
									break;
								}
								if (!$nothingDone)	$allowedTags_WOP[] = $WOP.'[conf_rte_b_'.$lI.']';
							}
						}
						if (count($fontAllowedAttrib))	{
							$HTMLparser[]="tags.font.allowedAttribs = ".implode(",",$fontAllowedAttrib);
							$HTMLparser[]="tags.font.rmTagIfNoAttrib = 1";
							$HTMLparser[]="tags.font.nesting = global";
						}
						if (count($enabledButtons))	{
							$typeP = 'richtext['.implode("|",$enabledButtons).']:rte_transform[mode='.$transformation.''.$rteImageDir.']';
						}

						$rte_colors=array();
						$setupUpColors=array();
						for ($a=1;$a<=3;$a++)	{
							if ($fConf["conf_rte_color".$a])	{
								$rte_colors[$id.'_color'.$a]=trim($this->wizard->sPS('
									'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rte_color'.$a.']').'
									'.$id.'_color'.$a.' {
										name = Color '.$a.'
										value = '.$fConf["conf_rte_color".$a].'
									}
								'));
								$setupUpColors[]=trim($fConf["conf_rte_color".$a]);
							}
						}

						$rte_classes=array();
						for ($a=1;$a<=6;$a++)	{
							if ($fConf["conf_rte_class".$a])	{
								$rte_classes[$id.'_class'.$a]=trim($this->wizard->sPS('
									'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rte_class'.$a.']').'
									'.$id.'_class'.$a.' {
										name = '.$fConf["conf_rte_class".$a].'
										value = '.$fConf["conf_rte_class".$a."_style"].'
									}
								'));
							}
						}

						$PageTSconfig= Array();
						if ($fConf["conf_rte_removecolorpicker"])	{
							$PageTSconfig[]="	".$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rte_removecolorpicker]');
							$PageTSconfig[]="disableColorPicker = 1";
						}
						if (count($rte_classes))	{
							$PageTSconfig[]="	".$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rte_class*]');
							$PageTSconfig[]="classesParagraph = ".implode(", ",array_keys($rte_classes));
							$PageTSconfig[]="classesCharacter = ".implode(", ",array_keys($rte_classes));
							if (in_array("p",$allowedTags) || in_array("div",$allowedTags))	{
								$HTMLparser[]="	".$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rte_class*]');
								if (in_array("p",$allowedTags))	{$HTMLparser[]="p.fixAttrib.class.list = ,".implode(",",array_keys($rte_classes));}
								if (in_array("div",$allowedTags))	{$HTMLparser[]="div.fixAttrib.class.list = ,".implode(",",array_keys($rte_classes));}
							}
						}
						if (count($rte_colors))		{
							$PageTSconfig[]="	".$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rte_color*]');
							$PageTSconfig[]="colors = ".implode(", ",array_keys($rte_colors));

							if (in_array("color",$fontAllowedAttrib) && $fConf["conf_rte_removecolorpicker"])	{
								$HTMLparser[]="	".$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rte_removecolorpicker]');
								$HTMLparser[]="tags.font.fixAttrib.color.list = ,".implode(",",$setupUpColors);
								$HTMLparser[]="tags.font.fixAttrib.color.removeIfFalse = 1";
							}
						}
						if (!strcmp($fConf["conf_rte_removePdefaults"],1))	{
							$PageTSconfig[]="	".$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rte_removePdefaults]');
							$PageTSconfig[]="hidePStyleItems = H1, H2, H3, H4, H5, H6, PRE";
						} elseif ($fConf["conf_rte_removePdefaults"]=="H2H3")	{
							$PageTSconfig[]="	".$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rte_removePdefaults]');
							$PageTSconfig[]="hidePStyleItems = H1, H4, H5, H6";
						} else {
							$allowedTags[]="h1";
							$allowedTags[]="h2";
							$allowedTags[]="h3";
							$allowedTags[]="h4";
							$allowedTags[]="h5";
							$allowedTags[]="h6";
							$allowedTags[]="pre";
						}


						$allowedTags = array_unique($allowedTags);
						if (count($allowedTags))	{
							$HTMLparser[]="	".$this->wizard->WOPcomment('WOP:'.implode(" / ",$allowedTags_WOP));
							$HTMLparser[]='allowTags = '.implode(", ",$allowedTags);
						}
						if ($fConf["conf_rte_div_to_p"])	{
							$HTMLparser[]="	".$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rte_div_to_p]');
							$HTMLparser[]='tags.div.remap = P';
						}
						if (count($HTMLparser))	{
							$PageTSconfig[]=trim($this->wizard->wrapBody('
								proc.exitHTMLparser_db=1
								proc.exitHTMLparser_db {
									',implode(chr(10),$HTMLparser),'
								}
							'));
						}

						$finalPageTSconfig=array();
						if (count($rte_colors))		{
							$finalPageTSconfig[]=trim($this->wizard->wrapBody('
								RTE.colors {
								',implode(chr(10),$rte_colors),'
								}
							'));
						}
						if (count($rte_classes))		{
							$finalPageTSconfig[]=trim($this->wizard->wrapBody('
								RTE.classes {
								',implode(chr(10),$rte_classes),'
								}
							'));
						}
						if (count($PageTSconfig))		{
							$finalPageTSconfig[]=trim($this->wizard->wrapBody('
								RTE.config.'.$table.'.'.$fConf["fieldname"].' {
								',implode(chr(10),$PageTSconfig),'
								}
							'));
						}
						if (count($finalPageTSconfig))	{
							$this->wizard->ext_localconf[]=trim($this->wizard->wrapBody("
								t3lib_extMgm::addPageTSConfig('

									# ***************************************************************************************
									# CONFIGURATION of RTE in table \"".$table."\", field \"".$fConf["fieldname"]."\"
									# ***************************************************************************************

								",trim($this->wizard->slashValueForSingleDashes(str_replace(chr(9),"  ",implode(chr(10).chr(10),$finalPageTSconfig)))),"
								');
							",0));
						}
					break;
				}
				$this->wizard->_typeP[$fConf["fieldname"]]	= $typeP;
			break;
			case "check":
			case "check_4":
			case "check_10":
				$configL[]='"type" => "check",';
				if ($t=="check")	{
					$DBfields[] = $fConf["fieldname"]." tinyint(3) unsigned DEFAULT '0' NOT NULL,";
					if ($fConf["conf_check_default"])	$configL[]='"default" => 1,	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_check_default]');
				} else {
					$DBfields[] = $fConf["fieldname"]." int(11) unsigned DEFAULT '0' NOT NULL,";
				}
				if ($t=="check_4" || $t=="check_10")	{
					$configL[]='"cols" => 4,';
					$cItems=array();
#					$aMax = ($t=="check_4"?4:10);
					$aMax = intval($fConf["conf_numberBoxes"]);
					for($a=0;$a<$aMax;$a++)	{
//						$cItems[]='Array("'.($fConf["conf_boxLabel_".$a]?str_replace("\\'","'",addslashes($this->wizard->getSplitLabels($fConf,"conf_boxLabel_".$a))):'English Label '.($a+1).'|Danish Label '.($a+1).'|German Label '.($a+1).'| etc...').'", ""),';
						$cItems[]='Array("'.addslashes($this->wizard->getSplitLabels_reference($fConf,"conf_boxLabel_".$a,$table.".".$fConf["fieldname"].".I.".$a)).'", ""),';
					}
					$configL[]=trim($this->wizard->wrapBody('
						"items" => Array (
							',implode(chr(10),$cItems),'
						),
					'));
				}
			break;
			case "radio":
			case "select":
				$configL[]='"type" => "'.($t=="select"?"select":"radio").'",';
				$notIntVal=0;
				$len=array();
				for($a=0;$a<t3lib_div::intInRange($fConf["conf_select_items"],1,20);$a++)	{
					$val = $fConf["conf_select_itemvalue_".$a];
					$notIntVal+= t3lib_div::testInt($val)?0:1;
					$len[]=strlen($val);
					if ($fConf["conf_select_icons"] && $t=="select")	{
						$icon = ', t3lib_extMgm::extRelPath("'.$extKey.'")."'."selicon_".$id."_".$a.".gif".'"';
										// Add wizard icon
						$this->wizard->addFileToFileArray("selicon_".$id."_".$a.".gif",t3lib_div::getUrl(t3lib_extMgm::extPath("kickstarter")."res/wiz.gif"));
					} else $icon="";
//					$cItems[]='Array("'.str_replace("\\'","'",addslashes($this->wizard->getSplitLabels($fConf,"conf_select_item_".$a))).'", "'.addslashes($val).'"'.$icon.'),';
					$cItems[]='Array("'.addslashes($this->wizard->getSplitLabels_reference($fConf,"conf_select_item_".$a,$table.".".$fConf["fieldname"].".I.".$a)).'", "'.addslashes($val).'"'.$icon.'),';
				}
				$configL[]=trim($this->wizard->wrapBody('
					'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_select_items]').'
					"items" => Array (
						',implode(chr(10),$cItems),'
					),
				'));
				if ($fConf["conf_select_pro"] && $t=="select")	{
					$cN = $this->wizard->returnName($extKey,"class",$id);
					$configL[]='"itemsProcFunc" => "'.$cN.'->main",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_select_pro]');

					$classContent= $this->wizard->sPS('
						class '.$cN.' {
							function main(&$params,&$pObj)	{
/*								debug("Hello World!",1);
								debug("\$params:",1);
								debug($params);
								debug("\$pObj:",1);
								debug($pObj);
	*/
									// Adding an item!
								$params["items"][]=Array($pObj->sL("Added label by PHP function|Tilføjet Dansk tekst med PHP funktion"), 999);

								// No return - the $params and $pObj variables are passed by reference, so just change content in then and it is passed back automatically...
							}
						}
					');

					$this->wizard->addFileToFileArray("class.".$cN.".php",$this->wizard->PHPclassFile($extKey,"class.".$cN.".php",$classContent,"Class/Function which manipulates the item-array for table/field ".$id."."));

					$this->wizard->ext_tables[]=$this->wizard->sPS('
						'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_select_pro]:').'
						if (TYPO3_MODE=="BE")	include_once(t3lib_extMgm::extPath("'.$extKey.'")."'.'class.'.$cN.'.php");
					');
				}

				$numberOfRelations = t3lib_div::intInRange($fConf["conf_relations"],1,100);
				if ($t=="select")	{
					$configL[]='"size" => '.t3lib_div::intInRange($fConf["conf_relations_selsize"],1,100).',	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_relations_selsize]');
					$configL[]='"maxitems" => '.$numberOfRelations.',	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_relations]');
				}

				if ($numberOfRelations>1 && $t=="select")	{
					if ($numberOfRelations*4 < 256)	{
						$DBfields[] = $fConf["fieldname"]." varchar(".($numberOfRelations*4).") DEFAULT '' NOT NULL,";
					} else {
						$DBfields[] = $fConf["fieldname"]." text NOT NULL,";
					}
				} elseif ($notIntVal)	{
					$varCharLn = t3lib_div::intInRange(max($len),1);
					$DBfields[] = $fConf["fieldname"]." ".($varCharLn>$this->wizard->charMaxLng?'var':'')."char(".$varCharLn.") DEFAULT '' NOT NULL,";
				} else {
					$DBfields[] = $fConf["fieldname"]." int(11) unsigned DEFAULT '0' NOT NULL,";
				}
			break;
			case "rel":
				if ($fConf["conf_rel_type"]=="group")	{
					$configL[]='"type" => "group",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rel_type]');
					$configL[]='"internal_type" => "db",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rel_type]');
				} else {
					$configL[]='"type" => "select",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rel_type]');
				}

				if ($fConf["conf_rel_type"]!="group" && $fConf["conf_relations"]==1 && $fConf["conf_rel_dummyitem"])	{
					$configL[]=trim($this->wizard->wrapBody('
						'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rel_dummyitem]').'
						"items" => Array (
							','Array("",0),','
						),
					'));
				}

				if (t3lib_div::inList("tt_content,fe_users,fe_groups",$fConf["conf_rel_table"]))		$this->wizard->EM_CONF_presets["dependencies"][]="cms";

				if ($fConf["conf_rel_table"]=="_CUSTOM")	{
					$fConf["conf_rel_table"]=$fConf["conf_custom_table_name"]?$fConf["conf_custom_table_name"]:"NO_TABLE_NAME_AVAILABLE";
				}

				if ($fConf["conf_rel_type"]=="group")	{
					$configL[]='"allowed" => "'.($fConf["conf_rel_table"]!="_ALL"?$fConf["conf_rel_table"]:"*").'",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rel_table]');
					if ($fConf["conf_rel_table"]=="_ALL")	$configL[]='"prepend_tname" => 1,	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rel_table]=_ALL');
				} else {
					switch($fConf["conf_rel_type"])	{
						case "select_cur":
							$where="AND ".$fConf["conf_rel_table"].".pid=###CURRENT_PID### ";
						break;
						case "select_root":
							$where="AND ".$fConf["conf_rel_table"].".pid=###SITEROOT### ";
						break;
						case "select_storage":
							$where="AND ".$fConf["conf_rel_table"].".pid=###STORAGE_PID### ";
						break;
						default:
							$where="";
						break;
					}
					$configL[]='"foreign_table" => "'.$fConf["conf_rel_table"].'",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rel_table]');
					$configL[]='"foreign_table_where" => "'.$where.'ORDER BY '.$fConf["conf_rel_table"].'.uid",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_rel_type]');
				}
				$configL[]='"size" => '.t3lib_div::intInRange($fConf["conf_relations_selsize"],1,100).',	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_relations_selsize]');
				$configL[]='"minitems" => 0,';
				$configL[]='"maxitems" => '.t3lib_div::intInRange($fConf["conf_relations"],1,100).',	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_relations]');

				if ($fConf["conf_relations_mm"])	{
					$mmTableName=$id."_mm";
					$configL[]='"MM" => "'.$mmTableName.'",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_relations_mm]');
					$DBfields[] = $fConf["fieldname"]." int(11) unsigned DEFAULT '0' NOT NULL,";

					$createTable = $this->wizard->sPS("
						#
						# Table structure for table '".$mmTableName."'
						# ".$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_relations_mm]')."
						#
						CREATE TABLE ".$mmTableName." (
						  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
						  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
						  tablenames varchar(30) DEFAULT '' NOT NULL,
						  sorting int(11) unsigned DEFAULT '0' NOT NULL,
						  KEY uid_local (uid_local),
						  KEY uid_foreign (uid_foreign)
						);
					");
					$this->wizard->ext_tables_sql[]=chr(10).$createTable.chr(10);
				} elseif (t3lib_div::intInRange($fConf["conf_relations"],1,100)>1 || $fConf["conf_rel_type"]=="group") {
					$DBfields[] = $fConf["fieldname"]." blob NOT NULL,";
				} else {
					$DBfields[] = $fConf["fieldname"]." int(11) unsigned DEFAULT '0' NOT NULL,";
				}

				if ($fConf["conf_rel_type"]!="group")	{
					$wTable=$fConf["conf_rel_table"];
					$wizards =array();
					if ($fConf["conf_wiz_addrec"])	{
						$wizards[] = trim($this->wizard->sPS('
							'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_wiz_addrec]').'
							"add" => Array(
								"type" => "script",
								"title" => "Create new record",
								"icon" => "add.gif",
								"params" => Array(
									"table"=>"'.$wTable.'",
									"pid" => "###CURRENT_PID###",
									"setValue" => "prepend"
								),
								"script" => "wizard_add.php",
							),
						'));
					}
					if ($fConf["conf_wiz_listrec"])	{
						$wizards[] = trim($this->wizard->sPS('
							'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_wiz_listrec]').'
							"list" => Array(
								"type" => "script",
								"title" => "List",
								"icon" => "list.gif",
								"params" => Array(
									"table"=>"'.$wTable.'",
									"pid" => "###CURRENT_PID###",
								),
								"script" => "wizard_list.php",
							),
						'));
					}
					if ($fConf["conf_wiz_editrec"])	{
						$wizards[] = trim($this->wizard->sPS('
							'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_wiz_editrec]').'
							"edit" => Array(
								"type" => "popup",
								"title" => "Edit",
								"script" => "wizard_edit.php",
								"popup_onlyOpenIfSelected" => 1,
								"icon" => "edit2.gif",
								"JSopenParams" => "height=350,width=580,status=0,menubar=0,scrollbars=1",
							),
						'));
					}
					if (count($wizards))	{
						$configL[]=trim($this->wizard->wrapBody('
							"wizards" => Array(
								"_PADDING" => 2,
								"_VERTICAL" => 1,
								',implode(chr(10),$wizards),'
							),
						'));
					}
				}
			break;
			case "files":
				$configL[]='"type" => "group",';
				$configL[]='"internal_type" => "file",';
				switch($fConf["conf_files_type"])	{
					case "images":
						$configL[]='"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_files_type]');
					break;
					case "webimages":
						$configL[]='"allowed" => "gif,png,jpeg,jpg",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_files_type]');
					break;
					case "all":
						$configL[]='"allowed" => "",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_files_type]');
						$configL[]='"disallowed" => "php,php3",	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_files_type]');
					break;
				}
				$configL[]='"max_size" => '.t3lib_div::intInRange($fConf["conf_max_filesize"],1,1000,500).',	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_max_filesize]');

				$this->wizard->EM_CONF_presets["uploadfolder"]=1;

				$ulFolder = 'uploads/tx_'.str_replace("_","",$extKey);
				$configL[]='"uploadfolder" => "'.$ulFolder.'",';
				if ($fConf["conf_files_thumbs"])	$configL[]='"show_thumbs" => 1,	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_files_thumbs]');

				$configL[]='"size" => '.t3lib_div::intInRange($fConf["conf_files_selsize"],1,100).',	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_files_selsize]');
				$configL[]='"minitems" => 0,';
				$configL[]='"maxitems" => '.t3lib_div::intInRange($fConf["conf_files"],1,100).',	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[conf_files]');

				$DBfields[] = $fConf["fieldname"]." blob NOT NULL,";
			break;
			case "none":
				$DBfields[] = $fConf["fieldname"]." tinytext NOT NULL,";
				$configL[]=trim($this->wizard->sPS('
					"type" => "none",
				'));
			break;
			case "passthrough":
				$DBfields[] = $fConf["fieldname"]." tinytext NOT NULL,";
				$configL[]=trim($this->wizard->sPS('
					"type" => "passthrough",
				'));
			break;
			default:
				debug("Unknown type: ".(string)$fConf["type"]);
			break;
		}

		if ($t=="passthrough")	{
			$columns[$fConf["fieldname"]] = trim($this->wizard->wrapBody('
				"'.$fConf["fieldname"].'" => Array (		'.$this->wizard->WOPcomment('WOP:'.$WOP.'[fieldname]').'
					"config" => Array (
						',implode(chr(10),$configL),'
					)
				),
			',2));
		} else {
			$columns[$fConf["fieldname"]] = trim($this->wizard->wrapBody('
				"'.$fConf["fieldname"].'" => Array (		'.$this->wizard->WOPcomment('WOP:'.$WOP.'[fieldname]').'
					"exclude" => '.($fConf["excludeField"]?1:0).',		'.$this->wizard->WOPcomment('WOP:'.$WOP.'[excludeField]').'
					"label" => "'.addslashes($this->wizard->getSplitLabels_reference($fConf,"title",$table.".".$fConf["fieldname"])).'",		'.$this->wizard->WOPcomment('WOP:'.$WOP.'[title]').'
					"config" => Array (
						',implode(chr(10),$configL),'
					)
				),
			',2));
		}
	}


// Include ux_class extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kickstarter/sections/class.tx_kickstarter_section_fields.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kickstarter/sections/class.tx_kickstarter_section_fields.php']);
}


?>