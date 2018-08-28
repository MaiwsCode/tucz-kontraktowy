<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczekontraktowe_tuczkontraktowy_Pasza_przewazenia  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_faktury_wet';
 
    }
    function fields() { // - here you choose the fields to add to the record browser


        // id kontraktu 
        $contract_id = new RBO_Field_Select(_M("contract"));
        $contract_id->from('kontrakty')->fields('farmer' ,'data_start');

        //data wystawienia
        $date = new RBO_Field_Date(_M("Date release"));
        $date->set_required()->set_visible();

        //waga pusto 
        $empty = new RBO_Field_Integer(_M("value netto"));
        $empty->set_required()->set_visible();

        //waga pelno 
        $full = new RBO_Field_Integer(_M("value netto"));
        $full->set_required()->set_visible();




        return array($contract_id,$date,$empty,$full); // - remember to return all defined fields
 
 
    }
    
}

?>