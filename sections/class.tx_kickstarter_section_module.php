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
 
class tx_kickstarter_section_module extends tx_kickstarter_sectionbase {
  var $sectionID = 'module';
	/**
	 * Renders the form in the kickstarter; this was add_cat_module()
	 */
	function render_wizard() {
		$lines=array();

		$action = explode(":",$this->wizard->modData["wizAction"]);
		if ($action[0]=="edit")	{
			$this->wizard->regNewEntry($this->sectionID,$action[1]);
			$lines = $this->wizard->catHeaderLines($lines,$this->sectionID,$this->wizard->options[$this->sectionID],"&nbsp;",$action[1]);
			$piConf = $this->wizard->wizArray[$this->sectionID][$action[1]];
			$ffPrefix='['.$this->sectionID.']['.$action[1].']';

				// Enter title of the module
			$subContent="<strong>Enter a title for the module:</strong><BR>".
				$this->wizard->renderStringBox_lang("title",$ffPrefix,$piConf);
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Description
			$subContent="<strong>Enter a description:</strong><BR>".
				$this->wizard->renderStringBox_lang("description",$ffPrefix,$piConf);
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Description
			$subContent="<strong>Enter a tab label (shorter description):</strong><BR>".
				$this->wizard->renderStringBox_lang("tablabel",$ffPrefix,$piConf);
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Position
			$optValues = array(
				"web" => "Sub in Web-module",
				"file" => "Sub in File-module",
				"user" => "Sub in User-module",
				"tools" => "Sub in Tools-module",
				"help" => "Sub in Help-module",
				"_MAIN" => "New main module"
			);
			$subContent="<strong>Sub- or main module?</strong><BR>".
				$this->wizard->renderSelectBox($ffPrefix."[position]",$piConf["position"],$optValues).
				$this->wizard->resImg("module.png");
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Sub-position
			$optValues = array(
				"0" => "Bottom (default)",
				"top" => "Top",
				"web_after_page" => "If in Web-module, after Web>Page",
				"web_before_info" => "If in Web-module, before Web>Info",
			);
			$subContent="<strong>Position in module menu?</strong><BR>".
				$this->wizard->renderSelectBox($ffPrefix."[subpos]",$piConf["subpos"],$optValues);
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Admin only
			$subContent = $this->wizard->renderCheckBox($ffPrefix."[admin_only]",$piConf["admin_only"])."Admin-only access!<BR>";
			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';

				// Options
			$subContent = $this->wizard->renderCheckBox($ffPrefix."[interface]",$piConf["interface"])."Allow other extensions to interface with function menu<BR>";
#			$lines[]='<tr'.$this->wizard->bgCol(3).'><td>'.$this->wizard->fw($subContent).'</td></tr>';
		}

		/* HOOK: Place a hook here, so additional output can be integrated */
		if(is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter']['add_cat_module'])) {
		  foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter']['add_cat_module'] as $_funcRef) {
		    $lines = t3lib_div::callUserFunction($_funcRef, $lines, $this);
		  }
		}

