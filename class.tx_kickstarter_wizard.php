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
 * TYPO3 Extension Repository
 *
 * @author	Kasper Skårhøj <kasperYYYY@typo3.com>
 */

require_once(t3lib_extMgm::extPath("kickstarter")."class.tx_kickstarter_compilefiles.php");

class tx_kickstarter_wizard extends tx_kickstarter_compilefiles {
	var $varPrefix = "kickstarter";		// redundant from "extrep"
	var $siteBackPath = "";
	var $EMmode=1;	// If run from Extension Manager, set to 1.

	var $wizArray=array();

	var $extKey_nusc = "myext";
	var $extKey = "my_ext";
	var $printWOP=0;
	var $outputWOP=0;
	var $saveKey="";
	var $pObj;

	var $afterContent;

	var $languages = array(
		"dk" => "Danish",
		"de" => "German",
		"no" => "Norwegian",
		"it" => "Italian",
		"fr" => "French",
		"es" => "Spanish",
		"nl" => "Dutch",
		"cz" => "Czech",
		"pl" => "Polish",
		"si" => "Slovenian",
		"fi" => "Finnish",
		"tr" => "Turkish",
		"se" => "Swedish",
		"pt" => "Portuguese",
		"ru" => "Russian",
		"ro" => "Romanian",
		"ch" => "Chinese",
		"sk" => "Slovak",
		"lt" => "Lithuanian",
		'is' => 'Icelandic',
		'hr' => 'Croatian',
		'hu' => 'Hungarian',
		'gl' => 'Greenlandic',
		'th' => 'Thai',
		'gr' => 'Greek',
		'hk' => 'Chinese (Trad)',
		'eu' => 'Basque',
		'bg' => 'Bulgarian',
		'br' => 'Brazilian Portuguese',
		'et' => 'Estonian',
		'ar' => 'Arabic',
		'he' => 'Hebrew',
		'ua' => 'Ukrainian',
		'lv' => 'Latvian',
		'jp' => 'Japanese',
		'vn' => 'Vietnamese',
		'ca' => 'Catalan',
		'ba' => 'Bosnian',
		'kr' => 'Korean',
	);
	var $reservedTypo3Fields="uid,pid,endtime,starttime,sorting,fe_group,hidden,deleted,cruser_id,crdate,tstamp";
	var $mysql_reservedFields="data,table,field,key,desc";

		// Internal:
	var $selectedLanguages = array();
	var $usedNames=array();
	var $fileArray=array();
	var $ext_tables=array();
	var $ext_localconf=array();
	var $ext_locallang=array();

	var $color = array("#C8D0B3","#FEE7B5","#eeeeee");

	var $modData;

	function tx_kickstarter_wizard() {
	  $this->modData = t3lib_div::_POST($this->varPrefix);
	}


	function initWizArray()	{
		$inArray = unserialize(base64_decode($this->modData["wizArray_ser"]));
		$this->wizArray = is_array($inArray) ? $inArray : array();
		if (is_array($this->modData["wizArray_upd"]))	{
			$this->wizArray = t3lib_div::array_merge_recursive_overrule($this->wizArray,$this->modData["wizArray_upd"]);
		}

		$lA = is_array($this->wizArray["languages"]) ? current($this->wizArray["languages"]) : "";
		if (is_array($lA))	{
			foreach($lA as $k => $v)	{
				if ($v && isset($this->languages[$k]))	{
					$this->selectedLanguages[$k]=$this->languages[$k];
				}
			}
		}
	}

	function mgm_wizard()	{
		$this->initWizArray();
		$this->sections = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter']['sections'];
		
		foreach($this->sections as $k => $v) {
			$this->options[$k] = array($v['titel'],$v['description']);
		}

		$saveKey = $this->saveKey = $this->wizArray["save"]["extension_key"] = trim($this->wizArray["save"]["extension_key"]);
		$this->outputWOP = $this->wizArray["save"]["print_wop_comments"] ? 1 : 0;



		if ($saveKey)	{
			$this->extKey=$saveKey;
			$this->extKey_nusc=str_replace("_","",$saveKey);
		}

		if ($this->modData["viewResult"])	{
			$this->modData["wizAction"]="";
			$this->modData["wizSubCmd"]="";
			if ($saveKey)	{
				$content = $this->view_result();
			} else $content = $this->fw("<strong>Error:</strong> Please enter an extension key first!<BR><BR>");
		} elseif ($this->modData["WRITE"])	{
			$this->modData["wizAction"]="";
			$this->modData["wizSubCmd"]="";
			if ($saveKey)	{
				$this->makeFilesArray($this->saveKey);
				$uploadArray = $this->makeUploadArray($this->saveKey,$this->fileArray);
				$this->pObj->importExtFromRep(0,$this->modData["loc"],0,$uploadArray);
			} else $content = $this->fw("<strong>Error:</strong> Please enter an extension key first!<BR><BR>");
		} elseif ($this->modData["totalForm"])	{
			$content = $this->totalForm();
		} elseif ($this->modData["downloadAsFile"])	{
			if ($saveKey)	{
				$this->makeFilesArray($this->saveKey);
				$uploadArray = $this->makeUploadArray($this->saveKey,$this->fileArray);
				$backUpData = $this->makeUploadDataFromArray($uploadArray);
				$filename="T3X_".$saveKey."-".str_replace(".","_","0.0.0").".t3x";
				$mimeType = "application/octet-stream";
				Header("Content-Type: ".$mimeType);
				Header("Content-Disposition: attachment; filename=".$filename);
				echo $backUpData;
				exit;
			} else $content = $this->fw("<strong>Error:</strong> Please enter an extension key first!<BR><BR>");
		} else {
			$action = explode(":",$this->modData["wizAction"]);
			if ((string)$action[0]=="deleteEl")	{
				unset($this->wizArray[$action[1]][$action[2]]);
			}

			$content = $this->getFormContent();
		}
		$wasContent = $content?1:0;
		$content = '
		<script language="javascript" type="text/javascript">
			function setFormAnchorPoint(anchor)	{
				document.'.$this->varPrefix.'_wizard.action = unescape("'.rawurlencode($this->linkThisCmd()).'")+"#"+anchor;
			}
		</script>
		<table border=0 cellpadding=0 cellspacing=0>
			<form action="'.$this->linkThisCmd().'" method="POST" name="'.$this->varPrefix.'_wizard">
			<tr>
				<td valign=top>'.$this->sidemenu().'</td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td valign=top>'.$content.'
					<input type="hidden" name="'.$this->piFieldName("wizArray_ser").'" value="'.htmlspecialchars(base64_encode(serialize($this->wizArray))).'" /><BR>';

		if ((string)$this->modData["wizSubCmd"])	{
			if ($wasContent)	$content.='<input name="update2" type="submit" value="Update..."> ';
		}
		$content.='
					<input type="hidden" name="'.$this->piFieldName("wizAction").'" value="'.$this->modData["wizAction"].'">
					<input type="hidden" name="'.$this->piFieldName("wizSubCmd").'" value="'.$this->modData["wizSubCmd"].'">
					'.$this->cmdHiddenField().'
				</td>
			</tr>
			</form>
		</table>'.$this->afterContent;

		return $content;
	}

	/**
	 * Get form content
	 */
	function getFormContent()	{

		if($this->sections[$this->modData["wizSubCmd"]]) {
			$path = t3lib_div::getFileAbsFileName($this->sections[$this->modData["wizSubCmd"]]['filepath']);
			require_once($path);
			$section = t3lib_div::makeInstance($this->sections[$this->modData["wizSubCmd"]]['classname']);
			$section->wizard = &$this;
			return $section->render_wizard();
		}
	}

