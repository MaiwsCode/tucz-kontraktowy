<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class tuczekontraktowe_paszeCommon extends ModuleCommon {


    public static function menu() {
		return array(__('Tucze kontraktowe') => array('__submenu__' => 1, __('Pasze') => array(
	    	'__icon__'=>'pasza.png','__icon_small__'=>'pasza.png'
			)));
	}
	public static function getPigAmount() {


	}

}
