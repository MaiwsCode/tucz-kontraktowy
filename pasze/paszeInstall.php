<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class tuczekontraktowe_paszeInstall extends ModuleInstall {

    public function install() {
        $ret = true;
        Base_ThemeCommon::install_default_theme ('tuczekontraktowe/pasze');
        Utils_CommonDataCommon::new_array("pasze", array('finisher' => 'finisher' , 'starter' => 'starter'));

        $table = new tuczekontraktowe_pasze_Pasze();
        $success = $table->install();
        $table->add_default_access();
        return $ret;
    }

    public function uninstall() {
        $table = new tuczekontraktowe_pasze_Pasze();
        $success = $table->uninstall();
        $ret = true;
        Utils_CommonDataCommon::remove("pasze");
        return $ret; 
    }

    public function requires($v) {

        return array(); 
    }
    public function info() { // Returns basic information about the module which will be available in the epesi Main Setup
		return array (
				'Author' => 'Mateusz Kostrzewski',
				'License' => 'MIT 1.0',
				'Description' => '' 
		);
	}
    public function version() {

        return array('1.0'); 
    }
    public function simple_setup() { // Indicates if this module should be visible on the module list in Main Setup's simple view
		return array (
				'package' => __ ( 'Pasze' ),
				'version' => '0.1' 
		); // - now the module will be visible as "HelloWorld" in simple_view
	}

}

?>