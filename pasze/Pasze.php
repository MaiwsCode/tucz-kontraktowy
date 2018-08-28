<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczekontraktowe_pasze_Pasze  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'pasze';
 
    }
    function fields() { // - here you choose the fields to add to the record browser


        
        $id_tuczu = new RBO_Field_Select(_M("Tucz"));
        $id_tuczu->from('kontrakty')->fields('farmer' ,'data_start');

        //nazwa paszy
        $feed = new RBO_Field_CommonData(_M('feed type'), 'pasze');
        $feed->set_visible();

        //cena paszy pod dany tucz
        $price_feed = new RBO_Field_Currency(_M("Price feed"));
        $price_feed->set_required()->set_visible();

        return array($id_tuczu, $feed,$price_feed); // - remember to return all defined fields
 
 
    }
    
}

?>