<?php

class tx_kickstarter_sectionbase {

  /* instance of the main Kickstarter Wizard class (class.tx_kickstarter_wizard.php) */
  var $wizard;
  
  /* instance of the Kickstarter Compilefiles class (class.tx_kickstarter_compilefiles.php) */
  var $compilefiles;
  
  /* Unique ID of this section (used in forms and data processing) */
  var $sectionID = 'uniqueID';
  
  /* renders the wizard for this section */
  function render_wizard() {
  }
  
  /* renders the code for this section */
  function render_extPart() {
  }
  
  function &process_hook($hookName, &$data) {
    if(is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter'][$this->sectionID][$hookName])) {
      foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter'][$this->sectionID][$hookName] as $_funcRef) {
	$data =& t3lib_div::callUserFunction($_funcRef, $data, $this);
      }
    }
    return $data;
  }
  
}
?>