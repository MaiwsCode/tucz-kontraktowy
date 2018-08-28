<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class tuczekontraktowe_tuczkontraktowyCommon extends ModuleCommon {


	/*
	 $contract_id->from('kontrakty')->fields('farmer' ,'data_start'); 
	 mysallem zeby to zrobic tak ze w przy przegladaniu kontraktow kazdy by mial kilka opcji 
		 dodawanie pasz 
		 faktur 
		 przewazanie
	no i przechodząc przez przycisk contract_id ustawialaby się wartosc domyślna żeby nie wybierać z żadnej listy 
	bo te kontrakty nie mają zadnej nazwy a nie wiem jak to tam wygląda ich ilość,
	 czestotliwość i mało intuicyjnie by się to wybierało
	
	
	*/

    public static function menu() {
		return array(__('Tucze kontraktowe') => array('__submenu__' => 1, __('Tucze kontraktowe') => array(
	    	'__icon__'=>'tucz.png','__icon_small__'=>'tucz.png'
			)));
	}
	public static function critDates() {
		$date_start = date("Y-m-d");
		$newdate = strtotime ( '-14 days' , strtotime ( $date_start ) ) ;
		$newdate = date ( 'Y-m-d' , $newdate );


    	return array('>=planed_purchase_date' => $newdate);
	}

	public static function critOnlyUbojnia() {
    	return array('group' => array('ubojnia') );
	}
	
	public static function critOnlyFarmers() {
    	return array('group' => array('farmer') );
    }
	
}