	/**
	 * Total form
	 */
	function totalForm()	{
		$buf = array($this->printWOP,$this->dontPrintImages);
		$this->printWOP = 1;

		$lines=array();
		foreach($this->options as $k => $v)	{
			// Add items:
			$items = $this->wizArray[$k];
			if (is_array($items))	{
				foreach($items as $k2 => $conf)	{
					$this->modData["wizSubCmd"]=$k;
					$this->modData["wizAction"]="edit:".$k2;
					$lines[]=$this->getFormContent();
				}
			}
		}

		$this->modData["wizSubCmd"]="";
		$this->modData["wizAction"]="";
		list($this->printWOP,$this->dontPrintImages) = $buf;

		$content = implode("<HR>",$lines);
		return $content;
	}

	/**
	 * Side menu
	 */
	function sidemenu()	{
#debug($this->modData);
		$actionType = $this->modData["wizSubCmd"].":".$this->modData["wizAction"];
		$singles = "emconf,save,ts,TSconfig,languages";
		$lines=array();
		foreach($this->options as $k => $v)	{
			// Add items:
			$items = $this->wizArray[$k];
			$c=0;
			$iLines=array();
			if (is_array($items))	{
				foreach($items as $k2=>$conf)	{
					$dummyTitle = t3lib_div::inList($singles,$k) ? "[Click to Edit]" : "<em>Item ".$k2."</em>";
					$isActive = !strcmp($k.":edit:".$k2,$actionType);
					$delIcon = $this->linkStr('<img src="'.$this->siteBackPath.'t3lib/gfx/garbage.gif" width="11" height="12" border="0" title="Remove item">',"","deleteEl:".$k.":".$k2);
					$iLines[]='<tr'.($isActive?$this->bgCol(2,-30):$this->bgCol(2)).'><td>'.$this->fw($this->linkStr($this->bwWithFlag($conf["title"]?$conf["title"]:$dummyTitle,$isActive),$k,'edit:'.$k2)).'</td><td>'.$delIcon.'</td></tr>';
					$c=$k2;
				}
			}
			if (!t3lib_div::inList($singles,$k) || !count($iLines))	{
				$c++;
				$addIcon = $this->linkStr('<img src="'.$this->siteBackPath.'t3lib/gfx/add.gif" width="12" height="12" border="0" title="Add item">',$k,'edit:'.$c);
			} else {$addIcon = "";}

			$lines[]='<tr'.$this->bgCol(1).'><td nowrap><strong>'.$this->fw($v[0]).'</strong></td><td>'.$addIcon.'</td></tr>';
			$lines = array_merge($lines,$iLines);
		}

		$lines[]='<tr><td>&nbsp;</td><td></td></tr>';

		$lines[]='<tr><td width=150>
		'.$this->fw("Enter extension key:").'<BR>
		<input type="text" name="'.$this->piFieldName("wizArray_upd").'[save][extension_key]" value="'.$this->wizArray["save"]["extension_key"].'">
		'.($this->wizArray["save"]["extension_key"]?"":'<BR><a href="http://typo3.org/1382.0.html" target="_blank"><font color=red>Make sure to enter the right extension key from the beginning here!</font> You can register one here.</a>').'
		</td><td></td></tr>';
# onClick="setFormAnchorPoint(\'_top\')"
		$lines[]='<tr><td><input type="submit" value="Update..."></td><td></td></tr>';
		$lines[]='<tr><td><input type="submit" name="'.$this->piFieldName("totalForm").'" value="Total form"></td><td></td></tr>';

		if ($this->saveKey)	{
			$lines[]='<tr><td><input type="submit" name="'.$this->piFieldName("viewResult").'" value="View result"></td><td></td></tr>';
			$lines[]='<tr><td><input type="submit" name="'.$this->piFieldName("downloadAsFile").'" value="D/L as file"></td><td></td></tr>';
			$lines[]='<tr><td>
			<input type="hidden" name="'.$this->piFieldName("wizArray_upd").'[save][print_wop_comments]" value="0"><input type="checkbox" name="'.$this->piFieldName("wizArray_upd").'[save][print_wop_comments]" value="1" '.($this->wizArray["save"]["print_wop_comments"]?" CHECKED":"").'>'.$this->fw("Print WOP comments").'
			</td><td></td></tr>';
		}

		/* HOOK: Place a hook here, so additional output can be integrated */
		if(is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter']['sidemenu'])) {
		  foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter']['sidemenu'] as $_funcRef) {
		    $lines = t3lib_div::callUserFunction($_funcRef, $lines, $this);
		  }
		}