		$content = '<table border=0 cellpadding=2 cellspacing=2>'.implode("",$lines).'</table>';
		return $content;
	}








	/**
	 * Renders the extension PHP codee; this was 
	 */
	function render_extPart($k,$config,$extKey) {
		$WOP="[module][".$k."]";
		$mN = ($config["position"]!="_MAIN"?$config["position"]."_":"").$this->wizard->returnName($extKey,"module","M".$k);
		$cN = $this->wizard->returnName($extKey,"class","module".$k);
		$pathSuffix = "mod".$k."/";

			// Insert module:
		switch($config["subpos"])	{
			case "top":
				$subPos="top";
			break;
			case "web_after_page":
				$subPos="after:layout";
			break;
			case "web_before_info":
				$subPos="before:info";
			break;
		}
		$this->wizard->ext_tables[]=$this->wizard->sPS('
			'.$this->wizard->WOPcomment('WOP:'.$WOP).'
			if (TYPO3_MODE=="BE")	{
					'.$this->wizard->WOPcomment('1. and 2. parameter is WOP:'.$WOP.'[position] , 3. parameter is WOP:'.$WOP.'[subpos]').'
				t3lib_extMgm::addModule("'.
					($config["position"]!="_MAIN"?$config["position"]:$this->wizard->returnName($extKey,"module","M".$k)).
					'","'.
					($config["position"]!="_MAIN"?$this->wizard->returnName($extKey,"module","M".$k):"").
					'","'.
					$subPos.
					'",t3lib_extMgm::extPath($_EXTKEY)."'.$pathSuffix.'");
			}
		');

			// Make conf.php file:
		$content = $this->wizard->sPS('
				// DO NOT REMOVE OR CHANGE THESE 3 LINES:
			define("TYPO3_MOD_PATH", "ext/'.$extKey.'/'.$pathSuffix.'");
			$BACK_PATH="../../../";
			$MCONF["name"]="'.$mN.'";

				'.$this->wizard->WOPcomment('WOP:'.$WOP.'[admin_only]: If the flag was set the value is "admin", otherwise "user,group"').'
			$MCONF["access"]="'.($config["admin_only"]?"admin":"user,group").'";
			$MCONF["script"]="index.php";

			$MLANG["default"]["tabs_images"]["tab"] = "moduleicon.gif";
			$MLANG["default"]["ll_ref"]="LLL:EXT:'.$extKey.'/'.$pathSuffix.'locallang_mod.php";
		');
		$this->wizard->EM_CONF_presets["module"][]=ereg_replace("\/$","",$pathSuffix);


		$ll=array();
		$this->wizard->addLocalConf($ll,$config,"title","module",$k,1,0,"mlang_tabs_tab");
		$this->wizard->addLocalConf($ll,$config,"description","module",$k,1,0,"mlang_labels_tabdescr");
		$this->wizard->addLocalConf($ll,$config,"tablabel","module",$k,1,0,"mlang_labels_tablabel");
		$this->wizard->addLocalLangFile($ll,$pathSuffix."locallang_mod.php",'Language labels for module "'.$mN.'" - header, description');

//			$MLANG["default"]["tabs"]["tab"] = "'.addslashes($config["title"]).'";	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[title]').'
//			$MLANG["default"]["labels"]["tabdescr"] = "'.addslashes($config["description"]).'";	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[description]').'
//			$MLANG["default"]["labels"]["tablabel"] = "'.addslashes($config["tablabel"]).'";	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[tablabel]').'

/*
		if (count($this->wizard->selectedLanguages))	{
			reset($this->wizard->selectedLanguages);
			while(list($lk,$lv)=each($this->wizard->selectedLanguages))	{
				if ($lv)	{
					$content.= $this->wizard->sPS('
							// '.$this->wizard->languages[$lk].' language:
						$MLANG["'.$lk.'"]["tabs"]["tab"] = "'.addslashes($config["title_".$lk]).'";	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[title_'.$lk.']').'
						$MLANG["'.$lk.'"]["labels"]["tabdescr"] = "'.addslashes($config["description_".$lk]).'";	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[description_'.$lk.']').'
						$MLANG["'.$lk.'"]["labels"]["tablabel"] = "'.addslashes($config["tablabel_".$lk]).'";	'.$this->wizard->WOPcomment('WOP:'.$WOP.'[tablabel_'.$lk.']').'
					');
				}
			}
		}
*/
		$content=$this->wizard->wrapBody('
			<?php
			',$content,'
			?>
		',0);

		$this->wizard->addFileToFileArray($pathSuffix."conf.php",trim($content));

			// Add title to local lang file
		$ll=array();
		$this->wizard->addLocalConf($ll,$config,"title","module",$k,1);
		$this->wizard->addLocalConf($ll,array("function1"=>"Function #1"),"function1","module",$k,1,1);
		$this->wizard->addLocalConf($ll,array("function2"=>"Function #2"),"function2","module",$k,1,1);
		$this->wizard->addLocalConf($ll,array("function3"=>"Function #3"),"function3","module",$k,1,1);
		$this->wizard->addLocalLangFile($ll,$pathSuffix."locallang.php",'Language labels for module "'.$mN.'"');

			// Add clear.gif
		$this->wizard->addFileToFileArray($pathSuffix."clear.gif",t3lib_div::getUrl(t3lib_extMgm::extPath("kickstarter")."res/clear.gif"));

			// Add clear.gif
		$this->wizard->addFileToFileArray($pathSuffix."moduleicon.gif",t3lib_div::getUrl(t3lib_extMgm::extPath("kickstarter")."res/notfound_module.gif"));


			// Make module index.php file:
		$indexContent = $this->wizard->sPS('
				// DEFAULT initialization of a module [BEGIN]
			unset($MCONF);
			require ("conf.php");
			require ($BACK_PATH."init.php");
			require ($BACK_PATH."template.php");
			$LANG->includeLLFile("EXT:'.$extKey.'/'.$pathSuffix.'locallang.php");
			#include ("locallang.php");
			require_once (PATH_t3lib."class.t3lib_scbase.php");
			$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
				// DEFAULT initialization of a module [END]
		');

		$indexContent.= $this->wizard->sPS('
			class '.$cN.' extends t3lib_SCbase {
				var $pageinfo;

				/**
				 *
				 */
				function init()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					parent::init();

					/*
					if (t3lib_div::_GP("clear_all_cache"))	{
						$this->wizard->include_once[]=PATH_t3lib."class.t3lib_tcemain.php";
					}
					*/
				}

				/**
				 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
				 */
				function menuConfig()	{
					global $LANG;
					$this->wizard->MOD_MENU = Array (
						"function" => Array (
							"1" => $LANG->getLL("function1"),
							"2" => $LANG->getLL("function2"),
							"3" => $LANG->getLL("function3"),
						)
					);
					parent::menuConfig();
				}

					// If you chose "web" as main module, you will need to consider the $this->wizard->id parameter which will contain the uid-number of the page clicked in the page tree
				/**
				 * Main function of the module. Write the content to $this->wizard->content
				 */
				function main()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					// Access check!
					// The page will show only if there is a valid page and if this page may be viewed by the user
					$this->wizard->pageinfo = t3lib_BEfunc::readPageAccess($this->wizard->id,$this->wizard->perms_clause);
					$access = is_array($this->wizard->pageinfo) ? 1 : 0;

					if (($this->wizard->id && $access) || ($BE_USER->user["admin"] && !$this->wizard->id))	{

							// Draw the header.
						$this->wizard->doc = t3lib_div::makeInstance("mediumDoc");
						$this->wizard->doc->backPath = $BACK_PATH;
						$this->wizard->doc->form=\'<form action="" method="POST">\';

							// JavaScript
						$this->wizard->doc->JScode = \'
							<script language="javascript" type="text/javascript">
								script_ended = 0;
								function jumpToUrl(URL)	{
									document.location = URL;
								}
							</script>
						\';
						$this->wizard->doc->postCode=\'
							<script language="javascript" type="text/javascript">
								script_ended = 1;
								if (top.fsMod) top.fsMod.recentIds["web"] = \'.intval($this->wizard->id).\';
							</script>
						\';

						$headerSection = $this->wizard->doc->getHeader("pages",$this->wizard->pageinfo,$this->wizard->pageinfo["_thePath"])."<br>".$LANG->sL("LLL:EXT:lang/locallang_core.php:labels.path").": ".t3lib_div::fixed_lgd_pre($this->wizard->pageinfo["_thePath"],50);

						$this->wizard->content.=$this->wizard->doc->startPage($LANG->getLL("title"));
						$this->wizard->content.=$this->wizard->doc->header($LANG->getLL("title"));
						$this->wizard->content.=$this->wizard->doc->spacer(5);
						$this->wizard->content.=$this->wizard->doc->section("",$this->wizard->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->wizard->id,"SET[function]",$this->wizard->MOD_SETTINGS["function"],$this->wizard->MOD_MENU["function"])));
						$this->wizard->content.=$this->wizard->doc->divider(5);


						// Render content:
						$this->wizard->moduleContent();


						// ShortCut
						if ($BE_USER->mayMakeShortcut())	{
							$this->wizard->content.=$this->wizard->doc->spacer(20).$this->wizard->doc->section("",$this->wizard->doc->makeShortcutIcon("id",implode(",",array_keys($this->wizard->MOD_MENU)),$this->wizard->MCONF["name"]));
						}

						$this->wizard->content.=$this->wizard->doc->spacer(10);
					} else {
							// If no access or if ID == zero

						$this->wizard->doc = t3lib_div::makeInstance("mediumDoc");
						$this->wizard->doc->backPath = $BACK_PATH;

						$this->wizard->content.=$this->wizard->doc->startPage($LANG->getLL("title"));
						$this->wizard->content.=$this->wizard->doc->header($LANG->getLL("title"));
						$this->wizard->content.=$this->wizard->doc->spacer(5);
						$this->wizard->content.=$this->wizard->doc->spacer(10);
					}
				}

				/**
				 * Prints out the module HTML
				 */
				function printContent()	{

					$this->wizard->content.=$this->wizard->doc->endPage();
					echo $this->wizard->content;
				}

				/**
				 * Generates the module content
				 */
				function moduleContent()	{
					switch((string)$this->wizard->MOD_SETTINGS["function"])	{
						case 1:
							$content="<div align=center><strong>Hello World!</strong></div><BR>
								The \'Kickstarter\' has made this module automatically, it contains a default framework for a backend module but apart from it does nothing useful until you open the script \'".substr(t3lib_extMgm::extPath("'.$extKey.'"),strlen(PATH_site))."'.$pathSuffix.'index.php\' and edit it!
								<HR>
								<BR>This is the GET/POST vars sent to the script:<BR>".
								"GET:".t3lib_div::view_array($_GET)."<BR>".
								"POST:".t3lib_div::view_array($_POST)."<BR>".
								"";
							$this->wizard->content.=$this->wizard->doc->section("Message #1:",$content,0,1);
						break;
						case 2:
							$content="<div align=center><strong>Menu item #2...</strong></div>";
							$this->wizard->content.=$this->wizard->doc->section("Message #2:",$content,0,1);
						break;
						case 3:
							$content="<div align=center><strong>Menu item #3...</strong></div>";
							$this->wizard->content.=$this->wizard->doc->section("Message #3:",$content,0,1);
						break;
					}
				}
			}
		');

		$SOBE_extras["firstLevel"]=0;
		$SOBE_extras["include"]=1;
		$this->wizard->addFileToFileArray($pathSuffix."index.php",$this->wizard->PHPclassFile($extKey,$pathSuffix."index.php",$indexContent,"Module '".$config["title"]."' for the '".$extKey."' extension.",$cN,$SOBE_extras));

	}

}


// Include ux_class extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kickstarter/sections/class.tx_kickstarter_section_module.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kickstarter/sections/class.tx_kickstarter_section_module.php']);
}


?>