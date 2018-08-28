<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class tuczekontraktowe_tuczkontraktowyInstall extends ModuleInstall {

    public function install() {
        $ret = true;
        Base_ThemeCommon::install_default_theme ('tuczekontraktowe_tuczkontraktowy');
        Base_ThemeCommon::install_default_theme($this->get_type());
        Base_LangCommon::install_translations($this->get_type());
        
        $table = new tuczekontraktowe_tuczkontraktowy_Kontrakty();
        $success = $table->install();
        $table->add_default_access();
        $table = new tuczekontraktowe_tuczkontraktowy_Upadki();
        $success = $table->install();
        $table->add_default_access();
        $table = new tuczekontraktowe_tuczkontraktowy_Kosztytransportu();
        $success = $table->install();
        $table->add_default_access();
        $table = new tuczekontraktowe_tuczkontraktowy_Wazenie();
        $success = $table->install();
        $table->add_default_access();
        $table = new tuczekontraktowe_tuczkontraktowy_Faktury_odbioru();
        $success = $table->install();
        $table->add_default_access();
        $table = new tuczekontraktowe_tuczkontraktowy_Faktury_weterynarz();
        $success = $table->install();
        $table->add_default_access();
        $table = new tuczekontraktowe_tuczkontraktowy_Faktury_zakup_paszy();
        $success = $table->install();
        $table->add_default_access();

        return $ret;
    }

    public function uninstall() {
        $table = new tuczekontraktowe_tuczkontraktowy_Kontrakty();
        $success = $table->uninstall();
        $table = new tuczekontraktowe_tuczkontraktowy_Upadki();
        $success = $table->uninstall();
        $table = new tuczekontraktowe_tuczkontraktowy_Kosztytransportu();
        $success = $table->uninstall();
        $table = new tuczekontraktowe_tuczkontraktowy_Wazenie();
        $success = $table->uninstall();
        $table = new tuczekontraktowe_tuczkontraktowy_Faktury_odbioru();
        $success = $table->uninstall();
        $ret = true;
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
				'package' => __ ( 'Tucze kontraktowe' ),
				'version' => '0.1' 
		); // - now the module will be visible as "HelloWorld" in simple_view
	}

}

?>