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

	/* reference to wizard-data in main wizard */
	var $catData;

	function tk_kickstarter_catbase(&$catData) {
		$this->catData =& $catData;
	}

	function render_wizard() {
		/* Hier auf $this->catData zugreifen um den aktuellen Status und die aktuellen Werte auszulesen */
		/* Da sollte auch alles wieder drin landen (kümmert sich der Wizard drum)! */
	}


	function render_extPart() {
	}

	/*.... alle die Standard-Funktionen, die überall gebraucht werden */


}

?>