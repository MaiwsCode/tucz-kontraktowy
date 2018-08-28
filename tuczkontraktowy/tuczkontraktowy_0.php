<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class tuczekontraktowe_tuczkontraktowy extends Module { 


public function body(){
	
    Base_ActionBarCommon::add(
        'add',
        __('Nowy kontrakt'), 
        '',
        null,
        0
    );
    Base_ActionBarCommon::add(
        'add',
        __('Przeglądaj kontrakty'), 
        '',
        null,
        0
    );


    // dodaje pasze 
    function add_feed($record){
        //dodaj pasze
        //pasze -> new record pasza pod kontrakt

        //wylicz cene paszy function()
        //wez pasze pod dany rekord 
        // oblicz wg wzoru
        //$record->price_feed = $X; 
        //$record->save();
    }


}   
public function settings(){

}    
}
