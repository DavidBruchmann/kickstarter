<?php

class tx_kickstarter_sectionbase {

  /* instance of the main Kickstarter Wizard class (class.tx_kickstarter_wizard.php) */
  var $wizard;
  
  /* instance of the Kickstarter Compilefiles class (class.tx_kickstarter_compilefiles.php) */
  var $compilefiles;
  
  /* name of the category, as shown in menu */
  var $catName = 'Category-Name';
  
  /* description of category */
  var $catDesc = 'Detailed description of this category';
  
  /* array of files which are created by this wizard. TODO: what if multiple categories work on the same files?*/
  var $catFiles = array();
  
  function tx_kickstarter_sectionbase() {
    $this->catName = '';
    $this->catDesc = '';
  }
  
  function render_wizard() {
    /* Just an example */
    $lines =& $this->process_hook('render_wizard', $lines);
  }
  
  
  function render_extPart() {
    /* Just an example */
    $lines =& $this->process_hook('render_extPart', $lines);
  }
  
  function &process_hook($hookName, &$lines) {
    if(is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter'][$this->catName][$hookName])) {
      foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter'][$this->catName][$hookName] as $_funcRef) {
	$lines =& t3lib_div::callUserFunction($_funcRef, $lines, $this);
      }
    }
    return $lines;
  }
  
}
?>