		$content = '<table border=0 cellpadding=2 cellspacing=2>'.implode("",$lines).'</table>';
		return $content;
	}

	/**
	 * View result
	 */
	function view_result()	{
		$this->makeFilesArray($this->saveKey);

		$keyA = array_keys($this->fileArray);
		asort($keyA);

		$filesOverview1=array();
		$filesOverview2=array();
		$filesContent=array();

		$filesOverview1[]= '<tr'.$this->bgCol(1).'>
			<td><strong>'.$this->fw("Filename:").'</strong></td>
			<td><strong>'.$this->fw("Size:").'</strong></td>
			<td><strong>'.$this->fw("&nbsp;").'</strong></td>
		</tr>';

		foreach($keyA as $fileName)	{
			$data = $this->fileArray[$fileName];

			$fI = pathinfo($fileName);
			if (t3lib_div::inList("php,sql,txt",strtolower($fI["extension"])))	{
				$linkToFile='<strong><a href="#'.md5($fileName).'">'.$this->fw("&nbsp;View&nbsp;").'</a></strong>';
				$filesContent[]='<tr'.$this->bgCol(1).'>
				<td><a name="'.md5($fileName).'"></a><strong>'.$this->fw($fileName).'</strong></td>
				</tr>
				<tr>
					<td>'.$this->preWrap($data["content"]).'</td>
				</tr>';
			} else $linkToFile=$this->fw("&nbsp;");

			$line = '<tr'.$this->bgCol(2).'>
				<td>'.$this->fw($fileName).'</td>
				<td>'.$this->fw(t3lib_div::formatSize($data["size"])).'</td>
				<td>'.$linkToFile.'</td>
			</tr>';
			if (strstr($fileName,"/"))	{
				$filesOverview2[]=$line;
			} else {
				$filesOverview1[]=$line;
			}
		}

		$content = '<table border=0 cellpadding=1 cellspacing=2>'.implode("",$filesOverview1).implode("",$filesOverview2).'</table>';
		$content.= $this->fw("<BR><strong>Author name:</strong> ".$GLOBALS['BE_USER']->user['realName']."
							<BR><strong>Author email:</strong> ".$GLOBALS['BE_USER']->user['email']);


		$content.= '<BR><BR>';
		if (!$this->EMmode)	{
			$content.='<input type="submit" name="'.$this->piFieldName("WRITE").'" value="WRITE to \''.$this->saveKey.'\'">';
		} else {
			$content.='
				<strong>'.$this->fw("Write to location:").'</strong><BR>
				<select name="'.$this->piFieldName("loc").'">'.
					($this->pObj->importAsType("G")?'<option value="G">Global: '.$this->pObj->typePaths["G"].$this->saveKey."/".(@is_dir(PATH_site.$this->pObj->typePaths["G"].$this->saveKey)?" (OVERWRITE)":" (empty)").'</option>':'').
					($this->pObj->importAsType("L")?'<option value="L">Local: '.$this->pObj->typePaths["L"].$this->saveKey."/".(@is_dir(PATH_site.$this->pObj->typePaths["L"].$this->saveKey)?" (OVERWRITE)":" (empty)").'</option>':'').
				'</select>
				<input type="submit" name="'.$this->piFieldName("WRITE").'" value="WRITE" onClick="return confirm(\'If the setting in the selectorbox says OVERWRITE\nthen the current extension in that location WILL be overridden! Totally!\nPlease decide if you want to continue.\n\n(Remember, this is a *kickstarter* - not an editor!)\');">
			';
		}


		$this->afterContent= '<BR><table border=0 cellpadding=1 cellspacing=2>'.implode("",$filesContent).'</table>';
		return $content;
	}


	/**
	 * @author	Luite van Zelst <luite@aegee.org>
	 */
	function renderFieldOverview($prefix,$fConf,$dontRemove=0)	{
			// Sorting
		$optTypes = array(
			"" => "",
			"input" => "String input",
			"input+" => "String input, advanced",
			"textarea" => "Text area",
			"textarea_rte" => "Text area with RTE",
			"textarea_nowrap" => "Text area, No wrapping",
			"check" => "Checkbox, single",
			"check_4" => "Checkbox, 4 boxes in a row",
			"check_10" => "Checkbox, 10 boxes in two rows (max)",
			"link" => "Link",
			"date" => "Date",
			"datetime" => "Date and time",
			"integer" => "Integer, 10-1000",
			"select" => "Selectorbox",
			"radio" => "Radio buttons",
			"rel" => "Database relation",
			"files" => "Files",
		);
		$optEval = array(
			"" => "",
			"date" => "Date (day-month-year)",
			"time" => "Time (hours, minutes)",
			"timesec" => "Time + seconds",
			"datetime" => "Date + Time",
			"year" => "Year",
			"int" => "Integer",
			"int+" => "Integer 0-1000",
			"double2" => "Floating point, x.xx",
			"alphanum" => "Alphanumeric only",
			"upper" => "Upper case",
			"lower" => "Lower case",
		);
		$optRte = array(
			"tt_content" => "Transform like 'Bodytext'",
			"basic" => "Typical (based on CSS)",
			"moderate" => "Transform images / links",
			"none" => "No transform",
			"custom" => "Custom transform"
		);

		switch($fConf['type']) {
			case 'rel':
				if ($fConf['conf_rel_table'] == '_CUSTOM') {
					$details .= $fConf['conf_custom_table_name'];
				} else {
					$details .= $fConf['conf_rel_table'];
				}
			break;
			case 'input+':
				if($fConf['conf_varchar']) $details[] = 'varchar';
				if($fConf['conf_unique']) $details[] = ($fConf['conf_unique'] == 'L') ?  'unique (page)': 'unique (site)';
				if($fConf['conf_eval']) $details[] = $optEval[$fConf['conf_eval']];
				$details = implode(', ', (array) $details);
			break;
			case 'check_10':
			case 'check_4':
				$details = ($fConf['conf_numberBoxes'] ? $fConf['conf_numberBoxes'] : '4') . ' checkboxes';
			break;
			case 'radio':
				if($fConf['conf_select_items']) $details = $fConf['conf_select_items'] . ' options';
			break;
			case 'select':
				if($fConf['conf_select_items']) $details[] = $fConf['conf_select_items'] . ' options';
				if($fConf['conf_select_pro']) $details[] = 'preprocessing';
				$details = implode(', ', (array) $details);
			break;
			case 'textarea_rte':
				if($fConf['conf_rte']) $details = $optRte[$fConf['conf_rte']];
			break;
			case 'files':
				$details[] = $fConf['conf_files_type'];
				$details[] = $fConf['conf_files'] . ' files';
				$details[] = $fConf['conf_max_filesize'] . ' kB';
				$details = implode(', ', (array) $details);
			break;
		}
		return sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>',
			$fConf['fieldname'],
			$fConf['title'],
			$optTypes[$fConf['type']],
			$fConf['exludeField'] ? 'Yes' : '',
			$details
			);
	}


	function presetBox(&$piConfFields)	{
		$_PRESETS = $this->modData["_PRESET"];

		$optValues = array();

		/* Static Presets from DB-Table are disabled. Just leave the code in here for possible future use */
		//		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'kickstarter_static_presets', '');
		//		while($presetRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
		//			$optValues[] = '<option value="'.htmlspecialchars($presetRow["fieldname"]).'">'.htmlspecialchars($presetRow["title"]." (".$presetRow["fieldname"].", type: ".$presetRow["type"].")").'</option>';
		//			if (is_array($_PRESETS) && in_array($presetRow["fieldname"],$_PRESETS))	{
		//				if (!is_array($piConfFields))	$piConfFields=array();
		//				$piConfFields[] = unserialize($presetRow["appdata"]);
		//			}
		//		}

			// Session presets:
		$ses_optValues=array();
		$sesdat = $GLOBALS["BE_USER"]->getSessionData("kickstarter");
		if (is_array($sesdat["presets"]))	{
			foreach($sesdat["presets"] as $kk1=>$vv1)	{
				if (is_array($vv1))	{
					foreach($vv1 as $kk2=>$vv2)	{
						$ses_optValues[]='<option value="'.htmlspecialchars($kk1.".".$vv2["fieldname"]).'">'.htmlspecialchars($kk1.": ".$vv2["title"]." (".$vv2["fieldname"].", type: ".$vv2["type"].")").'</option>';
						if (is_array($_PRESETS) && in_array($kk1.".".$vv2["fieldname"],$_PRESETS))	{
							if (!is_array($piConfFields))	$piConfFields=array();
							$piConfFields[] = $vv2;
						}
					}
				}
			}
		}
		if (count($ses_optValues))	{
			$optValues = array_merge($optValues,count($optValues)?array('<option value=""></option>'):array(),array('<option value="">__Fields picked up in this session__:</option>'),$ses_optValues);
		}
		if (count($optValues))		$selPresetBox = '<select name="'.$this->piFieldName("_PRESET").'[]" size='.t3lib_div::intInRange(count($optValues),1,10).' multiple>'.implode("",$optValues).'</select>';
		return $selPresetBox;
	}
	function cleanFieldsAndDoCommands($fConf,$catID,$action)	{
		$newFConf=array();
		$downFlag=0;
		foreach($fConf as $k=>$v)	{
			if ($v["type"] && trim($v["fieldname"]))	{
				$v["fieldname"] = $this->cleanUpFieldName($v["fieldname"]);

				if (!$v["_DELETE"])	{
					$newFConf[$k]=$v;
					if (t3lib_div::_GP($this->varPrefix.'_CMD_'.$v["fieldname"].'_UP_x') || $downFlag)	{
						if (count($newFConf)>=2)	{
							$lastKeys = array_slice(array_keys($newFConf),-2);

							$buffer = Array();
							$buffer[$lastKeys[1]] = $newFConf[$lastKeys[1]];
							$buffer[$lastKeys[0]] = $newFConf[$lastKeys[0]];

							unset($newFConf[$lastKeys[0]]);
							unset($newFConf[$lastKeys[1]]);

							$newFConf[$lastKeys[1]] = $buffer[$lastKeys[1]];
							$newFConf[$lastKeys[0]] = $buffer[$lastKeys[0]];
						}
						$downFlag=0;
					} elseif (t3lib_div::_GP($this->varPrefix.'_CMD_'.$v["fieldname"].'_DOWN_x'))	{
						$downFlag=1;
					}
				}

					// PRESET:
				//				if (t3lib_div::_GP($this->varPrefix.'_CMD_'.$v["fieldname"].'_SAVE_x'))	{
				//					$datArr=Array(
				//						"fieldname" => $v["fieldname"],
				//						"title" => $v["title"],
// 						"type" => $v["type"],
// 						"appdata" => serialize($v),
// 						"tstamp" => time()
// 					);

// 					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('fieldname', 'kickstarter_static_presets', 'fieldname="'.$GLOBALS['TYPO3_DB']->quoteStr($v['fieldname'], 'kickstarter_static_presets').'"');
// 					if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) || $v["_DELETE"])	{
// 						if ($v["_DELETE"])	{
// 							$GLOBALS['TYPO3_DB']->exec_DELETEquery('kickstarter_static_presets', 'fieldname="'.$GLOBALS['TYPO3_DB']->quoteStr($v['fieldname'], 'kickstarter_static_presets').'"');
// 						} else {
// 							$GLOBALS['TYPO3_DB']->exec_UPDATEquery('kickstarter_static_presets', 'fieldname="'.$GLOBALS['TYPO3_DB']->quoteStr($v['fieldname'], 'kickstarter_static_presets').'"', $datArr);
// 						}
// 					} else {
// 						$GLOBALS['TYPO3_DB']->exec_INSERTquery("kickstarter_static_presets", $datArr);
// 					}
// 				}
			} else {
			  //				unset($this->wizArray[$catID][$action]["fields"][$k]);
			  //				unset($fConf[$k]);
			}
		}
		//		debug($newFConf);
		$this->wizArray[$catID][$action]["fields"] = $newFConf;
		$sesdat = $GLOBALS["BE_USER"]->getSessionData("kickstarter");
		$sesdat["presets"][$this->extKey."-".$catID."-".$action]=$newFConf;
		$GLOBALS["BE_USER"]->setAndSaveSessionData("kickstarter",$sesdat);

#debug($newFConf);
		return $newFConf;
	}


	function renderField($prefix,$fConf,$dontRemove=0)	{
		$onCP = $this->getOnChangeParts($prefix."[fieldname]");
		$fieldName = $this->renderStringBox($prefix."[fieldname]",$fConf["fieldname"]).
			(!$dontRemove?" (Remove:".$this->renderCheckBox($prefix."[_DELETE]",0).')'.
				'<input type="image" hspace=2 src="'.$this->siteBackPath.TYPO3_mainDir.'gfx/pil2up.gif" name="'.$this->varPrefix.'_CMD_'.$fConf["fieldname"].'_UP" onClick="'.$onCP[1].'">'.
				'<input type="image" hspace=2 src="'.$this->siteBackPath.TYPO3_mainDir.'gfx/pil2down.gif" name="'.$this->varPrefix.'_CMD_'.$fConf["fieldname"].'_DOWN" onClick="'.$onCP[1].'">'.
				'<input type="image" hspace=2 src="'.$this->siteBackPath.TYPO3_mainDir.'gfx/savesnapshot.gif" name="'.$this->varPrefix.'_CMD_'.$fConf["fieldname"].'_SAVE" onClick="'.$onCP[1].'" title="Save this field setting as a preset.">':'');

		$fieldTitle = ((string)$fConf["type"] != 'passthrough') ? $this->renderStringBox_lang("title",$prefix,$fConf) : '';
		$typeCfg = "";

			// Sorting
		$optValues = array(
			"" => "",
			"input" => "String input",
			"input+" => "String input, advanced",
			"textarea" => "Text area",
			"textarea_rte" => "Text area with RTE",
			"textarea_nowrap" => "Text area, No wrapping",
			"check" => "Checkbox, single",
			"check_4" => "Checkbox, 4 boxes in a row",
			"check_10" => "Checkbox, 10 boxes in two rows (max)",
			"link" => "Link",
			"date" => "Date",
			"datetime" => "Date and time",
			"integer" => "Integer, 10-1000",
			"select" => "Selectorbox",
			"radio" => "Radio buttons",
			"rel" => "Database relation",
			"files" => "Files",
			"none" => "Not editable, only displayed",
			"passthrough" => "[Passthrough]",
		);
		$typeCfg.=$this->renderSelectBox($prefix."[type]",$fConf["type"],$optValues);
		$typeCfg.=$this->renderCheckBox($prefix."[excludeField]",isset($fConf["excludeField"])?$fConf["excludeField"]:1)." Is Exclude-field ".$this->whatIsThis("If a field is marked 'Exclude-field', users can edit it ONLY if the field is specifically listed in one of the backend user groups of the user.\nIn other words, if a field is marked 'Exclude-field' you can control which users can edit it and which cannot.")."<BR>";

		$fDetails="";
		switch((string)$fConf["type"])	{
			case "input+":
				$typeCfg.=$this->resImg("t_input.png",'','');

				$fDetails.=$this->renderStringBox($prefix."[conf_size]",$fConf["conf_size"],50)." Field width (5-48 relative, 30 default)<BR>";
				$fDetails.=$this->renderStringBox($prefix."[conf_max]",$fConf["conf_max"],50)." Max characters<BR>";
				$fDetails.=$this->renderCheckBox($prefix."[conf_required]",$fConf["conf_required"])."Required<BR>";
				$fDetails.=$this->resImg("t_input_required.png",'hspace=20','','<BR><BR>');

				$fDetails.=$this->renderCheckBox($prefix."[conf_varchar]",$fConf["conf_varchar"])."Create VARCHAR, not TINYTEXT field (if not forced INT)<BR>";

				$fDetails.=$this->renderCheckBox($prefix."[conf_check]",$fConf["conf_check"])."Apply checkbox<BR>";
				$fDetails.=$this->resImg("t_input_check.png",'hspace=20','','<BR><BR>');

				$optValues = array(
					"" => "",
					"date" => "Date (day-month-year)",
					"time" => "Time (hours, minutes)",
					"timesec" => "Time + seconds",
					"datetime" => "Date + Time",
					"year" => "Year",
					"int" => "Integer",
					"int+" => "Integer 0-1000",
					"double2" => "Floating point, x.xx",
					"alphanum" => "Alphanumeric only",
					"upper" => "Upper case",
					"lower" => "Lower case",
				);
				$fDetails.="<BR>Evaluate value to:<BR>".$this->renderSelectBox($prefix."[conf_eval]",$fConf["conf_eval"],$optValues)."<BR>";
				$fDetails.=$this->renderCheckBox($prefix."[conf_stripspace]",$fConf["conf_stripspace"])."Strip space<BR>";
				$fDetails.=$this->renderCheckBox($prefix."[conf_pass]",$fConf["conf_pass"])."Is password field<BR>";
				$fDetails.=$this->resImg("t_input_password.png",'hspace=20','','<BR><BR>');

				$fDetails.="<BR>";
				$fDetails.=$this->renderRadioBox($prefix."[conf_unique]",$fConf["conf_unique"],"G")."Unique in whole database<BR>";
				$fDetails.=$this->renderRadioBox($prefix."[conf_unique]",$fConf["conf_unique"],"L")."Unique inside parent page<BR>";
				$fDetails.=$this->renderRadioBox($prefix."[conf_unique]",$fConf["conf_unique"],"")."Not unique (default)<BR>";
				$fDetails.="<BR>";
				$fDetails.=$this->renderCheckBox($prefix."[conf_wiz_color]",$fConf["conf_wiz_color"])."Add colorpicker wizard<BR>";
				$fDetails.=$this->resImg("t_input_colorwiz.png",'hspace=20','','<BR><BR>');
				$fDetails.=$this->renderCheckBox($prefix."[conf_wiz_link]",$fConf["conf_wiz_link"])."Add link wizard<BR>";
				$fDetails.=$this->resImg("t_input_link2.png",'hspace=20','','<BR><BR>');
			break;
			case "input":
				$typeCfg.=$this->resImg("t_input.png",'','');

				$fDetails.=$this->renderStringBox($prefix."[conf_size]",$fConf["conf_size"],50)." Field width (5-48 relative, 30 default)<BR>";
				$fDetails.=$this->renderStringBox($prefix."[conf_max]",$fConf["conf_max"],50)." Max characters<BR>";
				$fDetails.=$this->renderCheckBox($prefix."[conf_required]",$fConf["conf_required"])."Required<BR>";
				$fDetails.=$this->resImg("t_input_required.png",'hspace=20','','<BR><BR>');

				$fDetails.=$this->renderCheckBox($prefix."[conf_varchar]",$fConf["conf_varchar"])."Create VARCHAR, not TINYTEXT field<BR>";
			break;
			case "textarea":
			case "textarea_nowrap":
				$typeCfg.=$this->resImg("t_textarea.png",'','');

				$fDetails.=$this->renderStringBox($prefix."[conf_cols]",$fConf["conf_cols"],50)." Textarea width (5-48 relative, 30 default)<BR>";
				$fDetails.=$this->renderStringBox($prefix."[conf_rows]",$fConf["conf_rows"],50)." Number of rows (height)<BR>";
				$fDetails.="<BR>";
				$fDetails.=$this->renderCheckBox($prefix."[conf_wiz_example]",$fConf["conf_wiz_example"])."Add wizard example<BR>";
				$fDetails.=$this->resImg("t_textarea_wiz.png",'hspace=20','','<BR><BR>');
			break;
			case "textarea_rte":
				$typeCfg.=$this->resImg($fConf["conf_rte"]!="tt_content"?"t_rte.png":"t_rte2.png",'','');

				$optValues = array(
					"tt_content" => "Transform content like the Content Element 'Bodytext' field (default/old)",
					"basic" => "Typical basic setup (new 'Bodytext' field based on CSS stylesheets)",
					"moderate" => "Moderate transform of images and links",
					"none" => "No transformation at all",
					"custom" => "Custom"
				);
				$fDetails.="<BR>Rich Text Editor Mode:<BR>".$this->renderSelectBox($prefix."[conf_rte]",$fConf["conf_rte"],$optValues)."<BR>";
				if ((string)$fConf["conf_rte"]=="custom")	{
					$optValues = array(
						"cut" => array("Cut button"),
						"copy" => array("Copy button"),
						"paste" => array("Paste button"),
						"formatblock" => array("Paragraph formatting","<DIV>, <P>"),
						"class" => array("Character formatting","<SPAN>)"),
						"fontstyle" => array("Font face","<FONT face=>)"),
						"fontsize" => array("Font size","<FONT size=>)"),
						"textcolor" => array("Font color","<FONT color=>"),
						"bold" => array("Bold","<STRONG>, <B>"),
						"italic" => array("italic","<EM>, <I>"),
						"underline" => array("Underline","<U>"),
						"left" => array("Left align","<DIV>, <P>"),
						"center" => array("Center align","<DIV>, <P>"),
						"right" => array("Right align","<DIV>, <P>"),
						"orderedlist" => array("Ordered bulletlist","<OL>, <LI>"),
						"unorderedlist" => array("Unordered bulletlist","<UL>, <LI>"),
						"outdent" => array("Outdent block","<BLOCKQUOTE>"),
						"indent" => array("Indent block","<BLOCKQUOTE>"),
						"link" => array("Link","<A>"),
						"table" => array("Table","<TABLE>, <TR>, <TD>"),
						"image" => array("Image","<IMG>"),
						"line" => array("Ruler","<HR>"),
						"user" => array("User defined",""),
						"chMode" => array("Edit source?","")
					);
					$subLines=array();
					$subLines[]='<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><strong>'.$this->fw("Button name:").'</strong></td>
						<td><strong>'.$this->fw("Tags allowed:").'</strong></td>
					</tr>';
					foreach($optValues as $kk=>$vv)	{
						$subLines[]='<tr>
							<td>'.$this->renderCheckBox($prefix."[conf_rte_b_".$kk."]",$fConf["conf_rte_b_".$kk]).'</td>
							<td>'.$this->resIcon($kk.".png").'</td>
							<td>'.$this->fw($vv[0]).'</td>
							<td>'.$this->fw(htmlspecialchars($vv[1])).'</td>
						</tr>';
					}
					$fDetails.='<table border=0 cellpadding=2 cellspacing=2>'.implode("",$subLines).'</table><BR>';

					$fDetails.="<BR><strong>Define specific colors:</strong><BR>
						<em>Notice: Use only HEX-values for colors ('blue' should be #0000ff etc.)</em><BR>";
					for($a=1;$a<4;$a++)	{
						$fDetails.="Color #".$a.": ".$this->renderStringBox($prefix."[conf_rte_color".$a."]",$fConf["conf_rte_color".$a],70)."<BR>";
					}
					$fDetails.=$this->resImg("t_rte_color.png",'','','<BR><BR>');

					$fDetails.=$this->renderCheckBox($prefix."[conf_rte_removecolorpicker]",$fConf["conf_rte_removecolorpicker"])."Hide colorpicker<BR>";
					$fDetails.=$this->resImg("t_rte_colorpicker.png",'hspace=20','','<BR><BR>');

					$fDetails.="<BR><strong>Define classes:</strong><BR>";
					for($a=1;$a<7;$a++)	{
						$fDetails.="Class Title:".$this->renderStringBox($prefix."[conf_rte_class".$a."]",$fConf["conf_rte_class".$a],100).
							"<BR>CSS Style: {".$this->renderStringBox($prefix."[conf_rte_class".$a."_style]",$fConf["conf_rte_class".$a."_style"],250)."}".
						"<BR>";
					}
					$fDetails.=$this->resImg("t_rte_class.png",'','','<BR><BR>');

#					$fDetails.=$this->renderCheckBox($prefix."[conf_rte_removePdefaults]",$fConf["conf_rte_removePdefaults"])."<BR>";
					$optValues = array(
						"0" => "",
						"1" => "Hide Hx and PRE from Paragraph selector.",
						"H2H3" => "Hide all, but H2,H3,P,PRE",
					);
					$fDetails.="<BR>Hide Paragraph Items:<BR>".$this->renderSelectBox($prefix."[conf_rte_removePdefaults]",$fConf["conf_rte_removePdefaults"],$optValues)."<BR>";
					$fDetails.=$this->resImg("t_rte_hideHx.png",'hspace=20','','<BR><BR>');

					$fDetails.="<BR><strong>Misc:</strong><BR>";
//					$fDetails.=$this->renderCheckBox($prefix."[conf_rte_custom_php_processing]",$fConf["conf_rte_custom_php_processing"])."Custom PHP processing of content<BR>";
					$fDetails.=$this->renderCheckBox($prefix."[conf_rte_div_to_p]",isset($fConf["conf_rte_div_to_p"])?$fConf["conf_rte_div_to_p"]:1).htmlspecialchars("Convert all <DIV> to <P>")."<BR>";
				}

				$fDetails.="<BR>";
				$fDetails.=$this->renderCheckBox($prefix."[conf_rte_fullscreen]",isset($fConf["conf_rte_fullscreen"])?$fConf["conf_rte_fullscreen"]:1)."Fullscreen link<BR>";
				$fDetails.=$this->resImg("t_rte_fullscreen.png",'hspace=20','','<BR><BR>');

				if (t3lib_div::inList("moderate,basic,custom",$fConf["conf_rte"]))	{
					$fDetails.="<BR>";
					$fDetails.=$this->renderCheckBox($prefix."[conf_rte_separateStorageForImages]",isset($fConf["conf_rte_separateStorageForImages"])?$fConf["conf_rte_separateStorageForImages"]:1)."Storage of images in separate folder (in uploads/[extfolder]/rte/)<BR>";
				}
				if (t3lib_div::inList("moderate,custom",$fConf["conf_rte"]))	{
					$fDetails.="<BR>";
					$fDetails.=$this->renderCheckBox($prefix."[conf_mode_cssOrNot]",isset($fConf["conf_mode_cssOrNot"])?$fConf["conf_mode_cssOrNot"]:1)."Use 'ts_css' transformation instead of 'ts_images-ts-reglinks'<BR>";
				}
			break;
			case "check":
				$typeCfg.=$this->resImg("t_input_link.png",'','');
				$fDetails.=$this->renderCheckBox($prefix."[conf_check_default]",$fConf["conf_check_default"])."Checked by default<BR>";
			break;
			case "select":
			case "radio":
				if ($fConf["type"]=="radio")	{
					$typeCfg.=$this->resImg("t_radio.png",'','');
				} else	{
					$typeCfg.=$this->resImg("t_sel.png",'','');
				}
				$fDetails.="<BR><strong>Define values:</strong><BR>";
				$subLines=array();
					$subLines[]='<tr>
						<td valign=top>'.$this->fw("Item label:").'</td>
						<td valign=top>'.$this->fw("Item value:").'</td>
					</tr>';
				$nItems = $fConf["conf_select_items"] = isset($fConf["conf_select_items"])?t3lib_div::intInRange(intval($fConf["conf_select_items"]),0,20):4;
				for($a=0;$a<$nItems;$a++)	{
					$subLines[]='<tr>
						<td valign=top>'.$this->fw($this->renderStringBox_lang("conf_select_item_".$a,$prefix,$fConf)).'</td>
						<td valign=top>'.$this->fw($this->renderStringBox($prefix."[conf_select_itemvalue_".$a."]",isset($fConf["conf_select_itemvalue_".$a])?$fConf["conf_select_itemvalue_".$a]:$a,50)).'</td>
					</tr>';
				}
				$fDetails.='<table border=0 cellpadding=2 cellspacing=2>'.implode("",$subLines).'</table><BR>';
				$fDetails.=$this->renderStringBox($prefix."[conf_select_items]",$fConf["conf_select_items"],50)." Number of values<BR>";

				if ($fConf["type"]=="select")	{
					$fDetails.=$this->renderCheckBox($prefix."[conf_select_icons]",$fConf["conf_select_icons"])."Add a dummy set of icons<BR>";
					$fDetails.=$this->resImg("t_select_icons.png",'hspace=20','','<BR><BR>');

					$fDetails.=$this->renderStringBox($prefix."[conf_relations]",t3lib_div::intInRange($fConf["conf_relations"],1,1000),50)." Max number of relations<BR>";
					$fDetails.=$this->renderStringBox($prefix."[conf_relations_selsize]",t3lib_div::intInRange($fConf["conf_relations_selsize"],1,50),50)." Size of selector box<BR>";

					$fDetails.=$this->renderCheckBox($prefix."[conf_select_pro]",$fConf["conf_select_pro"])."Add pre-processing with PHP-function<BR>";
				}
			break;
			case "rel":
				if ($fConf["conf_rel_type"]=="group" || !$fConf["conf_rel_type"])	{
					$typeCfg.=$this->resImg("t_rel_group.png",'','');
				} elseif(intval($fConf["conf_relations"])>1)	{
					$typeCfg.=$this->resImg("t_rel_selmulti.png",'','');
				} elseif(intval($fConf["conf_relations_selsize"])>1)	{
					$typeCfg.=$this->resImg("t_rel_selx.png",'','');
				} else {
					$typeCfg.=$this->resImg("t_rel_sel1.png",'','');
				}


				$optValues = array(
					"pages" => "Pages table, (pages)",
					"fe_users" => "Frontend Users, (fe_users)",
					"fe_groups" => "Frontend Usergroups, (fe_groups)",
					"tt_content" => "Content elements, (tt_content)",
					"_CUSTOM" => "Custom table (enter name below)",
					"_ALL" => "All tables allowed!",
				);
				if ($fConf["conf_rel_type"]!="group")	{unset($optValues["_ALL"]);}
				$optValues = $this->addOtherExtensionTables($optValues);
				$fDetails.="<BR>Create relation to table:<BR>".$this->renderSelectBox($prefix."[conf_rel_table]",$fConf["conf_rel_table"],$optValues)."<BR>";
				if ($fConf["conf_rel_table"]=="_CUSTOM")	$fDetails.="Custom table name: ".$this->renderStringBox($prefix."[conf_custom_table_name]",$fConf["conf_custom_table_name"],200)."<BR>";

				$optValues = array(
					"group" => "Field with Element Browser",
					"select" => "Selectorbox, select global",
					"select_cur" => "Selectorbox, select from current page",
					"select_root" => "Selectorbox, select from root page",
					"select_storage" => "Selectorbox, select from storage page",
				);
				$fDetails.="<BR>Type:<BR>".$this->renderSelectBox($prefix."[conf_rel_type]",$fConf["conf_rel_type"]?$fConf["conf_rel_type"]:"group",$optValues)."<BR>";
				if (t3lib_div::intInRange($fConf["conf_relations"],1,1000)==1 && $fConf["conf_rel_type"]!="group")	{
					$fDetails.=$this->renderCheckBox($prefix."[conf_rel_dummyitem]",$fConf["conf_rel_dummyitem"])."Add a blank item to the selector<BR>";
				}

				$fDetails.=$this->renderStringBox($prefix."[conf_relations]",t3lib_div::intInRange($fConf["conf_relations"],1,1000),50)." Max number of relations<BR>";
				$fDetails.=$this->renderStringBox($prefix."[conf_relations_selsize]",t3lib_div::intInRange($fConf["conf_relations_selsize"],1,50),50)." Size of selector box<BR>";
				$fDetails.=$this->renderCheckBox($prefix."[conf_relations_mm]",$fConf["conf_relations_mm"])."True M-M relations (otherwise commalist of values)<BR>";


				if ($fConf["conf_rel_type"]!="group")	{
					$fDetails.="<BR>";
					$fDetails.=$this->renderCheckBox($prefix."[conf_wiz_addrec]",$fConf["conf_wiz_addrec"])."Add 'Add record' link<BR>";
					$fDetails.=$this->renderCheckBox($prefix."[conf_wiz_listrec]",$fConf["conf_wiz_listrec"])."Add 'List records' link<BR>";
					$fDetails.=$this->renderCheckBox($prefix."[conf_wiz_editrec]",$fConf["conf_wiz_editrec"])."Add 'Edit record' link<BR>";
					$fDetails.=$this->resImg("t_rel_wizards.png",'hspace=20','','<BR><BR>');
				}
			break;
			case "files":
				if ($fConf["conf_files_type"]=="images")	{
					$typeCfg.=$this->resImg("t_file_img.png",'','');
				} elseif ($fConf["conf_files_type"]=="webimages")	{
					$typeCfg.=$this->resImg("t_file_web.png",'','');
				} else {
					$typeCfg.=$this->resImg("t_file_all.png",'','');
				}

				$optValues = array(
					"images" => "Imagefiles",
					"webimages" => "Web-imagefiles (gif,jpg,png)",
					"all" => "All files, except php/php3 extensions",
				);
				$fDetails.="<BR>Extensions:<BR>".$this->renderSelectBox($prefix."[conf_files_type]",$fConf["conf_files_type"],$optValues)."<BR>";

				$fDetails.=$this->renderStringBox($prefix."[conf_files]",t3lib_div::intInRange($fConf["conf_files"],1,1000),50)." Max number of files<BR>";
				$fDetails.=$this->renderStringBox($prefix."[conf_max_filesize]",t3lib_div::intInRange($fConf["conf_max_filesize"],1,1000,500),50)." Max filesize allowed (kb)<BR>";
				$fDetails.=$this->renderStringBox($prefix."[conf_files_selsize]",t3lib_div::intInRange($fConf["conf_files_selsize"],1,50),50)." Size of selector box<BR>";
				$fDetails.=$this->resImg("t_file_size.png",'','','<BR><BR>');
//				$fDetails.=$this->renderCheckBox($prefix."[conf_files_mm]",$fConf["conf_files_mm"])."DB relations (very rare choice, normally the commalist is fine enough)<BR>";
				$fDetails.=$this->renderCheckBox($prefix."[conf_files_thumbs]",$fConf["conf_files_thumbs"])."Show thumbnails<BR>";
				$fDetails.=$this->resImg("t_file_thumb.png",'hspace=20','','<BR><BR>');
			break;
			case "integer":
				$typeCfg.=$this->resImg("t_integer.png",'','');
			break;
			case "check_4":
			case "check_10":
				if ((string)$fConf["type"]=="check_4")	{
					$typeCfg.=$this->resImg("t_check4.png",'','');
				} else {
					$typeCfg.=$this->resImg("t_check10.png",'','');
				}
				$nItems= t3lib_div::intInRange($fConf["conf_numberBoxes"],1,10,(string)$fConf["type"]=="check_4"?4:10);
				$fDetails.=$this->renderStringBox($prefix."[conf_numberBoxes]",$nItems,50)." Number of checkboxes<BR>";

				for($a=0;$a<$nItems;$a++)	{
					$fDetails.="<BR>Label ".($a+1).":<BR>".$this->renderStringBox_lang("conf_boxLabel_".$a,$prefix,$fConf);
				}
			break;
			case "date":
				$typeCfg.=$this->resImg("t_date.png",'','');
			break;
			case "datetime":
				$typeCfg.=$this->resImg("t_datetime.png",'','');
			break;
			case "link":
				$typeCfg.=$this->resImg("t_link.png",'','');
			break;
		}

		if ($fConf["type"])	$typeCfg.=$this->textSetup("",$fDetails);

		$content='<table border=0 cellpadding=0 cellspacing=0>
			<tr><td valign=top>'.$this->fw("Field name:").'</td><td valign=top>'.$this->fw($fieldName).'</td></tr>
			<tr><td valign=top>'.$this->fw("Field title:").'</td><td valign=top>'.$this->fw($fieldTitle).'</td></tr>
			<tr><td valign=top>'.$this->fw("Field type:").'</td><td valign=top>'.$this->fw($typeCfg).'</td></tr>
		</table>';
		return $content;
	}


	function currentFields($addFields,$fArr)	{
		if (is_array($fArr))	{
			foreach($fArr as $k=>$v)	{
				if ($v["type"] && trim($v["fieldname"]))	{
					$addFields[trim($v["fieldname"])]=$v["fieldname"].": ".$v["title"];
				}
			}
		}
		return $addFields;
	}
	function addOtherExtensionTables($optValues)	{
		if (is_array($this->wizArray["tables"]))	{
			foreach($this->wizArray["tables"] as $k=>$info)	{
				if (trim($info["tablename"]))	{
					$tableName = $this->returnName($this->extKey,"tables",trim($info["tablename"]));
					$optValues[$tableName]="Extension table: ".$info["title"]." (".$tableName.")";
				}
			}
		}
		return $optValues;
	}
	function cleanUpFieldName($str)	{
		$fieldName = ereg_replace("[^[:alnum:]_]","",strtolower($str));
		if (!$fieldName || t3lib_div::inList($this->reservedTypo3Fields.",".$this->mysql_reservedFields,$fieldName) || in_array($fieldName,$this->usedNames))	{
			$fieldName.=($fieldName?"_":"").t3lib_div::shortmd5(microtime());
		}
		$this->usedNames[]=$fieldName;
		return $fieldName;
	}
	function whatIsThis($str)	{
		return ' <a href="#" title="'.htmlspecialchars($str).'" style="cursor:help" onClick="alert('.$GLOBALS['LANG']->JScharCode($str).');return false;">(What is this?)</a>';
	}
	function renderStringBox_lang($fieldName,$ffPrefix,$piConf)	{
		$content = $this->renderStringBox($ffPrefix."[".$fieldName."]",$piConf[$fieldName])." [English]";
		if (count($this->selectedLanguages))	{
			$lines=array();
			foreach($this->selectedLanguages as $k=>$v) {
				$lines[]=$this->renderStringBox($ffPrefix."[".$fieldName."_".$k."]",$piConf[$fieldName."_".$k])." [".$v."]";
			}
			$content.=$this->textSetup("",implode("<BR>",$lines));
		}
		return $content;
	}

	function textSetup($header,$content)	{
		return ($header?"<strong>".$header."</strong><BR>":"")."<blockquote>".trim($content)."</blockquote>";
	}
	function resImg($name,$p='align="center"',$pre="<BR>",$post="<BR>")	{
		if ($this->dontPrintImages)	return "<BR>";
		$imgRel = $this->path_resources().$name;
		$imgInfo = @getimagesize(PATH_site.$imgRel);
		return $pre.'<img src="'.$this->siteBackPath.$imgRel.'" '.$imgInfo[3].($p?" ".$p:"").' vspace=5 border=1 style="border:solid 1px;">'.$post;
	}
	function resIcon($name,$p="")	{
		if ($this->dontPrintImages)	return "";
		$imgRel = $this->path_resources("icons/").$name;
		if (!@is_file(PATH_site.$imgRel))	return "";
		$imgInfo = @getimagesize(PATH_site.$imgRel);
		return '<img src="'.$this->siteBackPath.$imgRel.'" '.$imgInfo[3].($p?" ".$p:"").'>';
	}
	function path_resources($subdir="res/")	{
		return substr(t3lib_extMgm::extPath("kickstarter"),strlen(PATH_site)).$subdir;
	}
	function getOnChangeParts($prefix)	{
		$md5h=t3lib_div::shortMd5($this->piFieldName("wizArray_upd").$prefix);
		return array('<a name="'.$md5h.'"></a>',"setFormAnchorPoint('".$md5h."');");
	}
	function renderCheckBox($prefix,$value,$defVal=0)	{
		if (!isset($value))	$value=$defVal;
		$onCP = $this->getOnChangeParts($prefix);
		return $this->wopText($prefix).$onCP[0].'<input type="hidden" name="'.$this->piFieldName("wizArray_upd").$prefix.'" value="0"><input type="checkbox" name="'.$this->piFieldName("wizArray_upd").$prefix.'" value="1"'.($value?" CHECKED":"").' onClick="'.$onCP[1].'"'.$this->wop($prefix).'>';
	}
	function renderTextareaBox($prefix,$value)	{
		$onCP = $this->getOnChangeParts($prefix);
		return $this->wopText($prefix).$onCP[0].'<textarea name="'.$this->piFieldName("wizArray_upd").$prefix.'" style="width:600px;" rows="10" wrap="OFF" onChange="'.$onCP[1].'" title="'.htmlspecialchars("WOP:".$prefix).'"'.$this->wop($prefix).'>'.t3lib_div::formatForTextarea($value).'</textarea>';
	}
	function renderStringBox($prefix,$value,$width=200)	{
		$onCP = $this->getOnChangeParts($prefix);
		return $this->wopText($prefix).$onCP[0].'<input type="text" name="'.$this->piFieldName("wizArray_upd").$prefix.'" value="'.htmlspecialchars($value).'" style="width:'.$width.'px;" onChange="'.$onCP[1].'"'.$this->wop($prefix).'>';
	}
	function renderRadioBox($prefix,$value,$thisValue)	{
		$onCP = $this->getOnChangeParts($prefix);
		return $this->wopText($prefix).$onCP[0].'<input type="radio" name="'.$this->piFieldName("wizArray_upd").$prefix.'" value="'.$thisValue.'"'.(!strcmp($value,$thisValue)?" CHECKED":"").' onClick="'.$onCP[1].'"'.$this->wop($prefix).'>';
	}
	function renderSelectBox($prefix,$value,$optValues)	{
		$onCP = $this->getOnChangeParts($prefix);
		$opt=array();
		$isSelFlag=0;
		foreach($optValues as $k=>$v)	{
			$sel = (!strcmp($k,$value)?" SELECTED":"");
			if ($sel)	$isSelFlag++;
			$opt[]='<option value="'.htmlspecialchars($k).'"'.$sel.'>'.htmlspecialchars($v).'</option>';
		}
		if (!$isSelFlag && strcmp("",$value))	$opt[]='<option value="'.$value.'" SELECTED>'.htmlspecialchars("CURRENT VALUE '".$value."' DID NOT EXIST AMONG THE OPTIONS").'</option>';
		return $this->wopText($prefix).$onCP[0].'<select name="'.$this->piFieldName("wizArray_upd").$prefix.'" onChange="'.$onCP[1].'"'.$this->wop($prefix).'>'.implode("",$opt).'</select>';
	}
	function wop($prefix)	{
		return ' title="'.htmlspecialchars("WOP: ".$prefix).'"';
	}
	function wopText($prefix)	{
		return $this->printWOP?'<font face="verdana,arial,sans-serif" size=1 color=#999999>'.htmlspecialchars($prefix).':</font><BR>':'';
	}
	function catHeaderLines($lines,$k,$v,$altHeader="",$index="")	{
					$lines[]='<tr'.$this->bgCol(1).'><td><strong>'.$this->fw($v[0]).'</strong></td></tr>';
					$lines[]='<tr'.$this->bgCol(2).'><td>'.$this->fw($v[1]).'</td></tr>';
					$lines[]='<tr><td></td></tr>';
		return $lines;
	}
	function linkCurrentItems($cat)	{
		$items = $this->wizArray[$cat];
		$lines=array();
		$c=0;
		if (is_array($items))	{
			foreach($items as $k=>$conf)	{
				$lines[]='<strong>'.$this->linkStr($conf["title"]?$conf["title"]:"<em>Item ".$k."</em>",$cat,'edit:'.$k).'</strong>';
				$c=$k;
			}
		}
		if (!t3lib_div::inList("save,ts,TSconfig,languages",$cat) || !count($lines))	{
			$c++;
			if (count($lines))	$lines[]='';
			$lines[]=$this->linkStr('Add new item',$cat,'edit:'.$c);
		}
		return $this->fw(implode("<BR>",$lines));
	}
	function linkStr($str,$wizSubCmd,$wizAction)	{
		return '<a href="#" onClick="
			document.'.$this->varPrefix.'_wizard[\''.$this->piFieldName("wizSubCmd").'\'].value=\''.$wizSubCmd.'\';
			document.'.$this->varPrefix.'_wizard[\''.$this->piFieldName("wizAction").'\'].value=\''.$wizAction.'\';
			document.'.$this->varPrefix.'_wizard.submit();
			return false;">'.$str.'</a>';
	}
	function bgCol($n,$mod=0)	{
		$color = $this->color[$n-1];
		if ($mod)	$color = t3lib_div::modifyHTMLcolor($color,$mod,$mod,$mod);
		return ' bgColor="'.$color.'"';
	}
	function regNewEntry($k,$index)	{
		if (!is_array($this->wizArray[$k][$index]))	{
			$this->wizArray[$k][$index]=array();
		}
	}
	function bwWithFlag($str,$flag)	{
		if ($flag)	$str = '<strong>'.$str.'</strong>';
		return $str;
	}
	/**
	 * Encodes extension upload array
	 */
	function makeUploadDataFromArray($uploadArray)	{
		if (is_array($uploadArray))	{
			$serialized = serialize($uploadArray);
			$md5 = md5($serialized);

			$content=$md5.":";
/*			if ($this->gzcompress)	{
				$content.="gzcompress:";
				$content.=gzcompress($serialized);
			} else {
	*/			$content.=":";
				$content.=$serialized;
//			}
		}
		return $content;
	}
	/**
	 * Make upload array out of extension
	 */
	function makeUploadArray($extKey,$files)	{
		$uploadArray=array();
		$uploadArray["extKey"]=$extKey;
		$uploadArray["EM_CONF"]=Array(
			"title" => "[No title]",
			"description" => "[Enter description of extension]",
			"category" => "example",
			"author" => $this->userfield("name"),
			"author_email" => $this->userfield("email"),

		);

		$uploadArray["EM_CONF"] = array_merge($uploadArray["EM_CONF"],$this->makeEMCONFpreset(""));

		if (is_array($this->_addArray))	{
			$uploadArray["EM_CONF"] = array_merge($uploadArray["EM_CONF"],$this->_addArray);
		}
		$uploadArray["misc"]["codelines"]=0;
		$uploadArray["misc"]["codebytes"]=0;
		$uploadArray["techInfo"] = "";

		$uploadArray["FILES"] = $files;
		return $uploadArray;
	}

	/**
	 * Getting link to this page + extra parameters, we have specified
	 *
	 * @param	array		Additional parameters specified.
	 * @return	string		The URL
	 */
	function linkThisCmd($uPA=array())	{
	  $url = t3lib_div::linkThisScript($uPA);
	  return $url;
	}

	/**
	 * Font wrap function; Wrapping input string in a <span> tag with font family and font size set
	 *
	 * @param	string		Input value
	 * @return	string		Wrapped input value.
	 */
	function fw($str)	{
		return '<span style="font-family:verdana,arial,sans-serif; font-size:10px;">'.$str.'</span>';
	}


	function piFieldName($key)	{
		return $this->varPrefix."[".$key."]";
	}
	function cmdHiddenField()	{
		return '<input type="hidden"  name="'.$this->piFieldName("cmd").'" value="'.htmlspecialchars($this->currentCMD).'">';
	}

	function preWrap($str)	{
		$str = str_replace(chr(9),"&nbsp;&nbsp;&nbsp;&nbsp;",htmlspecialchars($str));
		$str = '<pre>'.$str.'</pre>';
		return $str;
	}
}

// Include extension?
if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/kickstarter/modfunc1/class.tx_kickstarter_wizard.php"]) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/kickstarter/modfunc1/class.tx_kickstarter_wizard.php"]);
}

?>